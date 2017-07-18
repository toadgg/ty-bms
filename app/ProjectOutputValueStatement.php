<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectOutputValueStatement extends Model
{
    protected $guarded = ['id'];

    protected $touches = ['project'];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function totalBefore($calendar)
    {
        $total = 0.0;
        if (array_key_exists('id', $this->attributesToArray())) {
            if($this->attributes['year'] == $calendar->year) {
                for($i = 1; $i<=$calendar->month; $i++) {
                    $total += $this->attributes["m_$i"];
                }
            } elseif($this->attributes['year'] < $calendar->year) {
                for($i = 1; $i<=12; $i++) {
                    $total += $this->attributes["m_$i"];
                }
            }
        }
        return $total;
    }

    public function timesBefore($calendar)
    {
        $times = 0;
        if (array_key_exists('id', $this->attributesToArray())) {
            if($this->attributes['year'] == $calendar->year) {
                for($i = 1; $i<=$calendar->month; $i++) {
                    if($this->attributes["m_$i"]) {
                        $times++;
                    };
                }
            } elseif($this->attributes['year'] < $calendar->year) {
                for($i = 1; $i<=12; $i++) {
                    if($this->attributes["m_$i"]) {
                        $times++;
                    };
                }
            }
        }
        return $times;
    }
}
