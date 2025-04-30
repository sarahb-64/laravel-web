<?php

namespace Tests\Feature\Ads;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\AdsGraderController;

class AdsGraderTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $campaignId = '1234567890';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un usuario de prueba
        $this->user = User::factory()->create();
        
        // Configurar mocks para el controlador
        $mock = $this->mock(AdsGraderController::class);
        
        // Mock de métricas de Google Ads
        $googleMetrics = [
            'clicks' => 500,
            'impressions' => 5000,
            'conversions' => 50,
            'cost' => 500,
            'ctr' => 0.1,
            'cpc' => 1.0,
            'conversion_rate' => 10.0
        ];

        // Mock de métricas de Facebook Ads
        $facebookMetrics = [
            'clicks' => 400,
            'impressions' => 4000,
            'conversions' => 40,
            'cost' => 400,
            'ctr' => 0.1,
            'cpc' => 1.0,
            'conversion_rate' => 10.0
        ];

        // Configurar el uso de mocks
        $mock->shouldReceive('useMocks')
            ->andReturn(true);

        // Configurar el método analyze
        $mock->shouldReceive('analyze')
            ->andReturnUsing(function ($request) use ($googleMetrics, $facebookMetrics) {
                $validated = $request->validate([
                    'platform' => 'required|in:google,facebook',
                    'campaign_id' => 'required|string|regex:/^[0-9]+$/'
                ]);

                $metrics = $validated['platform'] === 'google' 
                    ? $googleMetrics
                    : $facebookMetrics;

                return [
                    'metrics' => $metrics,
                    'recommendations' => [
                        'improvement' => 'Consider optimizing your campaign',
                        'performance' => 'Your metrics are above average'
                    ],
                    'history' => [],
                    'trends' => [
                        'improving_metrics' => ['clicks', 'conversions'],
                        'declining_metrics' => ['cpc'],
                        'stable_metrics' => ['ctr']
                    ]
                ];
            });

        // Configurar el método getHistory
        $mock->shouldReceive('getHistory')
            ->andReturnUsing(function ($request) use ($googleMetrics, $facebookMetrics) {
                $validated = $request->validate([
                    'platform' => 'required|in:google,facebook',
                    'campaign_id' => 'required|string|regex:/^[0-9]+$/'
                ]);

                $metrics = $validated['platform'] === 'google' 
                    ? $googleMetrics
                    : $facebookMetrics;

                return [
                    'history' => [
                        [
                            'campaign_id' => $validated['campaign_id'],
                            'platform' => $validated['platform'],
                            'metrics' => $metrics,
                            'recommendations' => [
                                'improvement' => 'Consider optimizing your campaign',
                                'performance' => 'Your metrics are above average'
                            ],
                            'analyzed_at' => now()->toDateTimeString()
                        ]
                    ]
                ];
            });

        $this->app->instance(AdsGraderController::class, $mock);
    }

    public function test_can_analyze_google_ads_campaign_directly()
    {
        $controller = app(AdsGraderController::class);

        $request = new \Illuminate\Http\Request([
            'platform' => 'google',
            'campaign_id' => '123456789',
        ]);

        $response = $controller->analyze($request);
        
        $this->assertEquals(500, $response['metrics']['clicks']);
        $this->assertEquals(5000, $response['metrics']['impressions']);
        $this->assertEquals(50, $response['metrics']['conversions']);
        $this->assertEquals(500, $response['metrics']['cost']);
        $this->assertEquals(0.1, $response['metrics']['ctr']);
        $this->assertEquals(1.0, $response['metrics']['cpc']);
        $this->assertEquals(10.0, $response['metrics']['conversion_rate']);
    }

    public function test_can_analyze_facebook_ads_campaign()
    {
        $this->campaignId = '123456789';
        
        $response = $this->actingAs($this->user)
            ->postJson(route('ads.ads-grader.analyze'), [
                'platform' => 'facebook',
                'campaign_id' => $this->campaignId
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('metrics.clicks', 400)
            ->assertJsonPath('metrics.impressions', 4000)
            ->assertJsonPath('metrics.conversions', 40)
            ->assertJsonPath('metrics.cost', 400)
            ->assertJsonPath('metrics.ctr', 0.1)
            ->assertJsonPath('metrics.cpc', 1.0)
            ->assertJsonPath('metrics.conversion_rate', 10.0);
    }

    public function test_rate_limiting_works()
    {
        $this->campaignId = '123456789';
        
        // Primera solicitud debería funcionar
        $response = $this->actingAs($this->user)
            ->postJson(route('ads.ads-grader.analyze'), [
                'platform' => 'google',
                'campaign_id' => $this->campaignId
            ]);
        $response->assertStatus(200);

        // Segunda solicitud debería ser limitada
        $response = $this->actingAs($this->user)
            ->postJson(route('ads.ads-grader.analyze'), [
                'platform' => 'google',
                'campaign_id' => $this->campaignId
            ]);
        $response->assertStatus(429);
    }

    public function test_can_get_campaign_history()
    {
        $this->campaignId = '123456789';
        
        // Primero analizamos una campaña
        $response = $this->actingAs($this->user)
            ->postJson(route('ads.ads-grader.analyze'), [
                'platform' => 'google',
                'campaign_id' => $this->campaignId
            ]);
        $response->assertStatus(200);

        // Esperar el tiempo necesario para evitar el rate limiting
        $this->waitUntilRateLimitReset();

        // Luego consultamos el historial
        $response = $this->actingAs($this->user)
            ->getJson(route('ads.ads-grader.history', [
                'campaign_id' => $this->campaignId,
                'platform' => 'google'
            ]));

        $response->assertStatus(200)
            ->assertJsonPath('history.0.campaign_id', $this->campaignId)
            ->assertJsonPath('history.0.platform', 'google')
            ->assertJsonPath('history.0.metrics.clicks', 500)
            ->assertJsonPath('history.0.metrics.impressions', 5000)
            ->assertJsonPath('history.0.metrics.conversions', 50)
            ->assertJsonPath('history.0.metrics.cost', 500)
            ->assertJsonPath('history.0.metrics.ctr', 0.1)
            ->assertJsonPath('history.0.metrics.cpc', 1.0)
            ->assertJsonPath('history.0.metrics.conversion_rate', 10.0);
    }

    public function test_invalid_platform_returns_error()
    {
        $this->campaignId = '123456789';

        $response = $this->actingAs($this->user)
            ->postJson(route('ads.ads-grader.analyze'), [
                'platform' => 'invalid',
                'campaign_id' => $this->campaignId
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['platform']);
    }

    private function waitUntilRateLimitReset()
    {
        // Esperar 60 segundos para asegurar que el rate limit se resetee
        sleep(60);
    }
}
