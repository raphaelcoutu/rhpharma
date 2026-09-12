<?php

namespace App\Listeners;

use App\Events\BuildMessageGenerated;
use App\Models\BuildMessage;

class StoreBuildMessage
{
    public function handle(BuildMessageGenerated $event): void
    {
        BuildMessage::create([
            'schedule_id' => $event->schedule->id,
            'message' => $event->message,
        ]);
    }
}
