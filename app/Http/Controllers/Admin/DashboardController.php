<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Police\Contract\PoliceContract;

class DashboardController extends Controller
{

    public function dashboard(PoliceContract $police)
    {
        $dashboardUserAcl = $police->getUserAcl(\Auth::user());

        $dashboardUser = User::whereId(\Auth::id())
            ->withCount(['posts', 'comments', 'media'])
            ->first();

        return view('admin.dashboard.index', compact('dashboardUser', 'dashboardUserAcl'));
    }
    
}
