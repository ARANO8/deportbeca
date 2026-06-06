@extends('layouts.panel')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white py-2">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <h5 class="mb-2 mb-sm-0">
                    <i class="fas fa-users"></i> 
                    {{ $preinscripcion->tipo_inscripcion == 'grupal' ? 'Equipo: ' . ($preinscripcion->nombre_equipo ?: 'Sin nombre') : 'Participante: ' . $preinscripcion->representante_nombre }}
                </h5>
                <a href="{{ route('archivador.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        
        <div class="card-body p-2 p-md-3">
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white py-1">Información General</div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tr><td width="35%"><strong>Código:</strong></td><td>{{ $preinscripcion->codigo_inscripcion }}</code></td></tr>
                                    <tr><td><strong>Evento:</strong></td><td>{{ strtoupper($preinscripcion->tipo_evento) }}</span></td></tr>
                                    <tr><td><strong>Disciplina:</strong></td><td>{{ $preinscripcion->disciplina->nombre ?? 'N/A' }}</strong></td></tr>
                                    <tr><td><strong>Tipo:</strong></td>
                                        <td>
                                            @if($preinscripcion->tipo_inscripcion == 'grupal')
                                                <span class="badge bg-success">Grupal</span>
                                            @else
                                                <span class="badge bg-info">Individual</span>
                                            @endif
                                         </span>
                                    </span>
                                    
                                    @if($preinscripcion->estado == 'habilitado')
                                    <tr><td><strong>Estado:</strong></td><td><span class="badge bg-success">Habilitado</span></td></tr>
                                    @elseif($preinscripcion->estado == 'observado')
                                    <tr><td><strong>Estado:</strong></td><td><span class="badge bg-warning">Observado</span></td></tr>
                                    @else
                                    <tr><td><strong>Estado:</strong></td><td><span class="badge bg-secondary">Pendiente</span></td></tr>
                                    @endif
                                    
                                    @if($preinscripcion->facultad_id)
                                    <tr><td><strong>Facultad:</strong></td><td>{{ $preinscripcion->facultad->nombre ?? 'N/A' }}<br></td></tr>
                                    @endif
                                    @if($preinscripcion->carrera_id)
                                    <tr><td><strong>Carrera:</strong></td><td>{{ $preinscripcion->carrera->nombre ?? 'N/A' }}<br></td></tr>
                                    @endif
                                    @if($preinscripcion->observaciones)
                                    <tr><td><strong>Observación:</strong></td><td class="text-danger">{{ $preinscripcion->observaciones }}<br></td></tr>
                                    @endif
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white py-1">{{ $preinscripcion->tipo_inscripcion == 'grupal' ? 'Capitán' : 'Participante' }}</div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tr><td width="35%"><strong>Nombre:</strong></td><td>{{ $preinscripcion->representante_nombre }}</td></tr>
                                    <tr><td><strong>CI:</strong></td><td>{{ $preinscripcion->representante_ci }}</td></tr>
                                    <tr><td><strong>Email:</strong></td><td>{{ $preinscripcion->representante_email }}</td></tr>
                                    <tr><td><strong>Teléfono:</strong></td><td>{{ $preinscripcion->representante_telefono }}</td></tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header bg-success text-white py-1">Documentos</div>
                <div class="card-body p-2">
                    <div class="row g-2">
                        @php
                            function getDocUrl($path) {
                                if(!$path) return null;
                                $full = storage_path('app/public/' . $path);
                                if(file_exists($full)) {
                                    return route('ver.documento', ['filename' => basename($path)]);
                                }
                                return null;
                            }
                            $aval = getDocUrl($preinscripcion->documento_aval_path);
                            $ci = getDocUrl($preinscripcion->documento_ci_path);
                            $seguro = getDocUrl($preinscripcion->documento_seguro_path);
                            $matricula = getDocUrl($preinscripcion->documento_matricula_path);
                        @endphp
                        
                        @if($aval)
                        <div class="col-6 col-md-3">
                            <div class="text-center border rounded p-2">
                                <i class="fas fa-file-signature fa-2x text-primary"></i>
                                <p class="mb-1"><strong>Aval</strong></p>
                                <button onclick="verArchivo('{{ $aval }}', 'Aval del Evento')" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye"></i> Visualizar
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        @if($ci)
                        <div class="col-6 col-md-3">
                            <div class="text-center border rounded p-2">
                                <i class="fas fa-id-card fa-2x text-info"></i>
                                <p class="mb-1"><strong>Cédula</strong></p>
                                <button onclick="verArchivo('{{ $ci }}', 'Cédula de Identidad')" class="btn btn-info btn-sm w-100">
                                    <i class="fas fa-eye"></i> Visualizar
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        @if($seguro)
                        <div class="col-6 col-md-3">
                            <div class="text-center border rounded p-2">
                                <i class="fas fa-shield-alt fa-2x text-success"></i>
                                <p class="mb-1"><strong>Seguro</strong></p>
                                <button onclick="verArchivo('{{ $seguro }}', 'Seguro Médico')" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-eye"></i> Visualizar
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        @if($matricula)
                        <div class="col-6 col-md-3">
                            <div class="text-center border rounded p-2">
                                <i class="fas fa-graduation-cap fa-2x text-warning"></i>
                                <p class="mb-1"><strong>Matrícula</strong></p>
                                <button onclick="verArchivo('{{ $matricula }}', 'Matrícula Universitaria')" class="btn btn-warning btn-sm w-100">
                                    <i class="fas fa-eye"></i> Visualizar
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        @if(!$aval && !$ci && !$seguro && !$matricula)
                        <div class="col-12 text-center py-3">
                            <i class="fas fa-folder-open fa-3x text-muted"></i>
                            <p>No hay documentos disponibles</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($preinscripcion->tipo_inscripcion == 'grupal' && $preinscripcion->integrantes->where('es_capitan', false)->count() > 0)
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white py-1">Integrantes del Equipo ({{ $preinscripcion->integrantes->where('es_capitan', false)->count() }})</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr><th>#</th><th>Nombre</th><th>CI</th><th>Docs</th></tr>
                            </thead>
                            <tbody>
                                @foreach($preinscripcion->integrantes->where('es_capitan', false) as $index => $int)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $int->nombre }}</td>
                                    <td>{{ $int->ci }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            @if($int->documento_ci_path)
                                                <button onclick="verArchivo('{{ route('ver.documento', ['filename' => basename($int->documento_ci_path)]) }}', 'Cédula - {{ $int->nombre }}')" class="btn btn-outline-primary" title="Cédula">
                                                    <i class="fas fa-id-card"></i>
                                                </button>
                                            @endif
                                            @if($int->documento_seguro_path)
                                                <button onclick="verArchivo('{{ route('ver.documento', ['filename' => basename($int->documento_seguro_path)]) }}', 'Seguro - {{ $int->nombre }}')" class="btn btn-outline-success" title="Seguro">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                            @endif
                                            @if($int->documento_matricula_path)
                                                <button onclick="verArchivo('{{ route('ver.documento', ['filename' => basename($int->documento_matricula_path)]) }}', 'Matrícula - {{ $int->nombre }}')" class="btn btn-outline-warning" title="Matrícula">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                
                                @endforeach
                            </tbody>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="card-footer py-2">
            <div class="d-flex flex-wrap gap-2">
                @if($preinscripcion->estado == 'pendiente')
                    <button class="btn btn-success btn-sm" onclick="confirmar('habilitar', {{ $preinscripcion->id }})">Habilitar</button>
                    <button class="btn btn-warning btn-sm" onclick="observar({{ $preinscripcion->id }})">Observar</button>
                @elseif($preinscripcion->estado == 'observado')
                    <button class="btn btn-success btn-sm" onclick="confirmar('habilitar', {{ $preinscripcion->id }})">Habilitar</button>
                    <button class="btn btn-secondary btn-sm" onclick="confirmar('revertir', {{ $preinscripcion->id }})">Revertir</button>
                @elseif($preinscripcion->estado == 'habilitado')
                    <button class="btn btn-warning btn-sm" onclick="observar({{ $preinscripcion->id }})">Observar</button>
                @endif
                @if($preinscripcion->estado === 'habilitado')
                <a href="{{ route('archivador.credencial', $preinscripcion->id) }}"
                   class="btn btn-sm btn-success">
                    <i class="fas fa-id-card"></i> Descargar Credencial
                </a>
                @endif
                <a href="{{ route('archivador.historial', $preinscripcion->id) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-history"></i> Historial
                </a>
                <a href="{{ route('archivador.index') }}" class="btn btn-secondary btn-sm">Cerrar</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para visualizar archivos (PDF e Imágenes) -->
<div class="modal fade" id="visorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title" id="visorTitulo"><i class="fas fa-file"></i> Visualizador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="min-height: 550px; background: #525659;">
                <div id="loadingArchivo" class="text-center p-5 text-white">
                    <div class="spinner-border text-light mb-3" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando archivo...</p>
                </div>
                <div id="imagenContainer" style="text-align: center; display: none;">
                    <img id="visorImagen" src="" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
                </div>
                <div id="pdfContainer" style="text-align: center; display: none;">
                    <canvas id="pdfCanvas" style="margin: 0 auto; display: block; max-width: 100%; height: auto;"></canvas>
                </div>
                <div id="pdfControls" class="text-center p-2 bg-light" style="display: none;">
                    <button class="btn btn-sm btn-primary" id="btnPrev"><i class="fas fa-chevron-left"></i> Anterior</button>
                    <span id="pageNumDisplay" class="mx-2">1</span> / <span id="pageCountDisplay">0</span>
                    <button class="btn btn-sm btn-primary" id="btnNext">Siguiente <i class="fas fa-chevron-right"></i></button>
                </div>
                <div id="errorArchivo" class="text-center p-5 text-white" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-4x mb-3"></i>
                    <p>No se pudo cargar el archivo</p>
                </div>
            </div>
            <div class="modal-footer py-2">
                <a href="#" id="downloadLink" class="btn btn-primary">Descargar</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var pdfDoc = null;
var pageNum = 1;
var pageRendering = false;
var pageNumPending = null;
var canvas = null;
var ctx = null;

function verArchivo(url, titulo) {
    var extension = url.split('.').pop().toLowerCase();
    var esImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension);
    
    document.getElementById('loadingArchivo').style.display = 'block';
    document.getElementById('imagenContainer').style.display = 'none';
    document.getElementById('pdfContainer').style.display = 'none';
    document.getElementById('pdfControls').style.display = 'none';
    document.getElementById('errorArchivo').style.display = 'none';
    document.getElementById('visorTitulo').innerHTML = '<i class="fas ' + (esImagen ? 'fa-image' : 'fa-file-pdf') + '"></i> ' + titulo;
    document.getElementById('downloadLink').href = url;
    
    if (esImagen) {
        var img = document.getElementById('visorImagen');
        img.onload = function() {
            document.getElementById('loadingArchivo').style.display = 'none';
            document.getElementById('imagenContainer').style.display = 'block';
        };
        img.onerror = function() {
            document.getElementById('loadingArchivo').style.display = 'none';
            document.getElementById('errorArchivo').style.display = 'block';
        };
        img.src = url;
    } else {
        canvas = document.getElementById('pdfCanvas');
        ctx = canvas.getContext('2d');
        
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('pageCountDisplay').textContent = pdfDoc.numPages;
            document.getElementById('loadingArchivo').style.display = 'none';
            document.getElementById('pdfContainer').style.display = 'block';
            document.getElementById('pdfControls').style.display = 'block';
            renderPage(1);
        }).catch(function(error) {
            console.error('Error:', error);
            document.getElementById('loadingArchivo').style.display = 'none';
            document.getElementById('errorArchivo').style.display = 'block';
        });
    }
    
    var modal = new bootstrap.Modal(document.getElementById('visorModal'));
    modal.show();
}

