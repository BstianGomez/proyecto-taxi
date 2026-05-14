<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'acreedor',
        'nombre',
        'rut',
        'razon_social',
        'direccion',
        'comuna',
        'region',
        'telefono',
        'numero_cuenta',
        'tipo_cuenta',
        'banco',
        'nombre_titular',
        'rut_titular',
        'correo',
        'certificado_bancario'
    ];
}
