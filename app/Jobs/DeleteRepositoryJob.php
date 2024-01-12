<?php

namespace App\Jobs;

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
use Illuminate\Support\Facades\Storage;
use Throwable;

class DeleteRepositoryJob implements ShouldQueue, ShouldBeEncrypted
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
            Storage::disk('local')->deleteDirectory('/clones/z9RsqY8swoyqjazpIk4raQetGZrLzevV');
            $this->demand->update(['url' => '']);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception)
    {
        Mail::to(User::find($this->demand->user_id)->email)->send(new AnalyzeFailedMail($exception));
    }
}
