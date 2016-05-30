<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'begin_date',
        'end_date'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
}
