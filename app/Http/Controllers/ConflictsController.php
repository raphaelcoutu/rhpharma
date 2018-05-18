<?php

namespace App\Http\Controllers;

use App\Conflict;

class ConflictsController extends Controller
{
    public function fetch($scheduleId)
    {
        return Conflict::with('department')->where('schedule_id', $scheduleId)->get();
    }
}
