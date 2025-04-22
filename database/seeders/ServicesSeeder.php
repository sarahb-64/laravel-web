<?php

namespace Database\Seeders;

use App\Models\Landing\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'name' => 'Desarrollo Web',
            'description' => 'Creación de sitios web modernos y responsivos',
            'icon' => 'web-development',
            'price' => 1500
        ]);

        Service::create([
            'name' => 'Optimización SEO',
            'description' => 'Mejorar el posicionamiento en buscadores',
            'icon' => 'seo',
            'price' => 2000
        ]);

        Service::create([
            'name' => 'Mantenimiento',
            'description' => 'Actualizaciones y mejoras continuas',
            'icon' => 'maintenance',
            'price' => 1000
        ]);

        Service::create([
            'name' => 'Consultoría',
            'description' => 'Asesoramiento técnico y estrategia',
            'icon' => 'consulting',
            'price' => 1200
        ]);

        Service::create([
            'name' => 'Diseño UI/UX',
            'description' => 'Interfaces intuitivas y atractivas',
            'icon' => 'design',
            'price' => 1800
        ]);
    }
}