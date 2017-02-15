<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{

    public function reservation() {
        return $this->belongsTo('App\Invitation', 'fkReservation');
    }

    public function invitation_amount() {
        return $this->belongsTo('App\Invitation', 'fk_InvitationAmount');
    }

}
