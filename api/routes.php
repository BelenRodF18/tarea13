<?php

$router->get('/estado', 'EstadoController@ver');

// Autos
$router->get('/autos', 'AutosController@listar');
$router->get('/autos/:id', 'AutosController@ver');
$router->post('/autos', 'AutosController@crear');
$router->put('/autos/:id', 'AutosController@actualizar');

// Marcas
$router->get('/marcas', 'AutosController@marcas');
