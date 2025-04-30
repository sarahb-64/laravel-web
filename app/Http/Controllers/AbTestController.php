<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AbTestController extends Controller
{
    public function index()
    {
        return view('ab-test.index', [
            'header' => 'Calculadora de Pruebas A/B'
        ]);
    }

    public function calculate(Request $request)
    {
        $this->validate($request, [
            'variant_a_conversions' => 'required|integer',
            'variant_a_visitors' => 'required|integer',
            'variant_b_conversions' => 'required|integer',
            'variant_b_visitors' => 'required|integer',
        ]);

        $data = $request->all();
        $result = $this->calculateAbTest($data);

        // Always return JSON for API requests
        if ($request->isMethod('post') && $request->header('Content-Type') === 'application/json') {
            return response()->json($result);
        }

        return view('ab-test.index', [
            'header' => 'Calculadora de Pruebas A/B',
            'result' => $result
        ]);
    }

    private function calculateAbTest($data)
    {
        $a_rate = $data['variant_a_conversions'] / $data['variant_a_visitors'];
        $b_rate = $data['variant_b_conversions'] / $data['variant_b_visitors'];
        
        $pooled_rate = ($data['variant_a_conversions'] + $data['variant_b_conversions']) / 
                       ($data['variant_a_visitors'] + $data['variant_b_visitors']);
        
        $standard_error = sqrt(
            $pooled_rate * (1 - $pooled_rate) * 
            (1/$data['variant_a_visitors'] + 1/$data['variant_b_visitors'])
        );
        
        $z_score = ($b_rate - $a_rate) / $standard_error;
        $p_value = 2 * (1 - abs($z_score));
        
        return [
            'a_rate' => $a_rate * 100,
            'b_rate' => $b_rate * 100,
            'p_value' => $p_value,
            'confidence_interval' => [
                'lower' => $a_rate - 1.96 * $standard_error,
                'upper' => $a_rate + 1.96 * $standard_error
            ],
            'is_significant' => $p_value < 0.05
        ];
    }
}