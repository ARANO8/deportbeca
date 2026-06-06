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
Route::get('login', [LoginController::class,    'showLoginForm'])->name('login');
Route::post('login', [LoginController::class,    'login']);
Route::post('logout', [LoginController::class,    'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,  'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,  'reset'])->name('password.update');

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

// ==================== DISCIPLINAS (con autenticacion) ====================
Route::middleware(['auth', 'permiso:disciplinas,ver'])->group(function () {
    Route::resource('disciplines', DisciplineController::class);
    Route::put('disciplines/{id}/activo', [DisciplineController::class, 'activo'])->name('disciplines.activo');
    Route::put('disciplines/{id}/inactivo', [DisciplineController::class, 'inactivo'])->name('disciplines.inactivo');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::post('/alertas/{alerta}/marcar-leida', [AlertaController::class, 'marcarLeida'])->name('alertas.marcar.leida');
    Route::post('/alertas/marcar-todas', [AlertaController::class, 'marcarTodasLeidas'])->name('alertas.marcar.todas');
    Route::delete('/alertas/{alerta}', [AlertaController::class, 'destroy'])->name('alertas.destroy');
});

// ==================== CONFIGURACIÓN DE EVENTOS (ADMIN) ====================
Route::prefix('eventos')->middleware(['auth', 'permiso:eventos,ver'])->group(function () {
    Route::get('/', [EventoConfiguracionController::class, 'index'])->name('eventos.index');
    Route::get('{tipoEvento}/edit', [EventoConfiguracionController::class, 'edit'])->name('eventos.edit');
    Route::put('{tipoEvento}', [EventoConfiguracionController::class, 'update'])->name('eventos.update');
});

// ==================== ARCHIVADOR (GESTIÓN DE EQUIPOS) ====================
Route::prefix('archivador')->middleware(['auth', 'permiso:preinscripciones,ver'])->group(function () {
    Route::get('/', [ArchivadorController::class, 'index'])->name('archivador.index');
    Route::get('{id}/detalle', [ArchivadorController::class, 'show'])->name('archivador.show');
    Route::match(['GET', 'POST'], '{id}/habilitar', [ArchivadorController::class, 'habilitar'])->name('archivador.habilitar');
    Route::match(['GET', 'POST'], '{id}/observar', [ArchivadorController::class, 'observar'])->name('archivador.observar');
    Route::match(['GET', 'POST'], '{id}/revertir', [ArchivadorController::class, 'revertirPendiente'])->name('archivador.revertir');
    Route::get('{id}/integrante/{integranteId}/{tipo}', [ArchivadorController::class, 'descargarDocumentoIntegrante'])->name('archivador.descargar.integrante');
    Route::get('{id}/descargar/{tipo}', [ArchivadorController::class, 'descargarDocumento'])->name('archivador.descargar');
    Route::get('{id}/credencial/pdf', [ArchivadorController::class, 'generarCredencial'])->name('archivador.credencial');
    Route::get('{id}/historial', [ArchivadorController::class, 'historial'])->name('archivador.historial');
});

// ==================== ADMIN (USUARIOS, CARRERAS, PAGINAS, LUGARES) ====================
Route::middleware(['auth', 'permiso:usuarios,ver'])->group(function () {
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::patch('/users/{user}/activo', [UserController::class, 'activo'])->name('users.activo');
    Route::patch('/users/{user}/inactivo', [UserController::class, 'inactivo'])->name('users.inactivo');
    Route::resource('paginawebs', PaginaController::class);
});

Route::middleware(['auth', 'permiso:carreras,ver'])->group(function () {
    Route::resource('carreras', CarreraController::class);
    Route::patch('/carreras/{id}/activo', [CarreraController::class, 'activo'])->name('carreras.activo');
    Route::patch('/carreras/{id}/inactivo', [CarreraController::class, 'inactivo'])->name('carreras.inactivo');
});

