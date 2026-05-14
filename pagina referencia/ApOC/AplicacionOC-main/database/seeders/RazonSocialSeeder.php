<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RazonSocialSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('../datos_OC/FundRazonSocial.csv');
        
        if (!file_exists($csvFile)) {
            return;
        }

        
            $content = file_get_contents($csvFile);
            preg_match_all('/"([^"\n]+),""([^"\n]+)"",""([^"\n]+)"",""([^"\n]+)"",""([^"\n]+)"",""[^"\n]+"""/m', $content, $matches, PREG_SET_ORDER);
        
            $razones = [];
            foreach ($matches as $index => $match) {
                // Saltar header (primer match)
                if ($index === 0) {
                    continue;
                }

                $codCliente = trim($match[1]);
                $rut = trim($match[2]);
                $codDeudor = trim($match[4]);
                $razonSocial = trim($match[5]);
            
                // Buscar el ID del cliente
                $cliente = DB::table('clientes')->where('codigo', $codCliente)->first();
            
                if ($cliente && !empty($razonSocial)) {
                    $razones[] = [
                        'cliente_id' => $cliente->id,
                        'rut' => $rut,
                        'cod_deudor' => $codDeudor,
                        'razon_social' => $razonSocial,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        
            if (!empty($razones)) {
                DB::table('razones_sociales')->insert($razones);
            }
        if (!empty($razones)) {
            DB::table('razones_sociales')->insert($razones);
        }
    }
}
