<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('/auth')->group(function () {

    Route::post('/login', 'ControllerAuth@login');

    Route::post('/logout', 'ControllerAuth@logout');

});

Route::prefix('/resource-access')->group(function () {

    Route::get('', 'CResourceAccess@index');

    Route::get('{id}', 'CResourceAccess@show');

    Route::post('create', 'CResourceAccess@store');

});

Route::prefix('/users')->group(function () {
   Route::get('find/{name}', [App\Http\Controllers\AuthLdap::class, 'find']);
});
