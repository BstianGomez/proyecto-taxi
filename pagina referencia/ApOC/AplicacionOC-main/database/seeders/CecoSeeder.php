<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CecoSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('../datos_OC/CECO.csv');
        
        if (!file_exists($csvFile)) {
            return;
        }

        $content = file_get_contents($csvFile);
        preg_match_all('/"([^"\n]+),""([^"\n]+)"",""([^"\n]+)"",""([^"\n]+)"""/m', $content, $matches, PREG_SET_ORDER);
        
        $cecosByCodigo = [];
        foreach ($matches as $index => $match) {
            // Saltar header (primer match)
            if ($index === 0) {
                continue;
            }

            $codigo = trim($match[2]);
            $nombre = trim($match[3]);
            $tipo = trim($match[4]);
            
            if (!empty($codigo)) {
                $cecosByCodigo[$codigo] = [
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                    'tipo' => $tipo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $cecos = array_values($cecosByCodigo);
        
        if (!empty($cecos)) {
            DB::table('cecos')->insert($cecos);
        }
    }
}
