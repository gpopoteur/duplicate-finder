<?php

// Home
Route::get('/', 'CustomersController@index');

// Customers Routes
Route::get('/customers', ['as' => 'customers.index' , 'uses' => 'CustomersController@index']);
Route::get('/customers/create', ['as' => 'customers.new' , 'uses' => 'CustomersController@new']);
Route::get('/customers/{customer}', ['as' => 'customers.show' , 'uses' => 'CustomersController@show']);
Route::post('/customers', ['as' => 'customers.store' , 'uses' => 'CustomersController@store']);
Route::get('/customers/{customer}/edit', ['as' => 'customers.edit' , 'uses' => 'CustomersController@edit']);
Route::patch('/customers/{customer}', ['as' => 'customers.update' , 'uses' => 'CustomersController@update']);
Route::delete('/customers/{customer}', ['as' => 'customers.delete' , 'uses' => 'CustomersController@delete']);

// Find Customer Possible Duplicates
Route::get('/customers/{customer}/duplicates', ['as' => 'customers.duplicates' , 'uses' => 'CustomerDuplicatesController@show']);
