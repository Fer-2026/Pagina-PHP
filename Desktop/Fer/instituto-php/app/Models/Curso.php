<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * Modelo del módulo de cursos.
 *
 * Toda la interacción con la tabla `cursos` queda centralizada acá.
 * Las consultas usan PDO con sentencias preparadas.
 */
class Curso
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listar(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM cursos ORDER BY id DESC');

        return $stmt->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM cursos WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $curso = $stmt->fetch();

        return $curso === false ? null : $curso;
    }

    public function crear(string $titulo, string $descripcion, int $cupos): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO cursos (titulo, descripcion, cupos)
             VALUES (:titulo, :descripcion, :cupos)'
        );

        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':cupos' => $cupos,
        ]);
    }

    public function actualizar(int $id, string $titulo, string $descripcion, int $cupos): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE cursos
             SET titulo = :titulo, descripcion = :descripcion, cupos = :cupos
             WHERE id = :id'
        );

        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':cupos' => $cupos,
            ':id' => $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM cursos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
