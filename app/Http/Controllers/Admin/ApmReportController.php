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
        $apmReports = ApmReport::orderBy('category')->orderByDesc('published_at')->get();
        return view('admin.apm-reports.index', compact('apmReports'));
    }

    public function create()
    {
        return redirect()->route('admin.apm-reports.index');
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
            'title.required'    => 'O título do documento é obrigatório.',
            'category.required' => 'Selecione uma categoria válida para o documento.',
            'file.mimes'        => 'O arquivo deve ser do tipo PDF, DOC, DOCX, XLS ou XLSX.',
            'file.max'          => 'O tamanho máximo do arquivo é de 10 MB.',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('apm-reports', 'public');
        }

        ApmReport::create($data);
        return redirect()->route('admin.apm-reports.index')->with('success', 'Documento da APM publicado com sucesso!');
    }

    public function edit(ApmReport $apmReport)
    {
        return redirect()->route('admin.apm-reports.index');
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
        ], [
            'title.required'    => 'O título do documento é obrigatório.',
            'category.required' => 'Selecione uma categoria válida para o documento.',
            'file.mimes'        => 'O arquivo deve ser do tipo PDF, DOC, DOCX, XLS ou XLSX.',
            'file.max'          => 'O tamanho máximo do arquivo é de 10 MB.',
        ]);

        if ($request->hasFile('file')) {
            if (!empty($apmReport->file_path) && Storage::disk('public')->exists($apmReport->file_path)) {
                Storage::disk('public')->delete($apmReport->file_path);
            }
            $data['file_path'] = $request->file('file')->store('apm-reports', 'public');
        }

        $apmReport->update($data);
        return redirect()->route('admin.apm-reports.index')->with('success', 'Documento da APM atualizado!');
    }

    public function destroy(ApmReport $apmReport)
    {
        if (!empty($apmReport->file_path) && Storage::disk('public')->exists($apmReport->file_path)) {
            Storage::disk('public')->delete($apmReport->file_path);
        }
        $title = $apmReport->title;
        $apmReport->delete();
        return redirect()->route('admin.apm-reports.index')->with('success', "Documento \"{$title}\" removido!");
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um documento.');
        }

        if ($action === 'delete') {
            $reports = ApmReport::whereIn('id', $ids)->get();
            foreach ($reports as $r) {
                if (!empty($r->file_path) && Storage::disk('public')->exists($r->file_path)) {
                    Storage::disk('public')->delete($r->file_path);
                }
                $r->delete();
            }
            return back()->with('success', count($ids) . ' documento(s) da APM excluído(s) com sucesso!');
        }

        return back()->with('error', 'Ação inválida.');
    }
}
