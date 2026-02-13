<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchScanEventsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $scanSessionId;
    protected array $events;

    /**
     * Create a new job instance.
     */
    public function __construct(string $scanSessionId, array $events)
    {
        $this->scanSessionId = $scanSessionId;
        $this->events = $events;
    }

    /**
     * Execute the job.
     */
public function handle(): void
{
    try {
        $now = Carbon::now();

        // 1️⃣ Validar sesión y estado
        $session = DB::table('order_scan_sessions')
            ->where('scan_session_id', $this->scanSessionId)
            ->select('order_id', 'status')
            ->first();

        if (!$session) {
            logger()->warning("❌ Scan session not found", [
                'scan_session_id' => $this->scanSessionId
            ]);
            return;
        }

        if ($session->status !== 'OPEN') {
            logger()->warning("⛔ Scan session is not OPEN", [
                'scan_session_id' => $this->scanSessionId,
                'status' => $session->status
            ]);
            return;
        }

        $insertData = [];

        foreach ($this->events as $event) {

            $rfidInfo = DB::table('rfid_tags_info')
                ->where('rfid', $event['rfid'])
                ->select('rfid_tags_info_id', 'product_id')
                ->first();

            $insertData[] = [
                'scan_session_id'   => $this->scanSessionId,
                'order_id'          => $session->order_id,
                'rfid_tags_info_id' => $rfidInfo->rfid_tags_info_id ?? null,
                'product_id'        => $rfidInfo->product_id ?? null,
                'event_status'      => 'scanned',
                'scanned_at'        => $now,
            ];
        }

        if (!empty($insertData)) {
            DB::table('scan_events')->insert($insertData);
        }

        logger()->info(
            "🐇 Inserted " . count($insertData) . " scan events",
            [
                'scan_session_id' => $this->scanSessionId,
                'order_id' => $session->order_id
            ]
        );

    } catch (\Throwable $e) {
        logger()->error("❌ BatchScanEventsJob failed", [
            'error' => $e->getMessage(),
            'scan_session_id' => $this->scanSessionId,
        ]);

        throw $e;
    }
}



}
