<?php

namespace App\Jobs;

use App\Mail\AnalyzeBeginMail;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

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

        Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeBeginMail());
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFailedMail($exception));
    }
}
