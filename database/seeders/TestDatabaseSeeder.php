<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoProject;
use App\Models\AnswerThePublicQuery;
use App\Models\AbTest;

class TestDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Datos SEO
        SeoProject::factory()->create([
            'name' => 'Test Project',
            'url' => 'https://test-project.com',
            'status' => 'completed'
        ]);

        // Datos AnswerThePublic
        AnswerThePublicQuery::factory()->create([
            'keyword' => 'test keyword',
            'search_engine' => 'google',
            'language' => 'en'
        ]);

        // Datos A/B Test
        AbTest::factory()->create([
            'name' => 'Test A/B',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(7)
        ]);
    }
}