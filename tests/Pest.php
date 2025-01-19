<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Carbon\Carbon;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Unit');

function prepareApplicationDataForAssertion(array $data)
{
    $data['salary_min'] = toMinorAmount($data['salary_min']);
    $data['salary_max'] = toMinorAmount($data['salary_max']);
    $data['applied_at'] = Carbon::parse($data['applied_at'])->format('Y-m-d H:i:s');

    return $data;
}
