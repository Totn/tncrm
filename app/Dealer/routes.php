<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('dealer.route.prefix'),
    'namespace'  => config('dealer.route.namespace'),
    'middleware' => config('dealer.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('auth/users', 'Auth\UserController');

});
