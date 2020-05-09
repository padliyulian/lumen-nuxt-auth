<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->get('/key', function() {
//     return \Illuminate\Support\Str::random(32);
// });

Route::group(['prefix' => 'api/v1'], function(){
    Route::post('/register','AuthController@register');
    Route::post('/login', 'AuthController@login');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'api/v1'], function(){
    Route::post('/logout', 'AuthController@logout');
    Route::get('/login/user', 'UserController@info');
    Route::get('/login/user-role-name', 'UserController@roleName');

    Route::group(['namespace' => 'SPPD'], function(){
        // HRD
        // Route::get('/user-karyawan', 'HrdController@index');
        // Route::get('/karyawan/info', 'HrdController@karyawan');

        // LIST
        Route::get('/list/karyawan', 'ListController@karyawan');

        // CRUD SPPD
        Route::get('/user/sppd', 'SppdController@sppd');
        Route::post('/user/sppd', 'SppdController@sppdStore');
        Route::patch('/user/sppd/{sppd}', 'SppdController@sppdUpdate');
        Route::delete('/user/sppd/{sppd}', 'SppdController@sppdDestroy');
        Route::get('/user/sppd/{sppd}', 'SppdController@sppdPrint');

        // HRD
        // diketahui
        Route::get('/hrd/sppd/unapprove', 'HrdController@sppdUnApprove');

        // Karyawan
        Route::get('/karyawan/sppd/pemberi', 'KaryawanController@sppdPemberi');
        Route::get('/karyawan/sppd/diketahui', 'KaryawanController@sppdDiketahui');
        Route::get('/karyawan/sppd/selesai', 'KaryawanController@sppdSelesai');

        Route::patch('/karyawan/sppd/pemberi/{sppd}', 'KaryawanController@sppdPemberiApprove');
        Route::patch('/karyawan/sppd/diketahui/{sppd}', 'KaryawanController@sppdDiketahuiApprove');

        // update profil
        Route::get('/karyawan/user', 'KaryawanController@userShow');
        Route::patch('/karyawan/user/{user}', 'KaryawanController@userUpdate');
    });

    Route::group(['namespace' => 'chat'], function(){
        // user list
        Route::get('/karyawan/chat/user-list', 'ChatController@userList');

        // chat with
        Route::get('/karyawan/chat/user/{user}', 'ChatController@chatWith');
        Route::post('/karyawan/chat/user', 'ChatController@chatSend');
    });

    // Route::get('/user', 'UserController@index');
    Route::group(['middleware' => ['role:admin']], function () {

        // user
        Route::get('/user', 'UserController@index');
        Route::post('/user', 'UserController@store');
        Route::patch('/user/{user}', 'UserController@update');
        Route::delete('/user/{user}', 'UserController@destroy');

        // role
        Route::get('/role-list', 'RoleController@list');
        Route::get('/role', 'RoleController@index');
        Route::post('/role', 'RoleController@store');
        Route::patch('/role/{role}', 'RoleController@update');
        Route::delete('/role/{role}', 'RoleController@destroy');

        // role has permission
        Route::get('/roles-list', 'RolePermissionController@roleList');
        Route::get('/permissions-list', 'RolePermissionController@permissionList');
        Route::get('/role-permissions/{role}', 'RolePermissionController@rolePermission');
        Route::patch('/role-permissions/{role}', 'RolePermissionController@setRolePermission');

        // karyawan
        Route::get('/karyawan', 'KaryawanController@index');

        // sppd
        Route::get('/sppd', 'SppdController@index');
    });
});    

// Route::group(['middleware' => ['auth:api','permission:read users'], 'prefix' => 'api/v1'], function () {
// 	Route::get('/user', 'UserController@index');
// });
