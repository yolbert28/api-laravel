<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('client', ClientController::class);
Route::post('client/addService',[ClientController::class, 'addService'] );
Route::post('client/removeService',[ClientController::class, 'removeService'] );
Route::post('client/removeService/{all}',[ClientController::class, 'removeService'] );
Route::delete('client/{client}/{force}',[ClientController::class, 'destroy'] );

Route::apiResource('service', ServiceController::class);
Route::delete('service/{service}/{force}',[ServiceController::class, 'destroy'] );
