<?php

use Illuminate\Support\Facades\Route;
use Shtatva\DynamicCrud\Controllers\ModuleController;

Route::namespace('Shtatva\DynamicCrud\Controllers')->group(function () {
    // Add more routes within the same namespace here
					
    Route::resource('/table', 'TableController');
});

Route::post('/module/{table}', [ModuleController::class, 'store']);
Route::get('/module', [ModuleController::class, 'listingModules']);
Route::delete('/module/{table}', [ModuleController::class, 'delete']);

