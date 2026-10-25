<?php
require_once '../config/conexion.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $cupos = $_POST['cupos'] ?? 0;

    if (!empty($titulo) && !empty($descripcion)) {
        // Consulta Preparada obligatoria (Anti SQL Injection)[cite: 8, 10]
        $sql = "INSERT INTO cursos (titulo, descripcion, cupos) VALUES (:titulo, :descripcion, :cupos)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':cupos' => $cupos
        ]);

        header('Location: index.php?status=created');
        exit;
    } else {
        $error = "Por favor, completa todos los campos obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2>Agregar Nuevo Curso</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form action="crear.php" method="POST" class="needs-validation">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="cupos" class="form-label">Cupos</label>
            <input type="number" class="form-control" id="cupos" name="cupos" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Curso</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>