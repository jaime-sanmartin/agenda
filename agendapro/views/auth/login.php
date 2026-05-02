<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgendaPro Facilitador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header i {
            font-size: 60px;
            color: #667eea;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            font-weight: bold;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img height='80px' src='/agendapro/assets/img/default-logo.png'>
            <p class="text-muted"><b>Sistema de Gestión de Capacitaciones</b></p>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['reset']) && $_GET['reset'] == 'success'): ?>
        <div class="alert alert-success">Contraseña restablecida correctamente. Inicie sesión con su nueva contraseña.</div>
        <?php endif; ?>
        
        <form method="POST" action="/agendapro/auth/login">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>
            <button type="submit" class="btn btn-login">Ingresar</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="/agendapro/auth/recover" class="text-decoration-none">¿Olvidó su contraseña?</a>
        </div>

        <!-- Después del botón de login o antes de cerrar el formulario -->
        <div class="text-center mt-3">
            <a href="<?php echo BASE_URL; ?>/auth/solicitar_registro" class="text-decoration-none">
                <i class="fas fa-user-plus"></i> ¿No tiene cuenta? Solicite registro como facilitador
            </a>
        </div>

        <hr class="my-4">
        
        <div class="text-center text-muted small">
            <p>© 2024 AgendaPro Facilitador. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>