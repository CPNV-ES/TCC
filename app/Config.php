<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
  protected $fillable = [
      'nbDaysGracePeriod',
      'nbDaysLimitNonMember',
      'courtOpenTime',
      'courtCloseTime',
      'nbReservations'
  ];
}
