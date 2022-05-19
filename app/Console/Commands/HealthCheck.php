<?php

namespace App\Console\Commands;

use App\Notifications\AdminPostReject;
use App\Notifications\EmailVerification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Mailer\Test\Constraint\EmailCount;


class HealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check status Database & redis & emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("current database is " . DB::connection()->getDatabaseName());
        $this->info('Checking Redis Connection ');
        try {
            $redis = Redis::connection();
            $redis->set('health-check', Carbon::now());
            $this->info('done');

        } catch (\Throwable $e) {
            $this->alert("failed error:" . $e->getMessage());
        }
    }
}
