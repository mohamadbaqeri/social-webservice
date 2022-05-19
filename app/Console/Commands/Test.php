<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Test:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        User::where('firstname', 'emad')->update(['firstname' => 'freelancer']);
        return 0;
    }
}
