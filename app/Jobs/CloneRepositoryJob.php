<?php

namespace App\Jobs;

use App\Mail\AnalyzeFailedMail;
use App\Models\Repository;
use App\Models\User;
use App\Services\HandleGit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneRepositoryJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Repository $repository */
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
        $handleGit->gitClone();
        $handleGit->setRandomRepoName();
        $handleGit->getBranches();
    }
}
