<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Recomendaciones para una contraseña segura:
                        <ul class="mb-0 mt-2">
                            <li>Mínimo 6 caracteres</li>
                            <li>Incluir letras mayúsculas y minúsculas</li>
                            <li>Incluir números</li>
                            <li>Incluir caracteres especiales (!@#$%)</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/change-password" id="passwordForm">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña Actual <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key"></i> Nueva Contraseña <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                </div>
                                <small id="strengthText" class="text-muted">Ingrese una contraseña</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-check-circle"></i> Confirmar Nueva Contraseña <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <small id="confirmMessage" class="text-muted"></small>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>/auth/profile" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Perfil
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Medidor de fortaleza de contraseña
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    return Math.min(strength, 5);
}

function updateStrengthBar() {
    const password = document.getElementById('new_password').value;
    const strength = checkPasswordStrength(password);
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    
    let width = 0;
    let color = '';
    let message = '';
    
    switch(strength) {
        case 0:
            width = 0;
            color = '#e74a3b';
            message = 'Muy débil';
            break;
        case 1:
            width = 20;
            color = '#e74a3b';
            message = 'Débil';
            break;
        case 2:
            width = 40;
            color = '#f6c23e';
            message = 'Regular';
            break;
        case 3:
            width = 60;
            color = '#f6c23e';
            message = 'Buena';
            break;
        case 4:
            width = 80;
            color = '#1cc88a';
            message = 'Fuerte';
            break;
        case 5:
            width = 100;
            color = '#1cc88a';
            message = 'Muy fuerte';
            break;
    }
    
    bar.style.width = width + '%';
    bar.style.backgroundColor = color;
    text.innerHTML = message;
    text.style.color = color;
}

function checkConfirmPassword() {
    const password = document.getElementById('new_password').value;
    const confirm = document.getElementById('confirm_password').value;
    const message = document.getElementById('confirmMessage');
    
    if (confirm.length > 0) {
        if (password === confirm) {
            message.innerHTML = '<i class="fas fa-check-circle text-success"></i> Las contraseñas coinciden';
            message.style.color = '#1cc88a';
        } else {
            message.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Las contraseñas no coinciden';
            message.style.color = '#e74a3b';
        }
    } else {
        message.innerHTML = '';
    }
}

document.getElementById('new_password').addEventListener('input', updateStrengthBar);
document.getElementById('confirm_password').addEventListener('input', checkConfirmPassword);

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('new_password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Las contraseñas nuevas no coinciden.');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('La nueva contraseña debe tener al menos 6 caracteres.');
        return false;
    }
});
</script>

<style>
.password-strength {
    margin-top: 8px;
}

.progress {
    background-color: #e3e6f0;
    border-radius: 10px;
}

#strengthBar {
    transition: all 0.3s ease;
    border-radius: 10px;
}

#strengthText {
    font-size: 0.75rem;
    transition: all 0.3s ease;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>