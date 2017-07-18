<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapitalPlanDetail extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->hasOne('App\Category');
    }

}
