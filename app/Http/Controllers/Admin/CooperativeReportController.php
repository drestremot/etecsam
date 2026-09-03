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
        $cooperativeReports = CooperativeReport::orderBy('category')->orderByDesc('published_at')->get();
        return view('admin.cooperative-reports.index', compact('cooperativeReports'));
    }

    public function create()
    {
        return redirect()->route('admin.cooperative-reports.index');
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
        ], [
            'title.required'    => 'O título do relatório/documento é obrigatório.',
            'category.required' => 'Selecione a categoria do documento.',
            'category.in'       => 'Categoria inválida.',
            'file.mimes'        => 'O arquivo deve ser nos formatos: PDF, DOC, DOCX, XLS ou XLSX.',
            'file.max'          => 'O arquivo não pode ultrapassar 10MB.',
            'url.url'           => 'Informe uma URL válida com http:// ou https://',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('cooperative-reports', 'public');
        }

        CooperativeReport::create($data);
        return redirect()->route('admin.cooperative-reports.index')->with('success', 'Documento da Cooperativa cadastrado com sucesso!');
    }

    public function edit(CooperativeReport $cooperativeReport)
    {
        return redirect()->route('admin.cooperative-reports.index');
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
        ], [
            'title.required'    => 'O título do relatório/documento é obrigatório.',
            'category.required' => 'Selecione a categoria do documento.',
            'category.in'       => 'Categoria inválida.',
            'file.mimes'        => 'O arquivo deve ser nos formatos: PDF, DOC, DOCX, XLS ou XLSX.',
            'file.max'          => 'O arquivo não pode ultrapassar 10MB.',
            'url.url'           => 'Informe uma URL válida com http:// ou https://',
        ]);

        if ($request->hasFile('file')) {
            if (!empty($cooperativeReport->file_path)) {
                Storage::disk('public')->delete($cooperativeReport->file_path);
            }
            $data['file_path'] = $request->file('file')->store('cooperative-reports', 'public');
        }

        $cooperativeReport->update($data);
        return redirect()->route('admin.cooperative-reports.index')->with('success', 'Documento da Cooperativa atualizado com sucesso!');
    }

    public function destroy(CooperativeReport $cooperativeReport)
    {
        $title = $cooperativeReport->title;
        if (!empty($cooperativeReport->file_path)) {
            Storage::disk('public')->delete($cooperativeReport->file_path);
        }
        $cooperativeReport->delete();
        return redirect()->route('admin.cooperative-reports.index')->with('success', "Documento \"{$title}\" removido com sucesso!");
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um documento para a ação em lote.');
        }

        if ($action === 'delete') {
            $reports = CooperativeReport::whereIn('id', $ids)->get();
            foreach ($reports as $report) {
                if (!empty($report->file_path)) {
                    Storage::disk('public')->delete($report->file_path);
                }
                $report->delete();
            }
            return back()->with('success', count($ids) . ' documento(s) excluído(s) com sucesso!');
        }

        return back()->with('error', 'Ação em lote inválida.');
    }
}
