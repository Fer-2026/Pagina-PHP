<?php
/**
 * Utilidades para formularios: sanitización, escape de salida y token CSRF.
 * Regla del TP: ninguna variable de $_POST / $_GET se usa sin pasar por limpiar().
 */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

/** Sanitiza una entrada: solo texto, sin espacios sobrantes ni caracteres de control. */
function limpiar($valor, int $max = 200, bool $multilinea = false): string
{
    if (!is_string($valor)) {
        return '';
    }
    $valor = str_replace("\r\n", "\n", trim($valor));
    $permitidos = $multilinea ? '/[^\P{C}\n]+/u' : '/\p{C}+/u';
    $valor = preg_replace($permitidos, '', $valor) ?? '';
    return mb_substr($valor, 0, $max);
}

function normalizar(string $texto): string
{
    $texto = mb_strtolower($texto, 'UTF-8');
    return strtr($texto, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_verificar($token): bool
{
    return is_string($token) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function clase_invalida(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? ' is-invalid' : '';
}

function atributos_error(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? ' aria-invalid="true" aria-describedby="error-' . $campo . '"' : '';
}
