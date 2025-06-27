<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AnswerThePublicController extends Controller
{
    /**
     * Muestra la interfaz de AnswerThePublic
     */
    public function index()
    {
        return Inertia::render('AnswerThePublic/Index');
    }

    /**
     * Obtiene sugerencias de AnswerThePublic
     */
    public function getSuggestions(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
        ]);

        // Aquí iría la lógica para obtener las sugerencias
        // Por ahora, devolvemos un array vacío
        return response()->json([
            'suggestions' => []
        ]);
    }
}