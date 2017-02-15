<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $fillable = [
        'NPA',
        'name'
    ];
    public function personal_informations() {
        return $this->hasMany('App\Personal_information', 'fkLocality');
    }
}
