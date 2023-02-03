<?php

namespace App\PowerMeterModel;

use Illuminate\Database\Eloquent\Model;

class MeterRefundItem extends Model
{
    protected $table = 'meter_refund_items';
    public $timestamps = false;

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function meter_refund()
    {
        return $this->belongsTo('App\MeterRefund', 'meter_refund_id');
    }

}
