-- TP9: esquema y datos de ejemplo de la tabla usada por el buscador asincrónico.
-- La tabla ya existe en la base local; este archivo permite recrearla en una instalación limpia.

USE instituto_db;

CREATE TABLE IF NOT EXISTS cursos (
  id INT NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT NOT NULL,
  cupos INT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO cursos (titulo, descripcion, cupos)
SELECT 'Desarrollo Web Frontend', 'Curso enfocado en HTML5, CSS3, JavaScript moderno y frameworks orientados a componentes.', 30
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM cursos WHERE titulo = 'Desarrollo Web Frontend'
);

INSERT INTO cursos (titulo, descripcion, cupos)
SELECT 'Programación en PHP y Bases de Datos', 'Aprende a construir aplicaciones dinámicas del lado del servidor utilizando PHP 8, PDO y MySQL.', 25
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM cursos WHERE titulo = 'Programación en PHP y Bases de Datos'
);

INSERT INTO cursos (titulo, descripcion, cupos)
SELECT 'Introducción a la Lógica y Bases de Sistemas', 'Conceptos fundamentales de arquitectura de computadoras, algoritmos y resolución de problemas.', 40
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM cursos WHERE titulo = 'Introducción a la Lógica y Bases de Sistemas'
);
