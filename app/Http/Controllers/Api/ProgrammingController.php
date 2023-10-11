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
        $programmingEvent =  Programming::where('event_id', $event)
            ->select('id', 'quota_available', 'initial_date', 'initial_time')
            ->where('waiting','<>',1)
            ->where('initial_date',$date)
            ->get();

            if(count($programmingEvent) <= 0){
                return response()->json([
                    'status' => false,
                    'errors' => ['event not found']
                ], 400);
            }
        return $programmingEvent;
    }
   

    public function updateStat(Request $request)
    {
        return Programming::findOrFail($request->id)->update(['state' => $request->state]);
    }
}
