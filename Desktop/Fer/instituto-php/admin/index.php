<?php
declare(strict_types=1);

/**
 * Front controller del CRUD de cursos.
 *
 * Conserva la URL histórica `admin/index.php` y delega el trabajo en el
 * controlador de la capa MVC nativa del TP10.
 */

define('REQUERIR_ROL', 'admin');

require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../app/autoload.php';

$controlador = new App\Controllers\CursoController($pdo);
$accion = (string) ($_GET['accion'] ?? 'listar');

$controlador->despachar($accion);
