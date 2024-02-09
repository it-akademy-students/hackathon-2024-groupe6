<?php

namespace App\Jobs;

use App\Models\ComposerAuditResult;
use App\Models\Repository;
use App\Models\TestRequest;
use App\Services\HandleGit;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ComposerAuditJob implements ShouldBeEncrypted, ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Repository $repository;

    public TestRequest $testRequest;

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

    public function handle()
    {
        $name_rapport_file = now()->format('d-m-Y-H-i-s').'-composerAudit'.$this->repository->name;
        $base_path_repository = base_path().'/public'.Storage::url($this->repository->repo_path);
        $composeraudit_result_path = storage_path('app/public/').$this->repository->user_id.'/'.$name_rapport_file.'.json';

        $composer_audit_result = ComposerAuditResult::create([
            'test_request_id' => $this->testRequest->id,
            'path_result' => '/storage/'.$this->repository->user_id.'/'.$name_rapport_file.'.json',
            'result_status_id' => 3, //Processing
        ]);

        $handleGit = new HandleGit($this->repository);
        $handleGit->gitCheckout($this->branch);

        // Définir les variables d'environnement HOME et COMPOSER_HOME
        $environment = [
            'HOME' => env('HOME'),
            'COMPOSER_HOME' => env('COMPOSER_HOME'),
        ];

        $repoPath = $this->repository->repo_path;
        $command = 'cd '.$base_path_repository.' && /opt/homebrew/bin/php /usr/local/bin/composer install && /opt/homebrew/bin/php /usr/local/bin/composer update && /opt/homebrew/bin/php /usr/local/bin/composer audit --format=json >'.$composeraudit_result_path;

        $process = Process::fromShellCommandline($command, null, $environment);
        $process->run();

        // Vérifier si la commande a réussi
        if ($process->isSuccessful()) {
            // Output de la commande@
            //$output = $process->getOutput();
            $composer_audit_result->update([
                'result_status_id' => 1, //Success
            ]);
        } else {
            // Erreur de la commande
            $error = $process->errorOutput();
            $composer_audit_result->update([
                'result_status_id' => 2, //Fail
            ]);
            Process::run('echo '.$error.' > '.$composeraudit_result_path);
        }
    }

    public function failed()
    {
        $name_rapport_file = now()->format('d-m-Y-H-i-s').'-composerAudit'.$this->repository->name;
        $base_path_repository = base_path().'/public'.Storage::url($this->repository->repo_path);
        $composeraudit_result_path = storage_path('app/public/').$this->repository->user_id.'/'.$name_rapport_file.'.json';

        $result = Process::run('echo '.$error.' > '.$composeraudit_result_path);
    }
}
