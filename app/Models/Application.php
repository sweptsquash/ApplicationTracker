<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\SalaryPeriodType;
use App\Enums\SalaryType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperApplication
 */
class Application extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicationFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'url',
        'salary_period',
        'salary_type',
        'salary_min',
        'salary_max',
        'status',
        'notes',
        'applied_at',
    ];

    /**
     * @return array{salary_period: 'App\Enums\SalaryPeriodType', salary_type: 'App\Enums\SalaryType', salary_min: 'integer', salary_max: 'integer', status: 'App\Enums\ApplicationStatus', applied_at: 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'salary_period' => SalaryPeriodType::class,
            'salary_type' => SalaryType::class,
            'salary_min' => 'integer',
            'salary_max' => 'integer',
            'status' => ApplicationStatus::class,
            'applied_at' => 'datetime',
        ];
    }

    /** @return Attribute<int, int> */
    protected function salaryMin(): Attribute
    {
        return Attribute::make(
            fn ($value) => $value,
            fn ($value) => toMinorAmount($value),
        );
    }

    /** @return Attribute<int, int> */
    protected function salaryMax(): Attribute
    {
        return Attribute::make(
            fn ($value) => $value,
            fn ($value) => toMinorAmount($value),
        );
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
