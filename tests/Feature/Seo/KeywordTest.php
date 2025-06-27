<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeywordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_keyword_research_page()
    {
        $response = $this->get(route('seo.keyword-research'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_analyze_keywords()
    {
        $data = [
            'keyword' => 'laravel seo',
            'country' => 'us',
            'language' => 'en'
        ];

        $response = $this->post(route('seo.keyword-research.analyze'), $data);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'keyword',
                        'volume',
                        'difficulty',
                        'cpc',
                        'competition',
                        'trends',
                        'related_keywords'
                    ]
                ]);
    }

    /** @test */
    public function it_validates_keyword_required()
    {
        $response = $this->postJson(route('seo.keyword-research.analyze'), [
            'country' => 'us',
            'language' => 'en'
        ]);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['keyword']);
    }
}
