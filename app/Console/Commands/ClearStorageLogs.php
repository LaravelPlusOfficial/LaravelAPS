<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class ClearStorageLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:storage:logs {--keep-last : Whether the last log file should be kept}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove every log files in the log directory';


    /**
     * @var Filesystem
     */
    protected $disk;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $disk
     */
    public function __construct(Filesystem $disk)
    {
        parent::__construct();

        $this->disk = $disk;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $files = $this->getLogFiles();

            if ($this->option('keep-last') && $files->count() >= 1) {
                $files->shift();
            }

            $this->delete($files);

        } catch (\Exception $e) {
        }
    }

    /**
     * Get a collection of log files sorted by their last modification date.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getLogFiles()
    {
        return collect(
            $this->disk->allFiles(storage_path('logs'))
        )->sortBy('mtime');
    }

    /**
     * Delete the given files.
     *
     * @param \Illuminate\Support\Collection $files
     * @return int
     */
    private function delete(Collection $files)
    {
        return $files->each(function ($file) {
            $this->disk->delete($file);
        })->count();
    }
}
