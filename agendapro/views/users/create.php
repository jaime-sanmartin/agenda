<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus"></i> Nuevo Ejecutivo / Asociar Ejecutivo
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/users/create" id="userForm">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        <input type="hidden" name="modo" id="modo" value="crear">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" 
                                       placeholder="ejemplo@dominio.cl" required>
                                <span class="input-group-text" id="emailStatus">
                                    <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                    <i class="fas fa-check-circle text-success" style="display: none;"></i>
                                    <i class="fas fa-exclamation-circle text-danger" style="display: none;"></i>
                                </span>
                            </div>
                            <div id="emailMessage" class="small mt-1"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="otec_id" class="form-label">OTEC <span class="text-danger">*</span></label>
                            <select class="form-select" id="otec_id" name="otec_id" required>
                                <option value="">Seleccione una OTEC...</option>
                                <?php foreach ($otecs as $otec): ?>
                                    <option value="<?php echo $otec['id']; ?>" 
                                        <?php echo (($data['otec_id'] ?? '') == $otec['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($otec['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div id="camposUsuario">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($data['nombre'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" 
                                           value="<?php echo htmlspecialchars($data['telefono'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div id="mensajeAsociado" class="alert alert-info" style="display: none;">
                            <i class="fas fa-info-circle"></i> Este email ya pertenece a un ejecutivo. Al hacer clic en "Asociar Ejecutivo", lo vincularás a la OTEC seleccionada sin duplicar información.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Guardar Ejecutivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let timeoutId;
document.getElementById('email').addEventListener('input', function(e) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        verificarEmail();
    }, 500);
});

document.getElementById('otec_id').addEventListener('change', function() {
    if (document.getElementById('modo').value === 'asociar') {
        verificarEmail(); // Re-verificar cuando cambie la OTEC
    }
});

function verificarEmail() {
    let email = document.getElementById('email').value;
    let otecId = document.getElementById('otec_id').value;
    if (!email || email.length < 5 || !otecId) return;
    
    let spinner = document.querySelector('#emailStatus .fa-spinner');
    let checkIcon = document.querySelector('#emailStatus .fa-check-circle');
    let errorIcon = document.querySelector('#emailStatus .fa-exclamation-circle');
    let emailMessage = document.getElementById('emailMessage');
    
    spinner.style.display = 'inline-block';
    checkIcon.style.display = 'none';
    errorIcon.style.display = 'none';
    emailMessage.innerHTML = '';
    
    fetch(BASE_URL + '/users/verificarEmail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: 'email=' + encodeURIComponent(email) + '&otec_id=' + encodeURIComponent(otecId)
    })
    .then(response => response.json())
    .then(data => {
        spinner.style.display = 'none';
        if (data.exists) {
            checkIcon.style.display = 'inline-block';
            emailMessage.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Email registrado. Puede asociar este ejecutivo.</span>';
            
            // Cargar datos del ejecutivo existente
            document.getElementById('nombre').value = data.data.nombre;
            document.getElementById('telefono').value = data.data.telefono || '';
            
            // Deshabilitar campos (no se pueden editar porque es asociación)
            document.getElementById('nombre').disabled = true;
            document.getElementById('telefono').disabled = true;
            
            // Cambiar modo
            document.getElementById('modo').value = 'asociar';
            document.getElementById('user_id').value = data.data.id;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-link"></i> Asociar Ejecutivo';
            document.getElementById('mensajeAsociado').style.display = 'block';
            
            if (data.ya_asociado) {
                emailMessage.innerHTML += '<br><span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Este ejecutivo ya está asociado a esta OTEC.</span>';
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-check"></i> Ya asociado';
            } else {
                document.getElementById('submitBtn').disabled = false;
            }
        } else {
            errorIcon.style.display = 'inline-block';
            emailMessage.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Email no registrado. Se creará un nuevo ejecutivo.</span>';
            
            // Habilitar campos
            document.getElementById('nombre').disabled = false;
            document.getElementById('telefono').disabled = false;
            
            document.getElementById('modo').value = 'crear';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Guardar Ejecutivo';
            document.getElementById('mensajeAsociado').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
        }
    })
    .catch(error => {
        spinner.style.display = 'none';
        errorIcon.style.display = 'inline-block';
        emailMessage.innerHTML = '<span class="text-danger">Error al verificar email.</span>';
        console.error(error);
    });
}

document.getElementById('userForm').addEventListener('submit', function(e) {
    if (document.getElementById('modo').value === 'asociar' && !document.getElementById('user_id').value) {
        e.preventDefault();
        alert('No se pudo identificar el ejecutivo a asociar.');
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>