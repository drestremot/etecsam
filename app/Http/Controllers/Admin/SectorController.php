<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = Sector::orderBy('name')->paginate(20);
        return view('admin.sectors.index', compact('sectors'));
    }

    public function create()
    {
        return view('admin.sectors.form', ['sector' => new Sector(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'summary'     => 'required|string',
            'description' => 'nullable|string',
            'icon'        => 'required|string|max:50',
            'images'      => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['images'] = $data['images'] ? array_filter(array_map('trim', explode("\n", $data['images']))) : null;

        Sector::create($data);
        return redirect()->route('admin.sectors.index')->with('success', 'Setor cadastrado com sucesso!');
    }

    public function edit(Sector $sector)
    {
        return view('admin.sectors.form', compact('sector') + ['action' => 'edit']);
    }

    public function update(Request $request, Sector $sector)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'summary'     => 'required|string',
            'description' => 'nullable|string',
            'icon'        => 'required|string|max:50',
            'images'      => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['images'] = $data['images'] ? array_filter(array_map('trim', explode("\n", $data['images']))) : null;

        $sector->update($data);
        return redirect()->route('admin.sectors.index')->with('success', 'Setor atualizado!');
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();
        return redirect()->route('admin.sectors.index')->with('success', 'Setor removido!');
    }
}
