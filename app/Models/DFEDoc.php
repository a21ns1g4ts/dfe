<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DFEDoc extends Model
{
    protected $table = 'dfe_docs';

    protected $fillable = [
        'nsu',
        'schema',
        'content',
        'tipo',
    ];
}
