<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OcEnviadasController;
use App\Http\Controllers\UserManagementController;
use App\Http\Requests\StoreOcSolicitudRequest;
use App\Rules\SecureFile;
use App\Services\AuditService;
use App\Services\SecureFileValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

// Rutas públicas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:30,1'])  // Rate limiting: 30 intentos por minuto (más razonable para login)
    ->name('login.submit');

Route::get('/change-password', [AuthController::class, 'showChangePassword'])
    ->middleware('auth')
    ->name('password.change');

Route::post('/change-password', [AuthController::class, 'changePassword'])
    ->middleware('auth')
    ->name('password.update');

// Rutas públicas para validar solicitudes desde correo (sin autenticación)
Route::get('/oc/solicitudes/{id}/aceptar-email', function ($id) {
    $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();

    if (!$solicitud || $solicitud->estado !== 'Solicitada') {
        return view('oc.email-action-close');
    }

    DB::table('oc_solicitudes')
        ->where('id', $id)
        ->update([
            'estado' => 'Aceptada',
            'updated_at' => now(),
        ]);

    AuditService::log('OC_ACCEPTED_EMAIL', [
        'id' => $id,
        'ceco' => $solicitud->ceco,
        'tipo' => $solicitud->tipo_solicitud,
        'monto' => $solicitud->monto,
    ]);

    // Enviar confirmación por correo
    $solicitudActualizada = DB::table('oc_solicitudes')->where('id', $id)->first();
    
    Mail::to('fbasoalto@fundacionsofofa.cl')
        ->send(new \App\Mail\OcSolicitudMail($solicitudActualizada, 'accepted'));

    return view('oc.email-action-close');
})->name('oc.solicitudes.aceptar-email');

Route::get('/oc/solicitudes/{id}/rechazar-email', function ($id) {
    $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();

    if (!$solicitud || $solicitud->estado !== 'Solicitada') {
        return view('oc.email-action-close');
    }

    DB::table('oc_solicitudes')
        ->where('id', $id)
        ->update([
            'estado' => 'Rechazada',
            'updated_at' => now(),
        ]);

    AuditService::log('OC_REJECTED_EMAIL', [
        'id' => $id,
        'ceco' => $solicitud->ceco,
        'tipo' => $solicitud->tipo_solicitud,
        'monto' => $solicitud->monto,
    ]);

    // Enviar confirmación por correo
    $solicitudActualizada = DB::table('oc_solicitudes')->where('id', $id)->first();
    
    Mail::to('fbasoalto@fundacionsofofa.cl')
        ->send(new \App\Mail\OcSolicitudMail($solicitudActualizada, 'rejected'));

    return view('oc.email-action-close');
})->name('oc.solicitudes.rechazar-email');

