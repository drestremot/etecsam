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

        // URL externa — usa como está
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Normalize leading slash e prefixos públicos comuns.
        if (str_starts_with($path, '/')) {
            $path = ltrim($path, '/');
        }

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        if (str_starts_with($path, 'storage/app/public/')) {
            $path = substr($path, strlen('storage/app/public/'));
        }

        // Arquivos estáticos em public/ (imagens/, images/, icons/, etc.)
        if (str_starts_with($path, 'imagens/') || str_starts_with($path, 'images/') || str_starts_with($path, 'icons/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        // Uploads via admin — estão em storage/app/public/
        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }
}
