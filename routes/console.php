<?php

use App\Jobs\RekapAbsensiHarian;
use Illuminate\Support\Facades\Schedule;

Schedule::daily()->at('23:00')->job(new RekapAbsensiHarian);
