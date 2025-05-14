<?php

require_once 'src/core/Router.php';
require_once 'src/core/Request.php';

$router = new Router(new Request());

$router->get('/albums', 'AlbumController@getAll');
$router->get('/albums/{id}', 'AlbumController@getById');
$router->get('/albums/{id}/tracks', 'AlbumController@getTracksByAlbumId');
$router->post('/albums', 'AlbumController@add');
$router->put('/albums/{id}', 'AlbumController@put');
$router->delete('/albums/{id}', 'AlbumController@delete');



$router->resolve();

