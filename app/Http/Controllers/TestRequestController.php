<?php

namespace App\Http\Controllers;

use App\Jobs\PhpstanJob;
use App\Models\Repository;
use App\Models\TestRequest;
use Illuminate\Http\Request;

class TestRequestController extends Controller
{
    /**
     * Run the tests
     * @param Request $request
     * @return void
     */
    public function runTests(Request $request)
    {
        $repository = Repository::find($request->repository_id);
        $user_id = auth('sanctum')->user()->id;

        $test_request = TestRequest::create([
            'repo_id' => $repository->id,
            'user_id' => $user_id,
            'status' => 'processing'
        ]);

        if ($request->phpstan) {
            PhpstanJob::dispatch($repository, $test_request, $request->branch);
        }
    }

}
