<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - AgendaPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .reset-header i {
            font-size: 60px;
            color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .reset-header h2 {
            margin-top: 15px;
            color: #333;
            font-weight: 600;
        }
        .reset-header p {
            color: #666;
            font-size: 14px;
        }
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .strength-weak { width: 25%; background-color: #dc3545; }
        .strength-medium { width: 50%; background-color: #ffc107; }
        .strength-strong { width: 75%; background-color: #28a745; }
        .strength-very-strong { width: 100%; background-color: #20c997; }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="reset-header">
            <i class="fas fa-lock-open"></i>
            <h2>Restablecer contraseña</h2>
            <p>Ingresa tu nueva contraseña</p>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/auth/reset" id="resetForm">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" class="form-control" 
                           id="password" name="password" 
                           placeholder="Mínimo 6 caracteres" 
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength"></div>
                <small class="text-muted">La contraseña debe tener al menos 6 caracteres</small>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-check-circle text-muted"></i>
                    </span>
                    <input type="password" class="form-control" 
                           id="confirm_password" name="confirm_password" 
                           placeholder="Repite tu contraseña" 
                           required>
                </div>
                <div id="passwordMatch" class="small mt-1"></div>
            </div>
            
            <button type="submit" class="btn btn-reset" id="submitBtn">
                <i class="fas fa-save me-2"></i> Restablecer contraseña
            </button>
            
            <div class="text-center mt-3">
                <a href="<?php echo BASE_URL; ?>/auth/login" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
    
    <script>
    // Mostrar/ocultar contraseña
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Medidor de fuerza de contraseña
    document.getElementById('password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;
        
        strengthBar.className = 'password-strength';
        
        if (strength <= 2) {
            strengthBar.classList.add('strength-weak');
        } else if (strength === 3) {
            strengthBar.classList.add('strength-medium');
        } else if (strength === 4) {
            strengthBar.classList.add('strength-strong');
        } else {
            strengthBar.classList.add('strength-very-strong');
        }
    });
    
    // Validación de coincidencia de contraseñas
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const matchDiv = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    
    function validateMatch() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        if (confirm.length === 0) {
            matchDiv.innerHTML = '';
            matchDiv.className = '';
            submitBtn.disabled = false;
            return;
        }
        
        if (password === confirm) {
            matchDiv.innerHTML = '<i class="fas fa-check-circle text-success"></i> Las contraseñas coinciden';
            matchDiv.className = 'text-success';
            submitBtn.disabled = false;
        } else {
            matchDiv.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Las contraseñas no coinciden';
            matchDiv.className = 'text-danger';
            submitBtn.disabled = true;
        }
    }
    
    passwordInput?.addEventListener('input', validateMatch);
    confirmInput?.addEventListener('input', validateMatch);
    
    // Validación antes de enviar
    document.getElementById('resetForm')?.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        
        if (password.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            return false;
        }
        
        if (password !== confirmInput.value) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>