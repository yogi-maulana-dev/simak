<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ⬅️ WAJIB

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         DB::table('roles')->insert([
            ['name' => 'superadmin'],
            ['name' => 'admin_univ'],
            ['name' => 'admin_fakultas'],
            ['name' => 'admin_prodi'],
            ['name' => 'asesor_fakultas'],
            ['name' => 'asesor_prodi'],
        ]);
    }
}
