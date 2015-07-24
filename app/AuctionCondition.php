<?php

namespace App;

use App\Common\BaseModel;

class AuctionCondition extends BaseModel
{
    protected $table = 'auction_conditions';

    public $timestamps = false;

    /**
     * Returns true if the provided auction condition ID is valid
     *
     * @param $id
     * @return bool
     */
    public static function isValidConditionId($id)
    {
        $conditions = AuctionCondition::all(['id']);

        return $conditions->contains('id', $id);
    }

}