function renderPage(num) {
    pageRendering = true;
    pdfDoc.getPage(num).then(function(page) {
        var viewport = page.getViewport({ scale: 1.2 });
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        var renderContext = { canvasContext: ctx, viewport: viewport };
        var renderTask = page.render(renderContext);
        renderTask.promise.then(function() {
            pageRendering = false;
            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        });
    });
    document.getElementById('pageNumDisplay').textContent = num;
    pageNum = num;
}

function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

document.getElementById('btnPrev').onclick = function() { if (pageNum <= 1) return; queueRenderPage(pageNum - 1); };
document.getElementById('btnNext').onclick = function() { if (!pdfDoc) return; if (pageNum >= pdfDoc.numPages) return; queueRenderPage(pageNum + 1); };

document.getElementById('visorModal').addEventListener('hidden.bs.modal', function() {
    if (canvas) { ctx.clearRect(0, 0, canvas.width, canvas.height); }
    pdfDoc = null;
    pageNum = 1;
});

function confirmar(tipo, id) {
    let msg = tipo === 'habilitar' ? '¿Habilitar este equipo?' : '¿Revertir a pendiente?';
    Swal.fire({title: msg, icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Sí'})
        .then(r => r.isConfirmed && (window.location.href = `/archivador/${id}/${tipo === 'habilitar' ? 'habilitar' : 'revertir'}`));
}

function observar(id) {
    Swal.fire({
        title: 'Observar equipo',
        input: 'textarea',
        inputPlaceholder: 'Motivo de la observación...',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        confirmButtonText: 'Observar',
        preConfirm: m => !m || m.length < 10 ? Swal.showValidationMessage('Mínimo 10 caracteres') : m
    }).then(r => r.value && (window.location.href = `/archivador/${id}/observar?motivo_observacion=${encodeURIComponent(r.value)}`));
}
</script>

<style>
    @media (max-width: 576px) {
        .container-fluid { padding-left: 8px; padding-right: 8px; }
        .btn-sm { font-size: 0.7rem; padding: 0.2rem 0.4rem; }
        .table-sm td, .table-sm th { padding: 0.3rem; font-size: 0.8rem; }
    }
    .gap-2 { gap: 0.5rem; }
    .btn-group-sm .btn { padding: 0.2rem 0.4rem; font-size: 0.7rem; }
    #pdfCanvas, #visorImagen { max-width: 100%; height: auto; }
</style>
@endsection