// ...existing code...
// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas para Gestor de Facturación (solo admin y super admin)
    Route::middleware(['role:super_admin,admin'])->group(function () {
    Route::get('/oc/gestor', function (Request $request) {
        $query = DB::table('oc_solicitudes');
        $sort = $request->input('sort', 'newest');
            
        if ($request->input('vista') === 'historial') {
            $query->where('estado', '!=', 'Solicitada')
                  ->where('estado', '!=', 'Aceptada');
            
            if ($sort === 'oldest') {
                $query->orderBy('updated_at')->orderBy('id');
            } else {
                $query->orderByDesc('updated_at')->orderByDesc('id');
            }
        } else {
            // Por defecto: mostrar Solicitadas Y Aceptadas
            $query->where(function($q) {
                $q->where('estado', 'Solicitada')
                  ->orWhere('estado', 'Aceptada');
            });

            if ($sort === 'oldest') {
                $query->orderBy('created_at')->orderBy('id');
            } else {
                $query->orderByDesc('created_at')->orderByDesc('id');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                  ->orWhere('proveedor', 'like', "%{$search}%")
                  ->orWhere('ceco', 'like', "%{$search}%")
                  ->orWhere('estado', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo_solicitud', $request->tipo);
        }

        $rows = $query->paginate(50)->withQueryString();
        return view('oc.gestor', compact('rows'));
    })->name('oc.gestor');

    // Gestión de Configuraciones de Proyecto
    Route::get('/oc/configuracion', [\App\Http\Controllers\ProjectSettingsController::class, 'index'])->name('oc.config');
    Route::post('/oc/configuracion/coordinador', [\App\Http\Controllers\ProjectSettingsController::class, 'storeCoordinador'])->name('oc.config.coordinador.store');
    Route::delete('/oc/configuracion/coordinador/{id}', [\App\Http\Controllers\ProjectSettingsController::class, 'destroyCoordinador'])->name('oc.config.coordinador.destroy');
    Route::post('/oc/configuracion/tipo-proyecto', [\App\Http\Controllers\ProjectSettingsController::class, 'storeTipoProyecto'])->name('oc.config.tipo-proyecto.store');
    Route::delete('/oc/configuracion/tipo-proyecto/{id}', [\App\Http\Controllers\ProjectSettingsController::class, 'destroyTipoProyecto'])->name('oc.config.tipo-proyecto.destroy');
    Route::post('/oc/configuracion/tipo-servicio', [\App\Http\Controllers\ProjectSettingsController::class, 'storeTipoServicio'])->name('oc.config.tipo-servicio.store');
    Route::delete('/oc/configuracion/tipo-servicio/{id}', [\App\Http\Controllers\ProjectSettingsController::class, 'destroyTipoServicio'])->name('oc.config.tipo-servicio.destroy');
    
    // AJAX para agregar opciones desde el formulario
    Route::post('/oc/configuracion/ajax-add', [\App\Http\Controllers\ProjectSettingsController::class, 'ajaxStore'])->name('oc.config.ajax.add');

    Route::post('/oc/gestor/{id}/status', function (Request $request, $id) {
        $validated = $request->validate([
            'estado' => 'required|in:Aceptada,Rechazada',
            'observacion' => 'required_if:estado,Rechazada|string|nullable'
        ]);

        $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();
        if (!$solicitud || $solicitud->estado === 'Facturado') {
            return response()->json(['success' => false, 'error' => 'No se puede modificar una solicitud ya facturada'], 403);
        }

        $estadoAnterior = $solicitud->estado;
        $nuevoEstado = $validated['estado'];

        $updateData = [
            'estado' => $nuevoEstado,
            'updated_at' => now(),
        ];

        if ($nuevoEstado === 'Rechazada') {
            $updateData['observacion_rechazo'] = $validated['observacion'];
        }

        DB::table('oc_solicitudes')->where('id', $id)->update($updateData);

        // --- Log de Auditoría ---
        AuditService::log($nuevoEstado === 'Aceptada' ? 'OC_ACCEPTED' : 'OC_REJECTED', [
            'id' => $id,
            'ceco' => $solicitud->ceco,
            'tipo' => $solicitud->tipo_solicitud,
            'monto' => $solicitud->monto,
            'estado_anterior' => $estadoAnterior,
            'nuevo_estado' => $nuevoEstado,
            'observacion' => $validated['observacion'] ?? null
        ]);

        // --- Notificación por Correo ---
        try {
            $emailSubject = $nuevoEstado === 'Aceptada' ? 'Solicitud OC Aceptada' : 'Solicitud OC Rechazada';
            $emailContent = "Cambio de Estado en Solicitud de OC\n\n";
            $emailContent .= "ID: $id\n";
            $emailContent .= "CECO: {$solicitud->ceco}\n";
            $emailContent .= "Tipo: {$solicitud->tipo_solicitud}\n";
            $emailContent .= "Monto: $" . number_format($solicitud->monto, 0, ',', '.') . "\n";
            $emailContent .= "Nuevo Estado: $nuevoEstado\n";
            
            if ($nuevoEstado === 'Rechazada' && !empty($validated['observacion'])) {
                $emailContent .= "Motivo del rechazo: {$validated['observacion']}\n";
            }
            
            $emailContent .= "Fecha: " . now()->format('d/m/Y H:i') . "\n";

            Mail::raw($emailContent, function ($message) use ($emailSubject, $solicitud) {
                $message->to($solicitud->correo_sesion_usuario ?? 'fbasoalto@fundacionsofofa.cl')
                        ->bcc('fbasoalto@fundacionsofofa.cl')
                        ->subject($emailSubject);
            });
        } catch (\Exception $e) {
            \Log::error("Error enviando correo de cambio de estado OC: " . $e->getMessage());
            // No detenemos la respuesta exitosa si falla el correo
        }

        return response()->json(['success' => true]);
    })->name('oc.gestor.status');

    Route::get('/oc/gestor/poll', function () {
        $count = DB::table('oc_solicitudes')->where('estado', 'Solicitada')->count();
        $latestId = DB::table('oc_solicitudes')->max('id');
        return response()->json(['count' => $count, 'latest_id' => $latestId]);
    })->name('oc.gestor.poll');

    Route::post('/oc/gestor/{id}/habilitar-edicion', function ($id) {
        DB::table('oc_solicitudes')->where('id', $id)->update([
            'estado' => 'Edicion',
            'updated_at' => now()
        ]);
        return response()->json(['success' => true]);
    })->name('oc.gestor.habilitar-edicion');

    Route::post('/oc/gestor/{id}/solicitar-ajuste', function (Request $request, $id) {
        DB::table('oc_solicitudes')->where('id', $id)->update([
            'estado' => 'Ajuste',
            'observacion_rechazo' => $request->comentario,
            'updated_at' => now()
        ]);
        return response()->json(['success' => true]);
    })->name('oc.gestor.solicitar-ajuste');

    Route::post('/oc/gestor/{id}/facturacion', function (Request $request, $id) {
        $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();
        if (!$solicitud) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $newState = $solicitud->estado_facturacion === 'Facturado' ? 'No Facturado' : 'Facturado';
        
        $updateData = [
            'estado_facturacion' => $newState, 
            'updated_at' => now()
        ];
        
        // Sincronizar con el estado principal
        if ($newState === 'Facturado') {
            $updateData['estado'] = 'Facturado';
        } elseif ($solicitud->estado === 'Facturado') {
            // Si deja de estar facturado, vuelve a 'Aceptada' o 'Enviada' (podemos dejarlo en 'Aceptada' como fallback seguro)
            $updateData['estado'] = 'Aceptada';
        }

        DB::table('oc_solicitudes')->where('id', $id)->update($updateData);

        return response()->json(['success' => true, 'estado_facturacion' => $newState]);
    })->name('oc.gestor.facturacion');
    });

    Route::post('/proveedores/ajax-create', function (\Illuminate\Http\Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'rut' => 'required|string|max:20',
            'nombre' => 'required|string|max:300',
            'razon_social' => 'required|string|max:300',
            'direccion' => 'required|string',
            'comuna' => 'required|string',
            'region' => 'required|string',
            'telefono' => 'required|string',
            'numero_cuenta' => 'required|string',
            'tipo_cuenta' => 'required|string',
            'banco' => 'required|string',
            'nombre_titular' => 'required|string',
            'rut_titular' => 'required|string',
            'correo' => 'required|email',
            'certificado_bancario' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $certificadoPath = null;
        if ($request->hasFile('certificado_bancario')) {
            $certificadoPath = $request->file('certificado_bancario')->store('certificados', 'public');
        }

        $acreedor = 'P-' . time() . rand(10, 99);

        $id = \Illuminate\Support\Facades\DB::table('proveedores')->insertGetId([
            'rut' => $request->rut,
            'nombre' => $request->nombre,
            'razon_social' => $request->razon_social,
            'direccion' => $request->direccion,
            'comuna' => $request->comuna,
            'region' => $request->region,
            'telefono' => $request->telefono,
            'numero_cuenta' => $request->numero_cuenta,
            'tipo_cuenta' => $request->tipo_cuenta,
            'banco' => $request->banco,
            'nombre_titular' => $request->nombre_titular,
            'rut_titular' => $request->rut_titular,
            'correo' => $request->correo,
            'certificado_bancario' => $certificadoPath,
            'acreedor' => $acreedor,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $proveedor = \Illuminate\Support\Facades\DB::table('proveedores')->find($id);

        return response()->json([
            'success' => true,
            'proveedor' => $proveedor
        ]);    });

        // Enviar todos los datos del dashboard (sin filtros) a Gmail
        Route::post('/dashboard/send-gmail-all', function (Request $request) {
            $grafico1 = $request->input('grafico1');
            $grafico2 = $request->input('grafico2');
            $email = $request->input('gmail');
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Debes ingresar un correo electrónico válido.');
            }
            
            try {
                $rows = DB::table('oc_solicitudes')->orderByDesc('created_at')->orderByDesc('id')->get();
                
                // Generar datos para el PDF
                $sumByCeco = DB::table('oc_solicitudes')
                    ->where('estado', '!=', 'Rechazada')
                    ->select('ceco', DB::raw('SUM(monto) as total_monto'))
                    ->groupBy('ceco')
                    ->orderByDesc('total_monto')
                    ->get()
                    ->map(function ($item) {
                        $numericCeco = preg_replace('/[^0-9]/', '', $item->ceco);
                        return (object) [
                            'ceco' => $numericCeco ?: $item->ceco,
                            'total_monto' => $item->total_monto,
                        ];
                    })
                    ->sortByDesc('total_monto')
                    ->values();
                
                $statusCountsRaw = DB::table('oc_solicitudes')
                    ->select('estado', DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
                    ->groupBy('estado')
                    ->get();
                
                $statusCounts = ['Solicitada'=>0, 'Enviada'=>0, 'Aceptada'=>0, 'Rechazada'=>0, 'Facturado'=>0];
                $statusCostos = ['Solicitada'=>0, 'Enviada'=>0, 'Aceptada'=>0, 'Rechazada'=>0, 'Facturado'=>0];
                foreach($statusCountsRaw as $r) {
                    $statusCounts[$r->estado] = $r->total;
                    $statusCostos[$r->estado] = (float)($r->total_monto ?? 0);
                }

                // Sobrescribir FACTURADO con el conteo correcto (por estado_facturacion)
                $facturadoStats = DB::table('oc_solicitudes')
                    ->where('estado_facturacion', 'Facturado')
                    ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
                    ->first();
                $statusCounts['Facturado'] = $facturadoStats->total ?? 0;
                $statusCostos['Facturado'] = $facturadoStats->total_monto ?? 0;
                
                // ENVIADA = Contar directamente desde oc_enviadas
                $enviadaStats = DB::table('oc_enviadas')
                    ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(CAST(monto AS DECIMAL(15,2))) as total_monto'))
                    ->first();
                $statusCounts['Enviada'] = $enviadaStats->total ?? 0;
                $statusCostos['Enviada'] = $enviadaStats->total_monto ?? 0;
                
                // Generar HTML para el PDF
                $html = view('pdf.dashboard', [
                    'rows' => $rows,
                    'sumByCeco' => $sumByCeco,
                    'statusCounts' => $statusCounts,
                    'statusCostos' => $statusCostos,
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                ])->render();
                
                // Generar PDF
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $pdfContent = $dompdf->output();
                
                // Enviar correo con PDF adjunto
                Mail::raw('Adjunto encontrarás el reporte PDF del Dashboard de Órdenes de Compra con todos los datos de las solicitudes.', function ($message) use ($email, $pdfContent) {
                    $message->to($email)
                        ->bcc(auth()->user()->email)
                        ->subject('Dashboard OC - Reporte PDF')
                        ->attachData($pdfContent, 'dashboard_oc_' . date('Y-m-d_His') . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                });
                
                AuditService::log('EMAIL_SENT', [
                    'to' => $email,
                    'type' => 'pdf_report',
                    'rows_count' => count($rows)
                ]);
                
                return back()->with('success', 'Reporte PDF enviado correctamente a ' . $email);
            } catch (\Exception $e) {
                \Log::error('Error enviando correo: ' . $e->getMessage());
                return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
            }
        })->name('oc.send_gmail_all');
    
    // Descargar PDF del dashboard
    Route::get('/dashboard/export-pdf', function (Request $request) {
        $grafico1 = $request->input('grafico1');
        $grafico2 = $request->input('grafico2');

        $rows = DB::table('oc_solicitudes')->orderByDesc('created_at')->orderByDesc('id')->get();
        
        $sumByCeco = DB::table('oc_solicitudes')
            ->where('estado', '!=', 'Rechazada')
            ->select('ceco', DB::raw('SUM(monto) as total_monto'))
            ->groupBy('ceco')
            ->orderByDesc('total_monto')
            ->get()
            ->map(function ($item) {
                $numericCeco = preg_replace('/[^0-9]/', '', $item->ceco);
                return (object) [
                    'ceco' => $numericCeco ?: $item->ceco,
                    'total_monto' => $item->total_monto,
                ];
            })
            ->sortByDesc('total_monto')
            ->values();
        
        $statusCountsRaw = DB::table('oc_solicitudes')
            ->select('estado', DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
            ->groupBy('estado')
            ->get();
        
        $statusCounts = ['Solicitada'=>0, 'Enviada'=>0, 'Aceptada'=>0, 'Rechazada'=>0, 'Facturado'=>0];
        $statusCostos = ['Solicitada'=>0, 'Enviada'=>0, 'Aceptada'=>0, 'Rechazada'=>0, 'Facturado'=>0];
        
        foreach($statusCountsRaw as $r) {
            $statusCounts[$r->estado] = $r->total;
            $statusCostos[$r->estado] = $r->total_monto;
        }
        
        // Sobrescribir FACTURADO con el conteo correcto (por estado_facturacion)
        $facturadoStats = DB::table('oc_solicitudes')
            ->where('estado_facturacion', 'Facturado')
            ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
            ->first();
        $statusCounts['Facturado'] = $facturadoStats->total ?? 0;
        $statusCostos['Facturado'] = $facturadoStats->total_monto ?? 0;
        
        // ENVIADA = Contar directamente desde oc_enviadas
        $enviadaStats = DB::table('oc_enviadas')
            ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(CAST(monto AS DECIMAL(15,2))) as total_monto'))
            ->first();
        $statusCounts['Enviada'] = $enviadaStats->total ?? 0;
        $statusCostos['Enviada'] = $enviadaStats->total_monto ?? 0;
        
        $html = view('pdf.dashboard', [
            'rows' => $rows,
            'sumByCeco' => $sumByCeco,
            'statusCounts' => $statusCounts,
            'statusCostos' => $statusCostos,
            'grafico1' => $grafico1,
            'grafico2' => $grafico2,
        ])->render();
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        AuditService::log('PDF_EXPORTED', ['rows_count' => count($rows)]);
        
        return $dompdf->stream('dashboard_oc_' . date('Y-m-d_His') . '.pdf');
    })->name('oc.export.pdf');
    

    // Rutas para Gestión de Proveedores
    Route::middleware(['role:super_admin,admin,gestor'])->group(function () {
        Route::get('/proveedores', function (\Illuminate\Http\Request $request) {
            $query = \Illuminate\Support\Facades\DB::table('proveedores');

            if ($request->filled('from')) {
                $query->whereDate('created_at', '>=', $request->input('from'));
            }

            if ($request->filled('to')) {
                $query->whereDate('created_at', '<=', $request->input('to'));
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('rut', 'like', "%{$search}%")
                      ->orWhere('acreedor', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%");
                });
            }

            // Orden por fecha: por defecto, mas reciente -> mas antigua.
            $sort = $request->input('sort', 'recent');
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
            } else {
                $query->orderByDesc('created_at')->orderByDesc('id');
            }

            $rows = $query->paginate(8)->withQueryString();
            return view('oc.proveedores', compact('rows'));
        })->name('proveedores.index');

        Route::get('/proveedores/download', function (\Illuminate\Http\Request $request) {
            $query = DB::table('proveedores');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('rut', 'like', "%{$search}%")
                      ->orWhere('acreedor', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%");
                });
            }

            $sort = $request->input('sort', 'recent');
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
            } else {
                $query->orderByDesc('created_at')->orderByDesc('id');
            }

            $proveedores = $query->get();

            $filename = "listado_proveedores_" . date('Y-m-d_H-i-s') . ".csv";
            $headers = [
                "Content-type"        => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($proveedores) {
                $file = fopen('php://output', 'w');
                // Añadir BOM para UTF-8 (compatibilidad Excel)
                fputs($file, "\xEF\xBB\xBF");
                
                // Encabezados
                fputcsv($file, [
                    'ID', 'RUT', 'Nombre', 'Acreedor', 'Razón Social', 
                    'Correo', 'Dirección', 'Comuna', 'Banco', 
                    'Tipo de Cuenta', 'N° Cuenta', 'Nombre Titular', 'RUT Titular', 'Fecha Registro'
                ], ';');

                foreach ($proveedores as $p) {
                    fputcsv($file, [
                        $p->id,
                        $p->rut,
                        $p->nombre,
                        $p->acreedor,
                        $p->razon_social,
                        $p->correo,
                        $p->direccion,
                        $p->comuna,
                        $p->banco,
                        $p->tipo_cuenta,
                        $p->numero_cuenta,
                        $p->nombre_titular,
                        $p->rut_titular,
                        $p->created_at
                    ], ';');
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        })->name('proveedores.download');

        Route::post('/proveedores', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'rut' => 'required|string|max:50',
                'nombre' => 'required|string|max:300',
                'razon_social' => 'nullable|string|max:300',
                'acreedor' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:500',
                'comuna' => 'nullable|string|max:255',
                'region' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:100',
                'numero_cuenta' => 'nullable|string|max:255',
                'tipo_cuenta' => 'nullable|string|max:255',
                'banco' => 'nullable|string|max:255',
                'nombre_titular' => 'nullable|string|max:255',
                'rut_titular' => 'nullable|string|max:50',
                'correo' => 'nullable|email|max:255',
            ]);

            $data = $validated;

            // La columna acreedor es NOT NULL en BD; si no viene, se autogenera.
            if (empty($data['acreedor'])) {
                $data['acreedor'] = 'P-' . time() . rand(10, 99);
            }

            $data['created_at'] = now();
            $data['updated_at'] = now();
            \Illuminate\Support\Facades\DB::table('proveedores')->insert($data);
            return back()->with('success', 'Proveedor creado exitosamente.');
        })->name('proveedores.store');

        Route::put('/proveedores/{id}', function (\Illuminate\Http\Request $request, $id) {
            $data = $request->except(['_token', '_method']);
            $data['updated_at'] = now();
            \Illuminate\Support\Facades\DB::table('proveedores')->where('id', $id)->update($data);
            return back()->with('success', 'Proveedor actualizado exitosamente.');
        })->name('proveedores.update');

        Route::delete('/proveedores/{id}', function ($id) {
            \Illuminate\Support\Facades\DB::table('proveedores')->where('id', $id)->delete();
            return back()->with('success', 'Proveedor eliminado exitosamente.');
        })->name('proveedores.destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (protegido)
    Route::get('/dashboard', function (Request $request) {
        $from = $request->query('from');
        $to = $request->query('to');
        $ceco = $request->query('ceco');
        $estado = $request->query('estado');

        $baseQuery = DB::table('oc_solicitudes');
        if ($from) {
            $baseQuery->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $baseQuery->whereDate('created_at', '<=', $to);
        }
        if ($ceco) {
            $baseQuery->where('ceco', $ceco);
        }
        if ($estado) {
            // Si se filtra por 'Facturado', buscar en estado_facturacion
            if ($estado === 'Facturado') {
                $baseQuery->where('estado_facturacion', 'Facturado');
            } elseif ($estado === 'Enviada') {
                // Si es Enviada, buscar las que tengan registro en la tabla oc_enviadas
                $baseQuery->whereIn('id', function($q) {
                    $q->select('oc_solicitud_id')->from('oc_enviadas');
                });
            } else {
                $baseQuery->where('estado', $estado);
            }
        }

        $rows = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        $chartQuery = clone $baseQuery;
        if ($estado !== 'Rechazada' && $estado !== 'Enviada') {
            $chartQuery->where('estado', '!=', 'Rechazada');
        }

        // Procesar datos en PHP en lugar de SQL REGEX (más seguro)
        $sumByCeco = (clone $chartQuery)
            ->select('ceco', DB::raw('SUM(monto) as total_monto'))
            ->groupBy('ceco')
            ->orderByDesc('total_monto')
            ->get()
            ->map(function ($item) {
                // Extraer números de CECO usando PHP
                $numericCeco = preg_replace('/[^0-9]/', '', $item->ceco);

                return (object) [
                    'ceco' => $numericCeco ?: $item->ceco,
                    'total_monto' => $item->total_monto,
                ];
            })
            ->sortByDesc('total_monto')
            ->values();  // Convertir a array con índices numéricos consecutivos

        $yearFunc = DB::getDriverName() === 'sqlite' ? "strftime('%Y', created_at)" : "YEAR(created_at)";
        $monthFunc = DB::getDriverName() === 'sqlite' ? "strftime('%m', created_at)" : "MONTH(created_at)";

        // Datos por mes y CECO
        $sumByCecoMonth = (clone $chartQuery)
            ->select('ceco', DB::raw("$yearFunc as year"), DB::raw("$monthFunc as month"), DB::raw('SUM(monto) as total_monto'))
            ->groupBy('ceco', 'year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $numericCeco = preg_replace('/[^0-9]/', '', $item->ceco);

                return (object) [
                    'ceco' => $numericCeco ?: $item->ceco,
                    'year' => $item->year,
                    'month' => $item->month,
                    'total_monto' => $item->total_monto,
                ];
            })
            ->values();  // Convertir a array con índices numéricos consecutivos

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
        
        // Sobrescribir FACTURADO con el conteo correcto (por estado_facturacion)
        $facturadoStats = (clone $baseQuery)
            ->where('estado_facturacion', 'Facturado')
            ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(monto) as total_monto'))
            ->first();
        $statusCounts['Facturado'] = $facturadoStats->total ?? 0;
        $statusCostos['Facturado'] = $facturadoStats->total_monto ?? 0;
        
        // ENVIADA = Contar directamente desde la tabla oc_enviadas
        $enviadaQuery = DB::table('oc_enviadas');
        if ($from) {
            $enviadaQuery->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $enviadaQuery->whereDate('created_at', '<=', $to);
        }
        if ($ceco) {
            $enviadaQuery->where('ceco', $ceco);
        }
        $enviadaStats = $enviadaQuery
            ->select(DB::raw('COUNT(*) as total'), DB::raw('SUM(CAST(monto AS DECIMAL(15,2))) as total_monto'))
            ->first();
        $statusCounts['Enviada'] = $enviadaStats->total ?? 0;
        $statusCostos['Enviada'] = $enviadaStats->total_monto ?? 0;

        $cecos = DB::table('oc_solicitudes')
            ->select('ceco')
            ->distinct()
            ->orderBy('ceco')
            ->pluck('ceco');

        AuditService::log('DASHBOARD_VIEWED', [
            'filters' => ['from' => $from, 'to' => $to, 'ceco' => $ceco, 'estado' => $estado],
        ]);

        return view('oc.dashboard', [
            'rows' => $rows,
            'sumByCeco' => $sumByCeco,
            'sumByCecoMonth' => $sumByCecoMonth,
            'statusCounts' => $statusCounts,
            'statusCostos' => $statusCostos,
                    'statusCostos' => $statusCostos,
            'cecos' => $cecos,
            'filters' => [
                'from' => $from,
                'to' => $to,
                'ceco' => $ceco,
                'estado' => $estado,
            ],
        ]);
    })->name('oc.dashboard');

    // Validación de solicitudes (solo para admin/gestor)
    Route::middleware(['role:super_admin,admin,gestor'])->group(function () {
        Route::post('/oc/solicitudes/{id}/aceptar', function ($id) {
            $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();

            if (!$solicitud) {
                return redirect()->route('oc.dashboard')->with('error', 'Solicitud no encontrada');
            }

            if ($solicitud->estado !== 'Solicitada') {
                return redirect()->route('oc.dashboard')->with('error', 'Solo se pueden aceptar solicitudes en estado "Solicitada"');
            }

            DB::table('oc_solicitudes')
                ->where('id', $id)
                ->update([
                    'estado' => 'Aceptada',
                    'updated_at' => now(),
                ]);

            AuditService::log('OC_ACCEPTED', [
                'id' => $id,
                'ceco' => $solicitud->ceco,
                'tipo' => $solicitud->tipo_solicitud,
                'monto' => $solicitud->monto,
            ]);

            // Enviar notificación por correo
            $emailContent = "Solicitud de OC Aceptada\n\n";
            $emailContent .= "ID: $id\n";
            $emailContent .= "CECO: {$solicitud->ceco}\n";
            $emailContent .= "Tipo: {$solicitud->tipo_solicitud}\n";
            $emailContent .= "Tipo de Documento: {$solicitud->tipo_documento}\n";
            $emailContent .= "Proveedor: " . ($solicitud->proveedor ?? 'N/A') . "\n";
            $emailContent .= "Descripción: " . ($solicitud->descripcion ?? 'N/A') . "\n";
            $emailContent .= "Cantidad: {$solicitud->cantidad}\n";
            $emailContent .= "Monto: $" . number_format($solicitud->monto, 0, ',', '.') . "\n";
            $emailContent .= "Estado: Aceptada\n";
            $emailContent .= "Fecha de aceptación: " . now()->format('d/m/Y H:i') . "\n";

            Mail::raw($emailContent, function ($message) use ($solicitud) {
                $message->to($solicitud->correo_sesion_usuario ?? 'fbasoalto@fundacionsofofa.cl')
                        ->bcc('fbasoalto@fundacionsofofa.cl')
                        ->subject('Solicitud OC Aceptada');
            });

            return redirect()->route('oc.dashboard')->with('success', 'Solicitud aceptada correctamente');
        })->name('oc.solicitudes.aceptar');

        Route::post('/oc/solicitudes/{id}/rechazar', function ($id) {
            $solicitud = DB::table('oc_solicitudes')->where('id', $id)->first();

            if (!$solicitud) {
                return redirect()->route('oc.dashboard')->with('error', 'Solicitud no encontrada');
            }

            if ($solicitud->estado !== 'Solicitada') {
                return redirect()->route('oc.dashboard')->with('error', 'Solo se pueden rechazar solicitudes en estado "Solicitada"');
            }

            DB::table('oc_solicitudes')
                ->where('id', $id)
                ->update([
                    'estado' => 'Rechazada',
                    'updated_at' => now(),
                ]);

            AuditService::log('OC_REJECTED', [
                'id' => $id,
                'ceco' => $solicitud->ceco,
                'tipo' => $solicitud->tipo_solicitud,
                'monto' => $solicitud->monto,
            ]);

            // Enviar notificación por correo
            $emailContent = "Solicitud de OC Rechazada\n\n";
            $emailContent .= "ID: $id\n";
            $emailContent .= "CECO: {$solicitud->ceco}\n";
            $emailContent .= "Tipo: {$solicitud->tipo_solicitud}\n";
            $emailContent .= "Tipo de Documento: {$solicitud->tipo_documento}\n";
            $emailContent .= "Proveedor: " . ($solicitud->proveedor ?? 'N/A') . "\n";
            $emailContent .= "Descripción: " . ($solicitud->descripcion ?? 'N/A') . "\n";
            $emailContent .= "Cantidad: {$solicitud->cantidad}\n";
            $emailContent .= "Monto: $" . number_format($solicitud->monto, 0, ',', '.') . "\n";
            $emailContent .= "Estado: Rechazada\n";
            $emailContent .= "Fecha de rechazo: " . now()->format('d/m/Y H:i') . "\n";

            Mail::raw($emailContent, function ($message) use ($solicitud) {
                $message->to($solicitud->correo_sesion_usuario ?? 'fbasoalto@fundacionsofofa.cl')
                        ->bcc('fbasoalto@fundacionsofofa.cl')
                        ->subject('Solicitud OC Rechazada');
            });

            return redirect()->route('oc.dashboard')->with('success', 'Solicitud rechazada correctamente');
        })->name('oc.solicitudes.rechazar');
    });

    // Gestión de usuarios (solo para admin/gestor)
    Route::middleware(['role:super_admin,admin,gestor'])->group(function () {
        Route::get('/usuarios', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/usuarios/crear', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/usuarios', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/usuarios/{user}/editar', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/usuarios/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Rutas de OC enviadas (protegidas)
    Route::get('/oc/enviadas', [OcEnviadasController::class, 'index'])->name('oc.enviadas');
    Route::get('/oc/enviadas/{id}/pdf', [OcEnviadasController::class, 'downloadPdf'])->name('oc.enviadas.pdf');
    Route::post('/oc/send', [OcEnviadasController::class, 'store'])->name('oc.send');
    Route::post('/oc/enviadas/{id}/send', [OcEnviadasController::class, 'sendEmail'])->name('oc.enviadas.sendEmail');
    Route::put('/oc/enviadas/{id}', [OcEnviadasController::class, 'update'])->name('oc.enviadas.update');

    // Rutas de subida de archivos (protegidas)
    Route::post('/oc/subir', function (Request $request) {
        $request->validate([
            'numero_oc' => ['required', 'string', 'max:50', 'unique:oc_subidas,numero_oc'],
            'ceco' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'in:Solicitada,Enviada,Aceptada,Facturado'],
            'monto' => ['required', 'numeric', 'min:1'],
            'fecha_envio' => ['required', 'date'],
            'enviado_a_email' => ['required', 'email'],
            'proveedor_email' => ['required', 'email'],
            'archivo' => ['required', 'file', 'max:10240', new SecureFile],
        ]);

        $file = $request->file('archivo');

        // Validar y sanitizar archivo
        $validator = new SecureFileValidator;
        try {
            $fileInfo = $validator->validate($file);
            $safeName = $validator->sanitizeFilename($file);
        } catch (\Exception $e) {
            AuditService::logDangerousFileAttempt($file->getClientOriginalName(), $e->getMessage());

            return back()->withErrors(['archivo' => 'Archivo rechazado por seguridad']);
        }

        $path = $file->storeAs('ocs', $safeName, 'private');

        $tokenEnvio = Str::uuid()->toString();
        $tokenProveedor = Str::uuid()->toString();

        DB::table('oc_subidas')->insert([
            'numero_oc' => $request->input('numero_oc'),
            'ceco' => $request->input('ceco'),
            'estado' => $request->input('estado'),
            'monto' => $request->input('monto'),
            'fecha_envio' => $request->input('fecha_envio'),
            'archivo_path' => $path,
            'archivo_nombre' => $file->getClientOriginalName(),
            'enviado_a_email' => $request->input('enviado_a_email'),
            'proveedor_email' => $request->input('proveedor_email'),
            'token_envio' => $tokenEnvio,
            'token_proveedor' => $tokenProveedor,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        AuditService::log('FILE_UPLOADED', [
            'numero_oc' => $request->input('numero_oc'),
            'file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('oc.dashboard')
            ->with('success', 'OC subida correctamente. Comparte los links de descarga con el destinatario y el proveedor.');
    })->name('oc.subir');

    // Rutas de exportación (protegidas)
    // Exportar OC Enviadas
    Route::get('/oc/enviadas/export', function () {
        $rows = DB::table('oc_enviadas')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $filename = 'oc_enviadas_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8 for Excel
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($rows->isNotEmpty()) {
                $columns = array_keys((array) $rows->first());
                fputcsv($file, $columns, ';');

                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $rowArray['created_at'] = substr($row->created_at ?? '', 0, 10);
                    $rowArray['updated_at'] = substr($row->updated_at ?? '', 0, 10);
                    fputcsv($file, $rowArray, ';');
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    })->name('oc.enviadas.export');

    Route::get('/oc/export', function () {
        $rows = DB::table('oc_solicitudes')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $filename = 'solicitudes_oc_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'CECO', 'Tipo Solicitud', 'Tipo Documento', 'Estado', 'RUT', 'Proveedor', 'Descripción', 'Cantidad', 'Monto', 'Fecha Creación'], ';');

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->ceco ?? '',
                    $row->tipo_solicitud ?? '',
                    $row->tipo_documento ?? '',
                    $row->estado ?? '',
                    $row->rut ?? '',
                    $row->proveedor ?? '',
                    $row->descripcion ?? '',
                    $row->cantidad ?? '',
                    number_format($row->monto ?? 0, 0, ',', '.'),
                    $row->created_at ?? '',
                ], ';');
            }

            fclose($file);
        };

        AuditService::log('EXPORT_REQUESTED', [
            'total_rows' => count($rows),
        ]);

        return response()->stream($callback, 200, $headers);
    })->name('oc.export');
});

// ==================== RUTAS PÚBLICAS (sin autenticación) ====================

// Búsqueda pública de OC (con rate limiting)
Route::middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::get('/oc', function (Request $request) {
        if (Auth::user()->hasRole('usuario')) {
            return redirect()->route('oc.user.home');
        }

        if (Auth::user()->isCliente()) {
            return redirect()->route('oc.home');
        }

        $query = DB::table('oc_solicitudes');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ceco', 'like', "%{$search}%")
                    ->orWhere('tipo_solicitud', 'like', "%{$search}%")
                    ->orWhere('tipo_documento', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%")
                    ->orWhere('rut', 'like', "%{$search}%")
                    ->orWhere('proveedor', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($tipo = $request->input('tipo')) {
            $query->where('tipo_solicitud', $tipo);
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $rows = $query->leftJoin('oc_enviadas', 'oc_solicitudes.id', '=', 'oc_enviadas.oc_solicitud_id')
            ->select('oc_solicitudes.*', 'oc_enviadas.comentario as manager_comment', 'oc_enviadas.file_path as sent_file_path')
            ->orderByDesc('oc_solicitudes.created_at')
            ->orderByDesc('oc_solicitudes.id')
            ->paginate(10)
            ->withQueryString();

        return view('oc.index', ['rows' => $rows]);
    })->name('oc.index');

    Route::get('/inicio-usuario', function () {
        if (!Auth::user()->hasRole('usuario')) {
            return redirect()->route('oc.index');
        }

        $recentRequests = DB::table('oc_solicitudes')
            ->leftJoin('oc_enviadas', 'oc_solicitudes.id', '=', 'oc_enviadas.oc_solicitud_id')
            ->select('oc_solicitudes.*', 'oc_enviadas.comentario as manager_comment', 'oc_enviadas.file_path as sent_file_path')
            ->where(function ($query) {
                $query->where('oc_solicitudes.correo_sesion_usuario', Auth::user()->email)
                    ->orWhere(DB::raw("JSON_EXTRACT(oc_solicitudes.datos_extra, '$.correo_contacto')"), Auth::user()->email);
            })
            ->orderByDesc('oc_solicitudes.created_at')
            ->take(10)
            ->get();

        return view('oc.home_usuario', compact('recentRequests'));
    })->name('oc.user.home');

    // Nueva ruta Inicio Solicitante
    Route::get('/inicio', function() {
        if (!Auth::user()->isCliente()) {
            return redirect()->route('oc.index');
        }

        $recentRequests = DB::table('oc_solicitudes')
            ->leftJoin('oc_enviadas', 'oc_solicitudes.id', '=', 'oc_enviadas.oc_solicitud_id')
            ->select('oc_solicitudes.*', 'oc_enviadas.comentario as manager_comment', 'oc_enviadas.file_path as sent_file_path')
            ->where(DB::raw("JSON_EXTRACT(oc_solicitudes.datos_extra, '$.correo_contacto')"), Auth::user()->email)
            ->orderByDesc('oc_solicitudes.created_at')
            ->take(10)
            ->get();

        return view('oc.home_solicitante', compact('recentRequests'));
    })->name('oc.home');

    // Formularios públicos de solicitud de OC
    Route::get('/oc/cliente', function () {
        $clientes = DB::table('clientes')->orderBy('nombre')->get();
        $razonesSociales = DB::table('razones_sociales')->orderBy('razon_social')->get();
        $proveedores = DB::table('proveedores')->orderBy('nombre')->get();

        return view('oc.cliente', compact('clientes', 'razonesSociales', 'proveedores'));
    })->name('oc.cliente');

    Route::post('/oc/cliente', function (StoreOcSolicitudRequest $request) {
        $validated = $request->validated();
        $allData = $request->except(['_token', 'my_company_website', 'adjunto']);
        if ($request->hasFile('adjunto')) {
            $allData['ruta_adjunto'] = $request->file('adjunto')->store('adjuntos_oc', 'public');
        }
        if (Auth::check()) {
            $allData['correo_sesion_usuario'] = Auth::user()->email;
            $allData['nombre_sesion_usuario'] = Auth::user()->name;
        }
        if (Auth::check()) {
            $allData['correo_sesion_usuario'] = Auth::user()->email;
            $allData['nombre_sesion_usuario'] = Auth::user()->name;
        }

        $ceco_val = $validated['ceco'] ?? $validated['cod_cliente'] ?? '20132';
        if ($ceco_val === 'N/A' || $ceco_val === 'NA' || trim($ceco_val) === '') {
            $ceco_val = '20132';
        }

        $solicitudData = [
            'ceco' => $ceco_val,
            'tipo_solicitud' => 'Cliente',
            'tipo_documento' => $validated['tipo_documento'] ?? 'N/A',
            'estado' => 'Solicitada',
            'rut' => $validated['rut_proveedor'] ?? null,
            'proveedor' => $validated['nombre_proveedor'] ?? null,
            'descripcion' => $validated['descripcion'] ?? $validated['nombre_curso'] ?? null,
            'cantidad' => $validated['cantidad_participantes'] ?? $validated['cantidad'] ?? 1,
            'monto' => $validated['monto'] ?? 0,
            'datos_extra' => json_encode($allData, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('oc_solicitudes')->insert($solicitudData);
        $solicitud = DB::table('oc_solicitudes')->where('ceco', $solicitudData['ceco'])->latest('id')->first();

        AuditService::log('OC_CREATED', ['type' => 'Cliente']);

        // Enviar notificación por correo
        $montoValor = floatval($solicitud->monto ?? 0);
        $tipoCorreo = $montoValor >= 1000000 ? 'created' : 'info';

        Mail::to('fbasoalto@fundacionsofofa.cl')
            ->bcc(Auth::user()->email)
            ->send(new \App\Mail\OcSolicitudMail($solicitud, $tipoCorreo));

        return redirect()->route('oc.index')->with('success', 'Solicitud de OC Cliente enviada correctamente');
    })->name('oc.cliente.store');

    Route::get('/oc/interna', function () {
        $cecos = DB::table('cecos')->orderBy('codigo')->get();
        $proveedores = DB::table('proveedores')->orderBy('nombre')->get();

        return view('oc.interna', compact('cecos', 'proveedores'));
    })->name('oc.interna');

    Route::post('/oc/interna', function (StoreOcSolicitudRequest $request) {
        $validated = $request->validated();
        $allData = $request->except(['_token', 'my_company_website', 'adjunto']);
        if ($request->hasFile('adjunto')) {
            $allData['ruta_adjunto'] = $request->file('adjunto')->store('adjuntos_oc', 'public');
        }
        if (Auth::check()) {
            $allData['correo_sesion_usuario'] = Auth::user()->email;
            $allData['nombre_sesion_usuario'] = Auth::user()->name;
        }
        if (Auth::check()) {
            $allData['correo_sesion_usuario'] = Auth::user()->email;
            $allData['nombre_sesion_usuario'] = Auth::user()->name;
        }

        $ceco_val = $validated['ceco'] ?? '20132';
        if ($ceco_val === 'N/A' || $ceco_val === 'NA' || trim($ceco_val) === '') {
            $ceco_val = '20132';
        }

        $solicitudData = [
            'ceco' => $ceco_val,
            'tipo_solicitud' => 'Interna',
            'tipo_documento' => $validated['tipo_documento'] ?? 'N/A',
            'estado' => 'Solicitada',
            'rut' => $validated['rut_proveedor'] ?? null,
            'proveedor' => $validated['nombre_proveedor'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'cantidad' => $validated['cantidad'] ?? 1,
            'monto' => $validated['monto'] ?? 0,
            'datos_extra' => json_encode($allData, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('oc_solicitudes')->insert($solicitudData);
        $solicitud = DB::table('oc_solicitudes')->where('ceco', $solicitudData['ceco'])->latest('id')->first();

        AuditService::log('OC_CREATED', ['type' => 'Interna']);

        // Enviar notificación por correo
        $montoValor = floatval($solicitud->monto ?? 0);
        $tipoCorreo = $montoValor >= 1000000 ? 'created' : 'info';

        Mail::to('fbasoalto@fundacionsofofa.cl')
            ->bcc(Auth::user()->email)
            ->send(new \App\Mail\OcSolicitudMail($solicitud, $tipoCorreo));

        return redirect()->route('oc.index')->with('success', 'Solicitud de OC Interna enviada correctamente');
    })->name('oc.interna.store');

    Route::get('/oc/negocio', function () {
        $cecos = DB::table('cecos')->orderBy('codigo')->get();
        $proveedores = DB::table('proveedores')->orderBy('nombre')->get();
        
        $coordinadores = \App\Models\CoordinadorProyecto::orderBy('nombre')->get();
        $tipoProyectos = \App\Models\TipoProyecto::orderBy('nombre')->get();
        $tipoServicios = \App\Models\TipoServicio::orderBy('nombre')->get();

        return view('oc.negocio', compact('cecos', 'proveedores', 'coordinadores', 'tipoProyectos', 'tipoServicios'));
    })->name('oc.negocio');

    Route::post('/oc/negocio', function (StoreOcSolicitudRequest $request) {
        $validated = $request->validated();
        $allData = $request->except(['_token', 'my_company_website', 'adjunto']);
        if ($request->hasFile('adjunto')) {
            $allData['ruta_adjunto'] = $request->file('adjunto')->store('adjuntos_oc', 'public');
        }
        if (Auth::check()) {
            $allData['correo_sesion_usuario'] = Auth::user()->email;
            $allData['nombre_sesion_usuario'] = Auth::user()->name;
        }
        if (Auth::check()) {
            $allData['correo_sesion_usuario'] = Auth::user()->email;
            $allData['nombre_sesion_usuario'] = Auth::user()->name;
        }

        $ceco_val = $validated['ceco'] ?? '20132';
        if ($ceco_val === 'N/A' || $ceco_val === 'NA' || trim($ceco_val) === '') {
            $ceco_val = '20132';
        }

        $solicitudData = [
            'ceco' => $ceco_val,
            'tipo_solicitud' => 'Negocio',
            'tipo_documento' => $validated['tipo_documento'] ?? 'N/A',
            'estado' => 'Solicitada',
            'rut' => $validated['rut_proveedor'] ?? null,
            'proveedor' => $validated['nombre_proveedor'] ?? null,
            'descripcion' => $validated['observacion'] ?? $validated['descripcion'] ?? null,
            'cantidad' => $validated['cantidad_modulos'] ?? $validated['cantidad'] ?? 1,
            'monto' => $validated['monto'] ?? 0,
            'datos_extra' => json_encode($allData, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('oc_solicitudes')->insert($solicitudData);
        $solicitud = DB::table('oc_solicitudes')->where('ceco', $solicitudData['ceco'])->latest('id')->first();

        AuditService::log('OC_CREATED', ['type' => 'Negocio']);

        // Enviar notificación por correo
        $montoValor = floatval($solicitud->monto ?? 0);
        $tipoCorreo = $montoValor >= 1000000 ? 'created' : 'info';

        Mail::to('fbasoalto@fundacionsofofa.cl')
            ->bcc(Auth::user()->email)
            ->send(new \App\Mail\OcSolicitudMail($solicitud, $tipoCorreo));

        return redirect()->route('oc.index')->with('success', 'Solicitud de OC unidad de negocio enviada correctamente');
    })->name('oc.negocio.store');

    // Descarga de archivo sin autenticación (pero con validación de token)

    // Nueva ruta para forzar descarga segura de adjuntos
    Route::get('/oc/adjunto/{path}', function (string $path) {
        if (!Auth::check()) {
            abort(403, 'No autorizado');
        }
        
        // Revisar en el disco 'public' donde se guardan habitualmente (Laravel 11 usa app/private por defecto en disco local)
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download($path);
        }
        
        // Fallback por si la ruta incluye public/ en su nombre (poco probable pero seguro)
        if (\Illuminate\Support\Facades\Storage::exists($path)) {
            return \Illuminate\Support\Facades\Storage::download($path);
        }

        abort(404, 'El archivo adjunto no se encontró en el servidor.');
    })->where('path', '.*')->name('oc.adjunto.descargar');

    Route::get('/oc/descargar/{token}', function (string $token) {
        $row = DB::table('oc_subidas')
            ->where('token_envio', $token)
            ->orWhere('token_proveedor', $token)
            ->first();

        if (! $row) {
            AuditService::log('DOWNLOAD_FAILED', [
                'token' => $token,
                'reason' => 'Token not found',
                'severity' => 'warning',
            ]);
            abort(404);
        }

        if (! Storage::exists($row->archivo_path)) {
            AuditService::log('DOWNLOAD_FAILED', [
                'token' => $token,
                'reason' => 'File not found',
                'severity' => 'warning',
            ]);
            abort(404);
        }

        AuditService::logFileDownload($row->archivo_nombre, Storage::size($row->archivo_path));

        return Storage::download($row->archivo_path, $row->archivo_nombre);
    })->name('oc.descargar');
});
