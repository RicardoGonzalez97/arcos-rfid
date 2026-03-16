<?php

namespace App\Jobs;

use App\Services\PurchaseOrderNormalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NormalizeDockOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $dockId;

    public $timeout = 120;
    public $tries = 3;

    public function __construct(int $dockId)
    {
        $this->dockId = $dockId;
    }

    public function handle(PurchaseOrderNormalizer $normalizer): void
    {
        logger()->info("Queue normalization started", [
            'dock_id' => $this->dockId
        ]);

        DB::table('dock_purchase_orders')
            ->where('dock_id', $this->dockId)
            ->orderBy('purchase_order_id')
            ->chunkById(50, function ($rows) use ($normalizer) {

                foreach ($rows as $row) {

                    try {

                        $normalizer->normalize($row->purchase_order_id);

                    } catch (\Throwable $e) {

                        logger()->error('Purchase order normalization failed', [
                            'purchase_order_id' => $row->purchase_order_id,
                            'error' => $e->getMessage()
                        ]);

                    }
                }

            }, 'purchase_order_id');

        logger()->info("Queue normalization finished", [
            'dock_id' => $this->dockId
        ]);
    }
}