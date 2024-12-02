<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class AccountController extends Controller
{
    public function index(): JsonResponse
    {
        $accounts = Account::all();
        return (AccountResource::collection($accounts))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreAccountRequest $request)
    {
        $account = Account::create([
            'name' => $request->name,
            'bank_id' => $request->bank_id,
            'balance' => $request->balance,
            'user_id' => $request->user()->id,
        ]);

        return (AccountResource::make($account))
            ->additional(['message' => 'Conta adicionada com sucesso!'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $account = Account::findOrFail($id);
        return (AccountResource::make($account))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $account->update($request->only(['name', 'bank_id', 'balance']));

        return (AccountResource::make($account))
            ->additional(['message' => 'Conta atualizada com sucesso!'])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(['message' => 'Account deleted successfully']);
    }
}
