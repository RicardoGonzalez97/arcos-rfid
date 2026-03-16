<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CloseScanSessionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $scanSessionId;

    public function __construct(string $scanSessionId)
    {
        $this->scanSessionId = $scanSessionId;
    }

    public function handle(): void
    {
        DB::transaction(function () {

            $session = DB::table('order_scan_sessions')
                ->where('scan_session_id', $this->scanSessionId)
                ->lockForUpdate()
                ->first();

            if (!$session || $session->status !== 'CLOSING') {
                return;
            }

            $dockId = $session->dock_id;

            /*
            |--------------------------------------------------------------------------
            | Limpiar resultados previos
            |--------------------------------------------------------------------------
            */

            DB::table('scan_product_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();

            DB::table('scan_session_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | Generar resultados por producto
            |--------------------------------------------------------------------------
            */

            DB::insert("
                INSERT INTO scan_product_results
                (scan_session_id, order_id, product_id, expected_qty, scanned_qty, status, created_at)

                SELECT
                    ?,
                    op.order_id,
                    op.product_id,
                    op.expected_qty,
                    COALESCE(se.scanned_qty,0),

                    CASE
                        WHEN COALESCE(se.scanned_qty,0) < op.expected_qty THEN 'MISSING'
                        WHEN COALESCE(se.scanned_qty,0) > op.expected_qty THEN 'EXTRA'
                        ELSE 'OK'
                    END,

                    NOW()

             FROM
            (
                SELECT
                    op.order_id,
                    op.product_id,
                    SUM(op.expected_quantity) as expected_qty
                FROM order_products op
                JOIN (
                    SELECT DISTINCT se.order_id
                    FROM scan_events se
                    WHERE se.scan_session_id = ?
                    AND se.order_id IS NOT NULL
                ) o ON op.order_id = o.order_id
                GROUP BY op.order_id, op.product_id
            ) op

            LEFT JOIN
            (
                SELECT
                    order_id,
                    product_id,
                    COUNT(*) scanned_qty
                FROM scan_events
                WHERE scan_session_id = ?
                AND order_id IS NOT NULL
                GROUP BY order_id, product_id
            ) se

            ON op.order_id = se.order_id
            AND op.product_id = se.product_id
            ", [
                $this->scanSessionId,
                $this->scanSessionId,
                $this->scanSessionId
            ]);

            /*
            |--------------------------------------------------------------------------
            | Insertar productos extras
            |--------------------------------------------------------------------------
            */

            DB::insert("
                INSERT INTO scan_product_results
                (scan_session_id, order_id, product_id, expected_qty, scanned_qty, status, created_at)

                SELECT
                    ?,
                    se.order_id,
                    se.product_id,
                    0,
                    COUNT(*),
                    'EXTRA',
                    NOW()

                FROM scan_events se

                LEFT JOIN order_products op
                ON op.order_id = se.order_id
                AND op.product_id = se.product_id

                WHERE se.scan_session_id = ?
                AND se.order_id IS NOT NULL
                AND op.product_id IS NULL

                GROUP BY se.order_id, se.product_id
            ", [
                $this->scanSessionId,
                $this->scanSessionId
            ]);

            /*
            |--------------------------------------------------------------------------
            | Generar resultados por orden
            |--------------------------------------------------------------------------
            */

            DB::insert("
                INSERT INTO scan_session_results
                (
                    scan_session_id,
                    dock_id,
                    order_id,
                    expected_total,
                    scanned_total,
                    missing_total,
                    extra_total,
                    status,
                    created_at,
                    updated_at
                )

                SELECT
                    ?,
                    ?,
                    order_id,

                    SUM(expected_qty),
                    SUM(scanned_qty),

                    SUM(
                        CASE
                            WHEN scanned_qty < expected_qty
                            THEN expected_qty - scanned_qty
                            ELSE 0
                        END
                    ),

                    SUM(
                        CASE
                            WHEN scanned_qty > expected_qty
                            THEN scanned_qty - expected_qty
                            ELSE 0
                        END
                    ),

                    CASE
                        WHEN SUM(CASE WHEN scanned_qty < expected_qty THEN 1 ELSE 0 END) = 0
                        AND SUM(CASE WHEN scanned_qty > expected_qty THEN 1 ELSE 0 END) = 0
                        THEN 'COMPLETE'

                        WHEN SUM(CASE WHEN scanned_qty < expected_qty THEN 1 ELSE 0 END) > 0
                        AND SUM(CASE WHEN scanned_qty > expected_qty THEN 1 ELSE 0 END) = 0
                        THEN 'INCOMPLETE'

                        WHEN SUM(CASE WHEN scanned_qty < expected_qty THEN 1 ELSE 0 END) = 0
                        AND SUM(CASE WHEN scanned_qty > expected_qty THEN 1 ELSE 0 END) > 0
                        THEN 'OVER_SCANNED'

                        ELSE 'PARTIAL'
                    END,

                    NOW(),
                    NOW()

                FROM scan_product_results
                WHERE scan_session_id = ?
                GROUP BY order_id
            ", [
                $this->scanSessionId,
                $dockId,
                $this->scanSessionId
            ]);

            /*
            |--------------------------------------------------------------------------
            | Extras globales
            |--------------------------------------------------------------------------
            */

            $globalExtras = DB::table('scan_events')
                ->where('scan_session_id', $this->scanSessionId)
                ->whereNull('order_id')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Totales globales
            |--------------------------------------------------------------------------
            */

            $sessionTotals = DB::table('scan_session_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->selectRaw('
                    SUM(expected_total) expected_total,
                    SUM(scanned_total) scanned_total,
                    SUM(missing_total) missing_total,
                    SUM(extra_total) extra_total
                ')
                ->first();

            $missingTotal = (int) ($sessionTotals->missing_total ?? 0);
            $extraTotal   = (int) ($sessionTotals->extra_total ?? 0) + $globalExtras;

            if ($missingTotal === 0 && $extraTotal === 0) {
                $status = 'COMPLETE';
            } elseif ($missingTotal > 0 && $extraTotal === 0) {
                $status = 'INCOMPLETE';
            } elseif ($missingTotal === 0 && $extraTotal > 0) {
                $status = 'OVER_SCANNED';
            } else {
                $status = 'PARTIAL';
            }

            DB::table('order_scan_sessions')
                ->where('scan_session_id', $this->scanSessionId)
                ->update([
                    'status' => $status,
                    'closed_at' => now(),
                    'updated_at' => now()
                ]);
        });
    }
}