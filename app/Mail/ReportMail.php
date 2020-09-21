<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;
use App\Report;

class ReportMail extends Mailable
{
  use Queueable, SerializesModels;

  public $report;
  public $report_path;
  public $items;
  public $statistics;

  /**
  * Create a new message instance.
  *
  * @return void
  */
  public function __construct(Report $report, String $report_path = null, Collection $items)
  {
    $this->report = $report;
    $this->report_path = $report_path;
    $this->items = $items;
    $this->statistics = getQueryStatistics($items);
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    $view = $this->view('emails.report')->subject('Inventory Report');
    if ($this->report_path !== null) {
      $view->attach($this->report_path, [
        'as' => 'Report-'.$this->report->name.'.csv',
      ]);
    }
    return $view;

  }
}
