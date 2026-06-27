<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ApmFinanceSummary;

class ApmDashboardController extends Controller
{
    public function index()
    {
        return view('admin.apm-dashboard.index', ApmFinanceSummary::compute());
    }
}
