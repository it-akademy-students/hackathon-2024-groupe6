<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhpstanReport extends Model
{
    use HasFactory;

    protected $fillable = [
      'demand_id',
      'url_report'
    ];

    /**
     * Relationships between models Result & Demand
     */
    public function demand(): BelongsTo
    {
        return $this->belongsTo('Demand');
    }
}
