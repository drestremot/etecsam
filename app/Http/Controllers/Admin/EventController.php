<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_date', 'desc')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.form', ['event' => new Event(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'location'   => 'nullable|string|max:255',
            'color'      => 'nullable|string|max:20',
        ]);

        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Evento cadastrado com sucesso!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.form', compact('event') + ['action' => 'edit']);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'location'   => 'nullable|string|max:255',
            'color'      => 'nullable|string|max:20',
        ]);

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Evento atualizado!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento removido!');
    }
}
