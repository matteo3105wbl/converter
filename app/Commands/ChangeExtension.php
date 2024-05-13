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
        $files = array_diff(scandir('Desktop/converter/toConvert'), array('..', '.', '.DS_Store','transformate'));

        $bar = $this->output->createProgressBar(count($files));

        $bar->start();

        if (!is_dir('Desktop/converter/toConvert/transformate')) {
            mkdir('Desktop/converter/toConvert/transformate');
        }

        foreach ($files as $file) {
            $info = pathinfo($file);
            $ext = $info['extension'];

            if ($ext == 'png') {
                $image = imagecreatefrompng('Desktop/converter/toConvert/' . $file);
                imagejpeg($image, 'Desktop/converter/toConvert/transformate/' . $info['filename'] . '.jpg', 100);
            } else {
                $image = imagecreatefromjpeg('Desktop/converter/toConvert/' . $file);
                imagepng($image, 'Desktop/converter/toConvert/transformate/' . $info['filename'] . '.png', 9);
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
