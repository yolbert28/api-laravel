<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::apiResource('client', ClientController::class);
Route::post('client/addService',[ClientController::class, 'addService'] );
Route::post('client/removeService',[ClientController::class, 'removeService'] );
Route::post('client/removeAllService/{id}',[ClientController::class, 'removeAllServices'] );

Route::apiResource('service', ServiceController::class);
Route::post('service/removeAllClient/{id}',[ServiceController::class, 'removeAllClients'] );
