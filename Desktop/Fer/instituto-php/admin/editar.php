<?php
declare(strict_types=1);

/**
 * Compatibilidad con la URL anterior `admin/editar.php`.
 * La lógica vive en `app/Controllers/CursoController.php`.
 */

$_GET['accion'] = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'actualizar' : 'editar';

require __DIR__ . '/index.php';
