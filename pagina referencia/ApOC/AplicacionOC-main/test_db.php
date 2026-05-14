<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$baseQuery = \Illuminate\Support\Facades\DB::table('oc_solicitudes')
                ->whereIn('id', function($q) {
                    $q->select('oc_solicitud_id')->from('oc_enviadas');
                });
$chartQuery = clone $baseQuery;

$sumByCeco = (clone $chartQuery)
            ->select('ceco', \Illuminate\Support\Facades\DB::raw('SUM(monto) as total_monto'))
            ->groupBy('ceco')
            ->orderByDesc('total_monto')
            ->get();
print_r($sumByCeco);
