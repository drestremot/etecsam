<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

use App\Models\Course;
use App\Models\Sector;
use App\Models\Post;
use App\Models\Teacher;
use App\Models\Event;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Subject;
use App\Models\Laboratory;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // =========================================================================
        // 1. USUÁRIOS ADMINISTRATIVOS (MANTIDO)
        // =========================================================================

        // Crie aqui seus usuários fixos (Diretoria, Admins) se necessário
        // Exemplo:
        // User::factory()->create(['email' => 'admin@etec.sp.gov.br']);

        // =========================================================================
        // 2. IMPORTAÇÃO DO QUADRO DE AULAS (AUTOMÁTICO)
        // =========================================================================

        // =========================================================================
        // CONFIGURAÇÃO
        // =========================================================================
        $csvPath = database_path('seeders/data/QUADRO-AULAS.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("ERRO: Arquivo CSV não encontrado em: $csvPath");
            return;
        }

        $file = fopen($csvPath, 'r');
        $rowNumber = 0;

        $this->command->info("=== INICIANDO IMPORTAÇÃO (UTF-8) ===");

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $rowNumber++;

            // Pula as 3 primeiras linhas (Cabeçalhos do arquivo)
            if ($rowNumber <= 3) continue;

            // REMOVI A FUNÇÃO 'fixEncoding' QUE CAUSAVA O ERRO DE CARACTERES
            // O arquivo já é UTF-8, então lemos direto.

            // Verifica colunas mínimas
            if (count($row) < 10) continue;

            // Mapeamento das Colunas (0 a 9)
            $cursoNomeBase = trim($row[0]);
            $turmaNome     = trim($row[2]);
            $turmaApelido  = trim($row[3]);
            $periodoRaw    = trim($row[4]);
            $componente    = trim($row[5]);
            $ha            = intval($row[6]);
            $origem        = trim($row[7]);
            $profNome      = trim($row[9]); // Professor

            if (empty($cursoNomeBase) || empty($componente)) continue;

            // --- 1. SÉRIE/MÓDULO ---
            $serieSuffix = '';
            if (str_starts_with($turmaApelido, '1º')) $serieSuffix = ' - 1º Módulo/Série';
            elseif (str_starts_with($turmaApelido, '2º')) $serieSuffix = ' - 2º Módulo/Série';
            elseif (str_starts_with($turmaApelido, '3º')) $serieSuffix = ' - 3º Módulo/Série';

            $cursoNomeFinal = $cursoNomeBase . $serieSuffix;

            // --- 2. UNIDADE ---
            $unitInfo = $this->getUnitFromTurma($turmaNome);
            $unit = Unit::firstOrCreate(
                ['name' => $unitInfo['name']],
                ['city' => $unitInfo['city']]
            );

            // --- 3. PROFESSOR ---
            $teacher = null;
            if (!empty($profNome) && strlen($profNome) > 3) {
                $photoName = Str::slug($profNome) . '.jpg';

                $teacher = Teacher::firstOrCreate(
                    ['name' => $profNome],
                    [
                        'role' => 'Professor',
                        'specialty' => 'Docente',
                        'email' => Str::slug($profNome, '.') . '@etec.sp.gov.br',
                        'photo' => 'imagens/docentes/' . $photoName,
                    ]
                );
            }

            // --- 4. CURSO ---
            $periodo = 'Integral';
            if (stripos($periodoRaw, 'Noite') !== false) $periodo = 'Noturno';
            elseif (stripos($periodoRaw, 'Manhã') !== false) $periodo = 'Matutino';
            elseif (stripos($periodoRaw, 'Tarde') !== false) $periodo = 'Vespertino';

            $course = $this->createCourse($cursoNomeFinal, $periodo, $unit);

            // --- 5. DISCIPLINA (Força Atualização) ---
            if (stripos($origem, 'Matriz') !== false) {
                $componente .= ' - Turma A';
            } elseif (stripos($origem, 'Divisão') !== false) {
                $componente .= ' - Turma B';
            }

            // Garante que o professor seja vinculado, mesmo se a disciplina já existir
            Subject::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'name' => $componente
                ],
                [
                    'teacher_id' => $teacher ? $teacher->id : null,
                    'workload' => ($ha > 0) ? $ha * 20 : 80,
                    'ptd_file' => '#',
                ]
            );
        }

        fclose($file);
        $this->command->info("Importação Finalizada com Sucesso!");

        // 2. COLABORADORES DA EQUIPE
        $equipeAdm = [
            [
                'name' => 'Carlos Silva',
                'role' => 'Agente Administrativo',
                'specialty' => 'Responsável pelo Patrimônio e Compras.',
                'email' => 'carlos.adm@etec.sp.gov.br',
                'phone' => '(18) 3722-1234 Ramal 210',
            ],
            [
                'name' => 'Fernanda Souza',
                'role' => 'Assistente de RH',
                'specialty' => 'Especialista em Folha de Pagamento e Benefícios.',
                'email' => 'fernanda.rh@etec.sp.gov.br',
                'phone' => '(18) 3722-1234 Ramal 211',
            ],
            [
                'name' => 'Roberto Oliveira',
                'role' => 'Auxiliar Financeiro',
                'specialty' => 'Controle de Verbas e Prestação de Contas (APM).',
                'email' => 'roberto.fin@etec.sp.gov.br',
                'phone' => '(18) 3722-1234 Ramal 212',
            ],
        ];

        foreach ($equipeAdm as $func) {
            \App\Models\Teacher::updateOrCreate(
                ['email' => $func['email']],
                $func
            );
        }

        // 3. ARQUIVOS PARA DOWNLOAD (Diretoria de Serviços)
        $docsAdm = [
            ['title' => 'Requerimento Padrão de Funcionário', 'category' => 'Administrativo', 'file_path' => '#'],
            ['title' => 'Formulário de Auxílio Transporte', 'category' => 'Administrativo', 'file_path' => '#'],
            ['title' => 'Modelo de Declaração de Bens', 'category' => 'Administrativo', 'file_path' => '#'],
            ['title' => 'Manual de Procedimentos - Compras', 'category' => 'Administrativo', 'file_path' => '#'],
            ['title' => 'Calendário de Pagamentos 2026', 'category' => 'Administrativo', 'file_path' => '#'],
        ];

        foreach ($docsAdm as $doc) {
            \App\Models\Document::create(array_merge($doc, ['published_at' => now()]));
        }



        // ... após os códigos da diretoria de serviços ...

        // 4. SECRETARIA ACADÊMICA (Equipe)
        $dirSecretaria = \App\Models\Teacher::updateOrCreate(
            ['email' => 'secretaria.academica@etec.sp.gov.br'],
            [
                'name' => 'Maria Helena (Secretária Acadêmica)',
                'role' => 'Diretora de Secretaria Acadêmica',
                'specialty' => 'Gestão de Vida Escolar e Legislação Educacional.',
                'phone' => '(18) 3722-1234 Ramal 202',
                'photo' => 'imagens/equipe/dir_secretaria.jpg',
            ]
        );

        $equipeSec = [
            [
                'name' => 'João Pedro',
                'role' => 'Agente Técnico e Administrativo',
                'specialty' => 'Atendimento ao Aluno e Matrículas.',
                'email' => 'joao.sec@etec.sp.gov.br',
                'phone' => '(18) 3722-1234 Ramal 203',
            ],
            [
                'name' => 'Juliana Costa',
                'role' => 'Agente Técnico e Administrativo',
                'specialty' => 'Expedição de Diplomas e Históricos.',
                'email' => 'juliana.sec@etec.sp.gov.br',
                'phone' => '(18) 3722-1234 Ramal 204',
            ],
        ];

        foreach ($equipeSec as $func) {
            \App\Models\Teacher::updateOrCreate(['email' => $func['email']], $func);
        }

        // 5. DOCUMENTOS DA SECRETARIA (Downloads)
        $docsSec = [
            ['title' => 'Requerimento de Aproveitamento de Estudos', 'category' => 'Secretaria', 'file_path' => '#'],
            ['title' => 'Solicitação de Trancamento de Matrícula', 'category' => 'Secretaria', 'file_path' => '#'],
            ['title' => 'Modelo de Procuração para Matrícula', 'category' => 'Secretaria', 'file_path' => '#'],
            ['title' => 'Lista de Documentos para Matrícula', 'category' => 'Secretaria', 'file_path' => '#'],
            ['title' => 'Regimento Comum das Etecs', 'category' => 'Secretaria', 'file_path' => '#'],
        ];

        foreach ($docsSec as $doc) {
            \App\Models\Document::create(array_merge($doc, ['published_at' => now()]));
        }


        // 0. SUPERINTENDENTE
