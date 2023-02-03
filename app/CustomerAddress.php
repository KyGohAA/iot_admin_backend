<?php

namespace App;

use DB;
use Auth;
use Schema;
use Request;
use Validator;
use Image;

use App\LeafAPI;
use App\Customer;


use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends ExtendModel
{
    protected $table = 'customer_addresses';
    public $timestamps = false;

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function create_or_update_customer_address_by_id_house_member($id_house_member, $leaf_group_id=null)
    {
        $customer = customer::set_customer_by_id_house_member($id_house_member);
        
        


    }


}
