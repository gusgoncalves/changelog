<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Changelog::index');

$routes->get('changelog', 'Changelog::index');
$routes->post('changelog/criar', 'Changelog::criarVersao');

$routes->get('changelog/editar/(:num)', 'Changelog::editar/$1');
$routes->get('changelog/remover/(:num)', 'Changelog::remover/$1');
$routes->post('changelog/add-item', 'Changelog::adicionarItem');

$routes->get('changelog/sql/(:num)', 'Changelog::gerarSql/$1');

$routes->post('changelog/ordenar', 'Changelog::ordenar');
