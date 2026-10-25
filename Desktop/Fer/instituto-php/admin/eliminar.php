<?php
require_once '../config/conexion.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cursos WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Opcional: manejar error de borrado
    }
}

header('Location: index.php');
exit;