<?php

namespace App\Http\Controllers\Api\Lab;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpaceResource;
use App\Http\Resources\UserResource;
use App\Models\Space;
use App\Models\User;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function index()
    {
        return SpaceResource::collection(Space::with('auxiliar.teacher')->orderBy('name')->get());
    }

    public function auxiliares()
    {
        return UserResource::collection(
            User::role('Auxiliar')->where('is_active', true)->with('teacher')->orderBy('name')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'auxiliar_id' => 'nullable|exists:users,id',
        ]);

        $space = Space::create($validated);
        $space->load('auxiliar.teacher');

        return (new SpaceResource($space))
            ->additional(['message' => 'Espaço didático cadastrado com sucesso!'])
            ->response()->setStatusCode(201);
    }

    public function update(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'auxiliar_id' => 'nullable|exists:users,id',
        ]);

        $space->update($validated);
        $space->load('auxiliar.teacher');

        return (new SpaceResource($space))
            ->additional(['message' => 'Espaço atualizado com sucesso!']);
    }

    public function destroy(Space $space)
    {
        $space->delete();

        return response()->json(['message' => 'Espaço excluído.']);
    }
}
