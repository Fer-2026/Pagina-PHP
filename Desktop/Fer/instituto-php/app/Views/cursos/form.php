<?php
$esEdicion = $modo === 'editar';
$accionFormulario = $esEdicion
    ? url('admin/index.php?accion=actualizar&id=' . (int) $curso['id'])
    : url('admin/index.php?accion=guardar');
require_once dirname(__DIR__, 3) . '/includes/header.php';
?>
<main id="contenido">
  <section class="page-header">
    <div class="container">
      <nav aria-label="Migas de pan">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="<?= e(url('admin/index.php')) ?>">Cursos</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?= $esEdicion ? 'Editar' : 'Agregar' ?></li>
        </ol>
      </nav>
      <h1 class="mb-1"><?= $esEdicion ? 'Editar curso' : 'Agregar nuevo curso' ?></h1>
      <p class="mb-0">
        <?= $esEdicion ? 'Modificá los datos y guardá los cambios.' : 'Completá los datos del curso. La operación requiere rol admin.' ?>
      </p>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body p-4 p-md-5">
              <?php if ($error !== ''): ?>
                <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
              <?php endif; ?>

              <form action="<?= e($accionFormulario) ?>" method="POST">
                <?= csrf_campo() ?>

                <div class="mb-3">
                  <label for="titulo" class="form-label">Título</label>
                  <input type="text" class="form-control" id="titulo" name="titulo" value="<?= e($curso['titulo'] ?? '') ?>" maxlength="150" required>
                </div>

                <div class="mb-3">
                  <label for="descripcion" class="form-label">Descripción</label>
                  <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= e($curso['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                  <label for="cupos" class="form-label">Cupos</label>
                  <input type="number" class="form-control" id="cupos" name="cupos" value="<?= e($curso['cupos'] ?? '') ?>" min="1" required>
                </div>

                <button type="submit" class="btn <?= $esEdicion ? 'btn-warning' : 'btn-success' ?>">
                  <?= $esEdicion ? 'Actualizar curso' : 'Guardar curso' ?>
                </button>
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
