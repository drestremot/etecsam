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
  .link-btn { display: inline-block; background: #1a4d2e; color: #fff; font-weight: 700; font-size: 14px; padding: 12px 24px; border-radius: 8px; text-decoration: none; margin-top: 8px; }
  .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; }
</style>
</head>
<body>
<div class="card">
  <div class="header">
    <h1>{{ $title }}</h1>
    <p>Etec Sebastiana Augusta de Moraes — etecsam.com.br</p>
    <div class="badge">Reserva #{{ $reservation->id }}</div>
  </div>

  <div class="body">
    <p style="font-size:15px;color:#111827;margin-bottom:20px;">{{ $bodyText }}</p>

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
    </div>

    <div class="section">
      <a href="{{ url(route('lab.reservations.show', $reservation->id)) }}" class="link-btn">
        Ver Reserva →
      </a>
    </div>
  </div>

  <div class="footer">
    Notificação enviada por {{ $actingUser->name }} &nbsp;•&nbsp;
    Para responder diretamente a {{ $actingUser->name }}, use o "Responder" deste e-mail &nbsp;•&nbsp;
    etecsam.com.br
  </div>
</div>
</body>
</html>
