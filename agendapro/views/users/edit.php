<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit"></i> Editar Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/users/edit/<?php echo $user['id']; ?>" id="userForm">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rut" class="form-label">
                                    <i class="fas fa-id-card"></i> RUT
                                </label>
                                <input type="text" class="form-control" id="rut" name="rut" 
                                       value="<?php echo htmlspecialchars($user['rut'] ?? ''); ?>" 
                                       placeholder="Ej: 12.345.678-9">
                                <small class="text-muted">Opcional, formato: 12.345.678-9</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user"></i> Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="text" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rol" class="form-label">
                                    <i class="fas fa-tag"></i> Rol <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="rol" name="rol" required>
                                    <option value="">Seleccione un rol...</option>
                                    <option value="facilitador" <?php echo ($user['rol'] == 'facilitador') ? 'selected' : ''; ?>>
                                        <i class="fas fa-crown"></i> Facilitador
                                    </option>
                                    <option value="ejecutivo" <?php echo ($user['rol'] == 'ejecutivo') ? 'selected' : ''; ?>>
                                        <i class="fas fa-building"></i> Ejecutivo OTEC
                                    </option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="otecField" style="display: none;">
                                <label for="otec_id" class="form-label">
                                    <i class="fas fa-building"></i> OTEC Asignada
                                </label>
                                <select class="form-select" id="otec_id" name="otec_id">
                                    <option value="">Seleccione una OTEC...</option>
                                    <?php if (!empty($otecs)): ?>
                                        <?php foreach ($otecs as $otec): ?>
                                            <option value="<?php echo $otec['id']; ?>" <?php echo ($user['otec_id'] == $otec['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($otec['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Requerido para usuarios con rol Ejecutivo</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key"></i> Nueva Contraseña
                                </label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted">Dejar en blanco para mantener la actual. Mínimo 6 caracteres.</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-check-circle"></i> Confirmar Nueva Contraseña
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" 
                                       <?php echo ($user['activo'] == 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="activo">
                                    <i class="fas fa-check-circle text-success"></i> Usuario Activo
                                </label>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/users" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Formateador de RUT automático
const rutInput = document.getElementById('rut');
if (rutInput) {
    rutInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\./g, '').replace(/-/g, '');
        if (value.length > 1) {
            let rut = value.slice(0, -1);
            let dv = value.slice(-1);
            rut = rut.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            e.target.value = rut + '-' + dv;
        }
    });
}

// Mostrar/ocultar campo OTEC según rol
const rolSelect = document.getElementById('rol');
const otecField = document.getElementById('otecField');

function toggleOtecField() {
    if (rolSelect.value === 'ejecutivo') {
        otecField.style.display = 'block';
        document.getElementById('otec_id').required = true;
    } else {
        otecField.style.display = 'none';
        document.getElementById('otec_id').required = false;
    }
}

rolSelect.addEventListener('change', toggleOtecField);
toggleOtecField();

// Validar contraseñas coincidentes (solo si se ingresa nueva)
const form = document.getElementById('userForm');
form.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        return false;
    }
    
    if (password.length > 0 && password.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres.');
        return false;
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>