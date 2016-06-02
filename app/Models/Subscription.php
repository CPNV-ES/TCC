<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'status',
        'amount'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }
}
