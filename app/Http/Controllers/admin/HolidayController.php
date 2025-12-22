<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Holidays;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $holidays = Holidays::paginate();
        return view('screens.manageHolidayPage', compact('holidays'));
    }

    public function store(Request $request){}

    public function update(Request $request, $holiday_id){}

    public function destroy($holiday_id){}

    public function bulkDestroy(Request $request){}
}
