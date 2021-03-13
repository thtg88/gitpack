<?php

use App\Http\Controllers\Api\V1\DeploymentController;
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

Route::group(['prefix' => 'v1'], static function () {
    Route::group(
        [
            'prefix' => 'apps/{app:uuid}/deployments',
            'as' => 'apps.deployments.',
        ],
        static function () {
            Route::post('/', [DeploymentController::class, 'start'])
                ->name('start');
            Route::post('current/fail', [DeploymentController::class, 'failCurrent'])
                ->name('fail-current');
            Route::post('current/succeed', [DeploymentController::class, 'succeedCurrent'])
                ->name('succeed-current');
        }
    );
});
