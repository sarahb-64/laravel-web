<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AIWriterController extends Controller
{
    public function index()
    {
        return view('ai-writer.index');
    }

    public function generateContent(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|min:3',
            'tone' => 'required|string',
            'length' => 'required|integer|min:100|max:2000',
        ]);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente experto en escritura creativa. Genera contenido original basado en el tema proporcionado.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Genera un contenido sobre el tema: {$validated['topic']} con un tono {$validated['tone']} y aproximadamente {$validated['length']} palabras."
                    ]
                ]
            ]);

            return response()->json([
                'success' => true,
                'content' => $response->choices[0]->message->content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al generar el contenido. Por favor, intenta nuevamente.'
            ], 500);
        }
    }
}