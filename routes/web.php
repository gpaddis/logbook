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
Route::get('/logbook/update', 'EntryController@update')->name('logbook.update');
Route::post('/logbook', 'LogbookEntryController@store');

/**
 * Live Counter
 */
Route::get('/logbook/livecounter', 'LiveCounterController@index')->name('livecounter.index');
// Route::post('/logbook/livecounter', 'LiveCounterController@store');
Route::post('/logbook/livecounter/add', 'LiveCounterController@add');

/**
 * Patron Categories
 */
Route::get('/patron-categories', 'PatronCategoryController@index')->name('patron-categories.index');
Route::get('/patron-categories/{patronCategory}', 'PatronCategoryController@show')->name('patron-category.index');
Route::post('/patron-categories', 'PatronCategoryController@store');


/**
 * Application Settings
 */
