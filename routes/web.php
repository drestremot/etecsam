<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

// Define que a página inicial do site já carrega o Controller da Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/fale-conosco', [SiteController::class, 'contact'])->name('contact');
// Rota Institucional ("A Escola")
Route::get('/escola', [SiteController::class, 'institutional'])->name('institutional');
// Rota de Detalhes do Curso (A QUE ESTAVA FALTANDO)
// Rota para visualizar os DETALHES de um curso específico
// O nome 'courses.show' é o que estamos usando nos links da tela
Route::get('/curso/{slug}', [App\Http\Controllers\SiteController::class, 'show'])->name('courses.show');
Route::get('/secretaria', [App\Http\Controllers\SiteController::class, 'academic'])->name('academic');
Route::get('/contato', [SiteController::class, 'contact'])->name('contact');
Route::get('/agenda', [SiteController::class, 'agenda'])->name('agenda');
Route::get('/diretoria-servicos', [App\Http\Controllers\SiteController::class, 'administrative'])->name('administrative');
Route::get('/biblioteca', [SiteController::class, 'library'])->name('library');
// Rota para ver os cursos de uma UNIDADE específica
Route::get('/unidade/{id}', [App\Http\Controllers\SiteController::class, 'unit'])->name('units.show');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::get('/unidade-didatica/{slug}', [App\Http\Controllers\SiteController::class, 'sector'])->name('sectors.show');
