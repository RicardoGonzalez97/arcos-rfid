<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Jobs\BatchScanEventsJob;
use App\Jobs\CloseScanSessionJob;

class ScanSessionController extends Controller
{

     public function batch(Request $request)
    {
       $request->validate([
            'scan_session_id' => 'required|uuid',
            'events' => 'required|array|min:1',
            'events.*.rfid' => 'required|string',
        ]);

        $scanSessionId = $request->scan_session_id;
        $events = $request->events;

        // Enviar al job (RabbitMQ o el driver que tengas configurado)
        BatchScanEventsJob::dispatch($scanSessionId, $events);

        return response()->json([
            'message' => 'Events queued successfully 🚀',
            'count' => count($events)
        ], 202); // 202 = Accepted
    }

    public function close(string $scanSessionId)
    {
        $updated = DB::table('order_scan_sessions')
            ->where('scan_session_id', $scanSessionId)
            ->where('status', 'OPEN')
            ->update([
                'status' => 'CLOSING',
                'closed_at' => now(),
            ]);

        if ($updated === 0) {
            return response()->json([
                'message' => 'Scan session is not open or does not exist'
            ], 409);
        }

        CloseScanSessionJob::dispatch($scanSessionId);

        return response()->json([
            'message' => 'Scan session closing started'
        ], 202);
    }

     public function show(string $scanSessionId)
    {
        $result = DB::table('scan_session_results')
            ->where('scan_session_id', $scanSessionId)
            ->first();

        if (!$result) {
            return response()->json([
                'message' => 'Scan session result not found',
            ], 404);
        }

        return response()->json([
            'scan_session_id' => $result->scan_session_id,
            'status'          => $result->status,
            'expected_total'  => $result->expected_total,
            'scanned_total'   => $result->scanned_total,
            'missing_total'   => $result->missing_total,
            'extra_total'     => $result->extra_total,
        ], 200);
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'order_id' => 'required|integer|exists:orders,order_id',
    ], [
        'order_id.required' => 'El order_id es obligatorio',
        'order_id.integer'  => 'Debe ser un número',
        'order_id.exists'   => 'La orden no existe',
    ]);

   
    $openSessionExists = DB::table('order_scan_sessions')
        ->where('order_id', $validated['order_id'])
        ->where('status', 'OPEN')
        ->exists();

    if ($openSessionExists) {
        return response()->json([
            'message' => 'Ya existe una sesión de escaneo abierta para esta orden'
        ], 409);
    }

    $scanSessionId = (string) Str::uuid();

    DB::table('order_scan_sessions')->insert([
        'order_id'        => $validated['order_id'],
        'scan_session_id' => $scanSessionId,
        'status'          => 'OPEN',
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

    return response()->json([
        'scan_session_id' => $scanSessionId,
        'order_id'        => $validated['order_id'],
        'status'          => 'OPEN',
    ], 201);
}
}
