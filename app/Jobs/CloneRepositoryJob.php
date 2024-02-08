<?php

namespace App\Jobs;

use App\Models\Repository;
use App\Services\HandleGit;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneRepositoryJob implements ShouldBeEncrypted, ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Repository $repository;

    /**
     * Create a new job instance.
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $handleGit = new HandleGit($this->repository);
        $handleGit->setRandomRepoName();
        $handleGit->gitClone();
    }
}
