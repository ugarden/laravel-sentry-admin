<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('doc', 'DocController@index');

Route::get('', function () {
    return redirect('admin');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth.admin']], function () {
    $rc = [
        'user' => 'UserController',
        'operation' => 'OperationController',
        'group' => 'GroupController',
        'util' => 'UtilController',
        '' => 'IndexController',//注意这里必须放在最后位置，不然会出现路由错误。
    ];
    foreach ($rc as $k => $v)
        Route::controller($k, $v);
});

Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => ['auth.api']], function () {
    $rc = [
    ];
    foreach ($rc as $k => $v)
        Route::controller($k, $v);
});