# SDD Init — CEOGestion

Detected: 2026-05-27

## Project Context

- Name: CEOGestion
- Repo: Laravel 12 application for IT service management, equipment inventory, contracts, and a client portal.
- Platform: Windows local dev (XAMPP).

## Stack

- Backend: PHP 8.2, Laravel 12
- Frontend: Blade, jQuery, Tailwind CSS v4, Vite, Axios
- Build tooling: npm + Vite

## Architecture & Conventions

- MVC with module-based controllers: `app/Http/Controllers/{Administrativo,Parametros,Incidencias,Seguridad}`.
- Views mirror modules under `resources/views/`.
- Routes split by module in `routes/` and loaded from `routes/web.php`.
- CRUD changes must update migration/model/controller/views/JS filters together.
- Route conventions: custom routes before `Route::resource`, named routes with full module prefix, explicit params in Blade.

## Testing & Quality

- Test runner: `php artisan test` (PHPUnit)
- Suites: Unit + Feature (tests/Unit, tests/Feature)
- DB: sqlite in-memory for tests via phpunit.xml
- Coverage: not configured
- Lint/format: Laravel Pint (`./vendor/bin/pint`)
- Type checking: not configured

## Strict TDD

- Status: enabled (test runner detected)

## References

- AGENTS.md
- CLAUDE.md (UI/design workflow)
- COMIENZA_AQUI.md
- ESTADO_PROYECTO.md
- PROTOCOLO_IMPLEMENTACION_CRUD.md
- BUENAS_PRACTICAS.md
- PROTOCOLO_CAMBIOS_SEGURIDAD.md
