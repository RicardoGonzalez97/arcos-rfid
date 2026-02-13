<?php

namespace App\Http\Controllers;

use App\Jobs\ArcosRfidRabbitJob;
use Illuminate\Http\JsonResponse;

class HolaController extends Controller
{
    public function hola(): JsonResponse
    {
        ArcosRfidRabbitJob::dispatch();

        return response()->json([
            'mensaje' => 'Hola mundo 🚀 job enviado a RabbitMQ'
        ]);
    }
}
