<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TestRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'repo_id',
        'user_id',
      
    ];

    /**
     * Relationships between models User & TestRequest
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('User');
    }

    /**
     * Relationships between models PhpStanResult & TestRequest
     * @return HasOne
     */
    public function phpstanResult(): HasOne
    {
        return $this->hasOne(PhpstanResult::class, 'test_request_id', 'id');
    }

    /**
     * Relationships between models Repository & TestRequest
     * @return BelongsTo
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo('Repository');
    }


    /**
     * Relationships between models Result & TestRequest
     */
    public function phpSecurityCheckerResult(): HasOne
    {
        return $this->hasOne('PhpstanResult');
    }
}
