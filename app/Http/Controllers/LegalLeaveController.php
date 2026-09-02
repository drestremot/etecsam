<?php

namespace App\Http\Controllers;

use App\Models\LegalLeave;
use App\Models\LegalLeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LegalLeaveController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();
        $isCoordinator = $canManage || in_array($user->role, ['Coordenador', 'Coordenação de Curso', 'Diretor', 'Diretora de Serviços', 'Responsável do Departamento']);
        $canViewAudit = $user->canViewMedicalAudit();

        // 1. Query Folgas Concedidas
        $leavesQuery = LegalLeave::with(['user.department', 'requests']);

        if (!$canManage) {
            $leavesQuery->where('user_id', $user->id);
        }

        if ($request->filled('user_id') && $canManage) {
            $leavesQuery->where('user_id', $request->user_id);
        }

        if ($request->filled('type')) {
            $leavesQuery->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $leavesQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $leavesQuery->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('registration_number', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->input('per_page', 25);
        if ($perPage === 'all') {
            $perPage = max(1, $leavesQuery->count());
        } else {
            $perPage = in_array((int)$perPage, [10, 25, 50, 100]) ? (int)$perPage : 25;
        }

        $leaves = $leavesQuery->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'leaves_page')->withQueryString();

        // 2. Query Solicitações de Usufruto de Folga
        $requestsQuery = LegalLeaveRequest::with(['user.department', 'legalLeave', 'reviewer']);

        if (!$canManage && !$isCoordinator) {
            $requestsQuery->where('user_id', $user->id);
        }

        if ($request->filled('request_status')) {
            $requestsQuery->where('status', $request->request_status);
        }

        $leaveRequests = $requestsQuery->orderBy('requested_date', 'desc')->paginate($perPage, ['*'], 'requests_page')->withQueryString();

        // 3. Indicadores (KPIs)
        $baseStatsQuery = LegalLeave::query();
        $baseRequestsQuery = LegalLeaveRequest::query();
        if (!$canManage) {
            $baseStatsQuery->where('user_id', $user->id);
        }
        if (!$canManage && !$isCoordinator) {
            $baseRequestsQuery->where('user_id', $user->id);
        }

        $stats = [
            'total_granted' => (clone $baseStatsQuery)->sum('days_granted'),
            'total_used' => (clone $baseStatsQuery)->sum('days_used'),
            'total_remaining' => (clone $baseStatsQuery)->sum('days_remaining'),
            'pending_requests' => (clone $baseRequestsQuery)->where('status', 'pendente')->count(),
            'approved_requests' => (clone $baseRequestsQuery)->where('status', 'aprovado')->count(),
        ];

        $users = $canManage ? User::orderBy('name')->get() : collect([$user]);

        return view('legal_leaves.index', compact(
            'leaves',
            'leaveRequests',
            'stats',
            'users',
            'canManage',
            'isCoordinator',
            'canViewAudit'
        ));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem cadastrar folgas previstas em lei.');
        }

        $users = User::orderBy('name')->get();
        return view('legal_leaves.create', compact('users'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem cadastrar folgas previstas em lei.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:eleicao,juri_popular,doacao_sangue,alistamento,casamento,luto,convocacao_judicial,outro'],
            'description' => ['required', 'string', 'max:255'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'event_date' => ['nullable', 'date'],
            'days_granted' => ['required', 'integer', 'min:1'],
            'expiration_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $path = $request->file('attachment')->store('legal_leaves', 'public');

        $leave = LegalLeave::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'document_number' => $validated['document_number'] ?? null,
            'event_date' => $validated['event_date'] ?? null,
            'days_granted' => (int) $validated['days_granted'],
            'days_used' => 0,
            'days_remaining' => (int) $validated['days_granted'],
            'expiration_date' => $validated['expiration_date'] ?? null,
            'attachment_path' => $path,
            'notes' => $validated['notes'] ?? null,
            'created_by' => $user->id,
            'status' => 'ativo',
        ]);

        $targetUser = User::find($validated['user_id']);

        $leave->recordAudit(
            'concessao',
            "Concessão de {$leave->days_granted} dia(s) de folga legal ({$leave->type_label}) para o colaborador {$targetUser->name}.",
            [
                'colaborador' => $targetUser->name,
                'tipo' => $leave->type_label,
                'dias_concedidos' => $leave->days_granted,
                'descricao' => $leave->description,
                'documento' => $leave->document_number ?? 'Não informado',
                'fundamentacao' => $leave->legal_basis,
                'cadastrado_por' => $user->name,
            ],
            $user->id
        );

        return redirect()->route('legal-leaves.show', $leave->id)
            ->with('success', 'Direito de folga legal cadastrado com sucesso pela Diretoria de Serviços!');
    }

    public function show(LegalLeave $legalLeave)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();
        $isCoordinator = $canManage || in_array($user->role, ['Coordenador', 'Coordenação de Curso', 'Diretor', 'Diretora de Serviços', 'Responsável do Departamento']);
        $canViewAudit = $user->canViewMedicalAudit();

        if (!$canManage && $legalLeave->user_id !== $user->id) {
            abort(403, 'Acesso restrito. Somente o próprio colaborador e a Diretoria de Serviços podem visualizar este atestado de folga.');
        }

        $relations = ['user.department', 'creator', 'requests.reviewer'];
        if ($canViewAudit) {
            $relations[] = 'audits.user';
        }

        $legalLeave->load($relations);

        return view('legal_leaves.show', compact('legalLeave', 'canManage', 'isCoordinator', 'canViewAudit'));
    }

    public function edit(LegalLeave $legalLeave)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem editar este registro.');
        }

        $users = User::orderBy('name')->get();
        return view('legal_leaves.edit', compact('legalLeave', 'users'));
    }

    public function update(Request $request, LegalLeave $legalLeave)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem editar este registro.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'string', 'in:eleicao,juri_popular,doacao_sangue,alistamento,casamento,luto,convocacao_judicial,outro'],
            'description' => ['required', 'string', 'max:255'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'event_date' => ['nullable', 'date'],
            'days_granted' => ['required', 'integer', 'min:1'],
            'expiration_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:ativo,esgotado,expirado'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $changes = [];
        if ($legalLeave->days_granted != $validated['days_granted']) {
            $changes['Dias Concedidos'] = ['anterior' => $legalLeave->days_granted, 'novo' => $validated['days_granted']];
        }
        if ($legalLeave->type != $validated['type']) {
            $changes['Tipo'] = ['anterior' => $legalLeave->type_label, 'novo' => $validated['type']];
        }
        if ($legalLeave->description != $validated['description']) {
            $changes['Descrição'] = ['anterior' => $legalLeave->description, 'novo' => $validated['description']];
        }

        if ($request->hasFile('attachment')) {
            if (Storage::disk('public')->exists($legalLeave->attachment_path)) {
                Storage::disk('public')->delete($legalLeave->attachment_path);
            }
            $newPath = $request->file('attachment')->store('legal_leaves', 'public');
            $legalLeave->attachment_path = $newPath;
            $changes['Comprovante'] = ['anterior' => 'Arquivo anterior', 'novo' => 'Novo comprovante anexado'];
        }

        $legalLeave->update([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'document_number' => $validated['document_number'] ?? null,
            'event_date' => $validated['event_date'] ?? null,
            'days_granted' => (int) $validated['days_granted'],
            'expiration_date' => $validated['expiration_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        $legalLeave->recalculateBalance();

        $actionType = $request->hasFile('attachment') ? 'substituicao_anexo' : 'edicao';
        $legalLeave->recordAudit(
            $actionType,
            "Registro de folga legal atualizado por {$user->name} (Diretoria de Serviços).",
            !empty($changes) ? $changes : ['atualizacao' => 'Dados revisados sem divergência'],
            $user->id
        );

        return redirect()->route('legal-leaves.show', $legalLeave->id)
            ->with('success', 'Registro de folga legal atualizado com sucesso!');
    }

    public function requestUsage(Request $request, LegalLeave $legalLeave)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();

        if (!$canManage && $legalLeave->user_id !== $user->id) {
            abort(403, 'Você só pode solicitar folga para seus próprios direitos concedidos.');
        }

        if ($legalLeave->days_remaining <= 0) {
            return redirect()->back()->with('error', 'Este crédito de folga não possui saldo restante disponível.');
        }

        $validated = $request->validate([
            'requested_date' => ['required', 'date', 'after_or_equal:today'],
            'requested_days' => ['required', 'integer', 'min:1', 'max:' . $legalLeave->days_remaining],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $requestedDate = Carbon::parse($validated['requested_date'])->startOfDay();
        $now = now();
        $hoursAdvance = $now->diffInHours($requestedDate, false);
        $isWithin72h = $hoursAdvance >= 72;

        $leaveRequest = LegalLeaveRequest::create([
            'legal_leave_id' => $legalLeave->id,
            'user_id' => $legalLeave->user_id,
            'requested_date' => $validated['requested_date'],
            'requested_days' => (int) $validated['requested_days'],
            'reason' => $validated['reason'] ?? null,
            'is_within_72h_deadline' => $isWithin72h,
            'status' => 'pendente',
        ]);

        $msgAntecedencia = $isWithin72h
            ? "Solicitação dentro do prazo regulamentar de 72h ({$hoursAdvance}h de antecedência)."
            : "⚠️ Atenção: Solicitação efetuada com {$hoursAdvance}h de antecedência (inferior a 72h regulamentares).";

        $legalLeave->recordAudit(
            'solicitacao_usufruto',
            "Solicitação de usufruto de {$leaveRequest->requested_days} dia(s) para " . $requestedDate->format('d/m/Y') . " por {$user->name}. {$msgAntecedencia}",
            [
                'data_solicitada' => $requestedDate->format('d/m/Y'),
                'dias_solicitados' => $leaveRequest->requested_days,
                'horas_antecedencia' => $hoursAdvance,
                'cumpriu_72h' => $isWithin72h ? 'Sim' : 'Não (Urgência)',
                'motivo' => $leaveRequest->reason ?? 'Não informado',
            ],
            $user->id
        );

        $alertType = $isWithin72h ? 'success' : 'warning';
        $alertMsg = $isWithin72h
            ? 'Solicitação de folga enviada com sucesso! O coordenador/encarregado foi notificado para tomar ciência.'
            : 'Solicitação de folga enviada com registro de urgência (menos de 72h de antecedência). Aguarde parecer da coordenação.';

        return redirect()->route('legal-leaves.index')->with($alertType, $alertMsg);
    }

    public function reviewRequest(Request $request, LegalLeaveRequest $leaveRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();
        $isCoordinator = $canManage || in_array($user->role, ['Coordenador', 'Coordenação de Curso', 'Diretor', 'Diretora de Serviços', 'Responsável do Departamento']);

        if (!$isCoordinator) {
            abort(403, 'Apenas coordenadores, encarregados de setor ou direção podem deferir solicitações de folga.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:aprovado,rejeitado,cancelado'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $leaveRequest->status_label;

        $leaveRequest->update([
            'status' => $validated['status'],
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'rejection_reason' => $validated['status'] === 'rejeitado' ? ($validated['rejection_reason'] ?? null) : null,
        ]);

        $leaveRequest->legalLeave->recalculateBalance();

        $actionName = $validated['status'] === 'aprovado' ? 'aprovacao_usufruto' : ($validated['status'] === 'rejeitado' ? 'rejeicao_usufruto' : 'cancelamento');

        $leaveRequest->legalLeave->recordAudit(
            $actionName,
            "Solicitação de folga para {$leaveRequest->requested_date->format('d/m/Y')} ({$leaveRequest->requested_days} dia(s)) foi {$leaveRequest->status_label} por {$user->name}.",
            [
                'status' => ['anterior' => $oldStatus, 'novo' => $leaveRequest->status_label],
                'data_folga' => $leaveRequest->requested_date->format('d/m/Y'),
                'avaliador' => $user->name,
                'motivo_rejeicao' => $leaveRequest->rejection_reason ?? 'N/A',
                'saldo_restante_atual' => $leaveRequest->legalLeave->days_remaining,
            ],
            $user->id
        );

        $msg = $validated['status'] === 'aprovado'
            ? 'Ciência tomada e folga aprovada com sucesso! O saldo foi debitado.'
            : 'Solicitação de folga rejeitada.';

        return redirect()->back()->with('success', $msg);
    }

    public function download(LegalLeave $legalLeave)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageMedicalCertificates();

        if (!$canManage && $legalLeave->user_id !== $user->id) {
            abort(403, 'Acesso restrito. Somente o próprio colaborador e a Diretoria de Serviços podem baixar este comprovante.');
        }

        if (!Storage::disk('public')->exists($legalLeave->attachment_path)) {
            return redirect()->back()->with('error', 'Arquivo do atestado não encontrado no servidor.');
        }

        return Storage::disk('public')->download($legalLeave->attachment_path);
    }

    public function destroy(LegalLeave $legalLeave)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageMedicalCertificates()) {
            abort(403, 'Apenas funcionários da Diretoria de Serviços podem excluir este registro.');
        }

        if (Storage::disk('public')->exists($legalLeave->attachment_path)) {
            Storage::disk('public')->delete($legalLeave->attachment_path);
        }

        $legalLeave->delete();

        return redirect()->route('legal-leaves.index')->with('success', 'Crédito de folga legal removido com sucesso.');
    }
}

