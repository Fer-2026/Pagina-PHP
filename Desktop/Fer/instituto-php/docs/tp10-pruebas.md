# TP10 - Pruebas de MVC nativo y módulo con framework

Fecha de ejecución: 13/11/2026

## Entorno

| Elemento | Versión / detalle |
|---|---|
| Sistema | Windows, XAMPP |
| Apache | 2.4.58 |
| PHP | 8.2.12 |
| MariaDB | 10.4.32 |
| Base de datos | `instituto_db` |
| Aplicación principal | `http://localhost/instituto-php/` |
| Framework | CodeIgniter 4.7.6 |
| Composer | 2.11.2 |
| Extensiones PHP | `intl`, `mbstring`, `mysqli`, `pdo_mysql`, `zip`, `curl`, `openssl` |
| Cuenta admin | `admin@instituto.edu.ar` / `Admin123!` |

Para ejecutar CodeIgniter se habilitaron las extensiones `intl` y `zip` en `php.ini`. La primera la requiere el framework y la segunda la utiliza Composer para instalar dependencias.

## Parte A - Refactorización del CRUD de cursos a MVC nativo

### Estructura creada

```text
app/
  autoload.php
  Models/
    Curso.php
  Controllers/
    CursoController.php
  Views/
    cursos/
      index.php
      form.php
      eliminar.php
admin/
  index.php          -> front controller
  crear.php          -> compatibilidad con la URL anterior
  editar.php         -> compatibilidad con la URL anterior
  eliminar.php       -> compatibilidad con la URL anterior
```

### Responsabilidades

- **Modelo:** `App\Models\Curso` centraliza todas las consultas sobre `cursos` con PDO y sentencias preparadas.
- **Controlador:** `App\Controllers\CursoController` recibe la acción, valida los datos, aplica CSRF, llama al modelo y decide el redirect o la vista.
- **Vistas:** `app/Views/cursos/*.php` contienen solamente HTML y datos escapados; no ejecutan SQL.
- **Front controller:** `admin/index.php` conserva la URL histórica, exige rol `admin` y delega en el controlador.

### Acciones disponibles

| Acción | Método | Ruta de ejemplo | Función |
|---|---|---|---|
| Listar | GET | `admin/index.php` | Muestra los cursos |
| Crear formulario | GET | `admin/index.php?accion=crear` | Formulario de alta |
| Guardar | POST | `admin/index.php?accion=guardar` | Valida y crea |
| Editar formulario | GET | `admin/index.php?accion=editar&id=1` | Carga el curso |
| Actualizar | POST | `admin/index.php?accion=actualizar&id=1` | Valida y actualiza |
| Confirmar eliminación | GET | `admin/index.php?accion=confirmar_eliminar&id=1` | Pide confirmación |
| Eliminar | POST | `admin/index.php?accion=eliminar&id=1` | Elimina con CSRF |

Las URL anteriores `admin/crear.php`, `admin/editar.php` y `admin/eliminar.php` siguen funcionando como capas delgadas de compatibilidad.

### Casos probados

| N.º | Caso | Resultado |
|---|---|---|
| 1 | Listado autenticado con los 3 cursos originales | `200`, tabla completa. Captura `01-nativo-listado.png`. |
| 2 | Abrir formulario de alta | `200`, campos vacíos. Captura `02-nativo-crear.png`. |
| 3 | Crear curso válido | `302` a `admin/index.php?status=created`; alta confirmada. Captura `03-nativo-creado.png`. |
| 4 | Abrir edición del curso creado | `200`, datos precargados. Captura `04-nativo-editar.png`. |
| 5 | Actualizar curso | `302` a `admin/index.php?status=updated`; cambios visibles. Captura `05-nativo-actualizado.png`. |
| 6 | Abrir confirmación de baja | `200`, datos del curso y formulario POST. Captura `06-nativo-confirmar-eliminar.png`. |
| 7 | Confirmar eliminación con CSRF válido | `302` a `admin/index.php?status=deleted`; registro eliminado. Captura `07-nativo-eliminado.png`. |
| 8 | Enviar alta sin título | `200` con error de validación; no se inserta nada. Captura `12-nativo-validacion.png`. |
| 9 | Enviar baja con token CSRF inválido | `302` a `status=csrf`; el registro permanece. |
| 10 | Editar un ID inexistente | `302` a `status=notfound`. |
| 11 | Abrir `admin/index.php` sin sesión | `302` a `login.php?error=acceso_denegado`. |
| 12 | Abrir las URL antiguas `crear.php`, `editar.php` y `eliminar.php` | `200`; delegan en el controlador. |
| 13 | Verificación de sintaxis PHP de los 17 archivos del TP10 | 0 errores. |

Después de las pruebas se eliminó el curso temporal y la tabla quedó con los 3 registros originales:

```text
1  Desarrollo Web Frontend
2  Programación en PHP y Bases de Datos
3  Introducción a la Lógica y Bases de Sistemas
```

## Parte B - Módulo independiente con CodeIgniter 4

### Módulo elegido

**Novedades institucionales**: un módulo público e independiente, con listado, búsqueda, detalle y endpoint JSON. Usa una tabla propia (`novedades`), por lo que no depende del CRUD nativo de cursos.

### Estructura del proyecto

```text
tp10/framework/
  app/
    Config/
      App.php          -> baseURL y indexPage
      Database.php     -> conexión a instituto_db
      Routes.php       -> rutas del módulo
    Controllers/
      Novedades.php
    Models/
      NovedadModel.php
    Views/
      layouts/main.php
      novedades/index.php
      novedades/show.php
  public/
    index.php
    assets/tp10.css
  composer.json
  composer.lock
  spark
```

