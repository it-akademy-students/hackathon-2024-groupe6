<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Demand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name_project',
        'url',
        'status',
        'repo_path',
        'branches'
    ];

    /**
     * Relationships between models User & Demand
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo('User');
    }

    /**
     * Relationships between models Result & Demand
     */
    public function result() : HasOne
    {
        return $this->hasOne('Result');
    }
}
