<?php

namespace Database\Seeders;

use App\Models\Landing\Skill;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    public function run()
    {
        Skill::create([
            'name' => 'PHP',
            'description' => 'Desarrollo web con PHP y Laravel',
            'percentage' => 90,
            'icon' => 'php'
        ]);

        Skill::create([
            'name' => 'JavaScript',
            'description' => 'Desarrollo front-end con Vue.js',
            'percentage' => 85,
            'icon' => 'javascript'
        ]);

        Skill::create([
            'name' => 'MySQL',
            'description' => 'Base de datos y consultas optimizadas',
            'percentage' => 80,
            'icon' => 'mysql'
        ]);

        Skill::create([
            'name' => 'HTML/CSS',
            'description' => 'Desarrollo web responsivo',
            'percentage' => 95,
            'icon' => 'html'
        ]);

        Skill::create([
            'name' => 'Git',
            'description' => 'Control de versiones y colaboración',
            'percentage' => 90,
            'icon' => 'git'
        ]);
    }
}