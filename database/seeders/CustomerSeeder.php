<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $customers = [
            [
                'name' => 'PT Nusantara Jaya',
                'email' => 'halo@nusantarajaya.co.id',
                'phone' => '+62 812 1111 2222',
                'address' => 'Jl. Sudirman Kav. 52-53, Jakarta Selatan 12190',
                'tags' => ['Corporate', 'VIP'],
                'total_spend' => 85000000,
                'notes' => 'Pelanggan premium dengan riwayat pembayaran sangat baik.',
            ],
            [
                'name' => 'CV Cahaya Mandiri',
                'email' => 'info@cahaya-mandiri.id',
                'phone' => '+62 813 3344 5566',
                'address' => 'Jl. Gatot Subroto No. 88, Bandung 40262',
                'tags' => ['Corporate'],
                'total_spend' => 72000000,
                'notes' => 'Klien korporat dengan pesanan reguler bulanan.',
            ],
            [
                'name' => 'UD Berkah Sejahtera',
                'email' => 'berkah@usaha.id',
                'phone' => '+62 812 7788 9900',
                'address' => 'Jl. Ahmad Yani No. 45, Surabaya 60234',
                'tags' => ['Retail', 'Local'],
                'total_spend' => 65000000,
            ],
            [
                'name' => 'Toko Sejahtera Elektronik',
                'email' => 'cs@sejahterashop.id',
                'phone' => '+62 812 3456 7890',
                'address' => 'Jl. Diponegoro No. 123, Semarang 50241',
                'tags' => ['Retail'],
                'total_spend' => 48000000,
            ],
            [
                'name' => 'PT Samudra Global',
                'email' => 'sales@samudraglobal.co.id',
                'phone' => '+62 21 5089 1234',
                'address' => 'Kawasan Industri Pulogadung, Jakarta Timur 13930',
                'tags' => ['Corporate', 'International'],
                'total_spend' => 38000000,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
