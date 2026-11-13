<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Curso;
use PDO;
use PDOException;

/**
 * Controlador del CRUD de cursos.
 *
 * Recibe la acción desde el front controller `admin/index.php`, valida la
 * solicitud, llama al modelo y devuelve la vista correspondiente.
 */
class CursoController
{
    private Curso $modelo;

    public function __construct(PDO $pdo)
    {
        $this->modelo = new Curso($pdo);
    }

    public function despachar(string $accion): void
    {
        switch ($accion) {
            case 'crear':
                $this->crear();
                break;
            case 'guardar':
                $this->guardar();
                break;
            case 'editar':
                $this->editar();
                break;
            case 'actualizar':
                $this->actualizar();
                break;
            case 'confirmar_eliminar':
                $this->confirmarEliminar();
                break;
            case 'eliminar':
                $this->eliminar();
                break;
            default:
                $this->listar();
        }
    }

    private function listar(): void
    {
        $cursos = $this->modelo->listar();
        $status = (string) ($_GET['status'] ?? '');
        $mensajes = [
            'created' => 'Curso creado correctamente.',
            'updated' => 'Curso actualizado correctamente.',
            'deleted' => 'Curso eliminado correctamente.',
            'notfound' => 'El curso solicitado no existe.',
            'csrf' => 'La sesión del formulario expiró. Volvé a intentarlo.',
        ];

        $this->render('cursos/index', [
            'titulo_pagina' => 'Administración de cursos',
            'cursos' => $cursos,
            'status' => $status,
            'mensajes' => $mensajes,
        ]);
    }

    private function crear(): void
    {
        $this->renderForm('crear', [
            'id' => null,
            'titulo' => '',
            'descripcion' => '',
            'cupos' => '',
        ], '');
    }

    private function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('admin/index.php?accion=crear');
        }

        if (!csrf_valido()) {
            $this->renderForm('crear', $this->datosDelPost(), 'La sesión del formulario expiró. Volvé a intentarlo.');
            return;
        }

        [$datos, $error] = $this->validar($_POST);

        if ($error !== '') {
            $this->renderForm('crear', $datos, $error);
            return;
        }

        try {
            $this->modelo->crear($datos['titulo'], $datos['descripcion'], (int) $datos['cupos']);
            $this->redirigir('admin/index.php?status=created');
        } catch (PDOException $e) {
            error_log('Error al crear curso: ' . $e->getMessage());
            $this->renderForm('crear', $datos, 'No se pudo guardar el curso. Intentá nuevamente.');
        }
    }

    private function editar(): void
    {
        $id = $this->idDesdeGet();

        if ($id === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        $curso = $this->modelo->buscarPorId($id);

        if ($curso === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        $this->renderForm('editar', $curso, '');
    }

    private function actualizar(): void
    {
        $id = $this->idDesdeGet();

        if ($id === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        $curso = $this->modelo->buscarPorId($id);

        if ($curso === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('admin/index.php?accion=editar&id=' . $id);
        }

        if (!csrf_valido()) {
            $this->renderForm('editar', $curso, 'La sesión del formulario expiró. Volvé a intentarlo.');
            return;
        }

        [$datos, $error] = $this->validar($_POST);
        $datos['id'] = $id;

        if ($error !== '') {
            $this->renderForm('editar', $datos, $error);
            return;
        }

        try {
            $this->modelo->actualizar($id, $datos['titulo'], $datos['descripcion'], (int) $datos['cupos']);
            $this->redirigir('admin/index.php?status=updated');
        } catch (PDOException $e) {
            error_log('Error al actualizar curso: ' . $e->getMessage());
            $this->renderForm('editar', $datos, 'No se pudo actualizar el curso. Intentá nuevamente.');
        }
    }

    private function confirmarEliminar(): void
    {
        $id = $this->idDesdeGet();

        if ($id === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        $curso = $this->modelo->buscarPorId($id);

        if ($curso === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        $this->render('cursos/eliminar', [
            'titulo_pagina' => 'Eliminar curso',
            'curso' => $curso,
        ]);
    }

    private function eliminar(): void
    {
        $id = $this->idDesdeGet();

        if ($id === null) {
            $this->redirigir('admin/index.php?status=notfound');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
            $this->redirigir('admin/index.php?status=csrf');
        }

        try {
            $this->modelo->eliminar($id);
            $this->redirigir('admin/index.php?status=deleted');
        } catch (PDOException $e) {
            error_log('Error al eliminar curso: ' . $e->getMessage());
            $this->redirigir('admin/index.php?status=notfound');
        }
    }

    /**
     * @param array<string, mixed> $entrada
     * @return array{0: array<string, mixed>, 1: string}
     */
    private function validar(array $entrada): array
    {
        $datos = $this->datosDelPost($entrada);

        if ($datos['titulo'] === '' || mb_strlen((string) $datos['titulo']) > 150) {
            return [$datos, 'El título es obligatorio y no puede superar los 150 caracteres.'];
        }

        if ($datos['descripcion'] === '') {
            return [$datos, 'La descripción es obligatoria.'];
        }

        $cuposValidados = filter_var($datos['cupos'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($cuposValidados === false) {
            return [$datos, 'Los cupos deben ser un número entero mayor que cero.'];
        }

        $datos['cupos'] = $cuposValidados;

        return [$datos, ''];
    }

    /**
     * @param array<string, mixed> $entrada
     * @return array<string, mixed>
     */
    private function datosDelPost(array $entrada = []): array
    {
        return [
            'titulo' => strip_tags(trim((string) ($entrada['titulo'] ?? ''))),
            'descripcion' => strip_tags(trim((string) ($entrada['descripcion'] ?? ''))),
            'cupos' => trim((string) ($entrada['cupos'] ?? '')),
        ];
    }

    private function idDesdeGet(): ?int
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        return $id === false ? null : $id;
    }

    /**
     * @param array<string, mixed> $datos
     */
    private function renderForm(string $modo, array $datos, string $error): void
    {
        $this->render('cursos/form', [
            'titulo_pagina' => $modo === 'crear' ? 'Agregar curso' : 'Editar curso',
            'modo' => $modo,
            'curso' => $datos,
            'error' => $error,
        ]);
    }

    private function redirigir(string $ruta): void
    {
        redirigir($ruta);
    }

    /**
     * @param array<string, mixed> $datos
     */
    private function render(string $vista, array $datos = []): void
    {
        extract($datos, EXTR_SKIP);
        require __DIR__ . '/../Views/' . $vista . '.php';
    }
}
