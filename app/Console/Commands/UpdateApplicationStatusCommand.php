<?php

namespace App\Console\Commands;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Console\Command;

class UpdateApplicationStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-application-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update an application status from Applied to Awaiting Response after 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Application::where('status', ApplicationStatus::APPLIED)
            ->where('created_at', '<=', now()->subDays(7))
            ->update(['status' => ApplicationStatus::AWAITING_RESPONSE]);
    }
}
