<?php
$mensaje = $mensajes[$status] ?? null;
$tipoAlerta = in_array($status, ['notfound', 'csrf'], true) ? 'warning' : 'success';
require_once dirname(__DIR__, 3) . '/includes/header.php';
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
          <div class="d-flex flex-wrap align-items-center gap-2">
            <h1 class="mb-1">Cursos</h1>
            <span class="badge text-bg-primary">MVC nativo</span>
          </div>
          <p class="mb-0">CRUD protegido para el rol <strong>admin</strong>, separado en modelo, vista y controlador.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="<?= e(url('admin_panel.php')) ?>" class="btn btn-outline-dark">Panel admin</a>
          <a href="<?= e(url('admin/index.php?accion=crear')) ?>" class="btn btn-primary">Agregar nuevo curso</a>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <?php if ($mensaje !== null): ?>
        <div class="alert alert-<?= e($tipoAlerta) ?>" role="alert">
          <?= e($mensaje) ?>
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
                <?php foreach ($cursos as $item): ?>
                  <tr>
                    <td><?= e($item['id']) ?></td>
                    <td><?= e($item['titulo']) ?></td>
                    <td><?= e($item['descripcion']) ?></td>
                    <td><?= e($item['cupos']) ?></td>
                    <td class="text-nowrap">
                      <a href="<?= e(url('admin/index.php?accion=editar&id=' . (int) $item['id'])) ?>" class="btn btn-sm btn-warning">Editar</a>
                      <form action="<?= e(url('admin/index.php?accion=eliminar&id=' . (int) $item['id'])) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Confirma eliminar este registro?')">
                        <?= csrf_campo() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($cursos)): ?>
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
<?php require_once dirname(__DIR__, 3) . '/includes/footer.php'; ?>
