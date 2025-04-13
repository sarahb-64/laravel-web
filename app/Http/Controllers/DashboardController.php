<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = [
            'can_view_dashboard' => $user->hasRole('admin'),
            'can_manage_users' => $user->hasRole('admin'),
            'can_manage_roles' => $user->hasRole('admin'),
            'can_manage_settings' => $user->hasRole('admin'),
        ];

        return Inertia::render('Dashboard', [
            'permissions' => $permissions,
            'user' => $user
        ]);
    }
}