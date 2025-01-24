<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\AuthController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth:api')->get('user', [AuthController::class, 'me']);

Route::middleware('auth:api')->group(function () {
    Route::post('travel-orders', [TravelOrderController::class, 'create']); // Criar pedido
    Route::put('travel-orders/{id}', [TravelOrderController::class, 'update']); // Atualizar pedido
    Route::get('travel-orders/{id}', [TravelOrderController::class, 'show']); // Consultar pedido
    Route::get('travel-orders', [TravelOrderController::class, 'index']); // Listar todos os pedidos
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
