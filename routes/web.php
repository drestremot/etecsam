<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// ─── Site Público ────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

Route::get('/escola',              [SiteController::class, 'institutional'])->name('institutional');
Route::get('/a-escola',            [SiteController::class, 'institutional']);
Route::get('/cursos',              [SiteController::class, 'coursesList'])->name('courses.index');
Route::get('/unidades',            [SiteController::class, 'coursesList'])->name('units.index');
Route::get('/curso/{slug}',        [SiteController::class, 'show'])->name('courses.show');
Route::get('/secretaria',          [SiteController::class, 'academic'])->name('academic');
Route::get('/contato',             [SiteController::class, 'contact'])->name('contact');
Route::post('/contato',            [SiteController::class, 'sendContact'])->name('contact.send');
Route::get('/fale-conosco',        [SiteController::class, 'contact']);
Route::get('/agenda',              [SiteController::class, 'agenda'])->name('agenda');
Route::get('/superintendencia',    [SiteController::class, 'superintendence'])->name('superintendence');
Route::get('/supervisao-regional', [SiteController::class, 'regionalSupervision'])->name('regional-supervision');
Route::get('/diretoria-academica', [SiteController::class, 'academicDivision'])->name('academic-division');
Route::get('/diretoria-servicos',  [SiteController::class, 'administrative'])->name('administrative');
Route::get('/biblioteca',          [SiteController::class, 'library'])->name('library');
Route::get('/cooperativa',         [SiteController::class, 'cooperative'])->name('cooperative');
Route::get('/cooperativa/financeiro', [SiteController::class, 'cooperativeFinance'])->name('cooperative.finance');
Route::get('/apm',                 [SiteController::class, 'apm'])->name('apm');
Route::get('/apm/financeiro',      [SiteController::class, 'apmFinance'])->name('apm.finance');
Route::get('/auxiliares-docentes', [SiteController::class, 'auxiliaryTeachers'])->name('auxiliary-teachers');
Route::get('/colaboradores',       [SiteController::class, 'collaborators'])->name('collaborators');
Route::get('/segurancas',          [SiteController::class, 'securityStaff'])->name('security-staff');
Route::get('/unidade/{id}',        [SiteController::class, 'unit'])->name('units.show');
Route::get('/unidade-didatica/{slug}', [SiteController::class, 'sector'])->name('sectors.show');
Route::get('/politica-de-privacidade', fn () => view('pages.privacy-policy'))->name('privacy-policy');

use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\MedicalCertificateController;
use App\Http\Controllers\LegalLeaveController;
use App\Http\Controllers\VanReservationController;

