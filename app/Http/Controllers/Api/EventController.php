<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::select('events.id','events.name','programmings.initial_date')
        ->selectRaw('SUM(programmings.quota_available) as quota_available')
        ->join('programmings', 'events.id', '=', 'programmings.event_id')
        ->where('programmings.waiting','<>',1)
        ->groupBy('events.id','programmings.initial_date','events.name')
        ->get();
        return $events;
    }

}
