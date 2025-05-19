<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $productId;
    public $newStock;

    /**
     * Create a new event instance.
     *
     * @param int $productId
     * @param int $newStock
     */
    public function __construct(string $productId, string $newStock)
    {
        $this->productId = $productId;
        $this->newStock = $newStock;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('product.' . $this->productId), // Channel spesifik untuk setiap produk
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string|null
     */
    public function broadcastAs(): ?string
    {
        return 'product.stock.updated';
    }
}