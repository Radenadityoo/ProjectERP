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

if (! function_exists('base_currency')) {
    /**
     * Returns the application base currency used to store normalized monetary values.
     */
    function base_currency(): string
    {
        return config('app.base_currency', 'IDR');
    }
}

if (! function_exists('currency_rate_to_base')) {
    /**
     * Get multiplicative rate to convert a value FROM the given currency TO the base currency.
     */
    function currency_rate_to_base(string $fromCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $rates = setting('currency.rates', [
            'USD_IDR' => 15500,
            'EUR_IDR' => 17000,
            'USD_EUR' => 0.92,
        ]);

        $base = base_currency();
        if ($fromCurrency === $base) {
            return 1.0;
        }

        // Current base currency is assumed to be IDR; extend here when base changes.
        if ($base === 'IDR') {
            if ($fromCurrency === 'USD') {
                return (float) ($rates['USD_IDR'] ?? 15500);
            }
            if ($fromCurrency === 'EUR') {
                return (float) ($rates['EUR_IDR'] ?? 17000);
            }
        }

        return 1.0;
    }
}

if (! function_exists('currency_rate_from_base')) {
    /**
     * Get multiplicative rate to convert a value FROM the base currency TO the target currency.
     */
    function currency_rate_from_base(string $toCurrency): float
    {
        $toCurrency = strtoupper($toCurrency);
        $rates = setting('currency.rates', [
            'USD_IDR' => 15500,
            'EUR_IDR' => 17000,
            'USD_EUR' => 0.92,
        ]);

        $base = base_currency();
        if ($toCurrency === $base) {
            return 1.0;
        }

        if ($base === 'IDR') {
            if ($toCurrency === 'USD') {
                $den = $rates['USD_IDR'] ?? 15500;
                return $den ? 1 / (float) $den : 1.0;
            }
            if ($toCurrency === 'EUR') {
                $den = $rates['EUR_IDR'] ?? 17000;
                return $den ? 1 / (float) $den : 1.0;
            }
        }

        return 1.0;
    }
}

if (! function_exists('convert_to_base')) {
    /**
     * Convert a monetary amount from a given currency into the base currency.
     */
    function convert_to_base($amount, string $fromCurrency = 'IDR'): float
    {
        $amount = (float) ($amount ?? 0);
        $rate = currency_rate_to_base($fromCurrency);
        return $amount * $rate;
    }
}

if (! function_exists('convert_from_base')) {
    /**
     * Convert a monetary amount from the base currency into the target currency.
     */
    function convert_from_base($amount, string $toCurrency = 'IDR'): float
    {
        $amount = (float) ($amount ?? 0);
        $rate = currency_rate_from_base($toCurrency);
        return $amount * $rate;
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
