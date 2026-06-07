<?php

use App\Http\Controllers\Admin\PaginaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\ArchivadorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\EventoConfiguracionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PreinscripcionController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserSenttingsController;
use App\Http\Controllers\WelcomController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ==================== RUTAS PÚBLICAS ====================
Route::get('/', [WelcomController::class, 'index']);

// Rutas de autenticación (equivalente a Auth::routes() sin requerir laravel/ui)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// SEC-04 + SEC-06: descarga de documentos protegida — requiere auth y evita path traversal
Route::get('/ver-documento/{filename}', function (string $filename) {
    // Eliminar cualquier intento de path traversal: tomar solo el nombre base
    $filename = basename(urldecode($filename));

    // Solo permitir extensiones de documentos válidos
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (! in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'webp'], true)) {
        abort(403, 'Tipo de archivo no permitido.');
    }

    // Buscar en disco privado (local) primero — nuevos uploads
    $carpetasPrivadas = [
        'preinscripciones/documentos/'.$filename,
        'preinscripciones/integrantes/'.$filename,
    ];
    foreach ($carpetasPrivadas as $ruta) {
        if (Storage::disk('local')->exists($ruta)) {
            return Storage::disk('local')->download($ruta, $filename);
        }
    }

    // Fallback: disco público — uploads anteriores a la migración de seguridad
    $carpetasPublicas = [
        storage_path('app/public/preinscripciones/documentos/'.$filename),
        storage_path('app/public/preinscripciones/integrantes/'.$filename),
    ];
    foreach ($carpetasPublicas as $ruta) {
        if (file_exists($ruta)) {
            return response()->download($ruta, $filename);
        }
    }

    abort(404, 'Documento no encontrado.');
})->middleware('auth')->name('ver.documento');

Route::prefix('preinscripcion')->group(function () {
    // SEC-09: rate limiting — max 10 intentos/min por IP en validación de código
    Route::post('validar-codigo', [PreinscripcionController::class, 'validarCodigo'])
        ->middleware('throttle:preinscripcion')
        ->name('preinscripcion.validar.codigo');
    Route::get('formulario-modal', [PreinscripcionController::class, 'formularioModal'])->name('preinscripcion.formulario.modal');
    Route::post('store', [PreinscripcionController::class, 'store'])
        ->middleware('throttle:preinscripcion')
        ->name('preinscripcion.store');
    // Verificación pública: sin código muestra el formulario; con código muestra el resultado
    Route::get('verificar', [PreinscripcionController::class, 'verificarEstado'])->name('preinscripcion.verificar.form');
    Route::get('verificar/{codigo}', [PreinscripcionController::class, 'verificarEstado'])->name('preinscripcion.verificar');
});

// ==================== PORTAL PÚBLICO DE RESULTADOS (sin auth) ====================
Route::prefix('resultados')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('index');
    Route::get('/evento/{eventoId}', [PortalController::class, 'evento'])->name('evento');
    Route::get('/serie/{serieId}', [PortalController::class, 'serie'])->name('serie');
    Route::get('/evento/{eventoId}/fixture', [PortalController::class, 'fixture'])->name('fixture');
});

// ==================== PERFIL Y CONTRASENA (cualquier usuario autenticado) ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/NewPassword', [UserSenttingsController::class, 'NewPassword'])->name('NewPassword');
    Route::post('/change/password', [UserSenttingsController::class, 'changePassword'])->name('changePassword');
    Route::get('/perfil', [UserSenttingsController::class, 'editarPerfil'])->name('perfil.editar');
    Route::put('/perfil', [UserSenttingsController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});

// ==================== ALERTAS (propias del usuario, validadas por user_id en el controlador) ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::post('/alertas/{alerta}/marcar-leida', [AlertaController::class, 'marcarLeida'])->name('alertas.marcar.leida');
    Route::post('/alertas/marcar-todas', [AlertaController::class, 'marcarTodasLeidas'])->name('alertas.marcar.todas');
    Route::delete('/alertas/{alerta}', [AlertaController::class, 'destroy'])->name('alertas.destroy');
});

