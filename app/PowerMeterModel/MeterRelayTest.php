<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use App\Company;
use App\LeafAPI;

use Illuminate\Database\Eloquent\Builder;

class MeterRelayTest extends ExtendModel
{
    protected $table = 'meter_relay_tests';
    public $timestamps = true;
    protected $listing_except_columns = ['created_by','updated_by','created_at','updated_at','leaf_group_id'];

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
    | Here to manage of data's
    |--------------------------------------------------------------------------
    |
    */

    public static function combobox($state_id=null)
    {
        return static::ofAvailable('status',true)
                                ->where('state_id','=',$state_id)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select MeterRelayTest...'), '');
    }

    const find_test_model_mapper = ['relay_controller_ip_address'  , 'unit_id'  ,  'reference_no'  ,  'meter_ip'  ,   'function_name'  ];
    public static function getTestModel($filter_data)
    {
        if(isset($filter_data['id'])){

            $return = static::find($filter_data['id']);
            return $return;
        }else{
            $return = static::ofAvailable('status',true);
            foreach(static::find_test_model_mapper as $filter_key)
            {//dd($input[$filter_key]);
                 $data = isset($filter_data[$filter_key]) ? $filter_data[$filter_key] : '';
                 $return = $return->where($filter_key,'=',$data);
            }

            return $return->first();
        }
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
                    'state_id'  =>  'required',
                    'code'      =>  'required|unique:meter_relay_tests,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'name'      =>  'required|unique:meter_relay_tests,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:meter_relay_tests,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:meter_relay_tests,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }


    

