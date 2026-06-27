<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApmReportController extends Controller
{
    public function index()
    {
        $apmReports = ApmReport::orderBy('category')->orderByDesc('published_at')->paginate(20);
        return view('admin.apm-reports.index', compact('apmReports'));
    }

    public function create()
    {
        return view('admin.apm-reports.form', ['apmReport' => new ApmReport(), 'action' => 'create']);
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
            $data['file_path'] = $request->file('file')->store('apm-reports', 'public');
        }

        ApmReport::create($data);
        return redirect()->route('admin.apm-reports.index')->with('success', 'Documento cadastrado com sucesso!');
    }

    public function edit(ApmReport $apmReport)
    {
        return view('admin.apm-reports.form', compact('apmReport') + ['action' => 'edit']);
    }

    public function update(Request $request, ApmReport $apmReport)
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
            if (!empty($apmReport->file_path)) {
                Storage::disk('public')->delete($apmReport->file_path);
            }
            $data['file_path'] = $request->file('file')->store('apm-reports', 'public');
        }

        $apmReport->update($data);
        return redirect()->route('admin.apm-reports.index')->with('success', 'Documento atualizado!');
    }

    public function destroy(ApmReport $apmReport)
    {
        if (!empty($apmReport->file_path)) {
            Storage::disk('public')->delete($apmReport->file_path);
        }
        $apmReport->delete();
        return redirect()->route('admin.apm-reports.index')->with('success', 'Documento removido!');
    }
}
