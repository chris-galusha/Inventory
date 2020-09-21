<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Report;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
  /**
  * The Artisan commands provided by your application.
  *
  * @var array
  */
  protected $commands = [
    //
  ];

  /**
  * Define the application's command schedule.
  *
  * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
  * @return void
  */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('db:backup') // Back up database at 7:00 AM and 7:00 PM
    ->twiceDaily(7, 19);
    $schedule->command('db:cleanup') // Clean up old database backups
    ->daily('12:00');
    $schedule->command('report:cleanup') // Clean up old reports
    ->daily('12:00');

    // Get all reports from the database
    $reports = Report::all();

    // Go through each task to dynamically set them up.
    foreach ($reports as $report) {
      // Use the scheduler to add the task at its desired frequency
      $frequency = $report->frequency; // everyHour, everyMinute, twiceDaily etc.
      switch ($frequency) {
        case 'dailyAt':
        $schedule->call(function() use($report, $frequency) {
          $report->run();
        })->$frequency($report->time)->when($report->active);
          break;

        case 'weeklyOn':
        $schedule->call(function() use($report, $frequency) {
          $report->run();
        })->$frequency($report->day_of_week, $report->time)->when($report->active);
          break;

        case 'monthlyOn':
        $schedule->call(function() use($report, $frequency) {
          $report->run();
        })->$frequency($report->day_of_month, $report->time)->when($report->active);
          break;

        default:
        $schedule->call(function() use($report, $frequency) {
          $report->run();
        })->$frequency()->when($report->active);
          break;
      }
    }
  }

  /**
  * Register the commands for the application.
  *
  * @return void
  */
  protected function commands()
  {
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
  }
}
