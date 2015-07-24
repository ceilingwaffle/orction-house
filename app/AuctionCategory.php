<?php

namespace App;

use App\Common\BaseModel;

class AuctionCategory extends BaseModel
{
    protected $table = 'auction_categories';

    public $timestamps = false;

    /**
     * Returns true if the provided category ID is valid
     *
     * @param $id
     * @return bool
     */
    public static function isValidCategoryId($id)
    {
        $categories = AuctionCategory::all(['id']);

        return $categories->contains('id', $id);
    }

}
