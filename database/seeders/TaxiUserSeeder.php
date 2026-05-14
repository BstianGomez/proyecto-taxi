<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'taxi@sofofa.cl'],
            [
                'name' => 'Conductor de Taxi',
                'password' => \Illuminate\Support\Facades\Hash::make('taxi123'),
                'role' => 'taxi',
            ]
        );
    }
}
