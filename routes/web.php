<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\UserController;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('login', ['uses' => 'LoginController@index']);

$router->post('login', 'AuthController@login');

$router->group( ['prefix' => 'api', 'middleware' => 'admin'] , function() use($router) {
    //User
    $router->post('register', 'AuthController@register');
    $router->get('user', 'UserController@index');
    $router->get('user-current-month/{id}', 'UserController@getCurrentMonth');
    $router->get('user-today-task/{id}', 'UserController@getTodayTask');
    $router->get('user-count-today-task/{id}', 'UserController@countTodayTask');
    $router->get('user-task-by-month/{id}/{id_pk}', 'UserController@userTaskByMonth');
    $router->get('user-total-by-month/{id}/{id_pk}', 'UserController@getSelectedTotalJam');
    $router->get('count-karyawan',  'UserController@countKaryawan');
    $router->get('user/{id}', 'UserController@show');
    $router->get('search-user/{nama}', 'UserController@search');
    $router->put('user/{id}', 'UserController@update');
    $router->delete('user/{id}', 'UserController@destroy');
    $router->post('upload-user/{id}', 'UserController@upload');

    //Pekerjaan
    $router->get('pekerjaan',  'PekerjaanController@index');
    $router->get('pekerjaan/detail/{id}', 'PekerjaanController@detail');
    $router->get('pekerjaan/{idUser}',  'PekerjaanController@show');
    $router->get('selected-pekerjaan/{id}',  'PekerjaanController@getSelectedPekerjaan');
    $router->get('pekerjaan-current-month', 'PekerjaanController@currentMonth');
    $router->get('search-pekerjaan/{bulan}',  'PekerjaanController@search');
    $router->post('pekerjaan',  'PekerjaanController@create');
    $router->put('pekerjaan/{id}',  'PekerjaanController@update');
    $router->delete('pekerjaan/{id}',  'PekerjaanController@destroy');

    $router->get('detailpk',  'DetailPekerjaanController@index');
    $router->get('detailpk-show/{id}',  'DetailPekerjaanController@show');
    $router->get('search-detailpk/{namaPekerjaan}',  'DetailPekerjaanController@search');
    $router->post('detailpk-create/{id}',  'DetailPekerjaanController@create');
    $router->put('detailpk-update/{id}',  'DetailPekerjaanController@update');
    $router->delete('detailpk-delete/{id}',  'DetailPekerjaanController@destroy');

});

$router->group( ['prefix' => 'api', 'middleware' => 'user'] , function() use($router) {
    $router->post('upload-user/{id}', 'UserController@upload');
    $router->get('user-current-month/{id}', 'UserController@getCurrentMonth');
    $router->get('user-count-today-task/{id}', 'UserController@countTodayTask');
    $router->get('user-today-task/{id}', 'UserController@getTodayTask');
    $router->get('user-task-by-month/{id}/{id_pk}', 'UserController@userTaskByMonth');
    $router->get('user-total-by-month/{id}/{id_pk}', 'UserController@getSelectedTotalJam');
    $router->get('user/{id}', 'UserController@show');
    $router->put('user/{id}', 'UserController@update');


    //Pekerjaan
    $router->get('pekerjaan',  'PekerjaanController@index');
    $router->get('pekerjaan/detail/{id}', 'PekerjaanController@detail');
    $router->get('pekerjaan/{idUser}',  'PekerjaanController@show');
    $router->get('pekerjaan-current-month', 'PekerjaanController@currentMonth');
    $router->get('selected-pekerjaan/{id}',  'PekerjaanController@getSelectedPekerjaan');
    $router->get('search-pekerjaan/{bulan}',  'PekerjaanController@search');
    $router->post('pekerjaan',  'PekerjaanController@create');
    $router->put('pekerjaan/{id}',  'PekerjaanController@update');
    $router->delete('pekerjaan/{id}',  'PekerjaanController@destroy');

    //Detail Pekerjaan
    $router->get('detailpk',  'DetailPekerjaanController@index');
    $router->get('detailpk-show/{id}',  'DetailPekerjaanController@show');
    $router->get('search-detailpk/{namaPekerjaan}',  'DetailPekerjaanController@search');
    $router->post('detailpk-create/{id}',  'DetailPekerjaanController@create');
    $router->put('detailpk-update/{id}',  'DetailPekerjaanController@update');
    $router->delete('detailpk-delete/{id}',  'DetailPekerjaanController@destroy');
});


