<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Mock data - Replace with actual database queries
        // Note: This is sample data - in production, you would query your database
        $projects = [
            [
                'id' => 1,
                'name' => 'Proyecto 1',
                'description' => 'Descripción del proyecto 1'
            ],
            [
                'id' => 2,
                'name' => 'Proyecto 2',
                'description' => 'Descripción del proyecto 2'
            ]
        ];

        // Sample tasks data
        $tasks = [
            [
                'id' => 1,
                'title' => 'Tarea 1',
                'project' => ['id' => 1, 'name' => 'Proyecto 1'],
                'status' => 'pending' // Possible statuses: pending, in_progress, completed
            ],
            [
                'id' => 2,
                'title' => 'Tarea 2',
                'project' => ['id' => 2, 'name' => 'Proyecto 2'],
                'status' => 'in_progress'
            ]
        ];

        // Sample activity log data
        $activities = [
            [
                'id' => 1,
                'description' => 'Actividad 1 realizada',
                'created_at' => 'Hace 1 hora' // Format: X time ago
            ],
            [
                'id' => 2,
                'description' => 'Actividad 2 realizada',
                'created_at' => 'Hace 2 horas'
            ]
        ];

        // Return the Inertia response with the dashboard data
        return Inertia::render('Dashboard', [
            'projects' => $projects,
            'tasks' => $tasks,
            'activities' => $activities
        ]);
    }
}