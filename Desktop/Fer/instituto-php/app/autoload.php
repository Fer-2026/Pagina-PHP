<?php
declare(strict_types=1);

/**
 * Autoload mínimo para las clases de la capa MVC nativa del TP10.
 */
spl_autoload_register(static function (string $clase): void {
    $prefijo = 'App\\';

    if (!str_starts_with($clase, $prefijo)) {
        return;
    }

    $ruta = __DIR__ . '/' . str_replace('\\', '/', substr($clase, strlen($prefijo))) . '.php';

    if (is_file($ruta)) {
        require_once $ruta;
    }
});
