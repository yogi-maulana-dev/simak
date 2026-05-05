<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@simak.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'super_admin',
            'prodi'     => null,
            'kode_lamp' => null,
        ]);

        User::factory()->create([
            'name'      => 'Admin TI',
            'email'     => 'admin.ti@simak.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'admin_prodi',
            'prodi'     => 'Teknik Informatika',
            'kode_lamp' => 'TI-001',
        ]);

        User::factory()->create([
            'name'      => 'Staf Akuntansi',
            'email'     => 'staf.ak@simak.ac.id',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'prodi'     => 'Akuntansi',
            'kode_lamp' => 'AK-002',
        ]);

        Folder::create([
            'name'       => 'Teknik Informatika',
            'parent_id'  => null,
            'path'       => '/Teknik Informatika',
            'created_by' => 1,
            'is_system'  => true,
            'kode_lamp'  => 'TI-001',
        ]);

        Folder::create([
            'name'       => 'Akuntansi',
            'parent_id'  => null,
            'path'       => '/Akuntansi',
            'created_by' => 1,
            'is_system'  => true,
            'kode_lamp'  => 'AK-002',
        ]);
    }
}