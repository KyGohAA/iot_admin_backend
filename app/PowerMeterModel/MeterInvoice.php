<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;

use App\Setting;
use App\Company;
use Illuminate\Database\Eloquent\Builder;

class MeterInvoice extends ExtendModel
{
    protected $table    =   'meter_invoices';
    public $timestamps  =   true;
    protected $listing_except_columns = ['last_invoice_date','last_meter_reading','icpt_amount','current_month_amount','gst_amount','kwtbb_amount','late_charge','total_amount','created_by','updated_by','created_at','updated_at','leaf_group_id'];

    const new_tag       =   'New';
    protected $guarded  =   [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

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

    public function meter_register()
    {
        return $this->belongsTo('App\PowerMeterModel\MeterRegister', 'meter_register_id');
    }

    public function items()
    {
        return $this->hasMany('App\MeterInvoiceItem', 'meter_invoice_id');
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
    
    public function get_last_meter_reading()
    {
        if ($model = static::where('meter_register_id','=',$this->meter_register_id)->orderBy('id','desc')->first()) {
            return $model->current_meter_reading;
        }
        return 0;
    }

    public function get_meter_register_id($leaf_room_id)
    {
        if ($model = MeterRegister::where('leaf_room_id','=',$leaf_room_id)->first()) {
            return $model->id;
        }
        return false;
    }

    public function get_last_invoice_date()
    {
        return static::where('meter_register_id','=',$this->meter_register_id)->orderBy('id','desc')->pluck('document_date');
    }

    public function general_no()
    {
        $number = static::where('document_date','=',$this->setDate('now'))->count()+1;
        return date('ymd').'/'.str_pad($number, 3, 0, STR_PAD_LEFT);
    }

    public function get_over_due_amount($meter_register_id)
    {
        return self::outstanding_balance($meter_register_id);
    }

    public static function total_invoices($payment_status=null)
    {
        $model =  new static();
        if ($payment_status != null) {
            $model = $model->where('is_paid','=',$payment_status);
        }
        return $model->count();
    }

    public static function outstanding_balance($meter_register_id)
    {
        $model = new self();
        $total_collect = UTransaction::where('meter_register_id','=',$meter_register_id)->sum('amount');
        $bill_amount = static::where('meter_register_id','=',$meter_register_id)
                                ->where('due_date','<=',$model->setDate('now'))->sum('total_amount');
        $total = $total_collect - $bill_amount;
        if ($total < 0) {
            return str_replace('-', '', $total);
        }
        return 0;
    }

    public static function getUnsettleInvoiceListingByRoomOrMeterRegisterId($roomId ,$meterRegisterId){

        $listing = static::where('is_paid' , '=' , false)
                        ->where('outstanding_amount' , '>' , 0);

        if(isset( $roomId)){
             $listing = $listing->where('leaf_room_id' , '=' , $roomId);
        }

        if(isset( $meterRegisterId)){
            $listing = $listing->where('meter_register_id' , '=' , $meterRegisterId);
        }
                       
        return $listing->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of index listing displayed
    |--------------------------------------------------------------------------
    |
    */

    public function table_cols()
    {
        $except = $this->listing_except_columns;

        return array_diff(Schema::getColumnListing($this->table), $except);
    }

    public function listing_header()
    {
        return array_diff($this->table_cols(), $this->listing_except_columns);
    }

    public function scopeListing($query) 
    {
        return $query->select(array_diff($this->table_cols(), $this->listing_except_columns));
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of validation & save form
    |--------------------------------------------------------------------------
    |
    */

    public function validate_form($input)
    {
        $rules = [
                    'leaf_house_id'         =>  'required',
                    'leaf_room_id'          =>  'required',
                    'current_meter_reading' =>  'required',
                    ];

    	$validator = Validator::make($input, $rules);

    	if ($validator->fails()) {
    		return $validator;
    	}
    	return false;
    }

    public function save_form($input)
    {
        $model = MeterRegister::where('leaf_room_id','=',$input['leaf_room_id'])->first();
        $utility_charges = $model->utility_charge ? $model->utility_charge->prices:[];
        $length = count($utility_charges);

        DB::beginTransaction();
        try {
            $this->document_no              =   $this->general_no();
            $this->last_invoice_date        =   $this->get_last_invoice_date();
            $this->document_date            =   $this->setDate('now');
            $this->due_date                 =   $this->setDate('+'.Company::due_date_period().'days');
            $this->meter_register_id        =   $this->get_meter_register_id($input['leaf_room_id']);
            $this->last_meter_reading       =   $this->get_last_meter_reading();
            $this->current_meter_reading    =   $input['current_meter_reading'];
            $this->over_due_amount          =   $this->outstanding_balance($this->meter_register_id);
            $this->current_amount           =   $this->setDouble(0);
            $this->icpt_amount              =   $this->setDouble(0);
            $this->current_month_amount     =   $this->setDouble(0);
            $this->gst_amount               =   $this->setDouble(0);;
            $this->kwtbb_amount             =   $this->setDouble(0);;
            $this->late_charge              =   $this->setDouble(0);
            $this->total_amount             =   $this->setDouble(0);
            if (!$this->id) {
                $this->created_by           =   Auth::id() ? Auth::id():0;
                $this->updated_by           =   0;
                $this->leaf_group_id        =   Company::get_group_id();
            } else {
                $this->updated_by           =   Auth::id() ? Auth::id():0;
            }
            $this->save();

            $this->items()->delete();
            $total_current_usage = ($this->current_meter_reading - $this->last_meter_reading);
            foreach ($utility_charges as $index => $row) {
                if ($row['started'] <= $total_current_usage) {
                    $item = new MeterInvoiceItem();
                    if ($length == ($index+1)) {
                        $item['meter_block']   =   $row['started'];
                        $item['meter_usage']   =   ($total_current_usage - $row['started'])+1;
                    } else {
                        $item['meter_block']   =   $row['started'].' - '.$row['ended'];
                        $item['meter_usage']   =   (($total_current_usage > $row['ended'] ? $row['ended']:$total_current_usage) - $row['started'])+($index ? 1:0);
                    }
                    $item['unit_price']    =   $this->setDouble($row['unit_price']);
                    $item['total_price']   =   $this->setDouble($item['meter_usage'] * $item['unit_price']);
                    $item['is_gst']        =   $row['is_gst'];
                    $item['gst_amount']    =   $row['is_gst'] ? ($item['total_price'] * (6/100)):0;
                    $item['total_amount']  =   $item['total_price']+$item['gst_amount'];
                    $this->current_amount  +=   $this->setDouble($item['total_price']);
                    $this->gst_amount += $item['gst_amount'];
                    $this->items->save($item);
                }
            }

            $this->icpt_amount              =   $this->setDouble($total_current_usage * 0.0152);
            $this->current_month_amount     =   $this->setDouble($this->current_amount - $this->icpt_amount);
            $this->gst_amount               =   $this->setDouble($this->gst_amount);;
            $this->kwtbb_amount             =   $this->setDouble($this->current_amount * (1.6/100));;
            $this->late_charge              =   $this->setDouble(0);
            $this->total_amount             =   $this->setDouble($this->current_month_amount+$this->over_due_amount+$this->gst_amount+$this->kwtbb_amount+$this->late_charge);
            $this->save();
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }
}
