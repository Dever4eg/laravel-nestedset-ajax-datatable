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
Route::get('employees/get-data', 'EmployeeController@GetData')->name('api.employees.getData');
Route::get('employees/get-one', 'EmployeeController@show')->name('api.employees.show');
Route::delete('employees/destroy', 'EmployeeController@destroy')->name('api.employees.destroy');
Route::post('employees/store', 'EmployeeController@store')->name('api.employees.store');
Route::post('employees/update-chief', 'EmployeeController@UpdateChief')->name('api.employees.updateChief');