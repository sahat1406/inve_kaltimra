<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Pelaporan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        Role::firstOrCreate(['id' => 1], ['role' => 'admin']);
        Role::firstOrCreate(['id' => 2], ['role' => 'user']);

        // Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'admin', 'password' => bcrypt('1234'), 'role_id' => 1]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            ['name' => 'user', 'password' => bcrypt('1234'), 'role_id' => 2]
        );

        // Kategori & Lokasi
        $kategori = Kategori::firstOrCreate(['kategori' => 'Elektronik']);
        $lokasi = Lokasi::firstOrCreate(['lokasi' => 'Gedung Utama']);

        // Barang
        $barang = Barang::firstOrCreate([
            'kd_barang'   => 'BRG001',
            'nm_barang'   => 'Laptop Lenovo',
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
            'barang_id' => $barang->id,
            'user_id'   => $user->id, // ← pastikan ini ada!
        ]);
    }
}
