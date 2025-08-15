<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\DepartmentController;
Use App\Http\Controllers\DesignationController;
Use App\Http\Controllers\EmployeeController;
Use App\Http\Controllers\MailController;
Use App\Http\Controllers\Auth\AuthController;
use App\Models\User;
Use App\Http\Controllers\LocationController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehiclesCategoryController;

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('home');
// Route::get('send-mail', [MailController::class, 'index']);
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
    Route::controller(DepartmentController::class)
    ->prefix('department')
    ->middleware('auth')
    ->name('department.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'destroy')->name('destroy');
    });
Route::controller(DesignationController::class)
    ->prefix('designation')
    ->middleware('auth')
    ->name('designation.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });
    Route::controller(EmployeeController::class)
    ->prefix('employee')
    ->middleware('auth')
    ->name('employee.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });

     Route::controller(LocationController::class)
    ->prefix('location')
    ->middleware('auth')
    ->name('locations.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });



    Route::controller(PartsController::class)
    ->prefix('parts')
    ->middleware('auth')
    ->name('parts.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });

    Route::controller(VehiclesCategoryController::class)
    ->prefix('VehiclesCategory')
    ->middleware('auth')
    ->name('VehiclesCategory.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });

    Route::controller(TownController::class)
    ->prefix('town')
    ->middleware('auth')
    ->name('town.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/edit', 'edit')->name('edit'); // Changed to POST
        Route::post('/update', 'update')->name('update'); // Changed to POST
        Route::post('/delete', 'destroy')->name('destroy'); // Changed to POST
    });

    Route::controller(VehicleController::class)
    ->prefix('vehicle')
    ->middleware('auth')
    ->name('vehicle.')
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

Route::get('/custom-verify/{id}', function ($id) {
    $user = User::find($id);

    if ($user) {
        $user->is_active = 1;
        $user->save();
        return redirect('/login')->with('success', 'Your email has been verified. You can now log in.');
    }

    return redirect('/login')->with('error', 'Invalid verification link.');
})->name('custom.verify');

