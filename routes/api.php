<?php
use App\Http\Controllers\ScanSessionResultController;
use App\Http\Controllers\ScanSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\RfidController;

Route::get('test', function () {
    return response()->json([
        'message' => 'Hello World'
    ]);
});


Route::post('/scan-sessions', [ScanSessionController::class, 'store']);
// crea sesión por dock_id en body

Route::post('/scan-events/batch', [ScanSessionController::class, 'batch']);
// procesa por dock_id

Route::post('/docks/{dockId}/close-session',
    [ScanSessionController::class, 'close']
);

Route::get('/docks/{dockId}/closed-results',
    [ScanSessionController::class, 'show']
);

Route::post('/power/report', [PowerController::class, 'report']);

Route::post('/rfid/start', [RfidController::class,'start']);
Route::post('/rfid/stop', [RfidController::class,'stop']);
Route::get('/docks/with-products', [RfidController::class, 'withProducts']);
Route::get('/docks/initialization', [RfidController::class, 'initialization']);