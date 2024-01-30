<?php

namespace App\Http\Controllers;

use App\Http\Requests\Demand\DemandRequest;
use App\Http\Resources\Demand\RegisterDemandRessource;
use App\Http\Resources\Errors\GeneralErrorsResources;
use App\Jobs\CloneRepositoryJob;
use App\Jobs\DeleteRepositoryJob;
use App\Jobs\PhpstanJob;
use App\Models\Demand;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemandController extends Controller
{
    /**
     * @return JsonResponse
     * Display a listing of the resource.
     */
    public function getRepositories(): JsonResponse
    {
        $repositories = Demand::where('user_id', auth('sanctum')->user()->id)
            ->get();
        return response()->json($repositories);
    }

    /**
     * @param DemandRequest $request
     * @return RegisterDemandRessource|GeneralErrorsResources|bool
     * Show the form for creating a new resource.
     */
    public function create(DemandRequest $request): RegisterDemandRessource|bool|GeneralErrorsResources
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->user()->id;
        //$response = Http::get($data['url_repo']);

        //if ($response->successful()) {
            //if (!$demand = Demand::where('url', '=', $data['url'])->first()) {
                $demand = Demand::create($data);
                CloneRepositoryJob::dispatch($demand, $data['url']);
                PhpstanJob::dispatch($demand);
            //}
            //DeleteRepositoryJob::dispatch($demand->id);
            return new RegisterDemandRessource($demand);
        //}

        return false;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