// ==================== DISCIPLINAS (permiso por accion) ====================
Route::middleware(['auth'])->prefix('disciplinas')->name('disciplinas.')->group(function () {
    Route::get('/', [DisciplineController::class, 'index'])->middleware('permiso:disciplinas,ver')->name('index');
    Route::get('/create', [DisciplineController::class, 'create'])->middleware('permiso:disciplinas,crear')->name('create');
    Route::post('/', [DisciplineController::class, 'store'])->middleware('permiso:disciplinas,crear')->name('store');
    Route::get('/{discipline}', [DisciplineController::class, 'show'])->middleware('permiso:disciplinas,ver')->name('show');
    Route::get('/{discipline}/edit', [DisciplineController::class, 'edit'])->middleware('permiso:disciplinas,editar')->name('edit');
    Route::put('/{discipline}', [DisciplineController::class, 'update'])->middleware('permiso:disciplinas,editar')->name('update');
    Route::patch('/{id}/activo', [DisciplineController::class, 'activo'])->middleware('permiso:disciplinas,editar')->name('activo');
    Route::patch('/{id}/inactivo', [DisciplineController::class, 'inactivo'])->middleware('permiso:disciplinas,editar')->name('inactivo');
    Route::delete('/{discipline}', [DisciplineController::class, 'destroy'])->middleware('permiso:disciplinas,eliminar')->name('destroy');
});

// ==================== CONFIGURACIÓN DE EVENTOS (ADMIN) ====================
Route::prefix('eventos')->middleware(['auth'])->group(function () {
    Route::get('/', [EventoConfiguracionController::class, 'index'])->middleware('permiso:eventos,ver')->name('eventos.index');
    Route::get('{tipoEvento}/edit', [EventoConfiguracionController::class, 'edit'])->middleware('permiso:eventos,editar')->name('eventos.edit');
    Route::get('{tipoEvento}', [EventoConfiguracionController::class, 'show'])->middleware('permiso:eventos,ver')->name('eventos.show');
    Route::put('{tipoEvento}', [EventoConfiguracionController::class, 'update'])->middleware('permiso:eventos,editar')->name('eventos.update');
});

// ==================== ARCHIVADOR (GESTIÓN DE EQUIPOS / preinscripciones) ====================
Route::prefix('archivador')->middleware(['auth'])->group(function () {
    Route::get('/', [ArchivadorController::class, 'index'])->middleware('permiso:preinscripciones,ver')->name('archivador.index');
    Route::get('{id}/detalle', [ArchivadorController::class, 'show'])->middleware('permiso:preinscripciones,ver')->name('archivador.show');
    Route::match(['GET', 'POST'], '{id}/habilitar', [ArchivadorController::class, 'habilitar'])->middleware('permiso:preinscripciones,editar')->name('archivador.habilitar');
    Route::match(['GET', 'POST'], '{id}/observar', [ArchivadorController::class, 'observar'])->middleware('permiso:preinscripciones,editar')->name('archivador.observar');
    Route::match(['GET', 'POST'], '{id}/revertir', [ArchivadorController::class, 'revertirPendiente'])->middleware('permiso:preinscripciones,editar')->name('archivador.revertir');
    Route::get('{id}/integrante/{integranteId}/{tipo}', [ArchivadorController::class, 'descargarDocumentoIntegrante'])->middleware('permiso:preinscripciones,ver')->name('archivador.descargar.integrante');
    Route::get('{id}/descargar/{tipo}', [ArchivadorController::class, 'descargarDocumento'])->middleware('permiso:preinscripciones,ver')->name('archivador.descargar');
    Route::get('{id}/credencial/pdf', [ArchivadorController::class, 'generarCredencial'])->middleware('permiso:preinscripciones,ver')->name('archivador.credencial');
    Route::get('{id}/historial', [ArchivadorController::class, 'historial'])->middleware('permiso:preinscripciones,ver')->name('archivador.historial');
});

// ==================== USUARIOS Y COMUNICADOS (modulo usuarios) ====================
Route::middleware(['auth'])->group(function () {
    // Usuarios
    Route::get('/users', [UserController::class, 'index'])->middleware('permiso:usuarios,ver')->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('permiso:usuarios,crear')->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('permiso:usuarios,crear')->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permiso:usuarios,ver')->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permiso:usuarios,editar')->name('users.edit');
    Route::match(['PUT', 'PATCH'], '/users/{user}', [UserController::class, 'update'])->middleware('permiso:usuarios,editar')->name('users.update');
    Route::patch('/users/{user}/activo', [UserController::class, 'activo'])->middleware('permiso:usuarios,editar')->name('users.activo');
    Route::patch('/users/{user}/inactivo', [UserController::class, 'inactivo'])->middleware('permiso:usuarios,editar')->name('users.inactivo');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permiso:usuarios,eliminar')->name('users.destroy');

    // Comunicados (paginas web)
    Route::get('/paginawebs', [PaginaController::class, 'index'])->middleware('permiso:usuarios,ver')->name('paginawebs.index');
    Route::get('/paginawebs/create', [PaginaController::class, 'create'])->middleware('permiso:usuarios,crear')->name('paginawebs.create');
    Route::post('/paginawebs', [PaginaController::class, 'store'])->middleware('permiso:usuarios,crear')->name('paginawebs.store');
    Route::get('/paginawebs/{paginaweb}', [PaginaController::class, 'show'])->middleware('permiso:usuarios,ver')->name('paginawebs.show');
    Route::get('/paginawebs/{paginaweb}/edit', [PaginaController::class, 'edit'])->middleware('permiso:usuarios,editar')->name('paginawebs.edit');
    Route::match(['PUT', 'PATCH'], '/paginawebs/{paginaweb}', [PaginaController::class, 'update'])->middleware('permiso:usuarios,editar')->name('paginawebs.update');
    Route::delete('/paginawebs/{paginaweb}', [PaginaController::class, 'destroy'])->middleware('permiso:usuarios,eliminar')->name('paginawebs.destroy');
});

