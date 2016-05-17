<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription_per_member;

class Member extends Model
{

    protected $fillable = [
        'last_name',
        'first_name',
        'address',
        'city',
        'email',
        'mobile_phone',
        'home_phone',
        'zip_code'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->active             = 0;
        $this->administrator      = 0;
        $this->validate           = 0;
        $this->to_verify          = 0;
    }

    public function Status()
    {
        return $this->belongsToMany('App\Models\Subscription', 'subscriptions_per_member', 'fk_member', 'fk_subscription');
    }


    /*
 * Take the date in EU format and change it to US
 */
    public function SetBirthDathe($data)
    {
        $this->birth_date         = date("Y-m-d", strtotime($data));
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
        $this->last_name          = $data['last_name'];
        $this->first_name         = $data['first_name'];;
        $this->address            = $data['address'];;
        $this->city               = $data['city'];;
        $this->email              = $data['email'];;
        $this->mobile_phone       = $data['mobile_phone'];;
        $this->home_phone         = $data['home_phone'];;
        $this->zip_code           = $data['zip_code'];;
        $this->birth_date         = date("Y-m-d", strtotime($data['birth_date']));
        $this->to_verify          = 0;
    }
    
    /*
     * Hash and update the password
     */
    public function UpdatePassword($password)
    {
        $this->password = Hash::make($password);
        $this->token = null;
    }

    public function EditMember($id, $switch_bool)
    {
        $this->id = $id;
        $this->switch_bool = $switch_bool;

    }
}
