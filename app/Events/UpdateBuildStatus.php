<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateBuildStatus
 * @package App\Events
 */
class UpdateBuildStatus implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scheduleId;
    public $buildStep;
    public $status;
    public $message;

    /**
     * Create a new event instance.
     *
     * Status :
     * - 0 : En attente
     * - 1 : Succès
     * - 2 : Erreur
     * - 3 : En cours
     *
     * @return void
     */
    public function __construct($scheduleId, $buildStep, $status, $message = null)
    {
        $this->scheduleId = $scheduleId;
        $this->buildStep = $buildStep;
        $this->status = $status;
        $this->message = $message;
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
