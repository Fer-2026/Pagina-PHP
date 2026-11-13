# Módulo Novedades - CodeIgniter 4 (TP10)

Proyecto independiente del TP10. Implementa un módulo público de **Novedades institucionales** con rutas, controlador, modelo con Query Builder, vistas con layout y un endpoint JSON.

## Requisitos

- PHP 8.1 o superior con `intl`, `mbstring`, `mysqli` y `pdo_mysql`.
- MySQL/MariaDB con la base `instituto_db`.
- Composer (solo si no existe `vendor/`).

## Instalación

Desde la raíz del repositorio:

```bash
mysql -u root --default-character-set=utf8mb4 < sql/novedades_tp10.sql
cd tp10/framework
composer install
```

La configuración local ya apunta a:

- URL base: `http://localhost/instituto-php/tp10/framework/public/`
- Base de datos: `instituto_db`
- Usuario: `root`
- Contraseña: vacía

Si el proyecto se mueve a otra ruta, editar `app/Config/App.php` y `app/Config/Database.php`.

## Rutas

| Método | Ruta | Función |
|---|---|---|
| GET | `/` | Inicio del proyecto; muestra Novedades |
| GET | `/novedades` | Listado y búsqueda (`?q=`) |
| GET | `/novedades/{id}` | Detalle de una novedad |
| GET | `/api/novedades` | Listado JSON |

## Estructura del módulo

```text
app/
  Config/Routes.php
  Controllers/Novedades.php
  Models/NovedadModel.php
  Views/layouts/main.php
  Views/novedades/index.php
  Views/novedades/show.php
public/
  index.php
  assets/tp10.css
```

## Verificación

```bash
php spark routes
php -l app/Controllers/Novedades.php
php -l app/Models/NovedadModel.php
```

Abrir:

- `http://localhost/instituto-php/tp10/framework/public/novedades`
- `http://localhost/instituto-php/tp10/framework/public/novedades/4`
- `http://localhost/instituto-php/tp10/framework/public/api/novedades`

El detalle de las pruebas y las capturas está en `../../docs/tp10-pruebas.md`.
