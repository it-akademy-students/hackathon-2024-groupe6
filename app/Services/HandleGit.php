<?php

namespace App\Services;

use App\Models\Repository;
use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HandleGit
{
  /** @var Repository $repository */
  protected Repository $repository;

  /** @var string $base_path_repository */
  protected string $base_path_repository;

  /** @var string $repo_name */
  protected string $repo_name;

  public function __construct(Repository $repository)
  {
    $this->repository = $repository;
    $this->base_path_repository = base_path() . '/public' . Storage::url($repository->repo_path);
    $this->repo_name = Str::random(32);
  }

  /**
   * Clone a repo by the $repo_link
   *
   * @return void
   */
  public function gitClone(): void
  {
    Process::run(
      'git clone ' . $this->repository->url . ' '
      . storage_path('app/public/') . $this->repository->user_id . '/' . $this->repo_name
    );
  }

  /**
   * Get the list of origin branches to array and update the model
   *
   * @return void
   */
  public function getBranches(): void
  {
    $process_branches = Process::run('cd ' . $this->base_path_repository . ' && git branch -r');

    $branches_array = explode("\n", $process_branches->output());

    foreach ($branches_array as $branch) {
      $branch = trim($branch);
    }

    array_pop($branches_array);

    $this->repository->update([
      'branches' => $branches_array
    ]);
  }

  /**
   * Git fetch origin command
   *
   * @return void
   */
  public function gitFetchOrigin(): void
  {
    Process::run('cd ' . $this->base_path_repository . ' && git fetch --all');
  }

  /**
   * Change branch command
   * @retrun ProcessResult
   */
  public function gitCheckout(string $branch): \Illuminate\Contracts\Process\ProcessResult
  {
    Process::pipe(function (Pipe $pipe) use ($branch) {
      $pipe->run('cd ' . $this->base_path_repository);
      $pipe->run('git checkout ' . $branch);
    });
  }

  /**
   * Set a random repo name and update the model
   *
   * @return void
   */
  public function setRandomRepoName(): void
  {
    $this->repository->update([
      'repo_path' => 'public/' . $this->repository->user_id . '/' . $this->repo_name,
    ]);

    $this->base_path_repository = base_path() . '/public' . Storage::url($this->repository->repo_path);
  }
}
