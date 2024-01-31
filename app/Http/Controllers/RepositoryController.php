<?php

namespace App\Http\Controllers;

use App\Http\Requests\Demand\DemandRequest;
use App\Http\Requests\Demand\RepositoryRequest;
use App\Http\Resources\Demand\RepositoryResource;
use App\Http\Resources\Error\ErrorRessource;
use App\Models\Repository;
use App\Models\TestRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param RepositoryRequest $request
     * @return RepositoryResource|ErrorRessource
     */
    public function store(RepositoryRequest $request): RepositoryResource|ErrorRessource
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->user()->id;

        $repositories = Repository::create($data);
        //CloneRepositoryJob::dispatch($demand, $data['url']);
        return new RepositoryResource($repositories);
    }

    /**
     * @return JsonResponse
     */
    public function getRepositories(): JsonResponse
    {
        $repositories = Repository::where('user_id', '=', auth('sanctum')->user()->id)->get();
        return response()->json($repositories);
    }
}
