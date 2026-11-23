<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Pelaporan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles: ID 1 = manager, ID 2 = admin
        Role::firstOrCreate(['id' => 1], ['role' => 'manager']);
        Role::firstOrCreate(['id' => 2], ['role' => 'admin']);

        // Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'manager', 'password' => bcrypt('1234'), 'role_id' => 1]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            ['name' => 'admin', 'password' => bcrypt('1234'), 'role_id' => 2]
        );

        // Kategori & Lokasi
        $kategori = Kategori::firstOrCreate(['kategori' => 'Elektronik']);
        $lokasi = Lokasi::firstOrCreate(['lokasi' => 'Gedung Utama']);

        // Aset
        $aset = Aset::firstOrCreate([
            'kd_aset'   => 'AST001',
            'nm_aset'   => 'Laptop Lenovo',
            'kategori_id' => $kategori->id,
            'lokasi_id'   => $lokasi->id,
            'gambar'      => 'default.jpg',
            'deskripsi'   => 'Laptop kantor untuk kebutuhan administrasi.',
            'tgl_penambahan'=> Carbon::now()->format('Y-m-d'),
        ]);

        // Pelaporan (Wajib isi user_id!)
        Pelaporan::create([
            'judul'     => 'Laptop Rusak',
            'deskripsi' => 'Layar tidak menyala dan baterai cepat habis.',
            'status'    => 'menunggu',
            'aset_id' => $aset->id,
            'user_id'   => $user->id, // ← pastikan ini ada!
        ]);
    }
}
