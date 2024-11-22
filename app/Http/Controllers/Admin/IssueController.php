<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Issuer\StoreIssuerRequest;
use App\Http\Resources\IssuerResource;
use App\Models\Issuer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creditCards = Issuer::all();
        return (IssuerResource::collection($creditCards))->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssuerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos/issuer', 'public');
        }

        $bank = Issuer::create($data);

        return (IssuerResource::make($bank))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank = Issuer::findOrFail($id);
        return (IssuerResource::make($bank))->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bank = Issuer::findOrFail($id);

        $data = $request->validated();

        //TODO: Corrigir upload (Não esta recebendo os prarâmetros)

        if ($request->hasFile('logo')) {
            if ($bank->logo) {
                Storage::disk('public')->delete($bank->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos/issuer', 'public');
        }

        $bank->update($data);

        return (IssuerResource::make($bank))->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
