<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\StoreBankRequest;
use App\Http\Requests\Bank\UpdateBankRequest;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;


class BankController extends Controller
{
    public function index()
    {
        $creditCards = Bank::all();
        return (BankResource::collection($creditCards))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreBankRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos/banks', 'public');
        }

        $bank = Bank::create($data);

        return (BankResource::make($bank))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return (BankResource::make($bank))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateBankRequest $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $data = $request->validated();

        //TODO: Corrigir upload (Não esta recebendo os prarâmetros)

        if ($request->hasFile('logo')) {
            if ($bank->logo) {
                Storage::disk('public')->delete($bank->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos/banks', 'public');
        }

        $bank->update($data);

        return (BankResource::make($bank))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
