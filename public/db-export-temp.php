<?php
// ================================================================
// SCRIPT TEMPORARIO DE EXPORT DO BANCO - Etec SAM
// Acesse: https://www.etecsam.com.br/db-export-temp.php?token=EtecSAM2026download
// APAGUE ESTE ARQUIVO APOS O DOWNLOAD!
// ================================================================

$token_correto = "EtecSAM2026download";
$token_recebido = $_GET["token"] ?? "";

if ($token_recebido !== $token_correto) {
    http_response_code(404);
    exit("Not Found");
}

// Tentar varios caminhos possiveis para o banco
$possible_paths = [
    __DIR__ . "/../database/database.sqlite",
    "/home/u615674013/public_html/etecsam/database/database.sqlite",
    "/home/u615674013/domains/etecsam.com.br/public_html/etecsam/database/database.sqlite",
    dirname(__DIR__) . "/database/database.sqlite",
];

$db_path = null;
foreach ($possible_paths as $path) {
    $resolved = realpath($path);
    if ($resolved && file_exists($resolved) && filesize($resolved) > 0) {
        $db_path = $resolved;
        break;
    }
}

if (!$db_path) {
    http_response_code(500);
    echo "Banco nao encontrado. Caminhos tentados:<br>";
    foreach ($possible_paths as $p) {
        $r = realpath($p);
        $exists = $r && file_exists($r);
        $size = $exists ? filesize($r) : 0;
        echo "- $p => " . ($r ?: "INVALIDO") . " | exists=${$exists} | size=$size<br>";
    }
    echo "<br>__DIR__: " . __DIR__;
    exit;
}

$size = filesize($db_path);
$timestamp = date("Ymd_His");
$filename = "database_servidor_{$timestamp}.sqlite";

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header("Content-Length: {$size}");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("X-DB-Path: {$db_path}");
header("X-DB-Size: {$size}");

readfile($db_path);

register_shutdown_function(function() {
    @unlink(__FILE__);
});
?>
