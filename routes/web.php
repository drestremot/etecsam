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
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class)->except(['show']);
    Route::resource('laboratories',\App\Http\Controllers\Admin\LaboratoryController::class)->except(['show']);
    Route::resource('projects',    \App\Http\Controllers\Admin\ProjectController::class)->except(['show']);
    Route::resource('courses',     \App\Http\Controllers\Admin\CourseController::class)->except(['show']);
    Route::resource('courses.subjects', \App\Http\Controllers\Admin\SubjectController::class)->except(['show']);
    Route::resource('units',       \App\Http\Controllers\Admin\UnitController::class)->except(['show']);
    Route::resource('sectors',     \App\Http\Controllers\Admin\SectorController::class)->except(['show']);
    Route::resource('events',      \App\Http\Controllers\Admin\EventController::class)->except(['show']);
    Route::resource('documents',   \App\Http\Controllers\Admin\DocumentController::class)->except(['show']);

    // Parceiros
    Route::resource('partners', Admin\PartnerController::class);

    // Temas do Site
    Route::resource('themes', Admin\ThemeController::class);
    Route::post('themes/{theme}/activate', [Admin\ThemeController::class, 'activate'])->name('themes.activate');
    Route::post('themes/deactivate', [Admin\ThemeController::class, 'deactivate'])->name('themes.deactivate');

});
