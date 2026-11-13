-- TP10 - Módulo independiente "Novedades" para CodeIgniter 4
-- Base: instituto_db (misma base que usa el sitio principal)

USE instituto_db;

CREATE TABLE IF NOT EXISTS novedades (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(160) NOT NULL,
    resumen VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    fecha DATE NOT NULL,
    publicado TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_novedades_titulo (titulo),
    KEY idx_novedades_publicado_fecha (publicado, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO novedades (titulo, resumen, contenido, fecha, publicado) VALUES
(
    'Nueva edición de la Feria de Ciencias',
    'Estudiantes de todos los cursos presentarán sus proyectos en la muestra anual.',
    'La Feria de Ciencias del Instituto Cooperativo 14 de Abril N° 8001 se realizará el viernes 23 de octubre. Las y los estudiantes expondrán proyectos de investigación, robótica, cuidado del ambiente y tecnología aplicada. La comunidad educativa está invitada a recorrer los stands y participar de las demostraciones.',
    '2026-10-15',
    1
),
(
    'Inscripciones abiertas para los talleres 2027',
    'Ya se encuentra disponible el formulario de inscripción a los talleres extracurriculares.',
    'Los talleres de programación, robótica, comunicación y deportes abren su inscripción para el ciclo lectivo 2027. Las familias interesadas pueden acercarse a la secretaría del instituto o completar el formulario de inscripción publicado en el sitio. Los cupos son limitados y se asignan por orden de inscripción.',
    '2026-11-02',
    1
),
(
    'Taller de programación web para estudiantes',
    'Un espacio para crear páginas y aplicaciones con HTML, CSS, PHP y bases de datos.',
    'El taller de programación web propone un recorrido práctico por el desarrollo de aplicaciones: estructura HTML, estilos CSS, lógica con JavaScript, programación del lado del servidor con PHP y almacenamiento en MySQL. Los encuentros se realizan en el laboratorio de informática y culminan con un proyecto integrador.',
    '2026-11-05',
    1
),
(
    'Muestra anual de Educación Física',
    'Actividades recreativas y deportivas para compartir una jornada en comunidad.',
    'La muestra anual de Educación Física se llevará a cabo en el predio deportivo del instituto. Habrá demostraciones de básquet, vóleibol, atletismo y juegos cooperativos. Se recomienda a las familias asistir con ropa cómoda y respetar los horarios informados por los profesores.',
    '2026-11-20',
    1
);
