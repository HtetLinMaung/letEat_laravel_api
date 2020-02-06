<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function phones() {
        return $this->hasMany('App\RestaurantsPhone');
    }
}
