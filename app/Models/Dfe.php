<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dfe extends Model
{
    use HasFactory;

    protected $fillable = [
        'tp_amb',
        'ver_aplic',
        'c_stat',
        'x_motivo',
        'dh_resp',
        'ult_nsu',
        'max_nsu',
    ];

    public function docs()
    {
        return $this->hasMany(DfeDoc::class);
    }
}
