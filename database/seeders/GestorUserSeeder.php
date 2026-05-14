<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GestorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'gestor@sofofa.cl'],
            [
                'name' => 'Gestor de Taxis',
                'password' => \Illuminate\Support\Facades\Hash::make('gestor123'),
                'role' => 'gestor',
            ]
        );
    }
}
