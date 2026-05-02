// assets/js/calendar.js
// Módulo principal del calendario interactivo

class AgendaCalendar {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Dia'
            },
            editable: false,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true,
            weekends: true,
            ...options
        };
        
        this.calendar = null;
        this.init();
    }
    
    init() {
        // Esperar a que FullCalendar esté disponible
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar no está cargado');
            return;
        }
        
        this.calendar = new FullCalendar.Calendar(this.element, {
            ...this.options,
            events: this.loadEvents.bind(this),
            eventClick: this.onEventClick.bind(this),
            select: this.onDateSelect.bind(this),
            eventDrop: this.onEventDrop.bind(this),
            eventResize: this.onEventResize.bind(this),
            datesSet: this.onDatesSet.bind(this),
            loading: this.onLoading.bind(this)
        });
        
        this.calendar.render();
    }
    
    async loadEvents(info, successCallback, failureCallback) {
        try {
            const start = info.startStr;
            const end = info.endStr;
            const url = `${BASE_URL}/calendar/getEvents?start=${start}&end=${end}`;
            
            const response = await fetch(url);
            const events = await response.json();
            
            successCallback(events);
        } catch (error) {
            console.error('Error loading events:', error);
            failureCallback(error);
        }
    }
    
    onEventClick(info) {
        const event = info.event;
        const extendedProps = event.extendedProps;
        
        // Mostrar modal con detalles
        const modal = document.getElementById('eventModal');
        if (modal) {
            document.getElementById('eventTitle').innerText = event.title;
            document.getElementById('eventStatus').innerText = extendedProps.status || 'N/A';
            document.getElementById('eventOtec').innerText = extendedProps.otec || 'N/A';
            document.getElementById('eventStart').innerText = this.formatDateTime(event.start);
            document.getElementById('eventEnd').innerText = event.end ? this.formatDateTime(event.end) : 'N/A';
            
            // Configurar botones según tipo y rol
            const approveBtn = document.getElementById('approveBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            
            if (approveBtn && rejectBtn) {
                const isPending = extendedProps.type === 'booking' && extendedProps.status === 'pendiente';
                const isFacilitador = window.userRole === 'facilitador';
                
                approveBtn.style.display = isPending && isFacilitador ? 'inline-block' : 'none';
                rejectBtn.style.display = isPending && isFacilitador ? 'inline-block' : 'none';
                
                if (approveBtn.style.display === 'inline-block') {
                    approveBtn.onclick = () => this.updateBookingStatus(event.id, 'aprobada');
                    rejectBtn.onclick = () => this.updateBookingStatus(event.id, 'rechazada');
                }
            }
            
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }
    
    onDateSelect(info) {
        // Solo permitir selección a ejecutivos para crear reservas
        if (window.userRole === 'ejecutivo') {
            const startDate = info.startStr;
            const endDate = info.endStr;
            window.location.href = `${BASE_URL}/bookings/create?start=${startDate}&end=${endDate}`;
        } else if (window.userRole === 'facilitador') {
            // Facilitador puede bloquear horarios
            if (confirm('¿Desea bloquear este horario?')) {
                this.createAvailabilityBlock(info.startStr, info.endStr);
            }
        }
    }
    
    onEventDrop(info) {
        if (window.userRole !== 'facilitador') return;
        
        const event = info.event;
        const start = event.start.toISOString();
        const end = event.end ? event.end.toISOString() : null;
        
        this.updateEventDateTime(event.id, start, end);
    }
    
    onEventResize(info) {
        if (window.userRole !== 'facilitador') return;
        
        const event = info.event;
        const start = event.start.toISOString();
        const end = event.end.toISOString();
        
        this.updateEventDateTime(event.id, start, end);
    }
    
    onDatesSet(info) {
        // Actualizar título del mes actual
        const titleEl = document.querySelector('.fc-toolbar-title');
        if (titleEl) {
            const date = info.view.currentStart;
            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const title = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
            titleEl.innerText = title;
        }
    }
    
    onLoading(isLoading) {
        const loader = document.getElementById('calendarLoader');
        if (loader) {
            loader.style.display = isLoading ? 'block' : 'none';
        }
    }
    
    async updateBookingStatus(eventId, newStatus) {
        const bookingId = eventId.replace('booking_', '');
        
        try {
            const response = await fetch(`${BASE_URL}/bookings/updateStatus`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: bookingId,
                    status: newStatus
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.calendar.refetchEvents();
                this.showNotification('Estado actualizado correctamente', 'success');
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                if (modal) modal.hide();
            } else {
                this.showNotification(data.error || 'Error al actualizar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión', 'error');
        }
    }
    
    async updateEventDateTime(eventId, start, end) {
        try {
            const response = await fetch(`${BASE_URL}/calendar/updateEvent`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: eventId,
                    start: start,
                    end: end
                })
            });
            
            const data = await response.json();
            
            if (!data.success) {
                this.calendar.refetchEvents();
                this.showNotification(data.error || 'Error al actualizar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.calendar.refetchEvents();
        }
    }
    
    async createAvailabilityBlock(start, end) {
        try {
            const response = await fetch(`${BASE_URL}/availability/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    fecha_inicio: start,
                    fecha_fin: end,
                    estado: 'bloqueado'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.calendar.refetchEvents();
                this.showNotification('Horario bloqueado correctamente', 'success');
            } else {
                this.showNotification(data.error || 'Error al bloquear horario', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión', 'error');
        }
    }
    
    formatDateTime(date) {
        if (!date) return 'N/A';
        const d = new Date(date);
        return d.toLocaleString('es-CL', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    showNotification(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    refresh() {
        this.calendar.refetchEvents();
    }
    
    gotoDate(date) {
        this.calendar.gotoDate(date);
    }
    
    changeView(viewName) {
        this.calendar.changeView(viewName);
    }
}



document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        window.agendaCalendar = new AgendaCalendar(calendarEl, {
            editable: window.userRole === 'facilitador',
            selectable: true
        });
    }
});
