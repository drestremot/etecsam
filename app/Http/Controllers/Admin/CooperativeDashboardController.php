<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\CooperativeFinanceSummary;

class CooperativeDashboardController extends Controller
{
    public function index()
    {
        return view('admin.cooperative-dashboard.index', CooperativeFinanceSummary::compute());
    }
}
