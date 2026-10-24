-- =====================================================================
-- TP6: Esquema relacional MySQL / MariaDB
-- Proyecto: Sitio web del Instituto Cooperativo 14 de Abril N.º 8001
-- Motor: InnoDB | Codificación: utf8mb4
-- Ejecutar en phpMyAdmin (pestaña SQL o Importar) o con el cliente mysql.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS instituto_14_abril
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE instituto_14_abril;

-- Reinicio del esquema: permite ejecutar este script cuantas veces haga falta.
-- ATENCIÓN: borra las tablas y sus datos.
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS evento_anio, consultas, eventos, usuarios,
  estados_consulta, asuntos_consulta, anios, tipos_evento, roles;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- TABLAS MAESTRAS (3FN: cada dato descriptivo se guarda una sola vez)
-- ---------------------------------------------------------------------

-- Roles de usuario del sistema
CREATE TABLE IF NOT EXISTS roles (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(30)  NOT NULL UNIQUE,
  descripcion VARCHAR(150) NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tipos de evento de la agenda (feriado, acto, jornada, receso...)
CREATE TABLE IF NOT EXISTS tipos_evento (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  clave      VARCHAR(20) NOT NULL UNIQUE,
  nombre     VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Años de cursada (1.º a 5.º)
CREATE TABLE IF NOT EXISTS anios (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  numero TINYINT NOT NULL UNIQUE,
  nombre VARCHAR(20) NOT NULL,
  ciclo  ENUM('Básico','Orientado') NOT NULL,
  CONSTRAINT chk_anios_numero CHECK (numero BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- Asuntos posibles del formulario de contacto
CREATE TABLE IF NOT EXISTS asuntos_consulta (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  clave  VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

-- Estados de una consulta (pendiente, respondida...)
CREATE TABLE IF NOT EXISTS estados_consulta (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- USUARIOS (autenticación, rol y datos personales)
-- password_hash guarda el resultado de password_hash() de PHP, nunca la clave.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  rol_id        INT NOT NULL,
  nombre        VARCHAR(60)  NOT NULL,
  apellido      VARCHAR(60)  NOT NULL,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  telefono      VARCHAR(20)  NULL,
  activo        TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_rol FOREIGN KEY (rol_id) REFERENCES roles(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TABLA PRINCIPAL 1: EVENTOS DE LA AGENDA ESCOLAR
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS eventos (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  tipo_id      INT NOT NULL,
  creado_por   INT NULL,
  titulo       VARCHAR(200) NOT NULL,
  descripcion  TEXT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin    DATE NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_eventos_tipo FOREIGN KEY (tipo_id) REFERENCES tipos_evento(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_eventos_usuario FOREIGN KEY (creado_por) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT chk_eventos_fechas CHECK (fecha_fin IS NULL OR fecha_fin >= fecha_inicio)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TABLA DE RELACIÓN N:M: qué años alcanza cada evento
-- (un evento puede afectar a varios años y un año tiene muchos eventos)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS evento_anio (
  evento_id INT NOT NULL,
  anio_id   INT NOT NULL,
  PRIMARY KEY (evento_id, anio_id),
  CONSTRAINT fk_ea_evento FOREIGN KEY (evento_id) REFERENCES eventos(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_ea_anio FOREIGN KEY (anio_id) REFERENCES anios(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TABLA PRINCIPAL 2: CONSULTAS (formulario de contacto del TP5)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS consultas (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  asunto_id      INT NOT NULL,
  estado_id      INT NOT NULL,
  atendida_por   INT NULL,
  nombre         VARCHAR(80)  NOT NULL,
  email          VARCHAR(120) NOT NULL,
  telefono       VARCHAR(20)  NULL,
  mensaje        TEXT NOT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_consultas_asunto FOREIGN KEY (asunto_id) REFERENCES asuntos_consulta(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_consultas_estado FOREIGN KEY (estado_id) REFERENCES estados_consulta(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_consultas_usuario FOREIGN KEY (atendida_por) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ÍNDICES DE RENDIMIENTO
-- ---------------------------------------------------------------------
-- La agenda se consulta siempre por fecha (mes, trimestre, próximos eventos).
CREATE INDEX idx_eventos_fecha ON eventos (fecha_inicio);
-- El panel de consultas filtra por estado y ordena por antigüedad.
CREATE INDEX idx_consultas_estado_fecha ON consultas (estado_id, created_at);
-- Búsqueda de consultas previas de una misma persona.
CREATE INDEX idx_consultas_email ON consultas (email);
