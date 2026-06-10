<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteTheme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    private $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Marco', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];

    public function index()
    {
        $themes = SiteTheme::orderBy('name')->get();
        $months = $this->months;
        return view('admin.themes.index', compact('themes', 'months'));
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
        $request->validate([
            'name'            => 'required|string|max:100',
            'slug'            => 'required|string|max:100|unique:site_themes',
            'month'           => 'nullable|integer|min:1|max:12',
            'primary_color'   => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color'    => 'required|string|max:20',
            'text_color'      => 'required|string|max:20',
        ]);

        SiteTheme::create($request->only([
            'name','slug','month','primary_color','secondary_color','accent_color','text_color',
        ]) + ['active' => false]);

        return redirect()->route('admin.themes.index')->with('success', 'Tema criado com sucesso!');
    }

    public function edit(SiteTheme $theme)
    {
        $title  = 'Editar Tema: ' . $theme->name;
        $months = $this->months;
        return view('admin.themes.form', compact('theme', 'title', 'months'));
    }

    public function update(Request $request, SiteTheme $theme)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'month'           => 'nullable|integer|min:1|max:12',
            'primary_color'   => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color'    => 'required|string|max:20',
            'text_color'      => 'required|string|max:20',
        ]);

        $theme->update($request->only([
            'name','month','primary_color','secondary_color','accent_color','text_color',
        ]));

        return redirect()->route('admin.themes.index')->with('success', 'Tema atualizado!');
    }

    public function destroy(SiteTheme $theme)
    {
        if ($theme->active) {
            return back()->with('error', 'Nao e possivel excluir o tema ativo.');
        }
        $theme->delete();
        return redirect()->route('admin.themes.index')->with('success', 'Tema removido.');
    }

    public function activate(SiteTheme $theme)
    {
        SiteTheme::deactivateAll();
        $theme->update(['active' => true]);
        return redirect()->route('admin.themes.index')->with('success', 'Tema "' . $theme->name . '" ativado!');
    }

    public function deactivate()
    {
        SiteTheme::deactivateAll();
        return redirect()->route('admin.themes.index')->with('success', 'Tema padrao restaurado.');
    }
}
