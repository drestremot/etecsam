<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Document;
use App\Models\EventPhoto;
use App\Models\Laboratory;
use App\Models\Partner;
use App\Models\Project;
use App\Models\SiteTheme;
use App\Models\Teacher;
use App\Models\Unit;
use Illuminate\Console\Command;

class AuditImages extends Command
{
    protected $signature = 'images:audit';
    protected $description = 'Verifica se os arquivos de imagem/documento referenciados no banco existem no disco';

    protected array $targets = [
        Teacher::class    => ['photo'],
        Unit::class       => ['image'],
        Course::class     => ['image', 'photo'],
        EventPhoto::class => ['path'],
        Laboratory::class => ['photo'],
        Partner::class    => ['logo'],
        Project::class    => ['image'],
        SiteTheme::class  => ['popup_image'],
        Document::class   => ['file_path'],
    ];

    public function handle(): int
    {
        $missing = 0;
        $checked = 0;

        foreach ($this->targets as $modelClass => $fields) {
            $label = class_basename($modelClass);

            foreach ($modelClass::all() as $record) {
                foreach ($fields as $field) {
                    $value = $record->{$field} ?? null;

                    if (!$value) {
                        continue;
                    }

                    if (stripos($value, 'http://') === 0 || stripos($value, 'https://') === 0) {
                        continue;
                    }

                    $checked++;
                    $resolvedPath = $this->resolvePath($value);
                    $exists = file_exists($resolvedPath);

                    if (!$exists) {
                        $missing++;
                        $name = $record->name ?? $record->title ?? $record->id;
                        $this->line("MISSING  [{$label} #{$record->id} \"{$name}\".{$field}] {$value}");
                        $this->line("         esperado em: {$resolvedPath}");
                    }
                }
            }
        }

        $this->info("Verificadas {$checked} referencias. {$missing} arquivo(s) ausente(s).");

        return self::SUCCESS;
    }

    protected function resolvePath(string $path): string
    {
        $trimmed = ltrim(trim($path), '/');
        $lower = strtolower($trimmed);

        foreach (['public/', 'storage/app/public/', 'public/storage/'] as $prefix) {
            if (strpos($lower, $prefix) === 0) {
                $trimmed = substr($trimmed, strlen($prefix));
                $lower = substr($lower, strlen($prefix));
            }
        }

        if (str_starts_with($lower, 'imagens/') || str_starts_with($lower, 'images/') || str_starts_with($lower, 'icons/')) {
            return public_path($trimmed);
        }

        if (str_starts_with($lower, 'storage/')) {
            return public_path($trimmed);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->path($trimmed);
    }
}
