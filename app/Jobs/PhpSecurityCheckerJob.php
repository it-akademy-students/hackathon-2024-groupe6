<?php

namespace App\Jobs;

use App\Models\Repository;
use App\Models\TestRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\PhpSecurityCheckerResult;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use App\Services\HandleGit;

class PhpSecurityCheckerJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Repository $repository */
    public Repository $repository;

      /** @var TestRequest $testRequest */
      public TestRequest $testRequest;

      /** @var string $branch */
      public string $branch;

    /**
     * Create a new job instance.
     */
    public function __construct(Repository $repository, TestRequest $testRequest, string $branch)
    {
        $this->repository = $repository;
        $this->testRequest = $testRequest;
        $this->branch = $branch;
    }

     /**
     * Execute the job.
     */
    public function handle(): void
    {
        $name_rapport_file = now()->format('d-m-Y-H-i-s') . '-phpSecurityChecker' . $this->repository->name;
        $base_path_repository = base_path() . '/public' . Storage::url($this->repository->repo_path);

        PhpSecurityCheckerResult::create([
            'test_request_id' => $this->testRequest->id,
            'result_status_id' => 1,
            'path_result' => $phpsecuritychecker_result_path = storage_path('app/public/') . $this->repository->user_id . '/' . $name_rapport_file . '.json',
        ]);

        $handleGit = new HandleGit($this->repository);
        $handleGit->gitCheckout($this->testRequest->branch);

        $result = Process::run( base_path() . '/tools/local-php-security-checker  --path='. $this->repository->repo_path . '--format=json >'. $phpsecuritychecker_result_path);

        if($result){
            echo "ok";
        }else{
            echo "Erreur";
        }


    }
}

