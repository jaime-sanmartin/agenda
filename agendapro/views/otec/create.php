<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building"></i> OTEC / Asociar OTEC
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/otec/create" id="otecForm">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        <input type="hidden" name="modo" id="modo" value="crear">
                        <input type="hidden" name="otec_id" id="otec_id" value="">
                        
                        <div class="mb-3">
                            <label for="rut" class="form-label">RUT <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="rut" name="rut" 
                                       value="<?php echo htmlspecialchars($data['rut'] ?? ''); ?>" 
                                       placeholder="Ej: 76.123.456-7" required>
                                <span class="input-group-text" id="rutStatus">
                                    <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                    <i class="fas fa-check-circle text-success" style="display: none;"></i>
                                    <i class="fas fa-exclamation-circle text-danger" style="display: none;"></i>
                                </span>
                            </div>
                            <small class="text-muted">Ingrese el RUT. Si ya existe, podrá asociarse a él.</small>
                            <div id="rutMessage" class="small mt-1"></div>
                        </div>
                        
                        <div id="camposOtec">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($data['nombre'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" 
                                       value="<?php echo htmlspecialchars($data['direccion'] ?? ''); ?>">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contacto" class="form-label">Persona de Contacto</label>
                                    <input type="text" class="form-control" id="contacto" name="contacto" 
                                           value="<?php echo htmlspecialchars($data['contacto'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" 
                                           value="<?php echo htmlspecialchars($data['telefono'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" checked>
                                    <label class="form-check-label" for="activo">Activo</label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="mensajeAsociado" class="alert alert-info" style="display: none;">
                            <i class="fas fa-info-circle"></i> Esta OTEC ya existe en el sistema. Al hacer clic en "Asociar OTEC", te vincularás a ella sin duplicar información.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/otec" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Guardar OTEC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Formateador de RUT
function formatRut(rut) {
    let valor = rut.replace(/\./g, '').replace(/-/g, '');
    if (valor.length <= 1) return rut;
    let cuerpo = valor.slice(0, -1);
    let dv = valor.slice(-1);
    cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return cuerpo + '-' + dv;
}

// Validar y verificar RUT en el servidor
let timeoutId;
document.getElementById('rut').addEventListener('input', function(e) {
    let rut = e.target.value;
    // Formatear visualmente
    e.target.value = formatRut(rut);
    
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        verificarRut();
    }, 500);
});

function verificarRut() {
    let rut = document.getElementById('rut').value;
    //if (rut.length < 8) return;
    
    let spinner = document.querySelector('#rutStatus .fa-spinner');
    let checkIcon = document.querySelector('#rutStatus .fa-check-circle');
    let errorIcon = document.querySelector('#rutStatus .fa-exclamation-circle');
    let rutMessage = document.getElementById('rutMessage');
    
    spinner.style.display = 'inline-block';
    checkIcon.style.display = 'none';
    errorIcon.style.display = 'none';
    rutMessage.innerHTML = '';
    
    fetch(BASE_URL + '/otec/verificarRut', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: 'rut=' + encodeURIComponent(rut)
    })
    .then(response => response.json())
    .then(data => {
        spinner.style.display = 'none';
        if (data.exists) {
            // RUT existe: cargar datos y cambiar a modo asociar
            checkIcon.style.display = 'inline-block';
            rutMessage.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> RUT registrado. Puede asociarse a esta OTEC.</span>';
            
            // Cargar datos de la OTEC
            document.getElementById('nombre').value = data.data.nombre;
            document.getElementById('direccion').value = data.data.direccion || '';
            document.getElementById('contacto').value = data.data.contacto || '';
            document.getElementById('telefono').value = data.data.telefono || '';
            document.getElementById('email').value = data.data.email || '';
            
            // Deshabilitar campos para evitar edición (solo lectura)
            document.getElementById('nombre').disabled = true;
            document.getElementById('direccion').disabled = true;
            document.getElementById('contacto').disabled = true;
            document.getElementById('telefono').disabled = true;
            document.getElementById('email').disabled = true;
            document.getElementById('activo').disabled = true;
            
            // Cambiar modo y texto del botón
            document.getElementById('modo').value = 'asociar';
            document.getElementById('otec_id').value = data.data.id;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-link"></i> Asociar OTEC';
            document.getElementById('mensajeAsociado').style.display = 'block';
            
            if (data.ya_asociado) {
                rutMessage.innerHTML += '<br><span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Ya estás asociado a esta OTEC.</span>';
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check"></i> Ya asociado';
            } else {
                document.getElementById('submitBtn').disabled = false;
            }
        } else {
            // RUT no existe: modo crear normal
            errorIcon.style.display = 'inline-block';
            rutMessage.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> RUT no registrado. Puede crear una nueva OTEC.</span>';
            
            // Habilitar campos
            document.getElementById('nombre').disabled = false;
            document.getElementById('direccion').disabled = false;
            document.getElementById('contacto').disabled = false;
            document.getElementById('telefono').disabled = false;
            document.getElementById('email').disabled = false;
            document.getElementById('activo').disabled = false;
            
            document.getElementById('modo').value = 'crear';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Guardar OTEC';
            document.getElementById('mensajeAsociado').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
        }
    })
    .catch(error => {
        spinner.style.display = 'none';
        errorIcon.style.display = 'inline-block';
        rutMessage.innerHTML = '<span class="text-danger">Error al verificar RUT.</span>';
        console.error(error);
    });
}

// Antes de enviar, asegurar que si es modo asociar, se incluya el otec_id
document.getElementById('otecForm').addEventListener('submit', function(e) {
    if (document.getElementById('modo').value === 'asociar' && !document.getElementById('otec_id').value) {
        e.preventDefault();
        alert('No se pudo determinar la OTEC a asociar.');
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>