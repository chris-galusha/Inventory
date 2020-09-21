<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use App\Item;
use Illuminate\Support\Facades\Log;

class GenerateTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a certain number of test items';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function GenerateTestData () {

      $limit = 10**4;
      for ($i=0; $i < $limit; $i++) {
        try {
          $item = factory(Item::class)->create();
        } catch (QueryException $exception) {
          Log::debug('Duplicate attempted to be created.');
          $i--;
        }
      }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      try {
        $this->GenerateTestData();

        $this->info('The test data was generated successfully.');
      } catch (ProcessFailedException $exception) {
        $this->error('The test data failed to generate. See Log for details.');
        Log::debug($exception);
      }
    }
}
