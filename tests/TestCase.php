<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar migraciones
        Artisan::call('migrate');
        
        // Desactivar el manejo de excepciones para ver errores reales
        $this->withoutExceptionHandling();
    }
}