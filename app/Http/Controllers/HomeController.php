<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Event;
use App\Models\Sector; // <--- Importante: Adicionamos o modelo de Setores

class HomeController extends Controller
{
    public function index()
{
    // ALTERAÇÃO AQUI: Adicionei ->orderBy('city')
    // Também coloquei ->orderBy('name') para organizar alfabeticamente dentro da mesma cidade
    $units = Unit::withCount('courses')
                 ->where('is_active', true)
                 ->orderBy('city', 'asc')
                 ->orderBy('name', 'asc')
                 ->get();

    // ... (o resto do código continua igual)
    $sectors = Sector::where('is_active', true)->get();
    $nextEvents = Event::where('start_date', '>=', now())
                    ->orderBy('start_date')
                    ->take(3)
                    ->get();

    return view('home', compact('units', 'sectors', 'nextEvents'));
}
}
