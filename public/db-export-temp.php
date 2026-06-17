<?php
// ================================================================
// SCRIPT TEMPORARIO DE EXPORT DO BANCO - Etec SAM
// Acesse: https://www.etecsam.com.br/db-export-temp.php?token=EtecSAM2026download
// APAGUE ESTE ARQUIVO APOS O DOWNLOAD!
// ================================================================

// Verificar token de seguranca
$token_correto = "EtecSAM2026download";
$token_recebido = $_GET["token"] ?? "";

if ($token_recebido !== $token_correto) {
    http_response_code(404);
    echo "Not Found";
    exit;
}

// Caminho do banco de dados
$db_path = realpath(__DIR__ . "/../database/database.sqlite");

if (!$db_path || !file_exists($db_path)) {
    http_response_code(500);
    echo "Banco nao encontrado em: " . __DIR__ . "/../database/database.sqlite";
    exit;
}

// Verificar tamanho
$size = filesize($db_path);
if ($size === 0) {
    http_response_code(500);
    echo "Banco esta vazio!";
    exit;
}

// Servir o arquivo para download
$timestamp = date("Ymd_His");
$filename = "database_servidor_$timestamp.sqlite";

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Length: $size");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");

// Enviar o arquivo
readfile($db_path);

// Auto-deletar apos o download (seguranca)
register_shutdown_function(function() {
    @unlink(__FILE__);
});
?>
