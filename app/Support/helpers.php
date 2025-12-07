<?php

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $settings = Cache::remember('system_settings', 300, function () {
            return SystemSetting::all()->pluck('value', 'key')->toArray();
        });

        return data_get($settings, $key, $default);
    }
}

if (! function_exists('currency')) {
    function currency($value, string $fromCurrency = 'IDR'): string
    {
        $value = $value ?? 0;
        $fromCurrency = strtoupper($fromCurrency);

        $defaultCurrency = setting('currency.default', 'IDR');
        $rates = setting('currency.rates', [
            'USD_IDR' => 15500,
            'EUR_IDR' => 17000,
            'USD_EUR' => 0.92,
        ]);

        $toIdr = function ($amount, $currency) use ($rates) {
            $currency = strtoupper($currency);
            if ($currency === 'IDR') {
                return $amount;
            }
            if ($currency === 'USD') {
                return $amount * ($rates['USD_IDR'] ?? 15500);
            }
            if ($currency === 'EUR') {
                return $amount * ($rates['EUR_IDR'] ?? 17000);
            }
            return $amount;
        };

        $fromIdr = function ($amount, $currency) use ($rates) {
            $currency = strtoupper($currency);
            if ($currency === 'IDR') {
                return $amount;
            }
            if ($currency === 'USD') {
                return ($rates['USD_IDR'] ?? 15500) ? $amount / ($rates['USD_IDR'] ?? 15500) : $amount;
            }
            if ($currency === 'EUR') {
                return ($rates['EUR_IDR'] ?? 17000) ? $amount / ($rates['EUR_IDR'] ?? 17000) : $amount;
            }
            return $amount;
        };

        $baseIdr = $toIdr((float) $value, $fromCurrency);
        $converted = $fromIdr($baseIdr, $defaultCurrency);

        return format_currency_value($converted, $defaultCurrency);
    }
}

if (! function_exists('format_currency_value')) {
    function format_currency_value($amount, string $currency): string
    {
        $currency = strtoupper($currency);
        $amount = (float) $amount;

        switch ($currency) {
            case 'USD':
                return '$' . number_format($amount, 2, '.', ',');
            case 'EUR':
                return '€' . number_format($amount, 2, ',', '.');
            case 'IDR':
            default:
                return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }
}
