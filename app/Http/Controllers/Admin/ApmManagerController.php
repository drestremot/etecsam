<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApmManagerController extends Controller
{
    public function index()
    {
        $apmManagers = ApmManager::orderByDesc('is_active')->orderBy('name')->get();
        return view('admin.apm-managers.index', compact('apmManagers'));
    }

    public function toggle(ApmManager $apmManager)
    {
        $apmManager->update(['is_active' => !$apmManager->is_active]);
        $statusStr = $apmManager->is_active ? 'ativado(a)' : 'desativado(a)';
        return back()->with('success', "Gestor(a) \"{$apmManager->name}\" {$statusStr} com sucesso.");
    }

    public function create()
    {
        return redirect()->route('admin.apm-managers.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:30',
            'photo'     => 'nullable|image|max:4096',
            'is_active' => 'nullable',
        ], [
            'name.required' => 'O nome do gestor / membro da APM é obrigatório.',
            'role.required' => 'O cargo na APM é obrigatório.',
            'email.email'   => 'Insira um endereço de e-mail válido.',
            'photo.image'   => 'O arquivo enviado deve ser uma imagem válida (JPG, PNG, WebP).',
            'photo.max'     => 'A foto não pode ultrapassar 4 MB.',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('apm-managers', 'public');
        }

        ApmManager::create($data);

        return redirect()->route('admin.apm-managers.index')->with('success', 'Gestor(a) da APM cadastrado(a) com sucesso!');
    }

    public function edit(ApmManager $apmManager)
    {
        return redirect()->route('admin.apm-managers.index');
    }

    public function update(Request $request, ApmManager $apmManager)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:30',
            'photo'     => 'nullable|image|max:4096',
            'is_active' => 'nullable',
        ], [
            'name.required' => 'O nome do gestor / membro da APM é obrigatório.',
            'role.required' => 'O cargo na APM é obrigatório.',
            'email.email'   => 'Insira um endereço de e-mail válido.',
            'photo.image'   => 'O arquivo enviado deve ser uma imagem válida (JPG, PNG, WebP).',
            'photo.max'     => 'A foto não pode ultrapassar 4 MB.',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : $apmManager->is_active;

        if ($request->hasFile('photo')) {
            if ($apmManager->photo && Storage::disk('public')->exists($apmManager->photo)) {
                Storage::disk('public')->delete($apmManager->photo);
            }
            $data['photo'] = $request->file('photo')->store('apm-managers', 'public');
        }

        $apmManager->update($data);

        return redirect()->route('admin.apm-managers.index')->with('success', 'Cadastro do(a) gestor(a) da APM atualizado com sucesso!');
    }

    public function destroy(ApmManager $apmManager)
    {
        if ($apmManager->photo && Storage::disk('public')->exists($apmManager->photo)) {
            Storage::disk('public')->delete($apmManager->photo);
        }
        $name = $apmManager->name;
        $apmManager->delete();

        return redirect()->route('admin.apm-managers.index')->with('success', "Gestor(a) \"{$name}\" removido(a) com sucesso!");
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um membro para executar a ação em lote.');
        }

        switch ($action) {
            case 'activate':
                ApmManager::whereIn('id', $ids)->update(['is_active' => true]);
                return back()->with('success', count($ids) . ' gestor(es) da APM ativado(s) com sucesso!');

            case 'deactivate':
                ApmManager::whereIn('id', $ids)->update(['is_active' => false]);
                return back()->with('success', count($ids) . ' gestor(es) da APM desativado(s) com sucesso!');

            case 'delete':
                $managers = ApmManager::whereIn('id', $ids)->get();
                foreach ($managers as $m) {
                    if ($m->photo && Storage::disk('public')->exists($m->photo)) {
                        Storage::disk('public')->delete($m->photo);
                    }
                    $m->delete();
                }
                return back()->with('success', count($ids) . ' gestor(es) da APM excluído(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
