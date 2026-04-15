<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/test-broadcast', function () {

    event(new \App\Events\ProductScanned([
        'timestamp' => now()->format('H:i:s'),
        'product_id' => 'TEST123',
        'product_name' => 'Producto Test',
        'status' => 'scanned',
        'cantidad' => 1,
        'order_id' => 'ORD001'
    ], 1));

    return 'ok';
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/system-alerts', function () {
    return view('system-alerts');
})->name('system.alerts');

Route::get('/gate-settings', function () {
    return view('gate-settings');
})->name('gate.settings');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/system-alerts', fn() => view('system-alerts'))->name('system.alerts');
    Route::get('/gate-settings', fn() => view('gate-settings'))->name('gate.settings');
    Route::get('/final-inventory-confirmation', fn() => view('final-inventory-confirmation'))->name('final.inventory');
    Route::get('/session-history', fn() => view('session-history'))->name('session.history');
});

require __DIR__.'/auth.php';
