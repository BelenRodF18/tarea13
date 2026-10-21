<?php

$router->get('/estado', 'EstadoController@ver');

// Autos
$router->get('/autos', 'AutosController@listar');          // listado principal
$router->get('/autos/:id', 'AutosController@ver');         // detalle de un auto
$router->post('/autos', 'AutosController@crear');          // agregar auto
$router->put('/autos/:id', 'AutosController@actualizar');  // modificar auto

// Marcas (para el <select> de los formularios)
$router->get('/marcas', 'AutosController@marcas');
