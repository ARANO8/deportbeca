# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**DeportBeca** is a Laravel 10 sports event management system for a university context. It handles pre-registration of athletes, tournament fixture generation, scoring/standings, role-based access, and document management.

## Commands

### PHP / Laravel
```bash
php artisan migrate          # Run pending migrations
php artisan db:seed          # Seed the database
php artisan serve            # Start dev server (if not using XAMPP)
./vendor/bin/pint            # Lint/format PHP code
./vendor/bin/phpunit         # Run test suite
php artisan test --filter ClassName   # Run a single test class
```

### JavaScript / Assets
```bash
npm run dev      # Compile assets once (development)
npm run watch    # Watch and recompile on change
npm run prod     # Minified production build
```

> Do not run `npm run prod` during normal development — only at deploy time.

## Architecture

### Backend
- **Framework**: Laravel 10 (PHP 8.1+), Eloquent ORM, MySQL (`becas_deportes`)
- **Auth**: Laravel Auth + Sanctum (API tokens)
- **Service layer**: `app/Services/` backed by interfaces in `app/Interfaces/`; bound in `app/Providers/`
  - `FixtureGenerationService` — fixture generation logic (bound via `FixtureGenerationServiceProvider`)
- **Custom middleware**: `AdminMiddleware` (super admin) and `CheckPermiso` (`permiso:modulo,accion`, RBAC granular sobre `rol_modulo_permiso`)
- **Role model**: `User` → `Rol` (Administrador, Secretario, Instructor). Permisos por modulo en `rol_modulo_permiso`; `roles.es_super_admin` marca acceso total

### Frontend
- **Templates**: Blade (`resources/views/`). Layouts: `layouts/app.blade.php` (public/auth) and `layouts/panel.blade.php` (admin panel)
- **Vue**: Vue 2.6 mounted in `resources/js/app.js`, compiled with Laravel Mix (`webpack.mix.js`)
- **CSS**: Bootstrap 5.1 + custom SCSS in `resources/sass/`
- **Notifications**: Toastr (`resources/js/app.js`)
- **Compiled output**: `public/js/` and `public/css/` — never edit these directly

### Route Groups (routes/web.php)
| Prefix | Area |
|--------|------|
| `/preinscripcion/*` | Public pre-registration flow |
| `/archivador/*` | Team/equipment filing and document approval |
| `/fixture/*` | Tournament management, series, schedule, venues |
| `/calificaciones/*` | Scoring and standings per series |
| `/eventos/*` | Event configuration (disciplines, access codes, dates) |
| `/users/*`, `/carreras/*`, `/lugares/*`, `/paginawebs/*` | Admin panel |
| `/roles/*`, `/privilegios/*` | Role and permission management |

### Key Domain Models
- `EventoConfiguracion` — parent entity for each sports event; has disciplines, access codes, and dates
- `Preinscripcion` / `PreinscripcionIntegrante` — individual or group athlete registration
- `Serie` / `Partido` / `Estadistica` — tournament bracket → match → result/position
- `FixtureConfiguracion` — per-event tournament settings
- `Rol` / `Permiso` / `RolModuloPermiso` — access control

### Database
- Engine: MySQL 5.7+, database `becas_deportes`, host `127.0.0.1:3306`
- Migrations in `database/migrations/` — latest: `2026_06_01` (adds `posicion` to `estadisticas`)
- Seeders in `database/seeders/`
