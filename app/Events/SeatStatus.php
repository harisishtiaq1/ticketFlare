<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $seat;
    


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($seat)
    {
        $this->seat = $seat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('seatStatusChannel');
    }
    
    /**
     * broadcastAs
     *
     * @return void
     */
    public function broadcastAs()
    {
        return 'seat-status-event';
    }
}
