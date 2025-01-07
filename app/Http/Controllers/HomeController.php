<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
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

        return inertia(
            'index',
            [
                'applications' => ApplicationResource::collection($applications),
                'stats' => [
                    'applied' => Application::where('status', ApplicationStatus::APPLIED)->count(),
                    'interviewing' => Application::where('status', ApplicationStatus::INTERVIEWING)->count(),
                    'rejections' => Application::where('status', ApplicationStatus::REJECTED)->count(),
                    'offers' => Application::where('status', ApplicationStatus::OFFER)->count(),
                    'withdrawn' => Application::where('status', ApplicationStatus::WITHDRAWN)->count(),
                    'awaiting' => Application::where('status', ApplicationStatus::APPLIED)
                        ->where('created_at', '<=', now()->subWeek())
                        ->count(),
                ],
            ]
        );
    }
}
