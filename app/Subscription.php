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

    public function SetStatus($id_member, $status)
    {
        $date = getdate();
        $season = Season::where('dateStart', 'LIKE', '%'.$date['year'].'%')->get();
        if(count($season)==0) $season=[Season::all()->last];
        $this->fkUser = $id_member;
        $this->fkSeason =  $season[0]->id;
        $this->fkTypeSubscription = $status;
    }
}
