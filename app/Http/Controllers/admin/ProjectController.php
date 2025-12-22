<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Projects;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Projects::with('location')->latest()->paginate(10); 
        
        $locations = Locations::all();
        return view('screens.manageProjectPage', compact('projects', 'locations'));
    }

    public function store(Request $request){}

    public function update(Request $request, $project_id){}

    public function destroy($project_id){}

    public function bulkDestroy(Request $request){}


}
