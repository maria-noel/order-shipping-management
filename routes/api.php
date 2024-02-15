<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentController;

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
$apiVersion = 'v1';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix($apiVersion.'/orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/{order}/printLabel', [OrderController::class, 'printLabel']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::delete('/{order}', [OrderController::class, 'destroy']);
});

Route::prefix($apiVersion.'/shipments')->group(function () {
    Route::put('/{shipment}/changeStatus', [ShipmentController::class, 'changeStatus']);
    Route::post('/', [ShipmentController::class, 'store']);

});
