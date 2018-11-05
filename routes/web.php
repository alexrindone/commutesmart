<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LeaderboardController@individual')->middleware(['auth']);

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/', 'ProfileController@myProfile');
    Route::put('/user-update', 'ProfileController@userUpdate');
});

Route::prefix('trips')->middleware(['auth'])->group(function () {
    Route::get('/', 'TripsController@trips');
    Route::post('/add', 'TripsController@addTrip');
    Route::put('{id}/edit-trip', 'TripsController@editTrip');
    Route::delete('{id}/delete-trip', 'TripsController@deleteTrip');
});

Route::prefix('leaderboard')->group(function () {
    Route::get('/', 'LeaderboardController@individual');
    Route::get('/companies', 'LeaderboardController@companiesLeaderboard');
});

Route::prefix('admin')->middleware(['auth', 'admin_user'])->group(function () {
    Route::get('/users-export', 'AdminController@exportUserNameAddress');
    Route::get('/companies', 'AdminController@companies');
    Route::post('/add-company', 'AdminController@addCompany');
    Route::put('{id}/edit-company', 'AdminController@editCompany');
    Route::delete('{id}/delete-company', 'AdminController@deleteCompany');

    Route::get('/challenges', 'AdminController@challenges');
    Route::post('/add-challenge', 'AdminController@addChallenge');
    Route::put('{id}/edit-challenge', 'AdminController@editChallenge');
    Route::delete('{id}/delete-challenge', 'AdminController@deleteChallenge');
});
