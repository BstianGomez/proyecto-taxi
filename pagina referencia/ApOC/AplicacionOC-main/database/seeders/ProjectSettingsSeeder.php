<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoordinadorProyecto;
use App\Models\TipoProyecto;
use App\Models\TipoServicio;

class ProjectSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $coordinadores = ['Elisabet Cayuleo', 'Lorena Isla', 'Camila Aros', 'Paulina Coloma'];
        foreach ($coordinadores as $nombre) {
            CoordinadorProyecto::firstOrCreate(['nombre' => $nombre]);
        }

        $tipoServicios = ['GD', 'DI', 'DG'];
        foreach ($tipoServicios as $nombre) {
            TipoServicio::firstOrCreate(['nombre' => $nombre]);
        }

        $tipoProyectos = ['OR', 'OT'];
        foreach ($tipoProyectos as $nombre) {
            TipoProyecto::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
