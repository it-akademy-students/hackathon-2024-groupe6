<?php

namespace App\Http\Controllers;

use App\Jobs\PhpstanJob;
use App\Models\Repository;
use App\Http\Resources\Error\ErrorRessource;
use App\Jobs\CloneRepositoryJob;
use App\Jobs\PhpSecurityCheckerJob;
use App\Jobs\ComposerAuditJob;
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

      if ($request->php_security_checker) {
        PhpSecurityCheckerJob::dispatch($repository);
      }

      if ($request->composer_audit) {
        ComposerAuditJob::dispatch($repository);
      }
    }

}
