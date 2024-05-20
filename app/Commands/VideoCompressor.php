<?php

namespace App\Commands;

use FFMpeg\FFMpeg;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use function Termwind\{render};

require 'vendor/autoload.php';

class VideoCompressor extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'video_compressor';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Compressor for videos';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $files = array_diff(scandir('Desktop/converter/toConvert'), array('..', '.', '.DS_Store', 'resize', 'transformate', 'video_compressor'));

        $bar = $this->output->createProgressBar(count($files));

        $bar->start();

        if (!is_dir('Desktop/converter/toConvert/video_compressor')) {
            mkdir('Desktop/converter/toConvert/video_compressor');
        }


        foreach ($files as $file) {
            $info = pathinfo($file);
            $ext = $info['extension'];

            if ($ext !== 'mp4') {
                continue;
            }

            if (is_dir($file)) {
                continue;
            }


            $newFile = $info['basename'];
            $newFile = $newFile . ".mp4";

            $command = "ffmpeg -i Desktop/converter/toConvert/$file -vcodec libx264 -crf 32 Desktop/converter/toConvert/video_compressor/$newFile";

            exec($command);
            $bar->advance();
        }

        $bar->finish();
        $this->task('Compressing file', function () {
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
