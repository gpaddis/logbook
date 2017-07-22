<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/visits', 'VisitsLogController@index');
Route::post('/visits', 'VisitsLogController@store');
