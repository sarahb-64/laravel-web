<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UbersuggestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario
        $this->user = User::factory()->create();
        
        // Asignar rol de administrador
        $this->user->assignRole('admin');
        
        $this->actingAs($this->user);        
        
        // Mock the HTTP client to avoid real API calls
        Http::fake([
            'https://api.ubersuggest.io/*' => Http::response([
                'data' => [
                    'keyword' => 'laravel seo',
                    'volume' => 1000,
                    'difficulty' => 65,
                    'cpc' => 1.5,
                    'competition' => 'HIGH',
                    'trends' => [
                        ['month' => '2023-01', 'volume' => 900],
                        ['month' => '2023-02', 'volume' => 950],
                        ['month' => '2023-03', 'volume' => 1000],
                    ],
                    'related_keywords' => [
                        ['keyword' => 'laravel seo package', 'volume' => 500],
                        ['keyword' => 'laravel seo best practices', 'volume' => 400],
                    ]
                ]
            ], 200)
        ]);
    }

    /** @test */
    public function it_can_access_ubersuggest_page()
    {
        $response = $this->get(route('seo.ubersuggest'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_analyze_keyword_with_ubersuggest()
    {
        $data = [
            'keyword' => 'laravel seo',
            'country' => 'us',
            'language' => 'en'
        ];

        $response = $this->post(route('seo.ubersuggest.analyze'), $data);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'keyword',
                        'volume',
                        'difficulty',
                        'cpc',
                        'competition',
                        'trends' => [
                            '*' => ['month', 'volume']
                        ],
                        'related_keywords' => [
                            '*' => ['keyword', 'volume']
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson(route('seo.ubersuggest.analyze'), []);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['keyword']);
    }

    /** @test */
    public function it_handles_api_errors_gracefully()
    {
        // Mock an error response from the API
        Http::fake([
            'https://api.ubersuggest.io/*' => Http::response([
                'error' => 'API key is invalid'
            ], 401)
        ]);

        $data = [
            'keyword' => 'laravel seo',
            'country' => 'us',
            'language' => 'en'
        ];

        $response = $this->postJson(route('seo.ubersuggest.analyze'), $data);
        
        $response->assertStatus(500)
                ->assertJson([
                    'success' => false,
                    'message' => 'Failed to fetch data from Ubersuggest API'
                ]);
    }
}
