<?php

use App\Http\Controllers\Api\championController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/champions', [championController::class, 'index']);

Route::get('/champions/{id}', [championController::class, 'show']);

Route::post('/champions', [championController::class, 'store']);

Route::delete('/champions/{id}', [championController::class, 'destroy']);

Route::put('/champions/{id}', [championController::class, 'update']);