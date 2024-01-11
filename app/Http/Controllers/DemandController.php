<?php

namespace App\Http\Controllers;

use App\Http\Requests\Demand\DemandRequest;
use App\Http\Resources\Demand\RegisterDemandRessource;
use App\Jobs\CloneRepositoryJob;
use App\Jobs\DeleteRepositoryJob;
use App\Jobs\ProgpilotAnalyzeJob;
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
     * Show the form for creating a new resource.
     */
    public function create(DemandRequest $request): RegisterDemandRessource|bool
    {
        $data = $request->validated();
        $data['name_project'] = 'rrr';
        $data['user_id'] = 1;
        $data['status'] = 'processing';
        $data['repo_path'] = 'https://github.com/it-akademy-students/hackathon-2024-groupe6';
        //$response = Http::get($data['url']);

        //if ($response->successful()) {
            $demand = Demand::create($data);

            //CloneRepositoryJob::dispatch($demand, $data['url']);
            ProgpilotAnalyzeJob::dispatch(Demand::find(8));
            //DeleteRepositoryJob::dispatch($demand);

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