    const excluded_keys = ['status'];
    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'country_id' && !in_array($key , static::excluded_keys)) {
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

    public function init_meter_relay_test($input)
    {
        $leaf_api = new LeafAPI();

        $destination_url = 'http://localhost/leaf_webview/public/'; 
        $method_url = '';
        $url = $destination_url.$method_url;
        $response = $leaf_api->get($url , $input);;

        return $response;
    }

    public function init_status_change_request($input)
    {
        $leaf_api = new LeafAPI();
        if(!isset($input['url']->program_url))
        {
            return false;
        }

        if($input['url']->program_url == ''){return false;}


        $url = $input['program_url']; 
        $response = $leaf_api->get($url , $input);;

        return $response;
    }

    public static function initialize_reading_test($input)
    {
        DB::beginTransaction();
        try {
            //call method
            $model_x = new MeterRelayTest();
            $return = ( array ) json_decode($model_x->init_meter_relay_test($input), true);

            if(!$return['status_code']){return;}

                $model_get =  new MeterRelayTest();
                $model = $model_get->getTestModel($input);

            if(!isset($model['id']))
            {
                $model = new MeterRelayTest();
            }

            foreach ($input as $key => $value) {
                if ($key != '_token'  && !in_array($key , static::excluded_keys) && !in_array($key , static::reading_test_result_keys)) {
                    $model[$key] = (string) $value;
                }elseif(in_array($key, static::excluded_keys))
                {
                    $model[$key] = $value == 'on' ? true : false;
                }elseif(in_array($key, static::reading_test_result_keys))
                {
                
                    /*$new_temp = isset($model['meter_data']) ? json_decode($model['meter_data'])  : array() ;
                    array_push( $new_temp , $input['meter_data'] );*/
                    //$model[$key] = json_encode($new_temp);
                    $model[$key] = json_encode($value);
                }
            }
            if (!$model['id']) {
                $model['created_by']       =   Auth::id() ? Auth::id():0;
                $model['updated_by']       =   0;
                $model['leaf_group_id']    =   Company::get_group_id();
            } else {
                $model['updated_by']       =   Auth::id() ? Auth::id():0;
            }

            $temp_arr = isset($model['reading_data']) ? json_decode($model['reading_data'])  : array() ;
            array_push($temp_arr , (isset($return['listing']) ? $return['listing'] : array() ));
            $model['reading_data'] = json_encode($temp_arr) ;
            $model->save();

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }


    const remote_on_off_variabls =  [/*'on_value','off_value',*/'unit_id','reference_no','id','relay_controller_ip_address'];
    const remote_on_off_check = ['unit_id' => '' , 'reference_no' => ''];
    public static function init_meter_status_api($input)
    {
        $leaf_api = new LeafAPI();
        $destination_url = Company::getRelayApiUrl();
        //..  dd($destination_url);
        if($destination_url == false){ return false;}

        $meter_register_model = MeterRegister::find($input['meter_id']);
  
        //dd($meter_register_model);
        if(!isset($meter_register_model['id'])){return false;}

        foreach (static::remote_on_off_check as $key => $value)
        {
            if($meter_register_model[$key] == $value){
                return false;
            }
        }


        $trigger_variables = array();

        foreach(static::remote_on_off_variabls as $variable)
        {
            $trigger_variables[$variable] = $meter_register_model[$variable];
        }

        /*foreach(static::remote_on_off_variabls as $variable)
        {
            $input[$variable] = $meter_register_model[$variable];
        }

        foreach($destination_url as $key => $value)
        {
            $input[$key] = $value;
        }

        foreach($destination_url as $key => $value)
        {
            $input[$key] = $value;
        }*/

        if($trigger_variables['relay_controller_ip_address'] == null)
        {
            return false;
        }

        $meter_register_model_data = array();   
        $meter_register_necessary_fields = ['id','meter_id','ip_address','status','is_power_supply_on','modbus_address','on_value','off_value','unit_id','reference_no','relay_controller_ip_address','leaf_room_id'];
        foreach($meter_register_necessary_fields as $key)
        {
            $meter_register_model_data[$key] = $meter_register_model[$key];
        }
        $trigger_variables['power_status'] = array( ($input['is_power_supply_on'] == 1 ? 0 : 1) );
        $input['case_id'] = $meter_register_model->getSwithOnOffCaseId();
        $input['data']['meter_register_model'] = $meter_register_model_data;
        $input['data']['api_url'] = $destination_url;
        $input['data']['trigger_variables'] = $trigger_variables;
        $input['data'] = json_encode($input['data']);

        $response = $leaf_api->getPowerManagement($destination_url['url'] , $input);;

        return $response;
    }

    const api_excluded_keys = ['contract_no','meter_id'];
    const input_key_mappers = ['room_name' => 'room_name', 'meter_ip' => 'meter_ip'/*, 'is_power_supply_on' =>'is_power_supply_on'*/ , 'request_inputs' => 'request_inputs' , 'relay_switch_status' => 'is_power_supply_on' , 'meter_register_id' => 'meter_register_id'];
    const reading_test_result_keys = ['meter_data','ip_address'];
    public static function init_meter_status($input)
    {
        DB::beginTransaction();
        try {
            //call method
            $return = static::init_meter_status_api($input);
            //$return = ( array ) json_decode($model_x->init_meter_relay_test($input), true);
           // echo 'Get return <br>';
            $return = json_decode($return , true);
            $data = json_decode($return['data'],true);
  
            if(!$return['status_code']){return;}

                $model_get =  new MeterRelayTest();
                $model = $model_get->getTestModel($input);

            if(!isset($model['id']))
            {
                $model = new MeterRelayTest();
            }

            $meter_register_data = json_decode($data['input_data']['meter_register_model'],true);
            $meter_register_id = isset($meter_register_data['id']) ? $meter_register_data['id'] : 0;
            $request_inputs = json_decode($data['input_data']['trigger_variables'],true);
            $meter_ip = isset($meter_register_data['ip_address']) ? $meter_register_data['ip_address'] : 0;
            $leaf_group_id = isset($meter_register_data['leaf_group_id']) ? $meter_register_data['leaf_group_id'] : 0 ;
            $room_name = LeafAPI::get_room_name_by_leaf_room_id($meter_register_data['leaf_room_id']);
            $is_power_supply_on = isset($trigger_variables['power_status'][0]) ? ( $trigger_variables['power_status'][0] == 0 ? 1 : 0 ) : 0;

            foreach(static::input_key_mappers as $key => $variable_key)
            {
                $model[$key] = isset($$variable_key)  ? ( is_array($$variable_key) ? json_encode($$variable_key) : $$variable_key )  : '';
            }

            foreach($request_inputs as $key => $value)
            {
                if($key == 'power_status' || $key == 'id'  ){continue;}
                $model[$key] = is_array($value) ? json_encode($value) : $value;
            }

            foreach ($input as $key => $value) {

                if(in_array($key , static::api_excluded_keys))
                {
                    continue;
                }
                if ($key != '_token'  && !in_array($key , static::excluded_keys) && !in_array($key , static::reading_test_result_keys)) {
                    $model[$key] = (string) $value;
                }elseif(in_array($key, static::excluded_keys))
                {
                    $model[$key] = $value == 'on' ? true : false;
                }elseif(in_array($key, static::reading_test_result_keys))
                {
                    if($key == 'ip_address'){ $key = 'meter_ip';}
                    $model[$key] = json_encode($value);
                }
            }
            if (!$model['id']) {
                $model['created_by']       =   Auth::id() ? Auth::id():0;
                $model['updated_by']       =   0;
                $model['leaf_group_id']    =   Company::get_group_id();
            } else {
                $model['updated_by']       =   Auth::id() ? Auth::id():0;
            }

            $temp_arr = isset($model['reading_data']) ? json_decode($model['reading_data'])  : array() ;
            array_push($temp_arr , (isset($data['listing']) ? $data['listing'] : array() ));
            $model['reading_data'] = json_encode($temp_arr) ;
           ////dd($model);
            $model['status'] = 1;
            $model['leaf_group_id'] = 519;
            $model['is_power_supply_on'] = $is_power_supply_on;
            $model->save();

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }


}



        
