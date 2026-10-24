-- =====================================================================
-- TP6: Consultas de prueba CRUD
-- Ejecutar DESPUÉS de schema.sql (que deja las tablas vacías).
-- Para repetir las pruebas: volver a ejecutar schema.sql y luego este archivo.
-- Los datos de personas son ficticios. Contraseña de prueba: Demo1234!
-- =====================================================================
USE instituto_14_abril;

-- =====================================================================
-- 1) CREATE: datos iniciales
-- =====================================================================

-- Tablas maestras
INSERT INTO roles (nombre, descripcion) VALUES
  ('administrador', 'Gestiona usuarios, agenda y consultas'),
  ('secretaria',    'Carga eventos y responde consultas'),
  ('docente',       'Consulta la agenda institucional');

INSERT INTO tipos_evento (clave, nombre) VALUES
  ('feriado',  'Feriado'),
  ('acto',     'Acto escolar'),
  ('jornada',  'Jornada institucional'),
  ('receso',   'Receso de invierno'),
  ('comision', 'Comisión evaluadora'),
  ('puente',   'Puente o no laborable');

INSERT INTO anios (numero, nombre, ciclo) VALUES
  (1, '1.º año', 'Básico'),   (2, '2.º año', 'Básico'),  (3, '3.º año', 'Básico'),
  (4, '4.º año', 'Orientado'), (5, '5.º año', 'Orientado');

INSERT INTO asuntos_consulta (clave, nombre) VALUES
  ('inscripcion', 'Consulta de inscripción'),
  ('becas',       'Becas'),
  ('general',     'Consulta general'),
  ('otro',        'Otro');

INSERT INTO estados_consulta (nombre) VALUES
  ('Pendiente'), ('En proceso'), ('Respondida'), ('Archivada');

-- Usuarios (la clave se guarda como hash, nunca en texto plano)
INSERT INTO usuarios (rol_id, nombre, apellido, email, password_hash, telefono) VALUES
  ((SELECT id FROM roles WHERE nombre = 'administrador'), 'María Elena', 'Rojas',  'admin@example.com',     '$2y$10$Cq3Tp1r.XKZUGUqKJrkJzui3uOjskGKwkupQ2TC0K.7GDP.ODsR1u', '3874320675'),
  ((SELECT id FROM roles WHERE nombre = 'secretaria'),    'Andrea',      'Cruz',   'secretaria@example.com','$2y$10$Cq3Tp1r.XKZUGUqKJrkJzui3uOjskGKwkupQ2TC0K.7GDP.ODsR1u', NULL),
  ((SELECT id FROM roles WHERE nombre = 'docente'),       'Julián',      'Ortega', 'docente@example.com',   '$2y$10$Cq3Tp1r.XKZUGUqKJrkJzui3uOjskGKwkupQ2TC0K.7GDP.ODsR1u', NULL);

-- Tabla principal 1: eventos de la agenda (con claves foráneas)
INSERT INTO eventos (tipo_id, creado_por, titulo, descripcion, fecha_inicio, fecha_fin) VALUES
  ((SELECT id FROM tipos_evento WHERE clave = 'feriado'),
   (SELECT id FROM usuarios WHERE email = 'secretaria@example.com'),
   'Día Nacional de la Memoria', 'Feriado nacional.', '2026-03-24', NULL),
  ((SELECT id FROM tipos_evento WHERE clave = 'acto'),
   (SELECT id FROM usuarios WHERE email = 'secretaria@example.com'),
   'Promesa de lealtad a la Constitución Nacional', 'Acto de 3.er año y Día del Himno Nacional.', '2026-05-11', NULL),
  ((SELECT id FROM tipos_evento WHERE clave = 'jornada'),
   (SELECT id FROM usuarios WHERE email = 'admin@example.com'),
   'Jornada de acuerdos escolares de convivencia', 'Desarrollo de habilidades socioemocionales.', '2026-04-14', NULL),
  ((SELECT id FROM tipos_evento WHERE clave = 'receso'),
   (SELECT id FROM usuarios WHERE email = 'admin@example.com'),
   'Receso escolar de invierno', 'Dos semanas sin actividades.', '2026-07-13', '2026-07-24');

