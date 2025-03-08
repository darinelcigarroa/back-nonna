<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $orderItems;

    public function __construct($orderItems)
    {
        $this->orderItems = $orderItems;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('orders');
    }

    public function broadcastAs()
    {
        return 'OrderStatusUpdated';
    }
}
