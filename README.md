# DeportBeca UMSA

Sistema de gestion de eventos deportivos universitarios para la Universidad Mayor
de San Andres (UMSA). Administra la pre-inscripcion de atletas, la generacion de
fixtures (calendarios de torneo), el registro de resultados y la tabla de
posiciones, con control de acceso por roles.

---

## Tabla de contenidos

- [Caracteristicas](#caracteristicas)
- [Stack tecnologico](#stack-tecnologico)
- [Requisitos previos](#requisitos-previos)
- [Instalacion y puesta en marcha](#instalacion-y-puesta-en-marcha)
- [Credenciales por defecto](#credenciales-por-defecto)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Arquitectura](#arquitectura)
- [Roles y permisos](#roles-y-permisos)
- [Comandos utiles](#comandos-utiles)
- [Solucion de problemas](#solucion-de-problemas)

---

## Caracteristicas

- **Pre-inscripcion publica** de atletas (individual y por equipos) con carga de
  documentos y verificacion por codigo.
- **Archivador**: revision, habilitacion y observacion de inscripciones, con
  historial de cambios y generacion de credenciales en PDF.
- **Generacion de fixtures**: sorteo, distribucion en series, round-robin y fases
  eliminatorias, con asignacion de lugares y horarios.
- **Calificaciones y tabla de posiciones** por serie (grupal e individual).
- **Portal publico de resultados** (sin autenticacion).
- **Panel de administracion** con CRUD de usuarios, carreras, disciplinas,
  lugares, roles y eventos.
- **Control de acceso por roles** (RBAC) granular por modulo y accion.

---

## Stack tecnologico

| Capa | Tecnologia |
|------|-----------|
| Backend | PHP 8.1+, Laravel 10, Eloquent ORM |
| Base de datos | MySQL 5.7+ / 8.x (`becas_deportes`) |
| Autenticacion | Laravel Auth + Sanctum |
| Frontend | Blade, Bootstrap, Vue 2.6 (puntual), SASS |
| Build de assets | Laravel Mix (Webpack) |
| PDF / Excel | dompdf, maatwebsite/excel |

---

## Requisitos previos

Antes de instalar necesitas tener disponible en tu maquina:

- **PHP 8.1 o superior** con las extensiones: `pdo_mysql`, `mbstring`, `openssl`,
  `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`.
- **Composer 2.x** (gestor de dependencias de PHP).
- **MySQL 5.7+ u 8.x** (o MariaDB 10.3+).
- **Node.js 16+ y npm** (para compilar los assets).
- **Git**.

> En Windows, una forma sencilla de tener PHP + MySQL + Apache es instalar
> **XAMPP**. PHP y MySQL quedan en `C:\xampp\php` y `C:\xampp\mysql`. Aun asi
> necesitas instalar Composer y Node.js por separado.

Verifica las versiones:

```bash
php -v
composer -V
mysql --version
node -v
npm -v
```

---

## Instalacion y puesta en marcha

Sigue los pasos en orden. Los comandos asumen una terminal ubicada en la carpeta
del proyecto.

### 1. Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO> deportbeca
cd deportbeca
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Crear el archivo de entorno

Copia el archivo de ejemplo a `.env`:

```bash
# Linux / macOS
cp .env.example .env

# Windows (CMD)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env
```

### 4. Generar la clave de la aplicacion

```bash
php artisan key:generate
```

### 5. Crear la base de datos

Crea una base de datos vacia llamada `becas_deportes`. Por linea de comandos:

```bash
mysql -u root -p -e "CREATE DATABASE becas_deportes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

O desde phpMyAdmin (`http://localhost/phpmyadmin`): crear la base de datos
`becas_deportes` con cotejamiento `utf8mb4_unicode_ci`.

### 6. Configurar la conexion en `.env`

Edita el archivo `.env` y ajusta los datos de tu MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=becas_deportes
DB_USERNAME=root
DB_PASSWORD=
```

> Si vas a enviar correos (notificaciones de inscripcion), completa tambien la
> seccion `MAIL_*` con una cuenta SMTP valida. No es obligatorio para que el
> sistema funcione en local.

### 7. Ejecutar migraciones y sembrar datos

```bash
php artisan migrate
php artisan db:seed
```

El sembrado crea:
- Roles y permisos (Administrador, Secretario, Instructor).
- Usuario administrador inicial.
- 11 facultades de la UMSA.
- ~39 carreras.
- ~25 disciplinas deportivas.
- 7 lugares (escenarios deportivos).

> Alternativa: `php artisan migrate --seed` ejecuta ambos pasos de una vez.

### 8. Instalar y compilar los assets

```bash
npm install
npm run dev
```

> Para una compilacion de produccion (minificada): `npm run prod`.

### 9. Crear el enlace de almacenamiento

```bash
php artisan storage:link
```

### 10. Levantar el servidor

**Opcion A — Servidor embebido de Laravel (recomendado para desarrollo):**

```bash
php artisan serve
```

La aplicacion queda disponible en `http://127.0.0.1:8000`.

**Opcion B — XAMPP / Apache:**

Coloca el proyecto dentro de `htdocs` (por ejemplo `C:\xampp\htdocs\deportbeca`)
y accede a traves de la carpeta `public`:

```
http://localhost/deportbeca/public/
```

### 11. Iniciar sesion

Entra a `/login` con las credenciales por defecto (ver abajo) y cambia la
contrasena en el primer ingreso.

---

## Credenciales por defecto

El usuario administrador se crea con el seeder `AdminUserSeeder`:

| Campo | Valor |
|-------|-------|
| Email | `admin@deportbeca.edu.bo` |
| Contrasena | `Admin2024$Dep` |

> Puedes cambiar estos valores antes de sembrar definiendo `ADMIN_EMAIL` y
> `ADMIN_PASSWORD` en el archivo `.env`. **Cambia la contrasena tras el primer
> inicio de sesion.**

---

## Estructura del proyecto

```
.
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controladores (panel y portal publico)
│   │   │   └── Admin/        # CRUD de usuarios y comunicados
│   │   └── Middleware/       # AdminMiddleware, CheckPermiso (RBAC)
│   ├── Models/               # Modelos Eloquent
│   ├── Services/             # Logica de negocio (FixtureGenerationService)
│   ├── Interfaces/           # Contratos de servicios
│   └── Providers/            # Service providers y bindings
├── database/
│   ├── migrations/           # Esquema de la base de datos
│   ├── seeders/              # Datos iniciales (roles, facultades, carreras...)
│   └── factories/            # Factories para pruebas
├── public/                   # Punto de entrada (index.php) y assets compilados
├── resources/
│   ├── views/                # Plantillas Blade
│   │   ├── layouts/          # Layouts (panel, portal, publico)
│   │   └── includes/panel/   # Menus por rol y componentes del panel
│   ├── js/ y sass/           # Fuentes de assets (compilados con Mix)
├── routes/
│   └── web.php               # Rutas web (publicas y autenticadas)
├── tests/                    # Pruebas Feature y Unit (PHPUnit)
└── webpack.mix.js            # Configuracion de Laravel Mix
```

---

## Arquitectura

### Backend

- **Framework**: Laravel 10 (PHP 8.1+), Eloquent ORM, MySQL.
- **Capa de servicios**: `app/Services/` respaldada por interfaces en
  `app/Interfaces/` y enlazada en `app/Providers/`.
  - `FixtureGenerationService` — generacion de fixtures (enlazado por
    `FixtureGenerationServiceProvider`).
- **Middleware propio**:
  - `AdminMiddleware` — acceso solo para super administradores.
  - `CheckPermiso` — autorizacion granular `permiso:modulo,accion` sobre la
    tabla `rol_modulo_permiso`.

### Frontend

- **Plantillas**: Blade en `resources/views/`. Layouts principales:
  `layouts/app.blade.php` (publico/auth), `layouts/panel.blade.php` (panel) y
  `layouts/portal.blade.php` (portal de resultados).
- **CSS/JS**: Bootstrap + SASS, compilados con Laravel Mix hacia `public/`.

### Base de datos

- Motor MySQL, base `becas_deportes`.
- Relaciones con claves foraneas reales e integridad referencial (cascade /
  restrict segun corresponda). Las relaciones muchos-a-muchos usan tablas pivote
  (`serie_preinscripciones`, `evento_configuracion_disciplinas`).
- Modelos clave: `EventoConfiguracion`, `Preinscripcion`, `Serie`, `Partido`,
  `Estadistica`, `FixtureConfiguracion`, `Rol`, `RolModuloPermiso`.

---

## Roles y permisos

El sistema usa control de acceso basado en roles (RBAC) por modulo y accion.

- **Roles**: `Administrador`, `Secretario`, `Instructor` (tabla `roles`).
- **Super administrador**: el flag `roles.es_super_admin` otorga acceso total sin
  depender del nombre del rol.
- **Permisos**: la tabla `rol_modulo_permiso` define por rol y modulo las acciones
  `ver`, `crear`, `editar`, `eliminar`.
- **Modulos**: usuarios, carreras, disciplinas, lugares, preinscripciones,
  fixture, calificaciones, eventos, roles, privilegios.
- Las rutas se protegen con `middleware('permiso:modulo,accion')` y el menu del
  panel se adapta al rol del usuario.

> Las **facultades** se gestionan por seeder (no cambian). Para extenderlas a un
> CRUD administrativo, replica el patron de `CarreraController`.

---

## Comandos utiles

```bash
php artisan migrate              # Ejecutar migraciones pendientes
php artisan migrate:fresh --seed # Recrear toda la base y sembrar (borra datos)
php artisan db:seed              # Sembrar datos iniciales
php artisan db:seed --class=FacultadesSeeder   # Sembrar solo facultades
php artisan optimize:clear       # Limpiar cache de config, rutas y vistas
./vendor/bin/pint                # Formatear/lint del codigo PHP
./vendor/bin/phpunit             # Ejecutar la suite de pruebas
npm run dev                      # Compilar assets (desarrollo)
npm run watch                    # Recompilar al cambiar archivos
npm run prod                     # Compilar assets (produccion, minificado)
```

---

## Solucion de problemas

- **`419 Page Expired` al enviar formularios**: limpia la cache y refresca la
  sesion con `php artisan optimize:clear`.
- **Imagenes o estilos no cargan**: ejecuta `php artisan storage:link` y recompila
  con `npm run dev`.
- **`SQLSTATE[HY000] [1049] Unknown database`**: la base `becas_deportes` no
  existe; crea la base (paso 5) y revisa las credenciales en `.env`.
- **`Class ... not found` tras clonar**: ejecuta `composer install` y luego
  `composer dump-autoload`.
- **Permisos de escritura (Linux)**: `storage/` y `bootstrap/cache/` deben ser
  escribibles por el servidor web.
