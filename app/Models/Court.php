<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = [
        'name',
        'indor',
        'start_time',
        'end_time',
        'booking_window_member',
        'booking_window_not_member'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        //Check if there is a 'indor' = on in the attributes, if yes, it's a indor court
        if(array_key_exists('indor', $attributes) && $attributes['indor'] == 'on')
        {
            $this->indor = 1;
        }
    }
}
