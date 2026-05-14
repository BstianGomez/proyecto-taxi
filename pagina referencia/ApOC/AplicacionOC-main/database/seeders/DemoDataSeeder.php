<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'cliente@aplicacionoc.com';
        $ceco = '20001';
        $proveedor = 'HOTELERA NOVAPARK SPA';
        $rut = '76600635-3';
        
        $datosExtra = json_encode(['correo_contacto' => $email]);

        // 1. Solicitud Pendiente
        DB::table('oc_solicitudes')->insert([
            'ceco' => $ceco,
            'tipo_solicitud' => 'OC Cliente',
            'tipo_documento' => 'Factura',
            'estado' => 'Solicitada',
            'rut' => $rut,
            'proveedor' => $proveedor,
            'descripcion' => 'Servicios de alojamiento corporativo para equipo de ventas.',
            'cantidad' => 2,
            'monto' => 150000,
            'datos_extra' => $datosExtra,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);

        // 2. Solicitud Rechazada
        DB::table('oc_solicitudes')->insert([
            'ceco' => $ceco,
            'tipo_solicitud' => 'OC Interna',
            'tipo_documento' => 'Boleta',
            'estado' => 'Rechazada',
            'rut' => '77.888.999-0',
            'proveedor' => 'LIBRERIA NACIONAL',
            'descripcion' => 'Compra de insumos de oficina especializados.',
            'cantidad' => 10,
            'monto' => 45000,
            'datos_extra' => $datosExtra,
            'observacion_rechazo' => 'Falta cotización adjunta requerida por política interna.',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(4),
        ]);

        // 3. Solicitud Aceptada y Enviada
        $solicitudId = DB::table('oc_solicitudes')->insertGetId([
            'ceco' => $ceco,
            'tipo_solicitud' => 'OC Negocio',
            'tipo_documento' => 'Factura Exenta',
            'estado' => 'Enviada',
            'rut' => $rut,
            'proveedor' => $proveedor,
            'descripcion' => 'Implementación de sistema de gestión de residuos en sede central.',
            'cantidad' => 1,
            'monto' => 1250000,
            'datos_extra' => $datosExtra,
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(8),
        ]);

        // 4. Crear registro en oc_enviadas vinculado a la solicitud #3
        DB::table('oc_enviadas')->insert([
            'oc_solicitud_id' => $solicitudId,
            'numero_oc' => 'OC-2024-0001',
            'ceco' => $ceco,
            'tipo_solicitud' => 'OC Negocio',
            'proveedor' => $proveedor,
            'email_proveedor' => 'contacto@novapark.cl',
            'rut' => $rut,
            'descripcion' => 'Implementación de sistema de gestión de residuos en sede central.',
            'cantidad' => 1,
            'monto' => 1250000,
            'comentario' => 'Orden de compra enviada oficialmente al proveedor para inicio de servicios.',
            'created_at' => Carbon::now()->subDays(8),
            'updated_at' => Carbon::now()->subDays(8),
        ]);
        
        // 5. Otra Solicitud Aceptada (sin enviar aún)
        DB::table('oc_solicitudes')->insert([
            'ceco' => $ceco,
            'tipo_solicitud' => 'OC Cliente',
            'tipo_documento' => 'Factura',
            'estado' => 'Aceptada',
            'rut' => '88.123.456-7',
            'proveedor' => 'SODIMAC S.A.',
            'descripcion' => 'Materiales de construcción para reparación de techumbre oficina sur.',
            'cantidad' => 1,
            'monto' => 890000,
            'datos_extra' => $datosExtra,
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subMinutes(30),
        ]);
    }
}
