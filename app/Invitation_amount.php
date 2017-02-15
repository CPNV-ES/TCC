<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation_amount extends Model
{

    public function invitations() {
        return $this->hasMany('App\Invitation', 'fkInvitationAmount');
    }
}
