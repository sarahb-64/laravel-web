<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index()
    {
        $keywords = Keyword::all();
        return view('keywords.index', compact('keywords'));
    }

    public function create()
    {
        return view('keywords.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'priority' => 'required|integer',
        ]);

        Keyword::create($request->all());

        return redirect()->route('keywords.index')->with('success', 'Palabra clave agregada correctamente.');
    }

    public function edit($id)
    {
        $keyword = Keyword::findOrFail($id);
        return view('keywords.edit', compact('keyword'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'priority' => 'required|integer',
        ]);

        $keyword = Keyword::findOrFail($id);
        $keyword->update($request->all());

        return redirect()->route('keywords.index')->with('success', 'Palabra clave actualizada correctamente.');
    }

    public function destroy($id)
    {
        Keyword::findOrFail($id)->delete();
        return redirect()->route('keywords.index')->with('success', 'Palabra clave eliminada correctamente.');
    }
}

