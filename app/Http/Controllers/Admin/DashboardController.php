<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $commandbar = [
            'title' => 'Dashboard',
            'count' => 0,
            'showViewSwitch' => false,
        ];

        return view('admin.dashboard', compact('commandbar'));
    }
}
