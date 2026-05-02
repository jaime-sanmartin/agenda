-- --------------------------------------------------------
-- Host: localhost
-- Database: agendapro_db
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS agendapro_db;
USE agendapro_db;

-- --------------------------------------------------------
-- Table: otec
-- --------------------------------------------------------
CREATE TABLE otec (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    rut VARCHAR(12) NOT NULL UNIQUE,
    direccion TEXT,
    contacto VARCHAR(100),
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('facilitador', 'ejecutivo') NOT NULL DEFAULT 'ejecutivo',
    otec_id INT UNSIGNED NULL, -- Solo para ejecutivos
    reset_token VARCHAR(255) NULL,
    reset_expires DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (otec_id) REFERENCES otec(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Table: courses
-- --------------------------------------------------------
CREATE TABLE courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    duracion_horas INT UNSIGNED NOT NULL,
    modalidad ENUM('online', 'presencial') NOT NULL,
    publico TINYINT(1) DEFAULT 1, -- 1: visible en catálogo público, 0: oculto
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Table: bookings
-- --------------------------------------------------------
CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    otec_id INT UNSIGNED NOT NULL,
    curso_id INT UNSIGNED NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    valor_acordado DECIMAL(10,2) NULL,
    estado ENUM('propuesta', 'pendiente', 'aprobada', 'rechazada', 'confirmada', 'finalizada') NOT NULL DEFAULT 'pendiente',
    notas TEXT,
    created_by INT UNSIGNED NOT NULL, -- ID del usuario que creó la reserva
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (otec_id) REFERENCES otec(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Table: availability (Bloques de tiempo del facilitador)
-- --------------------------------------------------------
CREATE TABLE availability (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    estado ENUM('disponible', 'bloqueado') NOT NULL DEFAULT 'disponible',
    motivo TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Table: activity_logs (Opcional - para funcionalidad extra)
-- --------------------------------------------------------
CREATE TABLE activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    accion VARCHAR(100) NOT NULL,
    detalles TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Índices para optimización de búsquedas
-- --------------------------------------------------------
CREATE INDEX idx_bookings_fechas ON bookings(fecha_inicio, fecha_fin);
CREATE INDEX idx_availability_fechas ON availability(fecha_inicio, fecha_fin);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_bookings_otec ON bookings(otec_id);
CREATE INDEX idx_bookings_curso ON bookings(curso_id);
CREATE INDEX idx_bookings_estado ON bookings(estado);

-- --------------------------------------------------------
-- Datos de prueba (Opcional)
-- --------------------------------------------------------
INSERT INTO otec (nombre, rut, direccion, contacto, email) VALUES
('OTEC Capacita Chile', '76.123.456-7', 'Av. Providencia 1234, Santiago', 'Juan Pérez', 'contacto@capacitachile.cl');

INSERT INTO users (nombre, email, password, rol, otec_id) VALUES
('Facilitador Principal', 'facilitador@agendapro.com', '$2y$10$tu_hash_aqui', 'facilitador', NULL),
('Ejecutivo OTEC', 'ejecutivo@capacitachile.cl', '$2y$10$tu_hash_aqui', 'ejecutivo', 1);
-- Nota: Reemplazar '$2y$10$tu_hash_aqui' con el hash real de bcrypt para 'password123' u otra contraseña.

INSERT INTO courses (nombre, descripcion, duracion_horas, modalidad, publico) VALUES
('Liderazgo Efectivo', 'Curso para desarrollar habilidades de liderazgo', 16, 'online', 1),
('Comunicación Asertiva', 'Mejora tus habilidades comunicacionales', 8, 'presencial', 1);