// ─── Autenticação (Breeze) ────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Troca obrigatória de senha no primeiro acesso
    Route::get('/alterar-senha',  [\App\Http\Controllers\Auth\PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/alterar-senha', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])->name('password.change.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Painel Integrado & Módulos Operacionais ─────────────────────────────
    Route::get('/dashboard', [TaskBoardController::class, 'dashboard'])->name('dashboard');

    // KanbanTec (Tarefas / Ordens de Serviço)
    Route::get('/tasks',               [TaskBoardController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create',        [TaskBoardController::class, 'create'])->name('tasks.create');
    Route::get('/tasks/report',        [TaskBoardController::class, 'report'])->name('tasks.report');
    Route::get('/tasks/{task}',        [TaskBoardController::class, 'show'])->name('tasks.show');
    Route::post('/tasks',              [TaskBoardController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{task}/status',[TaskBoardController::class, 'updateStatus'])->name('tasks.status');
    Route::patch('/tasks/{task}/status',[TaskBoardController::class, 'updateStatus'])->name('tasks.update-status');

    // Atestados Médicos (AtestadosTec)
    Route::get('/atestados',                              [MedicalCertificateController::class, 'index'])->name('medical-certificates.index');
    Route::get('/atestados/create',                       [MedicalCertificateController::class, 'create'])->name('medical-certificates.create');
    Route::post('/atestados',                             [MedicalCertificateController::class, 'store'])->name('medical-certificates.store');
    Route::get('/atestados/{medicalCertificate}',         [MedicalCertificateController::class, 'show'])->name('medical-certificates.show');
    Route::get('/atestados/{medicalCertificate}/edit',    [MedicalCertificateController::class, 'edit'])->name('medical-certificates.edit');
    Route::put('/atestados/{medicalCertificate}',         [MedicalCertificateController::class, 'update'])->name('medical-certificates.update');
    Route::get('/atestados/{medicalCertificate}/download',[MedicalCertificateController::class, 'download'])->name('medical-certificates.download');
    Route::patch('/atestados/{medicalCertificate}/status',[MedicalCertificateController::class, 'updateStatus'])->name('medical-certificates.updateStatus');
    Route::delete('/atestados/{medicalCertificate}',      [MedicalCertificateController::class, 'destroy'])->name('medical-certificates.destroy');

    // Folgas Previstas em Lei (FolgasTec)
    Route::get('/folgas-legais',                                  [LegalLeaveController::class, 'index'])->name('legal-leaves.index');
    Route::get('/folgas-legais/create',                           [LegalLeaveController::class, 'create'])->name('legal-leaves.create');
    Route::post('/folgas-legais',                                 [LegalLeaveController::class, 'store'])->name('legal-leaves.store');
    Route::get('/folgas-legais/{legalLeave}',                     [LegalLeaveController::class, 'show'])->name('legal-leaves.show');
    Route::get('/folgas-legais/{legalLeave}/edit',                [LegalLeaveController::class, 'edit'])->name('legal-leaves.edit');
    Route::put('/folgas-legais/{legalLeave}',                     [LegalLeaveController::class, 'update'])->name('legal-leaves.update');
    Route::post('/folgas-legais/{legalLeave}/solicitar',          [LegalLeaveController::class, 'requestUsage'])->name('legal-leaves.request-usage');
    Route::patch('/folgas-legais/solicitacoes/{leaveRequest}/avaliar', [LegalLeaveController::class, 'reviewRequest'])->name('legal-leaves.review-request');
    Route::get('/folgas-legais/{legalLeave}/download',            [LegalLeaveController::class, 'download'])->name('legal-leaves.download');
    Route::delete('/folgas-legais/{legalLeave}',                  [LegalLeaveController::class, 'destroy'])->name('legal-leaves.destroy');

    // Reserva de Van Escolar (VanTec)
    Route::get('/van-reservas',                         [VanReservationController::class, 'index'])->name('van-reservations.index');
    Route::get('/van-reservas/create',                  [VanReservationController::class, 'create'])->name('van-reservations.create');
    Route::post('/van-reservas',                        [VanReservationController::class, 'store'])->name('van-reservations.store');
    Route::get('/van-reservas/{vanReservation}',        [VanReservationController::class, 'show'])->name('van-reservations.show');
    Route::get('/van-reservas/{vanReservation}/edit',   [VanReservationController::class, 'edit'])->name('van-reservations.edit');
    Route::put('/van-reservas/{vanReservation}',        [VanReservationController::class, 'update'])->name('van-reservations.update');
    Route::post('/van-reservas/{vanReservation}/approve',[VanReservationController::class, 'approve'])->name('van-reservations.approve');
    Route::patch('/van-reservas/{vanReservation}/approve',[VanReservationController::class, 'approve']);
    Route::post('/van-reservas/{vanReservation}/reject', [VanReservationController::class, 'reject'])->name('van-reservations.reject');
    Route::patch('/van-reservas/{vanReservation}/reject', [VanReservationController::class, 'reject']);
    Route::post('/van-reservas/{vanReservation}/start-trip', [VanReservationController::class, 'startTrip'])->name('van-reservations.start-trip');
    Route::post('/van-reservas/{vanReservation}/finish-trip', [VanReservationController::class, 'finishTrip'])->name('van-reservations.finish-trip');
    Route::post('/van-reservas/{vanReservation}/cancel', [VanReservationController::class, 'cancel'])->name('van-reservations.cancel');

    // Aliases de conveniência para rotas de reservas
    Route::get('/reservas', fn() => redirect()->route('lab.reservations.index'))->name('reservations.index');
    Route::get('/reservas/create', fn() => redirect()->route('lab.reservations.create'))->name('reservations.create');
    Route::get('/reservas/mapa', fn() => redirect()->route('lab.reservations.calendar'))->name('reservations.calendar');
    Route::get('/reservas/{reservation}', fn($res) => redirect()->route('lab.reservations.show', $res))->name('reservations.show');
});

require __DIR__.'/auth.php';

// ─── Painel Administrativo ────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::post('teachers/sync-all', [\App\Http\Controllers\Admin\TeacherController::class, 'syncAllToUsers'])->name('teachers.sync-all');
    Route::post('teachers/{teacher}/sync-user', [\App\Http\Controllers\Admin\TeacherController::class, 'syncSingleUser'])->name('teachers.sync-user');
    Route::resource('teachers',    \App\Http\Controllers\Admin\TeacherController::class)->except(['show']);
    Route::patch('teachers/{teacher}/toggle', [\App\Http\Controllers\Admin\TeacherController::class, 'toggle'])->name('teachers.toggle');
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)->except(['show']);
    Route::resource('laboratories',\App\Http\Controllers\Admin\LaboratoryController::class)->except(['show']);
    Route::resource('projects',    \App\Http\Controllers\Admin\ProjectController::class)->except(['show']);
    Route::resource('courses',     \App\Http\Controllers\Admin\CourseController::class)->except(['show']);
    Route::patch('courses/{course}/subjects/{subject}/teacher', [\App\Http\Controllers\Admin\SubjectController::class, 'updateTeacher'])->name('courses.subjects.teacher');
    Route::resource('courses.subjects', \App\Http\Controllers\Admin\SubjectController::class)->except(['show']);
    Route::resource('units',       \App\Http\Controllers\Admin\UnitController::class)->except(['show']);
    Route::resource('sectors',     \App\Http\Controllers\Admin\SectorController::class)->except(['show']);
    Route::resource('events',      \App\Http\Controllers\Admin\EventController::class)->except(['show']);
    Route::delete('event-photos/{photo}', [\App\Http\Controllers\Admin\EventController::class, 'destroyPhoto'])->name('event-photos.destroy');
    Route::resource('documents',   \App\Http\Controllers\Admin\DocumentController::class)->except(['show']);

    // Cooperativa Escola
    Route::resource('cooperative-managers', \App\Http\Controllers\Admin\CooperativeManagerController::class)->except(['show']);
    Route::patch('cooperative-managers/{cooperative_manager}/toggle', [\App\Http\Controllers\Admin\CooperativeManagerController::class, 'toggle'])->name('cooperative-managers.toggle');
    Route::resource('cooperative-members', \App\Http\Controllers\Admin\CooperativeMemberController::class)->except(['show']);
    Route::patch('cooperative-members/{cooperative_member}/toggle', [\App\Http\Controllers\Admin\CooperativeMemberController::class, 'toggle'])->name('cooperative-members.toggle');
    Route::get('cooperative-members/{cooperative_member}/dues', [\App\Http\Controllers\Admin\CooperativeMemberController::class, 'dues'])->name('cooperative-members.dues');
    Route::patch('cooperative-members/{cooperative_member}/dues/{cooperative_monthly_fee}/toggle', [\App\Http\Controllers\Admin\CooperativeMemberController::class, 'toggleDue'])->name('cooperative-members.dues.toggle');
    Route::resource('cooperative-reports', \App\Http\Controllers\Admin\CooperativeReportController::class)->except(['show']);
    Route::resource('cooperative-monthly-fees', \App\Http\Controllers\Admin\CooperativeMonthlyFeeController::class)->except(['show']);

    // Financeiro da Cooperativa
    Route::get('cooperative-dashboard', [\App\Http\Controllers\Admin\CooperativeDashboardController::class, 'index'])->name('cooperative-dashboard');
    Route::resource('cooperative-expenses', \App\Http\Controllers\Admin\CooperativeExpenseController::class)->except(['show']);
    Route::patch('cooperative-expenses/{cooperative_expense}/mark-paid', [\App\Http\Controllers\Admin\CooperativeExpenseController::class, 'markPaid'])->name('cooperative-expenses.mark-paid');
    Route::resource('cooperative-sales', \App\Http\Controllers\Admin\CooperativeSaleController::class)->except(['show']);
    Route::patch('cooperative-sales/{cooperative_sale}/mark-received', [\App\Http\Controllers\Admin\CooperativeSaleController::class, 'markReceived'])->name('cooperative-sales.mark-received');

    // Moradia Estudantil
    Route::resource('cooperative-housing-tenants', \App\Http\Controllers\Admin\CooperativeHousingTenantController::class)->except(['show']);
    Route::patch('cooperative-housing-tenants/{cooperative_housing_tenant}/toggle', [\App\Http\Controllers\Admin\CooperativeHousingTenantController::class, 'toggle'])->name('cooperative-housing-tenants.toggle');
    Route::get('cooperative-housing-tenants/{cooperative_housing_tenant}/dues', [\App\Http\Controllers\Admin\CooperativeHousingTenantController::class, 'dues'])->name('cooperative-housing-tenants.dues');
    Route::patch('cooperative-housing-tenants/{cooperative_housing_tenant}/dues/{cooperative_housing_fee}/toggle', [\App\Http\Controllers\Admin\CooperativeHousingTenantController::class, 'toggleDue'])->name('cooperative-housing-tenants.dues.toggle');
    Route::resource('cooperative-housing-fees', \App\Http\Controllers\Admin\CooperativeHousingFeeController::class)->except(['show']);

    // APM (Associação de Pais e Mestres)
    Route::resource('apm-managers', \App\Http\Controllers\Admin\ApmManagerController::class)->except(['show']);
    Route::patch('apm-managers/{apm_manager}/toggle', [\App\Http\Controllers\Admin\ApmManagerController::class, 'toggle'])->name('apm-managers.toggle');
    Route::resource('apm-reports', \App\Http\Controllers\Admin\ApmReportController::class)->except(['show']);

    // Financeiro da APM
    Route::get('apm-dashboard', [\App\Http\Controllers\Admin\ApmDashboardController::class, 'index'])->name('apm-dashboard');
    Route::resource('apm-expenses', \App\Http\Controllers\Admin\ApmExpenseController::class)->except(['show']);
    Route::patch('apm-expenses/{apm_expense}/mark-paid', [\App\Http\Controllers\Admin\ApmExpenseController::class, 'markPaid'])->name('apm-expenses.mark-paid');
    Route::resource('apm-incomes', \App\Http\Controllers\Admin\ApmIncomeController::class)->except(['show']);
    Route::patch('apm-incomes/{apm_income}/mark-received', [\App\Http\Controllers\Admin\ApmIncomeController::class, 'markReceived'])->name('apm-incomes.mark-received');

    // Carrossel da Página Inicial
    Route::resource('home-slides', \App\Http\Controllers\Admin\HomeSlideController::class)->except(['show']);
    Route::patch('home-slides/{home_slide}/toggle', [\App\Http\Controllers\Admin\HomeSlideController::class, 'toggle'])->name('home-slides.toggle');

    // Rotas de toggle (ativar/desativar)
    Route::patch('courses/{course}/toggle',         [\App\Http\Controllers\Admin\CourseController::class,     'toggle'])->name('courses.toggle');
    Route::patch('departments/{department}/toggle', [\App\Http\Controllers\Admin\DepartmentController::class, 'toggle'])->name('departments.toggle');
    Route::patch('laboratories/{laboratory}/toggle',[\App\Http\Controllers\Admin\LaboratoryController::class, 'toggle'])->name('laboratories.toggle');
    Route::patch('events/{event}/toggle',           [\App\Http\Controllers\Admin\EventController::class,      'toggle'])->name('events.toggle');
    Route::patch('units/{unit}/toggle',             [\App\Http\Controllers\Admin\UnitController::class,       'toggle'])->name('units.toggle');
    Route::patch('sectors/{sector}/toggle',         [\App\Http\Controllers\Admin\SectorController::class,     'toggle'])->name('sectors.toggle');

    // Parceiros
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);
    Route::patch('partners/{partner}/toggle', [\App\Http\Controllers\Admin\PartnerController::class, 'toggle'])->name('partners.toggle');

    // Auditoria e Logs do Sistema (Superintendente, Diretora de Serviços, Admin)
    Route::get('auditoria', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index')->middleware('can.view.audit');
    Route::get('auditoria/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit.export')->middleware('can.view.audit');
    Route::get('auditoria/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit.show')->middleware('can.view.audit');

    // Matriz de Papéis e Permissões (RBAC)
    Route::get('permissoes', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissoes/toggle', [\App\Http\Controllers\Admin\RolePermissionController::class, 'toggle'])->name('permissions.toggle');
    Route::post('permissoes/resetar-padroes', [\App\Http\Controllers\Admin\RolePermissionController::class, 'resetDefaults'])->name('permissions.reset-defaults');
    Route::get('usuarios/{user}/permissoes', [\App\Http\Controllers\Admin\RolePermissionController::class, 'userPermissions'])->name('users.permissions');
    Route::post('usuarios/{user}/permissoes/toggle', [\App\Http\Controllers\Admin\RolePermissionController::class, 'toggleUserPermission'])->name('users.permissions.toggle');

    // Gestão de Grade de Horários dos Professores & Colaboradores
    Route::resource('work-schedules', \App\Http\Controllers\Admin\WorkScheduleController::class)->except(['show']);

    // Painel do Ponto Eletrônico & Espelho de Ponto (Diretoria/RH)
    Route::get('ponto-gestao', [\App\Http\Controllers\Admin\AdminTimeClockController::class, 'index'])->name('timeclock.index');
    Route::get('ponto-gestao/espelho', [\App\Http\Controllers\Admin\AdminTimeClockController::class, 'mirror'])->name('timeclock.mirror');
    Route::post('ponto-gestao/{timeClockRecord}/justificar', [\App\Http\Controllers\Admin\AdminTimeClockController::class, 'justify'])->name('timeclock.justify');

});

// ─── Ponto Eletrônico com Reconhecimento Facial & GPS ─────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('ponto', [\App\Http\Controllers\TimeClockController::class, 'index'])->name('timeclock.index');
    Route::post('ponto/registrar', [\App\Http\Controllers\TimeClockController::class, 'store'])->name('timeclock.store');
});

