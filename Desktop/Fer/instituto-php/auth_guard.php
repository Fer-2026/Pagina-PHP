<?php
declare(strict_types=1);

/**
 * Guardián reutilizable de rutas.
 *
 * Uso:
 *   require_once __DIR__ . '/auth_guard.php';                 // solo sesión iniciada
 *   define('REQUERIR_ROL', 'admin');
 *   require_once __DIR__ . '/auth_guard.php';                 // sesión + rol admin
 */

require_once __DIR__ . '/config/sesion.php';

if (!isset($_SESSION['usuario_id'])) {
    redirigir('login.php?error=acceso_denegado');
}

if (defined('REQUERIR_ROL') && REQUERIR_ROL !== '' && REQUERIR_ROL !== null) {
    $rolActual = (string) ($_SESSION['rol'] ?? '');

    if ($rolActual !== REQUERIR_ROL) {
        redirigir('dashboard.php?error=sin_permiso');
    }
}
