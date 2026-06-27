<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSlideController extends Controller
{
    public function index()
    {
        $homeSlides = HomeSlide::orderBy('order')->orderByDesc('is_active')->get();
        return view('admin.home-slides.index', compact('homeSlides'));
    }

    public function toggle(HomeSlide $homeSlide)
    {
        $homeSlide->update(['is_active' => !$homeSlide->is_active]);
        return back()->with('success', '"' . $homeSlide->title . '" ' . ($homeSlide->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        return view('admin.home-slides.form', ['homeSlide' => new HomeSlide(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image'       => 'required|image|max:6144',
            'order'       => 'nullable|integer|min:0',
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['image'] = $request->file('image')->store('home-slides', 'public');

        HomeSlide::create($data);
        return redirect()->route('admin.home-slides.index')->with('success', 'Slide cadastrado com sucesso!');
    }

    public function edit(HomeSlide $homeSlide)
    {
        return view('admin.home-slides.form', compact('homeSlide') + ['action' => 'edit']);
    }

    public function update(Request $request, HomeSlide $homeSlide)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|max:6144',
            'order'       => 'nullable|integer|min:0',
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($homeSlide->image);
            $data['image'] = $request->file('image')->store('home-slides', 'public');
        }

        $homeSlide->update($data);
        return redirect()->route('admin.home-slides.index')->with('success', 'Slide atualizado com sucesso!');
    }

    public function destroy(HomeSlide $homeSlide)
    {
        Storage::disk('public')->delete($homeSlide->image);
        $homeSlide->delete();
        return redirect()->route('admin.home-slides.index')->with('success', 'Slide removido!');
    }
}
