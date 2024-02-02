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

class PhpSecurityCheckerJob implements ShouldQueue, ShouldBeEncrypted
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
     * Start the PHP Security Checker Test 
     * @param Repository $repository
     * @return output
     */
    public function handle()
    {
        $result = Process::run( base_path() . '/tools/local-php-security-checker --format=json --path='. $this->repository->repo_path);
       
        if($result){
            echo "ok";
        }else{
            echo "Erreur";
        }
    }
}

