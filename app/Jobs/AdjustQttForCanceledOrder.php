<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdjustQttForCanceledOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 5;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 1200;

    public $order;

    public $cancelled_items;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $cancelled_items = null)
    {
        $this->order = $order;
        $this->cancelled_items = $cancelled_items;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->order->inventories as $item) {
            // Increase stock_quantities of canceled items
            if (
                !$this->cancelled_items ||
                (is_array($this->cancelled_items) && in_array($item->id, $this->cancelled_items))
            ) {
                $item->increment('stock_quantity', $item->pivot->quantity);
            }
        }
    }
}
