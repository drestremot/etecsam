<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CooperativeReportController extends Controller
{
    public function index()
    {
        $cooperativeReports = CooperativeReport::orderBy('category')->orderByDesc('published_at')->paginate(20);
        return view('admin.cooperative-reports.index', compact('cooperativeReports'));
    }

    public function create()
    {
        return view('admin.cooperative-reports.form', ['cooperativeReport' => new CooperativeReport(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|in:Estatuto,Ata de Reunião,Prestação de Contas',
            'period'       => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
            'file'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'url'          => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('cooperative-reports', 'public');
        }

        CooperativeReport::create($data);
        return redirect()->route('admin.cooperative-reports.index')->with('success', 'Documento cadastrado com sucesso!');
    }

    public function edit(CooperativeReport $cooperativeReport)
    {
        return view('admin.cooperative-reports.form', compact('cooperativeReport') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeReport $cooperativeReport)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|in:Estatuto,Ata de Reunião,Prestação de Contas',
            'period'       => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
            'file'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'url'          => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('file')) {
            if (!empty($cooperativeReport->file_path)) {
                Storage::disk('public')->delete($cooperativeReport->file_path);
            }
            $data['file_path'] = $request->file('file')->store('cooperative-reports', 'public');
        }

        $cooperativeReport->update($data);
        return redirect()->route('admin.cooperative-reports.index')->with('success', 'Documento atualizado!');
    }

    public function destroy(CooperativeReport $cooperativeReport)
    {
        if (!empty($cooperativeReport->file_path)) {
            Storage::disk('public')->delete($cooperativeReport->file_path);
        }
        $cooperativeReport->delete();
        return redirect()->route('admin.cooperative-reports.index')->with('success', 'Documento removido!');
    }
}
