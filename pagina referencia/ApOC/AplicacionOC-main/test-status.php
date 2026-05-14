<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Test de StatusCounts (como en dashboard) ===\n";

$baseQuery = DB::table('oc_solicitudes');

$statusCountsRaw = (clone $baseQuery)
    ->select('estado', DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
    ->groupBy('estado')
    ->get();

$statusCounts = ['Solicitada'=>0, 'Enviada'=>0, 'Aceptada'=>0, 'Rechazada'=>0, 'Facturado'=>0];
$statusCostos = ['Solicitada'=>0, 'Enviada'=>0, 'Aceptada'=>0, 'Rechazada'=>0, 'Facturado'=>0];

foreach($statusCountsRaw as $r) {
    $statusCounts[$r->estado] = $r->total;
    $statusCostos[$r->estado] = $r->total_monto;
}

// Sobrescribir FACTURADO
$facturadoStats = (clone $baseQuery)
    ->where('estado_facturacion', 'Facturado')
    ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
    ->first();
$statusCounts['Facturado'] = $facturadoStats->total ?? 0;
$statusCostos['Facturado'] = $facturadoStats->total_monto ?? 0;

// ENVIADA
$enviadaStats = (clone $baseQuery)
    ->where('estado', 'Aceptada')
    ->where('estado_facturacion', 'Facturado')
    ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
    ->first();
$statusCounts['Enviada'] = $enviadaStats->total ?? 0;
$statusCostos['Enviada'] = $enviadaStats->total_monto ?? 0;

echo "\nStatusCounts:\n";
foreach ($statusCounts as $estado => $count) {
    echo "  $estado: $count\n";
}

echo "\nStatusCostos:\n";
foreach ($statusCostos as $estado => $cost) {
    echo "  $estado: $" . number_format($cost, 0, ',', '.') . "\n";
}
