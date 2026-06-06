# Facultades: estado actual y como extender a CRUD completo

## Estado actual (seed-only)

Las facultades de la UMSA se gestionan **solo por seeder**, no por interfaz de
administracion. La razon: el conjunto de facultades de la universidad es fijo y
no cambia con frecuencia.

- **Tabla**: `facultades` (`codigo`, `nombre`, `status`)
- **Seeder**: `database/seeders/FacultadesSeeder.php` — siembra las 11 facultades
  oficiales (FCE, FDC, FCPN, FHE, FCS, FCT, FAADU, FM, FAG, FI, FOE). Es
  idempotente (`updateOrInsert` por `codigo`), por lo que se puede re-ejecutar
  sin duplicar.
- **Modelo**: `app/Models/Facultad.php` (relacion `hasMany(Carrera)`, scopes
  `active()` / `inactive()`).
- **Uso**: el formulario de Carreras (`CarreraController::create/edit`) carga las
  facultades activas con `Facultad::active()->get()` para el `<select>`.

Para poblar o actualizar las facultades:

```bash
php artisan db:seed --class=FacultadesSeeder
```

Para agregar/quitar una facultad: editar el arreglo en `FacultadesSeeder.php` y
re-ejecutar el comando anterior.

## Como extender a CRUD completo (cuando se necesite)

Si en el futuro se requiere gestionar facultades desde el panel (como Carreras o
Disciplinas), seguir estos pasos. El patron de Carreras es el modelo a copiar.

### 1. Controlador

Crear `app/Http/Controllers/FacultadController.php` con la misma estructura que
`CarreraController` (index, create, store, show, edit, update, destroy, activo,
inactivo). Validaciones sugeridas:

```php
$request->validate([
    'codigo' => 'required|string|max:20|unique:facultades,codigo',
    'nombre' => 'required|string|max:150',
]);
```

En `destroy`, impedir borrar una facultad con carreras asociadas (la FK
`carreras.facultad_id` ya usa `ON DELETE CASCADE`, pero conviene avisar al
usuario antes de borrar en cascada):

```php
if ($facultad->carreras()->exists()) {
    return back()->with('toastr_error', 'No se puede eliminar: la facultad tiene carreras asociadas.');
}
```

### 2. Rutas

En `routes/web.php`, agregar un grupo protegido. Reutilizar el modulo de permiso
`carreras` (mas simple) o crear un modulo `facultades` propio (ver paso 5):

```php
Route::middleware(['auth', 'permiso:carreras,ver'])->group(function () {
    Route::resource('facultades', FacultadController::class);
    Route::patch('/facultades/{id}/activo', [FacultadController::class, 'activo'])->name('facultades.activo');
    Route::patch('/facultades/{id}/inactivo', [FacultadController::class, 'inactivo'])->name('facultades.inactivo');
});
```

### 3. Vistas

Copiar `resources/views/carreras/{index,create,edit,show}.blade.php` a
`resources/views/facultades/` y ajustar campos: Carreras tiene un `<select>` de
facultad que en Facultades no aplica; el resto (codigo, nombre, status) es igual.

### 4. Menu

Agregar el enlace en `resources/views/includes/panel/menu/admin.blade.php`:

```blade
<li class="nav-item {{ $r->routeIs('facultades.*') ? 'active' : '' }}">
    <a class="nav-link {{ $r->routeIs('facultades.*') ? 'active' : '' }}" href="{{ route('facultades.index') }}">
        <i class="fas fa-university"></i><strong>Facultades</strong>
    </a>
</li>
```

### 5. (Opcional) Modulo de permiso propio

Si se quiere controlar el acceso a Facultades de forma independiente de Carreras,
agregar el modulo `facultades` en tres lugares (deben coincidir):

- `app/Http/Controllers/PrivilegioController.php` — arreglo `$modulos`
- `database/seeders/RolesPermisosSeeder.php` — constante `MODULOS` y los permisos
  por rol
- usar `permiso:facultades,ver` en las rutas del paso 2

Y ejecutar `php artisan db:seed --class=RolesPermisosSeeder` para crear las filas
del nuevo modulo.
