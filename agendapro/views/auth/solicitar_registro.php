<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Registro - AgendaPro Facilitador</title>
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
        .register-card {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }
        .register-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-body {
            padding: 30px;
        }
        .btn-solicitar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-solicitar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card register-card">
                    <div class="register-header">
                        <i class="fas fa-user-plus fa-3x mb-3"></i>
                        <h3>Solicitar Registro como Facilitador</h3>
                        <p class="mb-0 opacity-75">Complete el formulario para solicitar acceso al sistema</p>
                    </div>
                    <div class="register-body">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> 
                                <?php 
                                if ($_GET['success'] == 'sent') {
                                    echo 'Su solicitud ha sido enviada correctamente. El administrador la revisará y se pondrá en contacto con usted.';
                                } elseif ($_GET['success'] == 'approved') {
                                    echo '¡Solicitud aprobada! Revise su correo electrónico para activar su cuenta.';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> 
                                <?php 
                                if ($_GET['error'] == 'email_exists') {
                                    echo 'Ya existe una solicitud pendiente o un usuario registrado con este email.';
                                } elseif ($_GET['error'] == 'invalid_token') {
                                    echo 'Token de activación inválido o expirado.';
                                } else {
                                    echo 'Error al procesar la solicitud. Intente nuevamente.';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo BASE_URL; ?>/auth/procesar_solicitud" id="solicitudForm">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user"></i> Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       placeholder="Ej: Juan Pérez González" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="ejemplo@dominio.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       placeholder="+569 1234 5678">
                            </div>
                            
                            <div class="mb-3">
                                <label for="rut" class="form-label">
                                    <i class="fas fa-id-card"></i> RUT
                                </label>
                                <input type="text" class="form-control" id="rut" name="rut" 
                                       placeholder="12.345.678-9">
                                <small class="text-muted">Opcional</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mensaje" class="form-label">
                                    <i class="fas fa-comment"></i> Mensaje (Opcional)
                                </label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="3" 
                                          placeholder="Cuéntenos por qué desea ser facilitador..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 btn-solicitar">
                                <i class="fas fa-paper-plane"></i> Enviar Solicitud
                            </button>
                        </form>
                        
                        <div class="login-link">
                            <a href="<?php echo BASE_URL; ?>/auth/login" class="text-decoration-none">
                                <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Formateador de RUT
        document.getElementById('rut').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\./g, '').replace(/-/g, '');
            if (value.length > 1) {
                let rut = value.slice(0, -1);
                let dv = value.slice(-1);
                rut = rut.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                e.target.value = rut + '-' + dv;
            }
        });
        
        // Validación de email
        document.getElementById('solicitudForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Por favor ingrese un email válido.');
            }
        });
    </script>
</body>
</html>