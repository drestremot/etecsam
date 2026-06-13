<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    private $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',  4 => 'Abril',
        5 => 'Maio',    6 => 'Junho',     7 => 'Julho',   8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];

    public function index()
    {
        $themes = SiteTheme::orderBy('month')->orderBy('name')->get();
        $months = $this->months;
        $active = SiteTheme::getActive();
        return view('admin.themes.index', compact('themes', 'months', 'active'));
    }

    public function create()
    {
        $theme  = new SiteTheme();
        $title  = 'Novo Tema';
        $months = $this->months;
        return view('admin.themes.form', compact('theme', 'title', 'months'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'slug'            => 'required|string|max:100|unique:site_themes',
            'month'           => 'nullable|integer|min:1|max:12',
            'primary_color'   => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color'    => 'required|string|max:20',
            'text_color'      => 'required|string|max:20',
            'description'     => 'nullable|string|max:2000',
            'popup_image'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('popup_image')) {
            $data['popup_image'] = $request->file('popup_image')->store('themes', 'public');
        }

        SiteTheme::create(array_merge($data, ['active' => false]));

        return redirect()->route('admin.themes.index')->with('success', 'Tema criado com sucesso!');
    }

    public function edit(SiteTheme $theme)
    {
        $title  = 'Editar: ' . $theme->name;
        $months = $this->months;
        return view('admin.themes.form', compact('theme', 'title', 'months'));
    }

    public function update(Request $request, SiteTheme $theme)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'month'           => 'nullable|integer|min:1|max:12',
            'primary_color'   => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color'    => 'required|string|max:20',
            'text_color'      => 'required|string|max:20',
            'description'     => 'nullable|string|max:2000',
            'popup_image'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('popup_image')) {
            if ($theme->popup_image) {
                Storage::disk('public')->delete($theme->popup_image);
            }
            $data['popup_image'] = $request->file('popup_image')->store('themes', 'public');
        }

        $theme->update($data);

        return redirect()->route('admin.themes.index')->with('success', 'Tema atualizado!');
    }

    public function destroy(SiteTheme $theme)
    {
        if ($theme->active) {
            return back()->with('error', 'Não é possível excluir o tema ativo. Desative-o primeiro.');
        }
        if ($theme->popup_image) {
            Storage::disk('public')->delete($theme->popup_image);
        }
        $theme->delete();
        return redirect()->route('admin.themes.index')->with('success', 'Tema removido.');
    }

    public function activate(SiteTheme $theme)
    {
        SiteTheme::deactivateAll();
        $theme->update(['active' => true]);
        return redirect()->route('admin.themes.index')
            ->with('success', 'Tema "' . $theme->name . '" ativado! O popup será exibido aos visitantes.');
    }

    public function deactivate()
    {
        SiteTheme::deactivateAll();
        return redirect()->route('admin.themes.index')->with('success', 'Cores padrão do site restauradas.');
    }
}
