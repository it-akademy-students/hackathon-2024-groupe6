<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResultStatus extends Model
{
    use HasFactory;

    protected $table = 'result_status';

    public function phpstanResults(): HasMany
    {
        return $this->hasMany('PhpstanResult');
    }
}
