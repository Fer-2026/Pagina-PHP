<?php
declare(strict_types=1);

define('REQUERIR_ROL', 'admin');

require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/config/conexion.php';

$totalUsuarios = (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
$totalAdmins = (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'")->fetchColumn();
$totalCursos = (int) $pdo->query('SELECT COUNT(*) FROM cursos')->fetchColumn();

$stmt = $pdo->query(
    'SELECT id, nombre, email, rol, created_at
     FROM usuarios
     ORDER BY id ASC'
);
$usuarios = $stmt->fetchAll();

$titulo_pagina = 'Panel de administración';

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
              <li class="breadcrumb-item"><a href="<?= e(url('dashboard.php')) ?>">Mi panel</a></li>
              <li class="breadcrumb-item active" aria-current="page">Administración</li>
            </ol>
          </nav>
          <h1 class="mb-1">Panel de administración</h1>
          <p class="mb-0">Vista restringida al rol <strong>admin</strong>.</p>
        </div>
        <span class="badge text-bg-dark fs-6">Solo administradores</span>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <?php if (isset($_GET['bienvenida'])): ?>
        <div class="alert alert-success" role="alert">
          Bienvenido/a, <?= e($_SESSION['usuario_nombre'] ?? '') ?>. Ingresaste con una cuenta administradora.
        </div>
      <?php endif; ?>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <p class="text-uppercase text-muted small mb-1">Usuarios registrados</p>
              <p class="display-6 mb-0"><?= e($totalUsuarios) ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <p class="text-uppercase text-muted small mb-1">Administradores</p>
              <p class="display-6 mb-0"><?= e($totalAdmins) ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <p class="text-uppercase text-muted small mb-1">Cursos cargados</p>
              <p class="display-6 mb-0"><?= e($totalCursos) ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body p-4">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h2 class="h5 mb-0">Usuarios del sistema</h2>
            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-sm btn-outline-dark" href="<?= e(url('admin/index.php')) ?>">Gestionar cursos</a>
              <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('dashboard.php')) ?>">Mi panel</a>
              <a class="btn btn-sm btn-danger" href="<?= e(url('logout.php')) ?>">Cerrar sesión</a>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
              <caption class="px-0">Las contraseñas se almacenan como hash bcrypt y no se muestran.</caption>
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Correo</th>
                  <th scope="col">Rol</th>
                  <th scope="col">Alta</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $item): ?>
                  <tr>
                    <td><?= e($item['id']) ?></td>
                    <td><?= e($item['nombre']) ?></td>
                    <td><?= e($item['email']) ?></td>
                    <td>
                      <span class="badge text-bg-<?= $item['rol'] === 'admin' ? 'dark' : 'success' ?>">
                        <?= e($item['rol']) ?>
                      </span>
                    </td>
                    <td><?= e(date('d/m/Y H:i', strtotime((string) $item['created_at']))) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="alert alert-info mb-0" role="note">
        Esta página está protegida por <code>auth_guard.php</code> con
        <code>define('REQUERIR_ROL', 'admin')</code>. Un usuario con rol <code>usuario</code>
        que intente abrirla es redirigido a su panel.
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
