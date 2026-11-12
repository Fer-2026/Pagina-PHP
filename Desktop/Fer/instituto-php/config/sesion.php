<?php
declare(strict_types=1);

/**
 * Configuración central de sesiones y helpers del TP8.
 * Se incluye desde el header y desde las páginas protegidas.
 */

require_once __DIR__ . '/env.php';

if (!defined('BASE_URL')) {
    $docRoot = str_replace('\\', '/', (string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $projectRoot = str_replace('\\', '/', dirname(__DIR__));
    $docRoot = rtrim($docRoot, '/');
    $projectRoot = rtrim($projectRoot, '/');

    $basePath = '';
    if ($docRoot !== '' && stripos($projectRoot, $docRoot) === 0) {
        $basePath = substr($projectRoot, strlen($docRoot));
    }

    define('BASE_URL', rtrim($basePath, '/'));
}

if (!function_exists('url')) {
    /**
     * Devuelve una URL absoluta dentro del proyecto.
     */
    function url(string $ruta = ''): string
    {
        return BASE_URL . '/' . ltrim($ruta, '/');
    }
}

if (!function_exists('redirigir')) {
    /**
     * Redirige y detiene la ejecución.
     */
    function redirigir(string $ruta): never
    {
        header('Location: ' . url($ruta));
        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    $cookiePath = BASE_URL !== '' ? BASE_URL . '/' : '/';

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');

    session_name('INSTITUTO_TP8');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookiePath,
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

if (!function_exists('csrf_token')) {
    /**
     * Genera (una sola vez por sesión) el token CSRF.
     */
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_campo')) {
    /**
     * Campo oculto listo para insertar en un formulario.
     */
    function csrf_campo(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_valido')) {
    /**
     * Compara el token enviado con el guardado en sesión.
     */
    function csrf_valido(): bool
    {
        $enviado = $_POST['csrf_token'] ?? '';

        return is_string($enviado)
            && $enviado !== ''
            && isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $enviado);
    }
}

if (!function_exists('usuario_autenticado')) {
    function usuario_autenticado(): bool
    {
        return isset($_SESSION['usuario_id']);
    }
}

if (!function_exists('es_admin')) {
    function es_admin(): bool
    {
        return (($_SESSION['rol'] ?? '') === 'admin');
    }
}

if (!function_exists('nombre_usuario')) {
    function nombre_usuario(): string
    {
        return (string) ($_SESSION['usuario_nombre'] ?? '');
    }
}
