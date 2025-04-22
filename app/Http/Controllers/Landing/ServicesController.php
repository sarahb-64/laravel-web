<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Service;
use Inertia\Inertia;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::all();
        
        return Inertia::render('Landing/Services/Index', [
            'services' => $services
        ]);
    }

    public function show(Service $service)
    {
        return Inertia::render('Landing/Services/Show', [
            'service' => $service
        ]);
    }
}