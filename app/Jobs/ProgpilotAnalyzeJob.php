<?php

namespace App\Jobs;

use App\Models\Demand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProgpilotAnalyzeJob implements ShouldQueue
{
    /** @var Demand $demand */
    public Demand $demand;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $context = new \progpilot\Context;
        $analyzer = new \progpilot\Analyzer;

        $context->inputs->setFolder(storage_path("app/clones/".$this->demand->repo_path));

        try {
            $analyzer->run($context);
        } catch (\Exception $e) {
            echo "Exception : ".$e->getMessage()."\n";
        }

        $results = $context->outputs->getResults();

        dd($results);
    }
}
