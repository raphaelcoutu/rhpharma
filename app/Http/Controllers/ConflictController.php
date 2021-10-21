<?php

namespace App\Http\Controllers;

use App\Models\Conflict;

class ConflictController extends Controller
{
    public function fetch($scheduleId)
    {
        return Conflict::with('department')->where('schedule_id', $scheduleId)->get();
    }
}
