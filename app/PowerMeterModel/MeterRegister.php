<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use App\Company;
use App\Room;
use App\House;
use App\Language;
use App\LeafAPI;
use App\PowerMeterModel\MeterClass;
use App\PowerMeterModel\CustomerPowerUsageSummary;
//use App\
//use App\
//use App\
//use App\
use Illuminate\Database\Eloquent\Builder;

class MeterRegister extends ExtendModel
{
    protected $table = 'meter_registers';
    public $timestamps = true;
    protected $listing_except_columns = ['status','rf_id','account_no','contract_no','meter_id','last_meter_reading','last_reading','reading_status','billing_address1','billing_address2','billing_postcode','billing_city_id','billing_state_id','billing_country_id','created_by','updated_by','created_at','updated_at','leaf_group_id','deposit','utility_charge_id','adjustment_usage_days','room_id','house_id','unit_id','reference_no','modbus_command','on_value','off_value','relay_controller_ip_address','reading_adjustment_data'];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

    public static function getMeterRegisterDetail($input)
    {
        //dd($input);
        $result = static::where('leaf_group_id','=',$input['leaf_group_id'])
                ->whereIn('ip_address',$input['ip_addresses'])
                ->select('leaf_room_id','meter_id','id','ip_address')
                ->get();
        return $result;
    }

