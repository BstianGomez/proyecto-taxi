<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('../datos_OC/Proveedores (1).csv');
        
        if (!file_exists($csvFile)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csvFile));
        array_shift($rows); // Remove header
        
        $proveedores = [];
        foreach ($rows as $row) {
            if (count($row) >= 3 && !empty(trim($row[1]))) {
                $acreedor = trim(str_replace('"', '', $row[1]));
                $nombre = trim(str_replace('"', '', $row[0]));
                $rut = trim(str_replace('"', '', $row[2]));
                
                if (!empty($acreedor) && !empty($nombre)) {
                    $proveedores[] = [
                        'acreedor' => $acreedor,
                        'nombre' => $nombre,
                        'rut' => $rut,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        DB::table('proveedores')->insert($proveedores);
    }
}
