<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $table = 'roles';
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

public function isAdmin() {
  return $this->id == 1;
}

public function isNormal() {
  return $this->id == 2;
}

public function isNormalOrBetter() {
  return $this->id <= 2;
}

public function isGuest() {
  return $this->id == 3;
}

}
