<?php

namespace App\Jobs;

use App\Mail\AnalyzeBeginMail;
use App\Mail\AnalyzeFailedMail;
use App\Models\Demand;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Throwable;

class CloneRepositoryJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Demand $demand */
    public Demand $demand;

    /** @var string $repo_link */
    public string $repo_link;

    /**
     * Create a new job instance.
     */
    public function __construct(Demand $demand, string $repo_link)
    {
        $this->demand = $demand;
        $this->repo_link = $repo_link;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $process = Process::run('git clone ' . $this->repo_link . ' ' . storage_path('app/clones/') . $repo_name = Str::random(32));

        $this->demand->update(['url' => '/clones/' . $repo_name]);

        Mail::to(User::find($this->demand->user_id)->email)->send(new AnalyzeBeginMail());
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Mail::to(User::find($this->demand->user_id)->email)->send(new AnalyzeFailedMail($exception));
    }
}
