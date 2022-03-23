<?php

use App\Http\Controllers\CapabilitiesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('patient/recursive', [PatientController::class, 'recursive']);
Route::get('patient/pagination', [PatientController::class, 'pagination']);
Route::get('patient/getmodified', [PatientController::class, 'getmodified']);
Route::get('Patient/search', [PatientController::class, 'search']);
Route::resource('Patient', PatientController::class);

Route::get('capabilities', [CapabilitiesController::class, 'index']);
Route::get('capabilitiesaws', [CapabilitiesController::class, 'aws']);
Route::get('explore', [CapabilitiesController::class, 'exploreClass']);

Route::get('auth', [AuthController::class, 'auth']);
Route::get('denyauth', [AuthController::class, 'deny']);
Route::get('authorize', [AuthController::class, 'authorization']);
Route::get('denyauthorize', [AuthController::class, 'denyauthorization']);

