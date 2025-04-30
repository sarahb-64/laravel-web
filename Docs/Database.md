# Documentación de la Base de Datos

## Vista General del Esquema

### Tablas de Análisis SEO
```sql
CREATE TABLE seo_projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    url VARCHAR(255),
    status ENUM('pendiente', 'procesando', 'completado', 'fallido'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE seo_metrics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED,
    metric_name VARCHAR(50),
    value TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES seo_projects(id)
);

CREATE TABLE seo_backlinks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED,
    source_url VARCHAR(255),
    target_url VARCHAR(255),
    anchor_text TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES seo_projects(id)
);

CREATE TABLE seo_keywords (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED,
    keyword VARCHAR(255),
    search_volume INTEGER,
    competition DECIMAL(5,2),
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES seo_projects(id)
);

CREATE TABLE seo_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED,
    report_type VARCHAR(50),
    content TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES seo_projects(id)
);
```

### Tablas de AnswerThePublic
```sql
CREATE TABLE answer_the_public_queries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255),
    search_engine VARCHAR(50),
    language VARCHAR(10),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE answer_the_public_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    query_id BIGINT UNSIGNED,
    suggestion TEXT,
    type VARCHAR(50),
    created_at TIMESTAMP,
    FOREIGN KEY (query_id) REFERENCES answer_the_public_queries(id)
);

CREATE TABLE answer_the_public_cache (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255),
    search_engine VARCHAR(50),
    data JSON,
    expires_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tablas de Pruebas A/B
```sql
CREATE TABLE ab_test (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    url VARCHAR(255),
    status ENUM('pendiente', 'procesando', 'completado', 'fallido'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE ab_test_variants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    test_id BIGINT UNSIGNED,
    variant_name VARCHAR(255),
    created_at TIMESTAMP,
    FOREIGN KEY (test_id) REFERENCES ab_test(id)
);

CREATE TABLE ab_test_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    test_id BIGINT UNSIGNED,
    variant_id BIGINT UNSIGNED,
    metric_name VARCHAR(50),
    value TEXT,
    created_at TIMESTAMP,
    FOREIGN KEY (test_id) REFERENCES ab_test(id),
    FOREIGN KEY (variant_id) REFERENCES ab_test_variants(id)
);
```

### Relaciones entre Tablas
- Un proyecto SEO puede tener múltiples métricas, backlinks, palabras clave y reportes
- Una consulta de AnswerThePublic puede tener múltiples sugerencias
- Una prueba A/B puede tener múltiples variantes y resultados

### Ejemplos de Consultas
SEO
```sql
-- Obtener métricas de un proyecto
SELECT m.* 
FROM seo_metrics m
WHERE m.project_id = ?
ORDER BY m.created_at DESC;

-- Obtener palabras clave con mayor volumen de búsqueda
SELECT k.* 
FROM seo_keywords k
WHERE k.project_id = ?
ORDER BY k.search_volume DESC
LIMIT 10;
```

AnswerThePublic
```sql
-- Obtener sugerencias para una consulta
SELECT r.* 
FROM answer_the_public_results r
WHERE r.query_id = ?
ORDER BY r.created_at DESC;

-- Verificar caché antes de hacer nuevas consultas
SELECT c.* 
FROM answer_the_public_cache c
WHERE c.keyword = ?
AND c.expires_at > NOW();
```

A/B Tests
```sql
-- Obtener resultados de una prueba
SELECT r.*, v.name as variant_name
FROM ab_results r
JOIN ab_variants v ON r.variant_id = v.id
WHERE r.test_id = ?
ORDER BY r.created_at DESC;

-- Calcular estadísticas de una prueba
SELECT 
    t.name,
    SUM(r.visitors) as total_visitors,
    SUM(r.conversions) as total_conversions,
    AVG(r.conversion_rate) as avg_conversion_rate
FROM ab_tests t
JOIN ab_results r ON t.id = r.test_id
WHERE t.id = ?
GROUP BY t.id;
```

## Mantenimiento de la Base de Datos
- Monitorear el tamaño de la base de datos
- Limpiar datos antiguos regularmente
- Optimizar índices para consultas frecuentes
- Realizar copias de seguridad periódicas
- Verificar integridad de datos

### Solución de Problemas
- Rendimiento lento:
  - Verificar índices
  - Optimizar consultas
  - Considerar particionamiento
- Espacio insuficiente:
  - Limpiar datos antiguos
  - Comprimir datos históricos
  - Considerar almacenamiento en frío
- Integridad de datos:
  - Verificar restricciones de clave foránea
  - Revisar registros duplicados
  - Monitorear errores de inserción
