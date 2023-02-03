<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use DB;
use Auth;
use Schema;
use Validator;
use App\Company;
use App\PaymentTestingAllowList;
use App\Setting;
use App\UserAssign;
use App\PowerMeterModel\CustomerPowerUsageSummary;


class User extends Authenticatable
{
    private $extendModel;
    protected $table = 'users';
    public $timestamps = true;
    protected $listing_except_columns = ['leaf_id_user','store_id','leaf_id_group','remember_token','created_by','updated_by','created_at','updated_at','is_admin','module_access','power_meter_start_charging_date','is_super_admin','id_house_member','power_mangement_start_charging_date','customer_power_usage_summary_id','language_id','photo','month_usages'];

    protected $guarded = [];

    use Notifiable;

    public function __construct()
    {
        $this->extendModel = new ExtendModel();
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

    public function getUserGroupIdAttribute($value)
    {
        return UserAssign::where('id','=',$this->id)
                            ->where('leaf_group_id','=',Company::get_group_id())
                            ->pluck('user_group_id');
    }

    
    public function getCustomerPowerUsageSummaryId()
    {
        $leaf_group_id = Company::get_group_id() == 0  ? 0 : Company::get_group_id();
        $temp = (array) json_decode($this->customer_power_usage_summary_id);

        return isset($temp[$leaf_group_id]) ? $temp[$leaf_group_id] : 0;
    }

    const remove_cpus_model_mappers = ['customer_id' => 0 , 'customer_name' => '-' , 'leaf_id_user' => 0 , 'id_house_member' => 0 ];
    public function removeCurrentRoomFromCustomerSummaryData()
    {
      
         $cpus_model = CustomerPowerUsageSummary::find($this->getCustomerPowerUsageSummaryId());
        if(isset($cpus_model['id']))
        {
            foreach (static::remove_cpus_model_mappers as $key => $value)
            {
                $cpus_model[$key] = $value;
            }

            $cpus_model->save();    
        }
        
        $leaf_group_id = Company::get_group_id() == 0  ? 0 : Company::get_group_id();
        $temp = (array) json_decode($this->customer_power_usage_summary_id);

        $is_exist = isset($temp[$leaf_group_id]) ? 1: 0;
        if($is_exist)
        {
            unset($temp[$leaf_group_id]);
        }

        $this->customer_power_usage_summary_id = json_encode($temp);
        $this->save();

    }

    

    public function saveOrUpdateCustomerPowerUsageSummaryId($customer_power_usage_summary_id)
    {
        DB::beginTransaction();
        try {

                        $repeat_item = 0 ;
                        $is_exist = false;
                        $leaf_group_id = Company::get_group_id() == 0  ? 0 : Company::get_group_id();

                        $temp = (array) json_decode($this->customer_power_usage_summary_id);

                        if(is_array($temp))
                        {
                            if(count($temp) > 0)
                            {
                                foreach($temp as $temp_leaf_group_id => $group_customer_power_usage_summary_id)
                                {
                                    if($leaf_group_id == $temp_leaf_group_id){
                                        $is_exist == true;
                                        $repeat_item ++;
                                    }
                                }
                            }
                        }

                        if($is_exist == false)
                        {
                            $temp[$leaf_group_id] = $customer_power_usage_summary_id;
                        }else{
                            $temp[$leaf_group_id][$repeat_item] = $customer_power_usage_summary_id;
                        }
                        //echo 'To save temp :'.json_encode($temp)."<br>";
                        //echo 'To befor_save :'.json_encode($this)."<br>";
                        $this->customer_power_usage_summary_id = json_encode($temp);

                        $this->save();
                        //echo 'after save :'.json_encode($this)."<br>";

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();

        return $this;

    }

    public static function getCustomerPowerUsageSummaryByLeafIdUser($leaf_id_user)
    {
        $user = User::where('leaf_id_user','=',$leaf_id_user)
                    ->first();
        return  CustomerPowerUsageSummary::find($user['customer_power_usage_summary_id']);
    }

    public static function getUserByLeafIdUser($leaf_id_user)
    {
        return User::where('leaf_id_user','=',$leaf_id_user)
                    ->first();
    }

    public static function get_model_by_leaf_id_user($leaf_id_user)
    {  
        return static::where('leaf_id_user','=',$leaf_id_user)
                ->first();
    }

    public static function get_model_by_id_house_member($id_house_member)
    {  
        return static::where('id_house_member','=',$id_house_member)
                ->first();
    }

    public static function get_profile_pic_by_id($user_id)
    {
        $model = User::where('id','=',$user_id)
                    ->first();

        return $model['photo'];
    }

    public static function get_user_by_email($email)
    {
        $model = User::where('email','=',$email)
                    ->first();

        return $model;
    }

    public function getGroupUserRoom($leaf_group_id=null,$is_all = false)
    {
        $leaf_group_id = isset($leaf_group_id) ? $leaf_group_id : Company::get_group_id() ;
        $user_room = json_decode($this->user_room);

        if(is_array($user_room))
        {
            if(count($user_room) > 0)
            {
                if($is_all == true)
                {
                    return $user_room;
                }else{

                    $return = array();

                    foreach($user_room as $key => $room)
                    {
                        if($key == $leaf_group_id)
                        {
                            array($return , $room);
                        }
                    }

                    return $return;
                }
                
            }

        }

        return false;
    }

    //Sunway medical center sp. tailor
    public static function get_date_statarted_temp_by_id_house_member($id_house_member){
        
        $convert_staff_id_arr_1 = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123,16265,16179,16196);
        $leaf_group_id = Setting::get_leaf_group_id();
        $room = LeafAPI::get_room_by_id_house_member($id_house_member);
        $user = static::get_model_by_id_house_member($id_house_member);
        $is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user['leaf_id_user'],$leaf_group_id);

        if(in_array($user['leaf_id_user'], $convert_staff_id_arr_1)){

            $date_started = '2019-04-01';

        }else if($is_allow_to_pay == false){
          
            $date_started = isset($room['member']['house_room_member_start_date']) ? $room['member']['house_room_member_start_date'] : '';
            //echo $date_started < Company::get_system_live_date($leaf_group_id);
            //dd($date_started < Company::get_system_live_date($leaf_group_id));
            if(($date_started < Company::get_system_live_date($leaf_group_id)) == true){
                $date_started = Company::get_system_live_date($leaf_group_id);
            }
                
            if($date_started == ""){
                $date_started = Company::get_system_live_date($leaf_group_id);
            }
             
        }else{
            $date_started =  isset($room['member']['house_room_member_start_date']) ? $room['member']['house_room_member_start_date'] : '';
            if($date_started == ""){
                $date_started = '2019-03-01';
            }
        }

        return $date_started;

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

    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }

