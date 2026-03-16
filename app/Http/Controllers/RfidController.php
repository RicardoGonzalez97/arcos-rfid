<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

}
