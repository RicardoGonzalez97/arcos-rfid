<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anomaly;
use App\Traits\ApiResponse;

class AnomalyController extends Controller
{
    use ApiResponse;

    /**
     * Obtener todas las anomalías con filtros opcionales
     */
    public function index(Request $request)
    {
       $query = Anomaly::with([
            'dock:id,number,name',
            'scanSession:scan_session_id,status',
            'resolver:id,name'
        ]);
        

        // 🔍 Filtros opcionales
        if ($request->filled('dock_id')) {
            $query->where('dock_id', $request->dock_id);
        }

        if ($request->filled('scan_session_id')) {
            $query->where('scan_session_id', $request->scan_session_id);
        }

        if ($request->filled('anomaly_type')) {
            $query->where('anomaly_type', $request->anomaly_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('detected_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('detected_at', '<=', $request->date_to);
        }

        // Ordenar por fecha más reciente
        $anomalies = $query->orderBy('detected_at', 'desc')
                           ->paginate($request->get('per_page', 10));

        return $this->ok($anomalies, 'Anomalies retrieved successfully');
    }

    public function summary(Request $request)
    {
        $query = Anomaly::query();

        if ($request->filled('dock_id')) {
            $query->where('dock_id', $request->dock_id);
        }

        if ($request->filled('scan_session_id')) {
            $query->where('scan_session_id', $request->scan_session_id);
        }

        return $this->ok([
            'total_errors' => $query->count(),
            'unknown'      => (clone $query)->where('anomaly_type', 'unknown')->count(),
            'duplicates'   => (clone $query)->where('anomaly_type', 'duplicate')->count(),
            'extras'       => (clone $query)->where('anomaly_type', 'extra')->count(),
            'missing'      => (clone $query)->where('anomaly_type', 'missing')->count(),
        ], 'Anomaly summary retrieved successfully');
    }

    /**
     * Obtener una anomalía específica
     */
    public function show($id)
    {
        $anomaly = Anomaly::with([
            'dock:id,name',
            'scanSession:scan_session_id,status',
            'resolver:id,name'
        ])->find($id);

        if (!$anomaly) {
            return $this->fail('Anomaly not found', 404);
        }

        return $this->ok($anomaly, 'Anomaly retrieved successfully');
    }
}