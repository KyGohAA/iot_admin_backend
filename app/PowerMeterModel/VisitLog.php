<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use App\Setting;
use App\Company;

use App\Room;
use App\House;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class VisitLog extends ExtendModel
{
    protected $table = 'visit_logs';
    public $timestamps = true;
    protected $listing_except_columns = ['leaf_group_id','created_at', 'updated_at','is_check_in','is_check_out','status' ];
    const list_table_columns = ['id','leaf_id_user','house_name','room_name','check_in_at','check_out_at' , 'duration'];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */


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
                                ->pluck('device_name','id')
                                ->prepend(Language::trans('Please select location...'), '');
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

    public static function getUserCheckInOutHistory($leaf_id_user)
    {
        return static::where('leaf_id_user', '=' , $leaf_id_user)->get();
    }

    public static function getUserLastVisitLog($leaf_id_user)
    {
        return static::where('leaf_id_user', '=' , $leaf_id_user)
                ->where('is_check_in', '=' , 1)
                ->where('is_check_out', '=' , 0)
                ->orderBy('created_at' , 'desc')
                ->first();
    }

    public static function getUserByLeafIdUser($leaf_id_user)
    {
        return User::where('leaf_id_user', '=' , $leaf_id_user)->first();
    }
    
    
    public static function getUserTotalCheckInOut($leaf_id_user)
    {
         $return = ['total_check_in'=>0,'total_check_out'=>0,'total_records'=>0];
         $visit_log_listing = DB::select('SELECT * FROM `visit_logs` WHERE `leaf_id_user` = ? ORDER BY `created_at` DESC', [$leaf_id_user]);
         foreach ($visit_log_listing as $visit_log)
         {  $visit_log = (array) $visit_log;
            if($visit_log['is_check_in'])
            {
                $return['total_check_in']++;
            }

            if($visit_log['is_check_out'])
            {
                $return['total_check_out']++;
            }
            
         }
         
         $return['total_records'] = count($visit_log_listing);
         return $return;
    }
    /*
    |--------------------------------------------------------------------------
    | Here to manage of validation & save form
    |--------------------------------------------------------------------------
    |
    */

    //leaf_id_user leaf_group_id leaf_room_id leaf_house_id
    const check_in_variables = ['device_info'=>'check_in_device_info','location_info'=>'check_in_location_info' , 'ip_address'=>'check_in_ip_address'];
    const check_out_variables = ['device_info'=>'check_out_device_info','location_info'=>'check_out_location_info' , 'ip_address'=>'check_out_ip_address'];
    const check_in_now_variables = ['created_at','check_in_at'];
    const check_out_now_variables = ['updated_at','check_out_at'];
    const create_default_value_mappers = ['status'=>1,'is_check_in' =>true];
    const excluded_variables = ['visit_type','location_info','device_info','ip_address','visit_log_id'];
    public function save_visit_log($input , $visit_type=null)
    {
//dd($input);
        DB::beginTransaction();
        try {

            if(!$this->id)
            {
                foreach ($input as $key => $value) {

                    if ($key != '_token'  && !in_array($key , static::check_in_now_variables)  && !in_array($key , static::check_out_now_variables)  && !in_array($key , static::excluded_variables) ) {
             
                        $this->$key = $value;
                       
                    }
                }
            }
            

            if($visit_type == 'check_in')
            {
                    foreach(static::check_in_now_variables as $key){
                        $this->$key =  date('Y-m-d h:m:s', strtotime('now'));
                    }

                    foreach(static::check_in_variables as $input_key =>$model_key){
                         $this->$model_key = json_encode($input[$input_key]);
                    }
                   

            }else if($visit_type == 'check_out')
            {
                    $this->is_check_out = true;
                    foreach(static::check_out_now_variables as $key){
                        $this->$key = date('Y-m-d h:m:s', strtotime('now'));
                    }

                    foreach(static::check_out_variables as $input_key =>$model_key){
                         $this->$model_key = json_encode($input[$input_key]);
                    }
                   
            }
            
            if (!$this->id) {

                foreach(static::create_default_value_mappers as $key => $value)
                {
                    $this->$key = $value;
                }

                $this->leaf_group_id    =   Company::get_group_id();

            } else {

            }


            $room = Room::findByLeafRoomId($this->leaf_room_id);
            $house = House::findByLeafHouseId($this->leaf_house_id);
 
            $this->house_name = $house['house_subgroup'].' Unit : '.$house['house_unit'];
            $this->room_name = 'Room '.$room['house_room_name'].' ( '.$room['house_room_type'].' room )';
     
            $this->save();

        } catch (Exception $e) {

            throw $e;
            DB::rollBack();
        }
        DB::commit();
        return $this;

    }
  
    public function validate_form($input)
    {
        $rules = [
                    'ip_address'      =>  'required|unique:visit_logs,ip_address,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'device_name'      =>  'required|unique:visit_logs,device_name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['ip_address'] = 'required|unique:visit_logs,ip_address,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['device_name'] = 'required|unique:visit_logs,device_name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

    	$validator = Validator::make($input, $rules);

    	if ($validator->fails()) {
    		return $validator;
    	}
    	return false;
    }

    public function save_form($input)
    {
    	DB::beginTransaction();
    	try {
            foreach ($input as $key => $value) {
                if ($key != '_token') {
                    $this->$key = (string) $value;
                }
            }
            if (!$this->id) {
                $this->created_at       =  date('Y-m-d h:m:s', strtotime('now'));
                $this->leaf_group_id    =   Company::get_group_id();
            } else {

            }
           
    		$this->save();
    	} catch (Exception $e) {
    		throw $e;
    		DB::rollBack();
    	}
    	DB::commit();
    }
}
