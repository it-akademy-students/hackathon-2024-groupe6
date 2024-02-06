<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class ComposerAuditResult extends Model
{
    use HasFactory;

    protected $fillable= [
        'test_request_id', 'result_status_id', 'path_result', 'branch'
    ];

    /**
     * Relationships between models ComposerAuditResult & TestRequest
     */
    public function testRequest(): BelongsTo
    {
        return $this->belongsTo('TestRequest');
    }

    public function resultStatus(): HasOne
    {
        return $this->hasOne('ResultStatus');
    }
}
