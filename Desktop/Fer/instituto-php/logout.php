<?php
declare(strict_types=1);

require_once __DIR__ . '/config/sesion.php';

// 1. Vaciar la superglobal de sesión.
session_unset();
$_SESSION = [];

// 2. Eliminar la cookie de sesión del navegador.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite'] ?? 'Lax',
    ]);
}

// 3. Destruir la sesión en el servidor.
session_destroy();

redirigir('login.php?logout=ok');
