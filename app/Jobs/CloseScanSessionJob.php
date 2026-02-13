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

         
            $orderId = DB::table('order_scan_sessions')
                ->where('scan_session_id', $this->scanSessionId)
                ->value('order_id');

            if (!$orderId) {
                logger("No order found for scan_session_id {$this->scanSessionId}");
                return;
            }
            DB::table('scan_product_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();

            DB::table('scan_session_results')
                ->where('scan_session_id', $this->scanSessionId)
                ->delete();


          
            $expected = DB::table('order_products')
                ->where('order_id', $orderId)
                ->select(
                    'product_id',
                    DB::raw('SUM(quantity) as expected_qty')
                )
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');

          
            $scanned = DB::table('scan_events')
                ->where('scan_session_id', $this->scanSessionId)
                ->whereNotNull('product_id')
                ->select(
                    'product_id',
                    DB::raw('COUNT(*) as scanned_qty')
                )
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');

            $results = [];

            foreach ($expected as $productId => $exp) {

                $scannedQty = $scanned[$productId]->scanned_qty ?? 0;

                if ($scannedQty < $exp->expected_qty) {
                    $status = 'MISSING';
                } elseif ($scannedQty > $exp->expected_qty) {
                    $status = 'EXTRA';
                } else {
                    $status = 'OK';
                }

                $results[] = [
                    'scan_session_id' => $this->scanSessionId,
                    'order_id'        => $orderId,
                    'product_id'      => $productId,
                    'expected_qty'    => $exp->expected_qty,
                    'scanned_qty'     => $scannedQty,
                    'status'          => $status,
                ];
            }

            foreach ($scanned as $productId => $scan) {
                if (!isset($expected[$productId])) {
                    $results[] = [
                        'scan_session_id' => $this->scanSessionId,
                        'order_id'        => $orderId,
                        'product_id'      => $productId,
                        'expected_qty'    => 0,
                        'scanned_qty'     => $scan->scanned_qty,
                        'status'          => 'EXTRA',
                    ];
                }
            }

        if (empty($results)) {
            logger("Scan session has no products", [
                'scan_session_id' => $this->scanSessionId
            ]);
            return;
        }

        DB::table('scan_product_results')->insert($results);

        $totals = DB::table('scan_product_results')
        ->where('scan_session_id', $this->scanSessionId)
        ->selectRaw('
            SUM(expected_qty) as expected_total,
            SUM(scanned_qty) as scanned_total,
            SUM(CASE WHEN scanned_qty < expected_qty THEN expected_qty - scanned_qty ELSE 0 END) as missing_total,
            SUM(CASE WHEN scanned_qty > expected_qty THEN scanned_qty - expected_qty ELSE 0 END) as extra_total
        ')
        ->first();

        $status = ($totals->missing_total == 0 && $totals->extra_total == 0)
            ? 'OK'
            : 'PARTIAL';

 
    DB::table('scan_session_results')->insert([
        'scan_session_id' => $this->scanSessionId,
        'order_id'        => $orderId,
        'expected_total'  => $totals->expected_total,
        'scanned_total'   => $totals->scanned_total,
        'missing_total'   => $totals->missing_total,
        'extra_total'     => $totals->extra_total,
        'status'          => $status,
    ]);

    DB::table('order_scan_sessions')
    ->where('scan_session_id', $this->scanSessionId)
    ->update([
        'status' => 'CLOSED'
    ]);

    logger("Scan session summarized", [
        'scan_session_id' => $this->scanSessionId,
        'status' => $status
    ]);

        });
    }
}