\App\Models\Teacher::updateOrCreate(
    ['email' => 'marcos.estremote@etec.sp.gov.br'], // Seu email chave
    [
        'name' => 'Marcos Antonio Estremote',
        'role' => 'Superintendente',
        'specialty' => 'Mestre em Ciência da Computação, Especialista em Gestão Educacional e Tecnologia.', // Seu Mini-currículo
        'phone' => '(18) 3722-1234',
        'photo' => 'imagens/equipe/marcos-estremote.jpg', // Coloque sua foto nesta pasta
        'lattes_url' => 'http://lattes.cnpq.br/SEU_ID_LATTES_AQUI', // Troque pelo seu link real
    ]
);





        $this->command->info("Cadastrando Laboratórios e Unidades Didáticas...");
        \App\Models\Sector::truncate();

        // Texto padrão pedagógico para facilitar (você pode personalizar um por um se quiser)
        $textoBase = "Esta unidade didática é fundamental para a formação prática dos alunos dos cursos de MTEC-PI em Agropecuária, Agronegócio, Florestas e Zootecnia. Aqui, os estudantes vivenciam a realidade do mercado de trabalho, aplicando conceitos de sustentabilidade, tecnologia e gestão produtiva.";

        $laboratorios = [
            // --- ÁREA DE INDÚSTRIA ---
            [
                'name' => 'Agroindústria',
                'icon' => 'factory',
                'summary' => 'Processamento em escala industrial e tecnologias de produção.',
                'description' => "Nossa Agroindústria é uma planta piloto completa. $textoBase Os alunos aprendem desde a recepção da matéria-prima até a embalagem final, focando em controle de qualidade e legislação sanitária.",
                'images' => [
                    'https://images.unsplash.com/photo-1595246140625-573b715d11dc?w=800&q=80', // Fábrica
                    'https://images.unsplash.com/photo-1606859191214-25806e8e2423?w=800&q=80', // Processamento
                    'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&q=80', // Tecnologia
                ]
            ],
            [
                'name' => 'Processamento de Alimentos',
                'icon' => 'utensils',
                'summary' => 'Tecnologia de alimentos e segurança alimentar.',
                'description' => "Laboratório focado na transformação de alimentos. $textoBase Desenvolvemos novos produtos e estudamos métodos de conservação.",
                'images' => [
                    'https://images.unsplash.com/photo-1606914501449-1a96e81d7b27?w=800&q=80', // Cozinha industrial
                    'https://images.unsplash.com/photo-1556910103-1c02745a30bf?w=800&q=80', // Chef
                ]
            ],
            [
                'name' => 'Práticas de Derivados do Leite',
                'icon' => 'cheese',
                'summary' => 'Produção de queijos, iogurtes e laticínios.',
                'description' => "Unidade equipada para o processamento completo do leite. $textoBase Alunos de Zootecnia e Agropecuária acompanham a cadeia produtiva desde a ordenha até a produção de queijos finos e iogurtes.",
                'images' => [
                    'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=800&q=80', // Queijos
                    'https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=800&q=80', // Leite
                ]
            ],
            [
                'name' => 'Práticas de Processamento de Carnes',
                'icon' => 'meat',
                'summary' => 'Tecnologia de carnes, cortes e embutidos.',
                'description' => "Laboratório de carnes e embutidos. $textoBase Foco em cortes nobres, defumação e produção de embutidos, respeitando rigorosamente as normas de higiene.",
                'images' => [
                    'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=800&q=80', // Carne
                    'https://images.unsplash.com/photo-1558030006-450675393462?w=800&q=80', // Açougue
                ]
            ],

            // --- ÁREA ANIMAL ---
            [
                'name' => 'Manejo de Bovinos (Leite e Corte)',
                'icon' => 'cow',
                'summary' => 'Pecuária de alta performance e ordenha mecânica.',
                'description' => "Referência regional em genética bovina. $textoBase Alunos de Zootecnia praticam manejo sanitário, nutricional e reprodutivo (inseminação artificial) em nosso rebanho de alta linhagem.",
                'images' => [
                    'https://images.unsplash.com/photo-1546445317-29f4545e9d53?w=800&q=80', // Vaca
                    'https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?w=800&q=80', // Gado pasto
                    'https://images.unsplash.com/photo-1596733430284-f7437764b1a9?w=800&q=80', // Bezerro
                ]
            ],
            [
                'name' => 'Criação de Suínos',
                'icon' => 'pig',
                'summary' => 'Suinocultura tecnificada e manejo sanitário.',
                'description' => "Unidade de suinocultura completa (ciclo completo). $textoBase Estudamos nutrição de precisão e manejo de dejetos integrado ao biodigestor.",
                'images' => [
                    'https://images.unsplash.com/photo-1604848698030-c434ba08ece1?w=800&q=80', // Porco
                    'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=800&q=80', // Leitões
                ]
            ],
            [
                'name' => 'Aves Poedeiras e Bem Estar Animal',
                'icon' => 'chicken',
                'summary' => 'Avicultura sustentável focada no bem-estar.',
                'description' => "Laboratório de avicultura focado no sistema 'Cage Free' (livre de gaiolas). $textoBase Enfatizamos o bem-estar animal como fator produtivo para os cursos de Zootecnia e Agropecuária.",
                'images' => [
                    'https://images.unsplash.com/photo-1548550027-211843312949?w=800&q=80', // Galinha
                    'https://images.unsplash.com/photo-1569396116180-2c1b712f5341?w=800&q=80', // Ovos
                ]
            ],
            [
                'name' => 'Piscicultura',
                'icon' => 'fish',
                'summary' => 'Tanques de criação e manejo de peixes.',
                'description' => "Tanques escavados e sistema de recirculação. $textoBase Monitoramento de qualidade da água e biometria de peixes nativos e exóticos.",
                'images' => [
                    'https://images.unsplash.com/photo-1522069169874-c58ec4b76be5?w=800&q=80', // Peixe
                    'https://images.unsplash.com/photo-1516916753473-75cfa697953e?w=800&q=80', // Água
                ]
            ],
            [
                'name' => 'Apicultura e Meliponicultura',
                'icon' => 'bee',
                'summary' => 'Abelhas com e sem ferrão e produção de mel.',
                'description' => "Apiário e Meliponário didático. $textoBase Essencial para entender a polinização nas culturas agrícolas (Florestas/Agro) e a produção de mel e própolis.",
                'images' => [
                    'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=800&q=80', // Abelha
                    'https://images.unsplash.com/photo-1587049352847-81a56d773cae?w=800&q=80', // Mel
                ]
            ],

            // --- ÁREA VEGETAL ---
            [
                'name' => 'Agrofloresta (ILPF)',
                'icon' => 'tree',
                'summary' => 'Integração Lavoura, Pecuária e Floresta.',
                'description' => "Área demonstrativa de sistemas complexos. $textoBase Onde os alunos de Florestas e Agropecuária aprendem a produzir conservando o solo e sequestrando carbono.",
                'images' => [
                    'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&q=80', // Floresta
                    'https://images.unsplash.com/photo-1502082553048-f009c37129b9?w=800&q=80', // Árvores
                ]
            ],
            [
                'name' => 'Plantas Medicinais e Aromáticas',
                'icon' => 'leaf',
                'summary' => 'Cultivo e estudo de princípios ativos naturais.',
                'description' => "Horto medicinal completo. $textoBase Estudo de botânica, extração de óleos essenciais e fitoterapia.",
                'images' => [
                    'https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=800&q=80', // Folha
                    'https://images.unsplash.com/photo-1515694346937-94d85e41e6f0?w=800&q=80', // Óleos
                ]
            ],
            [
                'name' => 'Banco de Sementes Crioulas',
                'icon' => 'seed',
                'summary' => 'Preservação da biodiversidade e genética vegetal.',
                'description' => "Guardiões da biodiversidade. $textoBase Os alunos aprendem sobre melhoramento genético, seleção de sementes e soberania alimentar.",
                'images' => [
                    'https://images.unsplash.com/photo-1492496913980-501348b61369?w=800&q=80', // Sementes
                ]
            ],
            [
                'name' => 'Laboratório de Forrageiras',
                'icon' => 'grass',
                'summary' => 'Pastagens e nutrição animal.',
                'description' => "Campo agrostológico com diversas espécies de capins e leguminosas. Base para a nutrição dos animais da fazenda.",
                'images' => [
                    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80', // Pasto
                ]
            ],

             // --- TECNOLOGIA E CIÊNCIAS ---
             [
                'name' => 'Laboratório de Informática',
                'icon' => 'computer',
                'summary' => 'Tecnologia da informação e desenvolvimento de sistemas.',
                'description' => "Espaço maker de alta tecnologia. $textoBase Equipado para os cursos de Desenvolvimento de Sistemas e Agronegócio Digital.",
                'images' => [
                    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&q=80', // Computador
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&q=80', // Coding
                ]
            ],
            [
                'name' => 'Laboratório de Física e Química',
                'icon' => 'flask',
                'summary' => 'Análises laboratoriais e experimentos científicos.',
                'description' => "Laboratório de ciências puras e aplicadas. $textoBase Realização de análises de solo (Agronomia), bromatologia (Zootecnia) e físico-química.",
                'images' => [
                    'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=800&q=80', // Lab
                    'https://images.unsplash.com/photo-1576086213369-97a306d36557?w=800&q=80', // Microscópio
                ]
            ],
        ];

        foreach ($laboratorios as $lab) {
            $lab['slug'] = Str::slug($lab['name']);
            \App\Models\Sector::create($lab);
        }
    }



