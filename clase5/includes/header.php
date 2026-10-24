<?php
require_once __DIR__ . '/../config/env.php';
$titulo_pagina = $titulo_pagina ?? 'Inicio';
$descripcion_pagina = $descripcion_pagina ?? 'Educación Secundaria, turno mañana, orientación en Ciencias Naturales. Salta.';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= e($titulo_pagina) ?> | <?= e(APP_NAME) ?></title>
    <link rel="icon" href="img/favicon.png" type="image/png" />
    <meta name="description" content="<?= e($descripcion_pagina) ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
  </head>
  <body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="topbar">
      <div class="container d-flex justify-content-between flex-wrap gap-2">
        <span>Salta · Educación Secundaria</span>
        <span>
          <a href="<?= e(APP_FACEBOOK) ?>" rel="noopener" target="_blank">Facebook<span class="visually-hidden"> (se abre en otra pestaña)</span></a> ·
          <a href="<?= e(APP_INSTAGRAM) ?>" rel="noopener" target="_blank">Instagram<span class="visually-hidden"> (se abre en otra pestaña)</span></a>
        </span>
      </div>
    </div>
    <?php require_once __DIR__ . '/nav.php'; ?>
