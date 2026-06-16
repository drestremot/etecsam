<?php
// ARQUIVO TEMPORARIO — delete imediatamente apos uso
if (($_GET['token'] ?? '') !== 'etecSAM2026') {
    http_response_code(403);
    die('Acesso negado.');
}

chdir(dirname(__DIR__));
header('Content-Type: text/html; charset=utf-8');
echo '<html><head><meta charset="utf-8"><title>Restore</title>';
echo '<style>body{font-family:monospace;background:#111;color:#0f0;padding:20px}pre{margin:0}.ok{color:#0f0}.err{color:#f44}.warn{color:#fa0}h3{color:#ff0}</style></head><body>';
echo '<h3>Restore Banco de Dados — Etec SAM</h3><pre>';

$dbFile    = 'database/database.sqlite';
$home      = trim(shell_exec('echo $HOME') ?? '');
$backupFile = $home . '/etecsam_db.sqlite';

echo "HOME detectado: $home\n";
echo "Backup esperado: $backupFile\n\n";

// 1. Verifica backup
if (!$home || !file_exists($backupFile) || !filesize($backupFile)) {
    echo '<span class="err">ERRO: backup nao encontrado em ' . htmlspecialchars($backupFile) . '</span>';
    echo "\n\nArquivos disponiveis em ~:\n";
    echo htmlspecialchars(shell_exec('ls -lh ~/ 2>&1') ?? '');
    echo '</pre></body></html>';
    exit;
}

$backupSize = filesize($backupFile);
echo "Backup encontrado: $backupSize bytes\n";

// 2. Garante que o diretorio database existe
if (!is_dir('database')) {
    mkdir('database', 0775, true);
    echo "Diretorio database/ criado\n";
}

// 3. Faz backup do banco atual (se existir)
if (file_exists($dbFile) && filesize($dbFile) > 0) {
    $currentSize = filesize($dbFile);
    echo "Banco atual: $currentSize bytes — salvando como database.sqlite.antes_restore\n";
    copy($dbFile, 'database/database.sqlite.antes_restore');
}

// 4. Restaura o backup
if (copy($backupFile, $dbFile)) {
    chmod($dbFile, 0664);
    echo '<span class="ok">Banco restaurado com sucesso! (' . filesize($dbFile) . ' bytes)</span>' . "\n\n";
} else {
    echo '<span class="err">ERRO ao copiar backup para ' . $dbFile . '</span>';
    echo '</pre></body></html>';
    exit;
}

// 5. Roda migrations pendentes (novas colunas is_active)
echo "Rodando migrations pendentes...\n";
echo htmlspecialchars(shell_exec('php artisan migrate --force 2>&1') ?? '');

// 6. Limpa e recria caches
echo "\nLimpando caches...\n";
foreach (['config:clear','route:clear','view:clear','config:cache','route:cache'] as $cmd) {
    echo htmlspecialchars(shell_exec("php artisan $cmd 2>&1") ?? '') . "\n";
}

// 7. Atualiza o backup com o estado atual (pos-migration)
copy($dbFile, $backupFile);
echo "\nBackup externo atualizado apos restore.\n";

// 8. Diagnostico
echo "\n=== Diagnostico ===\n";
echo "SQLite: " . (file_exists($dbFile) ? filesize($dbFile) . ' bytes' : 'FALTANDO') . "\n";
echo "pdo_sqlite: " . (extension_loaded('pdo_sqlite') ? 'OK' : 'FALTA') . "\n";

echo '</pre>';
echo '<p style="color:#0f0;font-size:1.3em"><strong>&#10003; Banco restaurado! DELETE este arquivo agora.</strong></p>';
echo '<p>Caminho: <code>' . __FILE__ . '</code></p>';
echo '</body></html>';
