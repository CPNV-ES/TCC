<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Member extends Model
{
  //birth_date
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
    public function SetBirthDate($data)
    {
        $this->birth_date         = date("Y-m-d", strtotime($data));
    }


    /*
     * Insert the login, token and activate account
     */
    public function UpdateLogin($login)
    {

        $subscription_per_member = new Subscription_per_member();
        $subscription_per_member->SetStatus($this->id, 1); // ESO : set status to member by default
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
        //$this->birth_date         = date("Y-m-d", strtotime($data['birth_date']));
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
    /*
    * update parameters of a account
    */
    public function UpdateAccount($data)
    {
      // IGI- update account parameters
      $this->to_verify = (array_key_exists('to_verify',$data)) ? $data['to_verify'] : '0';
      $this->administrator = (array_key_exists('administrator',$data)) ? $data['administrator'] : '0';
      $this->validate = (array_key_exists('validate',$data)) ? $data['validate'] : '0';
      $this->active = (array_key_exists('active',$data)) ? $data['active'] : '0';
    }

    public function getCurrentStatusAttribute()
    {
        //Get the current season, then the record for the current season with the current member then the status object
        $currentSeason = Season::where('begin_date', '<', Carbon::today())->where('end_date', '>', Carbon::today())->first();
        $subscriptionPerMember = Subscription_per_member::where('fk_member', $this->id)->where('fk_season', $currentSeason->id)->first();
        $currentStatus = Subscription::find($subscriptionPerMember->fk_subscription);
        return $currentStatus;
    }
}
