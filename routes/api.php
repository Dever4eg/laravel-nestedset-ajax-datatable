<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('employees/get-tree', 'EmployeeController@LazyLoadTree')->name('api.employees.getTree');

Route::middleware('auth:api')->group(function () {
    Route::get('employees/get-data', 'EmployeeController@getData')->name('api.employees.getData');
    Route::delete('employees/destroy', 'EmployeeController@destroy')->name('api.employees.destroy');
    Route::post('employees/store', 'EmployeeController@store')->name('api.employees.store');
});