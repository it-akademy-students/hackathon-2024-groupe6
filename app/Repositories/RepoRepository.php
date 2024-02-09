<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Repository;
use Illuminate\Database\Eloquent\Builder;

class RepoRepository
{
    public function query(): Builder
    {
        return Repository::query();
    }

    public function getByUserId()
    {
        return $this->query()->where('user_id', '=', auth('sanctum')->user()->id);
    }

    public function getByRepoId($repo_id)
    {
        return $this->query()->where('id', '=', $repo_id);
    }

    public function getTestsByRepoId($repo_id, $branch)
    {
        return $this->getByRepoId($repo_id)
            ->with(
                'testRequests',
                function ($query) use ($branch) {
                    $query->where('branch', '=', $branch);
                    $query->with('phpstanResult', function ($sub_q) {
                        $sub_q->with('status');
                    });
                    $query->with('phpSecurityCheckerResult', function ($sub_q) {
                        $sub_q->with('status');
                    });
                    $query->with('composerAuditResult', function ($sub_q) {
                        $sub_q->with('status');
                    });
                }
            );
    }

    public function getTestsByUserId()
    {
        return $this->getByUserId()
            ->with(
                'testRequests',
                function ($query) {
                    $query->with('phpstanResult', function ($sub_q) {
                        $sub_q->with('status');
                    });
                    $query->with('phpSecurityCheckerResult', function ($sub_q) {
                        $sub_q->with('status');
                    });
                    $query->with('composerAuditResult', function ($sub_q) {
                        $sub_q->with('status');
                    });
                }
            );
    }
}
