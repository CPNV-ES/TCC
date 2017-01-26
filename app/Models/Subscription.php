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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Struan Forsyth
     */
    public function seasons()
    {
        return $this->belongsToMany('App\Models\Season', 'subscriptions_per_member', 'fk_subscription', 'fk_season');
    }
}
