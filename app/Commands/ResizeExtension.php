<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\{render};

class ResizeExtension extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'resize';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Resize file extension';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = array_diff(scandir('Desktop/toConvert'), array('..', '.', '.DS_Store','resize'));

        $bar = $this->output->createProgressBar(count($files));

        $bar->start();

        if (!is_dir('Desktop/toConvert/resize')) {
            mkdir('Desktop/toConvert/resize');
        }

        $size_request = $this->ask('What size do you want?');
        $width_or_height = $this->choice(
        'What dimension do you want to fix?',
        ['width', 'height']);


        foreach ($files as $file) {
            $info = pathinfo($file);
            $ext = $info['extension'];
            $image = 'Desktop/converter/toConvert/' . $file;

            $size = getimagesize('Desktop/toConvert/' . $file);

            $ratio = $size[0]/$size[1]; // width/height


            switch($width_or_height) {
                case 'width': $width = true;
            break;
                case 'height': $width = false;

            }

            if( $ratio > 1 && $width == true) {
                $width = $size_request;
                $height = $size_request/$ratio;
            }
            elseif ( $ratio < 1 && $width == true) {
                $width = $size_request*$ratio;
                $height = $size_request;
            }
            elseif ( $ratio > 1 && $width !== true) {
                $height = $size_request;
                $width = $size_request/$ratio;
            } elseif ( $ratio < 1 && $width !== true) {
                $height = $size_request*$ratio;
                $width = $size_request;
            }


            $src = imagecreatefromstring(file_get_contents($image));
            $dst = imagecreatetruecolor($width,$height);

            imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);

            if ($ext == 'png') {
                imagepng($dst, 'Desktop/toConvert/resize/' . $info['filename'] . '.png', 9);
            } else {
                imagejpeg($dst, 'Desktop/toConvert/resize/' . $info['filename'] . '.jpg', 9);
            }

            imagedestroy($dst);
            $bar->advance();
        }


        $bar->finish();
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
