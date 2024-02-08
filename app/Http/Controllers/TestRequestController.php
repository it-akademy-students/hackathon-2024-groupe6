<?php

namespace App\Http\Controllers;

use App\Jobs\PhpstanJob;
use App\Models\Repository;
use App\Http\Resources\Error\ErrorRessource;
use App\Jobs\CloneRepositoryJob;
use App\Jobs\PhpSecurityCheckerJob;
use App\Jobs\ComposerAuditJob;
use App\Jobs\DeleteRepositoryJob;
use App\Models\TestRequest;
use Illuminate\Http\Request;
use App\Mail\AnalyzeBeginMail;
use App\Mail\AnalyzeFailedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Http\Requests\TestRequest\TestRequestRequest;


class TestRequestController extends Controller
{

    /**
     * Run the tests
     * @param Request $request
     * @return void
     */
    public function runTests(TestRequestRequest $request)
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

      if ($request->phpstan) {
          PhpstanJob::dispatch($repository, $test_request, $request->branch);
      }

      if ($request->composer_audit) {
        ComposerAuditJob::dispatch($repository, $test_request, $request->branch);
      }

      if ($request->php_security_checker) {
        PhpSecurityCheckerJob::dispatch($repository ,$test_request, $request->branch); 
      }
    
      Mail::to(User::find($repository->user_id)->email)->send(new AnalyzeBeginMail());      
    }

}
