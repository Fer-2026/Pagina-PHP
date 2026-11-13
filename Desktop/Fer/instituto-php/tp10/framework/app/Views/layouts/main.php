<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Módulo independiente de novedades desarrollado con CodeIgniter 4 para el TP10.">
    <title><?= esc($title ?? 'Novedades | TP10') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/tp10.css') ?>">
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>

    <header class="site-header">
        <div class="wrap header-inner">
            <a class="brand" href="<?= site_url('novedades') ?>">
                <span class="brand-mark" aria-hidden="true">14</span>
                <span class="brand-text">
                    <strong>Instituto 14 de Abril</strong>
                    <small>Módulo Novedades · TP10</small>
                </span>
            </a>
            <nav aria-label="Navegación del módulo">
                <a href="<?= site_url('novedades') ?>">Novedades</a>
                <a href="<?= site_url('api/novedades') ?>">API JSON</a>
            </nav>
        </div>
    </header>

    <main id="contenido">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="site-footer">
        <div class="wrap footer-inner">
            <p>TP10 · Módulo independiente desarrollado con <strong>CodeIgniter 4</strong>.</p>
            <p>Rutas, controlador, modelo con Query Builder y vistas con el motor de plantillas del framework.</p>
        </div>
    </footer>
</body>
</html>
