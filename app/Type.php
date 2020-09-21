<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
  protected $table = 'types';
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

  public function columns() {
    return $this->hasMany(Column::Class);
  }

  public function isDate() {
    return in_array($this->html_type, ['date', 'datetime-local']);
  }

  public function isText() {
    return $this->html_type == 'text';
  }
}
