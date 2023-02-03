<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use DateTime;
use App\User;
use App\Company;
use App\LeafAPI;
use App\NclAPI;
use App\Customer;
use App\Currency;
use App\Setting;
use App\PaymentTestingAllowList;


use Illuminate\Database\Eloquent\Builder;

class PowerMeterAccount extends ExtendModel
{
    protected $table = 'customer_power_usage_summaries';
    public $timestamps = true;
    protected $listing_except_columns = ['created_at','updated_at','leaf_group_id','ncl_id'];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */
    
    public function getCreditLimitAttribute($value)
    {
        return $this->getDouble($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function customer_group()
    {
        return $this->belongsTo('App\CustomerGroup', 'customer_group_id');
    }

    public function payment_term()
    {
        return $this->belongsTo('App\PaymentTerm', 'payment_term_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
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

    public function delivery_country()
    {
        return $this->belongsTo('App\Country', 'delivery_country_id');
    }

    public function delivery_state()
    {
        return $this->belongsTo('App\State', 'delivery_state_id');
    }

    public function delivery_city()
    {
        return $this->belongsTo('App\City', 'delivery_city_id');
    }

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

    

    public static function total_count()
    {
        return static::ofAvailable('status',true)->count();
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
                    'code'      =>  'required|unique:customer_power_usage_summaries,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'name'      =>  'required|unique:customer_power_usage_summaries,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:customer_power_usage_summaries,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:customer_power_usage_summaries,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public static function get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,$leaf_group_id){

        $return = static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                        ->where('current_credit_amount' , '>=' , $min_credit)
                        ->where('total_outstanding_amount' , '<=' , 0)
                        ->get();

        return $return;
    }

    public static function get_customer_with_outstanding_by_leaf_group_id($leaf_group_id){

        $return = static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                        ->where('total_outstanding_amount' , '>' , 0)
                        ->get();

        return $return;
    }

    public static function get_model_by_date_range_and_leaf_id_house_member($date_range , $leaf_id_house_member)
    {
        $return = static::where('check_in_date' , '=' , $date_range['date_started'])
                        ->where('check_out_date' , '=' , $date_range['date_ended'])
                        ->where('total_outstanding_amount' , '=' , $leaf_id_house_member)
                        ->first();

        return $return;
    }

    const room_type_stay = 'Stay';

    public static function get_leaf_user_account_data($leaf_id_user , $leaf_room_id)
    {  
        $return = static::where('leaf_room_id','=', $leaf_room_id)
                        ->where('type' , '=' , static::room_type_stay )
                        ->first();
                      
        if(isset($return['id'])){
            $tenants = json_decode($return['leaf_id_user']);
            foreach($tenants as $key => $tenant_id)
            {
                if($tenant_id == $leaf_id_user)
                {
                    return $return;
                }
            }
        }
                    
        return false;
    }

    public static function update_customer_power_usage_summary($leaf_group_id=null){
        
        if(isset($leaf_group_id)){
            Setting::set_company($leaf_group_id);
        }

        $leaf_api   =   new LeafAPI();
        $meter_reading = new MeterReading();
        $fdata      =   $leaf_api->get_houses();
        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
                $houses = $fdata['house'];
                foreach ($houses as $house) {
                    foreach($house['house_rooms'] as $room){
                        foreach ($room['house_room_members'] as $member) {
                            PowerMeterAccount::update_or_save_customer_summary_by_leaf_member_id($member['id_house_member']);
                        }   
                    }
                }
            }
        }

    }

    //Status code ------------------------------------------------------------------------------------------------------------------
    // 1 is need to update
    // 0 no need update
    // 99 no customer and summary model record
    // 100 no summary model record
    //Status code ------------------------------------------------------------------------------------------------------------------
    public static function check_is_need_to_update_by_id_house_member($id_house_member , $force_update = null){
       
       if(isset($force_update)){
            if($force_update == true){
                return 1;
            }
       }

        $leaf_api = new LeafAPI();
        $customer_model = Customer::get_customer_model_by_leaf_id_house_member($id_house_member);
       // echo 'Name :'.$customer_model['name'].' || id :'.$customer_model['id'].'  =  ' ;
        if(isset($customer_model['id']) == false){
            return 99;
        }

        $model = static::where('id_house_member', '=' , $id_house_member)
                        ->first();
                    
        $member = $leaf_api->get_member_detail_by_member_id($id_house_member);

        //Remain room
        if($model['id_house_member'] == $member['id_house_member'] && $model['leaf_house_id'] == $member['leaf_house_id'] && $model['leaf_room_id'] == $member['leaf_room_id']){
            
                $now = new DateTime();
                if(!isset($model['updated_at'])){
                    return 1;
                }

                $diff_in_second = $now->getTimestamp() - $model['updated_at']->getTimestamp();

                
                if($diff_in_second/3600 >= 1){                 
                    return 1;
                }else{
                    return 0;
                }

        //Switch room
        }else if($model['id_house_member'] == $member['id_house_member'] ){
          
            if($model['leaf_house_id'] != $member['leaf_house_id'] || $model['leaf_room_id'] != $member['leaf_room_id']){
                return 100;
            }else{
                return 100;
            }
            
        //No record
        }else{ 
            return 100;
        }

    }

    //public static function get_group_member_

    public static function find_model_by_leaf_member_id($id_house_member){

        $leaf_api = new LeafAPI();
        $member = $leaf_api->get_member_detail_by_member_id($id_house_member);
        $return = static::where('id_house_member','=',$member['id_house_member'])
                        ->where('leaf_house_id','=',$member['leaf_house_id'])
                        ->where('leaf_room_id','=',$member['leaf_room_id'])
                        ->first();

        return $return;

    }

      public static function update_or_save_customer_account_summary_by_leaf_member_id($id_house_member,$is_update_data = null){

        $model;
        $customer = new Customer();
  
        DB::beginTransaction();
        try {

            $customer_model = Customer::get_customer_model_by_leaf_id_house_member($id_house_member);       
            if(isset($customer_model['id']))
            {
                //create customer model 
                $customer_model = Customer::save_customer_from_leaf_house(null,$id_house_member);
            }  //stop here
            $model['leaf_id_user'] = $customer_model['leaf_id_user'];
            $model['id_house_member'] = $customer_model['id_house_member'];
            $model['ncl_id'] = $customer_model['ncl_id'];
            $model['customer_id'] = $customer_model['id'];
            $model['customer_name'] = $customer_model['name'];
            $model['currency_id'] = $customer_model['currency_id'];
            $model['status'] = $customer_model['status'];
            $model['type'] = "wip";

            $currency_model = Currency::find($customer_model['currency_id']);
            $model['currency_code'] = isset($currency_model['id']) ? $currency_model['code'] : '';
            $model['currency_rate'] = isset($currency_model['id']) ? $currency_model['rate'] : '';

            $member_model = LeafAPI::get_member_detail_by_member_id($id_house_member);
            $model['check_in_date'] = isset($member_model['house_room_member_start_date']) == true ? $member_model['house_room_member_start_date'] : '';
            $model['check_out_date'] = isset($member_model['house_room_member_end_date']) == true ? $member_model['house_room_member_end_date'] : '';
            $model['leaf_room_id'] =  isset($member_model['leaf_room_id']) == true ? $member_model['leaf_room_id'] : ''; 
            $model['house_name'] = LeafAPI::get_room_name_by_leaf_room_id($model['leaf_room_id']);
            $model['leaf_house_id'] = isset($member_model['leaf_house_id']) == true ? $member_model['leaf_house_id'] : ''; 

            $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($model['leaf_room_id']);
            $model['meter_register_id'] = $meter_register_model['id'];
     
            //App calculation ----------------------------------------------------------------------------------------------------------------------------
            //New logic for separate tester
            //new field for separete beta user//WIP 
            //---------------------------------------------------------------------------------------------------------------------------------------------
            $is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($customer_model['leaf_id_user'],$customer_model['leaf_group_id']);
            $date_started = User::get_date_statarted_temp_by_id_house_member($id_house_member);;
            /*if($is_allow_to_pay == false){
                $date_range['date_started'] = Company::get_system_live_date();
            }else{
                $date_started = $member_model['house_room_member_start_date'];
            }*/

            $date_range     = array('date_started' => $date_started ,'date_ended' =>  date('Y-m-d', strtotime('now')));
            $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $model['leaf_room_id'] , $date_range);
            echo "On test";
           
            $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($meter_register_model->id); 
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($customer_model['leaf_id_user'] ,$meter_register_model->id ,$customer_model['leaf_group_id']);
           
            foreach ($payment_received_listing as $row) {
                    $account_status['total_paid_amount'] += $row['total_amount'];
            }   

            foreach ($subsidy_listing as $row) {
                    $account_status['total_subsidy_amount'] += $row['total_amount'];
            } 

            $statistic['current_usage_kwh'] =  $account_status['total_usage_kwh']; 
            $statistic['current_usage_charges'] =  $account_status['total_payable_amount'];
            $statistic['balance_amount'] = $account_status['total_paid_amount'] -  $account_status['total_payable_amount'];  

            if($statistic['balance_amount'] > 0 ){
                     $statistic['current_balance_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['current_usage_kwh'] , $statistic['balance_amount']);
            }else{
                    $statistic['current_balance_kwh'] = 0;
            }
            //App calculation ----------------------------------------------------------------------------------------------------------------------------

            $model['total_usage_kwh'] = $statistic['current_usage_kwh'];
            $model['total_payable_amount'] = $account_status['total_payable_amount'];
            $model['total_paid_amount'] = $account_status['total_paid_amount'];
            $model['total_subsidy_amount'] = $account_status['total_subsidy_amount'];
            $model['total_outstanding_amount'] = $model['total_payable_amount'] - $model['total_paid_amount'] -  $model['total_subsidy_amount'];
            $current_credit = $model['total_paid_amount'] - $model['total_payable_amount'];
            $model['current_credit_amount'] = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
            $model['current_balance_kwh'] = $statistic['current_balance_kwh'];

            if(isset($model['id']) == false){

                $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));

            }else{

                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
            }

            $model['leaf_group_id'] = $customer_model['leaf_group_id'];
            if(is_array($model) == true){
                $new_model = new PowerMeterAccount();
                foreach($model as $key => $value)
                {
                    $new_model[$key] = $value;
                }
                $new_model->save();
                unset($model);
                $model = $new_model;
            }else{
                $model->save();
            } 
            

        }catch (Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
        DB::commit();    


        return $model;
    }
 


const default_data_mappers = [ 'ncl_id'=>0,'is_power_supply_on'=>'1',''=>'',''=>'',''=>''];
const date_now_mappers =['created_at' , 'updated_at'];




      const member_detail_to_summary_model_variables_mapping = ['leaf_room_id'=>'leaf_room_id','id_house_member'=>'','id_house_member'=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>''];
      const member_detail_data_to_summary_model_variables_mapping = ['leaf_room_id'=>'leaf_room_id','id_house_member'=>'','id_house_member'=>'','leaf_id_user'=>'house_member_id_user','leaf_house_id'=>'leaf_house_id',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>''];
      

        const model_to_customer_key_mappers = ['ncl_id' =>'ncl_id','customer_id' =>'id','customer_name' =>'name','currency_id' =>'currency_id','status' =>'status'];
     // house_member_id_user
      public static function getOrCreatePowerMeterAccount($membership_detail,$is_update_data = null){
        dd($membership_detail);
        $model;
        $customer = new Customer();
        $counter=0;
        $id_house_member = isset($membership_detail['id_house_member']) ? $membership_detail['id_house_member'] : 0;
        if(isset($is_update_data)){
     
            $model =  static::find_model_by_leaf_member_id($id_house_member);   

        }

        if(!isset($model['id'])){
            
            $model =  new PowerMeterAccount();    
            
        }
        
        DB::beginTransaction();
        try {

            $customer_model = Customer::get_customer_model_by_leaf_id_house_member($id_house_member);         
            //$model['leaf_id_user'] = $customer_model['leaf_id_user'];
            //$model['id_house_member'] = $customer_model['id_house_member'];

            foreach(static::member_detail_to_summary_model_variables_mapping as $key => $value)
            {
                $model[$key] = isset($membership_detail[$value]) ? $membership_detail[$value] : '';
            }

            $membership_detail_data = $membership_detail['member_detail'];
            foreach(static::member_detail_data_to_summary_model_variables_mapping as $key => $value)
            {
                $model[$key] = isset($membership_detail_data[$value]) ? $membership_detail_data[$value] : '';
            }

            foreach(static::model_to_customer_key_mappers as $key => $value)
            {
                $model[$key] = isset($customer_model[$value]) ? $customer_model[$value] : '';
            }
        
            $model['type'] = "wip";

            $currency_model = Currency::find($customer_model['currency_id']);
            $model['currency_code'] = isset($currency_model['id']) ? $currency_model['code'] : '';
            $model['currency_rate'] = isset($currency_model['id']) ? $currency_model['rate'] : '';

            $member_model = isset($membership_detail['id_house_member']) ? $membership_detail['id_house_member'] : LeafAPI::get_member_detail_by_member_id($id_house_member);
            
            if(  $model['is_checked_in'] = true)
            {

            }else{
                  $model['check_in_date'] = isset($member_model['house_room_member_start_date']) == true ? $member_model['house_room_member_start_date'] : '';
                  $model['is_checked_in'] = true;
            }

            
            $model['check_out_date'] = isset($member_model['house_room_member_end_date']) == true ? $member_model['house_room_member_end_date'] : '';
            $model['leaf_room_id'] =  isset($member_model['leaf_room_id']) ? $member_model['leaf_room_id'] : ''; 
            $model['house_name'] = LeafAPI::get_room_name_by_leaf_room_id($model['leaf_room_id']);
            $model['leaf_house_id'] = isset($member_model['leaf_house_id']) == true ? $member_model['leaf_house_id'] : ''; 
  dd($model);
            $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($model['leaf_room_id']);
            $model['meter_register_id'] = $meter_register_model['id'];
     
            //App calculation ----------------------------------------------------------------------------------------------------------------------------
            //New logic for separate tester
            //new field for separete beta user//WIP 
            //---------------------------------------------------------------------------------------------------------------------------------------------
            $is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($customer_model['leaf_id_user'],$customer_model['leaf_group_id']);
            $date_started = User::get_date_statarted_temp_by_id_house_member($id_house_member);;
            /*if($is_allow_to_pay == false){
                $date_range['date_started'] = Company::get_system_live_date();
            }else{
                $date_started = $member_model['house_room_member_start_date'];
            }*/

            $date_range     = array('date_started' => $date_started ,'date_ended' =>  date('Y-m-d', strtotime('now')));
            $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $model['leaf_room_id'] , $date_range);
          
         
            $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($meter_register_model->id); 
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($customer_model['leaf_id_user'] ,$meter_register_model->id ,$customer_model['leaf_group_id']);
           
            foreach ($payment_received_listing as $row) {
                    $account_status['total_paid_amount'] += $row['total_amount'];
            }   

            foreach ($subsidy_listing as $row) {
                    $account_status['total_subsidy_amount'] += $row['total_amount'];
            } 

            $statistic['current_usage_kwh'] =  $account_status['total_usage_kwh']; 
            $statistic['current_usage_charges'] =  $account_status['total_payable_amount'];
            $statistic['balance_amount'] = $account_status['total_paid_amount'] -  $account_status['total_payable_amount'];  

            if($statistic['balance_amount'] > 0 ){
                     $statistic['current_balance_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['current_usage_kwh'] , $statistic['balance_amount']);
            }else{
                    $statistic['current_balance_kwh'] = 0;
            }
            //App calculation ----------------------------------------------------------------------------------------------------------------------------

            $model['total_usage_kwh'] = $statistic['current_usage_kwh'];
            $model['total_payable_amount'] = $account_status['total_payable_amount'];
            $model['total_paid_amount'] = $account_status['total_paid_amount'];
            $model['total_subsidy_amount'] = $account_status['total_subsidy_amount'];
            $model['total_outstanding_amount'] = $model['total_payable_amount'] - $model['total_paid_amount'] -  $model['total_subsidy_amount'];
            $current_credit = $model['total_paid_amount'] - $model['total_payable_amount'];
            $model['current_credit_amount'] = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
            $model['current_balance_kwh'] = $statistic['current_balance_kwh'];

            if(isset($model['id']) == false){

                $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));

            }else{

                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
            }

            $model['leaf_group_id'] = $customer_model['leaf_group_id'];
            if(is_array($model) == true){
                $new_model = new PowerMeterAccount();
                foreach($model as $key => $value)
                {
                    $new_model[$key] = $value;
                }
                $new_model->save();
                unset($model);
                $model = $new_model;
            }else{
                $model->save();
            } dd($model);
            

        }catch (Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
        DB::commit();    


        return $model;
    }
    public static function update_or_save_customer_summary_by_leaf_member_id_obsolete($id_house_member,$is_update_data = null){

        $model;
        $customer = new Customer();
        $counter=0;
        if(isset($is_update_data)){
            echo $counter.'<br>';$counter++;
            $model =  static::find_model_by_leaf_member_id($id_house_member);   

        }else{
            
            $is_proceed = static::check_is_need_to_update_by_id_house_member($id_house_member);
             echo $counter.'<br>';$counter++;
            if($is_proceed == 0){
                 return ;
            }else if($is_proceed == 1){
                //update
                $model =  static::find_model_by_leaf_member_id($id_house_member);   
                
            }else if($is_proceed == 99){
                //create new customer and update it
                $model =  new PowerMeterAccount();          
                $customer->save_customer_from_leaf_house(null,$id_house_member); 

            }else if($is_proceed == 100){
                $model =  new PowerMeterAccount();    
            }
        }
         echo $counter.'<br>';$counter++;
        DB::beginTransaction();
        try {

            $customer_model = Customer::get_customer_model_by_leaf_id_house_member($id_house_member);         
            $model['leaf_id_user'] = $customer_model['leaf_id_user'];
            $model['id_house_member'] = $customer_model['id_house_member'];
            $model['ncl_id'] = $customer_model['ncl_id'];
            $model['customer_id'] = $customer_model['id'];
            $model['customer_name'] = $customer_model['name'];
            $model['currency_id'] = $customer_model['currency_id'];
            $model['status'] = $customer_model['status'];
            $model['type'] = "wip";

            $currency_model = Currency::find($customer_model['currency_id']);
            $model['currency_code'] = isset($currency_model['id']) ? $currency_model['code'] : '';
            $model['currency_rate'] = isset($currency_model['id']) ? $currency_model['rate'] : '';

            $member_model = LeafAPI::get_member_detail_by_member_id($id_house_member);
            $model['check_in_date'] = isset($member_model['house_room_member_start_date']) == true ? $member_model['house_room_member_start_date'] : '';
            $model['check_out_date'] = isset($member_model['house_room_member_end_date']) == true ? $member_model['house_room_member_end_date'] : '';
            $model['leaf_room_id'] =  isset($member_model['leaf_room_id']) == true ? $member_model['leaf_room_id'] : ''; 
            $model['house_name'] = LeafAPI::get_room_name_by_leaf_room_id($model['leaf_room_id']);
            $model['leaf_house_id'] = isset($member_model['leaf_house_id']) == true ? $member_model['leaf_house_id'] : ''; 

            $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($model['leaf_room_id']);
            $model['meter_register_id'] = $meter_register_model['id'];
     
            //App calculation ----------------------------------------------------------------------------------------------------------------------------
            //New logic for separate tester
            //new field for separete beta user//WIP 
            //---------------------------------------------------------------------------------------------------------------------------------------------
            $is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($customer_model['leaf_id_user'],$customer_model['leaf_group_id']);
            $date_started = User::get_date_statarted_temp_by_id_house_member($id_house_member);;
            /*if($is_allow_to_pay == false){
                $date_range['date_started'] = Company::get_system_live_date();
            }else{
                $date_started = $member_model['house_room_member_start_date'];
            }*/

            $date_range     = array('date_started' => $date_started ,'date_ended' =>  date('Y-m-d', strtotime('now')));
            $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $model['leaf_room_id'] , $date_range);
            echo "On test";
           
            $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($meter_register_model->id); 
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($customer_model['leaf_id_user'] ,$meter_register_model->id ,$customer_model['leaf_group_id']);
           
            foreach ($payment_received_listing as $row) {
                    $account_status['total_paid_amount'] += $row['total_amount'];
            }   

            foreach ($subsidy_listing as $row) {
                    $account_status['total_subsidy_amount'] += $row['total_amount'];
            } 

            $statistic['current_usage_kwh'] =  $account_status['total_usage_kwh']; 
            $statistic['current_usage_charges'] =  $account_status['total_payable_amount'];
            $statistic['balance_amount'] = $account_status['total_paid_amount'] -  $account_status['total_payable_amount'];  

            if($statistic['balance_amount'] > 0 ){
                     $statistic['current_balance_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['current_usage_kwh'] , $statistic['balance_amount']);
            }else{
                    $statistic['current_balance_kwh'] = 0;
            }
            //App calculation ----------------------------------------------------------------------------------------------------------------------------

            $model['total_usage_kwh'] = $statistic['current_usage_kwh'];
            $model['total_payable_amount'] = $account_status['total_payable_amount'];
            $model['total_paid_amount'] = $account_status['total_paid_amount'];
            $model['total_subsidy_amount'] = $account_status['total_subsidy_amount'];
            $model['total_outstanding_amount'] = $model['total_payable_amount'] - $model['total_paid_amount'] -  $model['total_subsidy_amount'];
            $current_credit = $model['total_paid_amount'] - $model['total_payable_amount'];
            $model['current_credit_amount'] = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
            $model['current_balance_kwh'] = $statistic['current_balance_kwh'];

            if(isset($model['id']) == false){

                $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));

            }else{

                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
            }

