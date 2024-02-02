<?php

namespace App\Http\Controllers;


use App\Http\Resources\Error\ErrorRessource;
use App\Jobs\CloneRepositoryJob;
use App\Jobs\PhpSecurityCheckerJob;
use App\Jobs\ComposerAuditJob;
use App\Models\TestRequest;
use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestRequestController extends Controller
{ 
   
   
   public function runTests(Request $request)
   {
       $repository = Repository::find(13);
       $user_id = auth('sanctum')->user()->id;
   
       $test_request = TestRequest::create([
           'repo_id' => $repository->id,
           'user_id' => $user_id,
           'status' => 'processing'
       ]);
   
       if ($request->phpstan) {
           PhpstanJob::dispatch($repository);
       }

       if ($request->php_security_checker) {
         PhpSecurityCheckerJob::dispatch($repository);
        }

        if ($request->composer_audit) {
        ComposerAuditJob::dispatch($repository);
        }
   }
}
