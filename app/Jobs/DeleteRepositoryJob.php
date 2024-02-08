<?php

namespace App\Jobs;

use App\Mail\AnalyzeFailedMail;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DeleteRepositoryJob implements ShouldBeEncrypted, ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $repo_path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $repo_path)
    {
        $this->repo_path = $repo_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Storage::deleteDirectory($this->repo_path);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception)
    {
        //Mail::to(User::find($this->demand->user_id)->email)->send(new AnalyzeFailedMail($exception));
    }
}
