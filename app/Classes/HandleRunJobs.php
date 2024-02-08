<?php

namespace App\Classes;

use App\Jobs\ComposerAuditJob;
use App\Jobs\PhpSecurityCheckerJob;
use App\Jobs\PhpstanJob;
use App\Mail\AnalyzeFailedMail;
use App\Mail\AnalyzeFinishSuccessMail;
use App\Models\Repository;
use App\Models\TestRequest;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Throwable;

class HandleRunJobs
{
  /** @var Repository $repository */
  public Repository $repository;

  /** @var TestRequest $testRequest */
  public TestRequest $testRequest;

  /** @var string $branch */
  public string $branch;

  /** @var array $tests */
  public array $tests_array;

  public function __construct(Repository $repository, TestRequest $testRequest, array $tests_array)
  {
    $this->repository = $repository;
    $this->testRequest = $testRequest;
    $this->branch = $testRequest->branch;
    $this->tests_array = $tests_array;
  }

  public function run(): void
  {
    $jobs_array = [];

    if ($this->tests_array['phpstan'])
      $jobs_array[] = new PhpstanJob($this->repository, $this->testRequest, $this->branch);

    if ($this->tests_array['php_security_checker'])
      $jobs_array[] = new PhpSecurityCheckerJob($this->repository, $this->testRequest, $this->branch);

    if ($this->tests_array['composer_audit'])
      $jobs_array[] = new ComposerAuditJob($this->repository, $this->testRequest, $this->branch);

    $jobs_array[] = function () {
      Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFinishSuccessMail());
    };

    Bus::chain($jobs_array)
      ->catch(function (Throwable $e) {
        Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFailedMail());
      })
      ->dispatch();
  }
}
