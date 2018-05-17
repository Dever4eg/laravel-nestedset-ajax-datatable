<?php



Route::get('/', 'EmployeeController@tree');
Route::get('/table', 'EmployeeController@dataview');