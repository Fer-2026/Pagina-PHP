<?php
// Menú principal. La página activa se detecta por el nombre del archivo actual.
$menu = [
  'index.php' => 'Inicio',
  'institucional.php' => 'Institucional',
  'propuesta-educativa.php' => 'Propuesta educativa',
  'plan-de-estudios.php' => 'Plan de estudios',
  'ciencias-naturales.php' => 'Ciencias Naturales',
  'inscripcion.php' => 'Inscripción',
  'docentes.php' => 'Equipo docente',
  'agenda.php' => 'Agenda 2026',
  'galeria.php' => 'Galería',
  'contacto.php' => 'Contacto',
];
$pagina_actual = basename($_SERVER['SCRIPT_NAME']);
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
              <?php foreach ($menu as $archivo => $texto): ?>
                <?php $activo = ($archivo === $pagina_actual); ?>
                <li class="nav-item">
                  <a class="nav-link<?= $activo ? ' active' : '' ?>" href="<?= e($archivo) ?>"<?= $activo ? ' aria-current="page"' : '' ?>><?= e($texto) ?></a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>
