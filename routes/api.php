<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\CategorieController;

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

Route::controller(UserController::class)->group(function(){
    Route::post('login','loginUser');
});

Route::controller(UserController::class)->group(function(){
    Route::get('user','getUserDetail');
    Route::get('logout','userLogout');
});


// ->middleware('auth:api')



Route::apiResource('/products', ProductController::class);

Route::group(['prefix'=>'products'],function(){

    Route::apiResource('/{product}/reviews', ReviewController::class);

});



Route::apiResource('/categories',CategorieController::class);

Route::group(['prefix'=>'categories'],function(){

    Route::apiResource('/{categorie}/products', ProductController::class);

});
