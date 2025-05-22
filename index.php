<?php

// CORE
require_once __DIR__ . '/src/core/Router.php';
require_once __DIR__ . '/src/core/Request.php';
require_once __DIR__ . '/src/core/Logger.php';

// CONTROLLER
require_once __DIR__ . '/src/controller/DefaultController.php';
require_once __DIR__ . '/src/controller/ArtistController.php';
require_once __DIR__ . '/src/controller/AlbumController.php';
require_once __DIR__ . '/src/controller/TrackController.php';
require_once __DIR__ . '/src/controller/MediaTypeController.php';
require_once __DIR__ . '/src/controller/GenreController.php';
require_once __DIR__ . '/src/controller/PlaylistController.php';
require_once __DIR__ . '/src/controller/DocsController.php';

// DB
require_once __DIR__ . '/src/db/DBCredentials.php';
require_once __DIR__ . '/src/db/Database.php';

// MODEL
require_once __DIR__ . '/src/model/DefaultModel.php';
require_once __DIR__ . '/src/model/Artist.php';
require_once __DIR__ . '/src/model/Album.php';
require_once __DIR__ . '/src/model/Track.php';
require_once __DIR__ . '/src/model/MediaType.php';
require_once __DIR__ . '/src/model/Genre.php';
require_once __DIR__ . '/src/model/Playlist.php';


$router = new Router(new Request());

// ARTISTS
$router->get('/artists', 'ArtistController@getAll');
$router->get('/artists/{id}', 'ArtistController@getById');
$router->get('/artists/{id}/albums', 'ArtistController@getAlbumsByArtistId');
$router->post('/artists', 'ArtistController@add');
$router->delete('/artists/{id}', 'ArtistController@delete');

// ALBUMS
$router->get('/albums', 'AlbumController@getAll');
$router->get('/albums/{id}', 'AlbumController@getById');
$router->get('/albums/{id}/tracks', 'AlbumController@getTracksByAlbumId');
$router->post('/albums', 'AlbumController@add');
$router->put('/albums/{id}', 'AlbumController@put');
$router->delete('/albums/{id}', 'AlbumController@delete');

// TRACKS
$router->get('/tracks', 'TrackController@search');
$router->get('/tracks/{id}', 'TrackController@getById');
$router->get('/tracks', 'TrackController@getByComposer');
$router->post('/tracks', 'TrackController@add');
$router->put('/tracks/{id}', 'TrackController@put');
$router->delete('/tracks/{id}', 'TrackController@delete');

// MEDIA TYPES
$router->get('/media_types', 'MediaTypeController@getAll');

// GENRES
$router->get('/genres', 'GenreController@getAll');

// PLAYLISTS
$router->get('/playlists', 'PlaylistController@getAll');
$router->get('/playlists/{id}', 'PlaylistController@getById');
$router->post('/playlists', 'PlaylistController@add');
$router->post('/playlists/{id}/tracks', 'PlaylistController@addTrack');
$router->delete('/playlists/{playlistId}/tracks/{trackId}', 'PlaylistController@deleteTrack');
$router->delete('/playlists/{id}', 'PlaylistController@delete');

// DOCS
$router->get('/docs', 'DocsController@getDocs');

$router->resolve();

