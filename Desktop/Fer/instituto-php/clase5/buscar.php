<?php
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/includes/formularios.php';

$datos_agenda = require __DIR__ . '/data/eventos.php';
$tipos = $datos_agenda['tipos'];
$eventos = $datos_agenda['eventos'];
$paginas = require __DIR__ . '/data/paginas.php';

// --- Entrada GET: se sanitiza ANTES de usarla ---
$q = limpiar(filter_input(INPUT_GET, 'q', FILTER_DEFAULT), 80);
$tipo = limpiar(filter_input(INPUT_GET, 'tipo', FILTER_DEFAULT), 20);
if ($tipo !== '' && !array_key_exists($tipo, $tipos)) {
    $tipo = ''; // lista blanca: cualquier otro valor se descarta
}

$buscado = isset($_GET['q']) || isset($_GET['tipo']);
$error = null;
$res_paginas = [];
$res_eventos = [];

if ($buscado) {
    if ($q === '' && $tipo === '') {
        $error = 'Escribí una palabra o elegí un tipo de evento para buscar.';
    } elseif ($q !== '' && mb_strlen($q) < 2) {
        $error = 'La búsqueda debe tener al menos 2 caracteres.';
    } else {
        $aguja = normalizar($q);
        if ($tipo === '') {
            foreach ($paginas as $p) {
                if ($aguja !== '' && str_contains(normalizar($p['titulo'] . ' ' . $p['resumen']), $aguja)) {
                    $res_paginas[] = $p;
                }
            }
        }
        foreach ($eventos as $ev) {
            if ($tipo !== '' && $ev['tipo'] !== $tipo) {
                continue;
            }
            if ($aguja === '' || str_contains(normalizar($ev['texto']), $aguja)) {
                $res_eventos[] = $ev;
            }
        }
    }
}

$titulo_pagina = 'Buscar';
require_once 'includes/header.php';
?>
    <main id="contenido">
      <section class="page-header">
        <div class="container">
          <nav aria-label="Ruta"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Inicio</a></li><li class="breadcrumb-item active" aria-current="page">Buscar</li></ol></nav>
          <h1>Buscar en el sitio</h1>
          <p class="lead mb-0">Encontrá páginas y fechas de la agenda escolar.</p>
        </div>
      </section>
      <section class="py-5">
        <div class="container">
          <form class="row g-3 align-items-end" action="buscar.php" method="get" role="search">
            <div class="col-md-6">
              <label class="form-label" for="q">¿Qué buscás?</label>
              <input class="form-control" id="q" name="q" type="search" maxlength="80" value="<?= e($q) ?>" placeholder="Ej.: feriado, Güemes, becas" />
            </div>
            <div class="col-md-4">
              <label class="form-label" for="tipo">Tipo de evento (opcional)</label>
              <select class="form-select" id="tipo" name="tipo">
                <option value="">Todos</option>
                <?php foreach ($tipos as $clave => $nombre): ?>
                  <option value="<?= e($clave) ?>"<?= $tipo === $clave ? ' selected' : '' ?>><?= e($nombre) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2"><button class="btn btn-accent w-100" type="submit">Buscar</button></div>
          </form>

          <div class="mt-4" aria-live="polite">
            <?php if ($error): ?>
              <div class="alert alert-warning" role="alert"><?= e($error) ?></div>
            <?php elseif ($buscado): ?>
              <?php $total = count($res_paginas) + count($res_eventos); ?>
              <p class="note">
                <?= $total ?> resultado<?= $total === 1 ? '' : 's' ?>
                <?php if ($q !== ''): ?>para “<strong><?= e($q) ?></strong>”<?php endif; ?>
                <?php if ($tipo !== ''): ?>en <?= e($tipos[$tipo]) ?><?php endif; ?>.
              </p>
              <?php if ($total === 0): ?>
                <div class="alert alert-info" role="status">No encontramos resultados. Probá con otra palabra.</div>
              <?php endif; ?>

              <?php if ($res_paginas): ?>
                <h2 class="h5 mt-4">Páginas</h2>
                <ul class="list-group mb-4">
                  <?php foreach ($res_paginas as $p): ?>
                    <li class="list-group-item"><a href="<?= e($p['url']) ?>"><strong><?= e($p['titulo']) ?></strong></a><br><?= e($p['resumen']) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>

              <?php if ($res_eventos): ?>
                <h2 class="h5 mt-4">Agenda</h2>
                <ul class="list-group">
                  <?php foreach ($res_eventos as $ev): ?>
                    <li class="list-group-item">
                      <strong><?= e(date('d/m/Y', strtotime($ev['fecha']))) ?></strong>
                      <span class="chip chip-<?= e($ev['tipo']) ?>"><?= e($tipos[$ev['tipo']]) ?></span>
                      <?= e($ev['texto']) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </main>
<?php require_once 'includes/footer.php'; ?>
