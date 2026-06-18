<?php

if (!function_exists('photo_url')) {
    /**
     * Retorna a URL correta de uma foto/imagem armazenada no banco.
     *
     * Regras de resolução (em ordem):
     *  1. null / vazio        → retorna null
     *  2. URL externa (http)  → retorna como está
     *  3. imagens/ ou icons/  → arquivo em public/, usa asset()
     *  4. qualquer outra coisa → arquivo em storage/app/public/, usa Storage::url()
     *
     * Usar Storage::url() (em vez de asset('storage/...')) garante que o caminho
     * gerado respeite a configuração do disco 'public' em config/filesystems.php,
     * o que funciona tanto em desenvolvimento local quanto em servidores de produção.
     *
     * IMPORTANTE para deploy:
     *  - Definir APP_URL corretamente no .env do servidor
     *  - Executar `php artisan storage:link` após deploy
     */
    function photo_url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // Trim whitespace
        $path = trim($path);

        // External URL (case-insensitive)
        if (stripos($path, 'http://') === 0 || stripos($path, 'https://') === 0) {
            return $path;
        }

        // Normalize leading slash and common public prefixes (case-insensitive checks)
        $trimmed = ltrim($path, '/');

        $lower = strtolower($trimmed);

        if (strpos($lower, 'public/') === 0) {
            $trimmed = substr($trimmed, strlen('public/'));
            $lower = substr($lower, strlen('public/'));
        }

        if (strpos($lower, 'storage/app/public/') === 0) {
            $trimmed = substr($trimmed, strlen('storage/app/public/'));
            $lower = substr($lower, strlen('storage/app/public/'));
        }

        if (strpos($lower, 'public/storage/') === 0) {
            $trimmed = substr($trimmed, strlen('public/storage/'));
            $lower = substr($lower, strlen('public/storage/'));
        }

        // Static public assets
        if (str_starts_with($lower, 'imagens/') || str_starts_with($lower, 'images/') || str_starts_with($lower, 'icons/')) {
            return asset($trimmed);
        }

        // If path already looks like storage/relative (public/storage/...), serve via asset
        if (str_starts_with($lower, 'storage/')) {
            return asset($trimmed);
        }

        // Default: assume file is on the 'public' disk (storage/app/public)
        return \Illuminate\Support\Facades\Storage::disk('public')->url($trimmed);
    }
}
