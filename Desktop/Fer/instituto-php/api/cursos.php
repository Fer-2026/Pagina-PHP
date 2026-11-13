<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

require_once __DIR__ . '/../config/conexion.php';

/**
 * Envía una respuesta JSON y finaliza la ejecución.
 */
function responder_json(int $codigo, array $payload): void
{
    http_response_code($codigo);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Normaliza los tipos para que el JSON devuelva números donde corresponde.
 */
function normalizar_curso(array $curso): array
{
    return [
        'id' => (int) $curso['id'],
        'titulo' => (string) $curso['titulo'],
        'descripcion' => (string) $curso['descripcion'],
        'cupos' => (int) $curso['cupos'],
    ];
}

$metodo = $_SERVER['REQUEST_METHOD'] ?? '';

if ($metodo !== 'GET') {
    header('Allow: GET');
    responder_json(405, [
        'status' => 'error',
        'message' => 'Método no permitido. Este endpoint solo acepta GET.',
    ]);
}

try {
    // Detalle de un curso por ID.
    if (array_key_exists('id', $_GET)) {
        $idParam = $_GET['id'];

        if (!is_string($idParam)) {
            responder_json(400, [
                'status' => 'error',
                'message' => 'El parámetro id debe ser un número entero.',
            ]);
        }

        $id = filter_var($idParam, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($id === false) {
            responder_json(400, [
                'status' => 'error',
                'message' => 'El id debe ser un entero mayor que cero.',
            ]);
        }

        $stmt = $pdo->prepare(
            'SELECT id, titulo, descripcion, cupos
             FROM cursos
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $curso = $stmt->fetch();

        if ($curso === false) {
            responder_json(404, [
                'status' => 'error',
                'message' => 'Curso no encontrado.',
                'id' => $id,
            ]);
        }

        responder_json(200, [
            'status' => 'success',
            'data' => normalizar_curso($curso),
            'count' => 1,
        ]);
    }

    // Búsqueda por título o descripción.
    $qParam = $_GET['q'] ?? '';

    if (!is_string($qParam)) {
        responder_json(400, [
            'status' => 'error',
            'message' => 'El parámetro q debe ser un texto.',
        ]);
    }

    $q = trim($qParam);

    if (mb_strlen($q, 'UTF-8') > 100) {
        responder_json(400, [
            'status' => 'error',
            'message' => 'La búsqueda no puede superar los 100 caracteres.',
        ]);
    }

    if ($q === '') {
        $stmt = $pdo->query(
            'SELECT id, titulo, descripcion, cupos
             FROM cursos
             ORDER BY titulo ASC
             LIMIT 20'
        );
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, titulo, descripcion, cupos
             FROM cursos
             WHERE titulo LIKE :titulo OR descripcion LIKE :descripcion
             ORDER BY titulo ASC
             LIMIT 20'
        );

        $patron = '%' . $q . '%';
        $stmt->execute([
            ':titulo' => $patron,
            ':descripcion' => $patron,
        ]);
    }

    $cursos = array_map('normalizar_curso', $stmt->fetchAll());

    responder_json(200, [
        'status' => 'success',
        'data' => $cursos,
        'count' => count($cursos),
        'query' => $q,
        'limit' => 20,
    ]);
} catch (Throwable $error) {
    error_log('Error en api/cursos.php: ' . $error->getMessage());

    responder_json(500, [
        'status' => 'error',
        'message' => 'No se pudieron obtener los cursos. Intentá nuevamente.',
    ]);
}
