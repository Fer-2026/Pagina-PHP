<?php
declare(strict_types=1);

define('REQUERIR_ROL', 'admin');

require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../config/conexion.php';

$error = '';
$titulo = '';
$descripcion = '';
$cupos = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valido()) {
        $error = 'La sesión del formulario expiró. Volvé a intentarlo.';
    } else {
        $titulo = trim((string) ($_POST['titulo'] ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
        $cupos = (string) ($_POST['cupos'] ?? '');

        $titulo = strip_tags($titulo);
        $descripcion = strip_tags($descripcion);
        $cuposValidado = filter_var($cupos, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($titulo === '' || mb_strlen($titulo) > 150) {
            $error = 'El título es obligatorio y no puede superar los 150 caracteres.';
        } elseif ($descripcion === '') {
            $error = 'La descripción es obligatoria.';
        } elseif ($cuposValidado === false) {
            $error = 'Los cupos deben ser un número entero mayor que cero.';
        } else {
            try {
                $sql = 'INSERT INTO cursos (titulo, descripcion, cupos)
                        VALUES (:titulo, :descripcion, :cupos)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':titulo' => $titulo,
                    ':descripcion' => $descripcion,
                    ':cupos' => $cuposValidado,
                ]);

                redirigir('admin/index.php?status=created');
            } catch (PDOException $e) {
                error_log('Error al crear curso: ' . $e->getMessage());
                $error = 'No se pudo guardar el curso. Intentá nuevamente.';
            }
        }
    }
}

$titulo_pagina = 'Agregar curso';
require_once __DIR__ . '/../includes/header.php';
?>
<main id="contenido">
  <section class="page-header">
    <div class="container">
      <nav aria-label="Migas de pan">
        <ol class="breadcrumb mb-2">
          <li class="breadcrumb-item"><a href="<?= e(url('admin/index.php')) ?>">Cursos</a></li>
          <li class="breadcrumb-item active" aria-current="page">Agregar</li>
        </ol>
      </nav>
      <h1 class="mb-1">Agregar nuevo curso</h1>
      <p class="mb-0">Completá los datos del curso. La operación requiere rol admin.</p>
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

              <form action="<?= e(url('admin/crear.php')) ?>" method="POST">
                <?= csrf_campo() ?>

                <div class="mb-3">
                  <label for="titulo" class="form-label">Título</label>
                  <input type="text" class="form-control" id="titulo" name="titulo" value="<?= e($titulo) ?>" maxlength="150" required>
                </div>

                <div class="mb-3">
                  <label for="descripcion" class="form-label">Descripción</label>
                  <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= e($descripcion) ?></textarea>
                </div>

                <div class="mb-4">
                  <label for="cupos" class="form-label">Cupos</label>
                  <input type="number" class="form-control" id="cupos" name="cupos" value="<?= e($cupos) ?>" min="1" required>
                </div>

                <button type="submit" class="btn btn-success">Guardar curso</button>
                <a href="<?= e(url('admin/index.php')) ?>" class="btn btn-secondary">Cancelar</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
