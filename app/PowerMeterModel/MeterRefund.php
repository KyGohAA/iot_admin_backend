<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use App\Company;

use Illuminate\Database\Eloquent\Builder;

class MeterRefund extends ExtendModel
{
    protected $table = 'meter_refunds';
    public $timestamps = true;
    protected $listing_only_columns = ['id','document_date','document_no','customer_name','payment_method','reference_no','pay_from','currency_code','total_amount'];
    //'sales_person' , 'remark', 'tag'
    protected $guarded = [];

    const label_status_progress = 'PROGRESS';

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

    public function setDocumentDateAttribute($value)
    {
        return $this->attributes['document_date'] = $this->setDate($value);
    }

    public function getDocumentDateAttribute($value)
    {
        return $this->getDate($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }


    public function items()
    {
        return $this->hasMany('App\MeterRefundItem', 'meter_refund_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's listing
    |--------------------------------------------------------------------------
    |
    */

    public static function sort_by_combobox()
    {
     
        return [''=>'Please select one...','amount'=>'Amount','customer_id'=>'Customer','currency_id'=>'Currency','document_date'=>'Date','document_no'=>'Receipt No','reference_no' => 'Reference No.','pay_from'=>'Pay From','payment_method'=>'Payment Method'];

    }

    public static function combobox()
    {
        return static::orderBy('payment_no','asc')
                        ->pluck('payment_no','payment_no')
                        ->prepend(Language::trans('Please select invoice...'), '');
    }


    /*
    |--------------------------------------------------------------------------
    | Here to manage of index listing displayed
    |--------------------------------------------------------------------------
    |
    */

    public function table_cols()
    {
        return $this->listing_only_columns;
    }

    public function listing_header()
    {
        return $this->listing_only_columns;
    }

    public function scopeListing($query) 
    {
        return $query->select($this->listing_only_columns);
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of validation & save form
    |--------------------------------------------------------------------------
    |
    */

    public function gen_document_no()
    {
        $number = static::where('document_date','=',$this->setDate('now'))->count()+1;
        return date('ymd').'/'.str_pad($number, 3, 0, STR_PAD_LEFT);
    }

    public function validate_form($input)
    {
        $rules = [];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    const save_alert_columns = ['_token','products','name','contact_person','billing_address1','billing_address2','email','billing_country_id','billing_state_id','billing_postcode','deposit_to_account','remark',''];
    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            foreach ($input as $key => $value) {
                if (!in_array($key,static::save_alert_columns)) {
                    $this->$key = (string) $value;
                }
            }
            $this->customer_name        =   $input['name'];
            $this->currency_code        =   $this->display_relationed('currency', 'symbol');
            $this->document_no          =   $this->id ? $this->document_no:$this->gen_document_no();
            $this->document_date        =   date('Y-m-d', strtotime($this->document_date));
            $this->status               =   static::label_status_progress;
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }
            $this->amount               =   0;
            $this->gst_amount           =   0;
            $this->save();

            $this->items()->delete();
            /*foreach ($input['products'] as $row) {
                if ($row['product_id']) {
                    $item = new MeterRefundItem();
                    foreach ($row as $ikey => $ivalue) {
                        if ($ikey != 'description') {
                            $item->$ikey        =   $ivalue;
                        }
                    }
                    $item->product_name         =   $this->display_relationed('product', 'name');
                    $item->product_description  =   $row['description'];
                    $this->items->save($item);
                    $this->amount           +=  $this->setDouble($row['amount']-$row['tax_txt']);
                    $this->gst_amount       +=  $this->setDouble($row['tax_txt']);
                }
            }*/
            $this->total_amount         =   $this->setDouble($this->amount+$this->gst_amount);
            $this->save();
           /* if (!$this->updated_by) {
                $params['refund_header'][0]['address']              =   $this->get_billing_address();
                $params['refund_header'][0]['customer_recordid']    =   $this->display_relationed('customer', 'ncl_id');
                $params['refund_header'][0]['customer_code']        =   $this->display_relationed('customer', 'code');
                $params['refund_header'][0]['contact']              =   $this->contact_person;
                $params['refund_header'][0]['currency_code']        =   $this->currency_code;
                $params['refund_header'][0]['currency_rate']        =   $this->currency_rate;
                $params['refund_header'][0]['phone_no']             =   $this->phone_no;
                $params['refund_header'][0]['remark']               =   $this->remark;
                $params['refund_header'][0]['return_payment_date']  =   date('Y-m-d', strtotime($this->return_payment_date));
                $params['refund_header'][0]['reason']               =   $this->reason;
                $params['refund_header'][0]['date']                 =   $this->document_date;
                $params['refund_header'][0]['location']             =   '';
                $params['refund_header'][0]['purchase_order_no']    =   $this->po_no;
                $params['refund_header'][0]['payment_terms']        =   '';
                $params['refund_header'][0]['salesperson']          =   $this->sales_person;
                $params['refund_header'][0]['tax_type']             =   Company::get_is_inclusive() ? 'I':'E';
                $params['refund_header'][0]['status']               =   $this->status ? 'active':'inactive';
                $i=1;
                foreach ($this->items as $row) {
                    $params['refund_details'][]    =  [
                                'item_recordid'        =>   $row->display_relationed('product', 'ncl_id'),
                                'item_code'            =>   $row->display_relationed('product', 'code'),
                                'description'          =>   $row->product_description,
                                'quantity'             =>   $row->quantity,
                                'unit_of_measurement'  =>   $row->uom,
                                'unit_price'           =>   $row->amount/$row->quantity,
                                'tax_code'             =>   $row->display_relationed('tax', 'code'),
                            ]; 
                    $i++;
                }
                $ncl_api = new NclAPI();
                $ncl_id = $this->ncl_id ? $this->ncl_id:null;
                if ($result = $ncl_api->set_invoice($params, $ncl_id, 'sale')) {
                    DB::table('MeterRefunds')->where('id','=',$this->id)->update(['ncl_id'=>$result['register_id']]);
                }
            }*/
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }
}
