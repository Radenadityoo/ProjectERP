<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        SystemSetting::updateOrCreate(
            ['key' => 'general'],
            ['value' => [
                'company_name' => 'PT Nusantara Jaya ERP',
                'company_address' => 'Jl. Jenderal Sudirman No. 22, Jakarta Selatan',
                'default_language' => 'id',
                'timezone' => 'Asia/Jakarta',
            ]]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'currency'],
            ['value' => [
                'default' => 'IDR',
                'rates' => [
                    'USD_IDR' => 15500,
                    'EUR_IDR' => 17000,
                    'USD_EUR' => 0.92,
                ],
            ]]
        );
    }
}
