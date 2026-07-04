<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; margin: 30px; }
  h1 { color: #1a4d2e; border-bottom: 2px solid #1a4d2e; padding-bottom: 6px; }
  table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  th { background: #1a4d2e; color: #fff; padding: 7px 10px; text-align: left; }
  td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; }
  .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; background: #d1fae5; color: #065f46; }
  .section { margin-top: 18px; }
  .label { font-weight: bold; color: #6b7280; font-size: 10px; text-transform: uppercase; }
  .footer { margin-top: 40px; font-size: 10px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>

<h1>Checklist de Reserva — Etec Sebastiana Augusta de Moraes</h1>

<div class="section">
  <table>
    <tr><th colspan="2">Informações da Reserva #{{ $reservation->id }}</th></tr>
    <tr><td class="label">Professor</td><td>{{ $reservation->user->name ?? '—' }}</td></tr>
    <tr><td class="label">Espaço</td><td>{{ $reservation->space->name ?? '—' }}</td></tr>
    <tr><td class="label">Data</td><td>{{ $reservation->reservation_date->format('d/m/Y') }}</td></tr>
    <tr><td class="label">Horário</td><td>{{ substr($reservation->start_time,0,5) }}{{ $reservation->end_time ? ' – '.substr($reservation->end_time,0,5) : '' }}</td></tr>
    <tr><td class="label">Status</td><td><span class="badge">{{ $reservation->status_label }}</span></td></tr>
    @if($reservation->description)
    <tr><td class="label">Descrição</td><td>{{ $reservation->description }}</td></tr>
    @endif
  </table>
</div>

@if($reservation->materials->count())
<div class="section">
  <table>
    <tr><th>Material</th><th>Qtd. Solicitada</th><th>Qtd. Usada</th><th>Entregue</th><th>Devolvido</th></tr>
    @foreach($reservation->materials as $m)
    <tr>
      <td>{{ $m->name }}</td>
      <td style="text-align:center">{{ $m->pivot->quantity_requested }}</td>
      <td style="text-align:center">{{ $m->pivot->quantity_used ?? '—' }}</td>
      <td style="text-align:center">{{ $m->pivot->delivered ? 'Sim' : 'Não' }}</td>
      <td style="text-align:center">{{ $m->pivot->returned ? 'Sim' : 'Não' }}</td>
    </tr>
    @endforeach
  </table>
</div>
@endif

@if($reservation->obs)
<div class="section">
  <p class="label">Observações do Professor</p>
  <p>{{ $reservation->obs }}</p>
</div>
@endif

<div class="section">
  <table>
    <tr><th colspan="2">Assinaturas</th></tr>
    <tr>
      <td style="padding: 30px 10px; width:50%">
        <div style="border-top: 1px solid #374151; margin-top: 40px; padding-top: 4px; text-align: center;">Professor</div>
      </td>
      <td style="padding: 30px 10px; width:50%">
        <div style="border-top: 1px solid #374151; margin-top: 40px; padding-top: 4px; text-align: center;">Auxiliar de Laboratório</div>
      </td>
    </tr>
  </table>
</div>

<div class="footer">
  Gerado em {{ now()->format('d/m/Y H:i') }} • Etec Sebastiana Augusta de Moraes — Centro Paula Souza
</div>

</body>
</html>
