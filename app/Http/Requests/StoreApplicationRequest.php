<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use App\Enums\SalaryPeriodType;
use App\Enums\SalaryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'url' => ['required', 'url'],
            'company' => ['nullable', 'string'],
            'salary_period' => ['required', Rule::enum(SalaryPeriodType::class)],
            'salary_type' => ['required', Rule::enum(SalaryType::class)],
            'salary_min' => [Rule::requiredIf(fn () => $this->salary_type !== SalaryType::UNKNOWN), 'integer'],
            'salary_max' => ['required_if:salary_type,range', 'integer', 'gte:salary_min'],
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
        ];
    }
}
