<?php

namespace App\Http\Controllers;

use App\Enums\SourceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Filters\TransactionFilter;
use App\Http\Requests\Transaction\IndexTransactionRequest;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\CreditCard;
use App\Models\Transaction;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index(IndexTransactionRequest $request, TransactionFilter $filters)
    {
        $perPage = $request->input('per_page', 10);

        $transactions = Transaction::with(['category', 'user'])->filter($filters)->paginate($perPage);

        $transactions->setCollection(
            $transactions->getCollection()->map(function ($transaction) {
                if ($transaction->source_type === 'account') {
                    $transaction->load('sourceAccount');
                } elseif ($transaction->source_type === 'credit_card') {
                    $transaction->load('sourceCreditCard');
                }
                return $transaction;
            })
        );

        return (TransactionResource::collection($transactions))->response()->setStatusCode(Response::HTTP_OK);
    }

    // Criar nova transação
    public function store(StoreTransactionRequest $request)
    {
        $transactions = $request->processedTransactions();

        $createdTransactions = [];

        foreach ($transactions as $key => $transactionData) {

            $transaction = Transaction::create($transactionData);

            $transaction->load(['category', 'user']);

            if ($transaction->source_type === SourceTypeEnum::ACCOUNT->value) {
                $transaction->load('sourceAccount');

                $account = Account::find($transaction->source_id);
                if ($account) {
                    $account->balance -= $transaction->amount;
                    $account->save();
                }

            } elseif ($transaction->source_type === SourceTypeEnum::CREDIT_CARD->value) {
                $transaction->load('sourceCreditCard');

                $creditCard = CreditCard::find($transaction->source_id);
                if ($creditCard) {
                    $creditCard->available_limit -= $transaction->amount;
                    $creditCard->save();
                }
            }

            $createdTransactions[] = $transaction;
        }

        return TransactionResource::collection($createdTransactions)
            ->additional(['message' => 'Lançamento(s) adicionado(s) com sucesso!'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // Exibir uma transação específica
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->load(['category', 'user']);

        if ($transaction->source_type === SourceTypeEnum::ACCOUNT->value) {
            $transaction->load('sourceAccount');
        } elseif ($transaction->source_type === 'credit_card') {
            $transaction->load('sourceCreditCard');
        }

        return (TransactionResource::make($transaction))->response()->setStatusCode(Response::HTTP_OK);
    }

    // Atualizar uma transação
    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->load(['sourceAccount', 'sourceCreditCard']);

        $originalSourceType = $transaction->source_type;
        $originalSourceId = $transaction->source_id;
        $originalAmount = $transaction->amount;

        $transaction->update($request->validated());

        // Restitui o valor para a fonte original (se necessário)
        if ($originalSourceType === SourceTypeEnum::ACCOUNT->value) {
            $originalAccount = Account::find($originalSourceId);
            if ($originalAccount) {
                $originalAccount->balance += $originalAmount;
                $originalAccount->save();
            }
        } elseif ($originalSourceType === SourceTypeEnum::CREDIT_CARD->value) {
            $originalCreditCard = CreditCard::find($originalSourceId);
            if ($originalCreditCard) {
                $originalCreditCard->available_limit += $originalAmount;
                $originalCreditCard->save();
            }
        }

        // Subtrai o valor da nova fonte (se necessário)
        if ($transaction->source_type === SourceTypeEnum::ACCOUNT->value && $transaction->status !== TransactionStatusEnum::CANCELED->value) {
            $newAccount = Account::find($transaction->source_id);
            if ($newAccount) {
                $newAccount->balance -= $transaction->amount;
                $newAccount->save();
            }
        } elseif ($transaction->source_type === SourceTypeEnum::CREDIT_CARD->value && $transaction->status !== TransactionStatusEnum::CANCELED->value) {
            $newCreditCard = CreditCard::find($transaction->source_id);
            if ($newCreditCard) {
                $newCreditCard->available_limit -= $transaction->amount;
                $newCreditCard->save();
            }
        }


        return (TransactionResource::make($transaction))->response()->setStatusCode(Response::HTTP_OK);
    }

    // Remover uma transação
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
