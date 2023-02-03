<?php

namespace App\PowerMeterModel;

use Illuminate\Database\Eloquent\Model;

class MeterPaymentReceivedItem extends Model
{
    protected $table = 'meter_payment_received_items';
    public $timestamps = false;

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function ar_invoice()
    {
        return $this->belongsTo('App\MeterPaymentReceived', 'meter_payment_received_id');
    }

}
