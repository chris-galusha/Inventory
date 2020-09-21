<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Storage;
use Illuminate\Support\Facades\Log;

class CleanupDatabaseBackups extends Command
{
    protected $signature = 'db:cleanup';

    protected $description = 'Clean up old database backups';

    protected $process;

    protected $months_old = 3;

    public function __construct()
    {
        parent::__construct();

    }

    public function handle()
    {
        try {
            $files = Storage::disk('backups')->files();

            foreach ($files as $file) {
              $name = pathinfo($file)['filename'];
              $date = strtotime($name);
              if ($date && $date < strtotime("-$this->months_old months")) {
              	Storage::disk('backups')->delete($file);
                $message = "Cleaned up SQL backup $file because it was older than $this->months_old months.";
                Log::debug($message);
                $this->info($message);
              }
            }
            
            $message = 'The database backup cleanup has completed successfully.';
            $this->info($message);
            Log::debug($message);
        } catch (ProcessFailedException $exception) {
            $message = 'The database backup cleanup process failed. See log for details';
            $this->error($message);
            Log::debug($message);
            Log::debug($exception);
        }
    }
}
