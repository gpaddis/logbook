<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

/**
 * Logbook & Logbook Form
 */
Route::get('/logbook', 'LogbookEntryController@index')->name('logbook.index');
Route::get('/logbook/day', function () {
    return view('logbook.tabs.day');
})->name('logbook.day');
Route::get('/logbook/week', function () {
    return view('logbook.tabs.week');
})->name('logbook.week');

Route::get('/logbook/update', 'LogbookEntryController@update')->name('logbook.update');
Route::post('/logbook', 'LogbookEntryController@store')->name('logbook.store');

/**
 * Live Counter
 */
Route::get('/logbook/livecounter', 'LiveCounterController@index')->name('livecounter.index');
Route::post('/logbook/livecounter/add', 'LiveCounterController@add')->name('livecounter.add');
Route::post('/logbook/livecounter/subtract', 'LiveCounterController@subtract')->name('livecounter.subtract');

/**
 * Patron Categories
 */
Route::get('/patron-categories', 'PatronCategoryController@index')->name('patron-categories.index');
Route::get('/patron-categories/{patronCategory}', 'PatronCategoryController@show')->name('patron-category.show');
Route::post('/patron-categories', 'PatronCategoryController@store')->name('patron-category.store');


/**
 * Application Settings
 */
