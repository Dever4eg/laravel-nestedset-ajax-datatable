<?php



Route::get('/', 'EmployeeController@tree')->name('employees.tree');
Route::get('/table', 'EmployeeController@dataview')->name('employees.datatable');