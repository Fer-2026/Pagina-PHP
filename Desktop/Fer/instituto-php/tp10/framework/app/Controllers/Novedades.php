<?php

namespace App\Controllers;

use App\Models\NovedadModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Controlador del módulo independiente "Novedades".
 *
 * Demuestra el uso de rutas, un controlador, un modelo con Query Builder y
 * vistas con el motor de plantillas de CodeIgniter 4.
 */
class Novedades extends BaseController
{
    protected $helpers = ['url', 'form'];

    public function index(): string
    {
        $modelo = new NovedadModel();
        $busquedaParam = $this->request->getGet('q');
        $busqueda = is_string($busquedaParam) ? trim($busquedaParam) : '';

        return view('novedades/index', [
            'title'     => 'Novedades | Instituto 14 de Abril',
            'busqueda'  => $busqueda,
            'novedades' => $modelo->buscar($busqueda),
        ]);
    }

    public function show($id = null): string
    {
        $modelo  = new NovedadModel();
        $novedad = $modelo->find((int) $id);

        if ($novedad === null || (int) $novedad['publicado'] !== 1) {
            throw new PageNotFoundException('La novedad solicitada no existe.');
        }

        return view('novedades/show', [
            'title'    => $novedad['titulo'] . ' | Novedades',
            'novedad'  => $novedad,
        ]);
    }

    public function api()
    {
        $modelo    = new NovedadModel();
        $novedades = $modelo->publicadas();

        $datos = array_map(static function (array $novedad): array {
            return [
                'id'      => (int) $novedad['id'],
                'titulo'  => $novedad['titulo'],
                'resumen' => $novedad['resumen'],
                'fecha'   => $novedad['fecha'],
                'url'     => site_url('novedades/' . $novedad['id']),
            ];
        }, $novedades);

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($datos),
            'data'   => $datos,
        ]);
    }
}