// Totem Kiosk da Instituição (Acessível na recepção / sala dos professores)
Route::get('ponto-totem/{unit?}', [\App\Http\Controllers\TimeClockController::class, 'totem'])->name('timeclock.totem');
Route::post('ponto-totem/registrar', [\App\Http\Controllers\TimeClockController::class, 'totemStore'])->name('timeclock.totem.store');

// ─── Módulo Laboratório ───────────────────────────────────────────────────────
use App\Http\Controllers\Lab\LabReservationController;
use App\Http\Controllers\Lab\SpaceController;
use App\Http\Controllers\Lab\MaterialController;
use App\Http\Controllers\Lab\LabUserController;
use App\Http\Controllers\Lab\LabProfileController;

Route::prefix('laboratorio')->name('lab.')->middleware(['auth'])->group(function () {

    // Dashboard do módulo
    Route::get('/', [LabReservationController::class, 'dashboard'])->name('dashboard');

    // Perfil do usuário (acessível por todos)
    Route::get('/perfil',  [LabProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil',  [LabProfileController::class, 'update'])->name('profile.update');

    // Reservas — acessível por todos os usuários autenticados
    Route::prefix('reservas')->name('reservations.')->group(function () {
        Route::get('/',                                [LabReservationController::class, 'index'])->name('index');
        Route::get('/nova',                            [LabReservationController::class, 'create'])->name('create');
        Route::post('/',                               [LabReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}',                   [LabReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/pdf',               [LabReservationController::class, 'generatePDF'])->name('pdf');
        Route::post('/{reservation}/iniciar',          [LabReservationController::class, 'startClass'])->name('start');
        Route::post('/{reservation}/obs-professor',    [LabReservationController::class, 'submitProfessorObs'])->name('professor-obs');
        Route::post('/{reservation}/conferencia',      [LabReservationController::class, 'auxiliarFinalize'])->name('auxiliar-finalize');
        Route::get('/historico/concluidas',            [LabReservationController::class, 'history'])->name('history');
        Route::get('/mapa/calendario',                 [LabReservationController::class, 'calendar'])->name('calendar');
    });

    // API calendário e disponibilidade
    Route::get('/api/calendario', [LabReservationController::class, 'calendarEvents'])->name('api.calendar');
    Route::get('/api/disponibilidade/{space}', [LabReservationController::class, 'availability'])->name('api.availability');

    // Coordenador + Admin: aprovar, recusar, validar
    Route::middleware(['auth'])->group(function () {
        Route::patch('reservas/{reservation}/aprovar',  [LabReservationController::class, 'approve'])->name('reservations.approve')->middleware('can-coordinate');
        Route::patch('reservas/{reservation}/recusar',  [LabReservationController::class, 'reject'])->name('reservations.reject')->middleware('can-coordinate');
        Route::patch('reservas/{reservation}/validar',  [LabReservationController::class, 'validateActivity'])->name('reservations.validate')->middleware('can-coordinate');
        Route::post('reservas/{reservation}/documento', [LabReservationController::class, 'uploadScannedDoc'])->name('reservations.upload-doc')->middleware('can-coordinate');
    });

    // Área administrativa — somente admin
    Route::middleware(['admin'])->group(function () {

        // Espaços
        Route::resource('espacos', SpaceController::class)->parameters(['espacos' => 'space'])->names([
            'index'   => 'spaces.index',
            'create'  => 'spaces.create',
            'store'   => 'spaces.store',
            'edit'    => 'spaces.edit',
            'update'  => 'spaces.update',
            'destroy' => 'spaces.destroy',
        ]);

        // Materiais
        Route::resource('materiais', MaterialController::class)->parameters(['materiais' => 'material'])->names([
            'index'   => 'materials.index',
            'create'  => 'materials.create',
            'store'   => 'materials.store',
            'edit'    => 'materials.edit',
            'update'  => 'materials.update',
            'destroy' => 'materials.destroy',
        ]);

        // Usuários do sistema e colaboradores
        Route::prefix('usuarios')->name('users.')->group(function () {
            Route::get('/',                      [LabUserController::class, 'index'])->name('index');
            Route::post('/',                     [LabUserController::class, 'store'])->name('store');
            Route::put('/{user}',                [LabUserController::class, 'update'])->name('update');
            Route::post('/sincronizar-todos',    [LabUserController::class, 'syncAllTeachers'])->name('sync-all');
            Route::patch('/{user}/papel',        [LabUserController::class, 'updateRole'])->name('role');
            Route::patch('/{user}/vinculos',     [LabUserController::class, 'updateVinculos'])->name('vinculos');
            Route::patch('/{user}/status',       [LabUserController::class, 'toggleStatus'])->name('status');
            Route::post('/{user}/reset-senha',   [LabUserController::class, 'sendResetLink'])->name('reset-link');
            Route::delete('/{user}',             [LabUserController::class, 'destroy'])->name('destroy');
        });
    });
});
