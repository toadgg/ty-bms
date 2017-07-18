<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSection extends Model
{
    protected $guarded = ['id'];

    protected $touches = ['project'];


    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
