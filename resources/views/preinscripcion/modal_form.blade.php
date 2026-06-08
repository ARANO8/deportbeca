<form id="preinscripcionForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="tipo_inscripcion" id="tipo_inscripcion_hidden" value="">

    <div class="alert alert-info">
        <strong>{{ $evento->nombre }}</strong><br>
        {{ $evento->descripcion ?? '' }}
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="text-white">Disciplina *</label>
            <select name="disciplina_id" id="disciplinaSelect" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($disciplinas as $d)
                    @php $rg = $rangos[$d->id] ?? ['grupal' => ['min' => 1, 'max' => 1, 'permite' => true], 'individual' => ['min' => 1, 'max' => 1, 'permite' => true]]; @endphp
                    <option value="{{ $d->id }}"
                        data-permite-grupal="{{ $rg['grupal']['permite'] ? 1 : 0 }}"
                        data-permite-individual="{{ $rg['individual']['permite'] ? 1 : 0 }}"
                        data-grupal-min="{{ $rg['grupal']['min'] }}"
                        data-grupal-max="{{ $rg['grupal']['max'] }}"
                        data-individual-min="{{ $rg['individual']['min'] }}"
                        data-individual-max="{{ $rg['individual']['max'] }}">
                        {{ $d->nombre }}@if($d->codigo) ({{ $d->codigo }})@endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="text-white">Tipo de Inscripcion *</label>
            <select name="tipo_inscripcion_select" id="tipo_inscripcion_select" class="form-control" required>
                <option value="">Seleccione disciplina primero</option>
                <option value="grupal">Grupal (Equipo)</option>
                <option value="individual">Individual</option>
            </select>
        </div>
    </div>

    {{-- Cantidad de personas: rango definido por la disciplina + modalidad --}}
    <div class="mb-3" id="cantidadWrap" style="display:none;">
        <label class="text-white" id="cantidadLabel">Cantidad de integrantes *</label>
        <input type="number" name="cantidad_integrantes" id="cantidadIntegrantes" class="form-control" min="1" value="1">
        <small class="text-muted" id="cantidadHint"></small>
    </div>

    {{-- Datos del equipo (solo grupal) --}}
    <div id="seccionGrupal" style="display:none;">
        <div class="mb-3">
            <input type="text" name="nombre_equipo" class="form-control" placeholder="Nombre del equipo">
        </div>
    </div>

    {{-- Facultad / carrera de la inscripcion (segun tipo de evento) --}}
    @if($evento->tipo_evento === 'olimpiadas')
    <div class="mb-3" id="facultadWrap" style="display:none;">
        <label class="text-white">Facultad *</label>
        <select name="facultad_id" id="facultadInscripcion" class="form-control">
            <option value="">Seleccione facultad</option>
            @foreach($facultades as $facultad)
                <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
            @endforeach
        </select>
        <small class="text-muted">Todos los participantes pertenecen a esta facultad.</small>
    </div>
    @elseif($evento->tipo_evento === 'intercarreras')
    <div class="mb-3" id="carreraWrap" style="display:none;">
        <label class="text-white">Carrera *</label>
        <select name="carrera_id" id="carreraInscripcion" class="form-control">
            <option value="">Seleccione carrera</option>
            @foreach($carreras as $carrera)
                <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
            @endforeach
        </select>
        <small class="text-muted">Todos los participantes pertenecen a esta carrera.</small>
    </div>
    @endif

    {{-- Aval --}}
    <div class="mb-3" id="avalWrap" style="display:none;">
        <label class="text-white">Aval de participacion *</label>
        <input type="file" name="documento_aval" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
    </div>

    {{-- Persona principal: capitan (grupal) / representante 1 (individual) --}}
    <div id="seccionPrincipal" style="display:none;">
        <hr>
        <h5 class="text-white"><i class="fas fa-user"></i> <span id="tituloPrincipal">Capitan / Representante</span></h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="representante_nombre" class="form-control" placeholder="Nombre completo">
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="representante_ci" class="form-control" placeholder="Cedula de Identidad">
            </div>
            <div class="col-md-6 mb-3">
                <input type="email" name="representante_email" class="form-control" placeholder="Correo electronico">
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="representante_telefono" class="form-control" placeholder="Telefono">
            </div>
        </div>
        <h6 class="text-white"><i class="fas fa-file-upload"></i> Documentos (JPG, PNG, PDF - Max 5MB)</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="text-white small">Cedula *</label>
                <input type="file" name="documento_ci_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <div class="col-md-4 mb-3">
                <label class="text-white small">Seguro *</label>
                <input type="file" name="documento_seguro_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <div class="col-md-4 mb-3">
                <label class="text-white small">Matricula *</label>
                <input type="file" name="documento_matricula_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>
    </div>

    {{-- Participantes adicionales (personas 2..N), generados por JS --}}
    <div id="integrantesContainer"></div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary btn-lg px-5" id="btnEnviarPre" disabled>
            <i class="fas fa-save"></i> Enviar Pre-inscripcion
        </button>
    </div>
</form>
