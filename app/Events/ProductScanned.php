<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ProductScanned implements ShouldBroadcastNow
{
    public $data;
    public $dockId;

    public function __construct($data, $dockId)
    {
        $this->data = $data;
        $this->dockId = $dockId;
    }

    public function broadcastOn()
    {
        return new Channel('scan-session.' . $this->dockId);
    }

    public function broadcastAs()
    {
        return 'ProductScanned';
    }

     public function broadcastWith()
    {
        return $this->data;
    }
}