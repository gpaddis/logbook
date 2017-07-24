<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** 
 * Visits Logs
 */
Route::get('/visits', 'VisitsLogController@index')->name('visits');
Route::post('/visits', 'VisitsLogController@store');

/**
 * Application Settings
 */
Route::get('/settings/patron-categories', 'PatronCategoryController@index')->name('settings.patron-categories.index');
