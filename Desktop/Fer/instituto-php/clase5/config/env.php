<?php
/**
 * Carga las variables de entorno desde .env (o .env.example si .env no existe).
 * .env NO se sube a Git: cada entorno tiene el suyo.
 */
function cargar_env(string $ruta): array
{
    $vars = [];
    if (!is_file($ruta)) {
        return $vars;
    }
    foreach (file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        $linea = trim($linea);
        if ($linea === '' || $linea[0] === '#' || !str_contains($linea, '=')) {
            continue;
        }
        [$clave, $valor] = explode('=', $linea, 2);
        $vars[trim($clave)] = trim(trim($valor), "\"'");
    }
    return $vars;
}

function e($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

$raiz = dirname(__DIR__);
$archivo_env = is_file($raiz . '/.env') ? $raiz . '/.env' : $raiz . '/.env.example';
$env = cargar_env($archivo_env);

define('APP_NAME', $env['APP_NAME'] ?? 'Mi Proyecto');
define('APP_EMAIL', $env['APP_EMAIL'] ?? 'contacto@example.com');
define('APP_ENV', $env['APP_ENV'] ?? 'local');
define('APP_FACEBOOK', $env['APP_FACEBOOK'] ?? '#');
define('APP_INSTAGRAM', $env['APP_INSTAGRAM'] ?? '#');

$nombre_sitio = APP_NAME;
$email_contacto = APP_EMAIL;
