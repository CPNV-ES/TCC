<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function user() {
        return $this->belongsTo('App\User', 'fkUser');
    }

    public function season() {
        return $this->belongsTo('App\Season', 'fkSeason');
    }

    public function type_subscription() {
        return $this->belongsTo('App\Type_subscription', 'fkTypeSubscription');
    }
}
