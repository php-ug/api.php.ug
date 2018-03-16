<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

/** @var \Zend\Expressive\Application $app */

$app->get(
    '/',
    Phpug\Api\Action\HomePageAction::class,
    'home'
);
$app->get(
    '/api/ping',
    Phpug\Api\Action\PingAction::class,
    'api.ping'
);
//$app->post(
//    '/ug',
//    Phpug\Api\Action\PromoteUgAction::class,
//    'api.ug.promote'
//);
$app->get(
    '/ug',
    Phpug\Api\Action\GetUsergroupListAction::class,
    'api.ug.list'
);
