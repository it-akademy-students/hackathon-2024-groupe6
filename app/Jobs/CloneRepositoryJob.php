<?php

namespace App\Jobs;

use App\Mail\AnalyzeBeginMail;
use App\Mail\AnalyzeFailedMail;
use App\Models\Repository;
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

    /** @var Repository $repository */
    public Repository $repository;

    /** @var string $repo_link */
    public string $repo_link;

    /**
     * Create a new job instance.
     */
    public function __construct(Repository $repository, string $repo_link)
    {
        $this->repository = $repository;
        $this->repo_link = $repo_link;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Process::run('git clone ' . $this->repo_link . ' ' . storage_path('/app/public/') . $this->repository->user_id . '/' . $repo_name = Str::random(32));

        $branches = $this->getBranches($repo_name);

        $this->repository->update([
            'repo_path' => '/storage/app/public/' . $this->repository->user_id . '/' . $repo_name,
            'branches' => $branches
        ]);


        Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeBeginMail());
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Mail::to(User::find($this->repository->user_id)->email)->send(new AnalyzeFailedMail($exception));
    }

    /**
     * Get the list of origin branches to array
     * @param string $repo_name
     * @return array
     */
    private function getBranches(string $repo_name): array
    {
        $process_branches = Process::run('cd ' . storage_path('app/public/') . $this->repository->user_id . '/' . $repo_name . ' && git branch -r');

        $branches_array = explode("\n", $process_branches->output());
        array_shift($branches_array);

        return $branches_array;
    }
}
