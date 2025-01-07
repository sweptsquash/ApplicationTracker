<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ApplicationResetController extends Controller
{
    public function __invoke(): Response
    {
        DB::transaction(function () {
            DB::table('applications')->truncate();
            DB::table('companies')->truncate();
        });

        return response()->noContent();
    }
}
