# Cron Jobs

The web app has several cron jobs built in to perform certain tasks on an interval. These tasks include:

* Running scheduled reports (see [Reporting](reporting.md))
* Backing up the database (defualt: twice daily)
* Cleaning up old files such as:
  * Reports
  * Backups

By default, files older than 3 months are deleted by the cleanup jobs. This can be changed by going into `app/console/commands/Cleanup...php` and changing the line `protected $months_old = 3;` to the desired number of months.

Cron jobs must be set up to run on the operating system. For Ubuntu, see [Project Setup and Documentation](project%20setup%20and%20documentation.md). A log of the cron jobs can be found in `storage/logs/cron.log`.

All cron job related code is stored in the `app/Console` directory. The cron job scheduler itself is located in `app/Console/Kernel.php`. If, for example, one wanted to change how often the database backup is ran from twice daily to hourly, they would change the lines:
```
$schedule->command('db:backup') // Back up database at 7:00 AM and 7:00 PM
->twiceDaily(7, 19);
```
To:
```
$schedule->command('db:backup') // Back up database hourly
->hourly();
```
A full list of scheduling options can be found in the [Laravel Documentation](https://laravel.com/docs/5.6/scheduling#schedule-frequency-options).
