<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\{render};

class DNSLush extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dns';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Lush DNS configuration';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        system('sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder');
        $this->task('Resizing file', function () {
            return true;
        });
    }


    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        /*$schedule->command(static::class)->everyMinute();*/
    }
}
