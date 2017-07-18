<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['name', 'code', 'type', 'advance_payment_amount', 'advance_payment_times',
        'progress_payment_pct', 'pay_mode', 'signed_money', 'sync_pk', 'visible',
        'project_id', 'signed_date', 'content', 'sync_at', 'sync_from'];

    protected $appends = ['advance_payment_mode'];

    public function scopeVisible($query)
    {
        return $query->where('visible', 'on');
    }

    public function getAdvancePaymentModeAttribute()
    {
        $times = $this->attributes['advance_payment_times'];
        if (is_null($times)) {
            return "按产值比例";
        } else {
            return "按约定次数 $times 次扣除";
        }
    }

    public function getPayModeAttribute($value)
    {
        return $value == 0 ? '按进度' : '按部位';
    }

}
