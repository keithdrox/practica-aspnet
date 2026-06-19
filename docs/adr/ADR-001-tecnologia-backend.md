# ADR-001: Selección de Tecnología de Backend

- **Estado:** Aceptado
- **Fecha:** 2026-06-19
- **Autores:** Beltrán Montiel Fred Adrián, Cruz Pérez Justyn Keith, Pallo Pinto Daniel Alejandro

## Contexto

El PFC SportEdu requiere un backend que implemente autenticación, CRUD de
entidades deportivas y controles de seguridad OWASP. El equipo evaluó tres
opciones: PHP 8.x, ASP.NET Core 8 y Java/Spring Boot 3.

## Decisión

Se implementa **PHP 8.2 como tecnología principal** (obligatoria según la
guía) con PDO + PostgreSQL, y **ASP.NET Core 8 como segunda tecnología**,
con Entity Framework Core + PostgreSQL.

## Justificación

- PHP 8.2 con PDO cumple todos los requisitos de seguridad OWASP:
  prepared statements, Argon2id, CSRF manual, XSS con htmlspecialchars().
- El hosting en Ecuador para PHP es más económico ($3–5/mes vs $10–20/mes para .NET).
- ASP.NET Core se eligió como segunda tecnología por su rendimiento superior
  (50.000+ rps con Kestrel) y seguridad integrada (CSRF y XSS automáticos).
- Java/Spring Boot se descartó por mayor complejidad de configuración y
  mayor costo de hosting (JDK 21 requiere VPS con mínimo 512 MB RAM).

## Consecuencias

- El equipo gestiona dos stacks de desarrollo y dos entornos.
- La tabla comparativa del informe documenta las diferencias con criterios técnicos objetivos.
- Se usa PostgreSQL 16 como motor único para ambas tecnologías.