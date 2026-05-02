<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle"></i> Mi Perfil
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
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/auth/profile">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="text-center mb-4">
                                    <div class="avatar-circle-large mx-auto mb-3">
                                        <span class="initials-large">
                                            <?php 
                                            $nombre = $user['nombre'] ?? '';
                                            $iniciales = strtoupper(substr($nombre, 0, 2));
                                            echo $iniciales;
                                            ?>
                                        </span>
                                    </div>
                                    <h5 class="mb-0"><?php echo htmlspecialchars($user['nombre'] ?? ''); ?></h5>
                                    <span class="badge bg-primary mt-2">
                                        <?php echo ucfirst($user['rol'] ?? ''); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-user"></i> Nombre Completo
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                                <small class="text-muted">El email no se puede modificar</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>"
                                       placeholder="+569 1234 5678">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rol" class="form-label">
                                    <i class="fas fa-tag"></i> Rol
                                </label>
                                <input type="text" class="form-control" id="rol" name="rol" 
                                       value="<?php echo ucfirst($user['rol'] ?? ''); ?>" disabled>
                            </div>
                        </div>
                        
                        <?php if (($user['rol'] ?? '') == 'ejecutivo' && !empty($user['otec_nombre'])): ?>
                        <div class="mb-3">
                            <label for="otec" class="form-label">
                                <i class="fas fa-building"></i> OTEC Asignada
                            </label>
                            <input type="text" class="form-control" id="otec" name="otec" 
                                   value="<?php echo htmlspecialchars($user['otec_nombre']); ?>" disabled>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="created_at" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Miembro desde
                                </label>
                                <input type="text" class="form-control" id="created_at" name="created_at" 
                                       value="<?php echo date('d/m/Y', strtotime($user['created_at'] ?? 'now')); ?>" disabled>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_login" class="form-label">
                                    <i class="fas fa-clock"></i> Último acceso
                                </label>
                                <input type="text" class="form-control" id="last_login" name="last_login" 
                                       value="<?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Primera vez'; ?>" disabled>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <!-- Botón Volver al Dashboard con redirección según rol -->
                            <a href="<?php echo BASE_URL; ?>/dashboard/<?php echo strtolower($user['rol'] ?? 'facilitador'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Dashboard
                            </a>
                            <div>
                                <a href="<?php echo BASE_URL; ?>/auth/change-password" class="btn btn-info me-2">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Perfil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle-large {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
}

.initials-large {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>