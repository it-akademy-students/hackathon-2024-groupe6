<?php

namespace App\Http\Controllers;

use App\Http\Requests\Demand\DemandRequest;
use App\Http\Resources\Demand\RegisterDemandRessource;
use App\Http\Resources\Error\ErrorRessource;
use App\Jobs\CloneRepositoryJob;
use App\Models\Demand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemandController extends Controller
{
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
                CloneRepositoryJob::dispatch($demand);
                return new RegisterDemandRessource($demand);
            } catch (\Exception $exception) {
                return new ErrorRessource($exception);
            }
        }

        $customError = new ErrorRessource();
        $customError -> setMessage("L'url saisi n'est pas valide. Veuillez recommencer votre demande.");
        return $customError;
    }

    public function getRepositories()
    {
        $demands = Demand::where('user_id', '=', auth('sanctum')->user()->id)->get();
        return response()->json($demands);
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
