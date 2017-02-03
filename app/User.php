<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    public function personal_information() {
        return $this->belongsTo('App\Personal_information', 'fkPersonalInformation');
    }

    public function subscriptions() {
        return $this->hasMany('App\Subscription', 'fkUser');
    }
}
