<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UpdateBuildStatus
{
    use Dispatchable;

    public function __construct(
        public int $scheduleId,
        public string $buildStep,
        public int $status,
        public ?string $message = null,
    ) {}
}
