<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Request extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_project',
        'url',
        'status'=>'queued',
    ];

    /**
     * Relationships between models User & Request
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo('User');
    }

    /**
     * Relationships between models Result & Request
     */
    public function result() : HasOne
    {
        return $this->hasOne('Result');
    }
}
