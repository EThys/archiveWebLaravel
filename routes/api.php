<?php

use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\InvoiceKeyController;
use App\Http\Controllers\SubdirectoryController;
use App\Http\Controllers\TInvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\ImageController;

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
    Route::post('/changePassword',[PasswordChangeController::class, 'changePassword']);
    //Routes for directory
    Route::get('/allDirectories',[DirectoryController::class, 'index']);
    Route::post('/addDirectory',[DirectoryController::class, 'store']);
    Route::post('/filter/directory',[DirectoryController::class,'filter']);
    //Routes for branches
    Route::get('/allBranches',[BranchController::class, 'index']);
    Route::post('/addBranch',[BranchController::class, 'store']);
    //Routes for InvoicesKeys
    Route::get('/allInvoicesKeys',[InvoiceKeyController::class, 'index']);
    Route::post('/addInvoiceKeys',[InvoiceKeyController::class, 'store']);
    //Routes for invoice
    Route::get('/allInvoices',[TInvoiceController::class, 'index']);
    Route::get('/allInvoices/{id}',[TInvoiceController::class, 'getInvoicesForCurrentUser']);
    Route::get('/showInvoice/{id}',[TInvoiceController::class, 'show']);
    Route::post('/addInvoice',[TInvoiceController::class, 'store']);
    Route::post('/updateInvoice',[TInvoiceController::class, 'update']);
    Route::delete('/deleteInvoice/{id}',[TInvoiceController::class, 'delete']);
    //Routes for invoice 
    Route::post('/images/store', [ImageController::class, 'store']);
    Route::post('/images/update/{id}', [ImageController::class, 'update']);
    Route::delete('/images/delete/{id}', [ImageController::class, 'destroy']);
    //Routes for subdirectories
    Route::post('/addSubdirectory', [SubdirectoryController::class, 'store']);
    Route::get('/allSubdirectories', [SubdirectoryController::class, 'index']);
    //Routes for search
    Route::get('/invoices/filter',[TInvoiceController::class, 'search']);
    


});



