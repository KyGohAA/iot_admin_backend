<?php

namespace App;

use DB;
use Auth;
use Schema;
use Request;
use Validator;
use Image;
use Cookie;
use Illuminate\Database\Eloquent\Builder;

class Company extends ExtendModel
{
    protected $table = 'companies';
    public $timestamps = true;
    protected $listing_except_columns = ['city_id','city_name','state_id','state_name','country_id',
                                        'website','country_name','postcode','address','created_by','updated_by',
                                        'created_at','updated_at','leaf_group_id','accounting_ncl_id','accounting_winz_id'];
    protected $guarded = [];
    
    const cookie_label = 'group_id';

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */
    public function getTransactionPercentAttribute($value)
    {
        return $value*100;
    }

    public function getRawTransactionPercentAttribute()
    {
        return $this->transaction_charge;
    }


    public static function get_system_live_date($leaf_group_id)
    {
        if ($model = Company::where('leaf_group_id','=',$leaf_group_id)->first()) {
            return $model->system_live_date;
        }
        return null;
    }

    public function setTransactionPercentAttribute($value)
    {
        return $this->attributes['transaction_percent'] = number_format($value/100, 4, '.', '');
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

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id');
    }

    public function payment_term()
    {
        return $this->belongsTo('App\PaymentTerm', 'payment_term_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }

    public function getCurrenncyCode()
    {
        return isset($this->currency->code) ? $this->currency->code : 'RM';
    }


    public function backend_data()
    {
        return $this->hasOne('App\BackendData', 'company_id');
    }

    public function getPMOperationalSetting()
    {
        $temp  = json_decode($this->power_meter_operational_setting);
        return $temp;
    }

    public static function getByLeafGroupId($searchKey)
    {
        return static::withoutGlobalScopes()
                        ->whereIn('leaf_group_id',$searchKey)
                        ->groupBy('leaf_group_id')
                        ->distinct()
                        ->get();
    }
    public function getPowerMeterMobileAppMsg()
    {
        $return = array();
        $backend_data_model = $this->backend_data;
        //dd($backend_data_model);
        if(isset($backend_data_model['power_meter_mobile_app_msg']))
        {
            $temp = (array) json_decode($backend_data_model['power_meter_mobile_app_msg']);
            foreach ($temp as $key => $value)
            {
                $return[$key] = (array) $value;
                foreach($return[$key] as $language => $value)
                {
                    $return[$key][$language] = $value;
                    //$return[$key][$language] = ['title' => $value , 'detail' => $value ] ;
                }
            }

            return $return ;

        }else{

            return false;
        }
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
            $builder->where('leaf_group_id', '=', static::get_group_id());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's listing
    |--------------------------------------------------------------------------
    |
    */

    public static function getTopUpCombobox($company_id)
    {    
        $default_topup = 10;
        $combobox = array();
        $company_model = static::find($company_id);
        $temp_arr = explode(',', $company_model->power_meter_top_up_predefined_value);
        if(count($temp_arr) == 0)
        {
            array_push($combobox , $default_topup );
        }else{
            foreach ($temp_arr as $key => $value)
            {
                /*$temp[$value] = $value;
                array_push($combobox , $temp);*/
                $combobox[$value] = $value;
                unset($temp);
            }
        }
        

        return $combobox;
    }

    public static function combobox()
    {
        return static::ofAvailable('status',true)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select company...'), '');
    }

    public static function getRelayApiUrl()
    {
        $relay_url = isset(json_decode(Company::get_own_company()->power_meter_operational_setting)->remote_relay_api_url) ? json_decode(Company::get_own_company()->power_meter_operational_setting)->remote_relay_api_url : '';
        $relay_program_url = isset(json_decode(Company::get_own_company()->power_meter_operational_setting)->remote_relay_program_api_url) ? json_decode(Company::get_own_company()->power_meter_operational_setting)->remote_relay_program_api_url : '';

        if( $relay_url == '' ){
              return false;
        }

       return ['url' => $relay_url , 'program_url' => $relay_program_url];

    }
    


    public function get_address()
    {
        $string = null;
        $city = $this->display_relationed('city','name');
        $state = $this->display_relationed('state','name');
        $country = $this->display_relationed('country','name');
        $string .= ($city ? (', '.$city):null);
        $string .= ($state ? (', '.$state):null);
        $string .= ($country ? (', '.$country):null);
        return rtrim($this->address, ',').', '.$this->postcode.$string;
    }

    public static function due_date_period()
    {
        if ($model = Company::where('leaf_group_id','=',static::get_group_id())->first()) {
            return $model->due_date_duration;
        }
        return 0;
    }

    public static function system_live_date()
    {
        if ($model = Company::where('leaf_group_id','=',static::get_group_id())->first()) {
           return $model->system_live_date;
        }
        return 0;
    }

    public static function get_is_inclusive()
    {
        if ($model = self::where('leaf_group_id','=',static::get_group_id())->first()) {
            return $model->is_inclusive;
        }
        return false;
    }

    public static function get_gst_percent()
    {
        return '0.06';
    }

    public static function get_transaction_charge()
    {
        if ($model = self::where('leaf_group_id','=',static::get_group_id())->first()) {
            return $model->raw_transaction_percent;
        }
        return false;
    }

    public static function get_monthly_cut_off_day_by_leaf_group_id($leaf_group_id)
    {
        if ($model = self::where('leaf_group_id','=',$leaf_group_id)->first()) {
            return $model->monthly_cut_off_day;
        }
        return false;
    }

    public static function get_model_by_leaf_group_id($leaf_group_id)
    {
        return static::withoutGlobalScopes()
                    ->where('leaf_group_id','=',$leaf_group_id)->first();
    }

    public static function get_company_logo($leaf_group_id=null)
    {
        $model = static::get_model_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        return $model['logo_photo_path'] == '' ? '_app_icon.png' :  $model['logo_photo_path']  ;
    }

    public static function get_system_name($leaf_group_id=null)
    {
        $model = static::get_model_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        return $model['system_name'] == '' ? 'LeafExtend' :  $model['system_name']  ;
    }

    public static function get_own_company($leaf_group_id=null)
    {
        $leaf_group_id = Setting::get_leaf_group_id($leaf_group_id);
        return static::get_model_by_leaf_group_id($leaf_group_id);
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

    public static function get_ncl_id_by_leaf_group_id($leaf_group_id=null)
    {
        $model = Company::find(Setting::get_leaf_group_id($leaf_group_id));
        $integrated_accounting_sytem = json_decode($model['integrated_accounting_sytem']);
        if(count($integrated_accounting_sytem) > 0){
            if(in_array('NCL', $integrated_accounting_sytem) == true){
                return $model['accounting_ncl_id'] != '' ? $model['accounting_ncl_id'] : NclAPI::company_id;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function get_payment_term_model($leaf_group_id=null){

        $model = static::get_model_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        return isset($model['id']) ? PaymentTerm::find($model['payment_term_id']) : false;
    }

    public static function get_currency_model($leaf_group_id=null){

        $model = static::get_model_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        return isset($model['id']) ? Currency::find($model['currency_id']) : false;
    }

    public static function get_bank_account($leaf_group_id=null){

        $model = static::get_model_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        return isset($model['bank_account']) ? $model['bank_account'] : false;
    }

    

    public static function is_allow_to_access_module($module,$leaf_group_id=null)
    {
        $model = static::get_model_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        $module_array = json_decode($model['selected_module']);
        
        if(isset($module_array)){
            if(in_array($module, $module_array)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        

    }

    public function validate_form($input)
    {
        if (!isset($input['min_credit'])) {
            $rules = [
                        'name'          =>  'required',
                        ];

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return $validator;
            }
        }
        return false;
    }

    const to_json_mappers = ['power_meter_mailbox_setting','power_meter_operational_setting','top_up_amount_range'];
    const excluded_keys = ['top_up_amount_range','is_on_accounting_api'];
    const backend_data_keys = ['power_meter_payment_success_email','power_meter_power_supply_restore_email','power_meter_mobile_app_msg','power_meter_low_credit_reminder','power_meter_payment_reminder_email','power_meter_turn_off_meter_email'];
    const radio_button_keys = ['is_min_credit' , 'is_prepaid' , 'is_inclusive' , 'is_transaction_charge' , 'is_mobile_app_allow_payment' , 'is_direct_allow_to_payment' , 'is_pay_by_accumulate' , 'is_mobile_app_maintenance', 'is_top_up_with_predefined_value' ];
    const explode_by_comma_keys = ['power_meter_top_up_predefined_value'];
  


    public function save_form($input)
    {
        DB::beginTransaction();
        try {

//dd($input);
            $backend_data_model = $this->backend_data;
            if(!isset($backend_data_model['id']))
            {
                    $backend_data_model = new BackendData();
                    $backend_data_model['company_id'] = $this->id;
                    $backend_data_model['leaf_group_id'] = $this->leaf_group_id;
            }
           

            
            
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'logo_photo_path' && $key != 'system_live_date' && $key != 'selected_module' && $key != 'integrated_accounting_sytem' && $key != 'tester_id'  && !in_array( $key , static::to_json_mappers) && !in_array( $key , static::excluded_keys)  && !in_array( $key , static::backend_data_keys)  && !in_array( $key , static::radio_button_keys) && !in_array( $key , static::radio_button_keys) ) {

                    $this->$key = (string) $value;

                }else if(in_array( $key , static::backend_data_keys)) {

                    $backend_data_model[$key] = json_encode($value); 

                }else if(in_array( $key , static::explode_by_comma_keys)) {

                    $temp_array = explode(',', $value);
                    $this->$key = json_encode($temp_array); 
                    unset($temp_array);

                }else if($key == 'selected_module') {
                  
                    $temp_arr = array();
                    foreach ($value as $index => $selected_module) {            
                        array_push($temp_arr, $selected_module);
                    }
                    $this->$key = json_encode($temp_arr);

                }else if($key == 'integrated_accounting_sytem') {
                  
                    $temp_arr = array();
                    foreach ($value as $index => $integrated_accounting_sytem) {            
                        array_push($temp_arr, $integrated_accounting_sytem);
                    }
                    $this->$key = json_encode($temp_arr);

                }else if($key == 'system_live_date'){
                    $this->$key = date('Y-m-d', strtotime($value));
                }else if(in_array( $key , static::radio_button_keys)){


                    $this->$key = $value =='on' || $value == 1 ? true : false;

                    if($key =='is_top_up_with_predefined_value')
                    {
                        //echo $value;
                        //dd($this);
                        
                    }

                }else if($key == 'logo_photo_path'){

                    if (Request::hasFile('logo_photo_path')) {

                        if (Request::file('logo_photo_path')) {
                            $file = Request::file('logo_photo_path');
                            $destination    =   SETTING::SETIA_PRODUCT_DEFAULT_PHOTO;
                            $i = 1;
                            $img = Image::make($file->getRealPath());
                            $extension      =   'jpeg'; //$file->getClientOriginalExtension();
                            $file->move(public_path().'/'.$destination,$input['logo_photo_path']->getClientOriginalName().'-'.date('d-m-Y-H-i-s').'-'.$i.'.'.$extension);

                            //$this->article_cover_photo  = Image::make($file)->resize(200, 200)->save();
                            //save into directory and update path to database

                            if($this->logo_photo_path){

                                @unlink(public_path($this->logo_photo_path));
                        
                            }

                            $this->logo_photo_path = $destination.$input['logo_photo_path']->getClientOriginalName().'-'.date('d-m-Y-H-i-s').'-'.$i.'.'.$extension;
                            $img->save($destination.$input['logo_photo_path']->getClientOriginalName().'-'.date('d-m-Y-H-i-s').'-'.'_150x85_'.'.'.$extension);
                        }
                     }
                }else if(in_array( $key , static::to_json_mappers))
                {
                    if($key == 'power_meter_operational_setting') {
                        
                        $min_max_arr = explode(';',$value['top_up_amount_range']);
                        if(isset($min_max_arr[0]) && isset($min_max_arr[1]))
                        {   
                            $value['top_up_min_amount'] = $min_max_arr[0];
                            $value['top_up_max_amount'] = $min_max_arr[1];
                        }
                        
                    }

                    //$temp_array = isset($temp_array) ? $temp_array : array();
                    $this->$key = json_encode($value);
                }
            }

            $this->payment_term_id = $this->payment_term_id == '' ? 0  :$this->payment_term_id;
            $this->currency_id = $this->currency_id == '' ? 0  :$this->currency_id;
            $this->city_id = $this->city_id == '' ? 0  :$this->city_id;
            $this->state_id = $this->state_id == '' ? 0  :$this->state_id;
            $this->country_id = $this->country_id == '' ? 0  :$this->country_id;
            $this->membership_payment_allow_day = date('Y-m-d', strtotime('now'));

            $this->min_credit           =   (isset($input['is_min_credit']) ? $input['min_credit']:0);
            $this->transaction_percent  =   (isset($input['is_transaction_charge']) ? $input['transaction_percent']:0);
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   static::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }

            $backend_data_model->save();
            $this->save();
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of custom query & save form
    |--------------------------------------------------------------------------
    |
    */

    public function self_profile()
    {
        if ($model = static::where('leaf_group_id','=',static::get_group_id())->first()) {
           
            return $model;
        }
        return [];
    }

    public static function setGroupId($value)
    {
        $_COOKIE[self::cookie_label] = $value; 
        setcookie(self::cookie_label, $value, time() + (86400 * 30)*10, "/");
        //setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

        Cookie::queue(self::cookie_label, $value);
    }

    public function set_group_id($value)
    {
        $_COOKIE[self::cookie_label] = $value; 
        setcookie(self::cookie_label, $value, time() + (86400 * 30)*10, "/");
        //setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

        Cookie::queue(self::cookie_label, $value);
    }

    public static function get_group_id()
    {
        //return 519;
        $leaf_group_id = Cookie::get(self::cookie_label);
        if(Cookie::get(self::cookie_label) == null)
        {
            if(isset($_COOKIE[self::cookie_label])){
                 $leaf_group_id = $_COOKIE[self::cookie_label];
            }

        }
       
        return $leaf_group_id;
    }
}
