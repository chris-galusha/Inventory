<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class RecoverDatabase extends Command
{
    protected $signature = 'db:recover {sql_path}';

    protected $description = 'Backup the database';

    protected $process;

    protected $sql_path;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

      $sql_path = '"'.$this->argument('sql_path').'"';

      $this->process = new Process(sprintf(
          'mysql -u%s -p%s %s < %s',
          config('database.connections.mysql.username'),
          config('database.connections.mysql.password'),
          config('database.connections.mysql.database'),
          $sql_path
      ));

        try {
            $this->process->mustRun();

            $this->info('The backup has been created successfully.');
            Log::debug('Database recovery ran.');
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process failed. See log for details');
            Log::debug($exception);
        }
    }
}