Route::middleware(['auth', 'permiso:lugares,ver'])->group(function () {
    Route::resource('lugares', LugarController::class)->names([
        'index' => 'admin.lugares.index',
        'create' => 'admin.lugares.create',
        'store' => 'admin.lugares.store',
        'show' => 'admin.lugares.show',
        'edit' => 'admin.lugares.edit',
        'update' => 'admin.lugares.update',
        'destroy' => 'admin.lugares.destroy',
    ]);
    Route::patch('/lugares/{id}/activo', [LugarController::class, 'activo'])->name('admin.lugares.activo');
    Route::patch('/lugares/{id}/inactivo', [LugarController::class, 'inactivo'])->name('admin.lugares.inactivo');
});
Route::middleware(['auth', 'permiso:roles,ver'])->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RolController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');
    Route::patch('/roles/{id}/activo', [RolController::class, 'activo'])->name('roles.activo');
    Route::patch('/roles/{id}/inactivo', [RolController::class, 'inactivo'])->name('roles.inactivo');
});

// ============================================
// RUTAS DE PRIVILEGIOS
// ============================================
Route::middleware(['auth', 'permiso:privilegios,ver'])->group(function () {
    Route::get('/privilegios', [PrivilegioController::class, 'index'])->name('privilegios.index');
    Route::get('/privilegios/{id}', [PrivilegioController::class, 'show'])->name('privilegios.show');
    Route::get('/privilegios/{id}/edit', [PrivilegioController::class, 'edit'])->name('privilegios.edit');
    Route::put('/privilegios/{id}', [PrivilegioController::class, 'update'])->name('privilegios.update');
});
// Calificaciones — requiere auth + permiso de ver calificaciones
Route::middleware(['auth', 'permiso:calificaciones,ver'])->prefix('calificaciones')->name('calificaciones.')->group(function () {
    Route::get('/serie/{serie}', [CalificacionController::class, 'index'])->name('index');
    Route::post('/serie/{serie}/posiciones', [CalificacionController::class, 'guardarPosiciones'])->name('guardar.posiciones');
    Route::post('/partido/{partido}/resultado-grupal', [CalificacionController::class, 'guardarResultadoGrupal'])->name('guardar.resultado.grupal');
});
// routes/web.php - Agrega esto dentro del grupo auth

Route::middleware(['auth', 'permiso:fixture,ver'])->prefix('fixture')->name('fixture.')->group(function () {
    // Flujo principal
    Route::get('/', [FixtureController::class, 'index'])->name('index');
    Route::get('/evento/{evento}/disciplinas', [FixtureController::class, 'getDisciplinas'])->name('get.disciplinas');
    Route::get('/evento/{evento}/disciplina/{disciplina}/participantes', [FixtureController::class, 'participantes'])->name('participantes');
    Route::post('/evento/{evento}/disciplina/{disciplina}/guardar-participantes', [FixtureController::class, 'guardarParticipantes'])->name('guardar.participantes');
    Route::get('/evento/{evento}/configurar-series/{disciplina}', [FixtureController::class, 'configurarSeries'])->name('configurar.series');
    Route::post('/evento/{evento}/generar-fixture', [FixtureController::class, 'generarFixture'])->name('generar');

    // Mis fixtures
    Route::get('/mis-fixtures', [FixtureController::class, 'misFixtures'])->name('mis.fixtures');
    Route::get('/serie/{serie}/ver', [FixtureController::class, 'verFixtureSerie'])->name('ver.serie');
    Route::get('/evento/{evento}/calendario', [FixtureController::class, 'calendarioEvento'])->name('calendario');
    Route::get('/evento/{evento}/imprimir', [FixtureController::class, 'imprimirFixture'])->name('imprimir');

    // Asignaciones
    Route::post('/partido/{partido}/asignar-lugar', [FixtureController::class, 'asignarLugar'])->name('asignar.lugar');

    // Siguiente fase eliminatoria
    Route::post('/evento/{evento}/disciplina/{disciplina}/siguiente-fase', [FixtureController::class, 'generarSiguienteFase'])->name('siguiente.fase');

    Route::get('/evento/{evento}/calendario/json', [FixtureController::class, 'calendarioEventosJson'])->name('calendario.json');
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
