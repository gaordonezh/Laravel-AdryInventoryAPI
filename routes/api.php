<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportController;
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
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/getuser', [UserController::class, 'getUserLogged']);
    Route::put('/updatepassword/{id}', [UserController::class, 'updatePassword']);
    Route::put('/add-stock/{id}', [ProductController::class, 'addStock']);
    Route::apiResource('/dashboard', DashboardController::class);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/measurement-unit', MeasurementUnitController::class);
    Route::apiResource('/user', UserController::class);
    Route::put('/update-user/{id}', [UserController::class, 'updateUser']);
    Route::apiResource('/sales', SalesController::class);
    Route::post('/anular-venta', [SalesController::class, 'anularVenta']);
    //Reportes
    Route::get('report/sales/{value}', [ReportController::class, 'reportSales']);
});