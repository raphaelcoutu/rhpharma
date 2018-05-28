<?php

namespace App\Http\Controllers;

use App\Events\UpdateBuildStatus;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    public function updateStatus(Request $request)
    {
        event(new UpdateBuildStatus($request->scheduleId, $request->buildStep, $request->status));

        return response("OK", 200);
    }
}
