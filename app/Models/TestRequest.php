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
        'name_project',
        'user_id',
        'url',
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

    /**
     * Relationships between models Result & Demand
     */
    public function phpstanResult(): HasOne
    {
        return $this->hasOne('PhpstanResult');
    }
}
