<?php

namespace App\Jobs;

use App\Models\Demand;
use App\Models\PhpstanReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Process\Pipe;
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
        $name_rapport_file = now()->format('d-m-Y-H-i-s') . '-phpstan' . $this->demand->name;

        Process::run(base_path() . '/vendor/bin/phpstan analyse --level max --error-format=prettyJson ' . base_path(). '/public' . Storage::url($this->demand->repo_path) . ' > ' . storage_path('app/public/') . $this->demand->user_id . '/' . $name_rapport_file . '.json');

        PhpstanReport::create([
            'demand_id' => $this->demand->id,
            'url_report' => storage_path('app/public/') . $this->demand->user_id . '/' . $name_rapport_file . '.json'
        ]);
    }
}
