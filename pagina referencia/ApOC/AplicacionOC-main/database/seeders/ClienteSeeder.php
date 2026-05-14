<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('../datos_OC/FundClienteFundacion.csv');
        
        if (!file_exists($csvFile)) {
            return;
        }

        $content = file_get_contents($csvFile);
        preg_match_all('/"([^"\n]+),""([^"\n]+)"",""([^"\n]+)"""/m', $content, $matches, PREG_SET_ORDER);
        
        $clientesByCodigo = [];
        foreach ($matches as $index => $match) {
            // Saltar header (primer match)
            if ($index === 0) {
                continue;
            }

            $nombre = trim($match[2]);
            $codigoCompleto = trim($match[3]);

            // Extraer solo el código numérico del formato "Nombre-Codigo"
            $parts = explode('-', $codigoCompleto);
            $codigo = trim((string) end($parts));
            
            if (!empty($codigo) && !empty($nombre)) {
                $clientesByCodigo[$codigo] = [
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $clientes = array_values($clientesByCodigo);
        
        if (!empty($clientes)) {
            DB::table('clientes')->insert($clientes);
        }
    }
}
