<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantsPhone extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
