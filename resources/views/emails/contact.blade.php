<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f0e8; margin: 0; padding: 24px; color: #1f2937; }
        .card { background: #fff; border-radius: 12px; max-width: 560px; margin: 0 auto; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #1a4d2e; padding: 24px 32px; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; }
        .header p  { color: #c8e6c9; margin: 4px 0 0; font-size: 13px; }
        .badge { display: inline-block; background: #f5a623; color: #1a4d2e; font-weight: 700; font-size: 12px; padding: 3px 10px; border-radius: 20px; margin-top: 8px; }
        .body  { padding: 28px 32px; }
        .row   { margin-bottom: 18px; }
        .label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 14px; color: #111827; }
        .msg   { background: #f9fafb; border-left: 3px solid #2d6a4f; padding: 14px 16px; border-radius: 4px; font-size: 14px; line-height: 1.6; white-space: pre-wrap; }
        .footer { padding: 16px 32px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Nova mensagem via site</h1>
        <p>Etec Sebastiana Augusta de Moraes — etecsam.com.br</p>
        <div class="badge">{{ $assunto }}</div>
    </div>
    <div class="body">
        <div class="row">
            <div class="label">Nome</div>
            <div class="value">{{ $nome }}</div>
        </div>
        <div class="row">
            <div class="label">E-mail para resposta</div>
            <div class="value"><a href="mailto:{{ $email }}" style="color:#2d6a4f;">{{ $email }}</a></div>
        </div>
        @if($telefone)
        <div class="row">
            <div class="label">Telefone / WhatsApp</div>
            <div class="value">{{ $telefone }}</div>
        </div>
        @endif
        <div class="row">
            <div class="label">Mensagem</div>
            <div class="msg">{{ $mensagem }}</div>
        </div>
    </div>
    <div class="footer">
        Enviado em {{ now()->format('d/m/Y \à\s H:i') }} • etecsam.com.br
    </div>
</div>
</body>
</html>