    public function user_group()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }

    public function customer_power_usage_summary()
    {
        return $this->belongsTo('App\PowerMeterModel\CustomerPowerUsageSummary', 'customer_power_usage_summary_id');
    }
    

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
       /* parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->leftJoin('user_assigns','user_assigns.id','=','users.assign_id')
                        ->where('leaf_group_id', '=', Company::get_group_id());
        });*/
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
        $listing = $this->table_cols();
        $listing[0] = 'id';
        return $query->select(array_diff($listing, $this->listing_except_columns));
        // return $query->select(['photo','fullname','email','status','created_by','updated_by','user_id']);
    }

    public function display_relationed($parent, $col)
    {
        $parent = str_replace('_id', '', $parent);
        return ($this->$parent ? $this->$parent->$col:'');
    }

    public function display_status_string($col)
    {
        return ($this->$col ? Language::trans('Enabled'):Language::trans('Disabled'));
    }

    public function profile_jpg()
    {
        $leaf_api = new LeafAPI();
        $user = $leaf_api->get_user_by_email($this->email);
        if (isset($user['user_photo'])) {
            return Setting::photo_place($user['user_photo']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of validation & save form
    |--------------------------------------------------------------------------
    |
    */
    public function create_user_by_leaf_id_user($leaf_member_detail)
    {

    }

    public function get_or_create_user_account($data)
    {
        $data['leaf_id_user'] = isset($data['leaf_id_user']) ? $data['leaf_id_user'] : (isset($data['id_user']) ? $data['id_user'] : 0);
        if (!$model = static::where('leaf_id_user','=',$data['leaf_id_user'])->first()) {
            $model = new static();
            $input['photo']         =   isset($data['user_photo']) ?  $data['user_photo'] : '';
            $input['fullname']      =   isset($data['user_fullname']) ? $data['user_fullname'] : '' ;
            $input['email']         =   isset($data['user_email']) ?  $data['user_email'] : '';
            $input['status']        =   true;
            $input['leaf_id_user']  =   isset($data['leaf_id_user']) ?  $data['leaf_id_user'] : '';
            //company setting
            $user_group_id          =   isset($data['user_group_id']) ? $data['user_group_id']:0;

            $model->save_form($input);
            $model->create_group($model->id, $user_group_id);
        }
        return $model;
    }

    public function remove_group($id)
    {
        UserAssign::where('id','=',$user_id)->where('leaf_group_id','=',Company::get_group_id())->delete();
    }

    public function create_group($user_id, $group_id)
    {
        DB::beginTransaction();
        try {

            $model = UserAssign::get_model_by_user_and_user_group_id($user_id,$group_id) !== null  ? UserAssign::get_model_by_user_and_user_group_id($user_id,$group_id) : new UserAssign();
            $model->id         =   $user_id;
            $model->leaf_group_id   =   Company::get_group_id();
            $model->user_group_id   =   $group_id;
            $model->save();
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }

    public function validate_form($input)
    {
        $rules = [];

        if (!$this->id) {
            $rules['email'] =   'required';
        }

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public static function combobox_email_vs_email()
    {
        return static::orderBy('email','asc')
                        ->pluck('email','email');
    }


    public static function repositionArrayElement(array &$array, $key, int $order): void
    {
        if(($a = array_search($key, array_keys($array))) === false){
            throw new \Exception("The {$key} cannot be found in the given array.");
        }
        $p1 = array_splice($array, $a, 1);
        $p2 = array_splice($array, 0, $order);
        $array = array_merge($p2, $p1, $array);
    }

    public static function user_combobox($user_id=0)
    {
        $return =  static::orderBy('fullname','asc');
        if($user_id !=0)
        {
             $return = $return->where('id','!=',$user_id);
        } 

        $return = $return->pluck('fullname','id');

        if($user_id !=0)
        {
            $return_2 = static::orderBy('fullname','asc')
                                ->where('id','=',$user_id)
                                ->pluck('fullname','id');

            $return = array_merge($return->toArray(),$return_2->toArray());
            //dd($return);
           // User::repositionArrayElement($return, count($return)  -1 , 0 );
        }
        
        return  $return;
    }

    public static function user_assign_combobox($user_assign_id=0)
    {
        $assigned_user_id = array_column(UserAssign::getByUserGroupIdAndNotUserAssignId(Company::get_group_id(),$user_assign_id)->toArray(),'user_id');
        //dd($assigned_user_id);
        $return = static::orderBy('fullname','asc');
        if(count($assigned_user_id) > 0)
        {
             $return = $return->whereNotIn('id',$assigned_user_id);
        }
       

        $return = $return->pluck('fullname','id');


        return  $return;
    }


    public static function combobox()
    {
        $leaf_api = new LeafAPI();
        $listing = $leaf_api->get_member_list();

        $datas = [];
        $datas['']   =   Language::trans('Please select user...');
        if ($listing['status_code'] == 1) {
            foreach ($listing['member'] as $row) {
                $datas[$row['user_name']] = $row['user_name'];
            }
        }

        return $datas;
    }

    public function get_user_by_range($from, $to, $col)
    {
        $leaf_api = new LeafAPI();
        $listing = $leaf_api->get_member_list();
        $datas = [];
        $insert=false;
        // process if leaf api return status code = 1
        if ($listing['status_code'] == 1) {
            // get users listing in leaf api
            foreach ($listing['member'] as $row) {
                if ($row['user_name'] == $from) {
                    $insert=true;
                }
                if ($insert) {
                    $datas[] = $row[$col];
                }
                if ($row['user_name'] == $to) {
                    $insert=false;
                }
            }
        }
        return $datas;
    }

    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'status_code' && $key != 'user_group_id' && $key != 'power_mangement_start_charging_date') {
                    $this->$key = (string) $value;
                }else if($key == 'power_mangement_start_charging_date'){
                    $this->$key = date('Y-m-d H:m' , strtotime($value));
                }
            }
            if (!$this->id) {
                $this->created_by = Auth::id() ? Auth::id():0;
                $this->updated_by = 0;
            } else {
                $this->updated_by = Auth::id() ? Auth::id():0;
            }

            $this->save();

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }
}

