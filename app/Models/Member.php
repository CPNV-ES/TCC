<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Member extends Model
{
    //

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
        $this->phone              = $phone;
        $this->zip_code           = $zip_code;
        $this->inscription_date   = time();
        $this->active             = 0;
        $this->administrator      = 0;
        $this->validate           = 0;
    }

    /*
     * Insert the login, token and activate account
     */
    public function UpdateLogin($login)
    {
        $validationCode = str_random(20);
        $this->login = $login;
        $this->active = 1;
        $this->token = $validationCode;
    }

    public function UpdateUser($data)
    {
        extract($data);
        $this->last_name          = $last_name;
        $this->first_name         = $first_name;
        $this->address            = $address;
        $this->city               = $city;
        $this->email              = $email;
        $this->phone              = $phone;
        $this->zip_code           = $zip_code;
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
