<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modelo del módulo independiente "Novedades".
 *
 * Usa el Query Builder de CodeIgniter 4 y la tabla `novedades`.
 */
class NovedadModel extends Model
{
    protected $table         = 'novedades';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['titulo', 'resumen', 'contenido', 'fecha', 'publicado'];
    protected $useTimestamps = false;

    /**
     * Devuelve todas las novedades publicadas, de la más reciente a la más antigua.
     *
     * @return array<int, array<string, mixed>>
     */
    public function publicadas(): array
    {
        return $this->buscar('');
    }

    /**
     * Busca novedades publicadas por título, resumen o contenido.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buscar(string $termino): array
    {
        $builder = $this->builder();
        $builder->where('publicado', 1);

        if ($termino !== '') {
            $builder
                ->groupStart()
                ->like('titulo', $termino)
                ->orLike('resumen', $termino)
                ->orLike('contenido', $termino)
                ->groupEnd();
        }

        return $builder
            ->orderBy('fecha', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
    }
}
