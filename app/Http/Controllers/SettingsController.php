<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function general()
    {
        $settings = SystemSetting::all()->pluck('value', 'key');
        $general = $settings['general'] ?? [
            'company_name' => 'PT Nusantara ERP',
            'company_address' => 'Jl. Sudirman No. 123, Jakarta',
            'default_language' => 'id',
            'timezone' => 'Asia/Jakarta',
        ];

        return view('settings.general', compact('general'));
    }

    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'default_language' => 'required|in:id,en',
            'timezone' => 'required|string|max:100',
        ]);

        SystemSetting::updateOrCreate(['key' => 'general'], ['value' => $data]);
        Cache::forget('system_settings');

        return back()->with('success', 'General settings updated.');
    }

    public function currency()
    {
        $settings = SystemSetting::all()->pluck('value', 'key');
        $currency = $settings['currency'] ?? [
            'default' => 'IDR',
            'rates' => [
                'USD_IDR' => 15500,
                'EUR_IDR' => 17000,
                'USD_EUR' => 0.92,
            ],
        ];

        return view('settings.currency', compact('currency'));
    }

    public function updateCurrency(Request $request)
    {
        $data = $request->validate([
            'default' => 'required|in:USD,EUR,IDR',
            'USD_IDR' => 'required|numeric|min:0',
            'EUR_IDR' => 'required|numeric|min:0',
            'USD_EUR' => 'required|numeric|min:0',
        ]);

        $payload = [
            'default' => $data['default'],
            'rates' => [
                'USD_IDR' => $data['USD_IDR'],
                'EUR_IDR' => $data['EUR_IDR'],
                'USD_EUR' => $data['USD_EUR'],
            ],
        ];

        SystemSetting::updateOrCreate(['key' => 'currency'], ['value' => $payload]);
        Cache::forget('system_settings');

        return back()->with('success', 'Currency settings updated.');
    }
}
