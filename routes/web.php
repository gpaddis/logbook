<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** 
 * Logbook & Logbook Entries
 * 
 * TODO: the /logbook route should actually link to the @index route. Change that.
 */
Route::get('/logbook', 'EntryController@create')->name('logbook');
Route::post('/logbook', 'EntryController@store');

/**
 * Application Settings
 */
Route::get('/settings/patron-categories', 'PatronCategoryController@index')->name('settings.patron-categories');
Route::post('/settings/patron-categories', 'PatronCategoryController@store');
Route::get('/settings/patron-categories/{patronCategory}', 'PatronCategoryController@show');
