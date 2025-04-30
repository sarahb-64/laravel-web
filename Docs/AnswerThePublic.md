# Documentación de AnswerThePublic

## Descripción General
AnswerThePublic es una herramienta que ayuda a los usuarios a descubrir preguntas populares y búsquedas relacionadas con sus palabras clave. Integra los principales motores de búsqueda para proporcionar información valiosa para la creación de contenido y SEO.

## Instrucciones de Configuración

### Configuración de Laravel
1. Agregar claves API de los motores de búsqueda en [.env](cci:7://file:///c:/Users/sarah/OneDrive/Escritorio/Practicas%20APPYWEB/Laravel%20Project/laravel-web/.env:0:0-0:0):

```env
GOOGLE_API_KEY=your_api_key
BING_API_KEY=your_api_key
```

2. Configurar caché:
```env
CACHE_DRIVER=redis
```

## Documentación de la API

### Endpoints Disponibles
- `GET /answer-the-public/suggestions`: Obtener sugerencias de autocompletado
- `POST /answer-the-public/analyze`: Analizar relaciones de palabras clave
- `GET /answer-the-public/history`: Ver consultas anteriores

### Formato de Solicitud
```json
{
    "keyword": "palabra clave",
    "engine": "google",  // o "bing"
    "language": "es"
}
```

### Ejemplos de uso

```php
// Obtener sugerencias
$suggestions = Http::get('/answer-the-public/suggestions', [
    'keyword' => 'desarrollo web'
]);

// Analizar relaciones de palabras clave
$analysis = Http::post('/answer-the-public/analyze', [
    'keywords' => ['desarrollo web', 'programación']
]);
```

### Mejores Prácticas

1. Usar palabras clave específicas para mejores resultados
2. Combinar múltiples motores de búsqueda para datos más completos
3. Cachear resultados para reducir llamadas a la API
4. Monitorear límites de uso de la API

### Solución de Problemas
- Sin sugerencias: verificar claves API y conectividad con los motores de búsqueda.
- Respuesta lenta: verificar configuración de caché
- Límites de tasa: implementar manejo de errores apropiado