            $model['leaf_group_id'] = $customer_model['leaf_group_id'];
            if(is_array($model) == true){
                $new_model = new PowerMeterAccount();
                foreach($model as $key => $value)
                {
                    $new_model[$key] = $value;
                }
                $new_model->save();
                unset($model);
                $model = $new_model;
            }else{
                $model->save();
            } 
            

        }catch (Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
        DB::commit();    


        return $model;
    }

    public static function update_or_save_customer_summary_by_leaf_house_2($house , $is_update_data =null)
    {
        if(!isset($house['house_rooms'])){
            return;
        }

        foreach ($house['house_rooms'] as $room) {
            if(!isset($room['house_room_members'])){
                continue;
                if(count($room['house_room_members']) == 0){
                    continue;
                }
            }
        
            DB::beginTransaction();
          try {

                foreach ($room['house_room_members'] as $stay_member) 
                {       echo 'Patching user :'.$stay_member['house_member_name'].'<br>';

                        $date_range['date_started'] = isset($stay_member['house_room_member_start_date']) == true ? $stay_member['house_room_member_start_date'] : '' ;
                        $date_range['date_started'] = isset($stay_member['house_room_member_end_date']) == true ? ( $stay_member['house_room_member_end_date']  == '0000-00-00 00:00:00' ? '-' : $stay_member['house_room_member_end_date'] ): '';

                        $model =  PowerMeterAccount::get_model_by_date_range_and_leaf_id_house_member($date_range , $stay_member['id_house_member']);
                        
                        if(!isset($model['id'])){
                            $model =  new PowerMeterAccount(); 
                        }else if( $stay_member['house_room_member_deleted']  == false){

                        }else if( $stay_member['house_room_member_deleted']  == true && $model['type']  == 'left'){
                            if(isset($is_update_data)){
                                if($is_update_data != true){continue;}
                            }

                        }else if(isset($is_update_data)){
                            //for new tenant will update while old data keep
                            if($is_update_data != true){continue;}
                        }else{
                            //skip update for old data
                            continue;
                        }
                        


                        $id_house_member = $stay_member['id_house_member'];
                        $customer_model = Customer::get_customer_model_by_leaf_id_house_member($stay_member['id_house_member']);       

                        //create customer if no exist
                        if(!isset($customer_model['id'])){
                            echo 'New customer found :'.$stay_member['house_member_name'].'<br> <br>';
                             Customer::set_customer_by_id_house_member($stay_member['id_house_member']);
                             $customer_model = Customer::get_customer_model_by_leaf_id_house_member($stay_member['id_house_member']);  
                        }
                       
                        $model['leaf_id_user'] = $stay_member['house_member_id_user'];
                        $model['id_house_member'] = $stay_member['id_house_member'];
                        $model['ncl_id'] = $customer_model['ncl_id'];
                        $model['customer_id'] = $customer_model['id'];
                        $model['customer_name'] = $customer_model['name'];
                        $model['currency_id'] = $customer_model['currency_id'];
                        $model['status'] = $customer_model['status'];
                        $model['type'] = $stay_member['house_room_member_deleted'] == true ? 'Left' : 'Stay';

                        $currency_model = Currency::find($customer_model['currency_id']);
                        $model['currency_code'] = isset($currency_model['id']) ? $currency_model['code'] : '';
                        $model['currency_rate'] = isset($currency_model['id']) ? $currency_model['rate'] : '';

                        //$stay_member = LeafAPI::get_member_detail_by_member_id($id_house_member);
                        $model['check_in_date'] = isset($stay_member['house_room_member_start_date']) == true ? $stay_member['house_room_member_start_date'] : '' ;
                        $model['check_out_date'] = isset($stay_member['house_room_member_end_date']) == true ? ( $stay_member['house_room_member_end_date']  == '0000-00-00 00:00:00' ? '-' : $stay_member['house_room_member_end_date'] ): '';
                        $model['leaf_room_id'] =  isset($room['id_house_room']) == true ? $room['id_house_room'] : 0; 
                        $model['house_name'] = LeafAPI::get_room_name_by_leaf_room_id($room['id_house_room']);
                        $model['leaf_house_id'] = isset($house['id_house']) == true ? $house['id_house'] : ''; 

                        $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);
                        $model['meter_register_id'] = $meter_register_model['id'];

                        //App calculation ----------------------------------------------------------------------------------------------------------------------------
                        //New logic for separate tester
                        //new field for separete beta user//WIP 
                        //---------------------------------------------------------------------------------------------------------------------------------------------
                        //$is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($customer_model['leaf_id_user'],$customer_model['leaf_group_id']);

                        //temp , NEW LOGIC WITH STATED date
                        $date_started = $stay_member['house_room_member_start_date'];
                        $date_ended = $stay_member['house_room_member_deleted'] == true ? $stay_member['house_room_member_end_date'] : date('Y-m-d', strtotime('now')) ;
                        $date_range     = array('date_started' => $date_started ,'date_ended' =>  $date_ended);
                        //echo json_encode($date_range)."<br>";
                        $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $model['leaf_room_id'] , $date_range);
            

                        $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id_and_date_range($meter_register_model->id , $date_range); 
                        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($customer_model['leaf_id_user'] ,$meter_register_model->id , $date_range , Company::get_group_id());

                        foreach ($payment_received_listing as $row) {
                            $account_status['total_paid_amount'] += $row['total_amount'];
                        }   

                        foreach ($subsidy_listing as $row) {
                        $account_status['total_subsidy_amount'] += $row['total_amount'];
                        } 

                        $statistic['current_usage_kwh'] =  $account_status['total_usage_kwh']; 
                        $statistic['current_usage_charges'] =  $account_status['total_payable_amount'];
                        $statistic['balance_amount'] = $account_status['total_paid_amount'] -  $account_status['total_payable_amount'];  

                        if($statistic['balance_amount'] > 0 ){
                        $statistic['current_balance_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['current_usage_kwh'] , $statistic['balance_amount']);
                        }else{
                        $statistic['current_balance_kwh'] = 0;
                        }
                        //App calculation ----------------------------------------------------------------------------------------------------------------------------

                        $model['total_usage_kwh'] = $statistic['current_usage_kwh'];
                        $model['total_payable_amount'] = $account_status['total_payable_amount'];
                        $model['total_paid_amount'] = $account_status['total_paid_amount'];
                        $model['total_subsidy_amount'] = $account_status['total_subsidy_amount'];
                        $model['total_outstanding_amount'] = $model['total_payable_amount'] - $model['total_paid_amount'] -  $model['total_subsidy_amount'];
                        $current_credit = $model['total_paid_amount'] - $model['total_payable_amount'];
                        $model['current_credit_amount'] = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
                        $model['current_balance_kwh'] = $statistic['current_balance_kwh'];

                        if(isset($model['id']) == false){

                            $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                            $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));

                        }else{

                            $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
                        }

                        $model['leaf_group_id'] = $customer_model['leaf_group_id'];
                        if(is_array($model) == true){
                            dd($model);
                        } 
                        //dd($model);
                        $model->save();
                }


            }catch (Exception $e) {
                throw $e;
                //dd($e);
                DB::rollBack();
                return false;
            }



            DB::commit();  
        }       
    }


    public static function update_or_save_customer_summary_by_leaf_house($house , $is_update_data =null)
    {
        if(!isset($house['house_rooms'])){
            return;
        }

        foreach ($house['house_rooms'] as $room) {
            if(!isset($room['house_room_members'])){
                continue;
                if(count($room['house_room_members']) == 0){
                    continue;
                }
            }
        
            DB::beginTransaction();
          try {

                foreach (json_decode($room['house_room_members']) as $stay_member) 
                {       
                        $stay_member = (array) $stay_member;
                        echo 'Patching user :'.$stay_member['house_member_name'].'<br>';

                        $date_range['date_started'] = isset($stay_member['house_room_member_start_date']) == true ? $stay_member['house_room_member_start_date'] : '' ;
                        $date_range['date_ended'] = isset($stay_member['house_room_member_end_date']) == true ? ( $stay_member['house_room_member_end_date']  == '0000-00-00 00:00:00' ? '-' : $stay_member['house_room_member_end_date'] ): '';



                        $model =  PowerMeterAccount::get_model_by_date_range_and_leaf_id_house_member($date_range , $stay_member['id_house_member']);
                        
                        if(!isset($model['id'])){
                            $model =  new PowerMeterAccount(); 
                        }else if( $stay_member['house_room_member_deleted']  == false){

                        }else if( $stay_member['house_room_member_deleted']  == true && $model['type']  == 'left'){
                            if(isset($is_update_data)){
                                if($is_update_data != true){continue;}
                            }

                        }else if(isset($is_update_data)){
                            //for new tenant will update while old data keep
                            if($is_update_data != true){continue;}
                        }else{
                            //skip update for old data
                            continue;
                        }
                        


                        $id_house_member = $stay_member['id_house_member'];
                        $customer_model = Customer::get_customer_model_by_leaf_id_house_member($stay_member['id_house_member']);       

                        //create customer if no exist
                        if(!isset($customer_model['id'])){
                            echo 'New customer found :'.$stay_member['house_member_name'].'<br> <br>';
                             Customer::set_customer_by_id_house_member($stay_member['id_house_member']);
                             $customer_model = Customer::get_customer_model_by_leaf_id_house_member($stay_member['id_house_member']);  
                        }
                       
                        $model['leaf_id_user'] = $stay_member['house_member_id_user'];
                        $model['id_house_member'] = $stay_member['id_house_member'];
                        $model['ncl_id'] = $customer_model['ncl_id'];
                        $model['customer_id'] = $customer_model['id'];
                        $model['customer_name'] = $customer_model['name'];
                        $model['currency_id'] = $customer_model['currency_id'];
                        $model['status'] = $customer_model['status'];
                        $model['type'] = $stay_member['house_room_member_deleted'] == true ? 'Left' : 'Stay';

                        $currency_model = Currency::find($customer_model['currency_id']);
                        $model['currency_code'] = isset($currency_model['id']) ? $currency_model['code'] : '';
                        $model['currency_rate'] = isset($currency_model['id']) ? $currency_model['rate'] : '';

                        //$stay_member = LeafAPI::get_member_detail_by_member_id($id_house_member);
                        $model['check_in_date'] = isset($stay_member['house_room_member_start_date']) == true ? $stay_member['house_room_member_start_date'] : '' ;
                        $model['check_out_date'] = isset($stay_member['house_room_member_end_date']) == true ? ( $stay_member['house_room_member_end_date']  == '0000-00-00 00:00:00' ? '-' : $stay_member['house_room_member_end_date'] ): '';
                        $model['leaf_room_id'] =  isset($room['id_house_room']) == true ? $room['id_house_room'] : 0; 
                        $model['house_name'] = $house['house_subgroup'].' : '.$house['house_unit'].' : '.$room['house_room_name'].'['.$room['house_room_type'].']';
                        //LeafAPI::get_room_name_by_leaf_room_id($room['id_house_room']);
                        $model['leaf_house_id'] = isset($house['id_house']) == true ? $house['id_house'] : ''; 

                        $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);
                        $model['meter_register_id'] = $meter_register_model['id'];

                        //App calculation ----------------------------------------------------------------------------------------------------------------------------
                        //New logic for separate tester
                        //new field for separete beta user//WIP 
                        //---------------------------------------------------------------------------------------------------------------------------------------------
                        //$is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($customer_model['leaf_id_user'],$customer_model['leaf_group_id']);

                        //temp , NEW LOGIC WITH STATED date
                        $date_started = $stay_member['house_room_member_start_date'];
                        $date_ended = $stay_member['house_room_member_deleted'] == true ? $stay_member['house_room_member_end_date'] : date('Y-m-d', strtotime('now')) ;
                        $date_range     = array('date_started' => $date_started ,'date_ended' =>  $date_ended);
                        //echo json_encode($date_range)."<br>";
                        $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $model['leaf_room_id'] , $date_range);
            

                        $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id_and_date_range($meter_register_model->id , $date_range); 
                        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($customer_model['leaf_id_user'] ,$meter_register_model->id , $date_range , Company::get_group_id());

                        foreach ($payment_received_listing as $row) {
                            $account_status['total_paid_amount'] += $row['total_amount'];
                        }   

                        foreach ($subsidy_listing as $row) {
                        $account_status['total_subsidy_amount'] += $row['total_amount'];
                        } 

                        $statistic['current_usage_kwh'] =  $account_status['total_usage_kwh']; 
                        $statistic['current_usage_charges'] =  $account_status['total_payable_amount'];
                        $statistic['balance_amount'] = $account_status['total_paid_amount'] -  $account_status['total_payable_amount'];  

                        if($statistic['balance_amount'] > 0 ){
                        $statistic['current_balance_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['current_usage_kwh'] , $statistic['balance_amount']);
                        }else{
                        $statistic['current_balance_kwh'] = 0;
                        }
                        //App calculation ----------------------------------------------------------------------------------------------------------------------------

                        $model['total_usage_kwh'] = $statistic['current_usage_kwh'];
                        $model['total_payable_amount'] = $account_status['total_payable_amount'];
                        $model['total_paid_amount'] = $account_status['total_paid_amount'];
                        $model['total_subsidy_amount'] = $account_status['total_subsidy_amount'];
                        $model['total_outstanding_amount'] = $model['total_payable_amount'] - $model['total_paid_amount'] -  $model['total_subsidy_amount'];
                        $current_credit = $model['total_paid_amount'] - $model['total_payable_amount'];
                        $model['current_credit_amount'] = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
                        $model['current_balance_kwh'] = $statistic['current_balance_kwh'];

                        if(isset($model['id']) == false){

                            $model['created_at'] = date('Y-m-d h:m:s', strtotime('now'));
                            $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));

                        }else{

                            $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
                        }

                        $model['leaf_group_id'] = $customer_model['leaf_group_id'];
                        if(is_array($model) == true){
                            dd($model);
                        } 
                        //dd($model);
                        $model->save();
                }


            }catch (Exception $e) {
                throw $e;
                //dd($e);
                DB::rollBack();
                return false;
            }



            DB::commit();  
        }       
    }

    //wip
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
}
