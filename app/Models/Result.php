<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    /**
     * Relationships between models Result & Request
     */
    public function request() : BelongsTo
    {
        return $this->belongsTo('Request');
    }
}