// ==================== CARRERAS ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/carreras', [CarreraController::class, 'index'])->middleware('permiso:carreras,ver')->name('carreras.index');
    Route::get('/carreras/create', [CarreraController::class, 'create'])->middleware('permiso:carreras,crear')->name('carreras.create');
    Route::post('/carreras', [CarreraController::class, 'store'])->middleware('permiso:carreras,crear')->name('carreras.store');
    Route::get('/carreras/{carrera}', [CarreraController::class, 'show'])->middleware('permiso:carreras,ver')->name('carreras.show');
    Route::get('/carreras/{carrera}/edit', [CarreraController::class, 'edit'])->middleware('permiso:carreras,editar')->name('carreras.edit');
    Route::match(['PUT', 'PATCH'], '/carreras/{carrera}', [CarreraController::class, 'update'])->middleware('permiso:carreras,editar')->name('carreras.update');
    Route::patch('/carreras/{id}/activo', [CarreraController::class, 'activo'])->middleware('permiso:carreras,editar')->name('carreras.activo');
    Route::patch('/carreras/{id}/inactivo', [CarreraController::class, 'inactivo'])->middleware('permiso:carreras,editar')->name('carreras.inactivo');
    Route::delete('/carreras/{carrera}', [CarreraController::class, 'destroy'])->middleware('permiso:carreras,eliminar')->name('carreras.destroy');
});

// ==================== LUGARES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/lugares', [LugarController::class, 'index'])->middleware('permiso:lugares,ver')->name('admin.lugares.index');
    Route::get('/lugares/create', [LugarController::class, 'create'])->middleware('permiso:lugares,crear')->name('admin.lugares.create');
    Route::post('/lugares', [LugarController::class, 'store'])->middleware('permiso:lugares,crear')->name('admin.lugares.store');
    Route::get('/lugares/{lugar}', [LugarController::class, 'show'])->middleware('permiso:lugares,ver')->name('admin.lugares.show');
    Route::get('/lugares/{lugar}/edit', [LugarController::class, 'edit'])->middleware('permiso:lugares,editar')->name('admin.lugares.edit');
    Route::match(['PUT', 'PATCH'], '/lugares/{lugar}', [LugarController::class, 'update'])->middleware('permiso:lugares,editar')->name('admin.lugares.update');
    Route::patch('/lugares/{id}/activo', [LugarController::class, 'activo'])->middleware('permiso:lugares,editar')->name('admin.lugares.activo');
    Route::patch('/lugares/{id}/inactivo', [LugarController::class, 'inactivo'])->middleware('permiso:lugares,editar')->name('admin.lugares.inactivo');
    Route::delete('/lugares/{lugar}', [LugarController::class, 'destroy'])->middleware('permiso:lugares,eliminar')->name('admin.lugares.destroy');
});

// ==================== ROLES ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->middleware('permiso:roles,ver')->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->middleware('permiso:roles,crear')->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->middleware('permiso:roles,crear')->name('roles.store');
    Route::get('/roles/{id}', [RolController::class, 'show'])->middleware('permiso:roles,ver')->name('roles.show');
    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->middleware('permiso:roles,editar')->name('roles.edit');
    Route::put('/roles/{id}', [RolController::class, 'update'])->middleware('permiso:roles,editar')->name('roles.update');
    Route::patch('/roles/{id}/activo', [RolController::class, 'activo'])->middleware('permiso:roles,editar')->name('roles.activo');
    Route::patch('/roles/{id}/inactivo', [RolController::class, 'inactivo'])->middleware('permiso:roles,editar')->name('roles.inactivo');
    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->middleware('permiso:roles,eliminar')->name('roles.destroy');
});

// ==================== PRIVILEGIOS ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/privilegios', [PrivilegioController::class, 'index'])->middleware('permiso:privilegios,ver')->name('privilegios.index');
    Route::get('/privilegios/{id}/edit', [PrivilegioController::class, 'edit'])->middleware('permiso:privilegios,editar')->name('privilegios.edit');
    Route::put('/privilegios/{id}', [PrivilegioController::class, 'update'])->middleware('permiso:privilegios,editar')->name('privilegios.update');
});

