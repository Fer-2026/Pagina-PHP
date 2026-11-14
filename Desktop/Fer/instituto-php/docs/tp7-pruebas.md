# TP7: pruebas del CRUD de cursos

## Alcance

El TP7 agregó la gestión de cursos del sitio: listado, alta, edición y baja sobre la tabla `cursos` de la base `instituto_db`. En el TP10 ese módulo se refactorizó a MVC nativo, conservando las URL históricas `admin/crear.php`, `admin/editar.php` y `admin/eliminar.php`.

Esta es la verificación de regresión de la versión actual, integrada con la autenticación y el control de roles del TP8.

## Archivos que intervienen

| Archivo | Función |
|---|---|
| `admin/index.php` | Front controller del CRUD; exige rol `admin`. |
| `admin/crear.php`, `admin/editar.php`, `admin/eliminar.php` | Capas de compatibilidad con las URL originales del TP7. |
| `app/Models/Curso.php` | Consultas PDO con sentencias preparadas. |
| `app/Controllers/CursoController.php` | Validación, CSRF, altas, ediciones y bajas. |
| `app/Views/cursos/` | Listado, formulario y confirmación de eliminación. |
| `sql/cursos_tp9.sql` | Esquema y datos de ejemplo de `cursos`. |

## Entorno de prueba

- Sitio: <http://localhost/instituto-php/>
- PHP 8.2.12, Apache 2.4.58 y MariaDB 10.4.32 sobre XAMPP.
- Base de datos: `instituto_db`.
- Tabla: `cursos` (3 registros originales).
- Cuenta administradora: `admin@instituto.edu.ar` / `Admin123!`.
- Fecha de la verificación: 13/11/2026.

## Casos verificados

| # | Caso | Resultado |
|---|---|---|
| 1 | Abrir el listado con sesión de administrador | `200`; se muestran los tres cursos y las acciones de edición y baja. |
| 2 | Abrir el formulario de alta | `200`; campos vacíos y token CSRF. |
| 3 | Crear un curso válido | `302` a `admin/index.php?status=created`; el registro queda en la base. |
| 4 | Enviar el alta sin título | `200` con el error de validación; no se inserta ningún registro. |
| 5 | Abrir la edición de un curso existente | `200`; los datos aparecen precargados. |
| 6 | Actualizar un curso | `302` a `admin/index.php?status=updated`; los cambios se ven en la base. |
| 7 | Abrir la confirmación de baja | `200`; se muestra el curso y un formulario `POST` con CSRF. |
| 8 | Confirmar la baja con CSRF válido | `302` a `admin/index.php?status=deleted`; el registro desaparece. |
| 9 | Intentar editar un ID inexistente | `302` a `admin/index.php?status=notfound`. |
| 10 | Intentar eliminar un ID inexistente | `302` a `admin/index.php?status=notfound`; no se informa una baja falsa. |
| 11 | Enviar una baja con token CSRF inválido | `302` a `admin/index.php?status=csrf`; el registro permanece. |
| 12 | Abrir el CRUD sin sesión | `302` a `login.php?error=acceso_denegado`. |
| 13 | Abrir el CRUD con rol `usuario` | `302` a `dashboard.php?error=sin_permiso`; no se ejecuta la operación. |
| 14 | Abrir las URL antiguas `crear.php`, `editar.php` y `eliminar.php` | `200`; delegan en el controlador actual. |

## Evidencia

- Listado y operaciones del CRUD: [`capturas/tp10/01-nativo-listado.png`](capturas/tp10/01-nativo-listado.png), [`02-nativo-crear.png`](capturas/tp10/02-nativo-crear.png), [`03-nativo-creado.png`](capturas/tp10/03-nativo-creado.png), [`04-nativo-editar.png`](capturas/tp10/04-nativo-editar.png), [`05-nativo-actualizado.png`](capturas/tp10/05-nativo-actualizado.png), [`06-nativo-confirmar-eliminar.png`](capturas/tp10/06-nativo-confirmar-eliminar.png) y [`07-nativo-eliminado.png`](capturas/tp10/07-nativo-eliminado.png).
- Validación del formulario: [`12-nativo-validacion.png`](capturas/tp10/12-nativo-validacion.png).
- Acceso desde el panel de administración: [`tp8/12-cursos-admin.png`](capturas/tp8/12-cursos-admin.png).
- Detalle adicional de las pruebas del CRUD actual: [`tp10-pruebas.md`](tp10-pruebas.md).

## Observación

El TP7 se entregó antes de los módulos de autenticación y MVC. La versión que se conserva hoy mantiene la funcionalidad original y agrega las mejoras de seguridad de TP8 (sesión, rol y CSRF) y la separación de responsabilidades de TP10. Por eso las capturas de esta verificación son las del CRUD actual.
