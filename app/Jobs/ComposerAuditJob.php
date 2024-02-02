<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process; 
use App\Mail\AnalyzeBeginMail;
use App\Mail\AnalyzeFailedMail;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ComposerAuditJob implements  ShouldQueue, ShouldBeEncrypted
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

    
    public function handle()
    {
        $repoPath = storage_path('app/') . $this->repository->repo_path;
        
        // Définir les variables d'environnement HOME et COMPOSER_HOME
        $environment = [
            'HOME' => env('HOME'),  // Ajoutez votre chemin d'accès HOME si vous l'avez défini
            'COMPOSER_HOME' => env('COMPOSER_HOME'),  // Ajoutez votre chemin d'accès COMPOSER_HOME si vous l'avez défini
        ];

        $command = 'cd ' . $repoPath . ' && /opt/homebrew/bin/php /usr/local/bin/composer audit --format=json > audit.json';

        $process = Process::fromShellCommandline($command, null, $environment);
        $process->run();

        // Vérifier si la commande a réussi
        if ($process->isSuccessful()) {
            // Output de la commande
            $output = $process->getOutput();
            echo $output;
        } else {
            // Erreur de la commande
            $error = $process->getErrorOutput();
            echo $error;
        }
    }
}
