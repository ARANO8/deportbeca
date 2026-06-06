<form id="preinscripcionForm" enctype="multipart/form-data">
    @csrf
    
    <div class="alert alert-info">
        <strong>{{ $evento->nombre }}</strong><br>
        {{ $evento->descripcion }}
        @if($evento->fecha_inicio && $evento->fecha_fin)
            <br><small>Válido del {{ $evento->fecha_inicio->format('d/m/Y') }} al {{ $evento->fecha_fin->format('d/m/Y') }}</small>
        @endif
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="text-white">Tipo de Inscripción *</label>
            <select name="tipo_inscripcion" id="tipo_inscripcion" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="individual">Individual</option>
                <option value="grupal">Grupal (Equipo)</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="text-white">Disciplina *</label>
            <select name="disciplina_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($disciplinas as $d)
                    @if($d->subDisciplines->count())
                        <optgroup label="{{ $d->nombre }}">
                            @foreach($d->subDisciplines as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->nombre }} ({{ $sub->codigo }})</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $d->id }}">{{ $d->nombre }} ({{ $d->codigo }})</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    
    @if($evento->tipo_evento === 'olimpiadas')
    <div class="mb-3">
        <label class="text-white">Facultad del Equipo *</label>
        <select name="facultad_id" class="form-control" required>
            <option value="">Seleccione facultad</option>
            @foreach($facultades as $f)
                <option value="{{ $f->id }}">{{ $f->nombre }}</option>
            @endforeach
        </select>
        <small class="text-muted">Todos los integrantes deben pertenecer a esta facultad</small>
    </div>
    @endif
    
    <hr>
    <h5 class="text-white"><i class="fas fa-user-captain"></i> Datos del Capitán / Representante</h5>
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
            <input type="text" name="representante_telefono" class="form-control" placeholder="Teléfono / Celular" required>
        </div>
        
        @if($evento->tipo_evento === 'intercarreras')
        <div class="col-md-12 mb-3">
            <label class="text-white">Carrera del Capitán *</label>
            <select name="carrera_id" class="form-control" required>
                <option value="">Seleccione carrera</option>
                @foreach($carreras as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    
    <hr>
    <h5 class="text-white"><i class="fas fa-file-upload"></i> Documentos Requeridos (JPG, PNG, PDF - Max 5MB)</h5>
    <div class="row">
    <div class="col-md-6 mb-3">
        <label class="text-white">Cédula de Identidad *</label>
        <!-- ANTES: name="documento_ci" -->
        <input type="file" name="documento_ci_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-white">Seguro Médico *</label>
        <!-- ANTES: name="documento_seguro" -->
        <input type="file" name="documento_seguro_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-white">Matrícula Universitaria *</label>
        <!-- ANTES: name="documento_matricula" -->
        <input type="file" name="documento_matricula_capitan" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="text-white">Aval del Evento *</label>
        <input type="file" name="documento_aval" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
    </div>
</div>
    
    <div id="formulario_grupal" style="display:none">
        <hr>
        <h5 class="text-white"><i class="fas fa-users"></i> Datos del Equipo</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="nombre_equipo" id="nombre_equipo" class="form-control" placeholder="Nombre del equipo">
            </div>
            <div class="col-md-6 mb-3">
                <input type="number" name="cantidad_integrantes" id="cantidad_integrantes" class="form-control" 
                       min="{{ $evento->min_integrantes_grupal }}" max="{{ $evento->max_integrantes_grupal }}" 
                       placeholder="Cantidad de integrantes">
            </div>
        </div>
        <div id="integrantes_container"></div>
        
        @if($evento->tipo_evento === 'olimpiadas')
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle"></i> Todos los integrantes deben pertenecer a la misma facultad seleccionada anteriormente.
        </div>
        @elseif($evento->tipo_evento === 'intercarreras')
        <div class="alert alert-warning mt-3">
            <i class="fas fa-info-circle"></i> Cada integrante debe seleccionar su propia carrera.
        </div>
        @else
        <div class="alert alert-secondary mt-3">
            <i class="fas fa-info-circle"></i> Complete los datos de los integrantes del equipo.
        </div>
        @endif
    </div>
    
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-save"></i> Enviar Pre-inscripción
        </button>
    </div>
</form>

<script>
document.getElementById('tipo_inscripcion').addEventListener('change', function() {
    const div = document.getElementById('formulario_grupal');
    div.style.display = this.value === 'grupal' ? 'block' : 'none';
    if(this.value === 'grupal') generarCampos();
});

function generarCampos() {
    const cantidad = document.getElementById('cantidad_integrantes').value || 2;
    const container = document.getElementById('integrantes_container');
    const tipoEvento = '{{ $evento->tipo_evento }}';
    const carrerasHtml = `@foreach($carreras as $c)<option value="{{ $c->id }}">{{ $c->nombre }}</option>@endforeach`;

    container.innerHTML = '';
    for (let i = 2; i <= cantidad; i++) {
        let carreraField = '';
        if (tipoEvento === 'intercarreras') {
            carreraField = `
                <div class="col-md-12 mb-2">
                    <label class="text-white" style="font-size:0.85rem;">Carrera *</label>
                    <select name="integrantes[${i}][carrera_id]" class="form-control" required>
                        <option value="">Seleccione carrera</option>
                        ${carrerasHtml}
                    </select>
                </div>`;
        }

        container.innerHTML += `
            <div class="card mt-3 p-3" style="background: #0f172a; border: 1px solid #334155;">
                <strong class="text-white mb-2 d-block">
                    <i class="fas fa-user"></i> Integrante ${i}
                </strong>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <input type="text" name="integrantes[${i}][nombre]"
                               class="form-control" placeholder="Nombre completo" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="text" name="integrantes[${i}][ci]"
                               class="form-control" placeholder="Cédula de Identidad" required>
                    </div>
                    ${carreraField}
                </div>

                <hr style="border-color:#334155;">
                <small class="text-muted mb-2 d-block">
                    <i class="fas fa-file-upload"></i> Documentos del Integrante ${i} (JPG, PNG, PDF - Max 5MB)
                </small>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-white" style="font-size:0.85rem;">Cédula de Identidad *</label>
                        <input type="file" name="integrantes[${i}][documento_ci]"
                               class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-white" style="font-size:0.85rem;">Seguro Médico *</label>
                        <input type="file" name="integrantes[${i}][documento_seguro]"
                               class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-white" style="font-size:0.85rem;">Matrícula Universitaria *</label>
                        <input type="file" name="integrantes[${i}][documento_matricula]"
                               class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                </div>
            </div>
        `;
    }
}
document.getElementById('cantidad_integrantes')?.addEventListener('change', generarCampos);

document.getElementById('preinscripcionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    Swal.fire({ title: 'Enviando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    
    fetch('{{ route("preinscripcion.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        Swal.close();
        if(data.success) {
            Swal.fire('✅ Éxito', `${data.message}\n\nCódigo de inscripción: ${data.codigo}\nGuarde este código para verificar su estado.`, 'success');
            setTimeout(() => location.reload(), 3000);
        } else {
            Swal.fire('❌ Error', data.message, 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Error al enviar', 'error'));
});
</script>