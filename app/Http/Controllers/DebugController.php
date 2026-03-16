<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class DebugController extends Controller
{
    public function index()
    {
        return view('debug');
    }

    public function logs()
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return response()->json([
                'logs' => []
            ]);
        }

        $logs = File::lines($logPath)->take(-50);

        return response()->json([
            'logs' => $logs
        ]);
    }
}