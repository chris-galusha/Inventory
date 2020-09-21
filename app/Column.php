<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
  protected $table = 'columns';
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

  public function values() {
    return $this->belongsToMany(Value::Class);
  }

  public function type() {
    return $this->belongsTo(Type::Class);
  }
}
