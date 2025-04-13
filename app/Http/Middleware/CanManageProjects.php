<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CanManageProjects
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasPermissionTo('manage-projects')) {
            abort(403, 'No tienes permisos para gestionar proyectos SEO');
        }

        return $next($request);
    }
}
