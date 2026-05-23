<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Event;
use App\Models\Laboratory;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Teacher;
use App\Models\Unit;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'teachers'    => Teacher::count(),
            'departments' => Department::count(),
            'laboratories'=> Laboratory::count(),
            'projects'    => Project::count(),
            'courses'     => Course::count(),
            'units'       => Unit::count(),
            'sectors'     => Sector::count(),
            'events'      => Event::where('start_date', '>=', now())->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
