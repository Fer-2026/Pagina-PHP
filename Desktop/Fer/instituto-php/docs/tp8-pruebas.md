# TP8: pruebas de funcionamiento

## Entorno de prueba

- Sitio: <http://localhost/instituto-php/>
- PHP 8.2.12, Apache 2.4.58 y MariaDB 10.4.32 sobre XAMPP.
- Base de datos: `instituto_db`.
- Tabla creada: `usuarios`.
- Fecha de la prueba: 12/11/2026.

## Cuentas utilizadas

| Rol | Nombre | Correo | Contraseña | Origen |
|---|---|---|---|---|
| admin | Administrador del Instituto | `admin@instituto.edu.ar` | `Admin123!` | Datos semilla de `sql/usuarios_tp8.sql` |
| usuario | Usuario de Prueba | `usuario@instituto.edu.ar` | `Usuario123!` | Datos semilla de `sql/usuarios_tp8.sql` |
| usuario | Lucia Gomez | `lucia.gomez@example.com` | `Lucia1234` | Creada desde `registro.php` durante la prueba |
| usuario | Martina Ríos | `martina.rios@example.com` | `Martina1234` | Creada desde `registro.php` durante la prueba |

Las dos últimas cuentas explican por qué el panel de administración muestra cuatro usuarios en las capturas. El archivo SQL deja solamente las dos cuentas semilla para que la corrección sea reproducible.

## Casos ejecutados

| # | Caso | Resultado | Evidencia |
|---|---|---|---|
| 1 | Estructura de `usuarios` | `id`, `nombre`, `email` único, `password VARCHAR(255)`, `rol` y `created_at` creados correctamente. | `docs/capturas/tp8/14-bd-estructura.png` |
| 2 | Contraseñas almacenadas | Todos los registros guardan un hash bcrypt de 60 caracteres que empieza con `$2y$10$`. | `docs/capturas/tp8/15-bd-datos-hash.png` |
| 3 | Registro válido | Se crea la cuenta con rol `usuario`, hash bcrypt y redirección a `login.php?registro=ok`. | `docs/capturas/tp8/02-registro-formulario.png` y `04-registro-exitoso.png` |
| 4 | Correo duplicado | El registro se rechaza con el mensaje «El correo electrónico ya está registrado». | `docs/capturas/tp8/03-registro-error-duplicado.png` |
| 5 | Contraseña débil | Se rechaza una contraseña de menos de 8 caracteres. | Salida de terminal de este documento |
| 6 | Confirmación distinta | Se rechaza cuando `password` y `password_confirm` no coinciden. | Salida de terminal de este documento |
| 7 | Correo inválido | `FILTER_VALIDATE_EMAIL` rechaza el valor y se muestra el error. | Salida de terminal de este documento |
| 8 | Formulario de login | Se muestra el formulario con token CSRF. | `docs/capturas/tp8/05-login-formulario.png` |
| 9 | Credenciales incorrectas | Se muestra un error genérico sin indicar si el correo existe. | `docs/capturas/tp8/06-login-error.png` |
| 10 | Login correcto | Se guardan `usuario_id`, `usuario_nombre` y `rol`; se redirige al panel. | `docs/capturas/tp8/07-dashboard-usuario.png` |
| 11 | Regeneración de sesión | El ID de sesión cambia después de `session_regenerate_id(true)`. | Salida de terminal de este documento |
| 12 | Cookie de sesión | Se emite con `HttpOnly`, `SameSite=Lax` y ruta `/instituto-php/`. | Salida de terminal de este documento |
| 13 | Guardián sin sesión | `dashboard.php` redirige a `login.php?error=acceso_denegado`. | `docs/capturas/tp8/10-acceso-denegado.png` |
| 14 | Usuario intenta el panel admin | El rol `usuario` es redirigido a `dashboard.php?error=sin_permiso`. | `docs/capturas/tp8/08-rol-denegado.png` |
| 15 | CRUD sin sesión | Las cuatro rutas de `admin/` redirigen al login. | Salida de terminal de este documento |
| 16 | CRUD con rol usuario | Las cuatro rutas redirigen al dashboard y el curso no se elimina. | Salida de terminal de este documento |
| 17 | Panel de administración | El administrador ve los usuarios, los contadores y los accesos al CRUD. | `docs/capturas/tp8/11-panel-admin.png` |
| 18 | CRUD de cursos | Crear, editar y eliminar funcionan con el guardián de rol activo y tokens CSRF. | `docs/capturas/tp8/12-cursos-admin.png` y salida de terminal |
| 19 | Logout | Se ejecutan `session_unset()`, borrado de cookie y `session_destroy()`; redirige a `login.php?logout=ok`. | `docs/capturas/tp8/09-logout-exitoso.png` |
| 20 | Integración en el sitio | El header muestra «Ingresar / Crear cuenta» como invitado y «Mi panel / Admin / Salir» con sesión. | `docs/capturas/tp8/01-home-invitado.png` y `13-home-admin.png` |

