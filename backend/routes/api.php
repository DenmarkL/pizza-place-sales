<?php

use App\Http\Controllers\CsvUploadController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/upload/pizza-types', [CsvUploadController::class, 'uploadPizzaTypes']);
Route::post('/upload/pizzas', [CsvUploadController::class, 'uploadPizzas']);
Route::post('/upload/orders', [CsvUploadController::class, 'uploadOrders']);
Route::post('/upload/order-details', [CsvUploadController::class, 'uploadOrderDetails']);
