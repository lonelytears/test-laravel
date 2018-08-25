<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::registerAuthRoutes();

/* @var \Illuminate\Routing\Router $router */
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

//    $router->get('/', 'HomeController@index');
    $router->resource('/', 'LookController');
//    $router->resource('/test', 'TestController');
    $router->resource('/looks', 'LookController');
    $router->resource('/auth/users', 'UserController');
    //  $router->get('/test', 'TestController');
    //$router->get('/test/create', 'TestController@create');

});
