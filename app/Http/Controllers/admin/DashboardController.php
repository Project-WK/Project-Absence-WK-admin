<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Projects;
use App\Models\Attendances;

class DashboardController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today();

        $stats = [
            'total_employees' => User::where('role', 'employee')->count(),
            'pending_verifications' => User::where('status', 'pending')->count(),
            'projects_active' => Projects::where('status', 'ongoing')->count(),
            'projects_unpaid' => Projects::where('payment_status', '!=', 'paid')->count(),
            'attendance_today' => Attendances::whereDate('clock_in_time', $today)->count(),
            'late_today' => Attendances::whereDate('clock_in_time', $today)
                                    ->where('status_attendance', 'late')
                                    ->count(),
        ];

        return view('screens.dashboardPage', compact('stats'));
    }
}
