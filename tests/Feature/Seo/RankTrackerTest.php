<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use App\Models\Seo\RankTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RankTrackerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_rank_tracker_page()
    {
        $response = $this->get(route('seo.rank-tracker'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_track_keyword_rank()
    {
        $data = [
            'keyword' => 'laravel seo',
            'domain' => 'example.com',
            'location' => 'us',
            'language' => 'en'
        ];

        $response = $this->post(route('seo.rank-tracker.track'), $data);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'keyword',
                        'domain',
                        'position',
                        'previous_position',
                        'url',
                        'tracked_at'
                    ]
                ]);
    }

    /** @test */
    public function it_can_get_ranking_history()
    {
        $tracker = RankTracker::factory()->create();
        
        $response = $this->get(route('seo.rank-tracker.history', [
            'keyword' => $tracker->keyword,
            'domain' => $tracker->domain
        ]));

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'keyword',
                            'domain',
                            'position',
                            'tracked_at'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_validates_required_fields_for_tracking()
    {
        $response = $this->postJson(route('seo.rank-tracker.track'), []);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['keyword', 'domain']);
    }
}
