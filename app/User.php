<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $fillable = [
        'isAdmin',
        'isTrainer',
        'isMember',
        'active',
        'validated',
        'invitRight',
        'fkPersonalInformation',
        'invalidatedDate'
    ];

    public function personal_information() {
        return $this->belongsTo('App\PersonalInformation', 'fkPersonalInformation');
    }

    public function subscriptions() {
        return $this->hasMany('App\Subscription', 'fkUser');
    }

    public function UpdateAccountParam($data)
    {
        // IGI- update account parameters
        $this->isAdmin = (array_key_exists('isAdmin',$data)) ? $data['isAdmin'] : '0';
        $this->isTrainer = (array_key_exists('isTrainer',$data)) ? $data['isTrainer'] : '0';
        $this->isMember = (array_key_exists('isMember',$data)) ? $data['isMember'] : '0';
        $this->validated = (array_key_exists('validated',$data)) ? $data['validated'] : $this->validated;
        $this->active = (array_key_exists('active',$data)) ? $data['active'] : '0';
        $this->invitRight = (array_key_exists('invitRight',$data)) ? $data['invitRight'] : '0';
        $this->invalidatedDate = (array_key_exists('invalidatedDate',$data)) ? $data['invalidatedDate'] : null;
    }
    public function UpdateLogin($login){
        $subscription = new Subscription();
        $subscription->SetStatus($this->id, 1);
        $subscription->save();

        $this->username      = $login;
        $this->validated     = 1;
        $this->personal_information->_token = str_random(20);
        $this->personal_information->save();
        $this->save();
    }
    public function UpdatePassword($password){
        $this->password = Hash::make($password);
        $this->personal_information->_token = null;
        $this->save();
        $this->personal_information->save();
    }
}
