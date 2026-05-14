<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function razonesSociales()
    {
        return $this->hasMany(RazonSocial::class);
    }
}
