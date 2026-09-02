<?php

namespace App\Http\Controllers;

use App\Models\MedicalCertificate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalCertificateController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();
        $canViewAudit = $user->canViewMedicalAudit();

        $query = MedicalCertificate::with(['user.department', 'reviewer']);

        if (!$canManage) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('user_id') && $canManage) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('doctor_name', 'like', "%{$search}%")
                    ->orWhere('crm', 'like', "%{$search}%")
                    ->orWhere('cid', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('registration_number', 'like', "%{$search}%");
                    });
            });
        }

        $baseStatsQuery = MedicalCertificate::query();
        if (!$canManage) {
            $baseStatsQuery->where('user_id', $user->id);
        }

        $today = Carbon::today();

        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'pendentes' => (clone $baseStatsQuery)->where('status', 'pendente')->count(),
            'homologados' => (clone $baseStatsQuery)->where('status', 'homologado')->count(),
            'rejeitados' => (clone $baseStatsQuery)->where('status', 'rejeitado')->count(),
            'afastados_hoje' => (clone $baseStatsQuery)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->where('status', 'homologado')
                ->count(),
        ];

        $certificates = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $users = $canManage ? User::orderBy('name')->get() : collect([$user]);

        return view('medical_certificates.index', compact('certificates', 'stats', 'users', 'canManage', 'canViewAudit'));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem adicionar novos atestados.');
        }

        $users = User::orderBy('name')->get();
        return view('medical_certificates.create', compact('users'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem cadastrar atestados.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:medico,odontologico,acompanhamento,declaracao_horas,outro'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'crm' => ['nullable', 'string', 'max:50'],
            'cid' => ['nullable', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'days' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $calculatedDays = $request->filled('days') && $request->days > 0
            ? (int) $request->days
            : $startDate->diffInDays($endDate) + 1;

        $path = $request->file('attachment')->store('medical_certificates', 'public');

        $certificate = MedicalCertificate::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'doctor_name' => $validated['doctor_name'] ?? null,
            'crm' => $validated['crm'] ?? null,
            'cid' => $validated['cid'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $calculatedDays,
            'description' => $validated['description'] ?? null,
            'attachment_path' => $path,
            'status' => 'pendente',
        ]);

        $targetUser = User::find($validated['user_id']);

        $certificate->recordAudit(
            'criacao',
            "Atestado registrado pela Diretoria de Serviços para {$targetUser->name}.",
            [
                'colaborador' => $targetUser->name,
                'tipo' => $certificate->type_label,
                'periodo' => $certificate->start_date->format('d/m/Y') . ' até ' . $certificate->end_date->format('d/m/Y'),
                'dias' => $certificate->days,
                'medico' => $certificate->doctor_name ?? 'Não informado',
                'crm' => $certificate->crm ?? 'Não informado',
                'cadastrado_por' => $user->name,
            ],
            $user->id
        );

        return redirect()->route('medical-certificates.show', $certificate->id)
            ->with('success', 'Atestado médico cadastrado com sucesso pela Diretoria de Serviços!');
    }

    public function show(MedicalCertificate $medicalCertificate)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();
        $canViewAudit = $user->canViewMedicalAudit();

        if (!$canManage && $medicalCertificate->user_id !== $user->id) {
            abort(403, 'Acesso não autorizado a este atestado.');
        }

        $relations = ['user.department', 'reviewer'];
        if ($canViewAudit) {
            $relations[] = 'audits.user';
        }

        $medicalCertificate->load($relations);

        return view('medical_certificates.show', compact('medicalCertificate', 'canManage', 'canViewAudit'));
    }

    public function edit(MedicalCertificate $medicalCertificate)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem editar este atestado.');
        }

        $users = User::orderBy('name')->get();
        return view('medical_certificates.edit', compact('medicalCertificate', 'users'));
    }

    public function update(Request $request, MedicalCertificate $medicalCertificate)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem editar este atestado.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:medico,odontologico,acompanhamento,declaracao_horas,outro'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'crm' => ['nullable', 'string', 'max:50'],
            'cid' => ['nullable', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'days' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:pendente,homologado,rejeitado'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $calculatedDays = $request->filled('days') && $request->days > 0
            ? (int) $request->days
            : $startDate->diffInDays($endDate) + 1;

        $changes = [];
        if ($medicalCertificate->user_id != $validated['user_id']) {
            $oldUserName = $medicalCertificate->user->name ?? 'N/A';
            $newUserName = User::find($validated['user_id'])->name ?? 'N/A';
            $changes['Colaborador'] = ['anterior' => $oldUserName, 'novo' => $newUserName];
        }

        if ($medicalCertificate->type != $validated['type']) {
            $changes['Tipo de Afastamento'] = ['anterior' => $medicalCertificate->type_label, 'novo' => $validated['type']];
        }

        if ($medicalCertificate->doctor_name != ($validated['doctor_name'] ?? null)) {
            $changes['Nome do Médico'] = ['anterior' => $medicalCertificate->doctor_name, 'novo' => $validated['doctor_name'] ?? ''];
        }

        if ($medicalCertificate->crm != ($validated['crm'] ?? null)) {
            $changes['CRM/CRO'] = ['anterior' => $medicalCertificate->crm, 'novo' => $validated['crm'] ?? ''];
        }

        if ($medicalCertificate->cid != ($validated['cid'] ?? null)) {
            $changes['CID'] = ['anterior' => $medicalCertificate->cid, 'novo' => $validated['cid'] ?? ''];
        }

        if ($medicalCertificate->start_date->toDateString() != $validated['start_date']) {
            $changes['Data Início'] = ['anterior' => $medicalCertificate->start_date->format('d/m/Y'), 'novo' => $startDate->format('d/m/Y')];
        }

        if ($medicalCertificate->end_date->toDateString() != $validated['end_date']) {
            $changes['Data Término'] = ['anterior' => $medicalCertificate->end_date->format('d/m/Y'), 'novo' => $endDate->format('d/m/Y')];
        }

        if ($medicalCertificate->days != $calculatedDays) {
            $changes['Dias de Afastamento'] = ['anterior' => $medicalCertificate->days, 'novo' => $calculatedDays];
        }

        if ($medicalCertificate->description != ($validated['description'] ?? null)) {
            $changes['Observações'] = ['anterior' => $medicalCertificate->description, 'novo' => $validated['description'] ?? ''];
        }

        if ($medicalCertificate->status != $validated['status']) {
            $changes['Status'] = ['anterior' => $medicalCertificate->status_label, 'novo' => $validated['status']];
        }

        if ($request->hasFile('attachment')) {
            if (Storage::disk('public')->exists($medicalCertificate->attachment_path)) {
                Storage::disk('public')->delete($medicalCertificate->attachment_path);
            }
            $newPath = $request->file('attachment')->store('medical_certificates', 'public');
            $medicalCertificate->attachment_path = $newPath;
            $changes['Documento Anexo'] = ['anterior' => 'Arquivo anterior', 'novo' => 'Novo comprovante anexado'];
        }

        $medicalCertificate->update([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'doctor_name' => $validated['doctor_name'] ?? null,
            'crm' => $validated['crm'] ?? null,
            'cid' => $validated['cid'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $calculatedDays,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'rejection_reason' => $validated['status'] === 'rejeitado' ? ($validated['rejection_reason'] ?? null) : null,
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        $actionType = $request->hasFile('attachment') ? 'substituicao_anexo' : 'edicao';
        $medicalCertificate->recordAudit(
            $actionType,
            "Atestado atualizado pela Diretoria de Serviços por {$user->name}.",
            !empty($changes) ? $changes : ['status' => 'Informações atualizadas sem divergência'],
            $user->id
        );

        return redirect()->route('medical-certificates.show', $medicalCertificate->id)
            ->with('success', 'Atestado médico atualizado com sucesso e alteração registrada na auditoria!');
    }

    public function updateStatus(Request $request, MedicalCertificate $medicalCertificate)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços / Diretores podem homologar atestados.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:homologado,rejeitado,pendente'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $medicalCertificate->status_label;

        $medicalCertificate->update([
            'status' => $validated['status'],
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'rejection_reason' => $validated['status'] === 'rejeitado' ? ($validated['rejection_reason'] ?? null) : null,
        ]);

        $medicalCertificate->recordAudit(
            'alteracao_status',
            "Status alterado para {$validated['status']} por {$user->name}.",
            [
                'Status' => ['anterior' => $oldStatus, 'novo' => $medicalCertificate->status_label],
                'Motivo' => $validated['rejection_reason'] ?? 'Nenhum motivo informado',
            ],
            $user->id
        );

        $msg = $validated['status'] === 'homologado'
            ? 'Atestado homologado com sucesso!'
            : ($validated['status'] === 'rejeitado' ? 'Atestado rejeitado com registro em auditoria.' : 'Status redefinido para pendente.');

        return redirect()->back()->with('success', $msg);
    }

    public function download(MedicalCertificate $medicalCertificate)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();

        if (!$canManage && $medicalCertificate->user_id !== $user->id) {
            abort(403, 'Acesso não autorizado a este documento.');
        }

        if (!Storage::disk('public')->exists($medicalCertificate->attachment_path)) {
            return redirect()->back()->with('error', 'Arquivo do atestado não encontrado no servidor.');
        }

        return Storage::disk('public')->download($medicalCertificate->attachment_path);
    }

    public function destroy(MedicalCertificate $medicalCertificate)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem excluir atestados.');
        }

        if (Storage::disk('public')->exists($medicalCertificate->attachment_path)) {
            Storage::disk('public')->delete($medicalCertificate->attachment_path);
        }

        $medicalCertificate->delete();

        return redirect()->route('medical-certificates.index')
            ->with('success', 'Atestado removido com sucesso.');
    }
}

