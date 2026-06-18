<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PengajuanCuti;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'name' => 'Martin Edwards',
            'email' => 'mjedwards@ems.com',
            'password' => Hash::make('admin123'), // Password untuk login admin
            'role' => 'admin',
            'nip' => 'ADM001',
            'jabatan' => 'HRD Manager',
            'divisi' => 'HRD',
        ]);

        // 2. Akun Pegawai 
        $pegawai = User::create([
            'name' => 'James',
            'email' => 'jamesyufan@ems.com',
            'password' => Hash::make('pegawai123'), // Password untuk login pegawai
            'role' => 'pegawai',
            'nip' => 'PEG001',
            'jabatan' => 'Staff IT',
            'divisi' => 'IT Support',
            'sisa_jatah_cuti' => 12, // Definisikan eksplisit untuk kejelasan seeder
        ]);

        // 3. Data Dummy Pengajuan Cuti
        PengajuanCuti::create([
            'user_id' => $pegawai->id,
            'tanggal_mulai' => '2026-06-20',
            'tanggal_selesai' => '2026-06-22',
            'alasan' => 'Acara keluarga di luar kota',
            'jml_hari_cuti' => 3,
            'status' => 'Pending',
        ]);

        PengajuanCuti::create([
            'user_id' => $pegawai->id,
            'tanggal_mulai' => '2026-07-05',
            'tanggal_selesai' => '2026-07-06',
            'alasan' => 'Pemeriksaan kesehatan rutin',
            'jml_hari_cuti' => 2,
            'status' => 'Pending',
        ]);
    }
}