<?php

Route::get('/', function () {
    return view('welcome');
});

/**
 * Authentication Routes
 */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

/**
 * Registration Routes
 */
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('register-admin', 'Auth\RegisterController@showAdminRegistrationForm')->name('register-admin');
Route::post('register-admin', 'Auth\RegisterController@registerAdmin');

/**
 * Password Reset Routes
 */
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'HomeController@index')->name('home');

/**
 * Logbook & Logbook Form
 */
Route::get('/logbook', 'LogbookEntryController@index')->name('logbook.index');
Route::get('/logbook/day', function () {
    return view('logbook.tabs.day');
})->name('logbook.day');
Route::get('/logbook/year', 'LogbookEntryController@browseYear')->name('logbook.year');

Route::get('/logbook/update', 'LogbookEntryController@update')->name('logbook.update');
Route::post('/logbook', 'LogbookEntryController@store')->name('logbook.store');

/**
 * Live Counter
 */
Route::get('/logbook/livecounter', 'LiveCounterController@index')->name('livecounter.index');
Route::get('/logbook/livecounter/show', 'LiveCounterController@show')->name('livecounter.show');
Route::post('/logbook/livecounter/add', 'LiveCounterController@add')->name('livecounter.add');
Route::post('/logbook/livecounter/subtract', 'LiveCounterController@subtract')->name('livecounter.subtract');

/**
 * Patron Categories
 */
Route::middleware('permission:manage patron categories')->group(function () {
    Route::get('/patron-categories/create', 'PatronCategoryController@create')->name('patron-categories.create');
    Route::post('/patron-categories', 'PatronCategoryController@store')->name('patron-categories.store');
    Route::get('/patron-categories/{category}/edit', 'PatronCategoryController@edit')->name('patron-categories.edit');
    Route::patch('/patron-categories/{category}', 'PatronCategoryController@update')->name('patron-categories.update');
});
Route::get('/patron-categories', 'PatronCategoryController@index')->name('patron-categories.index');
Route::get('/patron-categories/{category}', 'PatronCategoryController@show')->name('patron-categories.show');

/**
 * Users Management
 */
Route::middleware('permission:manage users')->group(function () {
    Route::get('/users', 'UserController@index')->name('users.index');
});

/**
 * Application Settings
 */
