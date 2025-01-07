<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DfeDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'dfe_id',
        'nsu',
        'schema',
        'content',
        'tipo',
    ];

    public function dfe()
    {
        return $this->belongsTo(Dfe::class);
    }
}
