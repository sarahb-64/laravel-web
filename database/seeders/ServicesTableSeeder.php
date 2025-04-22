<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Landing\Service;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'name' => 'Desarrollo Web',
            'description' => 'Creación y mantenimiento de sitios web profesionales y aplicaciones web personalizadas.',
            'price' => 500.00,
            'icon' => '💻',
            'features' => [
                'Desarrollo web responsive',
                'Optimización SEO',
                'Integración con sistemas de pago',
                'Mantenimiento y soporte'
            ]
        ]);

        Service::create([
            'name' => 'Diseño Gráfico',
            'description' => 'Diseño creativo y profesional de logotipos, identidad corporativa y material promocional.',
            'price' => 300.00,
            'icon' => '🎨',
            'features' => [
                'Diseño de logotipos',
                'Identidad corporativa',
                'Diseño de packaging',
                'Material promocional'
            ]
        ]);

        Service::create([
            'name' => 'Marketing Digital',
            'description' => 'Estrategias de marketing digital para aumentar la visibilidad y el engagement de tu marca.',
            'price' => 400.00,
            'icon' => '📈',
            'features' => [
                'SEO y SEM',
                'Redes sociales',
                'Email marketing',
                'Análisis de datos'
            ]
        ]);
    }
}