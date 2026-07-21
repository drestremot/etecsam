<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token'    => 'required|string',
            'platform' => 'nullable|string|max:20',
        ]);

        DeviceToken::updateOrCreate(
            ['token' => $validated['token']],
            ['user_id' => $request->user()->id, 'platform' => $validated['platform'] ?? null],
        );

        return response()->json(['message' => 'Token registrado.']);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate(['token' => 'required|string']);

        DeviceToken::where('token', $validated['token'])->delete();

        return response()->json(['message' => 'Token removido.']);
    }
}
