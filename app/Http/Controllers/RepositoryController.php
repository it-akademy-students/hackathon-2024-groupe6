<?php

namespace App\Http\Controllers;

use App\Http\Requests\Repository\RepositoryRequest;
use App\Http\Resources\Error\ErrorRessource;
use App\Http\Resources\Repository\RepositoryResource;
use App\Jobs\CloneRepositoryJob;
use App\Models\Repository;
use App\Services\HandleGit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\RepoRepository;

class RepositoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(RepositoryRequest $request): RepositoryResource|ErrorRessource
    {
        $data = $request->validated();
        $data['user_id'] = auth('sanctum')->user()->id;
        $repository = Repository::create($data);

        CloneRepositoryJob::dispatchSync($repository);

        return new RepositoryResource($repository = Repository::find($repository->id));
    }

    public function getRepositories(RepoRepository $repository): JsonResponse
    {
        $repositories = $repository->getTestsByUserId()->get();

        return response()->json($repositories);
    }

    public function getRepository(Request $request)
    {
        $repository = Repository::find(intval($request->get('repo_id')));

        return response()->json($repository);
    }

    /**
     * Get the new branches on remote
     */
    public function gitFetchOrigin(Request $request): RepositoryResource
    {
        $repository = Repository::find(intval($request->get('repository_id')));

        $handleGit = new HandleGit($repository);
        $handleGit->gitFetchOrigin();
        $handleGit->getBranches();

        return new RepositoryResource($repository);
    }
}
