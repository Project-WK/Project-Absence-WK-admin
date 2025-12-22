<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\User;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function index(Request $request){
        $locations = Locations::with('leader')->latest()->paginate(10);
        
        // Ambil User yang Role-nya 'leader' untuk dropdown
        $leaders = User::where('role', 'leader')->get(); 

        return view('screens.manageLocationPage', compact('locations', 'leaders'));
    }

    public function store(Request $request){}

    public function update(Request $request, $location_id){}

    public function destroy($location_id){}

    public function bulkDestroy(Request $request){}
}
