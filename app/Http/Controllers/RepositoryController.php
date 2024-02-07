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
    $repository = Repository::create($data);

    CloneRepositoryJob::dispatch($repository);

    return new RepositoryResource($repository = Repository::find($repository->id));
  }

  /**
   * @return JsonResponse
   */
  public function getRepositories(): JsonResponse
  {

    $repositories = Repository::where('user_id', '=', auth('sanctum')->user()->id)
      ->with(
        'testRequests',
        function ($query) {
          $query->with('phpstanResult');
        }
      )
      ->get();
    return response()->json($repositories);
  }

  /**
   * Get the new branches on remote
   * @param Request $request
   * @return RepositoryResource
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
