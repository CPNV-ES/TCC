<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription_per_member extends Model
{
    protected $table = 'subscriptions_per_member';
    public function SetStatus($id_member, $status)
    {
        $date = getdate();
        $season = Season::where('begin_date', 'LIKE', '%'.$date['year'].'%')->get();
        $this->fk_member = $id_member;
        $this->fk_season =  $season[0]->id;
        $this->fk_subscription = $status;
    }
}
