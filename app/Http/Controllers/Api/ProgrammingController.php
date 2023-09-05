<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Programming;
use Illuminate\Http\Request;

class ProgrammingController extends Controller
{
  

    public function showEvent(Request $request)
    {
        $event = $request->eventId;
        $date = $request->date;
        return Programming::where('event_id', $event)
            ->select('id', 'quota_available', 'initial_date')
            ->where('waiting','<>',1)
            ->where('initial_date',$date)
            ->get();
        //return $programmingEvent;
    }
   

    public function updateStat(Request $request)
    {
        return Programming::findOrFail($request->id)->update(['state' => $request->state]);
    }
}
