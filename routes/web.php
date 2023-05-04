<?php

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

use App\Http\Controllers\StatisticExportController;
use App\Http\Controllers\TypeFishPictureController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\DataCollectionController;
use App\Http\Controllers\SpeciesFishController;
use App\Http\Controllers\FishingDataController;
use App\Http\Controllers\FishingGearController;
use App\Http\Controllers\LandingSiteController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TypeShipController;
use App\Http\Controllers\TypeFishController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('login', [LoginController::class, 'index'])->name('home');

Route::post('login', [LoginController::class, 'authenticate'])->name('login');


Route::prefix('forget-password')->name('forget-password.')->group(function(){

    Route::post('request-email', [ForgetPasswordController::class, 'requestEmail'])->name('request-email');
    
    Route::get('redirect', [ForgetPasswordController::class, 'redirect'])->name('redirect');

    Route::get('{id}', [ForgetPasswordController::class, 'index'])->name('index');
    
    Route::post('{id}', [ForgetPasswordController::class, 'update'])->name('update');
    
});

Route::middleware(['auth'])->group(function ()
{
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('dashboard', DashboardController::class)->only('index');

    Route::prefix('dashboard')->name('dashboard.')->group(function ()
    {
        
        Route::resource('branch', BranchController::class);
        
        Route::get('profile/{id}/edit', [UserController::class, 'editProfile'])->name('profile.edit');
        Route::post('profile/{id}', [UserController::class, 'updateProfile'])->name('profile.update');

        Route::resource('user', UserController::class);

        Route::resource('type-ship', TypeShipController::class);

        Route::resource('ship', ShipController::class);

        Route::resource('fishing-gear', FishingGearController::class);

        Route::prefix('type-fish')->name('type-fish.')->group(function ()
        {
            Route::resource('picture', TypeFishPictureController::class);
        });
        Route::resource('type-fish', TypeFishController::class);

        Route::resource('species-fish', SpeciesFishController::class);

        Route::resource('landing-site', LandingSiteController::class);

        Route::prefix('fishing-data')->name('fishing-data.')->group(function ()
        {
            Route::post('data-collection/{id}/verification', [DataCollectionController::class, 'verification'])->name('verification');
            Route::resource('data-collection', DataCollectionController::class);
            Route::post('{id}/verification', [FishingDataController::class, 'verification'])->name('verification');
            Route::post('ship/store', [FishingDataController::class, 'shipStore'])->name('ship.store');
            Route::post('fishing-gear/store', [FishingDataController::class, 'fishingGearStore'])->name('fishing-gear.store');
        });
        Route::get('fishing-data/export', [FishingDataController::class, 'export'])->name('fishing-data.export');
        Route::resource('fishing-data', FishingDataController::class);

        Route::get('map', [MapController::class, 'index'])->name('map.index');

        Route::prefix('statistic')->name('statistic.')->group(function ()
        {
            Route::get('1', [StatisticController::class, 'index1'])->name('1.index');
            Route::get('2', [StatisticController::class, 'index2'])->name('2.index');
            Route::get('3', [StatisticController::class, 'index3'])->name('3.index');
            Route::get('4', [StatisticController::class, 'index4'])->name('4.index');
            Route::get('5', [StatisticController::class, 'index5'])->name('5.index');
            Route::get('6', [StatisticController::class, 'index6'])->name('6.index');
            Route::get('7', [StatisticController::class, 'index7'])->name('7.index');
            Route::prefix('export')->name('export.')->group(function ()
            {  
                Route::get('1', [StatisticExportController::class, 'index1'])->name('1');
                Route::get('2', [StatisticExportController::class, 'index2'])->name('2');
                Route::get('3', [StatisticExportController::class, 'index3'])->name('3');
                Route::get('4', [StatisticExportController::class, 'index4'])->name('4');
                Route::get('5', [StatisticExportController::class, 'index5'])->name('5');
                Route::get('6', [StatisticExportController::class, 'index6'])->name('6');
                Route::get('7', [StatisticExportController::class, 'index7'])->name('7');
            });
        });

    });

});








