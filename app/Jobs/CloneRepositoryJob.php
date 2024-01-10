<?php

namespace App\Jobs;

use App\Models\Demand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CloneRepositoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Demand $demand */
    public Demand $demand;

    /** @var string $repo_link */
    public  string $repo_link;

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

        $this->demand->update(['url' => Storage::disk('local')->url('clones/' . $repo_name)]);

        /*if ($result->successful()) {
            dd("Projet cloné avec succès !");
        } else {
            dd("Erreur lors du clonage du projet: " . $result->errorOutput());
        }//*/
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Send user notification of failure, etc...
    }
}
