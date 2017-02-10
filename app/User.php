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
        'invitRight',
        'fkPersonalInformation'
    ];

    public function personal_information() {
        return $this->belongsTo('App\PersonalInformation', 'fkPersonalInformation');
    }

    public function subscriptions() {
        return $this->hasMany('App\Subscription', 'fkUser');
    }

    public function UpdateLogin($login){
      $subscription = new Subscription();
      $subscription->SetStatus($this->id, 1);
      $subscription->save();

      $this->username        = $login;
      $this->validated     = 1;
      $this->personal_information->token = str_random(20);
    }


}
