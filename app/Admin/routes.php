<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    //商品分类管理
    $router->resource('classes', ClassController::class);
    //商品管理
    $router->resource('goods', GoodController::class);
    //轮播图管理
    $router->resource('carousel', CarouselController::class);
    //专题管理
    $router->resource('special', SpecialController::class);
    //用户收货地址管理(仅查看)
    $router->resource('addresses', AddressController::class);

    //学校管理
    $router->resource('School', SchoolController::class);
    // 宿舍管理
    $router->resource('dorm', DormController::class);
    //新闻管理
    $router->resource('News', NewsController::class);

    //配送员服务范围管理
    $router->resource('service-range', ServiceRangeController::class);
    //订单管理
    $router->get('/orders/{id}/receive', 'OrderController@receive');
    $router->get('/orders/{id}/delivery', 'OrderController@delivery');
    $router->resource('orders', OrderController::class);


});
