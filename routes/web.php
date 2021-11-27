<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FinancialActivityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
|
|
*/
Route::get('/', [App\Http\Controllers\LoginController::class, 'index'])->name('login')->middleware('guest');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/validateCredentials', [App\Http\Controllers\LoginController::class, 'validateCredentials'])->name('validateCredentials');
Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
|
|
*/
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Category
|--------------------------------------------------------------------------
|
|
*/
Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category')->middleware('auth');
Route::post('/addCategory', [App\Http\Controllers\CategoryController::class, 'addCategory'])->name('addCategory');
Route::post('/updateCategory', [App\Http\Controllers\CategoryController::class, 'updateCategory'])->name('updateCategory');
Route::post('/deleteCategory', [App\Http\Controllers\CategoryController::class, 'deleteCategory'])->name('deleteCategory');
Route::post('/reactivateCategory', [App\Http\Controllers\CategoryController::class, 'reactivateCategory'])->name('reactivateCategory');

/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
|
|
*/
Route::get('/config', [App\Http\Controllers\ConfigController::class, 'index'])->name('config')->middleware('auth');
Route::post('/updateConfig', [App\Http\Controllers\ConfigController::class, 'updateConfig'])->name('updateConfig');
Route::post('/updateSaving', [App\Http\Controllers\ConfigController::class, 'updateSaving'])->name('updateSaving');
Route::post('/deleteSaving', [App\Http\Controllers\ConfigController::class, 'deleteSaving'])->name('deleteSaving');

/*
|--------------------------------------------------------------------------
| Salary
|--------------------------------------------------------------------------
|
|
*/
Route::get('/salary', [App\Http\Controllers\SalaryController::class, 'index'])->name('salary')->middleware('auth');
Route::post('/addSalary', [App\Http\Controllers\SalaryController::class, 'addSalary'])->name('addSalary');
Route::post('/updateSalary', [App\Http\Controllers\SalaryController::class, 'updateSalary'])->name('updateSalary');
Route::post('/deleteSalary', [App\Http\Controllers\SalaryController::class, 'deleteSalary'])->name('deleteSalary');

/*
|--------------------------------------------------------------------------
| Income
|--------------------------------------------------------------------------
|
|
*/
Route::get('/income', [App\Http\Controllers\FinancialActivityController::class, 'indexIncome'])->name('income');
Route::post('/addIncome', [App\Http\Controllers\FinancialActivityController::class, 'addIncome'])->name('addIncome');

/*
|--------------------------------------------------------------------------
| Salary
|--------------------------------------------------------------------------
|
|
*/