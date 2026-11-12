<?php
declare(strict_types=1);

define('REQUERIR_ROL', 'admin');

require_once __DIR__ . '/../auth_guard.php';
require_once __DIR__ . '/../config/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM cursos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        error_log('Error al eliminar curso: ' . $e->getMessage());
    }
}

redirigir('admin/index.php?status=deleted');
