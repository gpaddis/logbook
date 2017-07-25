<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** 
 * Visits Logs
 */
Route::get('/visits', 'EntryController@index')->name('visits');
Route::post('/visits', 'EntryController@store');

/**
 * Application Settings
 */
Route::get('/settings/patron-categories', 'PatronCategoryController@index')->name('settings.patron-categories');
Route::post('/settings/patron-categories', 'PatronCategoryController@store');
Route::get('/settings/patron-categories/{patronCategory}', 'PatronCategoryController@show');
