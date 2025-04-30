# Documentación de la Calculadora A/B

## Descripción General
La Calculadora A/B es una herramienta estadística que ayuda a los usuarios a analizar e interpretar los resultados de sus pruebas A/B. Proporciona cálculos precisos de tasas de conversión, significancia estadística e intervalos de confianza.

## Métodos Estadísticos
La calculadora utiliza los siguientes métodos estadísticos:
- Prueba Z para proporciones
- Prueba Chi-cuadrado
- Cálculos de intervalos de confianza

## Instrucciones de Uso

### Requisitos de Entrada
Para cada variante:
- Número de visitantes
- Número de conversiones
- Duración de la prueba (opcional)

### Métricas de Salida
- Tasas de conversión
- Significancia estadística
- Intervalos de confianza
- Tamaño de muestra recomendado
- Análisis de potencia

## Documentación de la API

### Endpoints Disponibles
- `POST /ab-test/calculate`: Calcular resultados de la prueba
- `GET /ab-test/validate`: Validar datos de entrada
- `POST /ab-test/suggest`: Sugerir tamaño de muestra

### Formato de Solicitud
```json
{
    "variant_a": {
        "visitors": 1000,
        "conversions": 100
    },
    "variant_b": {
        "visitors": 1000,
        "conversions": 120
    },
    "confidence_level": 0.95
}
```

### Mejores Prácticas

1. Tamaño de muestra suficiente
2. Ejecutar pruebas por tiempo adecuado
3. Considerar factores externos
4. Documentar condiciones de la prueba

### Solución de Problemas
- Resultados inválidos: Verificar datos de entrada
- Baja significancia: Aumentar tamaño de muestra
- Valores inesperados: Verificar supuestos estadísticos