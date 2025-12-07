<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $vendors = [
            [
                'name' => 'PT Mitra Logistik',
                'email' => 'sales@mitralogistik.co.id',
                'phone' => '+62 21 5088 1100',
                'address' => 'Kawasan Industri MM2100 Blok A, Bekasi 17520',
                'payment_terms' => '30 days',
                'tags' => ['Preferred', 'Logistics'],
                'total_spend' => 78200000,
                'notes' => 'Vendor utama untuk bahan baku dan distribusi.',
            ],
            [
                'name' => 'CV Bumi Makmur',
                'email' => 'contact@bumimakmur.id',
                'phone' => '+62 812 8899 7766',
                'address' => 'Jl. Raya Bogor KM 26, Cibinong 16914',
                'payment_terms' => '15 days',
                'tags' => ['Bahan Baku'],
                'total_spend' => 56400000,
            ],
            [
                'name' => 'PT Cahaya Elektronik',
                'email' => 'info@cahayaelektronik.co.id',
                'phone' => '+62 21 7778 9090',
                'address' => 'Jl. Gajah Mada No. 35, Jakarta Barat 11130',
                'payment_terms' => '45 days',
                'tags' => ['Elektronik'],
                'total_spend' => 41250000,
            ],
            [
                'name' => 'PT Anugerah Mesin',
                'email' => 'procurement@anugerahmesin.co.id',
                'phone' => '+62 31 5678 1234',
                'address' => 'Jl. Industri Raya No. 88, Surabaya 60177',
                'payment_terms' => '30 days',
                'tags' => ['Mesin', 'Peralatan'],
                'total_spend' => 32500000,
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
