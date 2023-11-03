<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Programming;
use App\Models\User;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programmings = Programming::all();
        // $events = Event::all();
        return view('admin.events.index', compact('programmings'));
    }

    public function indexRegister()
    {
        $programmings = Programming::all();
        return view('admin.events.registro', compact('programmings'));
    }

    public function users()
    {
        $users = User::where('id', '<>', 1);
        return view('admin.events.users', compact('users'));
    }



    public function create()
    {
        return view('admin.events.create');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }


    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function inform()
    {
        return view('admin.events.inform');
    }

    public function readQr(Request $request)
    {
        $token = $request->token;
        return view('admin.events.qr', compact('token'));
    }
    public function readQrWithImages()
    {
        return view('admin.events.qrimage');
    }
}