### Rutas

| Método | Ruta | Controlador | Función |
|---|---|---|---|
| GET | `/` | `Novedades::index` | Inicio del proyecto framework |
| GET | `/novedades` | `Novedades::index` | Listado y búsqueda |
| GET | `/novedades/{id}` | `Novedades::show` | Detalle de una novedad |
| GET | `/api/novedades` | `Novedades::api` | Respuesta JSON |

La ruta raíz también muestra el módulo para facilitar la corrección:

```text
http://localhost/instituto-php/tp10/framework/public/
```

### Modelo y vista

- `NovedadModel` extiende `CodeIgniter\Model` y usa el **Query Builder** para filtrar por `publicado`, buscar por título, resumen o contenido y ordenar por fecha.
- La vista principal usa el **motor de plantillas de CodeIgniter**: `layouts/main.php` define el layout y las vistas hijas usan `extend()`, `section()` y `endSection()`.
- Los datos se escapan con `esc()` y las URL se generan con `site_url()` / `base_url()`.

### Casos probados

| N.º | Caso | Resultado |
|---|---|---|
| 1 | `php spark routes` | Muestra las 4 rutas configuradas. |
| 2 | Abrir `/novedades` | `200`; 4 novedades ordenadas por fecha. Captura `08-framework-listado.png`. |
| 3 | Buscar `?q=taller` | `200`; 2 coincidencias por título/contenido. |
| 4 | Abrir `/novedades/8` | `200`; detalle completo. Captura `09-framework-detalle.png`. |
| 5 | Abrir `/api/novedades` | `200`; `Content-Type: application/json`; `count: 4`. Captura `10-framework-api-json.png`. |
| 6 | Abrir `/novedades/9999` | `404` con la página de error del framework. |
| 7 | Ver la tabla `novedades` en phpMyAdmin | 4 filas con título, resumen, contenido, fecha y estado. Captura `11-bd-novedades.png`. |
| 8 | Revisar `writable/logs` | Sin errores de la aplicación. |

Ejemplo de respuesta del endpoint:

```json
{
  "status": "success",
  "count": 4,
  "data": [
    {
      "id": 8,
      "titulo": "Muestra anual de Educación Física",
      "resumen": "Actividades recreativas y deportivas para compartir una jornada en comunidad.",
      "fecha": "2026-11-20",
      "url": "http://localhost/instituto-php/tp10/framework/public/novedades/8"
    }
  ]
}
```

## Cumplimiento de los requisitos del TP10

| Requisito | Implementación |
|---|---|
| Refactorizar un módulo previo a MVC nativo | CRUD de cursos, antes concentrado en `admin/*.php`. |
| Carpetas `app/Models`, `app/Views`, `app/Controllers` | Creadas en la raíz del proyecto. |
| Modelos con PDO y sentencias preparadas | `app/Models/Curso.php`. |
| Controladores que manejan la solicitud | `app/Controllers/CursoController.php`. |
| Vistas con HTML limpio | `app/Views/cursos/index.php`, `form.php` y `eliminar.php`. |
| Framework | CodeIgniter 4.7.6, instalado con Composer. |
| Rutas | `tp10/framework/app/Config/Routes.php`. |
| Controlador del framework | `tp10/framework/app/Controllers/Novedades.php`. |
| Modelo con ORM/Query Builder | `tp10/framework/app/Models/NovedadModel.php`. |
| Vista con motor de plantillas | Layout y secciones en `tp10/framework/app/Views/`. |
| Módulo adicional e independiente | Módulo Novedades con tabla `novedades`, búsqueda, detalle y API JSON. |
| Proyecto del framework incluido en el repositorio | Carpeta `tp10/framework/` (sin `vendor/`, que se instala con Composer). |
| Commit descriptivo | `feat: refactorizacion MVC e implementacion de modulo con framework`. |

## Comandos útiles

```bash
# Crear/repoblar la tabla del módulo
mysql -u root --default-character-set=utf8mb4 < sql/novedades_tp10.sql

# Ver las rutas del framework
cd tp10/framework
php spark routes

# Instalar dependencias si no existe vendor/
composer install

# Verificar sintaxis
php -l app/Models/Curso.php
php -l app/Controllers/CursoController.php
php -l tp10/framework/app/Controllers/Novedades.php
```

## Capturas

Las imágenes están en [`docs/capturas/tp10/`](capturas/tp10/).

1. [Listado nativo](capturas/tp10/01-nativo-listado.png)
2. [Formulario de alta nativo](capturas/tp10/02-nativo-crear.png)
3. [Alta confirmada](capturas/tp10/03-nativo-creado.png)
4. [Formulario de edición nativo](capturas/tp10/04-nativo-editar.png)
5. [Actualización confirmada](capturas/tp10/05-nativo-actualizado.png)
6. [Confirmación de eliminación](capturas/tp10/06-nativo-confirmar-eliminar.png)
7. [Eliminación confirmada](capturas/tp10/07-nativo-eliminado.png)
8. [Listado del módulo CodeIgniter](capturas/tp10/08-framework-listado.png)
9. [Detalle de la novedad](capturas/tp10/09-framework-detalle.png)
10. [Endpoint JSON](capturas/tp10/10-framework-api-json.png)
11. [Tabla `novedades` en phpMyAdmin](capturas/tp10/11-bd-novedades.png)
12. [Validación del formulario nativo](capturas/tp10/12-nativo-validacion.png)
