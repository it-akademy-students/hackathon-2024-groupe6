<?php

namespace App\Classes;

use App\Jobs\CloneRepositoryJob;
use App\Jobs\ComposerAuditJob;
use App\Jobs\DeleteRepositoryJob;
use App\Jobs\PhpSecurityCheckerJob;
use App\Jobs\PhpstanJob;
use App\Mail\AnalyzeFailedMail;
use App\Mail\AnalyzeFinishSuccessMail;
use App\Models\Repository;
use App\Models\TestRequest;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Throwable;

class HandleRunJobs
{
    public Repository $repository;

    public TestRequest $testRequest;

    public string $branch;

    public array $tests_array;

    public function __construct(Repository $repository, TestRequest $testRequest, array $tests_array)
    {
        $this->repository = $repository;
        $this->testRequest = $testRequest;
        $this->branch = $testRequest->branch;
        $this->tests_array = $tests_array;
    }

    /**
     * Handle tests to run
     */
    public function run(): void
    {
        if (is_null($this->repository->repo_path)) {
            Bus::batch([new CloneRepositoryJob($this->repository)])
                ->then(function (Batch $batch) {
                    $this->testsBatch();
                })
                ->catch(function (Batch $batch, Throwable $e) {
                    Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFailedMail());
                })
                ->dispatch();
        } else {
            $this->testsBatch();
        }
    }

    /**
     * run tests job batching
     *
     * @param  Batch  $batch
     */
    private function testsBatch(): void
    {
        $jobs_array = [];

        $this->repository = Repository::find($this->repository->id);

        if ($this->tests_array['phpstan']) {
            $jobs_array[] = new PhpstanJob($this->repository, $this->testRequest, $this->branch);
        }

        if ($this->tests_array['php_security_checker']) {
            $jobs_array[] = new PhpSecurityCheckerJob($this->repository, $this->testRequest, $this->branch);
        }

        if ($this->tests_array['composer_audit']) {
            $jobs_array[] = new ComposerAuditJob($this->repository, $this->testRequest, $this->branch);
        }

        $jobs_array[] = new DeleteRepositoryJob($this->repository->repo_path);

        Bus::batch($jobs_array)
            ->then(function (Batch $batch) {
                $this->thenBatch($batch);
            })
            ->catch(function (Batch $batch, Throwable $e) {
                Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFailedMail());
            })
            ->dispatch();
    }

    /**
     * Callback then Batch testsBatch(
     *
     * @return void)
     */
    private function thenBatch(Batch $batch): void
    {
        Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFinishSuccessMail());
        Repository::find($this->repository->id)
            ->update([
                'repo_path' => null,
                'branches' => null,
            ]);
    }
}
