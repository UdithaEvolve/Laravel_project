<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/supplier_home', 'SupplierController@HomePage');
Route::get('/supplier_new', 'SupplierController@New');
Route::get('/suplmas_home_data', 'SupplierController@HomeData');
Route::post('/supplier_save', 'SupplierController@Save');
