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
        'booking_window_not_member',

    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
}
