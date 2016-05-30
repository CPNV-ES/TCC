<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'fk_court',
        'fk_member_1',
        'fk_member_2',
        'fk_season',
        'date_hours'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
}
