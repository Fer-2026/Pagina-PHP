<?php
require_once dirname(__DIR__, 3) . '/includes/header.php';
?>
<main id="contenido">
  <section class="page-header">
    <div class="container">
      <nav aria-label="Migas de pan">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="<?= e(url('admin/index.php')) ?>">Cursos</a></li>
          <li class="breadcrumb-item active" aria-current="page">Eliminar</li>
        </ol>
      </nav>
      <h1 class="mb-1">Eliminar curso</h1>
      <p class="mb-0">Revisá el registro antes de confirmar la operación.</p>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card border-danger">
            <div class="card-body p-4 p-md-5">
              <h2 class="h4"><?= e($curso['titulo']) ?></h2>
              <p class="mb-3"><?= e($curso['descripcion']) ?></p>
              <p class="mb-4"><strong>Cupos:</strong> <?= e($curso['cupos']) ?></p>

              <form action="<?= e(url('admin/index.php?accion=eliminar&id=' . (int) $curso['id'])) ?>" method="post">
                <?= csrf_campo() ?>
                <button type="submit" class="btn btn-danger">Confirmar eliminación</button>
                <a href="<?= e(url('admin/index.php')) ?>" class="btn btn-secondary">Cancelar</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once dirname(__DIR__, 3) . '/includes/footer.php'; ?>
