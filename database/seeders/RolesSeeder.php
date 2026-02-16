<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'SuperAdmin']);
        Role::firstOrCreate(['name' => 'LeagueAdmin']);
        Role::firstOrCreate(['name' => 'TeamAdmin']);
        Role::firstOrCreate(['name' => 'Scorekeeper']);
        Role::firstOrCreate(['name' => 'Player']);
    }
}
