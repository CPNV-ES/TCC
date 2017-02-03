<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localitie extends Model
{

    public function personal_informations() {
        return $this->hasMany('App\Personal_information', 'fkLocality');
    }
}
