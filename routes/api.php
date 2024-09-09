<?php

use App\Http\Controllers\DirectoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;;
use App\Http\Controllers\PasswordChangeController;

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

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::group(['middleware'=>["auth:sanctum"]],function(){
    //Routes for Authentification
    Route::get('/logout',[AuthController::class, 'logout']);
    Route::post('/create/branch',[BranchController::class, 'store']);
    Route::post('/changePassword',[PasswordChangeController::class, 'changePassword']);
    //Routes for directory
    Route::get('/allDirectories',[DirectoryController::class, 'index']);
    Route::post('/addDirectory',[DirectoryController::class, 'store']);
    Route::post('/filter/directory',[DirectoryController::class,'filter']);
});



