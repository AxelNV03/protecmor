-- SCRIPT SQL FINAL PARA TESIS: PROTECMOR - REPRESENTACIÓN VISUAL COMPLETA

DROP DATABASE IF EXISTS protecmor;
CREATE DATABASE protecmor;
USE protecmor;

CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL, -- puede estar vacío si no verifica
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL, -- solo se usa si elige "recordarme"
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de roles (Laravel / Spatie)
CREATE TABLE roles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    guard_name VARCHAR(191) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Tabla de permisos (Laravel / Spatie)
CREATE TABLE permissions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    guard_name VARCHAR(191) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Pivot: modelo tiene roles
CREATE TABLE model_has_roles (
  role_id BIGINT NOT NULL,
  model_type VARCHAR(191) NOT NULL,
  model_id BIGINT NOT NULL,
  PRIMARY KEY (role_id, model_id, model_type),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);
-- Pivot: modelo tiene permisos
CREATE TABLE model_has_permissions (
  permission_id BIGINT NOT NULL,
  model_type VARCHAR(191) NOT NULL,
  model_id BIGINT NOT NULL,
  PRIMARY KEY (permission_id, model_id, model_type),
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
-- Pivot: permisos por rol
CREATE TABLE role_has_permissions (
  permission_id BIGINT NOT NULL,
  role_id BIGINT NOT NULL,
  PRIMARY KEY (permission_id, role_id),
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Tabla de grupos
CREATE TABLE grupos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    generacion VARCHAR(50) NULL,
    observaciones TEXT NULL
);


-- Tabla de alumnos
CREATE TABLE alumnos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    matricula VARCHAR(20) UNIQUE NULL,
    grupo_id BIGINT NULL,
    fecha_nacimiento DATE NOT NULL,
    telefono VARCHAR(20) NULL,
    sexo ENUM('Masculino', 'Femenino', 'Otro') NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);


-- Tabla de profesores
CREATE TABLE profesores (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    especialidad VARCHAR(100) NOT NULL,
    fecha_ingreso DATE NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabla de campos formativos
CREATE TABLE campos_formativos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('materia', 'taller') NOT NULL
);

-- Tabla de clases
CREATE TABLE clases (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    grupo_id BIGINT NOT NULL,
    profesor_id BIGINT NOT NULL,
    campo_formativo_id BIGINT NOT NULL,
    fecha_inicio DATE NULL,
    fecha_fin DATE NULL,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (profesor_id) REFERENCES profesores(id),
    FOREIGN KEY (campo_formativo_id) REFERENCES campos_formativos(id)
);

-- Tabla de calificaciones
CREATE TABLE calificaciones (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    alumno_id BIGINT NOT NULL,
    campo_id BIGINT NOT NULL,
    calificacion DECIMAL(5,2) NULL,
    nivel_desempeno ENUM('Bajo','Regular','Bueno','Excelente') NULL,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id),
    FOREIGN KEY (campo_id) REFERENCES campos_formativos(id)
);

-- Tabla de materiales y pase de lista
CREATE TABLE materiales (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    clase_id BIGINT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    archivo_url VARCHAR(255) NULL,
    tipo ENUM('material', 'pase_lista') NOT NULL DEFAULT 'material',
    fecha_subida TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clase_id) REFERENCES clases(id)
);

-- Tabla de mensajes por clase
CREATE TABLE mensajes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    clase_id BIGINT NOT NULL,
    usuario_id BIGINT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (clase_id) REFERENCES clases(id),
    FOREIGN KEY (usuario_id) REFERENCES users(id)
);

-- Tabla de constancias generadas
CREATE TABLE constancias (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    alumno_id BIGINT NOT NULL,
    taller_id BIGINT NOT NULL,
    archivo_url VARCHAR(255) NOT NULL,
    fecha_emision DATE NOT NULL,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id),
    FOREIGN KEY (taller_id) REFERENCES campos_formativos(id)
);

-- Tabla de eventos
CREATE TABLE eventos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    fecha DATE NOT NULL, 
    hora TIME NULL,
    duracion VARCHAR(50) NULL,
    costo DECIMAL(10,2) NULL,
    lugar VARCHAR(100) NULL,
    descripcion TEXT NULL,
    publico ENUM('alumnos','general','ambos') NOT NULL,
    requiere_inscripcion BOOLEAN NOT NULL DEFAULT FALSE,
    visible BOOLEAN NOT NULL DEFAULT TRUE,
    incluido_mensualidad BOOLEAN NOT NULL DEFAULT FALSE
);

-- Tabla de productos
CREATE TABLE productos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    precio DECIMAL(10,2) NOT NULL,
    costo DECIMAL(10,2) NULL,
    proveedor VARCHAR(100) NULL,
    categoria VARCHAR(100) NOT NULL,
    disponible BOOLEAN NOT NULL DEFAULT TRUE,
    imagen VARCHAR(255) NULL,
    visible BOOLEAN NOT NULL DEFAULT TRUE
);

-- Tabla de pagos
CREATE TABLE pagos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    alumno_id BIGINT NOT NULL,
    tipo ENUM('inscripcion','mensualidad') NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha_pago DATE NOT NULL,
    estado ENUM('pendiente','pagado','atrasado') NOT NULL DEFAULT 'pendiente',
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id)
);

-- Tabla de respaldos
CREATE TABLE respaldos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre_archivo VARCHAR(100) NOT NULL,
    usuario_id BIGINT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    ruta TEXT NOT NULL,
    tamano_bytes BIGINT NULL,                   -- Campo adicional recomendado
    FOREIGN KEY (usuario_id) REFERENCES users(id)
);

-- Tabla de notificaciones
CREATE TABLE notificaciones (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT NOT NULL,
    tipo ENUM('informativa','recordatorio','advertencia','urgente') NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    leida BOOLEAN NOT NULL DEFAULT FALSE,       -- Campo añadido para tracking
    fecha_leida TIMESTAMP NULL,                 -- Campo adicional recomendado
    FOREIGN KEY (usuario_id) REFERENCES users(id)
);