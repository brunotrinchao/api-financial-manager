<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatusEnum;
use App\Filters\TransactionFilter;
use App\Http\Requests\Transaction\IndexTransactionRequest;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(IndexTransactionRequest $request, TransactionFilter $filters)
    {
        $perPage = $request->input('per_page', 10);

        $transactions = Transaction::with(['category', 'user'])->filter($filters)->paginate($perPage);

        $transactions->getCollection()->each(function ($transaction) {
            if ($transaction->source_type === 'account') {
                $transaction->load('sourceAccount');
            } elseif ($transaction->source_type === 'credit_card') {
                $transaction->load('sourceCreditCard');
            }
        });

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

            if ($transaction->source_type === 'account') {
                $transaction->load('sourceAccount');
            } elseif ($transaction->source_type === 'credit_card') {
                $transaction->load('sourceCreditCard');
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

        if ($transaction->source_type === 'account') {
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
        $transaction->update($request->validated());
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
