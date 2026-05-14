<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OcSubida extends Model
{
    protected $table = 'oc_subidas';

    protected $fillable = [
        'numero_oc',
        'ceco',
        'estado',
        'monto',
        'fecha_envio',
        'archivo_path',
        'archivo_nombre',
        'enviado_a_email',
        'proveedor_email',
        'token_envio',
        'token_proveedor',
    ];

    protected $casts = [
        'fecha_envio' => 'date',
        'monto' => 'decimal:2',
    ];
}
