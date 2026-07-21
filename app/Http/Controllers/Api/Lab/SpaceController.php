<?php

namespace App\Http\Controllers\Api\Lab;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpaceResource;
use App\Models\Space;

class SpaceController extends Controller
{
    public function index()
    {
        return SpaceResource::collection(Space::with('auxiliar')->orderBy('name')->get());
    }
}
