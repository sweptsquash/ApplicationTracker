<?php

namespace App\Observers;

use App\Models\Company;
use Illuminate\Support\Str;

class CompanyObserver
{
    public function creating(Company $company)
    {
        $company->slug = Str::slug($company->name);
    }
}
