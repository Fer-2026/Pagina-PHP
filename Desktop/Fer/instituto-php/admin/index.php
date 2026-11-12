<?php
declare(strict_types=1);

define('REQUERIR_ROL', 'admin');

require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../config/conexion.php';

// Consulta segura para leer los registros
$stmt = $pdo->query('SELECT * FROM cursos ORDER BY id DESC');
$registros = $stmt->fetchAll();

$titulo_pagina = 'Administración de cursos';

$mensajes = [
    'created' => 'Curso creado correctamente.',
    'updated' => 'Curso actualizado correctamente.',
    'deleted' => 'Curso eliminado correctamente.',
    'notfound' => 'El curso solicitado no existe.',
];
$status = (string) ($_GET['status'] ?? '');

require_once __DIR__ . '/../includes/header.php';
?>
<main id="contenido">
  <section class="page-header">
    <div class="container">
      <nav aria-label="Migas de pan">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="<?= e(url('index.php')) ?>">Inicio</a></li>
          <li class="breadcrumb-item"><a href="<?= e(url('dashboard.php')) ?>">Mi panel</a></li>
          <li class="breadcrumb-item"><a href="<?= e(url('admin_panel.php')) ?>">Administración</a></li>
          <li class="breadcrumb-item active" aria-current="page">Cursos</li>
        </ol>
      </nav>
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
          <h1 class="mb-1">Cursos</h1>
          <p class="mb-0">CRUD protegido para el rol <strong>admin</strong>.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?= e(url('admin_panel.php')) ?>" class="btn btn-outline-dark">Panel admin</a>
          <a href="<?= e(url('admin/crear.php')) ?>" class="btn btn-primary">Agregar nuevo curso</a>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <?php if (isset($mensajes[$status])): ?>
        <div class="alert alert-<?= $status === 'notfound' ? 'warning' : 'success' ?>" role="alert">
          <?= e($mensajes[$status]) ?>
        </div>
      <?php endif; ?>

      <div class="card">
        <div class="card-body p-4">
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
              <caption class="px-0">Listado de cursos cargados en la base <code>instituto_db</code>.</caption>
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Título</th>
                  <th scope="col">Descripción</th>
                  <th scope="col">Cupos</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($registros as $item): ?>
                  <tr>
                    <td><?= e($item['id']) ?></td>
                    <td><?= e($item['titulo']) ?></td>
                    <td><?= e($item['descripcion']) ?></td>
                    <td><?= e($item['cupos']) ?></td>
                    <td class="text-nowrap">
                      <a href="<?= e(url('admin/editar.php?id=' . (int) $item['id'])) ?>" class="btn btn-sm btn-warning">Editar</a>
                      <a href="<?= e(url('admin/eliminar.php?id=' . (int) $item['id'])) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Confirma eliminar este registro?')">Eliminar</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($registros)): ?>
                  <tr>
                    <td colspan="5" class="text-center py-4">Todavía no hay cursos cargados.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
