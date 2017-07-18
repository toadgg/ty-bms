<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapitalPlan extends Model
{
    protected $guarded = ['id'];

    public function plate()
    {
        return $this->belongsTo('App\Plate');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function details()
    {
        return $this->hasMany('App\CapitalPlanDetail');
    }
}
