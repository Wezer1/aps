<?php

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

Route::group(['prefix'=>'posts' ], function (){
//    Route::group(['prefix'=>'images' ], function (){
//        Route::post('/',\App\Http\Controllers\Post\StoreController::class);
//    });
    Route::post('/', \App\Http\Controllers\Admin\Post\StoreController::class)->name('post.store');;
    Route::patch('/{post}', \App\Http\Controllers\Admin\Post\UpdateController::class);
    Route::get('/', \App\Http\Controllers\Admin\Post\IndexController::class);
    Route::get('/{post}',\App\Http\Controllers\Admin\Post\IndexController::class);
    Route::delete('/{post}', \App\Http\Controllers\Admin\Post\DeleteController::class);
});
