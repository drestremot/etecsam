<?php

if (!function_exists('photo_url')) {
    /**
     * Retorna a URL correta de uma foto/imagem armazenada no banco.
     *
     * Lida com dois padrões existentes no projeto:
     *  - Caminhos legados: "imagens/docentes/..."  → ficam em public/, usa asset()
     *  - Uploads via admin: "teachers/xxx.png"     → ficam em storage/, usa asset('storage/...')
     *  - URLs externas: "https://..."              → usa diretamente
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

        // Arquivos legados em public/ (imagens/, icons/, etc.)
        if (str_starts_with($path, 'imagens/') || str_starts_with($path, 'icons/')) {
            return asset($path);
        }

        // Uploads via admin — estão em storage/app/public/
        return asset('storage/' . $path);
    }
}
