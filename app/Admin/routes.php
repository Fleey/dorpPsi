<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as'         => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->get('api/areas/search', 'AreaController@searchAreaInfo');
    $router->get('api/customer/search', 'CustomerController@searchCustomerInfo');
    $router->get('api/product/search', 'ProductController@searchProductInfo');

    $router->resource('products', ProductController::class);
    $router->resource('areas', AreaController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('order_infos', OrderInfoController::class);
    $router->resource('customers', CustomerController::class);
});
