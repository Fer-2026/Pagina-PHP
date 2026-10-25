<?php
require_once '../config/conexion.php';

// Consulta segura para leer los registros
$stmt = $pdo->query("SELECT * FROM cursos ORDER BY id DESC");
$registros = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Cursos - Instituto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2 class="mb-4">Panel de Administración - Cursos</h2>
    <a href="crear.php" class="btn btn-primary mb-3">Agregar Nuevo Curso</a>
    <a href="../index.php" class="btn btn-secondary mb-3">Volver al Sitio</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Cupos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['id']) ?></td>
                <td><?= htmlspecialchars($item['titulo']) ?></td>
                <td><?= htmlspecialchars($item['descripcion']) ?></td>
                <td><?= htmlspecialchars($item['cupos']) ?></td>
                <td>
                    <a href="editar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="eliminar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Confirma eliminar este registro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>