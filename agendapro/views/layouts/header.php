<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="<?php echo Security::generateCSRFToken(); ?>">
    <title><?php echo $title ?? 'Agendify'; ?></title>
    <link rel="icon" type="image/ico" href="<?php echo BASE_URL; ?>/assets/img/favicon.ico">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/assets/css/style.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #D4E6F1;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', 'Segoe UI', 'Roboto', sans-serif;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.75rem 1rem;
            background-color: #D4E6F1;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .dropdown-menu {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.35rem;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .text-xs {
            font-size: 0.7rem;
        }
        
        .font-weight-bold {
            font-weight: 700 !important;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
        }
        
        .pagination .page-link {
            color: #4e73df;
        }
        
        .pagination .active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        /* Loading spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }
        
        /* Notificaciones toast */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9998;
            min-width: 300px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1rem;
            }
            .card-header h6 {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<!-- Loading Spinner -->
<div class="spinner-overlay" id="spinnerOverlay">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-notification" id="toastContainer"></div>

<?php if (isset($_SESSION['user'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/dashboard/<?php echo strtolower($_SESSION['user_rol'] ?? 'facilitador'); ?>">
            <img src="<?php echo BASE_URL; ?>/assets/img/default-logo-header.png" style='vertical-align: top; max-height: 60px; height: 50%; width: auto;' class="img-fluid login-logo" ><br>
        </a> 
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (Session::isAdministrador()): ?>
                    <!-- Menú para ADMINISTRADOR -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard/facilitador">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/calendar">
                            <i class="fas fa-calendar"></i> Calendario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/users">
                            <i class="fas fa-users"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/otec">
                            <i class="fas fa-building"></i> OTEC
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/courses">
                            <i class="fas fa-book"></i> Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/bookings">
                            <i class="fas fa-ticket-alt"></i> Reservas
                        </a>
                    </li>
                <?php elseif (Session::isFacilitador()): ?>
                    <!-- Menú para FACILITADOR -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard/facilitador">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/calendar">
                            <i class="fas fa-calendar"></i> Calendario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/otec">
                            <i class="fas fa-building"></i> Mis OTEC
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/users">
                            <i class="fas fa-users"></i> Mis Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/courses">
                            <i class="fas fa-book"></i> Mis Cursos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/bookings">
                            <i class="fas fa-ticket-alt"></i> Reservas
                        </a>
                    </li>
                <?php elseif (Session::isEjecutivo()): ?>
                    <!-- Menú para EJECUTIVO -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/dashboard/facilitador">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/calendar">
                            <i class="fas fa-calendar"></i> Calendario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/courses/public">
                            <i class="fas fa-book-open"></i> Catálogo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/bookings">
                            <i class="fas fa-ticket-alt"></i> Mis Reservas
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <!-- Notificaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px;">
                        <div class="dropdown-header">
                            <strong>Notificaciones</strong>
                            <button class="btn btn-sm btn-link float-end" id="markAllRead">Marcar todas como leídas</button>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div id="notificationList" class="notification-list" style="max-height: 400px; overflow-y: auto;">
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                                <p class="mt-2 text-muted small">Cargando...</p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-footer text-center">
                            <a href="<?php echo BASE_URL; ?>/notifications" class="text-decoration-none small">Ver todas</a>
                        </div>
                    </div>
                </li>
                
                <!-- Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <span class="ms-1 d-none d-lg-inline"><?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? $_SESSION['user_nombre'] ?? ''); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/auth/profile">
                            <i class="fas fa-id-card"></i> Mi Perfil
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/auth/change-password">
                            <i class="fas fa-key"></i> Cambiar Contraseña
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/auth/logout">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="container-fluid mt-3">
    <!-- Alertas flash -->
    <?php if (Session::hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo Session::getFlash('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (Session::hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo Session::getFlash('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (Session::hasFlash('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo Session::getFlash('warning'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (Session::hasFlash('info')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i> <?php echo Session::getFlash('info'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- Contenido principal -->
    <div class="fade-in">
        <?php echo $content ?? ''; ?>
    </div>
</main>

<!-- Scripts base -->
<script>
// Configuración global
const BASE_URL = '<?php echo BASE_URL; ?>';
const USER_ROLE = '<?php echo Session::user()['rol'] ?? ''; ?>';
const USER_ID = <?php echo Session::user()['id'] ?? 'null'; ?>;

// Función para mostrar/ocultar spinner
function showSpinner() {
    document.getElementById('spinnerOverlay').style.display = 'flex';
}

function hideSpinner() {
    document.getElementById('spinnerOverlay').style.display = 'none';
}

// Función para mostrar toast
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 fade show`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.marginBottom = '10px';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// Cargar notificaciones
async function loadNotifications() {
    try {
        const response = await fetch(BASE_URL + '/notifications/unread');
        const data = await response.json();
        
        const badge = document.getElementById('notificationBadge');
        const list = document.getElementById('notificationList');
        
        if (data.count > 0) {
            badge.style.display = 'inline-block';
            badge.textContent = data.count > 99 ? '99+' : data.count;
        } else {
            badge.style.display = 'none';
        }
        
        if (list && data.notifications) {
            if (data.notifications.length === 0) {
                list.innerHTML = '<div class="text-center py-3"><p class="text-muted small">No hay notificaciones</p></div>';
            } else {
                list.innerHTML = data.notifications.map(notif => `
                    <a href="${BASE_URL}${notif.link || '/notifications'}" class="dropdown-item notification-item ${notif.leido ? '' : 'bg-light'}">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-${notif.tipo === 'success' ? 'check-circle text-success' : notif.tipo === 'warning' ? 'exclamation-triangle text-warning' : notif.tipo === 'danger' ? 'exclamation-circle text-danger' : 'info-circle text-info'}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-bold">${escapeHtml(notif.titulo)}</div>
                                <div class="small text-muted">${escapeHtml(notif.mensaje.substring(0, 80))}${notif.mensaje.length > 80 ? '...' : ''}</div>
                                <div class="small text-muted mt-1">${new Date(notif.created_at).toLocaleString()}</div>
                            </div>
                        </div>
                    </a>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Función helper para escapar HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Marcar todas como leídas
document.getElementById('markAllRead')?.addEventListener('click', async function(e) {
    e.preventDefault();
    try {
        await fetch(`${BASE_URL}/notifications/markAllRead`, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        loadNotifications();
    } catch (error) {
        console.error('Error:', error);
    }
});

// Auto-cerrar alertas después de 5 segundos
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        setTimeout(() => bsAlert.close(), 3000);
    });
}, 3000);

// Cargar notificaciones al inicio
if (document.getElementById('notificationList')) {
    loadNotifications();
    // Recargar cada 30 segundos
    setInterval(loadNotifications, 30000);
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/es.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/js/calendar.js"></script>
</body>
</html>