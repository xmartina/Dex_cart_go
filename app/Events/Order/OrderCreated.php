<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated
{
    use Dispatchable, SerializesModels;

    public $order;
    public $notify_customer;

    /**
     * Create a new job instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order, $notify_customer = null)
    {
        $order->load('inventories.attachments');

        $this->order = $order;
        $this->notify_customer = $notify_customer;
    }
}
