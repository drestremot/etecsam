<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\LabReservation;
use App\Models\Material;
use App\Models\Space;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabReservationController extends Controller
{
    public function dashboard()
    {
        $user    = auth()->user();
        $teacher = \App\Models\Teacher::where('email', $user->email)->first();

        // Stats adaptados por papel
        if ($user->is_admin) {
            $stats = [
                'spaces'    => Space::count(),
                'materials' => Material::count(),
                'pending'   => LabReservation::where('status', 'pre_alocada')->count(),
                'active'    => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->count(),
            ];
            $recent = LabReservation::with(['user', 'space'])->latest()->take(5)->get();
        } elseif ($user->hasRole('Auxiliar')) {
            $stats = [
                'aguardando' => LabReservation::where('status', 'aguardando_conferencia')->count(),
                'ativas'     => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->count(),
                'concluidas' => LabReservation::whereIn('status', ['concluida', 'finalizada'])->count(),
                'total'      => LabReservation::count(),
            ];
            $recent = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['aguardando_conferencia', 'aprovada', 'em_execucao'])
                ->latest()->take(5)->get();
        } else {
            // Professor
            $stats = [
                'minhas'    => LabReservation::where('user_id', $user->id)->count(),
                'pendentes' => LabReservation::where('user_id', $user->id)->where('status', 'pre_alocada')->count(),
                'ativas'    => LabReservation::where('user_id', $user->id)->whereIn('status', ['aprovada', 'em_execucao'])->count(),
                'concluidas'=> LabReservation::where('user_id', $user->id)->whereIn('status', ['concluida', 'finalizada'])->count(),
            ];
            $recent = LabReservation::with(['space'])
                ->where('user_id', $user->id)
                ->latest()->take(5)->get();
        }

        return view('lab.dashboard', compact('stats', 'recent', 'teacher', 'user'));
    }

    public function index()
    {
        $user = auth()->user();

        $reservations = $user->hasRole('admin') || $user->hasRole('Auxiliar')
            ? LabReservation::with(['user', 'space'])->latest()->paginate(20)
            : LabReservation::with(['space'])->where('user_id', $user->id)->latest()->paginate(20);

        return view('lab.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $spaces    = Space::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        return view('lab.reservations.create', compact('spaces', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'space_id'         => 'required|exists:spaces,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required',
            'end_time'         => 'nullable',
            'description'      => 'nullable|string',
            'materials'        => 'nullable|array',
            'materials.*.id'   => 'exists:materials,id',
            'materials.*.qty'  => 'integer|min:1',
        ]);

        $reservation = LabReservation::create([
            'user_id'          => auth()->id(),
            'space_id'         => $validated['space_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time'       => $validated['start_time'],
            'end_time'         => $validated['end_time'] ?? null,
            'description'      => $validated['description'] ?? null,
            'status'           => 'pre_alocada',
        ]);

        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $mat) {
                $reservation->materials()->attach($mat['id'], [
                    'quantity_requested' => $mat['qty'] ?? 1,
                ]);
            }
        }

        return redirect()->route('lab.reservations.show', $reservation)
            ->with('success', 'Reserva criada! Aguarde aprovação do coordenador.');
    }

    public function show(LabReservation $reservation)
    {
        $reservation->load(['user', 'space', 'auxiliar', 'materials', 'images']);
        return view('lab.reservations.show', compact('reservation'));
    }

    public function approve(LabReservation $reservation)
    {
        $reservation->update(['status' => 'aprovada']);
        return back()->with('success', 'Reserva aprovada!');
    }

    public function reject(LabReservation $reservation)
    {
        $reservation->update(['status' => 'recusada']);
        return back()->with('success', 'Reserva recusada.');
    }

    public function startClass(LabReservation $reservation)
    {
        $reservation->update(['status' => 'em_execucao']);
        return back()->with('success', 'Aula iniciada!');
    }

    public function submitProfessorObs(Request $request, LabReservation $reservation)
    {
        $request->validate(['obs' => 'required|string']);
        $reservation->update([
            'obs'    => $request->obs,
            'status' => 'aguardando_conferencia',
        ]);
        return back()->with('success', 'Observações registradas. Aguardando conferência.');
    }

    public function auxiliarFinalize(Request $request, LabReservation $reservation)
    {
        $reservation->update([
            'status'                  => 'conferida',
            'confirmed_by_auxiliar_at' => now(),
        ]);
        return back()->with('success', 'Conferência registrada!');
    }

    public function uploadScannedDoc(Request $request, LabReservation $reservation)
    {
        $request->validate(['scanned_doc' => 'required|file|mimes:pdf,jpg,png|max:5120']);
        $path = $request->file('scanned_doc')->store('reservations/docs', 'public');
        $reservation->update([
            'scanned_doc'  => $path,
            'status'       => 'concluida',
            'finalized_at' => now(),
        ]);
        return back()->with('success', 'Documento enviado. Reserva concluída!');
    }

    public function history()
    {
        $reservations = LabReservation::with(['user', 'space'])
            ->whereIn('status', ['concluida', 'finalizada'])
            ->latest('finalized_at')
            ->paginate(20);

        return view('lab.reservations.history', compact('reservations'));
    }

    public function generatePDF(LabReservation $reservation)
    {
        $reservation->load(['user', 'space', 'auxiliar', 'materials']);
        $pdf = Pdf::loadView('lab.reservations.pdf', compact('reservation'));
        return $pdf->stream("checklist-reserva-{$reservation->id}.pdf");
    }

    public function calendarEvents()
    {
        $reservations = LabReservation::with('space')
            ->whereNotIn('status', ['recusada'])
            ->get()
            ->map(fn($r) => [
                'id'    => $r->id,
                'title' => $r->space->name . ' — ' . ($r->user->name ?? ''),
                'start' => $r->reservation_date->format('Y-m-d') . 'T' . $r->start_time,
                'end'   => $r->reservation_date->format('Y-m-d') . 'T' . ($r->end_time ?? $r->start_time),
                'color' => match($r->status) {
                    'pre_alocada' => '#9ca3af',
                    'aprovada'    => '#3b82f6',
                    'em_execucao' => '#f59e0b',
                    'concluida', 'finalizada' => '#22c55e',
                    'recusada'    => '#ef4444',
                    default       => '#6b7280',
                },
                'url'   => route('lab.reservations.show', $r->id),
            ]);

        return response()->json($reservations);
    }
}
