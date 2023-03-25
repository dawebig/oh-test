<?php

use App\Enums\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointCalculationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/calculate', [PointCalculationController::class, 'calculate'])->name(RouteNames::CALCULATE);
