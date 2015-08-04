<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackType extends Model
{
    const POSITIVE = 1;
    const NEUTRAL = 2;
    const NEGATIVE = 3;

    protected $table = 'feedback_types';

    public $timestamps = false;

}
