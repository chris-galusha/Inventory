<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
  protected $table = 'values';
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

  public function columns() {
    return $this->belongsToMany(Column::Class);
  }
}
