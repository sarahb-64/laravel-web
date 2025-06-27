# Extensión de Chrome: SEO Meta Tags Analyzer

Extensión de navegador que permite a los usuarios analizar y mejorar los meta tags SEO de cualquier página web directamente desde el navegador.

## Características

- Análisis en tiempo real de meta tags (título, descripción, palabras clave)
- Detección de encabezados (H1, H2, H3)
- Análisis de imágenes (atributos alt, título, dimensiones)
- Verificación de enlaces internos y externos
- Sugerencias de mejora basadas en las mejores prácticas de SEO
- Interfaz intuitiva y fácil de usar

## Requisitos

- Navegador Google Chrome o basado en Chromium (Edge, Brave, etc.)
- Acceso a la API de tu aplicación Laravel
- API Key válida para autenticación

## Instalación

1. **Descargar los archivos de la extensión**
   - Clona este repositorio o descarga los archivos en tu computadora.
   - Asegúrate de que los archivos estén en la carpeta `public/chrome-extension/` de tu proyecto Laravel.

2. **Cargar la extensión en Chrome**
   - Abre Chrome y ve a `chrome://extensions/`
   - Activa el "Modo desarrollador" en la esquina superior derecha
   - Haz clic en "Cargar descomprimida"
   - Selecciona la carpeta `public/chrome-extension` de tu proyecto

3. **Configurar la API Key**
   - Haz clic en el icono de la extensión en la barra de herramientas de Chrome
   - Ingresa tu API Key en el campo correspondiente
   - Haz clic en "Guardar"

## Uso

1. **Análisis rápido**
   - Navega a cualquier página web que desees analizar
   - Haz clic en el icono de la extensión en la barra de herramientas de Chrome
   - Haz clic en "Analizar Meta Tags"

2. **Menú contextual**
   - Haz clic derecho en cualquier página
   - Selecciona "Analizar SEO de la página"

3. **Resultados**
   - La extensión mostrará un resumen de los meta tags actuales
   - Se proporcionarán sugerencias de mejora cuando sea necesario
   - Los elementos analizados se resaltarán en la página

## Configuración de la API

Para que la extensión funcione correctamente, necesitas configurar los siguientes endpoints en tu aplicación Laravel:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/seo/analyze-meta-tags', [\App\Http\Controllers\Seo\MetaTagsController::class, 'analyze']);
});
```

## Personalización

Puedes personalizar la apariencia de la extensión modificando los archivos CSS en la carpeta `public/chrome-extension/`.

## Seguridad

- La extensión solo se comunica con tu dominio configurado
- La API Key se almacena localmente en el navegador del usuario
- Se recomienda usar HTTPS para todas las comunicaciones

## Solución de problemas

- **La extensión no se carga**: Asegúrate de que todos los archivos estén en la carpeta correcta y que el manifest.json sea válido.
- **Error de autenticación**: Verifica que la API Key sea correcta y que el usuario tenga los permisos necesarios.
- **No se detectan meta tags**: Algunas páginas pueden cargar contenido dinámico que no es accesible para la extensión.

## Contribución

Las contribuciones son bienvenidas. Por favor, envía un pull request con tus mejoras.

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo LICENSE para más detalles.
