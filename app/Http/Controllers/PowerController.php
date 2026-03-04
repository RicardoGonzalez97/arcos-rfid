<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PowerEvent;

class PowerController extends Controller
{
    public function report(Request $request)
    {
        $request->validate([
            'power_status' => 'required|string|max:50',
            'reported_at'  => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $event = PowerEvent::create([
            'power_status' => strtoupper(trim($request->power_status)),
            'reported_at'  => $request->reported_at ?? now(),
            'notes'        => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Power status recorded successfully',
            'data'    => $event
        ], 201);
    }
}