// assets/js/main.js

// Inicializar tooltips de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Función para confirmar eliminación
function confirmDelete(url, message) {
    if (confirm(message || '¿Está seguro de eliminar este registro? Esta acción no se puede deshacer.')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        
        var tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'csrf_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').content;
        
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Función para mostrar notificaciones
function showNotification(message, type = 'success') {
    var notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(function() {
        notification.remove();
    }, 5000);
}

// Función para cargar selectores dependientes
function loadDependentSelect(parentSelect, childSelect, url, params = {}) {
    var value = parentSelect.value;
    if (!value) {
        childSelect.innerHTML = '<option value="">Seleccione una opción</option>';
        return;
    }
    
    params[parentSelect.name] = value;
    var queryString = new URLSearchParams(params).toString();
    
    fetch(`${url}?${queryString}`)
        .then(response => response.json())
        .then(data => {
            childSelect.innerHTML = '<option value="">Seleccione una opción</option>';
            data.forEach(item => {
                var option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nombre;
                childSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Formatear fecha local
function formatDate(dateString, format = 'dd/mm/yyyy') {
    var date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    
    if (format === 'dd/mm/yyyy') {
        return `${day}/${month}/${year}`;
    } else if (format === 'dd/mm/yyyy hh:ii') {
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }
    return dateString;
}

// Validar RUT chileno
function validateRut(rut) {
    if (!rut) return false;
    
    var cleanRut = rut.replace(/\./g, '').replace(/-/g, '');
    if (cleanRut.length < 8 || cleanRut.length > 9) return false;
    
    var body = cleanRut.slice(0, -1);
    var dv = cleanRut.slice(-1).toUpperCase();
    
    var sum = 0;
    var mul = 2;
    
    for (var i = body.length - 1; i >= 0; i--) {
        sum += parseInt(body.charAt(i)) * mul;
        mul = mul === 7 ? 2 : mul + 1;
    }
    
    var result = 11 - (sum % 11);
    var expectedDv = result === 11 ? '0' : result === 10 ? 'K' : result.toString();
    
    return dv === expectedDv;
}

// Prevenir envío duplicado de formularios
document.addEventListener('submit', function(e) {
    var form = e.target;
    if (form.submitted) {
        e.preventDefault();
        return;
    }
    form.submitted = true;
    setTimeout(function() {
        form.submitted = false;
    }, 3000);
});

