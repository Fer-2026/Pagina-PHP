<?php
// Menú principal: cada ítem es [texto, archivo] o [texto, [submenú]].
$menu = [
    ['Inicio', 'index.php'],
    ['Institucional', [
        ['Quiénes somos', 'institucional.php'],
        ['Equipo docente', 'docentes.php'],
    ]],
    ['Académico', [
        ['Propuesta educativa', 'propuesta-educativa.php'],
        ['Plan de estudios', 'plan-de-estudios.php'],
        ['Ciencias Naturales', 'ciencias-naturales.php'],
    ]],
    ['Agenda 2026', 'agenda.php'],
    ['Galería', 'galeria.php'],
    ['Inscripción', 'inscripcion.php'],
    ['Contacto', 'contacto.php'],
];

$pagina_actual = basename($_SERVER['SCRIPT_NAME']);

function grupo_activo(array $hijos, string $actual): bool
{
    foreach ($hijos as [, $archivo]) {
        if ($archivo === $actual) {
            return true;
        }
    }
    return false;
}
?>
    <header>
      <nav class="navbar navbar-expand-xl bg-white sticky-top" aria-label="Principal">
        <div class="container">
          <a class="navbar-brand" href="index.php"><img class="logo" src="img/logo.png" width="48" height="48" alt="" /><?= e(APP_NAME) ?></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
              <?php foreach ($menu as [$texto, $destino]): ?>
                <?php if (is_array($destino)): ?>
                  <?php $abierto = grupo_activo($destino, $pagina_actual); ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?= $abierto ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= e($texto) ?></a>
                    <ul class="dropdown-menu">
                      <?php foreach ($destino as [$subtexto, $archivo]): ?>
                        <?php $activo = ($archivo === $pagina_actual); ?>
                        <li><a class="dropdown-item<?= $activo ? ' active' : '' ?>" href="<?= e($archivo) ?>"<?= $activo ? ' aria-current="page"' : '' ?>><?= e($subtexto) ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                <?php else: ?>
                  <?php $activo = ($destino === $pagina_actual); ?>
                  <li class="nav-item">
                    <a class="nav-link<?= $activo ? ' active' : '' ?>" href="<?= e($destino) ?>"<?= $activo ? ' aria-current="page"' : '' ?>><?= e($texto) ?></a>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
            <!-- Formulario GET: buscador -->
            <form class="d-flex gap-2 ms-xl-3 mt-3 mt-xl-0" role="search" action="buscar.php" method="get">
              <label class="visually-hidden" for="q-nav">Buscar en el sitio</label>
              <input class="form-control form-control-sm" id="q-nav" name="q" type="search" maxlength="80" placeholder="Buscar" />
              <button class="btn btn-sm btn-accent" type="submit">Buscar</button>
            </form>
          </div>
        </div>
      </nav>
    </header>
