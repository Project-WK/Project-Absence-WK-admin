<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shifts;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $shifts = Shifts::paginate();
        return view('screens.manageShiftPage', compact('shifts'));
    }

    public function store(Request $request){}

    public function update(Request $request, $shift_id){}

    public function destroy($shift_id){}

    public function bulkDestroy(Request $request){}
}
