<?php

namespace App\Http\Controllers;

use App\Classes\HandleRunJobs;
use App\Http\Requests\TestRequest\TestRequestRequest;
use App\Http\Resources\TestRequest\TestRequestResource;
use App\Models\Repository;
use App\Models\TestRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestRequestController extends Controller
{
    /**
     * Run the tests
     *
     * @param  Request  $request
     */
    public function runTests(TestRequestRequest $request): TestRequestResource
    {
        $request->validated();

        $repository = Repository::find($request->repository_id);
        $user_id = auth('sanctum')->user()->id;

        $test_request = TestRequest::create([
            'repo_id' => $repository->id,
            'user_id' => $user_id,
            'branch' => $request->branch,
        ]);

        $handle_run_jobs = new HandleRunJobs($repository, $test_request, $request->tests);
        $handle_run_jobs->run();

        return new TestRequestResource('Test(s) are running !, This may take a while', $test_request);
    }

    public function getTestsRequests(Request $request): JsonResponse
    {
        $user_id = auth('sanctum')->user()->id;
        $tests_requests = TestRequest::where('user_id', $user_id)->get();

        return response()->json($tests_requests);
    }
}
