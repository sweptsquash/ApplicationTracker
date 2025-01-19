<?php

use App\Enums\NotificationType;
use App\Models\Application;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

describe('Applications', function () {
    it('can be listed', function () {
        Application::factory()->count(3)->create();

        get(route('applications.index'))
            ->assertInertia(function (AssertableInertia $page) {
                $page->component('index')
                    ->has('applications', 3);
            });
    });

    it('can be created without a company', function () {
        $application = Application::factory()->make();

        post(route('applications.store'), $application->toArray())
            ->assertRedirect(route('home'))
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application created successfully.',
            ]);

        assertDatabaseHas('applications', [
            ...prepareApplicationDataForAssertion($application->toArray()),
            'company_id' => null,
        ]);
    });

    it('can be created with a company', function () {
        $application = Application::factory()->make();

        post(route('applications.store'), [...$application->toArray(), 'company' => 'Company Name'])
            ->assertRedirect(route('home'))
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application created successfully.',
            ]);

        $applicationData = $application->toArray();

        unset($applicationData['company_id']);

        assertDatabaseHas('applications', prepareApplicationDataForAssertion($applicationData));
        assertDatabaseHas('companies', ['name' => 'Company Name']);
    });

    it('can be shown', function () {
        $application = Application::factory()->create();

        getJson(route('applications.show', $application))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $application->id,
                'title' => $application->title,
                'url' => $application->url,
                'salary_period' => $application->salary_period,
                'salary_type' => $application->salary_type,
                'salary_min' => $application->salary_min,
                'salary_max' => $application->salary_max,
                'status' => $application->status,
                'notes' => $application->notes,
            ]);
    });

    it('can be updated', function () {
        $application = Application::factory(state: ['company_id' => null])->create();

        $newApplication = Application::factory()->make();

        put(route('applications.update', $application), $newApplication->toArray())
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application updated successfully.',
            ]);

        assertDatabaseHas('applications', [
            ...prepareApplicationDataForAssertion($newApplication->toArray()),
            'company_id' => $application->company_id,
        ]);
    });

    it('can be updated with a company', function () {
        $application = Application::factory()->create();

        $newApplication = Application::factory()->make();

        put(route('applications.update', $application), [...$newApplication->toArray(), 'company' => 'New Company Name'])
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application updated successfully.',
            ]);

        $newApplicationData = $newApplication->toArray();

        unset($newApplicationData['company_id']);

        assertDatabaseHas('applications', prepareApplicationDataForAssertion($newApplicationData));
        assertDatabaseHas('companies', ['name' => 'New Company Name']);
    });

    it('can be deleted without a company', function () {
        $application = Application::factory(state: ['company_id' => null])->create();

        $applicationId = $application->id;

        delete(route('applications.destroy', $application))
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application deleted successfully.',
            ]);

        assertDatabaseMissing('applications', ['id' => $applicationId]);
    });

    it('can be deleted with a company', function () {
        $application = Application::factory()->create();

        $applicationId = $application->id;

        $application->company()->create(['name' => 'Company Name']);

        delete(route('applications.destroy', $application))
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application deleted successfully.',
            ]);

        assertDatabaseMissing('applications', ['id' => $applicationId]);
    });

    it('can clear all applications', function () {
        Application::factory()->count(3)->create();

        delete(route('applications.reset'))
            ->assertSessionHas('notification', [
                'type' => NotificationType::Success,
                'body' => 'Application has been reset',
            ]);

        assertDatabaseMissing('applications', []);
    });
});
