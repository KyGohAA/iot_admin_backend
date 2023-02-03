<?php

namespace App\PowerMeterModel;

use DB;
use Log;
use Auth;
use Schema;
use Validator;
use DateTime;

use App\Room;
use App\User;
use App\Company;
use App\LeafAPI;
use App\NclAPI;
use App\Customer;
use App\Currency;
use App\Setting;
use App\PaymentTestingAllowList;
use App\Language;

use Illuminate\Database\Eloquent\Builder;

class CustomerPowerUsageSummary extends ExtendModel
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

    public function meter_register()
    {
        return $this->belongsTo('App\PowerMeterModel\MeterRegister', 'meter_register_id');
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

    public function getPowerSupplyState()
    {
        $this->power_supply_class =   $this->is_power_supply_on == true ? 'success' : 'danger';
        $this->power_supply_state = $this->is_power_supply_on == true ?  Language::trans('Active') :  Language::trans('Suspend');
        $this->last_meter_reading =  date('jS F Y h:00 A', strtotime($this->last_meter_reading));
    }

    const turn_on_off_variable_mapper = ['meter_id'=>'id' , 'ip_address'=> 'relay_controller_ip_address' , 'contract_no' => 'contract_no'];
    public function terminate_power_supply()
    {
        $this->is_power_supply_on = false;
        $meter_register_model = $this->meter_register;
        if(!isset($meter_register_model['id']))
        {
            $meter_register_model = $this->getOrUpdateMeterRegister();
        }

         if(!isset($meter_register_model['id']))
        {
           return false;
        }


        $trigger_data = array();
        $trigger_data['is_power_supply_on'] = false;
        foreach(static::turn_on_off_variable_mapper as $key => $meter_key)
        {
             $trigger_data[$key]  = isset($meter_register_model[$meter_key]) ? $meter_register_model[$meter_key] : '';
        }

        //echo 'Off data :'.json_encode($trigger_data)."<br>";
        //dd($trigger_data);
        MeterRelayTest::init_meter_status($trigger_data);
        if(!isset($meter_register_model['id']))
        {
            return false;
        }


        $meter_register_model['is_power_supply_on'] = false;
        $meter_register_model->save();
        $this->save();
        

        return true;

    }

    public function restore_power_supply()
    {
        $this->is_power_supply_on = true;
        $this->save();
        $meter_register_model = $this->meter_register;

        if(!isset($meter_register_model['id']))
        {
            $meter_register_model = $this->getOrUpdateMeterRegister();

        }

        if(!isset($meter_register_model['id']))
        {
            return false;
        }

        $trigger_data = array();
        $trigger_data['is_power_supply_on'] = true;
        foreach(static::turn_on_off_variable_mapper as $key => $meter_key)
        {
             $trigger_data[$key]  = isset($meter_register_model[$meter_key]) ? $meter_register_model[$meter_key] : '';
        }
        //dd($trigger_data);
        MeterRelayTest::init_meter_status($trigger_data);

        if(!isset($meter_register_model['id']))
        {
            return false;
        }

        $meter_register_model['is_power_supply_on'] = true;
        $meter_register_model->save();
        

        return true;

    }

    public function getCurrentAccountUser()
    {
        $user_model = User::getUserByLeafIdUser($this->leaf_id_user);
        return $user_model;
    }

    public function updateNotificationHistory($next_email_time)
    {
        //$this->last_below_credit_notification_email_at = date('Y-m-d H:i:s', strtotime('now'));
        $this->below_credit_notification_count = $this->below_credit_notification_count + 1;
        //$this->warning_email_number = $this->warning_email_number + 1;
        $notification_history = json_decode( $this->temp_notification_history, true);
        $notification_history = $notification_history  == null ? array() : $notification_history;
        $temp_notification_history = ['notification_at' => $this->last_below_credit_notification_email_at , 'current_warning_count' => $this->below_credit_notification_count];
        $this->below_credit_notification_history = json_encode( array_push ( $notification_history , $temp_notification_history) );
        $this->last_below_credit_notification_email_at = $next_email_time;
        $this->save();

    }


               

    public function getUserPreferLanguage()
    {

    }

    public function getOrUpdateMeterRegister()
    {
        $meter_register_model = $this->meter_register;

        if(isset($meter_register_model['id']))
        {
            $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($this->leaf_room_id);

        }

                //dd($new_meter_model);
        if(isset($meter_register_model['id']))
        {   $update_account_data = CustomerPowerUsageSummary::find($this->id);
            $update_account_data['meter_register_id'] = $meter_register_model['id'];
            $update_account_data->save();

        }

        return $meter_register_model;
    }

    const reset_counter_variables = ['below_credit_notification_count','warning_email_number'];
    const reset_time_variables = ['last_below_credit_notification_email_at','stop_supply_termination_time'];
    public function reset_warning_counter()
    {
        foreach (static::reset_counter_variables as $key)
        {
            $this->$key = 0;
        }

        foreach (static::reset_time_variables as $key)
        {
            $this->$key = null;
        }

        $this->save();
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

    public static function combobox(/*$state_id=null*/)
    {
        $listing = static::ofAvailable('status',true)
                                /*->where('state_id','=',$state_id)*/
                                ->select('house_name', 'customer_name' ,'id')
                                ->get();

        $return = array();
        foreach ($listing as $row)
        {
            $return[$row['id']] = $row['house_name'].' - '.$row['customer_name'];
        }

        return $return;
    }

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

    public static function getUserBelowCredit($min_credit,$leaf_group_id){

         $return = static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                        ->where('current_credit_amount' , '<=' , $min_credit)
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

    public static function getCustomerPowerUsageSummaryByIdHouseRoom($leaf_room_id){

        $return = static::where('leaf_room_id','=',$leaf_room_id)
                        ->get();

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
                            CustomerPowerUsageSummary::update_or_save_customer_summary_by_leaf_member_id($member['id_house_member']);
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
    public function getLeafGroupAll($leaf_group_id){

         $return = static::where('leaf_group_id' , '=' , $leaf_group_id)
                        ->where('status' , '=' , true)
                        ->get();

        return $return;

    }

    public static function getByMeterRegisterId($meter_register_id)
    {
        $return = static::where('meter_register_id' , '=' , $meter_register_id)
                        ->where('status' , '=' , true)
                        ->first();

        return $return;
    }

    public static function find_model_by_leaf_member_id($id_house_member){

        $leaf_api = new LeafAPI();
        $member = $leaf_api->get_member_detail_by_member_id($id_house_member , true);

        $return = static::where('id_house_member','=',$member['id_house_member'])
                        ->where('leaf_house_id','=',$member['leaf_house_id'])
                        ->where('leaf_room_id','=',$member['leaf_room_id'])
                        ->first();

        return $return;

    }
    
    const no_allow_zero_variables = ['leaf_room_id', 'leaf_house_id'/*, 'leaf_id_user'*/];
    public function updateAccountUsage(){
        
        //$this->total_usage_kwh
        if($this->check_in_date == '0000-00-00 00:00:00')
        {
            foreach(static::no_allow_zero_variables as $non_zero_variable)
            {
                if($this->$non_zero_variable == 0)
                {
                    return false;
                }
            }
        }


        $date_range     = array('date_started' =>  $this->check_in_date ,'date_ended' =>  date('Y-m-d', strtotime('now')));
        echo json_encode($date_range)."<br>";
        $payment_received_listing = MeterPaymentReceived::getPaymentByCPUSId($this->id); 
        $subsidy_listing    = MeterPaymentReceived::getSubsidyByCPUSId($this->id ,$this->leaf_group_id);
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        
        echo json_encode($this)."<br><br><br>";
        $meter_register_model = $this->meter_register;
        if(!isset($meter_register_model['id']))
        {
            $meter_register_model = $this->getOrUpdateMeterRegister();
            if(!isset($meter_register_model['id']))
            {
                 return false;
            }
           
        }

        $meter_register_id = $meter_register_model->id;   
                           
        foreach($monthly_cut_off_listing as $monthly_cut_off){
                $total_usage = 0;
            echo 'This- '.json_encode($monthly_cut_off)."<br>";
                $temp;
                $reading_listing ;
                $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
            
                if($reading_listing != null){
                    $total_usage = 0;
                    foreach ($reading_listing as $row) {
                        $total_usage += $row['total_usage'];
                    }

                    $temp_reading['total_usage_kwh'] = $total_usage;
                    $temp_reading['date'] = $monthly_cut_off['date_started'];
                    $temp_reading['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
                    $total_payable_amount +=   $temp_reading['total_payable_amount'];
                    $total_usage_kwh +=  $total_usage;
                    array_push($month_usage_summary , $temp_reading);
                } 

                $monthly_data = array();

                if($payment_received_listing !== false)
                {
                      foreach ($payment_received_listing as $row) {
                            if(date('Y-m', strtotime($row['document_date'])) == date('Y-m', strtotime($monthly_cut_off['date_started'])))
                            {
                                $monthly_data[date('Y-m', strtotime($monthly_cut_off['date_started']))]['total_paid_amount'] += $row['total_amount'];
                            }
                      }     
                }
                
                if($subsidy_listing !== false)
                {
                    foreach ($subsidy_listing as $row) {
                        if(date('Y-m', strtotime($row['document_date'])) == date('Y-m', strtotime($monthly_cut_off['date_started'])))
                        {
                             $monthly_data[date('Y-m', strtotime($monthly_cut_off['date_started']))] += $row['total_amount'];
                        }
                    } 
                }
        }
/*if(count($monthly_data) == 0)
{
    return;
}

dd($monthly_data);*/
    
//return ;

        $statistic['current_usage_kwh'] =  $account_status['total_usage_kwh']; 
        $statistic['current_usage_charges'] =  $account_status['total_payable_amount'];
        $statistic['balance_amount'] = $account_status['total_paid_amount'] -  $account_status['total_payable_amount'];  

        if($statistic['balance_amount'] > 0 ){
                 $statistic['current_balance_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['current_usage_kwh'] , $statistic['balance_amount']);
        }else{
                $statistic['current_balance_kwh'] = 0;
        }
        //App calculation ----------------------------------------------------------------------------------------------------------------------------

        $this->total_usage_kwh = $statistic['current_usage_kwh'];
        $this->total_payable_amount = $account_status['total_payable_amount'];
        $this->total_paid_amount = $account_status['total_paid_amount'];
        $this->total_subsidy_amount = $account_status['total_subsidy_amount'];
        $this->total_outstanding_amount = $this->total_payable_amount - $this->total_paid_amount -  $this->total_subsidy_amount;
        $current_credit = $this->total_paid_amount - $this->total_payable_amount;
        $this->current_credit_amount = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
        $this->current_balance_kwh = $statistic['current_balance_kwh'];

        if(isset($this->id) == false){

            $this->created_at = date('Y-m-d h:m:s', strtotime('now'));
            
        }else{

            $this->updated_at = date('Y-m-d h:m:s', strtotime('now'));
        }

        $this->leaf_group_id = Company::get_group_id();
        if(is_array($this) == true){
            //echo 'IS array';
            //dd($model);
        } 
        //echo 'Success';
       // dd($model);
        foreach (static::null_check_value_parameters as $not_null_parameter) 
        {
            $this->$not_null_parameter =  $this->$not_null_parameter === null ? 0 :  $this->$not_null_parameter;
        }


    }


    
    public function updateAccountUsageOri(){
        
        //$this->total_usage_kwh
        $date_range     = array('date_started' =>  $this->check_in_date ,'date_ended' =>  date('Y-m-d', strtotime('now')));
        echo json_encode($date_range)."<br>";
        $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range_smVersion( $this->leaf_room_id , $date_range);

        $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($this->meter_register_id); 
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($this->leaf_id_user ,$this->meter_register_id ,$this->leaf_group_id);
       

        
        foreach ($payment_received_listing as $row) {
            dd($row);
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

        $this->total_usage_kwh = $statistic['current_usage_kwh'];
        $this->total_payable_amount = $account_status['total_payable_amount'];
        $this->total_paid_amount = $account_status['total_paid_amount'];
        $this->total_subsidy_amount = $account_status['total_subsidy_amount'];
        $this->total_outstanding_amount = $this->total_payable_amount - $this->total_paid_amount -  $this->total_subsidy_amount;
        $current_credit = $this->total_paid_amount - $this->total_payable_amount;
        $this->current_credit_amount = $statistic['balance_amount'] < 0 ? 0 : $statistic['balance_amount'] ;
        $this->current_balance_kwh = $statistic['current_balance_kwh'];

        if(isset($this->id) == false){

            $this->created_at = date('Y-m-d h:m:s', strtotime('now'));
            
        }else{

            $this->updated_at = date('Y-m-d h:m:s', strtotime('now'));
        }

        $this->leaf_group_id = Company::get_group_id();
        if(is_array($this) == true){
            //echo 'IS array';
            //dd($model);
        } 
        //echo 'Success';
       // dd($model);
        foreach (static::null_check_value_parameters as $not_null_parameter) 
        {
            $this->$not_null_parameter =  $this->$not_null_parameter === null ? 0 :  $this->$not_null_parameter;
        }


    }


    public function getLatestStatus(){

        $r = Room::findByLeafRoomId($this->leaf_room_id);
        if($this->meter_register_id == 0)
        {
            $new_meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($this->leaf_room_id);
            if(isset($new_meter_register_model['id']))
            {
                $this->meter_register_id = $new_meter_register_model['id'];

            }
            
        }

        $meter_register = $this->meter_register;
        $this->is_power_supply_on = $meter_register['is_power_supply_on'];
        $this->last_meter_reading = $meter_register['last_meter_reading'];
        $this->save();
        $this->getPowerSupplyState();

    }

    public static function getByLeafRoomId($leaf_room_id)
    {
        $return = static::where('leaf_room_id' , '=' , $leaf_room_id)
                        ->where('status' , '=' , true)
                        ->first();

        return $return;
    }

    public static function getByData($data)
    {
        $return = static::where('leaf_room_id' , '=' , $data['leaf_room_id'])
                        ->where('leaf_id_user' , '=' , $data['leaf_id_user'])
                        ->where('status' , '=' , true)
                        ->first();

        return $return;
    }


const null_check_value_parameters = ['total_usage_kwh','total_payable_amount','total_paid_amount','total_subsidy_amount'];
const model_to_member_detail_value_mapper = array('leaf_id_user'=> 'house_member_id_user' , 'customer_name'=>'house_member_name');
const predefined_customer_value = array('currency_id' => 0 , 'ncl_id' => 0 , 'customer_id' => 0 , 'status' => true , 'type' => 'wip' , 'currency_code' =>'MYR' , 'currency_rate' =>1 ) ;
const model_to_house_member_value_mapper = array('is_allow_to_pay'=>'is_payable_member' ,'leaf_room_id' => 'leaf_room_id' , 'leaf_house_id' => 'leaf_house_id' , 'id_house_member'=>'id_house_member' ,'check_in_date' =>'house_room_member_start_date' , 'check_out_date' =>'house_room_member_end_date' );
 public static function update_or_save_customer_summary_by_member_detail($member_detail,$is_update_data = null){
//dd($member_detail);
      //  dd($member_detail['member_detail']['house_member_id_user']);
        $is_live = true;
        $customer = new Customer();
          Log::info('Save or update member detail :');
         Log::info(json_encode($member_detail));
        $model =  static::getByLeafRoomId($member_detail['leaf_room_id']);   
        $model = isset($model['id']) ? $model : new CustomerPowerUsageSummary();
        //dd($model);
  Log::info('NEW MODEL :');
          Log::info(json_encode($model));
        DB::beginTransaction();
        try {
           
            $leaf_api = new LeafAPI();
            $member_model = isset($member_detail['member_detail']) ? $member_detail['member_detail'] : array() ;
            $customer_model = Customer::get_customer_model_by_leaf_id_house_member($member_detail['id_house_member']);    
           
            if(!isset($customer_model['id']))
            {
                $customer_model = Customer::set_customer_from_leaf_pm($member_detail['member_detail']['house_member_id_user']);
            }

            foreach(static::predefined_customer_value as $key => $value) {
              
                $model[$key] = $value;
            }


            $house_member_detail = isset($member_detail['house_member_detail']) ? $member_detail['house_member_detail'] : array();
//dd($house_member_detail['id_house_member']);
            foreach(static::model_to_house_member_value_mapper as $model_key => $house_detail_key)
            { 
               // echo '<br>'.$house_detail_key.'='.isset($house_member_detail[$house_detail_key]);
                $model[$model_key] = isset($house_member_detail[$house_detail_key]) ? $house_member_detail[$house_detail_key] : '';
            }

            $member_detail_temp = isset($member_detail['member_detail']) ? $member_detail['member_detail'] : array();

            foreach(static::model_to_member_detail_value_mapper as $model_key => $member_detail_key)
            {
                
                $model[$model_key] = isset($member_detail_temp[$member_detail_key]) ? $member_detail_temp[$member_detail_key] : '';
            }

           
            if($model['leaf_room_id'] != '' || $model['leaf_room_id'] != 0)
            {
                $meter_register_model = MeterRegister::get_meter_register_by_leaf_room_id($model['leaf_room_id']);
            }else{
                 $meter_register_model = new MeterRegister();
            }
           

            $model['meter_register_id'] = isset($meter_register_model['id']) ? $meter_register_model['id'] : 0;
            $is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($customer_model['leaf_id_user'],$customer_model['leaf_group_id']);
            //$date_started = User::get_date_statarted_temp_by_id_house_member($id_house_member);;
            /*if($is_allow_to_pay == false){
                $date_range['date_started'] = Company::get_system_live_date();
            }else{
                $date_started = $member_model['house_room_member_start_date'];
            }*/

            $date_range     = array('date_started' =>  $model['check_in_date'] ,'date_ended' =>  date('Y-m-d', strtotime('now')));
            $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $model['leaf_room_id'] , $date_range);

            $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($meter_register_model['id']); 
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($customer_model['leaf_id_user'] ,$meter_register_model['id'] ,$customer_model['leaf_group_id']);
           
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
                
            }else{

                $model['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
            }

            $model['leaf_group_id'] = Company::get_group_id();
            if(is_array($model) == true){
                //echo 'IS array';
                //dd($model);
            } 
            //echo 'Success';
           // dd($model);
            foreach (static::null_check_value_parameters as $not_null_parameter) 
            {
                $model[$not_null_parameter] =  $model[$not_null_parameter] === null ? 0 :  $model[$not_null_parameter];
            }
            //dd($model);

            $model['house_name'] = LeafAPI::get_room_name_by_leaf_room_id($model['leaf_room_id'] , $is_live);
            $model->save();
  Log::info('Save model :'.json_encode($member_detail));
        }catch (Exception $e) {
            throw $e;
            DB::rollBack();
            return false;
        }
        DB::commit();    


        return $model;
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
            $model['check_in_date'] = isset($member_model['house_room_member_start_date']) == true ? date('Y-m-d H:m:s', strtotime($member_model['house_room_member_start_date'])) : '';
            $model['check_out_date'] = isset($member_model['house_room_member_end_date']) == true ? date('Y-m-d H:m:s', strtotime($member_model['house_room_member_end_date'])) : '';
            //$model['check_out_date'] = isset($member_model['house_room_member_end_date']) == true ? $member_model['house_room_member_end_date'] : '';
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
                $new_model = new CustomerPowerUsageSummary();
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

      const member_detail_to_summary_model_variables_mapping = ['leaf_room_id'=>'leaf_room_id','id_house_member'=>'id_house_member'/*,''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>''*/];
      const member_detail_data_to_summary_model_variables_mapping = ['leaf_room_id'=>'leaf_room_id','id_house_member'=>'','id_house_member'=>'','leaf_id_user'=>'house_member_id_user'/*,''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>'',''=>''*/];
      const default_data_mappers = [ 'ncl_id'=>0/*,''=>'',''=>'',''=>'',''=>''*/];

        const model_to_customer_key_mappers = ['ncl_id' =>'ncl_id','customer_id' =>'id','customer_name' =>'name','currency_id' =>'currency_id','status' =>'status'];

     // house_member_id_user
      public static function update_or_save_customer_summary_by_leaf_member_id($membership_detail,$is_update_data = null){
       // dd($membership_detail);
        $model;
        $customer = new Customer();
        $counter=0;
        $id_house_member = isset($membership_detail['id_house_member']) ? $membership_detail['id_house_member'] : 0;
        if(isset($is_update_data)){
     
            $model =  static::find_model_by_leaf_member_id($id_house_member);   

        }

        if(!isset($model['id'])){
            
            $model =  new CustomerPowerUsageSummary();    
            
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

            $member_model = isset($membership_detail['id_house_member']) ? $membership_detail/*['id_house_member']*/ : LeafAPI::get_member_detail_by_member_id($id_house_member);
            //stop at here

            if(!isset($member_model['house_room_member_start_date'])){
                return false;
            }
        
            $model['check_in_date'] = isset($member_model['house_room_member_start_date']) == true ? $member_model['house_room_member_start_date'] : '';
            $model['check_out_date'] = isset($member_model['house_room_member_end_date']) == true ? $member_model['house_room_member_end_date'] : '';
            $model['leaf_room_id'] =  isset($member_model['leaf_room_id']) ? $member_model['leaf_room_id'] : ''; 
            $model['house_name'] = LeafAPI::get_room_name_by_leaf_room_id($model['leaf_room_id']);
            $model['leaf_house_id'] = isset($member_model['leaf_house_id']) == true ? $member_model['leaf_house_id'] : ''; 
  //dd($model);
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
            //echo "On test";
         
            $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($meter_register_model['id']); 
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($customer_model['leaf_id_user'] ,$meter_register_model['id'] ,$customer_model['leaf_group_id']);
           
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
                $new_model = new CustomerPowerUsageSummary();
                foreach($model as $key => $value)
                {
                    $new_model[$key] = $value;
                }
                $new_model->save();
                unset($model);
                $model = $new_model;
            }else{
                $model->save();
            } //dd($model);
            

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
                $model =  new CustomerPowerUsageSummary();          
                $customer->save_customer_from_leaf_house(null,$id_house_member); 

            }else if($is_proceed == 100){
                $model =  new CustomerPowerUsageSummary();    
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
           
            $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id($meter_register_model['id']); 
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($customer_model['leaf_id_user'] ,$meter_register_model['id'] ,$customer_model['leaf_group_id']);
           
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
                $new_model = new CustomerPowerUsageSummary();
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

                        $model =  CustomerPowerUsageSummary::get_model_by_date_range_and_leaf_id_house_member($date_range , $stay_member['id_house_member']);
                        
                        if(!isset($model['id'])){
                            $model =  new CustomerPowerUsageSummary(); 
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
            

                        $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_meter_register_id_and_date_range($meter_register_model['id'] , $date_range); 
                        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($customer_model['leaf_id_user'] ,$meter_register_model['id'] , $date_range , Company::get_group_id());

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



                        $model =  CustomerPowerUsageSummary::get_model_by_date_range_and_leaf_id_house_member($date_range , $stay_member['id_house_member']);
                        
                        if(!isset($model['id'])){
                            $model =  new CustomerPowerUsageSummary(); 
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
