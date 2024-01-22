<?php

use App\Http\Controllers\AmoCRMController;
use App\Http\Middleware\oAuthMiddlewar;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ AmoCRMController::class, "sendingApplication"]);
        // ->middleware(oAuthMiddlewar::class);
Route::post('sendingApplication',[ AmoCRMController::class, "saveApplication"]);

Route::get('first-login',[ AmoCRMController::class, "firstLogin"])->name("first-login");
Route::post('first-login',[ AmoCRMController::class, "firstConnect"]);