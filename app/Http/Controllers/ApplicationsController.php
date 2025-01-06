<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
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

        return inertia('index', ['applications' => ApplicationResource::collection($applications)]);
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

        return back(HttpResponse::HTTP_CREATED)->flash(NotificationType::Success, 'Application created successfully.');
    }

    public function show(Application $application): Response
    {
        return inertia('index', ['application' => ApplicationResource::make($application)]);
    }

    public function update(UpdateApplicationRequest $request, Application $application): RedirectResponse
    {
        $validated = $request->validated();

        $application->update(Arr::except($validated, 'company'));

        if (Arr::has($validated, 'company') && ! blank($validated['company'])) {
            $application->company()->create(['name' => $validated['company']]);
        }

        return back()->flash(NotificationType::Success, 'Application updated successfully.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $application->delete();

        return back()->flash(NotificationType::Success, 'Application deleted successfully.');
    }
}