    public function getLeafHouseIdAttribute()
    {
        $house  = new House();
        $fdata      =   $house->get_houses();
        if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
                    if (isset($house['house_rooms'])) {
                        foreach ($house['house_rooms'] as $room) {
                            if ($room['id_house_room'] == $this->leaf_room_id) {
                                return $house['id_house'];
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function utility_charge()
    {
        return $this->belongsTo('App\UtilityCharge', 'utility_charge_id');
    }

    public function meter_class()
    {
        return $this->belongsTo('App\PowerMeterModel\MeterClass', 'meter_class_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function billing_country()
    {
        return $this->belongsTo('App\Country', 'billing_country_id');
    }

    public function billing_state()
    {
        return $this->belongsTo('App\State', 'billing_state_id');
    }

    public function billing_city()
    {
        return $this->belongsTo('App\City', 'billing_city_id');
    }

    public function getSwithOnOffCaseId()
    {
        return $this->meter_relay_test_id;
        /*$cpus_model = CustomerPowerUsageSummary::getByMeterRegisterId($this->id);
        return isset($cpus_model['meter_relay_test_id']) ? $cpus_model['meter_relay_test_id'] : 0;*/
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
        /*parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id());
        });*/
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's listing
    |--------------------------------------------------------------------------
    |
    */

    public static function get_id($input)
    {
        if ($model = DB::table('meter_registers')->where('ip_address','=',$input['ip_address'])->where('meter_id','=',$input['meter_id'])->first()) {
            return $model->id;
        }
        return false;
    }

    public static function get_meters_by_id_listing($id_listing)
    {
        $result = static::whereIn('id',$id_listing)
                 ->get();

        return $result;
    }

    public static function get_active_meter_register_by_leaf_group_id($leaf_group_id)
    {
        $result = static::where('status','=',true)
                ->where('leaf_group_id','=', (int)$leaf_group_id)
                ->select('leaf_room_id','meter_id','id')
                ->get();
        return $result;
    }

    public static function get_by_meter_register_id($meter_register_id)
    {
        $result = static::where('status','=',true)
                ->where('meter_id','=',$meter_register_id)
                ->get();

        return $result;
    }

    public static function combobox()
    {
        $house = new House();
        $rooms = $house->get_houses();
        $return[] = Language::trans('Please select room no...');
        $listing = static::ofAvailable('status',true)->get();
        foreach ($listing as $row) {
            $return[$row->id]   =   $row->convert_room_no($row->leaf_room_id, $rooms);
        }
        return $return;
    }

    public static function houses_array()
    {
        $house  = new House();
        $return     =   [];
        $fdata      =   $house->get_houses();
        if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
                    $return[] = [
                                    'id_house'    =>  $house['id_house'],
                                    'house_unit'  =>  $house['house_unit'],
                                    ];
                }
            }
        }
        return $return;
    }
    
    public static function rooms_array($house_id=null, $combobox=false)
    {
        $return[]   =   ['room_id'=>'','room_name'=>Language::trans('Please select room no...')];
        $house['house_rooms'] = Room::ofAvailable('status',true)
                                ->where('id_house', '=' , $house_id)
                                ->get();
                              
        if (!$combobox) { $return = [];}

        if (isset($house['house_rooms'])) {
            foreach ($house['house_rooms'] as $room) {
                $return[] = [
                                'room_id'   =>  $room['id_house_room'],
                                'room_name'   =>  $room['house_room_name'],
                                ];
            }
        }
        //dd($return);
        $rm = array_column($return,'room_name');
        asort($rm);
       /* dd($rm);*/
        $n_return = array();
        $last_element = '';

        foreach($rm as $index => $value)
        {
            $n_return[] = $return[$index];
            $last_element = $index;
        }
        //dd($last_element);
        $le = $return[$last_element];
        //dd($le);
        unset($n_return[count($rm)-1]);
        array_unshift($n_return, $le);
        //dd($n_return);
        return $n_return;
    }

    public static function houses_combobox()
    {
        $house  = new House();
        $return[]   =   Language::trans('Please select house no...');
        $fdata      =   $house->get_houses();

        if ($fdata['status_code']) {

            if (isset($fdata['house']) && $houses = $fdata['house']) {
      
                foreach ($houses as $house) {
                    $return[$house['id_house']] = $house['house_unit'];
                }
            }
        }

        return $return;
    }

    public static function rooms_combobox($house_id=null)
    {
        $house  = new House();
        $return[]   =   Language::trans('Please select room no...');
        $fdata      =   $house->get_houses();
        if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
                    if (isset($house['house_rooms']) && $house['id_house'] == $house_id) {
                        foreach ($house['house_rooms'] as $room) {
                            $return[$room['id_house_room']] =   $room['house_room_name'];
                        }
                    }
                }
            }
        }
        return $return;
    }
    //MOICW - auto_on_off
    public static function house_rooms_combobox($house_id=null)
    {
        $house   =   new House();
        $leaf_api = new LeafAPI();
        $fdata      =   $leaf_api->get_houses(true);
        $return[]   =   Language::trans('Please select house no...');
        //$fdata      =   $house->get_houses();

        if ($fdata['status_code']) {

            if (isset($fdata['house']) && $houses = $fdata['house']) {
    
                foreach ($houses as $house) {
                $house['id_house'] = $house['house_unit'];
                
                    foreach ($house['house_rooms'] as $room) {
                        $return[$room['id_house_room']] =   $house['house_unit']." Room ".$room['house_room_name'];
                    }
            
                }
            }
        }
    
        return $return;
    }
    public function get_billing_address()
    {
        $string = null;
        $city = $this->display_relationed('billing_city','name');
        $state = $this->display_relationed('billing_state','name');
        $country = $this->display_relationed('billing_country','name');
        $string .= ($city ? (', '.$city):null);
        $string .= ($state ? (', '.$state):null);
        $string .= ($country ? (', '.$country):null);
        return rtrim($this->billing_address1, ',').', '.rtrim($this->billing_address2, ',').', '.$this->billing_postcode.$string;
    }


    public static function get_meter_register_by_leaf_room_id($id)
    {

        $model = static::where('leaf_room_id' ,'=' , $id)
                ->where('status','=',1)
                ->orderBy('updated_at', 'desc')
               /* ->select('id','account_no','contract_no','meter_id','leaf_room_id','deposit','billing_address1','billing_address2','billing_postcode','billing_city_id','billing_state_id','billing_country_id')*/
                //->get();
               ->first();
           
        return $model;
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
        $company = new Company();
        $rules = [
                    'leaf_room_id'  =>  'required|unique:meter_registers,leaf_room_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['leaf_room_id']  =   'required|unique:meter_registers,leaf_room_id,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    const bulk_upload_mappers = ['meter_id'=>'modbus_address' , 'ip_address' => 'ip_address' ,'leaf_room_id' => 'leaf_room_id' , 'rf_id'=>'rf_id' , 'device_name'=>'device_name' , 'modbus_address'=>'modbus_address'];
    const default_value_mappers = ['meter_class_id'=>'1' , 'utility_charge_id' => 0,'deposit' => 0,'adjustment_usage_days' => '[]','is_power_supply_on' => true, 'billing_address1' => '','billing_address2' => '','billing_postcode' => '','billing_city_id' => '','billing_state_id' => '','billing_country_id' => '','status' => true,'reading_status' => '' ,'account_no' => '' ,'contract_no' => ''];
    //'last_reading_at' => '','last_reading' => '' ,'last_meter_reading' => ''
    public static function saveOrUpdateMeterRegisters($input)
    {
        $return = ['save_record' => 0, 'update_record' => 0 ];

        DB::beginTransaction();
        try {

            foreach ($input['bulk_upload'] as $key => $value) {
                //dd($value['leaf_room_id']);
                 if(!isset($value['leaf_room_id']))
                 {
                    continue;
                 }
                 //echo $value['leaf_room_id'];
                 $model = static::get_meter_register_by_leaf_room_id($value['leaf_room_id']);
                 //dd($model);
                 $model = isset($model['id']) ? $model : new MeterRegister();


                 if(isset($model['id']))
                 {
                    $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
                    $model['updated_by'] = Auth::id() ? Auth::id():0;
                   
                    $return['update_record'] ++;
                 }else{
                    
                    $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                    $model['created_by'] = Auth::id() ? Auth::id():0;
                    $model['leaf_group_id'] = Company::get_group_id();

                    $return['save_record'] ++;
                 }


                 foreach (static::bulk_upload_mappers as $meter_key => $input_key)
                 {
                    $model[$meter_key] = isset($value[$input_key]) ? $value[$input_key] : 0;
                 }

                 foreach (static::default_value_mappers as $meter_key => $default_value)
                 {
                    $model[$meter_key] = $default_value;
                 }

                 $model->save();
         
            }
           

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }

        DB::commit();
        return $return;
    }

    const bulk_upload_remote_on_off_variables = ['modbus_command','on_value','off_value','unit_id','reference_no','relay_controller_ip_address','is_remote_ready','remote_status_comment'];
    public static function saveOrUpdateMeterRegistersRemoteControl($input)
    {
        $return = ['save_record' => 0, 'update_record' => 0 ];

        DB::beginTransaction();
        try {

            foreach ($input['bulk_upload'] as $key => $value) {
                //dd($value['leaf_room_id']);
                 if(!isset($value['leaf_room_id']))
                 {
                    continue;
                 }

                 $model = static::get_meter_register_by_leaf_room_id($value['leaf_room_id']);
                 $model = isset($model['id']) ? $model : new MeterRegister();


                 if(isset($model['id']))
                 {
                    $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
                    $model['updated_by'] = Auth::id() ? Auth::id():0;
                   
                    $return['update_record'] ++;
                 }else{
                    
                    $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                    $model['created_by'] = Auth::id() ? Auth::id():0;
                    $model['leaf_group_id'] = Company::get_group_id();

                    $return['save_record'] ++;
                 }


                 foreach (static::bulk_upload_remote_on_off_variables as $key)
                 {
                    $model[$key] = isset($value[$key]) ? $value[$key] : 0;
                    /*if($key == 'modbus_command')
                    {
                        $model[$key] = isset($value[$key]) ? '%MW'.$value[$key].'%X' : '';
                    }else{
                        $model[$key] = isset($value[$key]) ? $value[$key] : 0;
                    }*/
                    
                 }

                 foreach (static::default_value_mappers as $meter_key => $default_value)
                 {
                    $model[$meter_key] = $default_value;
                 }

                 $model->save();
         
            }
           

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }

        DB::commit();
        return $return;
    }

    const bulk_update_mappers = ['utility_charge_id'=>'utility_charge_id'];
    public static function saveOrUpdateMeterRegistersPrice($input)
    {

        $return = ['save_record' => 0, 'update_record' => 0 ];
        DB::beginTransaction();
        try {

            foreach ($input['bulk_upload'] as $key => $value) {

                 if(!isset($value['leaf_room_id']))
                 {
                    continue;
                 }

                 $model = static::get_meter_register_by_leaf_room_id($value['leaf_room_id']);
                 $model = isset($model['id']) ? $model : new MeterRegister();


                 if(isset($model['id']))
                 {
                    $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
                    $model['updated_by'] = Auth::id() ? Auth::id():0;
                   
                    $return['update_record'] ++;
                 }

                 foreach (static::bulk_update_mappers as $meter_key => $input_key)
                 {
                    $model[$meter_key] = isset($value[$input_key]) ? $value[$input_key] : 0;
                 }

                 $model->save();
         
            }
           

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }

        DB::commit();
        return $return;

    }

    const update_meter_excluded_keys = ['monthly_usages' ];
    public function updateMeterRegisterStatus($input)
    {
        DB::beginTransaction();
        try {

            foreach ($input as $key => $value) {
                if($key == 'meter_data'){
                    $meter_data = json_decode($value);
                    foreach ($meter_data as $meter_key => $meter_value)
                    {
                        if ($meter_key != '_token' && $meter_key != 'leaf_house_id' && $meter_key != 'is_from_meter_pairing' && !in_array($meter_key , static::update_meter_excluded_keys)) {
                             $this->$meter_key = (string) $meter_value;
                         }
                    }
                }elseif ($key != '_token' && $key != 'leaf_house_id' && $key != 'is_from_meter_pairing' /*&& !in_array($key , static::excluded_keys)*/) {
                    $this->$key = (string) $value;
                }/*elseif(in_array($key, static::update_meter_excluded_keys))
                {
                    $this->$key = $value == 'on' ? true : false;
                }else if(){

                }*/
            }
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }

             
            $this->save();

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
        //dd($this);
        return $this;
    }

    const excluded_keys = ['status' , 'is_power_supply_on'];
    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'leaf_house_id' && $key != 'is_from_meter_pairing' && !in_array($key , static::excluded_keys)) {
                    $this->$key = (string) $value;
                }elseif(in_array($key, static::excluded_keys))
                {
                    $this->$key = $value == 'on' ? true : false;
                }
            }
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }
            $this->save();
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }

    public static function readingChecker($leaf_group_id)
    {
       $leaf_api = new LeafAPI();
       $houses         =    $leaf_api->get_houses(true,$leaf_group_id);
       $room_name_mapper = array();
       if(isset($houses['house']) && $houses = $houses['house']) {
            foreach ($houses as $house) {
                if (isset($house['house_rooms'])) {
                    foreach ($house['house_rooms'] as $room) {  
                                 $room_name_mapper[$room['id_house_room']] = $house['house_unit'].'-'.$room['house_room_name'];   
                        }
                    }
                }
        }

        $temp_readings =  MeterReading::withoutGlobalScopes()
                        ->where('leaf_group_id','=',$leaf_group_id)
                        //->whereBetween('current_date','=',date('Y-m-d', strtotime('now')))
                        ->whereBetween('current_date',['2022-04-01',date('Y-m-d', strtotime('now'))])
                        ->groupBy('meter_register_id')
                        ->distinct()
                        ->select('meter_register_id')
                        ->get()
                        ->toArray();

        $funtioning_meter_register_ids = array_column($temp_readings, 'meter_register_id');
        
        $meter_registers =  static::withoutGlobalScopes()
                        ->where('leaf_group_id','=',$leaf_group_id)
                        ->get();
        //dd($meter_registers);

        $result = array();
        foreach( $meter_registers as $row)
        {
            //dd($row);
            //echo $row['meter_register_id'].'<br>';
            if(!in_array($row['id'],$funtioning_meter_register_ids))
            {
                //echo $row['id'].' : '.$row['ip_address'].'<br>';
                $result[$room_name_mapper[$row['leaf_room_id']]] =  $row['ip_address'];
                //$result[$room_name_mapper[$row['leaf_room_id']]]['id'] = $row['id'];
                //echo $row['ip_address'].'<br>';
                //echo $room_name_mapper[$row['leaf_room_id']].'<br>';
            }else{
                //$result[$room_name_mapper[$row['leaf_room_id']]] =  $row['ip_address'];
            }
        }

        ksort($result);
        foreach($result as $room_name => $ip_address)
        {
            //dd($ip_address);
            //echo $ip_address.' : '. $room_name.'<br>';
            //echo $ip_address.'<br>';
                echo $room_name.'<br>';
        }


        dd($funtioning_meter_register_ids);
        
    }
}
