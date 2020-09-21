<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use Illuminate\Support\Facades\Log;

class CleanupReports extends Command
{
    protected $signature = 'report:cleanup';

    protected $description = 'Clean up old reports';

    protected $process;

    protected $months_old = 3;

    public function __construct()
    {
        parent::__construct();

    }

    public function handle()
    {
        try {
            $files = Storage::disk('reports')->files();

            foreach ($files as $file) {
              $name = pathinfo($file)['filename'];
              $date = explode("_", $name)[0];
              $date = strtotime($date);
              if ($date && $date < strtotime("-$this->months_old months")) {
              	Storage::disk('reports')->delete($file);
                $message = "Cleaned up report $file because it was older than $this->months_old months.";
                Log::debug($message);
                $this->info($message);
              }
            }

            $message = 'The report cleanup has completed successfully.';
            $this->info($message);
            Log::debug($message);
        } catch (ProcessFailedException $exception) {
            $message = 'The report cleanup process failed. See log for details';
            $this->error($message);
            Log::debug($message);
            Log::debug($exception);
        }
    }
}
