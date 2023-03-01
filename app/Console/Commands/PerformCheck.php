<?php

namespace App\Console\Commands;

use App\Jobs\VerifyEndpoint;
use Illuminate\Console\Command;

class PerformCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:perform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all endpoint';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

//      foreach (EndPoint::query()->where('next_check', '<=', now())->cursor() as $endpoint){
            dispatch(new VerifyEndpoint());
//      }
    }
}
