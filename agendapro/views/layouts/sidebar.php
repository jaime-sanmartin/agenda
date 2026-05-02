<?php
// views/layouts/sidebar.php
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand text-center py-3">
        <img height='50px' src='/agendapro/assets/img/default-logo.png' />
    </div>
    
    <div class="sidebar-user text-center py-3 border-bottom border-light">
        <div class="avatar-circle mx-auto mb-2">
            <span class="initials">
                <?php 
                $nombre = Session::user()['nombre'] ?? '';
                $iniciales = strtoupper(substr($nombre, 0, 2));
                echo $iniciales;
                ?>
            </span>
        </div>
        <div class="text-white">
            <strong><?php echo htmlspecialchars($nombre); ?></strong><br>
            <small class="text-white-50">
                <i class="fas fa-<?php echo Session::isFacilitador() ? 'chalkboard-teacher' : (Session::isAdministrador() ? 'user-shield' : 'building'); ?>"></i>
                <?php 
                if (Session::isAdministrador()) echo 'Administrador';
                elseif (Session::isFacilitador()) echo 'Facilitador';
                else echo 'Ejecutivo OTEC';
                ?>
            </small>
        </div>
    </div>
    
    <ul class="nav flex-column mt-3">
        <?php if (Session::isAdministrador()): ?>
            <!-- Menú Administrador -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard/admin">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/users">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/otec">
                    <i class="fas fa-building"></i>
                    <span>OTEC</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/courses">
                    <i class="fas fa-book"></i>
                    <span>Cursos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/bookings">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Reservas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/reports">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/solicitudes_pendientes">
                    <i class="fas fa-user-clock"></i>
                    <span>Solicitudes</span>
                </a>
            </li>
        <?php elseif (Session::isFacilitador()): ?>
            <!-- Menú Facilitador -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard/facilitador">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/calendar">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendario</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/otec">
                    <i class="fas fa-building"></i>
                    <span>Mis OTEC</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/users">
                    <i class="fas fa-users"></i>
                    <span>Mis Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/courses">
                    <i class="fas fa-book"></i>
                    <span>Mis Cursos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/bookings">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Reservas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/reports">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
        <?php else: ?>
            <!-- Menú Ejecutivo -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard/ejecutivo">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/calendar">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendario</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/courses/public">
                    <i class="fas fa-book-open"></i>
                    <span>Catálogo</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/bookings">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Mis Reservas</span>
                </a>
            </li>
        <?php endif; ?>
        
        <li class="nav-item mt-4">
            <hr class="border-light">
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/profile">
                <i class="fas fa-user"></i>
                <span>Mi Perfil</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/change-password">
                <i class="fas fa-key"></i>
                <span>Cambiar Contraseña</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="<?php echo BASE_URL; ?>/auth/logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer text-center py-3">
        <small class="text-white-50">
            <i class="fas fa-code-branch"></i> v1.0.0
            <br>
            © <?php echo date('Y'); ?>
        </small>
    </div>
    
    <!-- Botón para colapsar sidebar -->
    <button class="sidebar-toggle" title="Colapsar menú">
        <i class="fas fa-chevron-left"></i>
    </button>
</div>

<style>
/* Estilos específicos del sidebar */
.sidebar-brand {
    background: rgba(0, 0, 0, 0.1);
}
.avatar-circle {
    width: 60px;
    height: 60px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-circle .initials {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
}
.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.8);
    padding: 0.75rem 1rem;
    margin: 0.2rem 0;
    transition: all 0.3s;
}
.sidebar .nav-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}
.sidebar .nav-link.active {
    color: white;
    background: rgba(255, 255, 255, 0.2);
    border-left: 4px solid white;
}
.sidebar .nav-link i {
    width: 25px;
    margin-right: 10px;
    text-align: center;
}
.sidebar-footer {
    font-size: 0.75rem;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding-bottom: 60px; /* espacio para el botón de colapso */
}
@media (max-width: 992px) {
    .sidebar-footer {
        padding-bottom: 20px;
    }
}
</style>