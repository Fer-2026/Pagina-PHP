<?php
declare(strict_types=1);

require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/config/conexion.php';

$stmt = $pdo->prepare(
    'SELECT id, nombre, email, rol, created_at
     FROM usuarios
     WHERE id = :id
     LIMIT 1'
);
$stmt->execute([':id' => $_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if (!$usuario) {
    // La cuenta ya no existe: se invalida la sesión.
    $_SESSION = [];
    session_destroy();
    redirigir('login.php?error=sesion_invalida');
}

// Mantiene la sesión sincronizada con los datos actuales de la base.
$_SESSION['usuario_nombre'] = (string) $usuario['nombre'];
$_SESSION['rol'] = (string) $usuario['rol'];

$titulo_pagina = 'Mi panel';
$esAdmin = $usuario['rol'] === 'admin';
$fechaAlta = date('d/m/Y H:i', strtotime((string) $usuario['created_at']));

require_once __DIR__ . '/includes/header.php';
?>
<main id="contenido">
  <section class="page-header">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
          <nav aria-label="Migas de pan">
            <ol class="breadcrumb mb-2">
              <li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Inicio</a></li>
              <li class="breadcrumb-item active" aria-current="page">Mi panel</li>
            </ol>
          </nav>
          <h1 class="mb-1">Hola, <?= e($usuario['nombre']) ?></h1>
          <p class="mb-0">Esta vista está protegida: solo se muestra con una sesión iniciada.</p>
        </div>
        <span class="badge text-bg-<?= $esAdmin ? 'dark' : 'success' ?> fs-6">
          Rol: <?= e($usuario['rol']) ?>
        </span>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <?php if (isset($_GET['bienvenida'])): ?>
        <div class="alert alert-success" role="alert">
          Sesión iniciada correctamente. El ID de sesión fue regenerado con <code>session_regenerate_id(true)</code>.
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['error']) && $_GET['error'] === 'sin_permiso'): ?>
        <div class="alert alert-danger" role="alert">
          No tenés permisos para acceder a esa sección. La cuenta con la que ingresaste tiene rol
          <strong><?= e($_SESSION['rol'] ?? 'usuario') ?></strong>.
        </div>
      <?php endif; ?>

      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card h-100">
            <div class="card-body p-4">
              <h2 class="h5 mb-3">Datos de la cuenta</h2>
              <dl class="row mb-0">
                <dt class="col-sm-4">ID de usuario</dt>
                <dd class="col-sm-8"><?= e($usuario['id']) ?></dd>

                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8"><?= e($usuario['nombre']) ?></dd>

                <dt class="col-sm-4">Correo</dt>
                <dd class="col-sm-8"><?= e($usuario['email']) ?></dd>

                <dt class="col-sm-4">Rol</dt>
                <dd class="col-sm-8"><span class="badge text-bg-success"><?= e($usuario['rol']) ?></span></dd>

                <dt class="col-sm-4">Alta</dt>
                <dd class="col-sm-8"><?= e($fechaAlta) ?></dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card h-100">
            <div class="card-body p-4">
              <h2 class="h5 mb-3">Variables de sesión</h2>
              <p class="note">Estos son los datos guardados en <code>$_SESSION</code>:</p>
              <ul class="list-group list-group-flush session-list">
                <li class="list-group-item px-0">
                  <code>$_SESSION['usuario_id']</code><br>
                  <strong><?= e($_SESSION['usuario_id']) ?></strong>
                </li>
                <li class="list-group-item px-0">
                  <code>$_SESSION['usuario_nombre']</code><br>
                  <strong><?= e($_SESSION['usuario_nombre']) ?></strong>
                </li>
                <li class="list-group-item px-0">
                  <code>$_SESSION['rol']</code><br>
                  <strong><?= e($_SESSION['rol']) ?></strong>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-body p-4 d-flex flex-wrap gap-2 align-items-center">
              <h2 class="h5 me-auto mb-0">Acciones disponibles</h2>

              <?php if ($esAdmin): ?>
                <a class="btn btn-dark" href="<?= e(url('admin_panel.php')) ?>">Panel de administración</a>
                <a class="btn btn-outline-dark" href="<?= e(url('admin/index.php')) ?>">Gestionar cursos</a>
              <?php endif; ?>

              <a class="btn btn-outline-secondary" href="<?= e(url('index.php')) ?>">Volver al sitio</a>
              <a class="btn btn-danger" href="<?= e(url('logout.php')) ?>">Cerrar sesión</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
