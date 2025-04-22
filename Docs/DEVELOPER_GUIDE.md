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

## 4. Autenticación y Autorización

### 4.1 Sistema de Roles
- Administrador (Landing Page)
- Usuario (Plataforma SEO)
- Cliente (Ambos)

### 4.2 Permisos
- Gestión de contenido (Landing)
- Acceso a herramientas (SEO)
- Gestión de proyectos (SEO)

## 5. API y Servicios Externos

### 5.1 Integraciones
- Google Search Console
- Google Analytics
- APIs de análisis SEO
- Servicios de correo electrónico

## 6. Rutas y Endpoints

### 6.1 Landing Page
- GET / - Página principal
- GET /services - Servicios
- GET /contact - Contacto

### 6.2 Plataforma SEO
- GET /dashboard - Dashboard
- POST /projects - Crear proyecto
- GET /projects/{id}/analyze - Análisis SEO
- GET /keywords - Gestión de palabras clave
- POST /backlink-prices - Agregar precio
- GET /backlink-prices/compare - Comparar precios

## 7. Testing y Calidad

### 7.1 Pruebas Unitarias
- Validación de datos
- Lógica de negocio
- Servicios externos

### 7.2 Pruebas de Integración
- Flujo de registro
- Análisis SEO
- Seguimiento de rankings

## 8. Despliegue y Mantenimiento

### 8.1 Variables de Entorno
- Configuración de base de datos
- Claves API
- Configuración de correo
- Configuración de caché
- Configuración DataForSEO

### 8.2 Cron Jobs
- Actualización de rankings
- Análisis programados
- Limpieza de datos

Esta documentación técnica proporciona una visión completa de la arquitectura, estructura y mejores prácticas del proyecto. Para mantenerla actualizada, te sugiero:

1. Actualizar la documentación después de cada cambio significativo en la arquitectura.
2. Documentar nuevas funcionalidades a medida que se implementan.
3. Mantener actualizada la lista de dependencias y sus versiones.
4. Incluir ejemplos de código relevantes para cada sección.

