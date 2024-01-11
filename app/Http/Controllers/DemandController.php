<?php

namespace App\Http\Controllers;

use App\Http\Requests\Demand\DemandRequest;
use App\Http\Resources\Demand\RegisterDemandRessource;
use App\Http\Resources\Error\ErrorRessource;
use App\Models\Demand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DemandRequest $request): RegisterDemandRessource|ErrorRessource
    {
        $data = $request->validated();
        $response = Http::get($data['url']);

        if ($response->successful()) {
            try {
                $demand = Demand::create($data);
                // TODO : CloneRepositoryJob::dispatch($demand);
                return new RegisterDemandRessource($demand);
            } catch (\Exception $exception) {
                return new ErrorRessource($exception);
            }
        }

        $customError = new ErrorRessource();
        $customError -> setMessage("L\'url saisi n\'est pas valide. Veuillez recommencer votre demande.");
        return $customError;
    }

    /**
     * Display the specified resource.
     */
    public function show(Demand $demand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demand $demand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demand $demand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $demand)
    {
        //
    }
}
