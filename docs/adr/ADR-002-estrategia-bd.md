# ADR-002: Estrategia de Base de Datos

- **Estado:** Aceptado
- **Fecha:** 2026-06-19
- **Autores:** Beltrán Montiel Fred Adrián, Cruz Pérez Justyn Keith, Pallo Pinto Daniel Alejandro

## Contexto

El PFC requiere persistencia relacional para usuarios y productos. Se evaluaron
MySQL/MariaDB y PostgreSQL como motores, y acceso directo (PDO/ADO.NET)
vs ORM (Eloquent/EF Core).

## Decisión

Se utiliza **PostgreSQL 16** como motor único para ambas tecnologías
(PHP y ASP.NET Core), con **PDO + patrón Repository** en PHP y
**Entity Framework Core 8** en ASP.NET Core.

## Justificación

- PostgreSQL soporta mejor la concurrencia y tiene tipos de datos más ricos
  que MySQL (JSONB, arrays, rangos), relevantes para futuras expansiones del PFC.
- Usar el mismo motor en ambas tecnologías reduce la complejidad operativa:
  una sola instancia PostgreSQL sirve a ambas aplicaciones.
- PDO con ATTR_EMULATE_PREPARES = false garantiza prepared statements reales,
  previniendo SQL Injection de forma verificable.
- EF Core genera automáticamente consultas parametrizadas desde LINQ,
  sin riesgo de concatenación de strings SQL.
- El patrón Repository con interfaz permite sustituir PostgreSQL por otro
  motor (SQLite para pruebas) sin modificar la lógica de negocio.

## Consecuencias

- Requiere la extensión pdo_pgsql habilitada en php.ini de XAMPP.
- Las migraciones de EF Core (dotnet ef database update) deben ejecutarse
  antes del primer despliegue de ASP.NET Core.
- MySQL sigue siendo la alternativa de contingencia si el proveedor de
  hosting no soporta PostgreSQL.