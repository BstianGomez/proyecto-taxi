<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Verificación de Base de Datos ===\n";

$count = DB::table('oc_solicitudes')->count();
echo "Total de solicitudes: $count\n";

if ($count > 0) {
    $states = DB::table('oc_solicitudes')
        ->select('estado', DB::raw('COUNT(*) as total'))
        ->groupBy('estado')
        ->get();
    
    echo "\nPor estado principal:\n";
    foreach ($states as $state) {
        echo "  {$state->estado}: {$state->total}\n";
    }
    
    $facturation = DB::table('oc_solicitudes')
        ->select('estado_facturacion', DB::raw('COUNT(*) as total'))
        ->groupBy('estado_facturacion')
        ->get();
    
    echo "\nPor estado de facturación:\n";
    foreach ($facturation as $f) {
        echo "  {$f->estado_facturacion}: {$f->total}\n";
    }
    
    echo "\nPrimeras 3 solicitudes:\n";
    $rows = DB::table('oc_solicitudes')->limit(3)->get();
    foreach ($rows as $row) {
        echo "  ID: {$row->id}, Estado: {$row->estado}, Facturación: {$row->estado_facturacion}\n";
    }
} else {
    echo "No hay datos en la tabla oc_solicitudes\n";
}
