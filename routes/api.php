<?php

use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('wallet', WalletController::class);
Route::post('/user-wallet', [WalletController::class, 'createWallet'])->middleware('auth:sanctum');
Route::get('/getAllWallets', [WalletController::class, 'getWallets']);
Route::get('/wallet/{id}', [WalletController::class, 'show']);
Route::post('/transfer', [WalletController::class, 'creditWallet'])->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/all-users', [AuthController::class, 'getUsers']);


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
