<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trxId;
    public $sender;
    public $message;
    public $timestamp;

    /**
     * Create a new event instance.
     *
     * @param string $trxId
     * @param string $sender ('buyer' or 'seller')
     * @param string $message
     * @param string $timestamp
     */
    public function __construct(string $trxId, string $sender, string $message, string $timestamp)
    {
        $this->trxId = $trxId;
        $this->sender = $sender;
        $this->message = $message;
        $this->timestamp = $timestamp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat.' . $this->trxId), // Private channel untuk setiap percakapan
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string|null
     */
    public function broadcastAs(): ?string
    {
        return 'chat.message.new';
    }
}