<form id="preinscripcionForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tipo_inscripcion" id="tipo_inscripcion_hidden" value="grupal">
    
    <div class="alert alert-info">
        <strong>{{ $evento->nombre }}</strong><br>
        {{ $evento->descripcion ?? '' }}
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="text-white">Tipo de Inscripción *</label>
            <select name="tipo_inscripcion_select" id="tipo_inscripcion_select" class="form-control" required>
                <option value="individual">📝 Individual</option>
                <option value="grupal" selected>👥 Grupal (Equipo)</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="text-white">Disciplina *</label>
            <select name="disciplina_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($disciplinas as $disciplina)
                    @if($disciplina->sub_disciplines && $disciplina->sub_disciplines->count() > 0)
                        <optgroup label="{{ $disciplina->nombre }}">
                            @foreach($disciplina->sub_disciplines as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->nombre }} ({{ $sub->codigo }})</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $disciplina->id }}">{{ $disciplina->nombre }} ({{ $disciplina->codigo }})</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    
    <!-- SECCIÓN GRUPAL (SIN required para que no interfiera con individual) -->
    <div id="seccionGrupal">
        <hr>
        <h5 class="text-white"><i class="fas fa-users"></i> DATOS DEL EQUIPO</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <input type="text" name="nombre_equipo" class="form-control" placeholder="Nombre del equipo">
            </div>
            <div class="col-md-4 mb-3">
                <input type="number" name="cantidad_integrantes" id="cantidadIntegrantes" class="form-control" min="2" max="10" value="2">
                <small class="text-muted">Incluye al capitán (mínimo 2)</small>
            </div>
            <div class="col-md-4 mb-3">
                @if($evento->tipo_evento === 'intercarreras')
                    <select name="carrera_id" class="form-control">
                        <option value="">Seleccione carrera del equipo</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN INDIVIDUAL -->
    <div id="seccionIndividual" style="display: none;">
        <hr>
        <h5 class="text-white"><i class="fas fa-user"></i> DATOS DEL PARTICIPANTE</h5>
        <div class="row">
            @if($evento->tipo_evento === 'olimpiadas')
            <div class="col-md-12 mb-3">
                <label class="text-white">Facultad *</label>
                <select name="facultad_id_individual" class="form-control">
                    <option value="">Seleccione facultad</option>
                    @foreach($facultades as $facultad)
                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Facultad a la que pertenece</small>
            </div>
            @elseif($evento->tipo_evento === 'intercarreras')
            <div class="col-md-12 mb-3">
                <label class="text-white">Carrera *</label>
                <select name="carrera_id_individual" class="form-control">
                    <option value="">Seleccione carrera</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Carrera a la que pertenece</small>
            </div>
            @endif
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12 mb-3">
            <label class="text-white">Aval de participación *</label>
            <input type="file" name="documento_aval" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>
    </div>
    
    <!-- SECCIÓN FACULTAD PARA OLIMPIADAS (GRUPAL) -->
    @if($evento->tipo_evento === 'olimpiadas')
    <div class="mb-3" id="facultadGrupal">
        <label class="text-white">Facultad *</label>
        <select name="facultad_id" class="form-control" required>
            <option value="">Seleccione facultad</option>
            @foreach($facultades as $facultad)
                <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
            @endforeach
        </select>
        <small class="text-muted">Todos los integrantes deben pertenecer a esta facultad</small>
    </div>
    @endif
    
    <hr>
    <h5 class="text-white"><i class="fas fa-user-captain"></i> CAPITÁN DEL EQUIPO / PARTICIPANTE</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" name="representante_nombre" class="form-control" placeholder="Nombre completo" required>
        </div>
        <div class="col-md-6 mb-3">
            <input type="text" name="representante_ci" class="form-control" placeholder="Cédula de Identidad" required>
        </div>
        <div class="col-md-6 mb-3">
            <input type="email" name="representante_email" class="form-control" placeholder="Correo electrónico" required>
        </div>
        <div class="col-md-6 mb-3">
            <input type="text" name="representante_telefono" class="form-control" placeholder="Teléfono" required>
        </div>
    </div>
    
    <h5 class="text-white"><i class="fas fa-file-upload"></i> Documentos del Capitán / Participante</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="text-white">Cédula *</label>
            <input type="file" name="documento_ci_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="text-white">Seguro *</label>
            <input type="file" name="documento_seguro_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="text-white">Matrícula *</label>
            <input type="file" name="documento_matricula_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>
    </div>
    
    <div id="integrantesContainer"></div>
    
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-save"></i> Enviar Pre-inscripción
        </button>
    </div>
</form>