<?php

require_once 'src/core/Router.php';
require_once 'src/core/Request.php';

$router = new Router(new Request());

$router->get('/albums', 'AlbumController@getAll');
$router->post('/albums', 'AlbumController@store');

// Add more routes here...

$router->resolve();

