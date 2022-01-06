<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FinancialActivityController;
use App\Http\Controllers\PiggyBankController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

/**
* Ruta para limpiar cache. Siempre que se quiera limpir, hay que descomentar y lanzar
*/
Route::get('/clear-cache', function () {
   echo Artisan::call('config:clear');
   echo Artisan::call('config:cache');
   echo Artisan::call('cache:clear');
   echo Artisan::call('route:clear');
});

/*
|--------------------------------------------------------------------------
| Login (OK)
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
| Category (OK)
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
| Config (OK)
|--------------------------------------------------------------------------
|
|
*/
Route::get('/config', [App\Http\Controllers\ConfigController::class, 'index'])->name('config')->middleware('auth');
Route::post('/updateConfig', [App\Http\Controllers\ConfigController::class, 'updateConfig'])->name('updateConfig');
Route::post('/updateSaving', [App\Http\Controllers\ConfigController::class, 'updateSaving'])->name('updateSaving');

/*
|--------------------------------------------------------------------------
| Salary (OK)
|--------------------------------------------------------------------------
|
|
*/
Route::get('/salary', [App\Http\Controllers\SalaryController::class, 'index'])->name('salary')->middleware('auth');
Route::post('/addSalary', [App\Http\Controllers\SalaryController::class, 'addSalary'])->name('addSalary');
Route::post('/addSalaryMonth', [App\Http\Controllers\SalaryController::class, 'addSalaryMonth'])->name('addSalaryMonth');
Route::post('/updateSalary', [App\Http\Controllers\SalaryController::class, 'updateSalary'])->name('updateSalary');
Route::post('/deleteSalary', [App\Http\Controllers\SalaryController::class, 'deleteSalary'])->name('deleteSalary');


/*
|--------------------------------------------------------------------------
| Piggy_bank (OK)
|--------------------------------------------------------------------------
|
|
*/
Route::get('/piggyBank', [App\Http\Controllers\PiggyBankController::class, 'index'])->name('piggyBank')->middleware('auth');
Route::post('/updateAmountPiggyBank', [App\Http\Controllers\PiggyBankController::class, 'updateAmountPiggyBank'])->name('updateAmountPiggyBank');
Route::post('/cleanPiggyBank', [App\Http\Controllers\PiggyBankController::class, 'cleanPiggyBank'])->name('cleanPiggyBank');

/*
|--------------------------------------------------------------------------
| Income
|--------------------------------------------------------------------------
|
|
*/
Route::get('/income', [App\Http\Controllers\FinancialActivityController::class, 'indexIncome'])->name('income');
Route::post('/addIncome', [App\Http\Controllers\FinancialActivityController::class, 'addIncome'])->name('addIncome');
Route::post('/updateIncome', [App\Http\Controllers\FinancialActivityController::class, 'updateIncome'])->name('updateIncome');
Route::post('/deleteIncome', [App\Http\Controllers\FinancialActivityController::class, 'deleteIncome'])->name('deleteIncome');

/*
|--------------------------------------------------------------------------
| Expense
|--------------------------------------------------------------------------
|
|
*/
Route::get('/expense', [App\Http\Controllers\FinancialActivityController::class, 'indexExpense'])->name('expense');
Route::post('/addExpense', [App\Http\Controllers\FinancialActivityController::class, 'addExpense'])->name('addExpense');
Route::post('/updateExpense', [App\Http\Controllers\FinancialActivityController::class, 'updateExpense'])->name('updateExpense');
Route::post('/deleteExpense', [App\Http\Controllers\FinancialActivityController::class, 'deleteExpense'])->name('deleteExpense');

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
| History
|--------------------------------------------------------------------------
|
|
*/
Route::get('/history', [App\Http\Controllers\HomeController::class, 'indexHistory'])->name('history')->middleware('auth');
Route::get('/showHistory', [App\Http\Controllers\HomeController::class, 'showHistory'])->name('showHistory');

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
|
|
*/
Route::get('/search', [App\Http\Controllers\HomeController::class, 'indexSearch'])->name('search')->middleware('auth');
Route::get('/searchFilter', [App\Http\Controllers\HomeController::class, 'searchFilter'])->name('searchFilter')->middleware('auth');