## Evidencia de sesión y cookies

Se comparó el ID de sesión antes y después del login. El identificador cambió y la cookie se emitió con las opciones de seguridad configuradas:

```text
Cookie antes del login:
INSTITUTO_TP8    j5po0m007j9s9iokplelt43153

Cookie después del login:
INSTITUTO_TP8    8573u29cjh6dk86tcti0l1idhq

Set-Cookie del login:
INSTITUTO_TP8=8573u29cjh6dk86tcti0l1idhq; path=/instituto-php/; HttpOnly; SameSite=Lax

Location del login:
/instituto-php/dashboard.php?bienvenida=1

Set-Cookie del logout:
INSTITUTO_TP8=deleted; expires=Thu, 01 Jan 1970 00:00:01 GMT; Max-Age=0; path=/instituto-php/; HttpOnly; SameSite=Lax
```

## Evidencia del guardián en el CRUD

Sin sesión, las cuatro rutas protegidas redirigen al login con el parámetro solicitado por el TP:

```text
/admin/index.php        -> 302 -> /instituto-php/login.php?error=acceso_denegado
/admin/crear.php        -> 302 -> /instituto-php/login.php?error=acceso_denegado
/admin/editar.php?id=1  -> 302 -> /instituto-php/login.php?error=acceso_denegado
/admin/eliminar.php?id=1 -> 302 -> /instituto-php/login.php?error=acceso_denegado
```

Con una sesión de rol `usuario`, las mismas rutas redirigen al panel del usuario y no ejecutan la operación:

```text
/admin/index.php        -> 302 -> /instituto-php/dashboard.php?error=sin_permiso
/admin/crear.php        -> 302 -> /instituto-php/dashboard.php?error=sin_permiso
/admin/editar.php?id=1  -> 302 -> /instituto-php/dashboard.php?error=sin_permiso
/admin/eliminar.php?id=1 -> 302 -> /instituto-php/dashboard.php?error=sin_permiso

Curso 1 después del intento de borrado:
1 | 'Desarrollo Web Frontend'
```

## Evidencia de validaciones del registro

```text
Contraseña débil:          OK, se rechaza "corta".
Contraseñas distintas:     OK, se rechaza la confirmación diferente.
Correo inválido:           OK, se rechaza "correo-invalido".
Correo duplicado:          OK, se rechaza un correo ya registrado.
Token CSRF inválido:       OK, se rechaza el envío.
```

## Evidencia del CRUD de cursos

Con la sesión de administrador se creó un curso temporal, se editó y se eliminó. Las tres operaciones redirigieron con el estado correspondiente y el curso de prueba desapareció de la base:

```text
Crear:    /instituto-php/admin/index.php?status=created
Editar:   /instituto-php/admin/index.php?status=updated
Eliminar: /instituto-php/admin/index.php?status=deleted
Filas restantes con el ID de prueba: 0
```

## Reproducción

1. Iniciar Apache y MySQL desde XAMPP.
2. Importar `sql/usuarios_tp8.sql` en `instituto_db`.
3. Abrir `http://localhost/instituto-php/registro.php` y crear una cuenta, o ingresar con alguna de las cuentas semilla.
4. Probar `dashboard.php`, `admin_panel.php`, `admin/index.php` y `logout.php`.
