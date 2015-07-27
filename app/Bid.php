<?php

namespace App;

use App\Common\BaseModel;

class Bid extends BaseModel
{
    protected $table = 'bids';

    protected $fillable = [
        'amount',
        'auction_id',
        'user_id',
    ];

    /**
     * Disables use of the "updated_at" column
     *
     * @param $value
     * @return null
     */
    public function setUpdatedAtAttribute($value)
    {
        return null;
    }
}
