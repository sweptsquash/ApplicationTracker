<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:update-application-status')->hourly();
