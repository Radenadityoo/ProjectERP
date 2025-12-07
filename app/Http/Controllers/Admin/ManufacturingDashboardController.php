<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacturing;
use Carbon\Carbon;

class ManufacturingDashboardController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Manufacturing Dashboard',
            'showViewSwitch' => false,
        ];

        $total_mos = Manufacturing::count();
        $in_progress = Manufacturing::whereIn('status', ['draft', 'confirmed'])->count();
        $completed_mos = Manufacturing::where('status', 'done')->count();
        $late_mos = Manufacturing::whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->where('status', '!=', 'done')
            ->count();

        $recent_mos = Manufacturing::with('product')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('admin.manufacturing.dashboard', [
            'commandbar' => $commandbar,
            'stats' => [
                'total_mos' => $total_mos,
                'in_progress' => $in_progress,
                'completed_mos' => $completed_mos,
                'late_mos' => $late_mos,
            ],
            'recent_mos' => $recent_mos,
        ]);
    }

    /**
     * Return manufacturing order trend data for Chart.js (last 6 months)
     */
    public function trendsJson()
    {
        $months = collect(range(0, 5))
            ->map(fn($i) => now()->subMonths($i)->format('Y-m'))
            ->reverse()
            ->values();

        $counts = Manufacturing::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->format('M'));
        $data = $months->map(fn($m) => (int)($counts[$m] ?? 0));

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
