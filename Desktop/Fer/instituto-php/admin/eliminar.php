<?php
declare(strict_types=1);

/**
 * Compatibilidad con la URL anterior `admin/eliminar.php`.
 * La eliminación ahora se confirma mediante POST y token CSRF.
 */

$_GET['accion'] = $_SERVER['REQUEST_METHOD'] === 'POST' ? 'eliminar' : 'confirmar_eliminar';

require __DIR__ . '/index.php';
