<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;


Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    Route::get('/users', [UserController::class , 'index'])->name("users.get");
    Route::get('/users/{id}', [UserController::class, 'show'])->name("user.show");
    Route::post('/users', [UserController::class, 'store'])->name("users.store");
    Route::post('/users/{id}/edit', [UserController::class, 'edit'])->name("users.edit");
    Route::put('/users/{id}', [UserController::class, 'update'])->name("users.update");
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name("users.destroy");

    // Route::resource('/products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name("products");
    Route::get('/products/{id}', [ProductController::class, 'show'])->name("products.show");
    Route::post('/products', [ProductController::class, 'store'])->name("products.store");
    Route::post('/products/{id}/edit', [ProductController::class, 'edit'])->name("products.edit");
    Route::put('/products/{id}', [ProductController::class, 'update'])->name("products.update");
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name("products.destroy");


    Route::get('/categories', [CategorieController::class, 'index'])->name("categories");
    Route::get('/categories/{id}', [CategorieController::class, 'show'])->name("categories.show");
    Route::post('/categories', [CategorieController::class, 'store'])->name("categories.store");
    Route::post('/categories/{id}/edit', [CategorieController::class, 'edit'])->name("categories.edit");
    Route::put('/categories/{id}', [CategorieController::class, 'update'])->name("categories.update");
    Route::delete('/categories/{id}', [CategorieController::class, 'destroy'])->name("categories.destroy");


    
});    
 
Route::prefix('v1')->group(function (){

    Route::post('/login', [UserController::class, 'login']);        
    Route::post('/register', [UserController::class, 'register']);

});