-- Relación N:M: años alcanzados por cada evento
INSERT INTO evento_anio (evento_id, anio_id)
  SELECT e.id, a.id FROM eventos e JOIN anios a
  WHERE e.titulo = 'Promesa de lealtad a la Constitución Nacional' AND a.numero = 3;
INSERT INTO evento_anio (evento_id, anio_id)
  SELECT e.id, a.id FROM eventos e JOIN anios a
  WHERE e.titulo = 'Jornada de acuerdos escolares de convivencia';   -- los 5 años

-- Tabla principal 2: consultas recibidas por el formulario
INSERT INTO consultas (asunto_id, estado_id, nombre, email, telefono, mensaje) VALUES
  ((SELECT id FROM asuntos_consulta WHERE clave = 'becas'),
   (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente'),
   'Ana María O''Neil', 'ana@example.com', '387 432-0675', 'Quisiera consultar por becas para 1.º año.'),
  ((SELECT id FROM asuntos_consulta WHERE clave = 'inscripcion'),
   (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente'),
   'Carlos Pérez', 'carlos@example.com', NULL, '¿Qué documentación piden para inscribir a mi hijo?'),
  ((SELECT id FROM asuntos_consulta WHERE clave = 'otro'),
   (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente'),
   'Prueba Borrar', 'prueba@example.com', NULL, 'Registro de prueba que se elimina en el paso DELETE.');

-- =====================================================================
-- 2) READ
-- =====================================================================

-- 2.a) WHERE + ORDER BY: eventos de 2026 posteriores al 1 de abril, por fecha
SELECT id, titulo, fecha_inicio, fecha_fin
FROM eventos
WHERE fecha_inicio >= '2026-04-01'
ORDER BY fecha_inicio ASC;

-- 2.b) INNER JOIN (3 tablas): evento + tipo + usuario que lo cargó
SELECT e.titulo,
       t.nombre AS tipo,
       e.fecha_inicio,
       CONCAT(u.nombre, ' ', u.apellido) AS cargado_por
FROM eventos e
INNER JOIN tipos_evento t ON t.id = e.tipo_id
INNER JOIN usuarios u     ON u.id = e.creado_por
ORDER BY e.fecha_inicio;

-- 2.c) INNER JOIN con la tabla N:M: qué años alcanza cada evento
SELECT e.titulo, GROUP_CONCAT(a.nombre ORDER BY a.numero SEPARATOR ', ') AS anios
FROM eventos e
INNER JOIN evento_anio ea ON ea.evento_id = e.id
INNER JOIN anios a        ON a.id = ea.anio_id
GROUP BY e.id, e.titulo;

-- 2.d) INNER JOIN: consultas pendientes con su asunto y estado
SELECT c.id, c.nombre, c.email, s.nombre AS asunto, es.nombre AS estado, c.created_at
FROM consultas c
INNER JOIN asuntos_consulta s  ON s.id  = c.asunto_id
INNER JOIN estados_consulta es ON es.id = c.estado_id
WHERE es.nombre = 'Pendiente'
ORDER BY c.created_at DESC;

-- =====================================================================
-- 3) UPDATE (siempre con WHERE)
-- =====================================================================
-- La secretaria responde la consulta de Ana: cambia el estado y queda registrada.
UPDATE consultas
SET estado_id    = (SELECT id FROM estados_consulta WHERE nombre = 'Respondida'),
    atendida_por = (SELECT id FROM usuarios WHERE email = 'secretaria@example.com')
WHERE email = 'ana@example.com';

-- Comprobación del cambio (updated_at se actualiza solo)
SELECT c.nombre, es.nombre AS estado, c.updated_at
FROM consultas c INNER JOIN estados_consulta es ON es.id = c.estado_id
WHERE c.email = 'ana@example.com';

-- =====================================================================
-- 4) DELETE (siempre con WHERE)
-- =====================================================================
DELETE FROM consultas
WHERE email = 'prueba@example.com';

-- Comprobación: quedan 2 consultas
SELECT COUNT(*) AS consultas_restantes FROM consultas;
