<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// El módulo independiente es la página de inicio del proyecto CodeIgniter.
$routes->get('/', 'Novedades::index');
$routes->get('novedades', 'Novedades::index');
$routes->get('novedades/(:num)', 'Novedades::show/$1');

// Endpoint JSON adicional del mismo módulo.
$routes->get('api/novedades', 'Novedades::api');
