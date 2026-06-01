# AGENTS.md

## Project Snapshot
- CEOGestion is a Laravel 12 app for IT service management, equipment inventory, contracts, and a client portal.
- Local development is typically on Windows with XAMPP at `c:\xampp\htdocs\CEOGestion`.
- Frontend uses Blade, jQuery, Tailwind via Vite, and some DataTables-style list views.
- There is a large amount of root-level documentation. Prefer linking to it instead of copying it into new instructions.

## Start Here
- Read [COMIENZA_AQUI.md](COMIENZA_AQUI.md) for local restart workflow.
- Read [ESTADO_PROYECTO.md](ESTADO_PROYECTO.md) for current project status.
- Read [PROTOCOLO_IMPLEMENTACION_CRUD.md](PROTOCOLO_IMPLEMENTACION_CRUD.md) and [BUENAS_PRACTICAS.md](BUENAS_PRACTICAS.md) before changing CRUD flows or views.
- [CLAUDE.md](CLAUDE.md) is for UI/design-specific workflows, not a general coding guide.

## Core Commands
- Install/setup: `composer install`, `npm install`
- App key and DB setup: `php artisan key:generate`, `php artisan migrate --force`
- Run app: `php artisan serve --host=localhost --port=8000`
- Frontend dev: `npm run dev`
- Production assets: `npm run build`
- Tests: `php artisan test`
- Route inspection: `php artisan route:list`
- Cache reset after route/view/config changes:
  - `php artisan cache:clear`
  - `php artisan config:clear`
  - `php artisan route:clear`
  - `php artisan view:clear`

## Architecture
- Controllers are organized by module under `app/Http/Controllers/`:
  - `Administrativo/` for geographic masters
  - `Parametros/` for core business catalogs and equipment
  - `Incidencias/` for services/incidents
  - `Seguridad/` for auth/roles/permissions-related features
- Views follow the same modular structure in `resources/views/`.
- Routes are split by module in `routes/` and loaded from `routes/web.php`.
- Keep new code inside the existing module instead of creating parallel folders with duplicate concepts.

## Route And Blade Conventions
- Put custom/specific routes before `Route::resource(...)` in route files.
- Use named routes with full module prefix, for example `parametros.equipos.index`.
- Pass route parameters explicitly in Blade:
  - Good: `route('parametros.equipos.update', ['equipo' => $equipo->id])`
  - Avoid implicit model arguments in `route()` when the resource name is easy to mismatch.
- After route edits, always clear route cache and verify with `php artisan route:list`.

## CRUD Change Rules
- For any existing model change, update all relevant layers together:
  - migration
  - model `$fillable` / casts / relations
  - controller `create`, `edit`, `store`, `update`, `show`
  - related Blade views
  - dynamic JS filters if the form depends on related selects
- In `edit()` and `show()`, eager-load needed relations instead of relying on lazy loading.
- In forms, preserve the established `old(...)` and validation error patterns.

## View And UI Rules
- Make small, testable UI edits instead of large rewrites.
- After Blade changes, clear compiled views.
- For layout-sensitive changes, test desktop and narrow widths; see [BUENAS_PRACTICAS.md](BUENAS_PRACTICAS.md).
- Preserve current visual patterns unless the task is explicitly a redesign.

## Migrations And Database Gotchas
- This repo has a long migration history and some environments may already contain tables/columns created outside the current migration sequence.
- Before editing or adding migrations, inspect both:
  - `php artisan migrate:status`
  - the real schema currently present in the database
- Prefer additive, defensive migrations for existing tables.
- Do not assume local migration history is perfectly aligned with the actual schema.

## Validation Workflow
- For route/controller/view changes:
  1. Run the narrowest relevant cache clears.
  2. Run `php artisan route:list` if routes changed.
  3. Run a focused browser or manual flow for the touched CRUD path.
- For PHP-only logic, prefer `php artisan test` or the narrowest available validation.
- If no focused automated check exists, document exactly what was manually verified.

## Agent Behavior In This Repo
- Prefer minimal, targeted changes over broad refactors.
- Do not duplicate existing documentation; link to it.
- Do not rename route groups, folders, or controller namespaces unless the task explicitly requires it.
- Be careful with older docs: some root markdown files reflect historical states. Verify against current code before relying on them.
- If a bug appears to be caused by stale caches, clear caches before assuming the code is wrong.

## Useful References
- [README.md](README.md)
- [COMIENZA_AQUI.md](COMIENZA_AQUI.md)
- [ESTADO_PROYECTO.md](ESTADO_PROYECTO.md)
- [PROTOCOLO_IMPLEMENTACION_CRUD.md](PROTOCOLO_IMPLEMENTACION_CRUD.md)
- [BUENAS_PRACTICAS.md](BUENAS_PRACTICAS.md)
- [PROTOCOLO_CAMBIOS_SEGURIDAD.md](PROTOCOLO_CAMBIOS_SEGURIDAD.md)