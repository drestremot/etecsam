<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f0e8; margin: 0; padding: 24px; color: #1f2937; }
  .card { background: #fff; border-radius: 12px; max-width: 580px; margin: 0 auto; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.10); }
  .header { background: #1a4d2e; padding: 28px 32px; }
  .header h1 { color: #fff; margin: 0; font-size: 20px; }
  .header p { color: #c8e6c9; margin: 6px 0 0; font-size: 13px; }
  .badge { display: inline-block; background: #f5a623; color: #1a4d2e; font-weight: 700; font-size: 12px; padding: 3px 12px; border-radius: 20px; margin-top: 10px; }
  .body { padding: 28px 32px; }
  .section { margin-bottom: 22px; }
  .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
  .row { display: flex; gap: 24px; margin-bottom: 12px; }
  .field { flex: 1; }
  .label { font-size: 11px; color: #9ca3af; font-weight: 600; margin-bottom: 2px; }
  .value { font-size: 14px; color: #111827; }
  .obs-box { background: #f9fafb; border-left: 3px solid #2d6a4f; padding: 12px 14px; border-radius: 4px; font-size: 14px; line-height: 1.6; white-space: pre-wrap; color: #374151; }
  .obs-box.coordinator { border-left-color: #1a4d2e; background: #f0fdf4; }
  .link-btn { display: inline-block; background: #1a4d2e; color: #fff; font-weight: 700; font-size: 14px; padding: 12px 24px; border-radius: 8px; text-decoration: none; margin-top: 8px; }
  .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>Atividade Validada pelo Coordenador</h1>
    <p>Etec Sebastiana Augusta de Moraes — etecsam.com.br</p>
    <div class="badge">Reserva #{{ $reservation->id }} — Validada</div>
  </div>

  <div class="body">
    <p style="font-size:15px;color:#111827;margin-bottom:20px;">
      Olá, <strong>{{ $recipient === 'professor' ? ($reservation->user->name ?? 'Professor') : ($reservation->auxiliar->name ?? 'Auxiliar') }}</strong>!<br>
      A atividade no laboratório foi <strong>validada e arquivada</strong> pelo coordenador.
    </p>

    <div class="section">
      <div class="section-title">Detalhes da Reserva</div>
      <div class="row">
        <div class="field"><div class="label">Espaço</div><div class="value">{{ $reservation->space->name ?? '—' }}</div></div>
        <div class="field"><div class="label">Data</div><div class="value">{{ $reservation->reservation_date->format('d/m/Y') }}</div></div>
      </div>
      <div class="row">
        <div class="field"><div class="label">Horário</div><div class="value">{{ substr($reservation->start_time,0,5) }}{{ $reservation->end_time ? ' – '.substr($reservation->end_time,0,5) : '' }}</div></div>
        <div class="field"><div class="label">Professor</div><div class="value">{{ $reservation->user->name ?? '—' }}</div></div>
      </div>
      @if($reservation->description)
      <div class="field"><div class="label">Plano de Aula</div><div class="value" style="margin-top:4px">{{ $reservation->description }}</div></div>
      @endif
    </div>

    @if($reservation->obs)
    <div class="section">
      <div class="section-title">Observações do Professor</div>
      <div class="obs-box">{{ $reservation->obs }}</div>
    </div>
    @endif

    @if($reservation->auxiliar_obs)
    <div class="section">
      <div class="section-title">Observações do Auxiliar</div>
      <div class="obs-box">{{ $reservation->auxiliar_obs }}</div>
    </div>
    @endif

    @if($reservation->coordenador_obs)
    <div class="section">
      <div class="section-title">Considerações do Coordenador</div>
      <div class="obs-box coordinator">{{ $reservation->coordenador_obs }}</div>
    </div>
    @endif

    <div class="section">
      <div class="section-title">Acessar e imprimir o documento completo</div>
      <a href="{{ url(route('lab.reservations.show', $reservation->id)) }}" class="link-btn">
        Ver Atividade e Imprimir PDF →
      </a>
    </div>
  </div>

  <div class="footer">
    Validado em {{ $reservation->validated_at?->format('d/m/Y \à\s H:i') }} &nbsp;•&nbsp;
    Coordenador: {{ $reservation->coordenador->name ?? '—' }} &nbsp;•&nbsp;
    etecsam.com.br
  </div>
</div>
</body>
</html>
