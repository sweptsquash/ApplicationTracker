<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Response;

class ApplicationsController extends Controller
{
    public function index(): Response
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
                    'awaiting' => Application::where('status', ApplicationStatus::AWAITING_RESPONSE)->count(),
                ],
            ]
        );
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (Arr::has($validated, 'company') && ! blank($validated['company'])) {
            /** @var Company $company */
            $company = Company::firstOrCreate(
                ['slug' => Str::slug($validated['company'])],
                ['name' => $validated['company']],
            );

            $company->applications()->create(Arr::except($validated, 'company'));
        } else {
            Application::create($validated);
        }

        return back()->success('Application created successfully.');
    }

    public function show(Application $application): JsonResource
    {
        $application->load('company');

        return ApplicationResource::make($application);
    }

    public function update(UpdateApplicationRequest $request, Application $application): RedirectResponse
    {
        $validated = $request->validated();

        $application->update(Arr::except($validated, 'company'));

        if (Arr::has($validated, 'company') && ! blank($validated['company'])) {
            $application->company()->create(['name' => $validated['company']]);
        }

        return back()->success('Application updated successfully.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $application->delete();

        return back()->success('Application deleted successfully.');
    }
}
