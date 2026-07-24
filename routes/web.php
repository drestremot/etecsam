<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// ─── Site Público ────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

Route::get('/escola',              [SiteController::class, 'institutional'])->name('institutional');
Route::get('/curso/{slug}',        [SiteController::class, 'show'])->name('courses.show');
Route::get('/secretaria',          [SiteController::class, 'academic'])->name('academic');
Route::get('/contato',             [SiteController::class, 'contact'])->name('contact');
Route::post('/contato',            [SiteController::class, 'sendContact'])->name('contact.send');
Route::get('/fale-conosco',        [SiteController::class, 'contact']);
Route::get('/agenda',              [SiteController::class, 'agenda'])->name('agenda');
Route::get('/superintendencia',    [SiteController::class, 'superintendence'])->name('superintendence');
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

// ─── Autenticação (Breeze) ────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ─── Painel Administrativo ────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('teachers',    \App\Http\Controllers\Admin\TeacherController::class)->except(['show']);
    Route::patch('teachers/{teacher}/toggle', [\App\Http\Controllers\Admin\TeacherController::class, 'toggle'])->name('teachers.toggle');
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)->except(['show']);
    Route::resource('laboratories',\App\Http\Controllers\Admin\LaboratoryController::class)->except(['show']);
    Route::resource('projects',    \App\Http\Controllers\Admin\ProjectController::class)->except(['show']);
    Route::resource('courses',     \App\Http\Controllers\Admin\CourseController::class)->except(['show']);
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

    // Temas do Site
    Route::resource('themes', \App\Http\Controllers\Admin\ThemeController::class);
    Route::post('themes/{theme}/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');
    Route::post('themes/deactivate', [\App\Http\Controllers\Admin\ThemeController::class, 'deactivate'])->name('themes.deactivate');

});

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
        Route::get('/mapa/calendario',                 fn() => view('lab.reservations.calendar'))->name('calendar');
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
        Route::resource('espacos', SpaceController::class)->names([
            'index'   => 'spaces.index',
            'create'  => 'spaces.create',
            'store'   => 'spaces.store',
            'edit'    => 'spaces.edit',
            'update'  => 'spaces.update',
            'destroy' => 'spaces.destroy',
        ]);

        // Materiais
        Route::resource('materiais', MaterialController::class)->names([
            'index'   => 'materials.index',
            'create'  => 'materials.create',
            'store'   => 'materials.store',
            'edit'    => 'materials.edit',
            'update'  => 'materials.update',
            'destroy' => 'materials.destroy',
        ]);

        // Usuários do lab
        Route::prefix('usuarios')->name('users.')->group(function () {
            Route::get('/',                      [LabUserController::class, 'index'])->name('index');
            Route::post('/',                     [LabUserController::class, 'store'])->name('store');
            Route::patch('/{user}/papel',        [LabUserController::class, 'updateRole'])->name('role');
            Route::patch('/{user}/vinculos',     [LabUserController::class, 'updateVinculos'])->name('vinculos');
            Route::patch('/{user}/status',       [LabUserController::class, 'toggleStatus'])->name('status');
            Route::post('/{user}/reset-senha',   [LabUserController::class, 'sendResetLink'])->name('reset-link');
            Route::delete('/{user}',             [LabUserController::class, 'destroy'])->name('destroy');
        });
    });
});
