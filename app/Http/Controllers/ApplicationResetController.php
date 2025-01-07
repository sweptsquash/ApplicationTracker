<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ApplicationResetController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        DB::transaction(function () {
            DB::table('applications')->truncate();
            DB::table('companies')->truncate();
        });

        return back()->success('Application has been reset');
    }
}
