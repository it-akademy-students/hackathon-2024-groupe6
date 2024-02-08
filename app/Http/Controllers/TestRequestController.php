<?php

namespace App\Http\Controllers;

use App\Classes\HandleRunJobs;
use App\Http\Resources\Success\GeneralSuccessResource;
use App\Models\Repository;
use App\Jobs\DeleteRepositoryJob;
use App\Models\TestRequest;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest\TestRequestRequest;


class TestRequestController extends Controller
{

    /**
     * Run the tests
     * @param Request $request
     * @return GeneralSuccessResource
     */
    public function runTests(TestRequestRequest $request): GeneralSuccessResource
    {
      $request->validated();
      $repository = Repository::find($request->repository_id);
      
      if (is_null($repository->repo_path)) {
        CloneRepositoryJob::dispatch($repository);
        return new RepositoryResource($repository);
    }
      $user_id = auth('sanctum')->user()->id;
      $test_request = TestRequest::create([
          'repo_id' => $repository->id,
          'user_id' => $user_id,
          'branch' => $request->branch,
      ]);

      $handle_run_jobs = new HandleRunJobs($repository, $test_request, $request->tests);
      $handle_run_jobs->run();

      return new GeneralSuccessResource('Test(s) are running !, This may take a while');
    }
}
