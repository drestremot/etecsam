<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportToSeeders extends Command
{
    protected $signature   = 'db:export-seeders';
    protected $description = 'Exporta todos os dados do banco para ExportedDataSeeder.php';

    // Ordem respeitando foreign keys (dependentes depois dos pais)
    protected array $tables = [
        'users',
        'site_themes',
        'teachers',
        'departments',
        'units',
        'sectors',
        'courses',
        'subjects',
        'laboratories',
        'events',
        'event_photos',
        'documents',
        'projects',
        'partners',
        'course_coordinators',
    ];

    // Tabelas que devem ser ignoradas (sistema, cache, etc.)
    protected array $skip = [
        'migrations', 'cache', 'cache_locks', 'sessions',
        'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens',
        'sqlite_sequence', 'posts',
    ];

    public function handle(): int
    {
        $this->info('Exportando dados do banco para seeder...');
        $this->newLine();

        $sections = [];
        $totalRows = 0;

        foreach ($this->tables as $table) {
            try {
                $rows = DB::table($table)->get()->toArray();
            } catch (\Exception) {
                $this->warn("  Tabela '{$table}' não encontrada — ignorada.");
                continue;
            }

            $count = count($rows);

            if ($count === 0) {
                $this->line("  <fg=gray>• {$table}: vazia, ignorada</>");
                continue;
            }

            $sections[] = $this->buildSection($table, $rows);
            $totalRows += $count;
            $this->info("  ✓ {$table}: {$count} " . ($count === 1 ? 'registro' : 'registros'));
        }

        if (empty($sections)) {
            $this->warn('Banco vazio — nenhum seeder gerado.');
            return 1;
        }

        // Monta o arquivo seeder final
        $seederPath = database_path('seeders/ExportedDataSeeder.php');
        File::put($seederPath, $this->buildSeederFile($sections));

        $this->newLine();
        $this->info("✅ Seeder gerado: database/seeders/ExportedDataSeeder.php");
        $this->info("   Total: {$totalRows} registros em " . count($sections) . " tabelas");
        $this->newLine();
        $this->line('  Para restaurar no servidor:');
        $this->line('  <fg=yellow>php artisan db:seed --class=ExportedDataSeeder</>');

        return 0;
    }

    protected function buildSection(string $table, array $rows): string
    {
        $rowsPhp = $this->rowsToPhp($rows);

        return <<<PHP

        // --- {$table} ---
        DB::statement('DELETE FROM {$table}');
        DB::table('{$table}')->insert({$rowsPhp});
PHP;
    }

    protected function buildSeederFile(array $sections): string
    {
        $date    = now()->format('Y-m-d H:i:s');
        $body    = implode("\n", $sections);

        // Ordem inversa das tabelas para o DELETE (respeitando FKs)
        $reversed = array_reverse($this->tables);
        $truncates = implode("\n        ", array_map(
            fn($t) => "DB::statement('DELETE FROM {$t}');",
            $reversed
        ));

        return <<<PHP
<?php

/**
 * ExportedDataSeeder — gerado automaticamente em {$date}
 * Comando: php artisan db:export-seeders
 *
 * Para restaurar: php artisan db:seed --class=ExportedDataSeeder
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExportedDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        // Limpa todas as tabelas antes de inserir
        {$truncates}

        // Insere os dados na ordem correta
{$body}

        DB::statement('PRAGMA foreign_keys = ON');
    }
}
PHP;
    }

    protected function rowsToPhp(array $rows): string
    {
        $lines = [];

        foreach ($rows as $row) {
            $row    = (array) $row;
            $fields = [];

            foreach ($row as $key => $value) {
                $fields[] = "                '{$key}' => " . $this->phpValue($value);
            }

            $lines[] = "            [\n" . implode(",\n", $fields) . ",\n            ]";
        }

        return "[\n" . implode(",\n", $lines) . "\n        ]";
    }

    protected function phpValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return (string) $value;
        }

        // String: escapa aspas simples e barras
        $escaped = str_replace(['\\', "'"], ['\\\\', "\\'"], (string) $value);
        return "'{$escaped}'";
    }
}
