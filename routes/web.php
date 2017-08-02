<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** 
 * Logbook & Logbook Entries
 */
Route::get('/logbook', 'EntryController@index')->name('logbook.index');
Route::get('/logbook/update', 'EntryController@create')->name('logbook.create');
Route::post('/logbook', 'EntryController@store');

/**
 * Live Counter
 */
Route::get('/logbook/livecounter', 'LiveCounterController@index')->name('livecounter.index');
Route::get('/logbook/store', 'LiveCounterController@store');

/**
 * Application Settings
 */
Route::get('/settings/patron-categories', 'PatronCategoryController@index')->name('settings.patron-categories.index');
Route::post('/settings/patron-categories', 'PatronCategoryController@store');
Route::get('/settings/patron-categories/{patronCategory}', 'PatronCategoryController@show');
