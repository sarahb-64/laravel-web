# Guía Técnica para Desarrolladores

## 1. Estructura del Proyecto

### 1.1 Componentes Principales

#### 1.1.1 Landing Page Personal
- Página de presentación profesional
- Secciones:
  - Hero/Portada
  - Sobre mí
  - Habilidades
  - Servicios
  - Portafolio
  - Contacto

#### 1.1.2 Plataforma de Herramientas SEO
- Sistema de autenticación
- Gestión de proyectos
- Conjunto de herramientas SEO
- Dashboard de análisis

### 1.2 Arquitectura General
El proyecto utiliza Laravel 11 como framework principal, con las siguientes características técnicas:

- PHP 8.1 o superior
- Laravel Framework ^11.0
- Base de datos: MySQL/MariaDB
- Frontend: Inertia.js + Vue.js
- Autenticación: Laravel Fortify + Jetstream

## 2. Estructura de Directorios

### 2.1 Componentes de la Landing Page
app/
├── Http/
│   └── Controllers/
│       ├── Landing/
│       │   ├── HomeController.php
│       │   ├── SkillsController.php
│       │   ├── ServicesController.php
│       │   └── ContactController.php
│       └── Pages/
├── Models/
│   └── Landing/
│       ├── Skill.php
│       ├── Service.php
│       ├── Project.php
│       └── ContactMessage.php
└── Views/
    └── landing/
        ├── hero.blade.php
        ├── about.blade.php
        ├── skills.blade.php
        ├── services.blade.php
        ├── portfolio.blade.php
        ├── contact.blade.php
        └── layout.blade.php


### 2.2 Componentes de la Plataforma SEO
app/
├── Http/Controllers/
│   └── Seo/
│       ├── DashboardController.php
│       ├── RankTrackerController.php
│       ├── BacklinkPriceController.php
│       └── KeywordController.php
├── Models/
│   └── Seo/
│       ├── RankResult.php
│       ├── BacklinkPrice.php
│       └── Keyword.php
└── Views/
    └── seo/


## 3. Base de Datos

### 3.1 Tablas Principales

#### 3.1.1 Landing Page
- skills
- services
- projects
- testimonials

#### 3.1.2 Plataforma SEO
- seo_projects
- keywords
- analyses
- rank_history
- backlink_prices


## 4. Servicios Externos
### 4.1 Servicios de Análisis SEO

#### 4.1.1 Arquitectura del Servicio

El servicio de análisis SEO está compuesto por dos partes principales:

1. **Servicio Crawler Python**
   - Ubicación: `seo_analyzer/`
   - Responsabilidad: Realizar el análisis técnico de sitios web
   - Componentes principales:
     - `crawler.py`: Implementación del crawler y análisis SEO
     - `config.py`: Configuración y variables de entorno
     - `main.py`: Punto de entrada del servicio
     - `requirements.txt`: Dependencias Python

2. **Integración Laravel**
   - Ubicación: `app/`
   - Responsabilidad: Manejo de colas y procesamiento asíncrono
   - Componentes principales:
     - `App\Jobs\SeoAnalysisJob`: Procesamiento en segundo plano
     - `App\Models\Seo\SeoAnalysis`: Modelo de datos
     - `App\Http\Controllers\Seo\SeoAnalyzerController`: Controlador API

#### 4.1.2 Configuración del Servicio

1. **Requisitos del Sistema**
   - Python 3.8+
   - Virtualenv
   - Dependencias Python (requests, beautifulsoup4, selenium)
   - Laravel 11.0+
   - Base de datos MySQL/MariaDB

2. **Configuración Python**
   - Crear entorno virtual:
     ```bash
     python -m venv venv
     source venv/bin/activate  # En Windows: venv\Scripts\activate
     pip install -r requirements.txt
     ```
   - Variables de entorno en `seo_analyzer/.env`:
     ```
     BASE_URL=http://localhost:8000
     ```

3. **Configuración Laravel**
   - Variables de entorno en `.env`:
     ```
     QUEUE_CONNECTION=database
     SEO_ANALYZER_ENDPOINT=http://localhost:8000/api/seo/analysis
     ```
   - Migraciones:
     ```bash
     php artisan migrate
     ```

#### 4.1.3 Endpoints API

