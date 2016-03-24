<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription_per_member;

class Member extends Model
{
    public function Status()
    {
        return $this->belongsToMany('App\Models\Subscription', 'subscriptions_per_member', 'fk_member', 'fk_subscription');
    }


    /*
 * Create a user with the data givens
 */
    public function CreateUser($data)
    {
        extract($data);
        $this->last_name          = $last_name;
        $this->first_name         = $first_name;
        $this->address            = $address;
        $this->city               = $city;
        $this->email              = $email;
        $this->mobile_phone       = $mobile_phone;
        $this->home_phone         = $home_phone;
        $this->zip_code           = $zip_code;
        $this->active             = 0;
        $this->administrator      = 0;
        $this->validate           = 0;
        $this->birth_date         = date("Y-m-d", strtotime($birth_date));
    }
    /*
     * Insert the login, token and activate account
     */
    public function UpdateLogin($login, $status)
    {
        $subscription_per_member = new Subscription_per_member();
        $subscription_per_member->SetStatus($this->id, $status);
        $subscription_per_member->save();

        $validationCode     = str_random(20);
        $this->login        = $login;
        $this->validate     = 1;
        $this->token        = $validationCode;
    }
    public function UpdateUser($data)
    {
        extract($data);
        $this->last_name          = $last_name;
        $this->first_name         = $first_name;
        $this->address            = $address;
        $this->city               = $city;
        $this->email              = $email;
        $this->mobile_phone       = $mobile_phone;
        $this->home_phone         = $home_phone;
        $this->zip_code           = $zip_code;
        $this->birth_date         = date("Y-m-d", strtotime($birth_date));
    }
    /*
     * Hash and update the password
     */
    public function UpdatePassword($password)
    {
        $this->password = Hash::make($password);
        $this->token = null;
    }
}
