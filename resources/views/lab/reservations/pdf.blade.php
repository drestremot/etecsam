<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1f2937; background: #fff; }

  /* ── Cabeçalho ── */
  .header { background: #1a4d2e; color: #fff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: flex-start; }
  .header-left h1 { font-size: 16px; font-weight: 700; margin-bottom: 2px; }
  .header-left p  { font-size: 10px; color: #c8e6c9; }
  .header-right   { text-align: right; }
  .header-right .doc-id { font-size: 22px; font-weight: 700; color: #f5a623; }
  .header-right .doc-label { font-size: 9px; color: #c8e6c9; text-transform: uppercase; letter-spacing: .06em; }

  /* ── Status badge ── */
  .status-bar { padding: 8px 24px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; text-align: center; }
  .status-pre_alocada       { background: #f3f4f6; color: #6b7280; }
  .status-aprovada          { background: #dbeafe; color: #1d4ed8; }
  .status-em_execucao       { background: #fef3c7; color: #92400e; }
  .status-aguardando_conferencia { background: #ffedd5; color: #c2410c; }
  .status-aguardando_validacao   { background: #ede9fe; color: #6d28d9; }
  .status-validada          { background: #dcfce7; color: #166534; }
  .status-recusada          { background: #fee2e2; color: #991b1b; }

  /* ── Seções ── */
  .body { padding: 20px 24px; }
  .section { margin-bottom: 18px; }
  .section-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #6b7280; border-bottom: 1.5px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 10px; }
  .grid-2 { display: flex; gap: 0; }
  .col { flex: 1; padding-right: 16px; }
  .col:last-child { padding-right: 0; }
  .field { margin-bottom: 8px; }
  .label { font-size: 9px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 2px; }
  .value { font-size: 11px; color: #111827; font-weight: 500; }

  /* ── Tabela de materiais ── */
  table { width: 100%; border-collapse: collapse; margin-top: 4px; }
  th { background: #1a4d2e; color: #fff; padding: 6px 8px; font-size: 9px; text-align: left; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
  td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; color: #374151; }
  tr:nth-child(even) td { background: #f9fafb; }
  .qty-ok  { color: #166534; font-weight: 700; }
  .qty-no  { color: #9ca3af; }

  /* ── Observações ── */
  .obs-box { background: #f9fafb; border-left: 3px solid #2d6a4f; padding: 10px 12px; border-radius: 3px; font-size: 10px; line-height: 1.6; color: #374151; margin-bottom: 8px; white-space: pre-wrap; }
  .obs-box.coord { border-left-color: #1a4d2e; background: #f0fdf4; }
  .obs-meta { font-size: 9px; color: #9ca3af; margin-bottom: 3px; }

  /* ── Timeline ── */
  .timeline { margin: 0; padding: 0; list-style: none; }
  .timeline li { display: flex; gap: 10px; margin-bottom: 8px; align-items: flex-start; }
  .tl-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: 1px; }
  .tl-dot.green  { background: #22c55e; }
  .tl-dot.blue   { background: #3b82f6; }
  .tl-dot.yellow { background: #f59e0b; }
  .tl-dot.gray   { background: #d1d5db; }
  .tl-dot.purple { background: #a855f7; }
  .tl-text { font-size: 10px; color: #374151; }
  .tl-date { font-size: 9px; color: #9ca3af; }

  /* ── Assinaturas ── */
  .signatures { display: flex; gap: 24px; margin-top: 8px; }
  .sig-box { flex: 1; border-top: 1.5px solid #374151; padding-top: 6px; text-align: center; font-size: 10px; color: #6b7280; }

  /* ── Rodapé ── */
  .footer { border-top: 1px solid #e5e7eb; padding: 10px 24px; display: flex; justify-content: space-between; font-size: 9px; color: #9ca3af; margin-top: 12px; }
</style>
</head>
<body>

{{-- Cabeçalho --}}
<div class="header">
  <div class="header-left">
    <h1>Etec Sebastiana Augusta de Moraes</h1>
    <p>Centro Paula Souza — Gestão de Laboratórios e Espaços Didáticos</p>
    <p style="margin-top:6px;font-size:11px;color:#fff;">Comprovante de Atividade — Reserva de Espaço</p>
  </div>
  <div class="header-right">
    <div class="doc-label">Reserva Nº</div>
    <div class="doc-id">#{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</div>
    <div class="doc-label" style="margin-top:4px;">Gerado em {{ now()->format('d/m/Y H:i') }}</div>
  </div>
</div>

{{-- Status --}}
<div class="status-bar status-{{ $reservation->status }}">
  Status: {{ $reservation->status_label }}
</div>

<div class="body">

  {{-- Informações da reserva --}}
  <div class="section">
    <div class="section-title">Informações da Reserva</div>
    <div class="grid-2">
      <div class="col">
        <div class="field"><div class="label">Professor Solicitante</div><div class="value">{{ $reservation->user->name ?? '—' }}</div></div>
        <div class="field"><div class="label">Espaço / Laboratório</div><div class="value">{{ $reservation->space->name ?? '—' }}</div></div>
        @if($reservation->space?->description)
        <div class="field"><div class="label">Descrição do espaço</div><div class="value" style="color:#6b7280">{{ $reservation->space->description }}</div></div>
        @endif
      </div>
      <div class="col">
        <div class="field"><div class="label">Data da Atividade</div><div class="value">{{ $reservation->reservation_date->format('d/m/Y') }} ({{ $reservation->reservation_date->translatedFormat('l') }})</div></div>
        <div class="field"><div class="label">Horário</div><div class="value">{{ substr($reservation->start_time,0,5) }}{{ $reservation->end_time ? ' às '.substr($reservation->end_time,0,5) : '' }}</div></div>
        <div class="field"><div class="label">Solicitado em</div><div class="value">{{ $reservation->created_at->format('d/m/Y H:i') }}</div></div>
        @if($reservation->coordenador)
        <div class="field"><div class="label">Coordenador</div><div class="value">{{ $reservation->coordenador->name }}</div></div>
        @endif
      </div>
    </div>
  </div>

  {{-- Plano de aula --}}
  @if($reservation->description)
  <div class="section">
    <div class="section-title">Plano de Aula / Descrição da Atividade</div>
    <div class="obs-box" style="border-left-color:#3b82f6;background:#eff6ff">{{ $reservation->description }}</div>
  </div>
  @endif

  {{-- Materiais --}}
  @if($reservation->materials->count())
  <div class="section">
    <div class="section-title">Materiais e Recursos Solicitados</div>
    <table>
      <thead>
        <tr>
          <th>Material</th>
          <th>Nº Patrimônio</th>
          <th style="text-align:center">Qtd Solicitada</th>
          <th style="text-align:center">Entregue</th>
          <th style="text-align:center">Devolvido</th>
          <th style="text-align:center">Qtd Utilizada</th>
        </tr>
      </thead>
      <tbody>
        @foreach($reservation->materials as $m)
        <tr>
          <td><strong>{{ $m->name }}</strong></td>
          <td style="font-family:monospace">{{ $m->patrimony_number ?? '—' }}</td>
          <td style="text-align:center">{{ $m->pivot->quantity_requested }} {{ $m->unit ?? '' }}</td>
          <td style="text-align:center">
            @if($m->pivot->delivered)
              <span class="qty-ok">✓ Sim</span>
              @if($m->pivot->delivered_at)
                <br><span style="font-size:9px;color:#9ca3af">{{ \Carbon\Carbon::parse($m->pivot->delivered_at)->format('H:i') }}</span>
              @endif
            @else
              <span class="qty-no">—</span>
            @endif
          </td>
          <td style="text-align:center">
            @if($m->pivot->returned)
              <span class="qty-ok">✓ Sim</span>
              @if($m->pivot->returned_at)
                <br><span style="font-size:9px;color:#9ca3af">{{ \Carbon\Carbon::parse($m->pivot->returned_at)->format('H:i') }}</span>
              @endif
            @else
              <span class="qty-no">—</span>
            @endif
          </td>
          <td style="text-align:center">{{ $m->pivot->quantity_used ?? '—' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  {{-- Observações --}}
  @if($reservation->obs || $reservation->auxiliar_obs || $reservation->coordenador_obs)
  <div class="section">
    <div class="section-title">Registro de Observações</div>

    @if($reservation->obs)
    <div class="obs-meta">Professor — {{ $reservation->professor_released_at?->format('d/m/Y H:i') ?? '' }}</div>
    <div class="obs-box">{{ $reservation->obs }}</div>
    @endif

    @if($reservation->auxiliar_obs)
    <div class="obs-meta">Auxiliar: {{ $reservation->auxiliar->name ?? '—' }} — {{ $reservation->auxiliar_released_at?->format('d/m/Y H:i') ?? '' }}</div>
    <div class="obs-box">{{ $reservation->auxiliar_obs }}</div>
    @endif

    @if($reservation->coordenador_obs)
    <div class="obs-meta">Coordenador: {{ $reservation->coordenador->name ?? '—' }} — {{ $reservation->validated_at?->format('d/m/Y H:i') ?? '' }}</div>
    <div class="obs-box coord">{{ $reservation->coordenador_obs }}</div>
    @endif
  </div>
  @endif

  {{-- Timeline de status --}}
  <div class="section">
    <div class="section-title">Histórico da Atividade</div>
    <ul class="timeline">
      <li><div class="tl-dot green"></div><div><div class="tl-text">Reserva criada</div><div class="tl-date">{{ $reservation->created_at->format('d/m/Y H:i') }} — {{ $reservation->user->name ?? '—' }}</div></div></li>
      @if($reservation->approved_at ?? ($reservation->status !== 'pre_alocada' && $reservation->status !== 'recusada'))
      <li><div class="tl-dot blue"></div><div><div class="tl-text">Aprovada pelo coordenador</div><div class="tl-date">{{ $reservation->coordenador->name ?? '—' }}</div></div></li>
      @endif
      @if($reservation->professor_signed_at)
      <li><div class="tl-dot yellow"></div><div><div class="tl-text">Materiais entregues — Aula iniciada</div><div class="tl-date">{{ $reservation->professor_signed_at->format('d/m/Y H:i') }}</div></div></li>
      @endif
      @if($reservation->professor_released_at)
      <li><div class="tl-dot blue"></div><div><div class="tl-text">Professor registrou observações e liberou</div><div class="tl-date">{{ $reservation->professor_released_at->format('d/m/Y H:i') }}</div></div></li>
      @endif
      @if($reservation->auxiliar_released_at)
      <li><div class="tl-dot purple"></div><div><div class="tl-text">Auxiliar realizou conferência e liberou</div><div class="tl-date">{{ $reservation->auxiliar_released_at->format('d/m/Y H:i') }} — {{ $reservation->auxiliar->name ?? '—' }}</div></div></li>
      @endif
      @if($reservation->validated_at)
      <li><div class="tl-dot green"></div><div><div class="tl-text"><strong>Atividade validada e arquivada</strong></div><div class="tl-date">{{ $reservation->validated_at->format('d/m/Y H:i') }} — {{ $reservation->coordenador->name ?? '—' }}</div></div></li>
      @endif
      @if($reservation->status === 'recusada')
      <li><div class="tl-dot" style="background:#ef4444"></div><div><div class="tl-text">Reserva recusada</div><div class="tl-date">{{ $reservation->coordenador->name ?? '—' }}</div></div></li>
      @endif
    </ul>
  </div>

  {{-- Assinaturas --}}
  @if(in_array($reservation->status, ['validada', 'concluida', 'aguardando_validacao']))
  <div class="section">
    <div class="section-title">Assinaturas</div>
    <div class="signatures">
      <div class="sig-box">
        <div style="height:36px"></div>
        {{ $reservation->user->name ?? 'Professor' }}<br>
        <span style="font-size:9px">Professor Responsável</span>
      </div>
      @if($reservation->auxiliar)
      <div class="sig-box">
        <div style="height:36px"></div>
        {{ $reservation->auxiliar->name }}<br>
        <span style="font-size:9px">Auxiliar de Laboratório</span>
      </div>
      @endif
      @if($reservation->coordenador)
      <div class="sig-box">
        <div style="height:36px"></div>
        {{ $reservation->coordenador->name }}<br>
        <span style="font-size:9px">Coordenador</span>
      </div>
      @endif
    </div>
  </div>
  @endif

</div>

{{-- Rodapé --}}
<div class="footer">
  <span>Etec Sebastiana Augusta de Moraes — Centro Paula Souza — Andradina/SP</span>
  <span>Documento gerado em {{ now()->format('d/m/Y H:i') }} · etecsam.com.br</span>
</div>

</body>
</html>
