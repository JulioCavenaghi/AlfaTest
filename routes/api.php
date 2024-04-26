<?php

use App\Http\Controllers\EstrategiaWMSController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/estrategiaWMS', [EstrategiaWMSController::class, 'postEstrategiaWMS']);
Route::get('/estrategiaWMS/{cdEstrategia}/{dsHora}/{dsMinuto}/prioridade', [EstrategiaWMSController::class, 'getEstrategiaWMS']);
