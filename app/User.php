<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $fillable = [
      'isAdmin',
      'isTrainer',
      'isMember',
      'active',
      'validated',
      'invitRight'
  ];

    public function personal_information() {
        return $this->belongsTo('App\PersonalInformation', 'fkPersonalInformation');
    }

    public function subscriptions() {
        return $this->hasMany('App\Subscription', 'fkUser');
    }

}
