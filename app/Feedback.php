<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'message',
        'rating',
        'auction_id',
        'feedback_type_id',
        'left_by_user_id',
    ];
}
