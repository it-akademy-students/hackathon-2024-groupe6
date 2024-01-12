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

class PhpstanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Demand $demand */
    public Demand $demand;

    /**
     * Create a new job instance.
     */
    public function __construct(Demand $demand)
    {
        $this->demand = $demand;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //dd(base_path() . '/vendor/bin/phpstan analyse public' . Storage::url($this->demand->repo_path));
        $ttt = Process::run(base_path() . '/vendor/bin/phpstan analyse public' . Storage::url($this->demand->repo_path));

        dd(
            $ttt->successful(),
            $ttt->failed(),
            $ttt->exitCode(),
            $ttt->output(),
            $ttt->errorOutput()
        );
    }
}
