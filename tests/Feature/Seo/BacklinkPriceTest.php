<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BacklinkPriceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_backlink_price_page()
    {
        $response = $this->get(route('seo.backlink-price'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_analyze_backlink_prices()
    {
        $data = [
            'url' => 'https://example.com',
            'metrics' => ['da', 'pa', 'tf']
        ];

        $response = $this->post(route('seo.backlink-price.analyze'), $data);
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'metrics',
                        'estimated_price',
                        'suggestions'
                    ]
                ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson(route('seo.backlink-price.analyze'), []);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['url']);
    }
}
