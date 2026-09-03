<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeDue;
use App\Models\CooperativeMember;
use App\Models\CooperativeMonthlyFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CooperativeMemberController extends Controller
{
    public function index()
    {
        $cooperativeMembers = CooperativeMember::orderByDesc('is_active')->orderBy('name')->get();
        return view('admin.cooperative-members.index', compact('cooperativeMembers'));
    }

    public function toggle(CooperativeMember $cooperativeMember)
    {
        $cooperativeMember->update(['is_active' => !$cooperativeMember->is_active]);
        $statusStr = $cooperativeMember->is_active ? 'ativado(a)' : 'desativado(a)';
        return back()->with('success', "Cooperado(a) \"{$cooperativeMember->name}\" {$statusStr} com sucesso.");
    }

    public function create()
    {
        return redirect()->route('admin.cooperative-members.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'registration_number'  => 'nullable|string|max:100',
            'phone'                => 'nullable|string|max:30',
            'email'                => 'nullable|email|max:255',
            'sex'                  => 'nullable|in:M,F',
            'guardian_name'        => 'nullable|string|max:255',
            'guardian_phone'       => 'nullable|string|max:30',
            'joined_at'            => 'nullable|date',
            'photo'                => 'nullable|image|max:4096',
            'is_active'            => 'nullable',
        ], [
            'name.required' => 'O nome do cooperado é obrigatório.',
            'email.email'   => 'Insira um e-mail válido.',
            'photo.image'   => 'O arquivo de foto deve ser uma imagem válida.',
            'photo.max'     => 'A foto não pode ultrapassar 4 MB.',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('cooperative-members', 'public');
        }

        CooperativeMember::create($data);
        return redirect()->route('admin.cooperative-members.index')->with('success', 'Cooperado(a) cadastrado(a) com sucesso!');
    }

    public function edit(CooperativeMember $cooperativeMember)
    {
        return redirect()->route('admin.cooperative-members.index');
    }

    public function update(Request $request, CooperativeMember $cooperativeMember)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'registration_number'  => 'nullable|string|max:100',
            'phone'                => 'nullable|string|max:30',
            'email'                => 'nullable|email|max:255',
            'sex'                  => 'nullable|in:M,F',
            'guardian_name'        => 'nullable|string|max:255',
            'guardian_phone'       => 'nullable|string|max:30',
            'joined_at'            => 'nullable|date',
            'photo'                => 'nullable|image|max:4096',
            'is_active'            => 'nullable',
        ], [
            'name.required' => 'O nome do cooperado é obrigatório.',
            'email.email'   => 'Insira um e-mail válido.',
            'photo.image'   => 'O arquivo de foto deve ser uma imagem válida.',
            'photo.max'     => 'A foto não pode ultrapassar 4 MB.',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : $cooperativeMember->is_active;

        if ($request->hasFile('photo')) {
            if ($cooperativeMember->photo && Storage::disk('public')->exists($cooperativeMember->photo)) {
                Storage::disk('public')->delete($cooperativeMember->photo);
            }
            $data['photo'] = $request->file('photo')->store('cooperative-members', 'public');
        }

        $cooperativeMember->update($data);
        return redirect()->route('admin.cooperative-members.index')->with('success', 'Cadastro do(a) cooperado(a) atualizado com sucesso!');
    }

    public function destroy(CooperativeMember $cooperativeMember)
    {
        if ($cooperativeMember->photo && Storage::disk('public')->exists($cooperativeMember->photo)) {
            Storage::disk('public')->delete($cooperativeMember->photo);
        }
        $name = $cooperativeMember->name;
        $cooperativeMember->delete();
        return redirect()->route('admin.cooperative-members.index')->with('success', "Cooperado(a) \"{$name}\" removido(a) com sucesso!");
    }

    public function dues(CooperativeMember $cooperativeMember)
    {
        $monthlyFees = CooperativeMonthlyFee::orderBy('month')->get();
        $dues = $cooperativeMember->dues()->pluck('paid', 'cooperative_monthly_fee_id');

        return view('admin.cooperative-members.dues', compact('cooperativeMember', 'monthlyFees', 'dues'));
    }

    public function toggleDue(CooperativeMember $cooperativeMember, CooperativeMonthlyFee $cooperativeMonthlyFee)
    {
        if (!$cooperativeMember->is_active) {
            return back()->with('error', 'Cooperado inativo - mensalidades não são cobradas.');
        }

        $due = CooperativeDue::firstOrNew([
            'cooperative_member_id' => $cooperativeMember->id,
            'cooperative_monthly_fee_id' => $cooperativeMonthlyFee->id,
        ]);
        $due->paid = !$due->paid;
        $due->paid_at = $due->paid ? now() : null;
        $due->save();

        return back()->with('success', 'Mensalidade ' . ($due->paid ? 'marcada como paga!' : 'marcada como pendente.'));
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um cooperado para a ação em lote.');
        }

        switch ($action) {
            case 'activate':
                CooperativeMember::whereIn('id', $ids)->update(['is_active' => true]);
                return back()->with('success', count($ids) . ' cooperado(s) ativado(s) com sucesso!');

            case 'deactivate':
                CooperativeMember::whereIn('id', $ids)->update(['is_active' => false]);
                return back()->with('success', count($ids) . ' cooperado(s) desativado(s) com sucesso!');

            case 'delete':
                $members = CooperativeMember::whereIn('id', $ids)->get();
                foreach ($members as $m) {
                    if ($m->photo && Storage::disk('public')->exists($m->photo)) {
                        Storage::disk('public')->delete($m->photo);
                    }
                    $m->delete();
                }
                return back()->with('success', count($ids) . ' cooperado(s) excluído(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
