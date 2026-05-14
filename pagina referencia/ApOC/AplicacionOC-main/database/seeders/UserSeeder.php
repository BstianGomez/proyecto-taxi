<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@aplicacionoc.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@aplicacionoc.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Gestor
        User::create([
            'name' => 'Gestor de Solicitudes',
            'email' => 'gestor@aplicacionoc.com',
            'password' => Hash::make('password'),
            'role' => 'gestor',
        ]);

        // Cliente
        User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@aplicacionoc.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
        ]);
    }
}
