<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     */
    public function index(): Response
    {
        return Inertia::render('Landing/Contact', [
            'title' => 'Contacto',
        ]);
    }

    /**
     * Store a new contact message.
     */
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', '¡Mensaje enviado exitosamente!');
    }
}
