<?php

use App\Http\Controllers\FilterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\PropertiesController;
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

Route::get('test', [\App\Http\Controllers\testController::class, "test"])->name('test');

// user controller routes
Route::get('home', [HomeController::class, "index"])->name('home');
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, "login"])->name('login');
    Route::post('logout', [AuthController::class, "logout"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::post('me', [AuthController::class, "me"]);
});

Route::group([
    'middleware' => 'api',

], function () {
    Route::post('pay',[paymentController::class, "start"]);
});

Route::post("register", [UserController::class, "register"]);



//brand route
Route::middleware('jwt.verify')->prefix('brand/')->group(function() {
    Route::post("", [BrandController::class, "store"]);
    Route::put("{id}", [BrandController::class, "update"]);
});

//cart route
Route::middleware('jwt.verify')->prefix('cart/')->group(function() {
    Route::get("", [CartController::class, "getCart"]);
    Route::post("", [CartController::class, "addToCard"]);
    Route::post("group", [CartController::class, "addAllToCard"]);
    Route::put("{id}", [CartController::class, "update"]);
    Route::delete("{id}", [CartController::class, "delete"]);

});

//product route
Route::post('product/image/upload', [ImageController::class, "upload"])->name('image.upload');
Route::middleware('jwt.verify')->prefix('product/')->group(function() {
    Route::post("", [ProductController::class, "store"]);
    Route::put("{id}", [ProductController::class, "update"]);
    Route::delete("{id}", [ProductController::class, "delete"]);
    Route::post("adminSearch", [ProductController::class, "search"]);
});
