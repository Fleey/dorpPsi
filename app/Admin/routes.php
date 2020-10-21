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

    $router->get('/product', 'ProductController@getListPage')->name('product.getList');
    $router->get('/product/create', 'ProductController@createProductPage')->name('product.createProductPage');
    $router->post('/product', 'ProductController@createData')->name('product.createData');
    $router->put('/product/{productId}', 'ProductController@updateField')->name('product.updateField');

});
