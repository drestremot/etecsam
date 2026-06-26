<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Sector;
use App\Models\Event; // Importante: garante que o Model de Eventos seja reconhecido
use App\Models\Document;
use App\Models\Teacher;
use App\Models\Partner;

class SiteController extends Controller
{

    public function sector($slug)
    {
        $sector = \App\Models\Sector::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('pages.sector', compact('sector'));
    }

    public function index()
    {
        // 1. Buscamos as UNIDADES e contamos quantos cursos cada uma tem
        $units = \App\Models\Unit::withCount('courses')->where('is_active', true)->get();

        // 2. Buscamos eventos (se houver) para o calendário
        $nextEvents = \App\Models\Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        // 3. Enviamos para a tela 'welcome'
        return view('welcome', compact('units', 'nextEvents'));
    }

    public function unit($id)
    {
        // PÁGINA INTERNA: Carrega a unidade, o coordenador e os cursos dela
        $unit = \App\Models\Unit::with(['courses', 'coordinator'])->where('is_active', true)->findOrFail($id);

        return view('pages.unit', compact('unit'));
    }


    public function home()
    {
        // 1. Busca os setores (Escola Fazenda)
        $sectors = Sector::where('is_active', true)->get();

        // 2. Busca os cursos para a variável $featuredCourses que deu erro
        // Se não tiver cursos no banco, retorna uma lista vazia para não quebrar
        //$featuredCourses = Course::take(3)->get();
        //$featuredCourses = Course::orderBy('title', 'asc')->get();
        $featuredCourses = Course::where('is_active', true)->get();

        // 3. Busca os próximos eventos (se a tabela events existir)
        // Se você ainda não criou a tabela de eventos, pode comentar estas linhas abaixo:
        try {
            $nextEvents = Event::where('is_active', true)
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take(2)
                ->get();
        } catch (\Exception $e) {
            $nextEvents = []; // Retorna vazio se der erro no banco
        }

        // 4. Envia TUDO para a view 'home'
        return view('home', compact('sectors', 'featuredCourses', 'nextEvents'));
    }

    public function secretariat()
    {
        // Agrupa os documentos por categoria para facilitar a exibição
        $documents = Document::all()->groupBy('category');
        return view('pages.secretariat', compact('documents'));
    }

    public function contact()
    {
        return view('pages.contact');
    }


    public function superintendence()
    {
        // Diretor da Unidade (Marcos Antonio Estremote)
        $director = \App\Models\Teacher::where('role', 'like', '%Diretor%')
            ->orWhere('role', 'like', '%Superintendente%')
            ->orderByRaw("CASE WHEN role LIKE '%Diretor de Escola%' THEN 0 ELSE 1 END")
            ->first();

        // Equipe de apoio da Superintendência
        $staff = \App\Models\Teacher::where('role', 'like', '%Vice-Diretor%')
            ->orWhere('role', 'like', '%Assistente de Direção%')
            ->get();

        $downloads = \App\Models\Document::where('category', 'Superintendência')->get();

        return view('pages.superintendence', compact('director', 'staff', 'downloads'));
    }

    public function academicDivision()
    {
        // Responsável pela Gestão Pedagógica (Coordenadora Pedagógica)
        $director = \App\Models\Teacher::where('role', 'like', '%Coordenadora Pedagóg%')
            ->orWhere('role', 'like', '%Coordenador Pedagóg%')
            ->first();

        // Toda a equipe pedagógica agrupada por cargo
        $staffAll = \App\Models\Teacher::where(function ($q) {
                $q->where('role', 'like', '%Coordenador%')
                  ->orWhere('role', 'like', '%Coordenadora%')
                  ->orWhere('role', 'like', '%Pedagóg%')
                  ->orWhere('role', 'like', '%Orientador%')
                  ->orWhere('role', 'like', '%Orientadora%');
            })
            ->where('role', 'not like', '%Secretaria%')
            ->where(function ($q) use ($director) {
                if ($director) $q->where('id', '!=', $director->id);
            })
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        // Agrupa por cargo para exibição em seções
        $staffGroups = $staffAll->groupBy('role');

        $downloads = \App\Models\Document::where('category', 'Acadêmico')->get();

        return view('pages.academic-division', compact('director', 'staffGroups', 'downloads'));
    }

