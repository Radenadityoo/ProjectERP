<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\SystemSetting;

class LoadSystemSettings
{
    public function handle(Request $request, Closure $next)
    {
        $settings = Cache::remember('system_settings', 300, function () {
            return SystemSetting::all()->pluck('value', 'key')->toArray();
        });

        view()->share('appSettings', $settings);
        view()->share('defaultCurrency', data_get($settings, 'currency.default', 'IDR'));

        return $next($request);
    }
}
