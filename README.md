# Instituto Cooperativo 14 de Abril N.º 8001: sitio web

Sitio web institucional (Salta): Educación Secundaria, turno mañana, 1.º a 5.º año, Bachiller en Ciencias Naturales.

> **Proyecto completo:** la versión actualizada está en [`Desktop/Fer/instituto-php/`](Desktop/Fer/instituto-php/). La documentación detallada, las instrucciones de instalación y **todas las capturas** están en el [README del proyecto](Desktop/Fer/instituto-php/README.md).
>
> **TP3 en línea:** la entrega estática HTML5 está publicada en <https://fer-2026.github.io/Pagina-PHP/>.
>
> **Para la corrección:** abrir esa carpeta como raíz del proyecto y usar el README completo. La copia de la raíz conserva la versión TP1–TP4 del sitio e incluye la versión estática de TP3 publicada en GitHub Pages.

## Prototipo de Figma

- [Repositorio del prototipo](https://github.com/Fer-2026/Dise-o-UI-UX-Mockup-Figma-)
- [Mockups en Figma](https://www.figma.com/make/UL3GSEQOHBF6XCXKV9COQ8/High-Fidelity-Mockups-for-School?t=qmuPPG4mTDi6oJpE-1)

## Tecnologías

HTML5, CSS3, Bootstrap 5.3, JavaScript, PHP 8.x, MySQL/MariaDB, XAMPP/Laragon, Git, GitHub, Prettier y CodeIgniter 4 (TP10).

## Documentación y accesos rápidos

- [README completo del proyecto](Desktop/Fer/instituto-php/README.md)
- [Auditoría Lighthouse del TP3](docs/accesibilidad-lighthouse.md)
- [Carpeta general de capturas](Desktop/Fer/instituto-php/docs/capturas/)
- [Capturas de la Clase 5 / TP5](Desktop/Fer/instituto-php/clase5/docs/capturas/)
- [Capturas del TP6](Desktop/Fer/instituto-php/tp6/docs/capturas/)
- [Capturas del TP8](Desktop/Fer/instituto-php/docs/capturas/tp8/)
- [Capturas del TP9](Desktop/Fer/instituto-php/docs/capturas/tp9/)
- [Capturas del TP10](Desktop/Fer/instituto-php/docs/capturas/tp10/)
- [Pruebas del TP7](Desktop/Fer/instituto-php/docs/tp7-pruebas.md)
- [Pruebas del TP8](Desktop/Fer/instituto-php/docs/tp8-pruebas.md)
- [Pruebas del TP9](Desktop/Fer/instituto-php/docs/tp9-pruebas.md)
- [Pruebas del TP10](Desktop/Fer/instituto-php/docs/tp10-pruebas.md)

**Cuentas de prueba**

| Rol | Correo | Contraseña |
|---|---|---|
| admin | `admin@instituto.edu.ar` | `Admin123!` |
| usuario | `usuario@instituto.edu.ar` | `Usuario123!` |

## TP1: sitio estático y maquetación

Sitio construido con HTML5, CSS3 y Bootstrap 5.3, con 10 vistas y estructura común de header, navegación y footer.

![Sitio base ejecutándose en localhost](Desktop/Fer/instituto-php/docs/capturas/01-localhost.jpg)

## TP2: prototipo de Figma y diseño responsive

Prototipo visual y definición de la identidad del sitio antes de llevarlo a código.

![Inicio del prototipo en Figma](Desktop/Fer/instituto-php/docs/capturas/tp2/01-figma-inicio.jpg)

![Oferta académica en Figma](Desktop/Fer/instituto-php/docs/capturas/tp2/02-figma-oferta-academica.jpg)

![Contacto en Figma](Desktop/Fer/instituto-php/docs/capturas/tp2/03-figma-contacto.jpg)

## TP3: maquetación HTML5, responsive y accesibilidad

La entrega estática de TP3 está formada por **10 vistas HTML5 en la raíz del repositorio**, con Bootstrap 5.3, CSS propio, JavaScript y las carpetas `css/`, `js/` e `img/`. Se desarrolló en la rama `feature/maquetacion`, se formateó con Prettier y se publicó con GitHub Pages.

**Sitio publicado:** <https://fer-2026.github.io/Pagina-PHP/>

| Vista | Archivo estático |
|---|---|
| Inicio | [`index.html`](index.html) |
| Institucional | [`institucional.html`](institucional.html) |
| Docentes | [`docentes.html`](docentes.html) |
| Propuesta educativa | [`propuesta-educativa.html`](propuesta-educativa.html) |
| Plan de estudios | [`plan-de-estudios.html`](plan-de-estudios.html) |
| Ciencias Naturales | [`ciencias-naturales.html`](ciencias-naturales.html) |
| Agenda 2026 | [`agenda.html`](agenda.html) |
| Galería | [`galeria.html`](galeria.html) |
| Inscripción | [`inscripcion.html`](inscripcion.html) |
| Contacto | [`contacto.html`](contacto.html) |

**Auditoría Lighthouse:** [ver informe completo](docs/accesibilidad-lighthouse.md). En Mobile: Performance 98–99, Accesibilidad 100, Best Practices 100 y SEO 100. En Desktop: 100 en las cuatro categorías. Se corrigió el contraste de los enlaces del breadcrumb en `css/tp3.css`.

**Formato:** `npx prettier@3.3.3 --write "*.html" css/tp3.css` y `npx prettier@3.3.3 --check "*.html" css/tp3.css`.

![Inicio de la versión estática en escritorio](docs/capturas/tp3/01-sitio-estatico.png)

![Inicio responsive de la versión estática](docs/capturas/tp3/02-vista-movil.png)

![Resumen de la auditoría Lighthouse](docs/capturas/tp3/03-lighthouse.png)

La navegación dinámica con arreglo PHP, `aria-current` e `includes/` se incorporó después, en TP4; se conserva como evolución del proyecto:

![Menú de navegación del sitio](Desktop/Fer/instituto-php/docs/capturas/03-nav.jpg)

![Ítem activo y título dinámico](Desktop/Fer/instituto-php/docs/capturas/06-item-activo.jpg)

## TP4: migración a PHP, SSI y variables de entorno

Las 10 vistas pasaron a PHP y comparten header, nav y footer. El título se genera dinámicamente y la configuración se carga desde `.env`.

**1. Servidor local ejecutándose**

![Sitio corriendo en localhost/instituto-php](Desktop/Fer/instituto-php/docs/capturas/tp4/01-servidor-local.png)

**2. Estructura modular (SSI)**

![Código fuente de includes/header.php](Desktop/Fer/instituto-php/docs/capturas/tp4/02a-header.png)

![Código fuente de includes/nav.php](Desktop/Fer/instituto-php/docs/capturas/tp4/02b-nav.png)

![Código fuente de includes/footer.php](Desktop/Fer/instituto-php/docs/capturas/tp4/02c-footer.png)

**3. Navegación dinámica**

![Título dinámico en la pestaña y clase active en el menú](Desktop/Fer/instituto-php/docs/capturas/tp4/03-navegacion-dinamica.png)

**4. Variables de entorno**

![Archivo .env.example versionado en el repositorio](Desktop/Fer/instituto-php/docs/capturas/tp4/04-variables-entorno.png)

## TP5 / Clase 5: formularios y seguridad web

Formulario POST de contacto y buscador GET con sanitización, validación en servidor, escape XSS, token CSRF y patrón Post/Redirect/Get.

![Validación del formulario de contacto](Desktop/Fer/instituto-php/clase5/docs/capturas/01-contacto-errores.png)

![Envío exitoso del formulario](Desktop/Fer/instituto-php/clase5/docs/capturas/03-envio-exitoso.png)

![Intento de XSS mostrado como texto](Desktop/Fer/instituto-php/clase5/docs/capturas/04-xss-escapado.png)

![Búsqueda con resultados](Desktop/Fer/instituto-php/clase5/docs/capturas/05-busqueda-resultados.png)

[Ver las 8 capturas de TP5](Desktop/Fer/instituto-php/clase5/docs/capturas/)

## TP6: diseño y creación del esquema relacional en MySQL

Base `instituto_14_abril` normalizada a 1FN, 2FN y 3FN, con 9 tablas, claves foráneas, índices y consultas CRUD.

![Tablas creadas en phpMyAdmin](Desktop/Fer/instituto-php/tp6/docs/capturas/01-tablas-creadas.png)

![Modelo relacional en el diseñador](Desktop/Fer/instituto-php/tp6/docs/capturas/02-disenador.png)

![SELECT con WHERE y ORDER BY](Desktop/Fer/instituto-php/tp6/docs/capturas/03-select-where.png)

![INNER JOIN entre tablas](Desktop/Fer/instituto-php/tp6/docs/capturas/04-inner-join.png)

[Ver las 6 capturas de TP6](Desktop/Fer/instituto-php/tp6/docs/capturas/)

## TP7: CRUD de cursos

Listado, alta, edición y baja de cursos sobre la tabla `cursos` de `instituto_db`, con PDO, validación y CSRF.

![Listado de cursos en el panel de administración](Desktop/Fer/instituto-php/docs/capturas/tp7/01-crud-listado.png)

![Formulario de alta de un curso](Desktop/Fer/instituto-php/docs/capturas/tp7/02-crud-alta.png)

![Formulario de edición de un curso](Desktop/Fer/instituto-php/docs/capturas/tp7/03-crud-edicion.png)

## TP8: manejo de estado, sesiones y autenticación

Registro, login, logout, sesión segura y control de acceso por roles.

![Login del sistema](Desktop/Fer/instituto-php/docs/capturas/tp8/05-login-formulario.png)

![Dashboard del usuario](Desktop/Fer/instituto-php/docs/capturas/tp8/07-dashboard-usuario.png)

![Acceso denegado por rol](Desktop/Fer/instituto-php/docs/capturas/tp8/08-rol-denegado.png)

![Panel de administración](Desktop/Fer/instituto-php/docs/capturas/tp8/11-panel-admin.png)

[Ver las 15 capturas de TP8](Desktop/Fer/instituto-php/docs/capturas/tp8/)

## TP9: comunicación asincrónica y API JSON

Buscador con Fetch API, debounce, actualización parcial del DOM y endpoint JSON con códigos HTTP.

![Página inicial del buscador de cursos](Desktop/Fer/instituto-php/docs/capturas/tp9/01-cursos-inicial.png)

![Búsqueda web sin recargar](Desktop/Fer/instituto-php/docs/capturas/tp9/02-busqueda-web.png)

![Respuesta JSON 200](Desktop/Fer/instituto-php/docs/capturas/tp9/05-api-json-200.png)

![Respuesta JSON 404](Desktop/Fer/instituto-php/docs/capturas/tp9/06-api-json-404.png)

[Ver las 8 capturas de TP9](Desktop/Fer/instituto-php/docs/capturas/tp9/)

## TP10: patrón MVC e introducción a frameworks

Parte A: CRUD de cursos refactorizado a MVC nativo. Parte B: módulo independiente de Novedades con CodeIgniter 4.

![Listado del CRUD en MVC nativo](Desktop/Fer/instituto-php/docs/capturas/tp10/01-nativo-listado.png)

![Validación del formulario nativo](Desktop/Fer/instituto-php/docs/capturas/tp10/12-nativo-validacion.png)

![Listado del módulo CodeIgniter](Desktop/Fer/instituto-php/docs/capturas/tp10/08-framework-listado.png)

![Detalle de la novedad](Desktop/Fer/instituto-php/docs/capturas/tp10/09-framework-detalle.png)

![Endpoint JSON del módulo Novedades](Desktop/Fer/instituto-php/docs/capturas/tp10/10-framework-api-json.png)

![Tabla novedades en phpMyAdmin](Desktop/Fer/instituto-php/docs/capturas/tp10/11-bd-novedades.png)

[Ver las 12 capturas de TP10](Desktop/Fer/instituto-php/docs/capturas/tp10/)

## Instalación y ejecución local

1. Clonar el repositorio:

```bash
git clone https://github.com/Fer-2026/Pagina-PHP.git
```

2. Copiar la carpeta completa `Pagina-PHP/Desktop/Fer/instituto-php/` a `C:\xampp\htdocs\instituto-php` (o configurar el `DocumentRoot` apuntando a esa carpeta).
3. Crear el archivo local `.env` a partir de la plantilla:

```bash
cd /c/xampp/htdocs/instituto-php
cp .env.example .env
```

4. Iniciar **Apache** y **MySQL** desde XAMPP.
5. Abrir `http://localhost/instituto-php/`.
6. Para el módulo CodeIgniter, ejecutar `composer install` dentro de `tp10/framework/` si falta `vendor/`.

La explicación completa de cada TP, los requisitos y las pruebas están en el [README del proyecto](Desktop/Fer/instituto-php/README.md).

## Flujo de Git

La corrección y la evidencia de TP4 se integran desde la rama `feature/migracion-php-ssi` a `main` mediante Pull Request. Las etapas siguientes se desarrollaron en `feature/tp6-base-de-datos`, `feature/tp7-crud`, `feature/tp8-sesiones-autenticacion`, `feature/tp9-ajax-json` y `feature/tp10-mvc-framework`.
