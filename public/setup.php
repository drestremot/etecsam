<?php
// ARQUIVO TEMPORARIO — delete imediatamente apos uso
if (($_GET['token'] ?? '') !== 'etecSAM2026') {
    http_response_code(403);
    die('Acesso negado.');
}

chdir(dirname(__DIR__));
header('Content-Type: text/html; charset=utf-8');
echo '<html><head><meta charset="utf-8"><title>Setup</title>';
echo '<style>body{font-family:monospace;background:#111;color:#0f0;padding:20px}pre{margin:0}h3{color:#ff0}</style></head><body>';
echo '<h3>Setup Etec SAM</h3><pre>';

function run(string $cmd): void {
    echo "\n$ $cmd\n";
    echo htmlspecialchars(shell_exec($cmd . ' 2>&1') ?? '(sem saida)');
}

// 1. Garantir que o banco existe e tem permissao
$dbFile = 'database/database.sqlite';
if (!file_exists($dbFile)) {
    touch($dbFile);
    chmod($dbFile, 0664);
    echo "database.sqlite criado\n";
} else {
    chmod($dbFile, 0664);
    echo "database.sqlite ja existe (" . filesize($dbFile) . " bytes)\n";
}

// 2. Migrations
run('php artisan migrate --force');

// 3. Seed somente se banco vazio
try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
} catch (Exception $e) {
    $count = 0;
}

if ($count === 0) {
    echo "\nBanco vazio — executando seeds iniciais...\n";
    run('php artisan db:seed --class=AdminSeeder --force');
    run('php artisan db:seed --class=ThemeSeeder --force');
} else {
    echo "\nBanco com $count usuario(s) — seed ignorado\n";
}

// 4. Caches
run('php artisan config:clear');
run('php artisan route:clear');
run('php artisan view:clear');
run('php artisan config:cache');
run('php artisan route:cache');

// 5. Storage
run('mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache/data storage/logs');
run('chmod -R 775 storage/ bootstrap/cache/');
run('chmod 664 database/database.sqlite');

// 6. Symlink
if (!file_exists('public/storage')) {
    run('ln -s ' . realpath('storage/app/public') . ' public/storage');
}

// 7. Salva backup externo para proteger deploys futuros
$home = trim(shell_exec('echo $HOME'));
if ($home && is_dir($home)) {
    $backupPath = $home . '/etecsam_db.sqlite';
    if (copy($dbFile, $backupPath)) {
        echo "\nBackup salvo em: $backupPath (" . filesize($backupPath) . " bytes)\n";
    } else {
        echo "\nAVISO: nao foi possivel salvar backup em $backupPath\n";
    }
}

// 8. Diagnostico final
echo "\n=== Diagnostico ===\n";
echo "SQLite: " . (file_exists($dbFile) ? filesize($dbFile) . ' bytes' : 'FALTANDO') . "\n";
echo "pdo_sqlite: " . (extension_loaded('pdo_sqlite') ? 'OK' : 'FALTA') . "\n";
echo "backup externo: " . (isset($backupPath) && file_exists($backupPath) ? filesize($backupPath) . ' bytes' : 'nao salvo') . "\n";

echo '</pre>';
echo '<p style="color:#ff0;font-size:1.3em"><strong>&#10003; Pronto! DELETE este arquivo agora.</strong></p>';
echo '<p>Caminho: <code>' . __FILE__ . '</code></p>';
echo '</body></html>';
