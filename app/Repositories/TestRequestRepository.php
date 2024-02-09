<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Builder;

class TestRequestRepository
{
    public function query(): Builder
    {
        return TestRequest::query();
    }

    public function getResultsByTestRequest($test_request_id)
    {
        return $this->query()
            ->where('id', '=', $test_request_id)
            ->with('phpstanResult', function ($query) {
                $query->with('status');
            })
            ->with('phpSecurityCheckerResult', function ($query) {
                $query->with('status');
            })
            ->with('composerAuditResult', function ($query) {
                $query->with('status');
            });
    }
}
