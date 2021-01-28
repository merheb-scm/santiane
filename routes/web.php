<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoyageController;

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

Route::get('/welcome', function () {
    return view('welcome');
});

/*
Route::group(['prefix' => 'voyages', 'as' => 'voyages.'], function () {
    Route::post('dt', ['as' => 'datatable', 'uses' => 'VoyageController@datatable']);
    Route::post('dtAction/{action}/{val?}', ['as' => 'dtAction', 'uses' => 'VoyageController@dtAction']);
});
*/

Route::get('voyages/list', [VoyageController::class, 'getVoyages'])->name('voyages.list');

Route::get('voyages/{voyage}/steps', [VoyageController::class, 'getSteps'])->name('voyages.steps.list');
Route::delete('voyages/{voyage}/steps/{id}', [VoyageController::class, 'deleteStep'])->name('voyages.steps.delete') ;

// Route::delete('voyages/{id}/delete', [VoyageController::class, 'dtAction'])->name('voyages.action');
Route::resource('voyages', VoyageController::class);
