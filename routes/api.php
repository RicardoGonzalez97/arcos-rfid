<?php
use App\Http\Controllers\ScanSessionResultController;
use App\Http\Controllers\ScanSessionController;
use Illuminate\Support\Facades\Route;

Route::post('/scan-sessions', [ScanSessionController::class, 'store']);
Route::post('/scan-events/batch', [ScanSessionController::class, 'batch']);

Route::post(
    'scan-sessions/{scanSessionId}/close',
    [ScanSessionController::class, 'close']
);

Route::get(
    '/scan-sessions/{scanSessionId}/result',
    [ScanSessionController::class, 'show']
);
