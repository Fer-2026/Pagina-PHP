<?php
// Menú principal con submenús desplegables
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

// Helper de escape por si no existe en la configuración global
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

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

<nav class="navbar navbar-expand-lg bg-white sticky-top border-bottom" aria-label="Principal">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img class="logo" src="img/logo.png" alt="Logo Institucional" />
      <span><?= e(defined('APP_NAME') ? APP_NAME : 'Instituto Cooperativo') ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <?php foreach ($menu as [$texto, $destino]): ?>
          <?php if (is_array($destino)): ?>
            <?php $abierto = grupo_activo($destino, $pagina_actual); ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle<?= $abierto ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= e($texto) ?>
              </a>
              <ul class="dropdown-menu">
                <?php foreach ($destino as [$subtexto, $archivo]): ?>
                  <?php $activo = ($archivo === $pagina_actual); ?>
                  <li>
                    <a class="dropdown-item<?= $activo ? ' active' : '' ?>" href="<?= e($archivo) ?>"<?= $activo ? ' aria-current="page"' : '' ?>>
                      <?= e($subtexto) ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php else: ?>
            <?php $activo = ($destino === $pagina_actual); ?>
            <li class="nav-item">
              <a class="nav-link<?= $activo ? ' active' : '' ?>" href="<?= e($destino) ?>"<?= $activo ? ' aria-current="page"' : '' ?>>
                <?= e($texto) ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</nav>