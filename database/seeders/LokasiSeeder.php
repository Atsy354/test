<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            [
                'id' => 1,
                'nama_lokasi' => 'Stadion Utama',
                'aktif' => 'Y',
            ],
            [
                'id' => 2,
                'nama_lokasi' => 'Galeri Seni Kota',
                'aktif' => 'Y',
            ],
            [
                'id' => 3,
                'nama_lokasi' => 'Taman Kota',
                'aktif' => 'Y',
            ],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::updateOrCreate(
                ['id' => $lokasi['id']],
                $lokasi
            );
        }
    }
}