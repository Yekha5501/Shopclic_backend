<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('import', [ProductController::class, 'importForm'])->name('import');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import.store');
    Route::get('products/import', [ProductController::class, 'importForm'])->name('products.import'); // Import form
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import.store'); // Import processing


    // Transactions Routes

    Route::put('/Transactions/{order}', [OrderController::class, 'update'])->name('transactions.update');
    Route::delete('/Transactions/{order}', [OrderController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});



Route::middleware('auth')->group(function () {
    Route::get('/import/excel', [ImportController::class, 'importForm'])->name('import.excel.form');
    Route::post('/import/excel', [ImportController::class, 'import'])->name('import.excel.store');
    Route::resource('transactions', TransactionController::class);
    Route::get('/reports/transactions/{date}', [ReportController::class, 'transactionsByDate'])->name('reports.transactions-by-date');
});


require __DIR__ . '/auth.php';
