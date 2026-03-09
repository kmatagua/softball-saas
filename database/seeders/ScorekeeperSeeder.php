<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ScorekeeperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarnos de que el rol exista
        $role = Role::firstOrCreate(['name' => 'Scorekeeper', 'guard_name' => 'web']);

        $user = User::firstOrCreate(
        ['email' => 'anotador@example.com'],
        [
            'name' => 'Anotador de Prueba',
            'password' => Hash::make('password'),
        ]
        );

        $user->assignRole($role);
    }
}
