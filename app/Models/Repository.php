<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_project',
        'url', 'user_id'
    ];

    protected $casts = [
        'branches' => 'array'
    ];

    /**
     * Relationships between models User & Demand
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('User');
    }
}
