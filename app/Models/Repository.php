<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_project','repo_path','branches',
        'url', 'user_id', 'id',
    ];

    protected $casts = [
        'branches' => 'array'
    ];

    /**
     * Relationships between models User & Demand
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function testRequests(): HasMany
    {
        return $this->hasMany(TestRequest::class, 'repo_id', 'id');
    }
}
