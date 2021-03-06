<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    // 经销商管理路由
    $router->resource('dealers', 'DealerController');
    $router->resource('dealer/users', 'Dealer\UserController');
    $router->resource('dealer/roles', 'Dealer\RoleController');
    $router->resource('dealer/permissions', 'Dealer\PermissionController');
    $router->resource('dealer/menu', 'Dealer\MenuController');

});
