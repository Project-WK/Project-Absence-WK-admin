<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request){
        $date = $request->input('date', date('Y-m-d'));
    
    // Ambil data absensi berdasarkan tanggal
        $attendances = Attendances::with(['user', 'location'])
                        ->whereDate('created_at', $date)
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Data untuk Map (Hanya yang memiliki koordinat)
        $mapData = $attendances->whereNotNull('latitude')->map(function($item){
            return [
                'name' => $item->user->name,
                'time' => \Carbon\Carbon::parse($item->clock_in_time)->format('H:i'),
                'status' => $item->status, // 'on_time', 'late'
                'lat' => $item->latitude,
                'lng' => $item->longitude,
                'avatar' => substr($item->user->name, 0, 1)
            ];
        });
        return view('screens.manageAttendancePage', compact('attendances', 'date', 'mapData'));
    }
}
