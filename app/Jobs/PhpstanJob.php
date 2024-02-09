<?php

namespace App\Jobs;

use App\Models\PhpstanResult;
use App\Models\Repository;
use App\Models\TestRequest;
use App\Services\HandleGit;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class PhpstanJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Repository $repository;

    public TestRequest $testRequest;

    public string $branch;

    /**
     * Create a new job instance.
     */
    public function __construct(Repository $repository, TestRequest $testRequest, string $branch)
    {
        $this->repository = $repository;
        $this->testRequest = $testRequest;
        $this->branch = $branch;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $name_rapport_file = now()->format('d-m-Y-H-i-s').'-phpstan'.$this->repository->name;
        $base_path_repository = base_path().'/public'.Storage::url($this->repository->repo_path);
        $phpstan_result_path = storage_path('app/public/').$this->repository->user_id.'/'.$name_rapport_file.'.json';

        $phpstan_result = PhpstanResult::create([
            'test_request_id' => $this->testRequest->id,
            'result_status_id' => 3,
            'path_result' => '/storage/'.$this->repository->user_id.'/'.$name_rapport_file.'.json',
        ]);

        $handleGit = new HandleGit($this->repository);
        $handleGit->gitCheckout($this->testRequest->branch);

        $process = Process::run(
            base_path()
            .'/vendor/bin/phpstan analyse --level max --error-format=prettyJson '
            .$base_path_repository
            .' > '.$phpstan_result_path
        );

    }
}
