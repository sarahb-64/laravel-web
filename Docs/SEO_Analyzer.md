# Documentación del Analizador SEO

## Descripción General
El Analizador SEO es una herramienta completa diseñada para analizar sitios web y proporcionar información detallada sobre SEO. Consiste en dos componentes principales:
- Servicio de crawler basado en Python
- Interfaz web basada en Laravel

## Instrucciones de Configuración

### Configuración del Crawler Python
1. Configurar entorno virtual de Python:
```bash
python -m venv venv
source venv/bin/activate  # En Windows: venv\Scripts\activate
```

2. Instalar dependencias:
```bash
pip install -r requirements.txt
```

3. Configurar variables de entorno:

- BASE_URL: URL base para el crawler
- MAX_DEPTH: Profundidad máxima de crawling
- QUEUE_URL: URL de la cola de Laravel

### Integración con Laravel
1. Configurar cola en .env:
```bash
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

2. Ejecutar worker de la cola:
```bash
php artisan queue:work
```

## Documentación de la API
### Endpoints Disponibles

- POST/seo/analyse: Iniciar análisis SEO
- GET/ seo/results/{id}: Obtener resultados del análisis SEO
-GET/seo/reports/{id}: Generar informe SEO

### Formato de Solicitud

```json
{
    "url": "https://www.example.com"
    "depth": 2
    "metrics": ["title_length", "meta_description", "keywords", "backlinks"]
}
```

### Ejemplos de Uso

```php
// Ejemplo de inicio de análisis
$analysis = Http::post('/seo/analyze', [
    'url' => 'https://example.com',
    'depth' => 2
]);

// Ejemplo de obtención de resultados
$results = Http::get('/seo/results/' . $analysis->id);
```

### Mejores Prácticas
1. Establecer profundidad de crawling apropiada (1-3 recomendado)
2. Usar limitación de velocidad para evitar sobrecarga del servidor
3. Configurar manejo de errores adecuado
4. Monitorear el rendimiento de la cola regularmente

### Solución de Problemas
- Análisis lento: Verificar estado del worker de la cola y conexión Redis
- Datos faltantes: Verificar permisos del crawler y accesibilidad de la URL
- Problemas de rendimiento: Considerar aumentar los recursos del servidor
