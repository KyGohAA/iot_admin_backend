<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use App\Language;
use App\Company;
use App\Setting;
use App\LeafAPI;
use App\Customer;

use Illuminate\Database\Eloquent\Builder;

class MeterSubsidiary extends ExtendModel
{
    protected $table = 'meter_subsidiaries';
    public $timestamps = true;
    protected $listing_except_columns = ['remark','created_by','updated_by','created_at','updated_at','leaf_group_id','subsidize_tenant_id','is_sudsidy_distribute_directly'];

    protected $guarded = [];

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

    public static function combobox()
    {
        return static::ofAvailable('status',true)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select uom...'), '');
    }

    /*
    |--------------------------------------------------------------------------
    | Here to operation of model
    |--------------------------------------------------------------------------
    |
    */

    public function getSubsidyEffectiveDescription()
    {
        return Language::trans('This complementary/subsidy will distribute from').' '.$this->getDate($this->starting_date).' '.Language::trans('till').' '.$this->getDate($this->ending_date).' '.Language::trans('on').' '.$this->getDayInWord($this->implementation_date).' '.Language::trans('of every month.');
    }

    public static function get_subsidy_period($starting_date,$ending_date)
    {   
        $month = 0; 
        $different = Setting::date_differrent(date('Y-m', strtotime($starting_date)),date('Y-m', strtotime($ending_date)));
        if(strpos($different , "year") !== false){
            $different_arr = explode("||", str_replace(",", "" ,str_replace("month", "" ,str_replace("s", "" ,str_replace("year", "||", $different)))));
            
            $month = trim($different_arr[0]) * 12;
            if(isset($different_arr[1])){
                if($different_arr[1] != ""){
                    $month = $month + trim($different_arr[1]);
                }   
            }
            
        }else if(strpos($different , "month") !== false){
           $month = str_replace(",", "" ,str_replace("month", "" ,str_replace("s", "" ,$different)));
        }

        return $month;
    }

    public static function getListByDate($date , $leaf_group_id=null)
    {

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                                ->where('status' , '=' , true)
                                ->where('starting_date' , '=>' , date('Y-m-01', strtotime($date)))
                                ->where('ending_date' , '<=' , date('Y-m-01', strtotime($date)))
                                ->where('implementation_date' , '=' , date('d', strtotime($date)) )
                                ->get();

        return $return;
    }

    public function is_already_assigned_all($date=null)
    {
        $dedicated_month = $date != null ? date('Y-m', strtotime($date)) : date('Y-m', strtotime('now'));
        $tenant_list =  $this->subsidize_tenant_id;
        $dedicated_month_subsidized_list = MeterSubsidiary::get_subsidized_list_by_month($dedicated_month);

        $unassign_leaf_member_id = array_diff($tenant_list , array_column($dedicated_month_subsidized_list['']));

        return  ['status' => count($unassign_leaf_member_id) == 0 ? true : false , 'unassign_leaf_member_id' => $unassign_leaf_member_id];
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

    public static function get_subsidy_by_leaf_group_id($leaf_group_id)
    {
        $result = static::where('status','=',true)
                ->where('leaf_group_id','=',$leaf_group_id)
                ->get();

        return $result;
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
                'code'      =>  'required|unique:meter_subsidiaries,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                'name'      =>  'required|unique:meter_subsidiaries,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:meter_subsidiaries,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:meter_subsidiaries,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

    	$validator = Validator::make($input, $rules);

    	if ($validator->fails()) {
    		return $validator;
    	}
    	return false;
    }

    const excluded_keys = ['status','is_sudsidy_distribute_directly'];
    const control_keys = ['end_date_value','start_date_value'];
    public function save_form($input)
    {   //dd($input);
    	DB::beginTransaction();
    	try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && !in_array($key , static::excluded_keys) && !in_array($key , static::control_keys)) {
                     if($key == 'starting_date'){
                         $this->$key = $this->setDate('01-'.$value);
                     }else if($key == 'ending_date'){
                         $this->$key = $this->setDate('30-'.$value);
                     }else if($key == 'subsidize_tenant_id'){   

                        $temp_arr = array();
                        $room_type_list = Customer::combobox_from_leaf_by_room_type_member_id($input['room_type']);
                       
                        foreach ($value as $index => $tenant_id) {    
                            foreach ($room_type_list as $member_id => $member_name) {
                                
                               if($member_id == $tenant_id){
                                    array_push($temp_arr, $tenant_id);
                                    continue;
                               }
                            }        
                        }

                        $this->$key = json_encode($temp_arr);
          
                     }else{
                         //$this->$key = (string) $value;
                        if($key == 'subsidize_tenant_id_')
                        {
                            $key = 'subsidize_tenant_id';
                        }
                        if(is_array($value))
                        {
                            $this->$key = json_encode($value);
                        }else{
                            $this->$key = (string) $value;
                        }
                        
                     }
                   
                }elseif(in_array($key, static::excluded_keys))
                {   //echo $key.'='.$value."<br>";
                    $this->$key = $value == 'on' ? 1 : 0;
                }


            }
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }
            //dd($this);
    		$this->save();
    	} catch (Exception $e) {
    		throw $e;
    		DB::rollBack();
    	}
    	DB::commit();
    }
}

