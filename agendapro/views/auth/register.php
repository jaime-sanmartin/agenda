<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AgendaPro</title>
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
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
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
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header i {
            font-size: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .register-header h2 {
            margin-top: 15px;
            color: #333;
            font-weight: 600;
        }
        .register-header p {
            color: #666;
            font-size: 14px;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-register:hover {
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
        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 10px 0 0 10px;
        }
        .alert {
            border-radius: 10px;
        }
        .terms {
            font-size: 12px;
            text-align: center;
            color: #666;
        }
        .terms a {
            color: #667eea;
            text-decoration: none;
        }
        .terms a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h2>Crear cuenta</h2>
            <p>Regístrate como ejecutivo de OTEC</p>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/auth/register" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           placeholder="Juan Pérez" required
                           value="<?php echo htmlspecialchars($data['nombre'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="ejemplo@correo.com" required
                           value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                           placeholder="+56912345678"
                           value="<?php echo htmlspecialchars($data['telefono'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="otec_id" class="form-label">OTEC a la que perteneces</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                    <select class="form-select" id="otec_id" name="otec_id" required>
                        <option value="">Selecciona una OTEC</option>
                        <?php foreach ($otecs as $otec): ?>
                        <option value="<?php echo $otec['id']; ?>" 
                            <?php echo (isset($data['otec_id']) && $data['otec_id'] == $otec['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($otec['nombre']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Mínimo 6 caracteres" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength mt-1" id="passwordStrength"></div>
                <small class="text-muted">La contraseña debe tener al menos 6 caracteres</small>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                    <input type="password" class="form-control" id="confirm_password" 
                           name="confirm_password" placeholder="Repite tu contraseña" required>
                </div>
                <div id="passwordMatch" class="small mt-1"></div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">términos y condiciones</a>
                </label>
            </div>
            
            <button type="submit" class="btn btn-register" id="submitBtn">
                <i class="fas fa-user-plus me-2"></i> Registrarse
            </button>
            
            <div class="text-center mt-3">
                <a href="<?php echo BASE_URL; ?>/auth/login" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>
        </form>
        
        <hr class="my-4">
        
        <div class="terms">
            <p>© <?php echo date('Y'); ?> AgendaPro Facilitador. Todos los derechos reservados.</p>
        </div>
    </div>
    
    <!-- Modal de Términos y Condiciones -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <h6>1. Aceptación de los términos</h6>
                    <p>Al registrarse en AgendaPro Facilitador, usted acepta estos términos y condiciones.</p>
                    
                    <h6>2. Uso del sistema</h6>
                    <p>El sistema está diseñado exclusivamente para la gestión de capacitaciones entre facilitadores y OTEC.</p>
                    
                    <h6>3. Confidencialidad</h6>
                    <p>Toda la información compartida en el sistema es confidencial y no debe ser divulgada a terceros.</p>
                    
                    <h6>4. Responsabilidad</h6>
                    <p>El usuario es responsable de la veracidad de la información ingresada en el sistema.</p>
                    
                    <h6>5. Modificaciones</h6>
                    <p>Nos reservamos el derecho de modificar estos términos en cualquier momento.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
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
        
        strengthBar.className = '';
        
        if (strength <= 2) {
            strengthBar.innerHTML = '<div class="strength-weak" style="height: 3px; width: 25%; background-color: #dc3545;"></div>';
        } else if (strength === 3) {
            strengthBar.innerHTML = '<div class="strength-medium" style="height: 3px; width: 50%; background-color: #ffc107;"></div>';
        } else if (strength === 4) {
            strengthBar.innerHTML = '<div class="strength-strong" style="height: 3px; width: 75%; background-color: #28a745;"></div>';
        } else {
            strengthBar.innerHTML = '<div class="strength-very-strong" style="height: 3px; width: 100%; background-color: #20c997;"></div>';
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
    document.getElementById('registerForm')?.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const terms = document.getElementById('terms');
        
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
        
        if (!terms.checked) {
            e.preventDefault();
            alert('Debes aceptar los términos y condiciones');
            return false;
        }
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>