<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'dateStart',
        'dateEnd',
    ];

    public function subscriptions() {
        return $this->hasMany('App\Subscription', 'fkSeason');
    }
}
