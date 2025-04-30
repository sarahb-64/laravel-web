<?php

namespace Tests\Feature\AnswerThePublic;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerThePublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_keyword_suggestions_are_returned()
    {
        $response = $this->get('/answer-the-public/suggestions', [
            'keyword' => 'web development'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'suggestion',
                        'type'
                    ]
                ]
            ]);
    }

    public function test_cache_is_working()
    {
        $firstResponse = $this->get('/answer-the-public/suggestions', [
            'keyword' => 'test'
        ]);

        $secondResponse = $this->get('/answer-the-public/suggestions', [
            'keyword' => 'test'
        ]);

        $this->assertEquals(
            $firstResponse->json('data'),
            $secondResponse->json('data')
        );
    }

    public function test_invalid_keywords_are_handled()
    {
        $response = $this->get('/answer-the-public/suggestions', [
            'keyword' => ''
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Keyword is required'
            ]);
    }

    public function test_api_rate_limiting_works()
    {
        // Simulate multiple requests in a short time
        for ($i = 0; $i < 10; $i++) {
            $response = $this->get('/answer-the-public/suggestions', [
                'keyword' => 'test-' . $i
            ]);
        }

        $lastResponse = $this->get('/answer-the-public/suggestions', [
            'keyword' => 'test-final'
        ]);

        $lastResponse->assertStatus(429)
            ->assertJson([
                'status' => 'error',
                'message' => 'Too many requests'
            ]);
    }
}