// ==================== CALIFICACIONES ====================
Route::middleware(['auth'])->prefix('calificaciones')->name('calificaciones.')->group(function () {
    Route::get('/serie/{serie}', [CalificacionController::class, 'index'])->middleware('permiso:calificaciones,ver')->name('index');
    Route::post('/serie/{serie}/posiciones', [CalificacionController::class, 'guardarPosiciones'])->middleware('permiso:calificaciones,editar')->name('guardar.posiciones');
    Route::post('/partido/{partido}/resultado-grupal', [CalificacionController::class, 'guardarResultadoGrupal'])->middleware('permiso:calificaciones,editar')->name('guardar.resultado.grupal');
});

// ==================== FIXTURE ====================
Route::middleware(['auth'])->prefix('fixture')->name('fixture.')->group(function () {
    // Lectura
    Route::get('/', [FixtureController::class, 'index'])->middleware('permiso:fixture,ver')->name('index');
    Route::get('/evento/{evento}/disciplinas', [FixtureController::class, 'getDisciplinas'])->middleware('permiso:fixture,ver')->name('get.disciplinas');
    Route::get('/evento/{evento}/disciplina/{disciplina}/participantes', [FixtureController::class, 'participantes'])->middleware('permiso:fixture,ver')->name('participantes');
    Route::get('/evento/{evento}/configurar-series/{disciplina}', [FixtureController::class, 'configurarSeries'])->middleware('permiso:fixture,ver')->name('configurar.series');
    Route::get('/mis-fixtures', [FixtureController::class, 'misFixtures'])->middleware('permiso:fixture,ver')->name('mis.fixtures');
    Route::get('/serie/{serie}/ver', [FixtureController::class, 'verFixtureSerie'])->middleware('permiso:fixture,ver')->name('ver.serie');
    Route::get('/evento/{evento}/calendario', [FixtureController::class, 'calendarioEvento'])->middleware('permiso:fixture,ver')->name('calendario');
    Route::get('/evento/{evento}/imprimir', [FixtureController::class, 'imprimirFixture'])->middleware('permiso:fixture,ver')->name('imprimir');
    Route::get('/evento/{evento}/calendario/json', [FixtureController::class, 'calendarioEventosJson'])->middleware('permiso:fixture,ver')->name('calendario.json');

    // Creacion del fixture
    Route::post('/evento/{evento}/disciplina/{disciplina}/guardar-participantes', [FixtureController::class, 'guardarParticipantes'])->middleware('permiso:fixture,crear')->name('guardar.participantes');
    Route::post('/evento/{evento}/generar-fixture', [FixtureController::class, 'generarFixture'])->middleware('permiso:fixture,crear')->name('generar');
    Route::post('/evento/{evento}/disciplina/{disciplina}/siguiente-fase', [FixtureController::class, 'generarSiguienteFase'])->middleware('permiso:fixture,crear')->name('siguiente.fase');

    // Edicion (asignaciones sobre partidos)
    Route::post('/partido/{partido}/asignar-lugar', [FixtureController::class, 'asignarLugar'])->middleware('permiso:fixture,editar')->name('asignar.lugar');
});

// ==================== EXPORTACIONES (Excel y PDF) ====================
Route::middleware(['auth'])->prefix('exportar')->name('exportar.')->group(function () {
    // Excel
    Route::get('/preinscripciones/excel', [ExportController::class, 'preinscripcionesExcel'])->name('preinscripciones.excel');
    Route::get('/serie/{serie}/posiciones/excel', [ExportController::class, 'tablaPosicionesExcel'])->name('posiciones.excel');

    // PDF
    Route::get('/evento/{evento}/fixture/pdf', [ExportController::class, 'fixturePdf'])->name('fixture.pdf');
    Route::get('/serie/{serie}/posiciones/pdf', [ExportController::class, 'tablaPosicionesPdf'])->name('posiciones.pdf');
});

// ==================== RUTA PARA GENERAR CONTRASEÑA (AJAX) ====================
Route::get('/generate-password', function () {
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*()';

    $password = $letters[rand(0, strlen($letters) - 1)];
    $password .= $numbers[rand(0, strlen($numbers) - 1)];
    $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

    $allChars = $letters.$numbers.$specialChars;
    for ($i = 3; $i < 8; $i++) {
        $password .= $allChars[rand(0, strlen($allChars) - 1)];
    }

    return response()->json(['password' => str_shuffle($password)]);
})->middleware('auth')->name('generate.password');
