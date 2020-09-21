<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Item extends Model
{
  use SoftDeletes;
  protected $table = 'items';
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
    'deleted_at'
  ];

  public function location() {
    return $this->belongsTo(Value::Class);
  }
}