    public function administrative()
    {
        // Diretora de Serviços (Myruane)
        $director = \App\Models\Teacher::where('role', 'like', '%Serviços%')
            ->orderByRaw("CASE WHEN role LIKE '%Diretora%' OR role LIKE '%Diretor%' THEN 0 ELSE 1 END")
            ->first();

        // Colaboradores administrativos
        $staff = \App\Models\Teacher::where(function ($q) {
                $q->where('role', 'like', '%Administrativo%')
                  ->orWhere('role', 'like', '%Financeiro%')
                  ->orWhere('role', 'like', '%Recursos Humanos%')
                  ->orWhere('role', 'like', '%Manutenção%')
                  ->orWhere('role', 'like', '%Agente%');
            })
            ->where(function ($q) use ($director) {
                if ($director) $q->where('id', '!=', $director->id);
            })
            ->get();

        $downloads = \App\Models\Document::where('category', 'Administrativo')->get();

        $links = [
            ['name' => 'GDAE',          'desc' => 'Gestão Escolar',       'url' => 'https://www.gdae.sp.gov.br/',         'icon' => '📋'],
            ['name' => 'SEP',           'desc' => 'Portal do Servidor',    'url' => 'https://www.sep.sp.gov.br/',          'icon' => '👤'],
            ['name' => 'e-Folha',          'desc' => 'Folha de Pagamento',    'url' => 'https://www.e-folha.prodesp.sp.gov.br/',             'icon' => '💰'],
            ['name' => 'SIEEESP',       'desc' => 'Legislação',            'url' => 'https://www.sieeesp.org.br/',         'icon' => '⚖️'],
            ['name' => 'Transparência', 'desc' => 'Dados Públicos',        'url' => 'https://www.cps.sp.gov.br/transparencia/', 'icon' => '🔍'],
        ];

        return view('pages.administrative', compact('director', 'staff', 'links', 'downloads'));
    }

    public function institutional()
    {
        // Tenta encontrar o Superintendente ou Diretor
        $direcaoGeral = Teacher::where('role', 'like', '%Superintendente%')
            ->orWhere('role', 'like', '%Diretor de Escola%')
            ->first();

        // Pega os outros departamentos
        $departamentos = Teacher::where('role', 'not like', '%Superintendente%')
            ->where('role', 'not like', '%Diretor de Escola%')
            ->get();

        // Envia para a view
        return view('pages.institutional', compact('direcaoGeral', 'departamentos'));
    }


    // ... dentro da classe SiteController ...

    public function agenda()
    {
        // Busca eventos futuros, ordenados por data
        // O 'get()->groupBy...' agrupa os resultados pelo mês/ano (ex: "03/2026")
        $events = Event::with('photos')
            ->where('is_active', true)
            ->whereYear('start_date', now()->year)
            ->orderBy('start_date', 'asc')
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->start_date)->format('m/Y');
            });

        // Aniversariantes do mês atual
        $birthdays = Teacher::whereNotNull('birth_date')
            ->whereMonth('birth_date', now()->month)
            ->orderByRaw("strftime('%d', birth_date)")
            ->get();

        // Aniversariantes de HOJE (destaque)
        $todayBirthdays = $birthdays->filter(
            fn($t) => \Carbon\Carbon::parse($t->birth_date)->day === now()->day
        )->values();

        return view('pages.agenda', compact('events', 'birthdays', 'todayBirthdays'));
    }

    public function show($slug)
    {
        // Busca o curso pelo "apelido" (slug) no banco de dados
        // Se não achar, mostra erro 404 automaticamente
        $course = \App\Models\Course::with(['unit', 'coordinators', 'subjects.teacher'])
                                     ->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('pages.course-detail', compact('course'));
    }

    public function courses()
    {
        // Busca todas as unidades que possuem cursos, trazendo junto os cursos e o coordenador
        $units = \App\Models\Unit::with(['coordinator', 'courses' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->whereHas('courses', fn($q) => $q->where('is_active', true))
            ->get();

        return view('pages.courses-list', compact('units'));
    }

    public function library()
    {
        // Busca apenas os documentos da categoria Biblioteca
        $documents = \App\Models\Document::where('category', 'Biblioteca')->get();
        return view('pages.library', compact('documents'));
    }

    public function academic()
    {
        // 1. Busca a Equipe
        $director = \App\Models\Teacher::where('role', 'like', '%acadêmica%')->first();
        $staff = \App\Models\Teacher::where('role', 'like', '%secretaria%')->get();

        // 2. Busca os Downloads da categoria 'Secretaria'
        $downloads = \App\Models\Document::where('category', 'Secretaria')->get();

        // 3. Links Úteis para Alunos
        $links = [
            ['name' => 'NSA Online', 'desc' => 'Notas e Frequência', 'url' => 'https://nsa.cps.sp.gov.br/', 'icon' => '🎓'],
            ['name' => 'Vestibulinho Etec', 'desc' => 'Inscrições e Provas', 'url' => 'https://www.vestibulinhoetec.com.br/', 'icon' => '📝'],
            ['name' => 'Email Institucional', 'desc' => 'Acesso ao Teams/Email', 'url' => 'http://mail.etec.sp.gov.br/', 'icon' => '📧'],
            ['name' => 'Carteirinha Digital', 'desc' => 'Documento do Estudante', 'url' => '#', 'icon' => '🆔'],
            ['name' => 'Calendário Escolar', 'desc' => 'Datas Importantes', 'url' => '#', 'icon' => '📅'],
        ];

        return view('pages.academic', compact('director', 'staff', 'links', 'downloads'));
    }

    public function cooperative()
    {
        $managers = \App\Models\CooperativeManager::where('is_active', true)->orderBy('name')->get();
        $members = \App\Models\CooperativeMember::where('is_active', true)->orderBy('name')->get();
        $reports = \App\Models\CooperativeReport::orderByDesc('published_at')->get();

        return view('pages.cooperative', compact('managers', 'members', 'reports'));
    }
}