// --- FUNÇÕES AUXILIARES ---

   private function getUnitFromTurma($turma)
    {
        if (stripos($turma, 'Álvaro Guião') !== false) return ['name' => 'Escola Estadual Álvaro Guião', 'city' => 'Andradina'];
        if (stripos($turma, 'Noêmia Perotti') !== false || stripos($turma, 'Noêmi Perotti') !== false) return ['name' => 'Colégio EE Noêmia Dias Perotti', 'city' => 'Mirandópolis'];
        if (stripos($turma, 'Vicente Barbosa') !== false) return ['name' => 'Colégio E.E Vicente Barbosa', 'city' => 'Valparaíso'];
        if (stripos($turma, 'Hélio Faria') !== false) return ['name' => 'EMEF Hélio Faria', 'city' => 'Mirandópolis'];
        if (stripos($turma, 'Gildo Pereira') !== false) return ['name' => 'Colégio EMEF Gildo Pereira', 'city' => 'Nova Independência'];
        if (stripos($turma, 'Fundação') !== false) return ['name' => 'Fundação Educacional (FEA)', 'city' => 'Andradina'];
        if (stripos($turma, 'Maria Daúrea') !== false) return ['name' => 'Colégio Maria Dauria', 'city' => 'Castilho'];
        if (stripos($turma, 'Castilho') !== false) return ['name' => 'Colégio EMEF Doutor Youssef Neif Kassab', 'city' => 'Castilho'];

        return ['name' => 'ETEC Sede (Escola Agrícola)', 'city' => 'Andradina'];
    }

    private function createCourse($title, $type, $unit) {
        $slug = Str::slug($title . '-' . $unit->city . '-' . $unit->id);
        $slug = substr($slug, 0, 190);

        return Course::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'type' => $type,
                'description' => 'Curso técnico profissionalizante.',
                'content' => '',
                'unit_id' => $unit->id,
                'decentralized_coordinator_id' => null,
            ]
        );
    }
}
