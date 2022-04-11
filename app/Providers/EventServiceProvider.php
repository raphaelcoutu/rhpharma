<?php

namespace App\Providers;

use App\Events\UpdateBuildStatus;
use App\Listeners\BuildStatusChanged;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(UpdateBuildStatus::class, BuildStatusChanged::class);
    }
}
