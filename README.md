# Instituto Cooperativo 14 de Abril N.º 8001: sitio web

Sitio web institucional (Salta): Educación Secundaria, turno mañana, 1.º a 5.º año, Bachiller en Ciencias Naturales.

> **Proyecto completo:** la versión actualizada está en [`Desktop/Fer/instituto-php/`](Desktop/Fer/instituto-php/). La documentación detallada, las instrucciones de instalación y **todas las capturas** están en el [README del proyecto](Desktop/Fer/instituto-php/README.md).
>
> **Para la corrección:** abrir esa carpeta como raíz del proyecto y usar el README completo. La copia de la raíz es la versión inicial del sitio.

## Documentación y accesos rápidos

- [README completo del proyecto](Desktop/Fer/instituto-php/README.md)
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

## TP3: navegación dinámica y componentes reutilizables

Menú generado desde un arreglo PHP, con estado activo, `aria-current` y componentes reutilizables en `includes/`.

![Menú de navegación del sitio](Desktop/Fer/instituto-php/docs/capturas/03-nav.jpg)

![Ítem activo y título dinámico](Desktop/Fer/instituto-php/docs/capturas/06-item-activo.jpg)

## TP4: migración a PHP, SSI y variables de entorno

Las 10 vistas pasaron a PHP y comparten header, nav y footer. El título se genera dinámicamente y la configuración se carga desde `.env`.

![Header reutilizable](Desktop/Fer/instituto-php/docs/capturas/02-header.jpg)

![Footer reutilizable](Desktop/Fer/instituto-php/docs/capturas/04-footer.jpg)

![Título dinámico en la pestaña](Desktop/Fer/instituto-php/docs/capturas/05-titulo-dinamico.jpg)

![Archivo .env.example en el repositorio](Desktop/Fer/instituto-php/docs/capturas/07-env-example.jpg)

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

## Ejecución rápida

1. Iniciar **Apache** y **MySQL** desde XAMPP.
2. Copiar `Desktop/Fer/instituto-php/` a `htdocs/instituto-php/` o configurar el `DocumentRoot` apuntando a esa carpeta.
3. Abrir `http://localhost/instituto-php/`.
4. Para el módulo CodeIgniter, ejecutar `composer install` dentro de `tp10/framework/` si falta `vendor/`.

La explicación completa de cada TP, los requisitos y las pruebas están en el [README del proyecto](Desktop/Fer/instituto-php/README.md).
