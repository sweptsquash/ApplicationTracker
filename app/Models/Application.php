<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\SalaryPeriodType;
use App\Enums\SalaryType;
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
    ];

    /**
     * @return array{salary_period: 'App\Enums\SalaryPeriodType', salary_type: 'App\Enums\SalaryType', salary_min: int, salary_max: int, status: 'App\Enums\ApplicationStatus'}
     */
    protected function casts(): array
    {
        return [
            'salary_period' => SalaryPeriodType::class,
            'salary_type' => SalaryType::class,
            'salary_min' => 'integer',
            'salary_max' => 'integer',
            'status' => ApplicationStatus::class,
        ];
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
