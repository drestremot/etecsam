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
Route::get('/fale-conosco',        [SiteController::class, 'contact']);
Route::get('/agenda',              [SiteController::class, 'agenda'])->name('agenda');
Route::get('/superintendencia',    [SiteController::class, 'superintendence'])->name('superintendence');
Route::get('/diretoria-academica', [SiteController::class, 'academicDivision'])->name('academic-division');
Route::get('/diretoria-servicos',  [SiteController::class, 'administrative'])->name('administrative');
Route::get('/biblioteca',          [SiteController::class, 'library'])->name('library');
Route::get('/cooperativa',         [SiteController::class, 'cooperative'])->name('cooperative');
Route::get('/unidade/{id}',        [SiteController::class, 'unit'])->name('units.show');
Route::get('/unidade-didatica/{slug}', [SiteController::class, 'sector'])->name('sectors.show');

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
    Route::resource('cooperative-reports', \App\Http\Controllers\Admin\CooperativeReportController::class)->except(['show']);

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
