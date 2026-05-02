<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell"></i> Mis Notificaciones
                    </h6>
                    <?php if ($unread_count > 0): ?>
                    <button class="btn btn-sm btn-primary" id="markAllRead">
                        <i class="fas fa-check-double"></i> Marcar todas como leídas
                    </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($notifications)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes notificaciones</p>
                    </div>
                    <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($notifications as $notif): ?>
                        <div class="list-group-item list-group-item-action <?php echo $notif['leido'] ? '' : 'bg-light'; ?>" 
                             data-id="<?php echo $notif['id']; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <?php if (!$notif['leido']): ?>
                                    <span class="badge bg-primary me-2">Nuevo</span>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($notif['titulo']); ?>
                                </h6>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y H:i', strtotime($notif['created_at'])); ?>
                                </small>
                            </div>
                            <p class="mb-1"><?php echo htmlspecialchars($notif['mensaje']); ?></p>
                            <?php if ($notif['link']): ?>
                            <a href="<?php echo BASE_URL . $notif['link']; ?>" class="btn btn-sm btn-link p-0 mt-1">
                                Ver detalles <i class="fas fa-arrow-right"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item {
    transition: all 0.2s;
}
.list-group-item:hover {
    background-color: #f8f9fc;
    cursor: pointer;
}
.bg-light {
    background-color: #f8f9fc !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marcar una notificación como leída al hacer clic
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // No marcar si se hizo clic en un enlace
            if (e.target.tagName === 'A') return;
            
            const id = this.dataset.id;
            if (id && !this.classList.contains('bg-white')) {
                fetch(BASE_URL + '/notifications/markRead/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.remove('bg-light');
                        this.classList.add('bg-white');
                        // Actualizar contador
                        updateUnreadCount();
                    }
                });
            }
            
            // Si hay enlace, redirigir
            const link = this.querySelector('a');
            if (link && link.href) {
                window.location.href = link.href;
            }
        });
    });
    
    // Marcar todas como leídas
    const markAllBtn = document.getElementById('markAllRead');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch(BASE_URL + '/notifications/markAllRead', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    }
});

function updateUnreadCount() {
    // Actualizar el contador en el header
    fetch(BASE_URL + '/notifications/unread')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.style.display = 'inline-block';
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                } else {
                    badge.style.display = 'none';
                }
            }
        });
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>