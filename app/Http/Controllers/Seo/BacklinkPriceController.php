<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Seo\BacklinkPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BacklinkPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Inertia::render('Seo/BacklinkPrices/Index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|url',
            'type' => 'required|in:dofollow,nofollow',
            'price' => 'required|numeric|min:0',
            'da' => 'nullable|integer|min:0|max:100',
            'pa' => 'nullable|integer|min:0|max:100',
            'traffic' => 'nullable|integer|min:0',
            'language' => 'nullable|string|max:2',
            'description' => 'nullable|string|max:1000'
        ]);

        BacklinkPrice::create([
            'domain' => $request->domain,
            'type' => $request->type,
            'price' => $request->price,
            'da' => $request->da,
            'pa' => $request->pa,
            'traffic' => $request->traffic,
            'language' => $request->language,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Precio de backlink agregado exitosamente');
    }

    public function compare(Request $request)
    {
        $query = BacklinkPrice::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('min_da')) {
            $query->where('da', '>=', $request->min_da);
        }

        if ($request->has('min_pa')) {
            $query->where('pa', '>=', $request->min_pa);
        }

        if ($request->has('min_traffic')) {
            $query->where('traffic', '>=', $request->min_traffic);
        }

        if ($request->has('language')) {
            $query->where('language', $request->language);
        }

        $prices = $query->orderBy('price')->get();

        return Inertia::render('Seo/BacklinkPrices/Compare', [
            'prices' => $prices,
            'filters' => $request->all()
        ]);
    }
}