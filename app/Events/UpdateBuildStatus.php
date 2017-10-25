<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateBuildStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $schedule;
    public $buildStep;
    public $buildStatus;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($schedule, $buildStep, $buildStatus)
    {
        $this->schedule = $schedule;
        $this->buildStep = $buildStep;
        $this->statusId = $buildStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('build-status');
    }
}
