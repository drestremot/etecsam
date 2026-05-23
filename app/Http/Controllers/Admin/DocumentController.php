<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::orderBy('category')->orderBy('title')->paginate(20);
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.form', ['document' => new Document(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'file'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'url'      => 'nullable|url|max:500',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('documents', 'public');
        }

        Document::create($data);
        return redirect()->route('admin.documents.index')->with('success', 'Documento cadastrado com sucesso!');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.form', compact('document') + ['action' => 'edit']);
    }

    public function update(Request $request, Document $document)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'file'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'url'      => 'nullable|url|max:500',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            if (!empty($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $data['file_path'] = $request->file('file')->store('documents', 'public');
        }

        $document->update($data);
        return redirect()->route('admin.documents.index')->with('success', 'Documento atualizado!');
    }

    public function destroy(Document $document)
    {
        if (!empty($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return redirect()->route('admin.documents.index')->with('success', 'Documento removido!');
    }
}
