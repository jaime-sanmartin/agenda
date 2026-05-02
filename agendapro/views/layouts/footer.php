    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <span class="text-muted small">
                        &copy; <?php echo date('Y'); ?> AgendaPro Facilitador. Todos los derechos reservados.
                    </span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="text-muted small">
                        Versión 1.0 | 
                        <a href="#" class="text-muted text-decoration-none" data-bs-toggle="modal" data-bs-target="#aboutModal">
                            Acerca de
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Modal Acerca de -->
    <div class="modal fade" id="aboutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Acerca de AgendaPro Facilitador
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                        <h4 class="mt-2">AgendaPro Facilitador</h4>
                        <p class="text-muted">Sistema de Gestión de Capacitaciones</p>
                    </div>
                    <hr>
                    <dl class="row">
                        <dt class="col-sm-4">Versión</dt>
                        <dd class="col-sm-8">1.0.0</dd>
                        
                        <dt class="col-sm-4">Desarrollado por</dt>
                        <dd class="col-sm-8">AgendaPro Team</dd>
                        
                        <dt class="col-sm-4">Tecnologías</dt>
                        <dd class="col-sm-8">
                            PHP 7.4+, MySQL, Bootstrap 5, FullCalendar
                        </dd>
                        
                        <dt class="col-sm-4">Soporte</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:soporte@agendapro.com">soporte@agendapro.com</a>
                        </dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para eventos del calendario -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-day"></i> Detalles de la Capacitación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Curso:</label>
                        <p id="eventTitle" class="mb-0">-</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Estado:</label>
                        <p id="eventStatus" class="mb-0">-</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">OTEC:</label>
                        <p id="eventOtec" class="mb-0">-</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Inicio:</label>
                        <p id="eventStart" class="mb-0">-</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Fin:</label>
                        <p id="eventEnd" class="mb-0">-</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="approveBtn" style="display: none;">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                    <button type="button" class="btn btn-danger" id="rejectBtn" style="display: none;">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Función para actualizar estado de reserva desde el modal
    window.updateBookingStatus = function(eventId, newStatus) {
        const bookingId = eventId.replace('booking_', '');
        
        fetch(`${BASE_URL}/bookings/updateStatus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: bookingId, status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Estado actualizado correctamente', 'success');
                location.reload();
            } else {
                showToast(data.error || 'Error al actualizar', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error de conexión', 'danger');
        });
    };
    
    // Auto-cerrar toasts después de 5 segundos
    document.querySelectorAll('.toast').forEach(toast => {
        setTimeout(() => {
            const bsToast = bootstrap.Toast.getInstance(toast);
            if (bsToast) bsToast.hide();
        }, 5000);
    });
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    </script>
</body>
</html>