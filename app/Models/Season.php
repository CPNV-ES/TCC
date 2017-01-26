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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Struan Forsyth
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\Models\Subscription', 'subscriptions_per_member', 'fk_season', 'fk_subscription');
    }
}
