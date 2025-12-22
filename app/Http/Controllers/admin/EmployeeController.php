<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shifts;
use Illuminate\Http\Request;

use App\Models\User;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::paginate();
        $shifts = Shifts::all();
        return view('screens.manageEmployeePage', compact('employees', 'shifts'));
    }

    public function store(Request $request){}

    public function update(Request $request, $user_id){}

    public function destroy($user_id){}

    public function bulkDestroy(Request $request){}
}
