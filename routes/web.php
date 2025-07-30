<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\SubCategoryController;

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('home');

Route::controller(CategoryController::class)
    ->prefix('categories')
    ->middleware('auth')
    ->name('categories.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });

Route::controller(SubCategoryController::class)->prefix('sub-categories')->middleware('auth')->name('sub-categories.')->group(function () {
    Route::get('/', 'index')->name('index');
});
Route::controller(SubCategoryController::class)->prefix('sub-categories')->middleware('auth')->name('sub-categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });


Route::controller(ProductController::class)->prefix('products')->middleware('auth')->name('products.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::post('update', 'update')->name('update');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::post('delete', 'destroy')->name('destroy');
    Route::get('export','export')->name('export');

});

Route::controller(DropdownController::class)->prefix('dropdown')->middleware('auth')->name('dropdown.')->group(function () {
    Route::get('/categories', 'getAllCategories')->name('categories');
    Route::get('/sub-categories', 'getAllSubCategories')->name('sub-categories');
});


require __DIR__.'/auth.php';

