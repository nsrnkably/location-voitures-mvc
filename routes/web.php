<?php
use App\Routes\Route;

// ---------- Accueil ----------
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

// ---------- Voitures ----------
Route::get('/voitures', 'VoitureController@index');
Route::get('/voiture/show', 'VoitureController@show');
Route::get('/voiture/create', 'VoitureController@create');
Route::post('/voiture/create', 'VoitureController@store');
Route::get('/voiture/edit', 'VoitureController@edit');
Route::post('/voiture/edit', 'VoitureController@update');
Route::post('/voiture/delete', 'VoitureController@delete');

// ---------- Clients ----------
Route::get('/clients', 'ClientController@index');
Route::get('/client/create', 'ClientController@create');
Route::post('/client/create', 'ClientController@store');
Route::get('/client/edit', 'ClientController@edit');
Route::post('/client/edit', 'ClientController@update');
Route::post('/client/delete', 'ClientController@delete');

// ---------- Locations ----------
Route::get('/locations', 'LocationController@index');
Route::get('/location/create', 'LocationController@create');
Route::post('/location/create', 'LocationController@store');
Route::get('/location/edit', 'LocationController@edit');
Route::post('/location/edit', 'LocationController@update');
Route::post('/location/delete', 'LocationController@delete');

Route::dispatch();