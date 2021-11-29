<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CutiPrintEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $nip, $no_cuti;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($nip,$no_cuti)
    {
        $this->nip = $nip;
        $this->no_cuti = $no_cuti;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
