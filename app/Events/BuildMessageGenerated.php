<?php

namespace App\Events;

use App\Models\Schedule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuildMessageGenerated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timestamp;
    public $schedule;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param Schedule $schedule
     * @param $message
     * @param bool $clear
     */
    public function __construct(Schedule $schedule, $message, bool $clear = false)
    {
        $this->timestamp = date('Y/m/d H:i:s');
        $this->schedule = $schedule;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('build-message');
    }
}
