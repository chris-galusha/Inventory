<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  protected $table = 'reports';
  protected $casts = [
        'query_builder' => 'array',
        'options' => 'array'
    ];
  protected $guarded = [
    'id',
    'created_at',
    'updated_at'
  ];

  function run() {
    app('App\Http\Controllers\ReportController')->runReport($this);
  }
}
