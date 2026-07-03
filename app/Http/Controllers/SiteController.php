<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Course;
use App\Models\Sector;
use App\Models\Event;
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

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'nome'      => 'required|string|max:100',
            'telefone'  => 'nullable|string|max:20',
            'assunto'   => 'required|string|max:100',
            'email'     => 'required|email|max:150',
            'mensagem'  => 'required|string|min:10|max:3000',
        ], [
            'nome.required'     => 'Por favor, informe seu nome.',
            'email.required'    => 'Por favor, informe seu e-mail.',
            'email.email'       => 'Informe um e-mail válido.',
            'mensagem.required' => 'Por favor, escreva sua mensagem.',
            'mensagem.min'      => 'A mensagem deve ter pelo menos 10 caracteres.',
        ]);

        $destinatario = $this->emailPorAssunto($data['assunto']);

        try {
            Mail::send('emails.contact', $data, function ($m) use ($data, $destinatario) {
                $m->to($destinatario)
                  ->replyTo($data['email'], $data['nome'])
                  ->subject('Contato via site — ' . $data['assunto']);
            });

            return redirect()->route('contact')
                ->with('success', 'Mensagem enviada com sucesso! Responderemos em até 2 dias úteis.');
        } catch (\Exception $e) {
            return redirect()->route('contact')
                ->withInput()
                ->with('error', 'Não foi possível enviar a mensagem. Tente novamente ou entre em contato por telefone.');
        }
    }

    private function emailPorAssunto(string $assunto): string
    {
        return match(true) {
            str_contains($assunto, 'Secretaria') || str_contains($assunto, 'Documentos') || str_contains($assunto, 'Vestibulinho') => 'e028acad@cps.sp.gov.br',
            str_contains($assunto, 'Pedagógica') || str_contains($assunto, 'Coordenação')                                          => 'e028pedagogica@cps.sp.gov.br',
            str_contains($assunto, 'Parcerias')  || str_contains($assunto, 'Cooperativa') || str_contains($assunto, 'Serviços')    => 'e028adm@cps.sp.gov.br',
            default                                                                                                                 => 'e028dir@cps.sp.gov.br',
        };
    }


    public function superintendence()
    {
        // Diretor da Unidade (Marcos Antonio Estremote)
        $director = \App\Models\Teacher::where('role', 'like', '%Diretor%')
            ->orWhere('role', 'like', '%Superintendente%')
            ->orderByRaw("CASE WHEN role LIKE '%Diretor de Escola%' THEN 0 ELSE 1 END")
            ->first();

        // Assessor III - responde pela unidade na ausência do Superintendente
        $deputy = \App\Models\Teacher::where('role', 'like', '%Assessor III%')->first();

        // Equipe de apoio da Superintendência
        $staff = \App\Models\Teacher::where('role', 'like', '%Vice-Diretor%')
            ->orWhere('role', 'like', '%Assistente de Direção%')
            ->get();

        $downloads = \App\Models\Document::where('category', 'Superintendência')->get();

        return view('pages.superintendence', compact('director', 'deputy', 'staff', 'downloads'));
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
        $director = \App\Models\Teacher::where('role', 'like', '%Cooperativa%')->first();

        $managers = \App\Models\CooperativeManager::where('is_active', true)->orderBy('name')->get();
        $members = \App\Models\CooperativeMember::where('is_active', true)->orderBy('name')->get();

        $statutes = \App\Models\CooperativeReport::where('category', 'Estatuto')->orderByDesc('published_at')->get();
        $minutes = \App\Models\CooperativeReport::where('category', 'Ata de Reunião')->orderByDesc('published_at')->get();
        $reports = \App\Models\CooperativeReport::where('category', 'Prestação de Contas')->orderByDesc('published_at')->get();

        return view('pages.cooperative', compact('director', 'managers', 'members', 'statutes', 'minutes', 'reports'));
    }

    public function cooperativeFinance()
    {
        return view('pages.cooperative-finance', \App\Support\CooperativeFinanceSummary::compute());
    }

    public function apm()
    {
        $director = \App\Models\Teacher::where('role', 'like', '%APM%')->first();

        $managers = \App\Models\ApmManager::where('is_active', true)->orderBy('name')->get();

        $statutes = \App\Models\ApmReport::where('category', 'Estatuto')->orderByDesc('published_at')->get();
        $minutes = \App\Models\ApmReport::where('category', 'Ata de Reunião')->orderByDesc('published_at')->get();
        $reports = \App\Models\ApmReport::where('category', 'Prestação de Contas')->orderByDesc('published_at')->get();

        return view('pages.apm', compact('director', 'managers', 'statutes', 'minutes', 'reports'));
    }

    public function apmFinance()
    {
        return view('pages.apm-finance', \App\Support\ApmFinanceSummary::compute());
    }

    public function auxiliaryTeachers()
    {
        $staff = \App\Models\Teacher::where('role', 'like', '%Auxiliar Docente%')->orderBy('name')->get();

        return view('pages.staff-group', [
            'pageLabel'    => 'Apoio Institucional',
            'pageTitle'    => 'Auxiliares Docentes',
            'pageSubtitle' => 'Profissionais que apoiam o trabalho pedagógico em sala de aula',
            'iconPath'     => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
            'staff'        => $staff,
            'emptyMessage' => 'Nenhum auxiliar docente cadastrado ainda.',
        ]);
    }

    public function collaborators()
    {
        $staff = \App\Models\Teacher::where('role', 'like', '%Colaborador%')->orderBy('name')->get();

        return view('pages.staff-group', [
            'pageLabel'    => 'Apoio Institucional',
            'pageTitle'    => 'Colaboradores',
            'pageSubtitle' => 'Equipe de apoio às atividades da unidade escolar',
            'iconPath'     => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'staff'        => $staff,
            'emptyMessage' => 'Nenhum colaborador cadastrado ainda.',
        ]);
    }

    public function securityStaff()
    {
        $staff = \App\Models\Teacher::where(function ($q) {
                $q->where('role', 'like', '%Vigilante%')
                  ->orWhere('role', 'like', '%Segurança%');
            })
            ->orderBy('name')->get();

        return view('pages.staff-group', [
            'pageLabel'    => 'Apoio Institucional',
            'pageTitle'    => 'Seguranças (Vigilantes)',
            'pageSubtitle' => 'Equipe responsável pela segurança da unidade escolar',
            'iconPath'     => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'staff'        => $staff,
            'emptyMessage' => 'Nenhum vigilante cadastrado ainda.',
        ]);
    }
}
