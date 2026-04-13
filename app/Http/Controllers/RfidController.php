<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class RfidController extends Controller
{

    private $baseUrl = "http://189.194.132.250:8393";
    private $user = "admin";
    private $pass = "Atia123#IO";


    public function start(Request $request)
    {
        $dock = $request->dock_id;

        // Login
        $login = Http::timeout(10)
            ->withBasicAuth($this->user, $this->pass)
            ->get($this->baseUrl . "/RestLogin")
            ->json();

        if (!isset($login['message'])) {
            return response()->json([
                "error" => "No se pudo obtener el token"
            ], 500);
        }

        $token = $login['message'];

        // START
        $res = Http::timeout(10)
            ->withToken($token)
            ->put($this->baseUrl . "/START", [
                "Anden" => $dock,
                "Tipo" => "EMBARQUE"
            ]);

        return response()->json($res->json());
    }

     public function withProducts()
    {
        $docks = DB::table('supplier_appointment_slot_docks as d')
            ->join('dock_purchase_orders as dpo', 'd.id', '=', 'dpo.dock_id')
            ->join('purchase_orders as po', 'po.id', '=', 'dpo.purchase_order_id')
            ->join('purchase_order_items as poi', 'poi.purchase_order_id', '=', 'po.id')
            ->where('d.is_active', true)
            ->select(
                'd.id',
                'd.number',
                'd.name',
                DB::raw('COUNT(DISTINCT po.id) as purchase_orders_count'),
                DB::raw('COUNT(poi.id) as products_count'),
                DB::raw('SUM(poi.cantidad) as total_quantity')
            )
            ->groupBy('d.id', 'd.number', 'd.name')
            ->orderBy('d.number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $docks
        ]);
    }


    public function stop(Request $request)
    {
        $dock = $request->dock_id;

        // Login
        $login = Http::timeout(10)
            ->withBasicAuth($this->user, $this->pass)
            ->get($this->baseUrl . "/RestLogin")
            ->json();

        if (!isset($login['message'])) {
            return response()->json([
                "error" => "No se pudo obtener el token"
            ], 500);
        }

        $token = $login['message'];

        // STOP
        $res = Http::timeout(10)
            ->withToken($token)
            ->put($this->baseUrl . "/STOP", [
                "Anden" => $dock
            ]);

        return response()->json($res->json());
    }
public function initialization()
{
    // 1️⃣ Obtener docks activos con productos asignados
    $docks = DB::table('supplier_appointment_slot_docks as d')
        ->join('dock_purchase_orders as dpo', 'd.id', '=', 'dpo.dock_id')
        ->join('purchase_orders as po', 'po.id', '=', 'dpo.purchase_order_id')
        ->join('purchase_order_items as poi', 'poi.purchase_order_id', '=', 'po.id')
        ->where('d.is_active', true)
        ->select(
            'd.id',
            'd.number',
            'd.name',
            DB::raw('COUNT(DISTINCT po.id) as purchase_orders_count'),
            DB::raw('COUNT(poi.id) as products_count'),
            DB::raw('SUM(poi.cantidad) as total_quantity')
        )
        ->groupBy('d.id', 'd.number', 'd.name')
        ->orderBy('d.number')
        ->get();

    // 2️⃣ Obtener sesiones abiertas por dock (incluyendo fecha de inicio)
    $openSessions = DB::table('order_scan_sessions')
        ->where('status', 'OPEN')
        ->select(
            'dock_id',
            'scan_session_id',
            'created_at as session_started_at'
        )
        ->get()
        ->keyBy('dock_id');

    // 3️⃣ Construir respuesta con información de escaneos
    $result = $docks->map(function ($dock) use ($openSessions) {

        $session = $openSessions->get($dock->id);

        $scanEvents = [];

        if ($session) {
            // Obtener eventos de escaneo de la sesión activa
            $scanEvents = DB::table('scan_events as se')
                ->leftJoin('products as p', 'p.product_id', '=', 'se.product_id')
                ->where('se.scan_session_id', $session->scan_session_id)
                ->select(
                    'se.scanned_at as timestamp',
                    'se.product_id as tag_id',
                    'p.name as product_name',
                    'se.event_status as status',
                    DB::raw('1 as cantidad'),
                    'se.order_id'
                )
                ->orderByDesc('se.scanned_at')
                ->limit(100)
                ->get();
        }

        return [
            'id' => $dock->id,
            'number' => $dock->number,
            'name' => $dock->name,
            'purchase_orders_count' => (int) $dock->purchase_orders_count,
            'products_count' => (int) $dock->products_count,
            'total_quantity' => (int) $dock->total_quantity,
            'has_active_session' => (bool) $session,
            'scan_session_id' => $session->scan_session_id ?? null,
            'session_started_at' => $session->session_started_at ?? null, // ✅ NUEVO CAMPO
            'scanned_products' => $scanEvents,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $result,
    ]);
}
}