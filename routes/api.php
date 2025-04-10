<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Mặc định apiResource sẻ trỏ tới 5 phương thức mặc định trong controller api
// Nếu muốn tạo thêm phương thức mới trong controller api 
// thì ta cần tạo thêm đường dẫn riêng để trỏ tới phương thức đó
// Và route đó phải được đặt ở bên trên apiResource
Route::get('products/test', [ProductController::class, 'test']);
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');