1. **Iniciar Análisis SEO**
   ```bash
   POST /api/seo/analyze
   Content-Type: application/json

   {
       "url": "[https://ejemplo.com](https://ejemplo.com)"
   }
Respuesta:

   {
       "message": "Análisis SEO iniciado",
       "analysis_id": 1
   }

2. **Obtener Estado del Análisis**
   ```bash
   GET /api/seo/analysis/{analysis_id}
   ```
Respuesta:
{
    "url": "https://ejemplo.com",
    "page_load_time": 1.29,
    "meta_title": "Título de la página",
    "meta_description": "Descripción de la página",
    "heading_structure": {
        "h1": 1,
        "h2": 2,
        "h3": 0
    },
    "image_alt_coverage": 85.0,
    "internal_links": ["https://ejemplo.com/pagina"],
    "external_links": ["https://externo.com"],
    "mobile_friendly": true,
    "ssl_enabled": true,
    "status": "completed",
    "error_message": null
}

#### 4.1.4 Métricas de Análisis
1. Tiempo de Carga
   - Tiempo total de carga de la página
   - Tiempo de respuesta del servidor

2. Meta Tags
   - Título de la página
   - Descripción meta
   - Palabras clave
   - Validación de caracteres

3. Estructura de Contenido
   - Número y jerarquía de encabezados (H1-H6)
   - Longitud de encabezados
   - Densidad de palabras clave

4. Optimización de Imágenes
   - Presencia de atributos alt
   - Tamaño de imágenes
   - Formato de imágenes

5. Enlaces
   - Número de enlaces internos
   - Número de enlaces externos
   - Detección de enlaces rotos
   - Uso de atributo rel="nofollow"

6. Compatibilidad Móvil
   - Ancho de la vista
   - Tamaño de texto
   - Espaciado de elementos
   - Uso de media queries

7. Seguridad
   - Estado del certificado SSL
   - Uso de HTTPS
   - Detección de inyecciones

#### 4.1.5 Manejo de Errores
1. Tipos de Errores
   - Errores de red
   - URLs inválidas
   - Errores de timeout
   - Errores de parsing
   - Errores de autenticación

2. Respuestas de Error
{
    "status": "failed",
    "error_message": "Descripción del error"
}

#### 4.1.6 Monitoreo y Logging
1. Métricas de Proceso
   - Tiempo de procesamiento
   - Estado de la cola
   - Número de análisis completados
   - Errores detectados
2. Logs
   - Logs de errores
   - Logs de procesamiento
   - Logs de acceso
   - Logs de rendimiento
3. Alertas
   - Errores críticos
   - Tiempo de respuesta excesivo
   - Fallos en el servicio
   - Problemas de conectividad
#### 4.1.7 Mejores Prácticas
1. Uso del Servicio
   - Limitar el número de análisis simultáneos
   - Implementar rate limiting
   - Validar URLs antes de procesar
   - Manejar errores de manera robusta
   
2. Mantenimiento
   - Actualizar dependencias regularmente
   - Monitorear rendimiento
   - Limpiar logs antiguos
   - Mantener versiones compatibles

3. Seguridad
   - Validar todas las entradas
   - Limpiar datos sensibles
   - Implementar autenticación
   - Usar HTTPS para comunicaciones

## 5. Autenticación y Autorización

### 5.1 Sistema de Roles
- Administrador (Landing Page)
- Usuario (Plataforma SEO)
- Cliente (Ambos)

### 5.2 Permisos
- Gestión de contenido (Landing)
- Acceso a herramientas (SEO)
- Gestión de proyectos (SEO)

## 6. API y Servicios Externos

### 6.1 Integraciones
- Google Search Console
- Google Analytics
- APIs de análisis SEO
- Servicios de correo electrónico

## 7. Rutas y Endpoints

### 7.1 Landing Page
- GET / - Página principal
- GET /services - Servicios
- GET /contact - Contacto

### 7.2 Plataforma SEO
- GET /dashboard - Dashboard
- POST /projects - Crear proyecto
- GET /projects/{id}/analyze - Análisis SEO
- GET /keywords - Gestión de palabras clave
- POST /backlink-prices - Agregar precio
- GET /backlink-prices/compare - Comparar precios

## 8. Testing y Calidad

### 8.1 Pruebas Unitarias
- Validación de datos
- Lógica de negocio
- Servicios externos

### 8.2 Pruebas de Integración
- Flujo de registro
- Análisis SEO
- Seguimiento de rankings

## 9. Despliegue y Mantenimiento

### 9.1 Variables de Entorno
- Configuración de base de datos
- Claves API
- Configuración de correo
- Configuración de caché
- Configuración DataForSEO

### 9.2 Cron Jobs
- Actualización de rankings
- Análisis programados
- Limpieza de datos

Esta documentación técnica proporciona una visión completa de la arquitectura, estructura y mejores prácticas del proyecto. Para mantenerla actualizada, te sugiero:

1. Actualizar la documentación después de cada cambio significativo en la arquitectura.
2. Documentar nuevas funcionalidades a medida que se implementan.
3. Mantener actualizada la lista de dependencias y sus versiones.
4. Incluir ejemplos de código relevantes para cada sección.

