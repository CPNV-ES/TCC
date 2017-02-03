<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type_subscription extends Model
{

    public function subscriptions() {
        return $this->hasMany('App\Subscription', 'fkTypeSubscription');
    }
}
