# Instituto Cooperativo 14 de Abril N.º 8001: sitio web

Sitio web del instituto (Salta): Educación Secundaria, turno mañana, 1.º a 5.º año, Bachiller en Ciencias Naturales.

## Descripción y refactorización (Unidad 4)
El sitio estático del Hito 1 se migró a **PHP**:
- Las 10 vistas pasaron de `.html` a `.php`.
- Se aplicó **Server Side Includes**: `header.php`, `nav.php` y `footer.php` en `includes/` eliminan el código repetido.
- **SSR básico:** el `<title>` cambia según `$titulo_pagina` y el menú marca la página activa (`active` y `aria-current`).
- **Variables de entorno:** `.env` (ignorado por Git) con `.env.example` como plantilla, leído por `config/env.php`.

## Prototipo de Figma
- Figma: PEGAR-ENLACE-PUBLICO-AQUÍ
- Mockups exportados: https://github.com/TU-USUARIO/NOMBRE-REPO-FIGMA

## Tecnologías
HTML5, CSS3, Bootstrap 5.3, JavaScript, PHP 8.x, XAMPP / Laragon, Git y GitHub, Prettier.

## Estructura
```
config/env.php        carga de variables de entorno
includes/             header.php, nav.php, footer.php
css/  js/  img/       recursos
*.php                 10 vistas
.env.example          plantilla de variables
```

## Instalación y ejecución local
1. Instalá **XAMPP** (o **Laragon**) con PHP 8.x.
2. Cloná el repositorio dentro de la carpeta del servidor:
   - XAMPP: `C:\xampp\htdocs\`
   - Laragon: `C:\laragon\www\`
```bash
git clone https://github.com/TU-USUARIO/instituto-14-de-abril.git
cd instituto-14-de-abril
cp .env.example .env
```
3. Iniciá **Apache** (XAMPP: botón *Start*; Laragon: *Iniciar todo*).
4. Abrí http://localhost/instituto-14-de-abril/
5. Edit­á `.env` si querés cambiar el nombre, el correo o el entorno.

Alternativa sin XAMPP: `php -S localhost:8000` dentro de la carpeta.

## Evidencia de funcionamiento
![Servidor local ejecutándose (localhost)](d:\Sesion BENJA FRANCIA\Imágenes\Captura pagina php.jpg)
![Estructura modular: header.php](d:\Sesion BENJA FRANCIA\Imágenes\Header php.jpg)
![Estructura modular: nav.php](d:\Sesion BENJA FRANCIA\Imágenes\Navbar php.jpg)
![Estructura modular: footer.php](d:\Sesion BENJA FRANCIA\Imágenes\Footer php.jpg)
![Título dinámico en la pestaña](d:\Sesion BENJA FRANCIA\Imágenes\titulo dinamico.jpg)
![Ítem activo en el menú](d:\Sesion BENJA FRANCIA\Imágenes\menu php.jpg)
![Archivo .env.example en el repositorio](d:\Sesion BENJA FRANCIA\Imágenes\.env example php.jpg)

## Flujo de Git
Ramas `feature/maquetacion` y `feature/migracion-php-ssi`, integradas a `main` con Pull Request.

## Fuentes
Datos del instituto: sitios públicos y su perfil de Instagram. Los nombres del equipo docente son ficticios (maqueta). Agenda: PDF institucional 2026.
