<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabProfileController extends Controller
{
    public function edit()
    {
        $user    = auth()->user();
        $teacher = Teacher::where('email', $user->email)->first();
        return view('lab.profile', compact('user', 'teacher'));
    }

    public function update(Request $request)
    {
        $user    = auth()->user();
        $teacher = Teacher::where('email', $user->email)->first();

        $request->validate([
            'name'       => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'specialty'  => 'nullable|string|max:500',
            'bio'        => 'nullable|string|max:1000',
            'lattes_url' => 'nullable|url|max:255',
            'phone'      => 'nullable|string|max:20',
            'photo'      => 'nullable|image|max:2048',
        ]);

        // Atualiza nome no User
        $user->update(['name' => $request->name]);

        // Atualiza ou cria Teacher vinculado
        $teacherData = [
            'name'       => $request->name,
            'email'      => $user->email,
            'birth_date' => $request->birth_date,
            'specialty'  => $request->specialty,
            'bio'        => $request->bio,
            'lattes_url' => $request->lattes_url,
            'phone'      => $request->phone,
        ];

        if ($request->hasFile('photo')) {
            if ($teacher?->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $teacherData['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        if ($teacher) {
            $teacher->update($teacherData);
        } else {
            Teacher::create($teacherData);
        }

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
