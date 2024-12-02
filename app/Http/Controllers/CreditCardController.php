<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCard\IndexCreditCardRequest;
use App\Http\Requests\CreditCard\StoreCreditCardRequest;
use App\Http\Requests\CreditCard\UpdateCreditCardRequest;
use App\Http\Resources\CreditCardResource;
use App\Models\CreditCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CreditCardController extends Controller
{
    public function index(IndexCreditCardRequest $request)
    {
        $creditCards = CreditCard::all();
        $creditCards->load(['issuer']);
        return (CreditCardResource::collection($creditCards))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreCreditCardRequest $request): JsonResponse
    {
        $creditCard = CreditCard::create([
            'name' => $request->name,
            'limit' => $request->limit,
            'issuer_id' => $request->issuer_id,
            'user_id' => $request->user()->id,
        ]);

        $creditCard->load(['issuer']);

        return (CreditCardResource::make($creditCard))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $creditCard = CreditCard::with('bank')->findOrFail($id);
        return (CreditCardResource::make($creditCard))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateCreditCardRequest $request, $id)
    {
        $creditCard = CreditCard::findOrFail($id);

        $creditCard->update($request->validated());
        return (CreditCardResource::make($creditCard))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $creditCard = CreditCard::findOrFail($id);
        $creditCard->delete();
        return response()->json(['message' => 'Credit card deleted successfully']);
    }
}
