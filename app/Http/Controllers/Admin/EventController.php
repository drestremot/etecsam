<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('is_active')->orderBy('start_date', 'desc')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function toggle(Event $event)
    {
        $event->update(['is_active' => !$event->is_active]);
        $status = $event->is_active ? 'ativado' : 'desativado';
        return back()->with('success', '"' . $event->title . '" ' . $status . '.');
    }

    public function create()
    {
        return view('admin.events.form', ['event' => new Event(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'location'     => 'nullable|string|max:255',
            'color'        => 'nullable|string|max:20',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|max:4096',
            'captions'     => 'nullable|array',
            'captions.*'   => 'nullable|string|max:255',
        ]);

        $event = Event::create($data);

        $this->savePhotos($event, $request);

        return redirect()->route('admin.events.index')->with('success', 'Evento cadastrado com sucesso!');
    }

    public function edit(Event $event)
    {
        $event->load('photos');
        return view('admin.events.form', compact('event') + ['action' => 'edit']);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'location'     => 'nullable|string|max:255',
            'color'        => 'nullable|string|max:20',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|max:4096',
            'captions'     => 'nullable|array',
            'captions.*'   => 'nullable|string|max:255',
        ]);

        $event->update($data);

        $this->savePhotos($event, $request);

        return redirect()->route('admin.events.index')->with('success', 'Evento atualizado!');
    }

    public function destroy(Event $event)
    {
        foreach ($event->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento removido!');
    }

    public function destroyPhoto(EventPhoto $photo)
    {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
        return back()->with('success', 'Foto removida!');
    }

    private function savePhotos(Event $event, Request $request): void
    {
        if (!$request->hasFile('photos')) {
            return;
        }

        $order = $event->photos()->max('order') + 1;

        foreach ($request->file('photos') as $index => $file) {
            $path = $file->store('events', 'public');
            $event->photos()->create([
                'path'    => $path,
                'caption' => $request->input("captions.$index"),
                'order'   => $order++,
            ]);
        }
    }
}
