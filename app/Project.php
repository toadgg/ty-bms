<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id'];

    public function getLocationAttribute($value)
    {
        return explode(",",$value);
    }

    public function contract()
    {
        return $this->hasOne('App\Contract');
    }

    public function sections()
    {
        return $this->hasMany('App\ProjectSection');
    }

    public function outputValues()
    {
        return $this->hasMany('App\ProjectOutputValueStatement');
    }

    public function receipts()
    {
        return $this->hasMany('App\ProjectReceiptStatement');
    }

    public function capitalPlans()
    {
        return $this->hasMany('App\CapitalPlan');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', 'on');
    }

}
