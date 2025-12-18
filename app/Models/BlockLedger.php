<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'timestamp',
        'previous_hash',
        'current_hash',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
