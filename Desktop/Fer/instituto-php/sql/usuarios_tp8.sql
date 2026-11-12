-- ============================================================
-- TP8: Manejo de estado, sesiones y autenticación
-- Base de datos: instituto_db
-- Tabla: usuarios
-- ============================================================

CREATE DATABASE IF NOT EXISTS instituto_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE instituto_db;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol VARCHAR(20) NOT NULL DEFAULT 'usuario',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_email (email),
  KEY idx_usuarios_rol (rol),
  CONSTRAINT chk_usuarios_rol CHECK (rol IN ('admin', 'usuario'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cuentas de prueba para la corrección del TP.
-- Las contraseñas están guardadas con password_hash(..., PASSWORD_BCRYPT).
-- Admin:    admin@instituto.edu.ar    / Admin123!
-- Usuario:  usuario@instituto.edu.ar  / Usuario123!
INSERT INTO usuarios (nombre, email, password, rol)
VALUES
  (
    'Administrador del Instituto',
    'admin@instituto.edu.ar',
    '$2y$10$0vpHNT1MQiwWM0LLodvCy.hzzoLzvpueTr2d64LsfEgtGJChSkYEG',
    'admin'
  ),
  (
    'Usuario de Prueba',
    'usuario@instituto.edu.ar',
    '$2y$10$uXL3RsVodPSQ0EeYYZsiBOSVt45BloIUFSKWHo7qLP/tvma8r0Ahe',
    'usuario'
  )
ON DUPLICATE KEY UPDATE
  nombre = VALUES(nombre),
  password = VALUES(password),
  rol = VALUES(rol);

SELECT id, nombre, email, rol, created_at
FROM usuarios
ORDER BY id;
