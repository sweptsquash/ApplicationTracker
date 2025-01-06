<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $applications = Application::query()
            ->with('company')
            ->orderByDesc('created_at')
            ->paginate(30);

        return inertia('index', ['applications' => ApplicationResource::collection($applications)]);
    }
}
