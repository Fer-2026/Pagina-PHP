<?php
require_once '../config/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$error = '';

// Obtener los datos actuales del curso
try {
    $stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
    $stmt->execute([$id]);
    $curso = $stmt->fetch();

    if (!$curso) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Error al cargar el curso: " . $e->getMessage();
}

// Procesar la actualización al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cupos = filter_var($_POST['cupos'] ?? 0, FILTER_VALIDATE_INT);

    if (!empty($titulo) && !empty($descripcion) && $cupos !== false) {
        try {
            $stmt = $pdo->prepare("UPDATE cursos SET titulo = ?, descripcion = ?, cupos = ? WHERE id = ?");
            $stmt->execute([$titulo, $descripcion, $cupos, $id]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error al actualizar el curso: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, completa todos los campos correctamente.";
    }
}

include '../includes/header.php';
include '../includes/nav.php';
?>

<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Editar Curso</h2>
            <hr>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="editar.php?id=<?= $id ?>" method="POST">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título del Curso</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($curso['titulo']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><<?= htmlspecialchars($curso['descripcion']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="cupos" class="form-label">Cupos</label>
                    <input type="number" class="form-control" id="cupos" name="cupos" value="<?= $curso['cupos'] ?>" required>
                </div>
                <button type="submit" class="btn btn-warning">Actualizar Curso</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</main>

<?php
include '../includes/footer.php';
?>