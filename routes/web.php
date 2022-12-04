<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\JoinController;


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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('/fishes/trash', [FishController::class, 'deletelist']);
    Route::get('/fishes/trash/{fish}/restore', [FishController::class, 'restore']);
    Route::get('/fishes/trash/{fish}/forcedelete', [FishController::class, 'deleteforce']);
    Route::resource('fishes', FishController::class);
    Route::get('/suppliers/trash', [SupplierController::class, 'deletelist']);
    Route::get('/suppliers/trash/{supplier}/restore', [SupplierController::class, 'restore']);
    Route::get('/suppliers/trash/{supplier}/forcedelete', [SupplierController::class, 'deleteforce']);
    Route::resource('suppliers', SupplierController::class);
    Route::get('/stores/trash', [StoreController::class, 'deletelist']);
    Route::get('/stores/trash/{store}/restore', [StoreController::class, 'restore']);
    Route::get('/stores/trash/{store}/forcedelete', [StoreController::class, 'deleteforce']);
    Route::resource('stores', StoreController::class);
    Route::get('/totals', [JoinController::class,'index']);
});