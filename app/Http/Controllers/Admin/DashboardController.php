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
use App\Models\ApmManager;
use App\Models\ApmReport;
use App\Models\ApmIncome;
use App\Models\ApmExpense;
use App\Models\CooperativeManager;
use App\Models\CooperativeMember;
use App\Models\CooperativeReport;
use App\Models\CooperativeSale;
use App\Models\CooperativeExpense;
use App\Models\CooperativeHousingTenant;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'teachers'       => Teacher::count(),
            'departments'    => Department::count(),
            'laboratories'   => Laboratory::count(),
            'projects'       => Project::count(),
            'courses'        => Course::count(),
            'units'          => Unit::count(),
            'sectors'        => Sector::count(),
            'events'         => Event::where('start_date', '>=', now())->count(),
            'apm_managers'   => ApmManager::where('is_active', true)->count(),
            'apm_reports'    => ApmReport::count(),
            'apm_incomes'    => ApmIncome::count(),
            'apm_expenses'   => ApmExpense::count(),
            'coop_managers'  => CooperativeManager::where('is_active', true)->count(),
            'coop_members'   => CooperativeMember::where('is_active', true)->count(),
            'coop_reports'   => CooperativeReport::count(),
            'coop_sales'     => CooperativeSale::count(),
            'coop_expenses'  => CooperativeExpense::count(),
            'coop_tenants'   => CooperativeHousingTenant::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
