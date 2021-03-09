<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\EnvironmentVariableController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], static function () {
    Route::view('/', 'dashboard')->name('dashboard');

    Route::group(['as' => 'apps.', 'prefix' => 'apps'], static function () {
        Route::get('create', [AppController::class, 'create'])->name('create');
        Route::get('{id}', [AppController::class, 'edit'])->name('edit');
        Route::delete('{id}', [AppController::class, 'destroy'])
            ->name('destroy');
        Route::put('{id}', [AppController::class, 'update'])->name('update');
        Route::get('/', [AppController::class, 'index'])->name('index');
        Route::post('/', [AppController::class, 'store'])->name('store');
    });

    Route::group(
        ['as' => 'environment-variables.', 'prefix' => 'environment-variables'],
        static function () {
            Route::delete('{id}', [EnvironmentVariableController::class, 'destroy'])
                ->name('destroy');
            Route::put('{id}', [EnvironmentVariableController::class, 'update'])
                ->name('update');
            Route::post('/', [EnvironmentVariableController::class, 'store'])
                ->name('store');
        }
    );
});
