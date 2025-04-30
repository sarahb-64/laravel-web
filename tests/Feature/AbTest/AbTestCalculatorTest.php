<?php

namespace Tests\Feature\AbTest;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbTestCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_ab_test_calculation_works()
    {
        // Caso base de prueba A/B
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ],
            'confidence_level' => 0.95
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'a_rate',
                'b_rate',
                'z_score',
                'p_value',
                'confidence_interval'
            ])
            ->assertJsonPath('a_rate', 10.0)
            ->assertJsonPath('b_rate', 12.0)
            ->assertJsonPath('p_value', 0.0287)
            ->assertJsonPath('confidence_interval', [
                'lower' => 0.005,
                'upper' => 0.035
            ]);
    }

    public function test_invalid_input_is_handled()
    {
        // Casos de entrada inválida
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 0,  // Visitantes inválidos
                'conversions' => 10
            ],
            'variant_b' => [
                'visitors' => 100,
                'conversions' => 20
            ]
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'variant_a.visitors' => ['The variant a.visitors must be at least 1.']
                ]
            ]);

        // Conversiones inválidas
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 100,
                'conversions' => 150  // Más conversiones que visitantes
            ],
            'variant_b' => [
                'visitors' => 100,
                'conversions' => 20
            ]
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'variant_a' => ['The number of conversions cannot exceed the number of visitors.']
                ]
            ]);
    }

    public function test_large_numbers_are_handled()
    {
        // Caso con números grandes
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000000,
                'conversions' => 100000
            ],
            'variant_b' => [
                'visitors' => 1000000,
                'conversions' => 120000
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'a_rate',
                'b_rate',
                'z_score',
                'p_value'
            ])
            ->assertJsonPath('a_rate', 10.0)
            ->assertJsonPath('b_rate', 12.0)
            ->assertJsonPath('p_value', 0.0)
            ->assertJsonPath('confidence_interval.lower', 0.0196)
            ->assertJsonPath('confidence_interval.upper', 0.0204);
    }

    public function test_edge_cases_are_handled()
    {
        // Caso donde ambas variantes tienen 0 conversiones
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 0
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 0
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'a_rate' => 0.0,
                'b_rate' => 0.0,
                'p_value' => 1.0,
                'confidence_interval' => [
                    'lower' => 0.0,
                    'upper' => 0.0
                ]
            ]);

        // Caso donde una variante tiene 0 visitantes
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 0,
                'conversions' => 0
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 100
            ]
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'variant_a.visitors' => ['The variant a.visitors must be at least 1.']
                ]
            ]);
    }

    public function test_confidence_level_is_respected()
    {
        // Diferentes niveles de confianza
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ],
            'confidence_level' => 0.99
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('confidence_interval.lower', 0.002)
            ->assertJsonPath('confidence_interval.upper', 0.038);

        // Nivel de confianza inválido
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ],
            'confidence_level' => 1.5
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'confidence_level' => ['The confidence level must be between 0 and 1.']
                ]
            ]);
    }

    public function test_sample_size_recommendations()
    {
        // Prueba de tamaño de muestra recomendado
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 50,
                'conversions' => 5
            ],
            'variant_b' => [
                'visitors' => 50,
                'conversions' => 6
            ],
            'confidence_level' => 0.95
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('sample_size_recommendation', [
                'current_size' => 50,
                'recommended_size' => 1000,
                'reason' => 'Sample size too small for reliable results'
            ]);

        // Prueba con tamaño de muestra adecuado
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ],
            'confidence_level' => 0.95
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('sample_size_recommendation', [
                'current_size' => 1000,
                'recommended_size' => 1000,
                'reason' => 'Sample size is appropriate'
            ]);
    }

    public function test_power_analysis()
    {
        // Prueba de análisis de potencia
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ],
            'confidence_level' => 0.95
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('power_analysis', [
                'power' => 0.85,
                'minimum_detectable_effect' => 0.02,
                'required_sample_size' => 1000
            ]);

        // Prueba con bajo poder estadístico
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 100,
                'conversions' => 10
            ],
            'variant_b' => [
                'visitors' => 100,
                'conversions' => 12
            ],
            'confidence_level' => 0.95
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('power_analysis', [
                'power' => 0.25,
                'minimum_detectable_effect' => 0.02,
                'required_sample_size' => 1000,
                'warning' => 'Low statistical power: consider increasing sample size'
            ]);
    }

    public function test_multiple_variants()
    {
        // Prueba con múltiples variantes
        $response = $this->post('/ab-test/calculate', [
            'variants' => [
                [
                    'name' => 'A',
                    'visitors' => 1000,
                    'conversions' => 100
                ],
                [
                    'name' => 'B',
                    'visitors' => 1000,
                    'conversions' => 120
                ],
                [
                    'name' => 'C',
                    'visitors' => 1000,
                    'conversions' => 110
                ]
            ],
            'confidence_level' => 0.95
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'comparisons' => [
                    '*' => [
                        'variant_1',
                        'variant_2',
                        'p_value',
                        'confidence_interval'
                    ]
                ]
            ])
            ->assertJsonPath('comparisons.0.variant_1', 'A')
            ->assertJsonPath('comparisons.0.variant_2', 'B')
            ->assertJsonPath('comparisons.0.p_value', 0.0287)
            ->assertJsonPath('comparisons.1.variant_1', 'A')
            ->assertJsonPath('comparisons.1.variant_2', 'C')
            ->assertJsonPath('comparisons.1.p_value', 0.0684);
    }

    public function test_results_are_cached()
    {
        // Prueba de caché de resultados
        $firstResponse = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ]
        ]);

        $secondResponse = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ]
        ]);

        $this->assertEquals(
            $firstResponse->json('p_value'),
            $secondResponse->json('p_value')
        );
    }

    public function test_results_are_exportable()
    {
        // Prueba de exportación de resultados
        $response = $this->post('/ab-test/calculate', [
            'variant_a' => [
                'visitors' => 1000,
                'conversions' => 100
            ],
            'variant_b' => [
                'visitors' => 1000,
                'conversions' => 120
            ]
        ]);

        $exportResponse = $this->get('/ab-test/export/' . $response->json('id'));

        $exportResponse->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'id',
                'variant_a' => [
                    'visitors',
                    'conversions',
                    'conversion_rate'
                ],
                'variant_b' => [
                    'visitors',
                    'conversions',
                    'conversion_rate'
                ],
                'statistics' => [
                    'p_value',
                    'confidence_interval',
                    'sample_size_recommendation'
                ]
            ]);
    }
}
