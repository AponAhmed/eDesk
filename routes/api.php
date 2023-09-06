<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/send', [ApiController::class, 'store'])->withoutMiddleware(['auth']);
Route::get('/v', [ApiController::class, 'index']);

// Route::middleware('auth:sanctum')->group(function () {
//     // Protected routes requiring authentication
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

// });
