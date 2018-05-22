<?php




Auth::routes();


Route::middleware('auth')->group(function () {
    Route::get('/table', 'EmployeeController@dataview')->name('employees.datatable');
});

Route::get('/', 'EmployeeController@tree')->name('employees.tree');