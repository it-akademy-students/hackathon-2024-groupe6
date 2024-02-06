<?php

namespace App\Jobs;

use App\Models\PhpstanResult;
use App\Models\Repository;
use App\Models\TestRequest;
use App\Services\HandleGit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class PhpstanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Repository $repository */
    public Repository $repository;

    /** @var TestRequest $testRequest */
    public TestRequest $testRequest;

    /** @var string $branch */
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
        $name_rapport_file = now()->format('d-m-Y-H-i-s') . '-phpstan' . $this->repository->name;
        $base_path_repository = base_path() . '/public' . Storage::url($this->repository->repo_path);

        PhpstanResult::create([
            'test_request_id' => $this->testRequest->id,
            'result_status_id' => 1,
            'path_result' => $phpstan_result_path = storage_path('app/public/') . $this->repository->user_id . '/' . $name_rapport_file . '.json',
            'branch' => $this->branch
        ]);

        $handleGit = new HandleGit($this->repository);
        $handleGit->gitCheckout($this->branch);

        Process::run(
            base_path()
            . '/vendor/bin/phpstan analyse --level max --error-format=prettyJson '
            . $base_path_repository
            . ' > ' . $phpstan_result_path
        );
    }
}
