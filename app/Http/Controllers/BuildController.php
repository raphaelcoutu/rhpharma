<?php

namespace App\Http\Controllers;

use App\Events\UpdateBuildStatus;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    public function buildClinical(int $scheduleId)
    {
        event(new UpdateBuildStatus($scheduleId, 'clinical', 3));

        return response("OK", 200);
    }
}
