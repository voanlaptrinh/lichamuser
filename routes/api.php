<?php

use App\Http\Controllers\Api\LunarConvertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Đặt tên cho các route để JS có thể gọi một cách an toàn
Route::post('/convert-to-am', [LunarConvertController::class, 'convertToAm'])->name('api.to.am');
Route::post('/convert-to-duong', [LunarConvertController::class, 'convertToDuong'])->name('api.to.duong');