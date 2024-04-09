<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\{render};

class ChangeExtension extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'change';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Change file extension';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = array_diff(scandir('toConvert'), array('..', '.', '.DS_Store'));

        $bar = $this->output->createProgressBar(count($files));

        $bar->start();

        foreach ($files as $file) {
            $info = pathinfo($file);
            $ext = $info['extension'];

            if ($ext == 'png') {
                $image = imagecreatefrompng('toConvert/' . $file);
                imagejpeg($image, 'toConvert/' . $info['filename'] . '.jpg', 100);
            } else {
                $image = imagecreatefromjpeg('toConvert/' . $file);
                imagepng($image, 'toConvert/' . $info['filename'] . '.png', 9);
            }
            imagedestroy($image);
            $bar->advance();
        }


        $bar->finish();
        $this->task('Convert file', function () {
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
