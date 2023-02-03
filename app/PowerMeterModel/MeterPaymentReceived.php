<?php

namespace App\PowerMeterModel;

use DB;
use Log;
use Auth;
use Schema;
use Validator;
use App\PowerMeterModel\MeterRegister;
use App\Setting;
use App\UTrasaction;
use App\LeafAPI;
use App\PowerMeterModel\MeterSubsidiary;
use App\Company;
use App\Customer;
use Illuminate\Database\Eloquent\Builder;

class MeterPaymentReceived extends ExtendModel
{
    protected $table = 'meter_payment_receiveds';
    public $timestamps = true;
    protected $listing_only_columns = ['id','document_date','document_no','customer_name','payment_method','reference_no'/*,'deposit_to_account'*/,'currency_code','total_amount'];
    protected $guarded = [];

    const label_status_approved = 'APPROVED';
    const label_status_progress = 'PROGRESS';
    const label_subsidy = 'SUBSIDY';
    const label_payment = 'PAYMENT';

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

    public function items()
    {
        return $this->hasMany('App\MeterPaymentReceivedItem', 'meter_payment_received_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
        //parent::boot();
        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id())->where('status' , '=' , true);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's listing
    |--------------------------------------------------------------------------
    |
    */

    public static function get_today_new_record()
    {
        return static::/*ofAvailable('status',true)
                ->*/where('leaf_group_id','=' , Setting::get_leaf_group_id())
                ->where('document_date' ,'=' , date('Y-m-d', strtotime('now')))
                ->count();
    }

    public static function sort_by_combobox()
    {
        return [''=>'Please select one...','amount'=>'Amount','customer_id'=>'Customer','currency_id'=>'Currency','document_date'=>'Date','document_no'=>'Receipt No','reference_no' => 'Reference No.','payment_method'=>'Payment Method'];
    }

    public static function combobox()
    {
        return static::orderBy('document_no','asc')
                        ->pluck('document_no','document_no')
                        ->prepend(Language::trans('Please select invoice...'), '');
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

    public static function getAllPaymentReceivedByCustomerIdAndDateRange($id,$dataRange){
            
           $listing = static::where('customer_id', '=', $id)
                            ->whereBetween('document_date', [$dataRange['date_started'], $dataRange['date_ended']])
                            ->where('status', '=' ,true)
                            ->select('document_no','total_amount','gst_amount','amount','customer_id','id')
                            ->get();

            return $listing;   
    }

    public static function get_recent_room_pay_summary_by_leaf_group_id($leaf_group_id){

        $return     =   static::whereBetween('document_date',[date('Y-m-d', strtotime('-100 days')), date('Y-m-d', strtotime('now'))])
                             ->where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->groupBy('leaf_room_id')
                             ->select('leaf_room_id')
                             ->get();

        return $return;
    }

    public static function get_recent_pay_by_leaf_group_id($leaf_group_id){

        $return     =   static::whereBetween('document_date',[date('Y-m-d', strtotime('-500 days')), date('Y-m-d', strtotime('now'))])
                             ->where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('payment_method' , '!=' , static::label_subsidy)
                             ->where('type' , '!=' , static::label_subsidy)
                             //->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','payment_gateway_reference_no')
                             ->get();

        return $return;
    }

    public static function get_meter_payment_received_by_meter_register_id($meter_register_id , $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('meter_register_id' , '=' , $meter_register_id)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                             ->get();

        return $return;
    }

    public static function get_meter_payment_received_by_meter_register_id_and_date_range($meter_register_id , $date_range ,$leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->whereBetween('document_date',[$date_range['date_started'] , $date_range['date_ended']])
                             ->where('meter_register_id' , '=' , $meter_register_id)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                             ->get();

        return $return;
    }


    public static function get_meter_payment_received_by_leaf_id_user($leaf_id_user , $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('leaf_id_user' , '=' , $leaf_id_user)
                             ->where('payment_method' , '!=' , static::label_subsidy)
                             ->where('type' , '!=' , static::label_subsidy)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                             ->get();

        return $return;
    }

    public static function get_model_by_leaf_payment_id_dev($leaf_payment_id){

        $return     =   static::where('leaf_payment_id' , '=' , $leaf_payment_id)
                             //->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                             ->first();
        return $return;   
    }

    public static function get_model_by_leaf_payment_id($leaf_payment_id , $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('leaf_payment_id' , '=' , $leaf_payment_id)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                             ->first();

        return $return;   
    }

    
    public static function getPaymentByCPUSId($cpus_id , $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('customer_power_usage_summary_id' , '=' , $cpus_id)
                             //->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                             ->get();

        return $return;
    }


    public static function getSubsidyByCPUSId($cpus_id , $leaf_group_id=null){
      
        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('customer_power_usage_summary_id' , '=' , $cpus_id)
                             ->where('type' , '=' , static::label_subsidy)
                            // ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
        return count($return) > 0 ? $return : false;
    }

    public static function get_user_subsidy_by_leaf_id_user_and_meter_register_id($leaf_id_user, $meter_register_id , $leaf_group_id=null){
      
        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('meter_register_id' , '=' , $meter_register_id)
                             ->where('type' , '=' , static::label_subsidy)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
        return $return;
    }

    public static function get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range_testing($leaf_id_user, $meter_register_id , $date_range , $leaf_group_id=null){
    //  dd($date_range);
        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('meter_register_id' , '=' , $meter_register_id)
                             ->where('type' , '=' , static::label_subsidy)
                             ->whereBetween('document_date', [$date_range['date_started'], $date_range['date_ended']])
                             //->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
        return $return;
    }

    public static function get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($leaf_id_user, $meter_register_id , $date_range , $leaf_group_id=null){

        $date_range['date_started'] = date('Y-m' , strtotime($date_range['date_started'])).'-01';
        $date_range['date_ended'] = date('Y-m' , strtotime($date_range['date_ended'])).'-31';

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('meter_register_id' , '=' , $meter_register_id)
                             ->where('type' , '=' , static::label_subsidy)
                             ->whereBetween('document_date', [$date_range['date_started'], $date_range['date_ended']])
                             //->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
        return $return;
    }

    public static function get_user_subsidy_by_leaf_room_id($leaf_room_id , $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('leaf_room_id','=',$leaf_room_id)
                             ->where('type' , '=' , static::label_subsidy)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
                           
        return $return;
    }

    public static function get_user_subsidy_by_leaf_room_id_and_date_range($leaf_room_id , $date_range, $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->whereBetween('document_date', [$date_range['date_started'], $date_range['date_ended']])
                             ->where('leaf_room_id','=',$leaf_room_id)
                             ->where('type' , '=' , static::label_subsidy)
                             ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
                           
        return $return;
    }

    public static function getSubsidyBySudsidyId($meter_subsidiary_id, $leaf_group_id=null){

        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('meter_subsidiary_id', '=' , $meter_subsidiary_id)
                             ->orderBy('document_date', 'asc')
                             //->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','remark')
                             ->get();
        return $return;                 
        //return count($return) > 0 ? $return : false ;
    }


    public static function get_by_document_no($document_no , $leaf_group_id=null)
    {
        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('document_no','=',$document_no)
                             ->get();
                           
        return $return;
    }

    public static function get_by_reference_no($reference_no , $leaf_group_id=null)
    {
        $return     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                             ->where('reference_no','=',$reference_no)
                             ->get();            
        return $return;
    }



    /*public static function getUserBalanceCreditBLeafRoomIdAndDateRange($id , $dateRange , $customer_id){

            $meterReadingListing =[] ;
            $paymentListing      = [];
            $creditListing       = array();
            $creditModel         = array();

            $meterRegisterModel  = MeterRegister::get_meter_register_by_leaf_room_id($id);
            $meterReadingListing = DB::select('SELECT `meter_register_id` ,`current_date`, COUNT(*) as total_hours, AVG(current_usage) as average_usage, MAX(current_usage) as max_usage, MIN(current_usage) as min_usage, SUM(current_usage) as total_usage FROM `meter_readings` WHERE `current_date` >= ? AND `current_date` <= ? AND `meter_register_id` = ? GROUP BY YEAR(`current_date`), MONTH(`current_date`)  ASC', [$dateRange['date_started'], $dateRange['date_ended'],$meterRegisterModel['id']]);
            $paymentListing  = DB::select('SELECT `id` ,`document_date` , SUM(total_amount) as total_amount ,SUM(payment_amount) as payment_amount, SUM(gst_amount) as gst_amount FROM `meter_payment_receiveds` WHERE `document_date` >= ? AND `document_date` <= ? AND `customer_id` = ? GROUP BY YEAR(`document_date`), MONTH(`document_date`)  ASC', [$dateRange['date_started'], $dateRange['date_ended'],$customer_id]);

            foreach($meterReadingListing as $rowReading){
                if(count($paymentListing) ==0){
                                $creditModel['leaf_room_id']          =  $id;
                                $creditModel['meter_register_id']     =  $meterRegisterModel['id'];
                                $creditModel['date']                  =  $rowReading->current_date;
                                $creditModel['totalUsageKwh']         =  $rowReading->total_usage;
                                $creditModel['totalPayableAmount']    =  Setting::calculate_utility_fee($rowReading->total_usage);
                                $creditModel['totalPaidAmount']       =  0;
                                $creditModel['totalBalance']          =  $creditModel['totalPaidAmount'] - $creditModel['totalPayableAmount'] ;
                                array_push($creditListing,$creditModel);               
                                           
                }else{
                        foreach ($paymentListing as $rowPayment) {
                                    if(date('Y-m', strtotime($rowReading->current_date)) == date('Y-m', strtotime($rowPayment->document_date))){
                                        $creditModel['leaf_room_id']          =  $id;
                                        $creditModel['meter_register_id']     =  $meterRegisterModel['id'];
                                        $creditModel['date']                  =  $rowReading->current_date;
                                        $creditModel['totalUsageKwh']  createPrepaidPaymentReceivedByUserRoomDocumentDateAndPaidAmount       =  $rowReading->total_usage;
                                        $creditModel['totalPayableAmount']    =  Setting::calculate_utility_fee($rowReading->total_usage);
                                        $creditModel['totalPaidAmount']       =  $rowPayment->total_amount;
                                        $creditModel['totalBalance']          =  $creditModel['totalPaidAmount'] - $creditModel['totalPayableAmount'] ;
                                        array_push($creditListing,$creditModel);
                                    }
                        }
                }         
            }

            return $creditListing;
    }*/


    /*
    |--------------------------------------------------------------------------
    | Here to manage of data processing and patching
    |--------------------------------------------------------------------------
    |
    */

    public static function get_user_account_status_by_leaf_id_user_and_date_started($leaf_user_id , $date_started = null){
    
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $user_account_detail = array();
        $first_count = 0;

        $payment_listing = static::get_meter_payment_received_by_leaf_id_user($leaf_user_id,Setting::get_leaf_group_id());
        //dd($payment_listing);

        $member_detail = LeafAPI::get_all_stayed_room_by_leaf_id_user($leaf_user_id);
        usort($member_detail, 'App\Setting::compare_by_timeStamp');
        //Setting::asc_key_sort_by_function($member_detail);
        foreach ($member_detail as $member) {
                $month_usage_summary = array();
                $temp_payment_listing = array();
                $total_usage_kwh    = 0;
                $total_payable_amount   = 0;
                $total_subsidy_amount   = 0;

                $is_after_beta = true;
                $is_latest = false;
                $temp;
                if(isset($date_started)){   

                    if($date_started > $member['house_room_member_start_date'] && $date_started > $member['house_room_member_end_date']){
                        $is_after_beta == false;
                   
                    }

                    if($date_started > $member['house_room_member_start_date'] && $date_started < $member['house_room_member_end_date'] && $member['house_room_member_deleted'] == 0){
                        $is_latest == true;
                    }

                }

                $date_range ;
                if($is_latest == true){
                    $date_range['date_started'] = $member['house_room_member_start_date'] ;
                    $date_range['date_ended'] = date('Y-m-d', strtotime('now'));
                }else{
                    $date_range['date_started'] = $member['house_room_member_start_date'] ;
                    $date_range['date_ended'] = $member['house_room_member_end_date'];
                }

                //test code
                if(count($payment_listing) > 0){
                    //dd($payment_listing);
                    
                    foreach ($payment_listing as $payment_model) {
                       
                       
                       //test code
                        //if(count($payment_model) > 0){
                           // dd($payment_listing);
                        //}
                    }
                }

                //echo "Room Id : ".json_encode($member['id_house_room'])." = Date_started : ".$date_started."|| Date range : ".json_encode($date_range)."<br>";

                $monthly_cut_off_listing = $is_after_beta == false ? null : Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
                $meter_register_model = $is_after_beta == false ? null :  DB::table('meter_registers')->where('leaf_room_id','=', $member['id_house_room'])->first();
                $subsidy_listing    = $is_after_beta == false ? null :  MeterPaymentReceived::get_user_subsidy_by_leaf_room_id_and_date_range($member['id_house_room'] , $date_range);

                
                
                if($monthly_cut_off_listing != null){
                    foreach($monthly_cut_off_listing as $monthly_cut_off)
                    {

                        $temp_reading;
                        $reading_listing ;
                        if(isset($meter_register_model->id)){
                           $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
                        }

                        if(!isset($reading_listing)){
                            //report email
                        }

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
                    }
                }   
                
             
                if(count($subsidy_listing) > 0){
                    foreach ($subsidy_listing as $row) {
                        $total_subsidy_amount += $row['total_amount'];
                    }   
                }


                $temp['month_usage_summary']   = isset($month_usage_summary) ? $month_usage_summary : 0;
                $temp['total_usage_kwh']       = $total_usage_kwh;
                $temp['total_payable_amount']  = $total_payable_amount;
                $temp['total_paid_amount']     = $total_paid_amount;
                $temp['total_subsidy_amount']  = $total_subsidy_amount;
                $temp['date_range']            = $date_range;
                $temp['leaf_room_id']          = $member['id_house_room'];
                $temp['leaf_house_id']         = $member['id_house'];

                array_push($user_account_detail,$temp);
   
        }

            //$user_account_detail['payment_listing'] = $payment_listingget_user_account_status_by_leaf_id_user_and_date_started;
            return $user_account_detail;
    }

    public static function get_minimum_credit_user(){
        return 0;
    }

    public static function get_user_account_status_by_leaf_room_id_and_date_range($leaf_room_id , $date_range , $id_house_member=null){
    
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id_and_date_range($leaf_room_id , $date_range);
           
        foreach($monthly_cut_off_listing as $monthly_cut_off){

            $temp;
            $reading_listing ;
            
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
            }

            if(!isset($reading_listing)){
                //report email
            }

            if($reading_listing != null){
                $total_usage = 0;
                foreach ($reading_listing as $row) {
                    $total_usage += $row['total_usage'];
                }

                $temp['total_usage_kwh'] = $total_usage;
                $temp['date'] = $monthly_cut_off['date_started'];
                $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
                $total_payable_amount +=   $temp['total_payable_amount'];
                $total_usage_kwh +=  $total_usage;
                array_push($month_usage_summary , $temp);
            } 
        }
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
            }   
        }

            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;

            return $result;
    }


    public static function getUserMonthlyUsage($leaf_room_id , $date_range , $customer_id=null){
    
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();

        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        
        foreach($monthly_cut_off_listing as $monthly_cut_off){

            $temp;
            $reading_listing ;
            
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
            }else{
                return null;
            }

            if(!isset($reading_listing)){
                //report email
            }

            if($reading_listing != null){
                $total_usage = 0;
                foreach ($reading_listing as $row) {
                    $total_usage += $row['total_usage'];
                }

                $temp['total_usage_kwh'] = $total_usage;
                $temp['date'] = $monthly_cut_off['date_started'];
                $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
                $total_payable_amount +=   $temp['total_payable_amount'];
                $total_usage_kwh +=  $total_usage;
                array_push($month_usage_summary , $temp);
            } 
        }
     
            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;

            return $result;
    }



    public static function get_user_balance_credit_by_leaf_room_id_and_date_range($leaf_room_id , $date_range , $customer_id=null){
    
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);
      
        foreach($monthly_cut_off_listing as $monthly_cut_off){

            $temp;
            $reading_listing ;
            
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
            }else{
                return null;
            }

            if(!isset($reading_listing)){
                //report email
            }

            if($reading_listing != null){
                $total_usage = 0;
                foreach ($reading_listing as $row) {
                    $total_usage += $row['total_usage'];
                }

                $temp['total_usage_kwh'] = $total_usage;
                $temp['date'] = $monthly_cut_off['date_started'];
                $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
                $total_payable_amount +=   $temp['total_payable_amount'];
                $total_usage_kwh +=  $total_usage;
                array_push($month_usage_summary , $temp);
            } 
        }
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
            }   
        }



            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;

            return $result;
    }




    public static function get_user_balance_credit_by_id_house_member_and_date_range($id_house_member , $date_range , $customer_id=null){
        
        //default variables
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();

        foreach ($account_status as $status) {
            //Need room name
            /*echo $status['leaf_room_id']."<br>";
           //echo $status['date_range']['date_started']."-".$status['date_range']['date_ended']."<br>";
           //echo " Monthly Usage";
            
           //echo "<table>";
           //echo "<th>Date</th> <th>Usage Kwh</th> <th>Amount RM</th>";

            foreach($status['month_usage_summary'] as $monthly_usage_summary){
               //echo "<tr><td>".$monthly_usage_summary['date']."</td> <td>".$monthly_usage_summary['total_usage_kwh']."</td> <td>".$monthly_usage_summary['total_payable_amount']."</td></tr>";
            }
           //echo "</table>";*/
           
        }

        $room_listing = LeafAPI::get_all_stayed_room_by_id_house_member($id_house_member);
        dd($room_listing);
        foreach ($room_listing as $room) 
        {
           //echo $room['id_house_room'].":".$date_range['date_started']."=".$date_range['date_ended']."<br>";

            $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
            $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
            $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);
          
            foreach($monthly_cut_off_listing as $monthly_cut_off){

                $temp;
                $reading_listing ;
                
                if(isset($meter_register_model->id)){
                   $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
                }

                if(!isset($reading_listing)){
                    //report email
                }

                if($reading_listing != null){
                    $total_usage = 0;
                    foreach ($reading_listing as $row) {
                        $total_usage += $row['total_usage'];
                    }

                    $temp['total_usage_kwh'] = $total_usage;
                    $temp['date'] = $monthly_cut_off['date_started'];
                    $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
                    $total_payable_amount +=   $temp['total_payable_amount'];
                    $total_usage_kwh +=  $total_usage;
                    array_push($month_usage_summary , $temp);
                } 
            }
         
            if(count($subsidy_listing) > 0){
                foreach ($subsidy_listing as $row) {
                    $total_subsidy_amount += $row['total_amount'];
                }   
            }


                $result['month_usage_summary']   += $month_usage_summary;
                $result['total_usage_kwh']       += $total_usage_kwh;
                $result['total_payable_amount']  += $total_payable_amount;
                $result['total_paid_amount']     += $total_paid_amount;
                $result['total_subsidy_amount']     += $total_subsidy_amount;

          
        }


        return $result;
        
        
    }


    public static function create_subsidy_meter_payment_received_model_manual($meter_subsidiary_id)
    {
        $target_date = '07-2021';
        echo 'ID :'.$meter_subsidiary_id."<br>";
        $meter_subsidiary_model = MeterSubsidiary::find($meter_subsidiary_id);
        $member;

        echo 'Subsidy detail :'.json_encode($meter_subsidiary_model)."<br>";
        if(!isset($meter_subsidiary_model['id'])){
            return;
        }
      
        $implement_date = $meter_subsidiary_model['implementation_date'].date('-m-Y', strtotime('now'));
        //specialCreate
        $month_year_to_redistribute = $target_date;
        $implement_date = $meter_subsidiary_model['implementation_date'].'-'.$month_year_to_redistribute;
        $subsidy_member_id_listing = static::get_remaining_subsidy_member_id_by_meter_subsidiary_id($meter_subsidiary_id , $month_year_to_redistribute);
        //dd($subsidy_member_id_listing);
        $member_listing = LeafAPI::get_member_detail_list(true);
        //dd($member_listing);
        //dd($subsidy_member_id_listing);

        Log::info('Create Subsidy For :'.$meter_subsidiary_model['name'].'['.$implement_date.']');


        echo 'Before Loop : ';
        echo json_encode($subsidy_member_id_listing)."<br> <br> <br>";
        //Compare first leaf data


        //start with pass item
       foreach ($subsidy_member_id_listing as $member_id) 
       {
            echo 'Member id :'.$member_id.'<br> <br>';
           //$member = LeafAPI::get_member_detail_by_member_id($member_id,true);
           $member = isset($member_listing[$member_id]) ? $member_listing[$member_id]['member'] : false;
           echo 'Pointed member :'.json_encode($member)."<br>";

           //to be improve
           $cpus_model = CustomerPowerUsageSummary::find_model_by_leaf_member_id($member['id_house_member']);

           if(!isset($cpus_model['id'])){
                Log::info('User do not have mobile app account :'.json_encode($member));
                continue; 
           }
           //else{ dd($cpus_model);}
           
           if($member == false)
           {
                Log::alert('Creating complementary , member id no-exist : '.$member_id);
           }
           echo 'Member detail :'.json_encode($member)."<br>";

           $tempModel = new MeterPaymentReceived();
           $tempModel['payment_method'] = static::label_subsidy;
            
           //specialCreate
           $tempModel['document_date'] = $implement_date;
          /* $amount = $meter_subsidiary_model['amount'];
           $room = LeafAPI::get_room_meter_by_leaf_room_id($member['leaf_room_id']);
           if(!isset($room['house'])){
                return ;
           }*/
        
           //$tempModel = new MeterPaymentReceived();

           foreach(static::model_to_cpus_mappers as $key => $cpus_key)
           {
                $tempModel[$key] = $cpus_model[$cpus_key];
           }

           foreach(static::model_to_subsidy_mappers as $key => $subsidy_key)
           {
                $tempModel[$key] = isset($meter_subsidiary_model[$subsidy_key]) ? $meter_subsidiary_model[$subsidy_key] : '' ;
           }

           foreach(static::subsidy_default_value_mappers as $key => $value)
           {
                $tempModel[$key] = $value;
           }

           $doc_series = $meter_subsidiary_model['code'].'-'.date('m-Y');
           //specialCreate
           $update_doc = $target_date;
           $doc_series = $meter_subsidiary_model['code'].'-'.$update_doc;
           
           $tempModel['document_no'] = $tempModel->gen_document_no_by_doc_series($doc_series);
           $tempModel['type'] = static::label_subsidy;
           $tempModel['remark'] = $meter_subsidiary_model['name'].'-'.$tempModel->setDouble($tempModel['total_amount']);
           //$tempModel['status'] = static::label_status_approved;
           echo 'Checking 1 :'."<br>";
           if(!$tempModel->id){
                $tempModel['created_by']       =   Auth::id() ? Auth::id():0;
                //specialCreate
                $tempModel['created_at']       =   date('Y-m-d', strtotime($implement_date));
                //$tempModel['created_at']       =   date('Y-m-d', strtotime('now'));

            }else{
               

                $tempModel['updated_at']       =   date('Y-m-d', strtotime('now'));
                $tempModel['updated_by']         = Auth::id() ? Auth::id():0;
                //$customer['leaf_group_id']    =   Company::get_group_id();
            }


  /*         created_by
updated_by
created_at
updated_at*/

           /*$meter_payment_received_model['meter_register_id'] = $room['meter']['id'];
           $meter_payment_received_model['total_amount'] = $amount;
           $meter_payment_received_model['gst_amount'] = $amount;
           $meter_payment_received_model['amount'] = $amount;
           $meter_payment_received_model['payment_amount'] = $amount;
           $meter_payment_received_model['house_name'] = $room['house']['house_unit'].'-'.$room['house_room_name'];       
           $meter_payment_received_model['leaf_house_id'] = $room['house']['id_house'];
           $meter_payment_received_model['leaf_room_id'] = $room['id_house_room'];
           $meter_payment_received_model['meter_register_id'] = isset($room['meter']['id']) ? $room['meter']['id'] : 0 ;
           $meter_payment_received_model['is_tax_inclusive'] = true;*/
           
          echo 'Checking 2 :'."<br>";
           $customer = Customer::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
           if(!isset($customer['id'])){
                $new_customer = new Customer();
                $new_customer->save_customer_from_leaf_house('',$member['id_house_member']);
                $customer = Customer::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
           }

           $tempModel['customer_id'] =  isset($customer['id']) ? $customer['id'] : 0;
           //$tempModel['leaf_group_id'] = 519;
           //$tempModel['leaf_group_id'] = 519;
           $tempModel['leaf_group_id'] = Company::get_group_id();
           echo '<br> <br><br><br>'.'Temp model before save: '.json_encode($tempModel).'<br>';

            $tempModel['reference_no'] = $tempModel['document_no'];
           $tempModel->save();
           
          Log::info('Create new sub success : '.json_encode($tempModel));
           /*$meter_payment_received_model['customer_name'] =   $customer['name'];
           $meter_payment_received_model['id_house_member'] = $customer['id_house_member'];
           $meter_payment_received_model['leaf_id_user'] = $customer['leaf_id_user'];*/
           //$tempModel->save_form($meter_payment_received_model);   
       }
    }


    const model_to_cpus_mappers = ['customer_power_usage_summary_id' => 'id','customer_name' => 'customer_name','leaf_room_id' => 'leaf_room_id','leaf_house_id' => 'leaf_house_id','id_house_member' => 'id_house_member','leaf_id_user' => 'leaf_id_user','house_name' => 'house_name','meter_register_id' => 'meter_register_id','currency_id' => 'currency_id','currency_code' => 'currency_code','currency_rate' => 'currency_rate'];
    //,'return_payment_date' => ''
    const subsidy_default_value_mappers = ['utransaction_id' => 0 ,'payment_gateway_reference_no' => ''  ,'return_payment' => 0 ,'ncl_id' => 0 ,'leaf_payment_id' => '' ,'is_tax_inclusive' => 0 ,'tax_type' => '' ,'tag_code' => '' ,'bank_code' => '' ,'payment_method' => '-' ,'payment_method_id' => 0 ,'status' => 1 ,'sales_person' => '' ,'gst_amount' => 0 ];
    const model_to_subsidy_mappers = ['meter_subsidiary_id' => 'id' ,'payment_amount' => 'amount' ,'total_amount' => 'amount'];

    public static function create_subsidy_meter_payment_received_model($meter_subsidiary_id)
    {
        echo 'ID :'.$meter_subsidiary_id."<br>";
        $meter_subsidiary_model = MeterSubsidiary::find($meter_subsidiary_id);
        $member;

        echo 'Subsidy detail :'.json_encode($meter_subsidiary_model)."<br>";
        if(!isset($meter_subsidiary_model['id'])){
            return;
        }
      
        $implement_date = $meter_subsidiary_model['implementation_date'].date('-m-Y', strtotime('now'));
        //specialCreate
        //$month_year_to_redistribute = '04-2021';
        //$implement_date = $meter_subsidiary_model['implementation_date'].'-'.$month_year_to_redistribute;
        $subsidy_member_id_listing = static::get_remaining_subsidy_member_id_by_meter_subsidiary_id($meter_subsidiary_id /*, $month_year_to_redistribute*/);
        $member_listing = LeafAPI::get_member_detail_list(true);
        //dd($member_listing);
        //dd($subsidy_member_id_listing);

        Log::info('Create Subsidy For :'.$meter_subsidiary_model['name'].'['.$implement_date.']');


        echo 'Before Loop : ';
        echo json_encode($subsidy_member_id_listing)."<br> <br> <br>";
       foreach ($subsidy_member_id_listing as $member_id) 
       {
            echo 'Member id :'.$member_id.'<br> <br>';
           //$member = LeafAPI::get_member_detail_by_member_id($member_id,true);
           $member = isset($member_listing[$member_id]) ? $member_listing[$member_id]['member'] : false;
           echo 'Pointed member :'.json_encode($member)."<br>";
           $cpus_model = CustomerPowerUsageSummary::find_model_by_leaf_member_id($member['id_house_member']);

           if(!isset($cpus_model['id'])){
                Log::info('User do not have mobile app account :'.json_encode($member));
                continue; 
           }
           //else{ dd($cpus_model);}
           
           if($member == false)
           {
                Log::alert('Creating complementary , member id no-exist : '.$member_id);
           }
           echo 'Member detail :'.json_encode($member)."<br>";

           $tempModel = new MeterPaymentReceived();
           $tempModel['payment_method'] = static::label_subsidy;
            
           //specialCreate
           $tempModel['document_date'] = $implement_date;
          /* $amount = $meter_subsidiary_model['amount'];
           $room = LeafAPI::get_room_meter_by_leaf_room_id($member['leaf_room_id']);
           if(!isset($room['house'])){
                return ;
           }*/
        
           //$tempModel = new MeterPaymentReceived();

           foreach(static::model_to_cpus_mappers as $key => $cpus_key)
           {
                $tempModel[$key] = $cpus_model[$cpus_key];
           }

           foreach(static::model_to_subsidy_mappers as $key => $subsidy_key)
           {
                $tempModel[$key] = isset($meter_subsidiary_model[$subsidy_key]) ? $meter_subsidiary_model[$subsidy_key] : '' ;
           }

           foreach(static::subsidy_default_value_mappers as $key => $value)
           {
                $tempModel[$key] = $value;
           }

           //specialCreate
           /*$update_doc = '04-2021';
           $doc_series = $meter_subsidiary_model['code'].'-'.$update_doc;*/
           $doc_series = $meter_subsidiary_model['code'].'-'.date('m-Y');
           $tempModel['document_no'] = $tempModel->gen_document_no_by_doc_series($doc_series);
           $tempModel['type'] = static::label_subsidy;
           $tempModel['remark'] = $meter_subsidiary_model['name'].'-'.$tempModel->setDouble($tempModel['total_amount']);
           //$tempModel['status'] = static::label_status_approved;
           echo 'Checking 1 :'."<br>";
           if(!$tempModel->id){
                $tempModel['created_by']       =   Auth::id() ? Auth::id():0;
                //specialCreate
                $tempModel['created_at']       =   date('Y-m-d', strtotime($implement_date));
                //$tempModel['created_at']       =   date('Y-m-d', strtotime('now'));

            }else{
               

                $tempModel['updated_at']       =   date('Y-m-d', strtotime('now'));
                $tempModel['updated_by']         = Auth::id() ? Auth::id():0;
                //$customer['leaf_group_id']    =   Company::get_group_id();
            }


  /*         created_by
updated_by
created_at
updated_at*/

           /*$meter_payment_received_model['meter_register_id'] = $room['meter']['id'];
           $meter_payment_received_model['total_amount'] = $amount;
           $meter_payment_received_model['gst_amount'] = $amount;
           $meter_payment_received_model['amount'] = $amount;
           $meter_payment_received_model['payment_amount'] = $amount;
           $meter_payment_received_model['house_name'] = $room['house']['house_unit'].'-'.$room['house_room_name'];       
           $meter_payment_received_model['leaf_house_id'] = $room['house']['id_house'];
           $meter_payment_received_model['leaf_room_id'] = $room['id_house_room'];
           $meter_payment_received_model['meter_register_id'] = isset($room['meter']['id']) ? $room['meter']['id'] : 0 ;
           $meter_payment_received_model['is_tax_inclusive'] = true;*/
           
          echo 'Checking 2 :'."<br>";
           $customer = Customer::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
           if(!isset($customer['id'])){
                $new_customer = new Customer();
                $new_customer->save_customer_from_leaf_house('',$member['id_house_member']);
                $customer = Customer::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
           }

           $tempModel['customer_id'] =  isset($customer['id']) ? $customer['id'] : 0;
           //$tempModel['leaf_group_id'] = 519;
           $tempModel['leaf_group_id'] = Company::get_group_id();
           echo '<br> <br><br><br>'.'Temp model before save: '.json_encode($tempModel).'<br>';

            $tempModel['reference_no'] = $tempModel['document_no'];
           $tempModel->save();
           
          Log::info('Create new sub success : '.json_encode($tempModel));
           /*$meter_payment_received_model['customer_name'] =   $customer['name'];
           $meter_payment_received_model['id_house_member'] = $customer['id_house_member'];
           $meter_payment_received_model['leaf_id_user'] = $customer['leaf_id_user'];*/
           //$tempModel->save_form($meter_payment_received_model);   
       }
    }


 
    public static function create_subsidy_meter_payment_received_model_patching($meter_subsidiary_id , $date_range_flag = null)
    {
        $meter_subsidiary_model = MeterSubsidiary::find($meter_subsidiary_id);
        $member;
        if(!isset($meter_subsidiary_model['id'])){
            return;
        }
        
        $period = MeterSubsidiary::get_subsidy_period($meter_subsidiary_model['starting_date'],$meter_subsidiary_model['ending_date']);

        echo "Start :".$meter_subsidiary_model['code']."=".$meter_subsidiary_model['amount']."<br>";
        echo $period."<br>";
        //echo "========================= <br>";
        for($i = 0 ; $i < $period ; $i++){

            $month_year  = date('Y-m', strtotime("+".$i." months", strtotime($meter_subsidiary_model['starting_date'])));
            //echo $month_year."-".$date_range_flag['starting_date']."<br>";
            //dd(strtotime($month_year) - strtotime($date_range_flag['starting_date']));
            //echo "========================= <br>";
            if(isset($date_range_flag))
            {
                if($month_year == $date_range_flag['ending_date']){
                    //echo "Terminated by ended date:".$month_year."Code :".$meter_subsidiary_model['code']."<br>";
                    break;
                }

                if(strtotime($month_year) - strtotime($date_range_flag['starting_date'])  < 0){
                    //echo "Less than starting:".$month_year."<br>";
                    $i++;
                    continue;
                }
            }
            //dd("Hit 2");
            $subsidy_member_id_listing = static::get_remaining_subsidy_member_id_by_meter_subsidiary_id($meter_subsidiary_id,$month_year);
            $implement_date = $month_year.'-'.$meter_subsidiary_model['implementation_date'];
            $doc_series = $meter_subsidiary_model['code'].'-'.$month_year."/";
            ////echo "a:".$month_year." b:".$implement_date.' c:'.$doc_series."<br>";
                  
           foreach ($subsidy_member_id_listing as $member_id) 
           {
               //echo $member_id."<br>";
               $member = LeafAPI::get_member_detail_by_member_id($member_id);
               $room = LeafAPI::get_room_meter_by_leaf_room_id($member['leaf_room_id']);
      
               if(!isset($member['id_house_member'])){
                    //echo "Not found :".$member_id."<br>";
                    continue ;
               }

               if(strtotime($month_year) - strtotime(date('Y-m',strtotime($member['house_room_member_start_date']))) < 0){
                    //echo $member['house_member_name']."=".date('Y-m',strtotime($member['house_room_member_start_date']))."<br>";
                    continue ;
               }

               $meter_payment_received_model = new MeterPaymentReceived();
               $meter_payment_received_model['payment_method'] = static::label_subsidy;        
               $meter_payment_received_model['document_date'] = $implement_date;
               $amount = $meter_subsidiary_model['amount'];
               $meter_payment_received_model['meter_register_id'] = $room['meter']['id'];
               $meter_payment_received_model['total_amount'] = $amount;
               $meter_payment_received_model['gst_amount'] = $amount;
               $meter_payment_received_model['amount'] = $amount;
               $meter_payment_received_model['payment_amount'] = $amount;
               $meter_payment_received_model['type'] = static::label_subsidy;
               $meter_payment_received_model['remark'] = $meter_subsidiary_model['name'].'-'.$meter_payment_received_model->setDouble($amount);
               $meter_payment_received_model['house_name'] = $room['house']['house_unit'].'-'.$room['house_room_name'];
               $meter_payment_received_model['document_no'] = $meter_payment_received_model->gen_document_no_by_doc_series($doc_series);

               $meter_payment_received_model['leaf_house_id'] = $room['house']['id_house'];
               $meter_payment_received_model['leaf_room_id'] = $room['id_house_room'];
               $meter_payment_received_model['meter_register_id'] = isset($room['meter']['id']) ? $room['meter']['id'] : 0 ;
               $meter_payment_received_model['is_tax_inclusive'] = true;
               $meter_payment_received_model['status'] = static::label_status_approved;
              
               $customer = Customer::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
               if(!isset($customer['id'])){
                    $new_customer = new Customer();
                    $new_customer->save_customer_from_leaf_house('',$member['id_house_member']);
                    $customer = Customer::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
               }

               $meter_payment_received_model['customer_id'] =  $customer['id'];
               $meter_payment_received_model['customer_name'] =   $customer['name'];
               $meter_payment_received_model['id_house_member'] = $customer['id_house_member'];
               $meter_payment_received_model['leaf_id_user'] = $customer['leaf_id_user'];
               $meter_payment_received_model->save_form($meter_payment_received_model);

           }
        }  
        //echo "End :".$meter_subsidiary_model['code']."<br>";
    }

   
    public static function get_remaining_subsidy_member_id_by_meter_subsidiary_id($meter_subsidiary_id,$month_year=null){
        
        $remaining_member_id_arr = array();
        $meter_subsidiary_model = MeterSubsidiary::find($meter_subsidiary_id);
        $subsidy_member_id_listing =json_decode($meter_subsidiary_model['subsidize_tenant_id']);
        echo 'Listed M count : '.count($subsidy_member_id_listing)."<br>";
        echo 'Listed member : '.json_encode($subsidy_member_id_listing)."<br>";
        $implement_date = isset($month_year) ?  date('Y-m-d', strtotime($meter_subsidiary_model['implementation_date'].'-'.$month_year ) ): date('Y-m-', strtotime('now')).$meter_subsidiary_model['implementation_date'];
        echo 'Implementation date :'.$implement_date."<br>";
        $susidied_member_listing = static::where('document_date','=' , $implement_date)
                            ->where('type', '=' , static::label_subsidy)
                            ->pluck('id_house_member')
                            ->toArray();

       foreach ($subsidy_member_id_listing as $member_id)
       {
           if (in_array($member_id, $susidied_member_listing)){
              continue;
           }else{
                array_push($remaining_member_id_arr, $member_id);
           }

        }
        echo 'Remaining : '.count($remaining_member_id_arr).' => '.json_encode($remaining_member_id_arr)."<br>";

        return $remaining_member_id_arr;
    }

    public static function createPrepaidPaymentReceivedByUserRoomDocumentDateAndPaidAmount($room,$document_date, $amount , $house){
         
           $meter_payment_received_model = new MeterPaymentReceived();
           $meter_payment_received_model['payment_method'] = "Sunway Auto Debit";
           $meter_payment_received_model['status'] = true;
           $meter_payment_received_model['document_date'] = $document_date;
           $meter_payment_received_model['meter_register_id'] = $room['meter']['id'];
           $meter_payment_received_model['total_amount'] = $amount;
           $meter_payment_received_model['gst_amount'] = $amount;
           $meter_payment_received_model['amount'] = $amount;
           $meter_payment_received_model['payment_amount'] = $amount;
           $meter_payment_received_model['type'] = "Deposit";
           $meter_payment_received_model['house_name'] = $house['house_unit'].'-'.$room['house_room_name'];
           $meter_payment_received_model['document_no'] = $meter_payment_received_model->gen_document_no();
           $meter_payment_received_model['leaf_house_id'] = $house['id_house'];
           $meter_payment_received_model['leaf_room_id'] = $room['id_house_room'];
           $meter_payment_received_model['type']        = static::label_subsidy;
           $roomMembers = $room['house_room_members'];

           foreach ($roomMembers as $member){
              $tempModel = new MeterPaymentReceived();
              $meter_payment_received_model['customer_id'] =  $member['house_member_id_user'];
              $meter_payment_received_model['customer_name'] =   $member['house_member_name'];
              $tempModel->save_form($meter_payment_received_model);
          }
    }

    public static function get_meter_payment_received_by_leaf_user_start_stay_detail_all($star_stay_detail , $leaf_group_id=null){
        //dd($star_stay_detail);
        $return = array();
        $date_range = isset($star_stay_detail['date_range']) ? $star_stay_detail['date_range'] : '';
       // dd($star_stay_detail);
        if(is_array($star_stay_detail)){
                foreach($star_stay_detail as $detail)
                {
                    if(!isset($detail['leaf_id_user'])){continue;}
                    $leaf_room_id = $detail['leaf_room_id'];
                    $temp     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                                         ->where('leaf_id_user' , '=' , $detail['leaf_id_user'])
                                         ->where('payment_method' , '!=' , static::label_subsidy)
                                         ->where('type' , '!=' , static::label_subsidy)
                                         //->where('document_date' , '>=' , $detail['start_date'])
                                         ->groupBy('reference_no')
                                         ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount','payment_gateway_reference_no')
                                         ->get();
                                         //echo json_encode($temp)."<br>";
                    foreach($temp as $row){  

                        if(  !in_array( $row['id'] , array_column($return, 'id')))
                        {
                            array_push($return,$row);
                        }   
                        
                    }
                }

                return $return;
        }
        //dd($return);

        
        //dd($return);
        if(isset($star_stay_detail['date_range'])){
            
            $temp     =   static::where('leaf_group_id','=',Setting::get_leaf_group_id($leaf_group_id))
                                     ->where('leaf_room_id' , '=' , $leaf_room_id)
                                     ->where('payment_method' , '!=' , static::label_subsidy)
                                     ->where('type' , '!=' , static::label_subsidy)
                                     ->whereBetween('document_date' , [ $date_range['date_started'] ,$date_range['date_ended']])
                                     ->groupBy('reference_no')
                                     ->select('id','leaf_room_id','meter_register_id','house_name','customer_id','customer_name','document_no','reference_no','document_date','total_amount')
                                     ->get();

            
        
        
            foreach($temp as $row){     
                if(!in_array(  $row['document_no'] , array_column( $return,  'document_no'))){
                    array_push($return ,$row);
                }
            }
            
        }

        return $return;
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

    public function gen_document_no_by_doc_series($doc_series)
    {
        $number = static::where('document_no','like', '%'.$doc_series."%")->count()+1;
        return $doc_series.'/'.str_pad($number, 5, 0, STR_PAD_LEFT);
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

    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'name'  && $key != 'customer_name'/*  && $key != 'products'  */) {
                    $this->$key =  $value;
                }else if($key == 'name' || $key == 'customer_name'){
                    $this->customer_name        =    $value;               
                }
            }

         
            if(!isset($this->status)){
                $this->status               =   static::label_status_progress;
            }

            $this->currency_code        =   $this->display_relationed('currency', 'symbol');
            $this->document_date        =   date('Y-m-d', strtotime($this->document_date));

            if(isset($input->document_no)){
                $this->document_no          =   $input->document_no;
             }else{
                $this->document_no          =   $this->id ? $this->document_no:$this->gen_document_no();
                
             }
           
            
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }
            
            $this->gst_amount           =   0;
            $this->save();

           /* $this->items()->delete();
            foreach ($input['ar_invoice'] as $row) {
                if ($row['ar_invoice_id']) {
                    $item = new MeterPaymentReceivedItem();
                    foreach ($row as $ikey => $ivalue) {
                        if ($ikey != 'description') {
                            $item->$ikey        =   $ivalue;
                        }
                    }
                    //$item->name         =   $this->display_relationed('product', 'name');
                    $item->description  =   $row['description'];
                    $this->items->save($item);
                    $this->amount           +=  $this->setDouble($row['amount']-$row['tax_txt']);
                    $this->gst_amount       +=  $this->setDouble($row['tax_txt']);
                }
            }
            $this->total_amount         =   $this->setDouble($this->amount+$this->gst_amount);
            $this->save();*/
          /*  if (!$this->updated_by) {
                $params['payment_received_header'][0]['type']                 =   $this->type;
                $params['payment_received_header'][0]['customer_recordid']    =   $this->display_relationed('customer', 'ncl_id');
                $params['payment_received_header'][0]['customer_code']        =   $this->display_relationed('customer', 'code');*/
                /*$params['payment_received_header'][0]['customer_id']          =   $this->customer_id;
                $params['payment_received_header'][0]['customer_code']        =   $this->name;*/
                /*$params['payment_received_header'][0]['currency_id']          =   $this->currency_id;
                $params['payment_received_header'][0]['currency_rate']        =   $this->currency_rate;*/

             /*   $params['payment_received_header'][0]['currency_code']        =   $this->currency_code;
                $params['payment_received_header'][0]['currency_rate']        =   $this->currency_rate;

                
                $params['payment_received_header'][0]['payment_amount']       =   $this->amount;
                $params['payment_received_header'][0]['remark']               =   $this->remark;
                $params['payment_received_header'][0]['date']                 =   $this->document_date;
                //$params['payment_received_header'][0]['bank_code']            =   $this->bank_code;
                $params['payment_received_header'][0]['deposit_to']           =   $this->deposit_to;
                $params['payment_received_header'][0]['reference_no']         =   $this->reference_no;
                $params['payment_received_header'][0]['payment_method']       =   $this->payment_method_id;
                 $params['payment_received_header'][0]['document_date']       =   $this->document_date;
                $params['payment_received_header'][0]['salesperson']          =   $this->sales_person;
                $params['payment_received_header'][0]['tax_type']             =   Company::get_is_inclusive() ? 'I':'E';*/
                //$params['payment_received_header'][0]['status']               =   $this->status ? 'active':'inactive';
              /*  $params['payment_received_header'][0]['tag_code']             =   'test-first';
                $i=1;
                foreach ($this->items as $row) {
                    $params['invoice_details'][]    =  [
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
                if ($result = $ncl_api->set_payment_receive($params, $ncl_id, 'sale')) {
                    DB::table('meter_payment_receiveds')->where('id','=',$this->id)->update(['ncl_id'=>$result['register_id']]);
                }
            }*/
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }

    public static function get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment($leaf_room_id , $date_range , $customer_id=null){
        
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);
           //dd($monthly_cut_off_listing);
        //adjustment code start --------------------------------------------------------------------
                $adjustment_months = ['2020-02','2020-03','2020-04'];
                $adjustment_months_period = ['2020-02'=>9,'2020-03'=>31,'2020-04'=>16];
                $adjustment_period = ['date_started'=>'2020-02-20' , 'date_ended'=>'2020-4-16'];
                $adjustment_days = round((strtotime($adjustment_period['date_ended']) - strtotime($adjustment_period['date_started']))/(60 * 60 * 24));
                $total_adjustment_usage = 0;
                $adjustment_period_usage_listing =  MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$adjustment_period);

                if(is_array($adjustment_period_usage_listing)){
                    foreach ($adjustment_period_usage_listing as $row) {
                            $total_adjustment_usage += $row['total_usage'];
                    }
                }
                
                $daily_adjustment_usage = $total_adjustment_usage/$adjustment_days;
                /*echo "<table border=1>";    
                echo "<tr>  <td>Adjusted month</td> <td>Adjusted period (day)</td> <td> Adjusted usage</td> </tr>";                 
                echo 'Daily average usage :'.$daily_adjustment_usage."<br>";*/
                //dd($adjustment_period_usage_listing);
        //adjustment code end --------------------------------------------------------------------
        foreach($monthly_cut_off_listing as $monthly_cut_off){

            $temp;
            $reading_listing ;
            if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'){
                if(date('Y-m-d' ,strtotime($date_range['date_started']))  > '2020-04-17'){
                    $monthly_cut_off['date_started'] = $date_range['date_started'];
                }else{  
                    $monthly_cut_off['date_started'] = '2020-04-17';
                }
            }
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
            }
            
            if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-02'){
                //dd($reading_listing);
            }
            if(!isset($reading_listing)){
                //report email
            }

            if($reading_listing != null || in_array(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , $adjustment_months, true)){
                $total_usage = 0;
                if($reading_listing != null){
                    foreach ($reading_listing as $row) {
                        $total_usage += $row['total_usage'];
                    }
                }
                
                /*  if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'){
                        dd($reading_listing);
                    }  */
                    
//dd($adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))]);
            //adjustment code
            if(in_array(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , $adjustment_months, true)){
                $adjusted_day = 999 ;
                //".$adjustment_months."
               /* echo "In adjust .<br>";
                echo 'checking get first : '.date('Y-m' ,strtotime($monthly_cut_off['date_started'])).' get second :'.date('Y-m' ,strtotime($date_range['date_started'])).' compare status :'.$date_range['date_started']."=".$adjustment_period['date_started'] .'Flag :'.($date_range['date_started'] > $adjustment_period['date_started'])."<br>";
                    
                echo "<tr>";  */  
                        //&&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-02'
                        if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-02'  ){
                if(  date('Y-m-d' , strtotime($date_range['date_started'])) >  date('Y-m-d' , strtotime($adjustment_period['date_started'])) &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-02'){
                        
                        $adjusted_day = 29 - date('d' ,strtotime($date_range['date_started'])) + 1;
                    }else if(  date('Y-m-d' , strtotime($date_range['date_ended'])) >  date('Y-m-d' , strtotime($adjustment_period['date_started'])) && $date_range['date_ended'] <= '2020-02-29' ){
                        
                        $adjusted_day = date('d' ,strtotime($date_range['date_ended'])) - date('d' ,strtotime($adjustment_period['date_started'])) + 1;
                    }
                    
                }else if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-03'  ){
                    if(  date('Y-m-d' , strtotime($date_range['date_ended'])) <  date('Y-m-d' , strtotime($adjustment_period['date_ended']))  &&  date('Y-m' ,strtotime($date_range['date_ended'])) == '2020-03' ){
                        $adjusted_day =  date('d' ,strtotime($date_range['date_ended'])) -  date('d' ,strtotime($monthly_cut_off['date_started'])) + 1 ;
                        
                    }else if(  date('Y-m-d' , strtotime($date_range['date_started'])) >  date('Y-m-d' , strtotime('2020-03-01')) &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-03'){
                        
                        $adjusted_day = 29 - date('d' ,strtotime($adjustment_period['date_started'])) + 1;
                    }
                    
                    
                    
                }else if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'  ){
                    
                    if( date('Y-m-d' , strtotime($date_range['date_started'])) > date('Y-m-d' , strtotime($adjustment_period['date_ended'])) ){
                        $adjusted_day = 0;
                        
                    }else if( date('Y-m-d' , strtotime($date_range['date_started'])) < date('Y-m-d' , strtotime($adjustment_period['date_ended']))  &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-04' ){
                        
                        $adjusted_day = date('d' , strtotime($adjustment_period['date_ended'])) -  date('d' ,strtotime($date_range['date_started'])) + 1;
                    }else if(  date('Y-m-d' , strtotime($date_range['date_ended'])) <  date('Y-m-d' , strtotime($adjustment_period['date_ended']))  &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-04' ){
                        
                        $adjusted_day = date('d' , strtotime($adjustment_period['date_ended']));
                    }
                    
                }
                
                        
                        /*echo 'Adjusted month '.date('Y-m' ,strtotime($monthly_cut_off['date_started'])).' = '.$adjusted_day.'<br>'; */
                        
                    if($adjusted_day == 999){
                            $adjusted_day = $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))];
                    }
                    
                    /*  if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'){
                        dd($total_usage);
                    } */ 
                    $total_usage += $daily_adjustment_usage * $adjusted_day;
                    
                   /* echo "<td>".date('Y-m' ,strtotime($monthly_cut_off['date_started']))."</td>";
                    echo '<td>'.$adjusted_day."</td>";
                    echo '<td>'.$daily_adjustment_usage * $adjusted_day."</td>";
                    echo "</tr>";*/
                    
            }
                

                $temp['total_usage_kwh'] = $total_usage;
                $temp['date'] = $monthly_cut_off['date_started'];
                $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
                $total_payable_amount +=   $temp['total_payable_amount'];
                $total_usage_kwh +=  $total_usage;
                array_push($month_usage_summary , $temp);
            } 
        }
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
            }   
        }


            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;
        /*echo "</table>";*/
            return $result;
    }


    

    public static function get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment_second_test($leaf_room_id , $date_range , $customer_id=null){
        
        $adjusted_day = 0;
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);
           //dd($monthly_cut_off_listing);
        $meter_register_id = $meter_register_model->id;   
        
           
           // Feb - Apr 2020 adjustment -----------------------------------------------------------------------------------------------
                    //New code to get dynamic period
                        /* $feb_2020_adjustment_date = DB::raw('SELECT meter_register_id,current_date FROM meter_readings where meter_register_id ='.$meter_register_id.' WHERE current_date like %2020-02-% order by current_date desc')->first();
                            dd($feb_2020_adjustment_date);
 */
            if($meter_register_model->adjustment_usage_days == null){
                            $feb_2020_adjustment_date = MeterReading::SELECT('meter_register_id','current_date')
                                                        
                                                        ->where('meter_register_id' , '=' , $meter_register_id)
                                                        ->where('current_date', 'like', '2020-02-%')
                                                        
                                                        ->orderByDesc('current_date')
                                                        ->first();
                    
                            $april_2020_adjustment_date = MeterReading::SELECT('meter_register_id','current_date')
                                                    
                                                        ->where('meter_register_id' , '=' , $meter_register_id)
                                                        ->where('current_date', 'like', '2020-04-%')
                                                        
                                                        ->orderBy('current_date')
                                                        ->first();
                        
                        $temp['feb2020'] = $feb_2020_adjustment_date['current_date'];
                        $temp['apr2020'] = $april_2020_adjustment_date['current_date'];
                        
                        $meter_register_model_2 = MeterRegister::find($meter_register_model->id);
                        $meter_register_model_2['adjustment_usage_days'] = json_encode($temp);
                        $meter_register_model_2->save();
            }else{
                    
                    $temp = json_decode($meter_register_model->adjustment_usage_days);
                    $temp =  (array) $temp;
                    //dd($temp);
                    //dd($temp['feb2020']);
                    $feb_2020_adjustment_date['current_date'] = $temp['feb2020'];
                    $april_2020_adjustment_date['current_date'] = $temp['apr2020'];
                    
            }
            
                        
                            //echo 'Last reading time :'.$feb_2020_adjustment_date['current_date'].'='.$april_2020_adjustment_date['current_date'].'<br>';
                            //dd($april_2020_adjustment_date['current_date']);
                            
                    //-----------------------------------------------------------------------------------------------------------------
                    
                    
        //adjustment code start --------------------------------------------------------------------
                $adjustment_months = ['2020-02','2020-03','2020-04'];
                $adjustment_months_period = ['2020-02'=>9,'2020-03'=>31,'2020-04'=>16];
                $adjustment_period = ['date_started'=>'2020-02-20' , 'date_ended'=>'2020-4-16'];
                
                $adjustment_days = 0;
                $total_adjustment_usage = 0;

                $adjustment_months = ['2020-02','2020-03','2020-04'];
            

                $missing_march_2020 = array();
                $feb_date_started = isset($feb_2020_adjustment_date['current_date']) ?  $feb_2020_adjustment_date['current_date'] : '2020-02-20' ;
                $april_date_ended = isset($april_2020_adjustment_date['current_date']) ? $april_2020_adjustment_date['current_date'] : '2020-4-16' ;    
                $adjustment_period = ['date_started'=> $feb_date_started , 'date_ended'=> $april_date_ended];
                $adjustment_days = round((strtotime($adjustment_period['date_ended']) - strtotime($adjustment_period['date_started']))/(60 * 60 * 24));
                $total_adjustment_usage = 0;
                $adjustment_period_usage_listing =  MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_id , $adjustment_period);
                
               //echo 'Adjustment period :'.json_encode($adjustment_period).'<br>';
                //dd($adjustment_period_usage_listing);
                if(count($adjustment_period_usage_listing) > 0){
                    //echo 'count pass :'.count($adjustment_period_usage_listing);
                    if(count($adjustment_period_usage_listing) == 1 ){
                        
                        $total_adjustment_usage += $adjustment_period_usage_listing[0]['total_usage'];
                        
                    }else if(count($adjustment_period_usage_listing) > 1)
                    {
                        foreach ($adjustment_period_usage_listing as $row) {
                            //echo 'value:'.$row['total_usage']."  >> ";
                            $total_adjustment_usage += $row['total_usage'];
                        }
                    }
                    
                }
                        
                
                $daily_adjustment_usage = $total_adjustment_usage/$adjustment_days ;
            

            
                        
                    $feb_adjusted_day = isset($feb_2020_adjustment_date['current_date']) ? 29 - date('d' , strtotime($feb_2020_adjustment_date['current_date'])) : 9;
                    $april_adjusted_day = isset($april_2020_adjustment_date['current_date']) ? date('d' , strtotime($april_2020_adjustment_date['current_date'])) /* - 1 */ : 9;
                    $adjustment_months_period = ['2020-02'=> $feb_adjusted_day,'2020-03'=>31,'2020-04'=> $april_adjusted_day];
                   //echo 'Adjustment days :'.json_encode($adjustment_months_period).'<br>';
                    $cut_off_adjustment_period = ['feb2020'=>  $feb_2020_adjustment_date['current_date'] ,'2020-03'=>31,'apr2020'=> $april_2020_adjustment_date['current_date'] ];
                   //echo 'Adjustment cut off days :'.json_encode($cut_off_adjustment_period).'<br>';
                    //$row = (array)$row;
        foreach($monthly_cut_off_listing as $monthly_cut_off){

            $temp;
            $reading_listing ;
            if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == date('Y-m' ,strtotime($april_2020_adjustment_date['current_date']))){
                if(date('Y-m-d' ,strtotime($date_range['date_started']))  > $april_2020_adjustment_date['current_date']){
                    $monthly_cut_off['date_started'] = $date_range['date_started'];
                    //dd($monthly_cut_off);
                }else{  
                    //$monthly_cut_off['date_started'] = '2020-04-17';
                    $monthly_cut_off['date_started'] = date('Y-m-d',strtotime($april_2020_adjustment_date['current_date'] . "+1 days"));
                    //$april_2020_adjustment_date['current_date'] ;
                    //dd($monthly_cut_off);
                }
            }
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
                if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'  ){
                  //echo 'April :'.json_encode($monthly_cut_off).'<br>';
                   //dd($reading_listing);
               } 
            }
            
            if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-02'){
                //dd($reading_listing);
            }
            if(!isset($reading_listing)){
                //report email
            }

            if($reading_listing != null || in_array(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , $adjustment_months, true))
            {
                        $total_usage = 0;
                        if($reading_listing != null){
                            foreach ($reading_listing as $row) {
                                $total_usage += $row['total_usage'];
                            }
                        }

                    if(in_array(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , $adjustment_months, true)){
                        $adjusted_day = 999 ;

                       //echo "In adjust .<br>";
                       //echo 'checking get first : '.date('Y-m' ,strtotime($monthly_cut_off['date_started'])).' get second :'.date('Y-m' ,strtotime($date_range['date_started'])).' compare status :'.$date_range['date_started']."=".$adjustment_period['date_started'] .'Flag :'.($date_range['date_started'] > $adjustment_period['date_started'])."<br>";
                            
                       //echo "<tr>";    
                                //&&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-02'
                        if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-02'  ){
                            if(  date('Y-m-d' , strtotime($date_range['date_started'])) >  date('Y-m-d' , strtotime($adjustment_period['date_started'])) &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-02'){
                                
                                $adjusted_day = 29 - date('d' ,strtotime($date_range['date_started'])) + 1;
                                
                            }else if(  date('Y-m-d' , strtotime($date_range['date_ended'])) >  date('Y-m-d' , strtotime($adjustment_period['date_started'])) && $date_range['date_ended'] <= '2020-02-29' ){
                                
                                $adjusted_day = date('d' ,strtotime($date_range['date_ended'])) - date('d' ,strtotime($adjustment_period['date_started'])) + 1;
                            }
                            
                        }else if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-03'  ){
                            if(  date('Y-m-d' , strtotime($date_range['date_ended'])) <  date('Y-m-d' , strtotime($adjustment_period['date_ended']))  &&  date('Y-m' ,strtotime($date_range['date_ended'])) == '2020-03' ){
                                $adjusted_day =  date('d' ,strtotime($date_range['date_ended'])) -  date('d' ,strtotime($monthly_cut_off['date_started'])) + 1 ;
                                
                            }else if(  date('Y-m-d' , strtotime($date_range['date_started'])) >  date('Y-m-d' , strtotime('2020-03-01')) &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-03'){
                                
                                $adjusted_day = 31 - date('d' ,strtotime($adjustment_period['date_started'])) + 1;
                            }
                            
                            
                            
                        }else if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'  ){
                            
                            if( date('Y-m-d' , strtotime($date_range['date_started'])) > date('Y-m-d' , strtotime($adjustment_period['date_ended'])) ){
                                $adjusted_day = 0;
                                
                            }else if( date('Y-m-d' , strtotime($date_range['date_started'])) < date('Y-m-d' , strtotime($adjustment_period['date_ended']))  &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-04' ){
                                
                                $adjusted_day = date('d' , strtotime($adjustment_period['date_ended'])) -  date('d' ,strtotime($date_range['date_started'])) + 1;
                            }else if(  date('Y-m-d' , strtotime($date_range['date_ended'])) <  date('Y-m-d' , strtotime($adjustment_period['date_ended']))  &&  date('Y-m' ,strtotime($date_range['date_started'])) == '2020-04' ){
                                
                                $adjusted_day = date('d' , strtotime($adjustment_period['date_ended']));
                            }
                            
                        }
                        
                                
                               //echo 'Adjusted month '.date('Y-m' ,strtotime($monthly_cut_off['date_started'])).' = '.$adjusted_day.'<br>'; 
                                
                            if($adjusted_day == 999){
                                    $adjusted_day = $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))];
                            }
                           //echo 'Adjusted month second : '.date('Y-m' ,strtotime($monthly_cut_off['date_started'])).' = '.$adjusted_day.'<br>'; 
                            /*  if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'){
                                dd($total_usage);
                            } */ 
                            $total_usage += $daily_adjustment_usage * $adjusted_day;
                            
                           //echo "<td>".date('Y-m' ,strtotime($monthly_cut_off['date_started']))."</td>";
                           //echo '<td>'.$adjusted_day."</td>";
                           //echo '<td>'.$daily_adjustment_usage * $adjusted_day."</td>";
                           //echo "</tr>";
                            
                    }       
                
            } 
            
            $temp['total_usage_kwh'] = $total_usage;
            $temp['date'] = $monthly_cut_off['date_started'];
            $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
            $total_payable_amount +=   $temp['total_payable_amount'];
           //echo 'Current bill :'.$monthly_cut_off['date_started'].' = '.$temp['total_payable_amount'].' Total : '.$total_payable_amount.'<br>';
            $total_usage_kwh +=  $total_usage;
            array_push($month_usage_summary , $temp);
        }
            

                    
            //-------------------------------------------------------------------------------------------------------------------------------


                
               //echo "<table border=1>";    
               //echo "<tr>  <td>Last Reading Feb : ".$feb_2020_adjustment_date['current_date']."</td> <td>First Reading Apr ".$april_2020_adjustment_date['current_date']."</td> <td> Ttl days : ".$adjusted_day." Average :".$daily_adjustment_usage."</td> </tr>";    
               //echo "<tr>  <td>Adjusted month</td> <td>Adjusted period (day)</td> <td> Adjusted usage</td> </tr>";                 
               //echo 'Daily average usage :'.$daily_adjustment_usage."<br>";
                //dd($adjustment_period_usage_listing);
        //adjustment code end --------------------------------------------------------------------
    
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
            }   
        }


            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;
       //echo "</table>";
            return $result;
    }
    
    public static function get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment_second($leaf_room_id , $date_range , $customer_id=null){
        //
        $adjustment_data =array();
        //
        $adjusted_day = 0;
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);

        $meter_register_id = $meter_register_model->id;   
        
           
           // Feb - Apr 2020 adjustment -----------------------------------------------------------------------------------------------
                    //New code to get dynamic period
    
 
            if($meter_register_model->adjustment_usage_days == null){
                            $feb_2020_adjustment_date = MeterReading::SELECT('meter_register_id','current_date')
                                                        
                                                        ->where('meter_register_id' , '=' , $meter_register_id)
                                                        ->where('current_date', 'like', '2020-02-%')
                                                        
                                                        ->orderByDesc('current_date')
                                                        ->first();
                    
                            $april_2020_adjustment_date = MeterReading::SELECT('meter_register_id','current_date')
                                                    
                                                        ->where('meter_register_id' , '=' , $meter_register_id)
                                                        ->where('current_date', 'like', '2020-04-%')
                                                        
                                                        ->orderBy('current_date')
                                                        ->first();
                        
                        $temp['feb2020'] = $feb_2020_adjustment_date['current_date'];
                        $temp['apr2020'] = $april_2020_adjustment_date['current_date'];
                        
                        $meter_register_model_2 = MeterRegister::find($meter_register_model->id);
                        $meter_register_model_2['adjustment_usage_days'] = json_encode($temp);
                        $meter_register_model_2->save();
            }else{
                    
                    $temp = json_decode($meter_register_model->adjustment_usage_days);
                    $temp =  (array) $temp;
                    $feb_2020_adjustment_date['current_date'] = $temp['feb2020'];
                    $april_2020_adjustment_date['current_date'] = $temp['apr2020'];
                    
            }

                    //-----------------------------------------------------------------------------------------------------------------
                    
                    
        //adjustment code start --------------------------------------------------------------------
                $adjustment_months = ['2020-02','2020-03','2020-04'];
                $adjustment_months_period = ['2020-02'=>9,'2020-03'=>31,'2020-04'=>16];
                $adjustment_period = ['date_started'=>'2020-02-20' , 'date_ended'=>'2020-4-16'];
                
                $adjustment_days = 0;
                $total_adjustment_usage = 0;
                
                $missing_march_2020 = array();  
                $feb_date_started = isset($feb_2020_adjustment_date['current_date']) ?  date('Y-m-' ,strtotime($feb_2020_adjustment_date['current_date'])).date('d' ,strtotime($feb_2020_adjustment_date['current_date'])): '2020-02-20' ;
                $april_date_ended = isset($april_2020_adjustment_date['current_date']) ? date('Y-m-' ,strtotime($april_2020_adjustment_date['current_date'])).date('d' ,strtotime($april_2020_adjustment_date['current_date'])) : '2020-4-16' ; 
                 
                $adjustment_period = ['date_started'=> $feb_date_started , 'date_ended'=> $april_date_ended];

                //$adjustment_days = (round((strtotime($adjustment_period['date_ended']) - strtotime($adjustment_period['date_started'])))/(60 * 60 * 24));
                //$adjustment_days = round( ( (strtotime($adjustment_period['date_ended']) - strtotime($adjustment_period['date_started'])) + 2 )/(60 * 60 * 24));
                $total_adjustment_usage = 0;
                
                $adjustment_period_usage_listing = DB::select('SELECT `meter_register_id` ,`current_date`, COUNT(*) as total_hours, AVG(current_usage) as average_usage, MAX(current_usage) as max_usage, MIN(current_usage) as min_usage, SUM(current_usage) as total_usage FROM `meter_readings` WHERE `current_date` >= ? AND `current_date` <= ? AND `meter_register_id` = ? GROUP BY `meter_register_id`,YEAR(`current_date`) ASC', [  $adjustment_period['date_started'] , $adjustment_period['date_ended'] , $meter_register_id]);
                
                if(count($adjustment_period_usage_listing) > 0){

                    if(count($adjustment_period_usage_listing) == 1 ){
                        
                        $total_adjustment_usage += $adjustment_period_usage_listing[0]->total_usage;
                        
                    }else if(count($adjustment_period_usage_listing) > 1)
                    {
                        foreach ($adjustment_period_usage_listing as $row) {

                            $total_adjustment_usage += $row->total_usage;
                        }
                    }
                    
                }
                                    
                //calculate total days
                $feb_adjusted_day = isset($feb_2020_adjustment_date['current_date']) ? 29 - date('d' , strtotime($feb_2020_adjustment_date['current_date'])) + 1 : 9;
                $april_adjusted_day = isset($april_2020_adjustment_date['current_date']) ? date('d' , strtotime($april_2020_adjustment_date['current_date'])) /* - 1 */ : 9;
                $adjustment_months_period = ['2020-02'=> $feb_adjusted_day,'2020-03'=>31,'2020-04'=> $april_adjusted_day];
                foreach($adjustment_months_period as $key => $value)
                {
                    //echo 'Adjustment '.$key.' :'.$value.'<br>';
                    $adjustment_days += (int) $value;
                }
                //echo 'Total days :'.$adjustment_days."<br>";
                $daily_adjustment_usage = $total_adjustment_usage/$adjustment_days ;
            

            
                        
                    
                    
                    //$cut_off_adjustment_period = ['feb2020'=>  $feb_2020_adjustment_date['current_date'] ,'2020-03'=>31,'apr2020'=> $april_2020_adjustment_date['current_date'] ];
                    $cut_off_adjustment_period = ['feb2020'=>  $feb_2020_adjustment_date['current_date'] ,'march2020'=>31,'apr2020'=> $april_2020_adjustment_date['current_date'] ];
                    
                    //$row = (array)$row;
                    
        foreach($monthly_cut_off_listing as $monthly_cut_off){
            $adjusted_day = 0 ;
            $total_usage = 0;
            //get adjustment date section
                    //Feb Section
                    if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-02'){
            
                        $monthly_cut_off['date_ended'] =  date('Y-m-' ,strtotime($adjustment_period['date_started'])).date('d' ,strtotime($adjustment_period['date_started']. "-1 days"));
                        $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 29 - date('d' ,strtotime($cut_off_adjustment_period['feb2020'])) + 1;
                        
                        if(date('Y-m' ,strtotime($date_range['date_started'])) == '2020-02' ){ // move in at feb
                            
                            //move in feb meaning cut off start from move in date
                            $monthly_cut_off['date_started'] =  date('Y-m-d' , strtotime($date_range['date_started'])); 
                            if(date('Y-m-d' ,strtotime($date_range['date_started'])) > date('Y-m-d' ,strtotime($adjustment_period['date_started'])) )
                            {//move in after adjustment 
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 29 - date('d' ,strtotime($date_range['date_started'])) + 1;
                            }else{//move in before adjustment
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 29 - date('d' ,strtotime($cut_off_adjustment_period['feb2020'])) + 1;
                            }

                        }


                        if(date('Y-m' ,strtotime($date_range['date_ended'])) == '2020-02' ){ // leave at feb

                            //move out meaning cut off end from move in date
                            $monthly_cut_off['date_ended'] =  date('Y-m-d' , strtotime($date_range['date_ended'])); 
                            
                            //leave before adjustment
                            if(date('Y-m-d' ,strtotime($date_range['date_ended'])) > date('Y-m-d' ,strtotime($adjustment_period['date_started']))  )
                            {
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 0;
                                
                            }else{ //leave during adjustment
                            
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = date('d' ,strtotime($date_range['date_ended'])) - date('d' ,strtotime($adjustment_period['date_started'])) + 1;
                            }                       

                        }
                        
                    }
                    
                    //March section
                    if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-03'){

                        $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 31;
                        
                        if(date('Y-m' ,strtotime($date_range['date_started'])) == '2020-03' ){ // move in at march
                            
                            //move in meaning cut off start from move in date
                            $monthly_cut_off['date_started'] =  date('Y-m-d' , strtotime($date_range['date_started'])); 
                            $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 31 - date('d' ,strtotime($date_range['date_started'])) + 1;

                        }

                        if(date('Y-m' ,strtotime($date_range['date_ended'])) == '2020-03' ){ // leave at march
                        
                            //move out meaning cut off end from move in date
                            $monthly_cut_off['date_ended'] =  date('Y-m-d' , strtotime($date_range['date_ended'])); 
                            $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] =  date('d' ,strtotime($date_range['date_ended']));                     

                        }
                        
                    }
            
                    //April Secton
                    if(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) == '2020-04'){
                        
                        $monthly_cut_off['date_started'] =  date('Y-m-d' , strtotime($adjustment_period['date_ended']. "+1 days")); 
                        $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = date('d' ,strtotime($cut_off_adjustment_period['apr2020']));
                            
                        if(date('Y-m' ,strtotime($date_range['date_started'])) == '2020-04' ){ // move in at april
                        
                            //move in meaning cut off start from move in date
                            $monthly_cut_off['date_started'] =  date('Y-m-d' , strtotime($date_range['date_started'])); 
                            
                            if(date('Y-m-d' ,strtotime($date_range['date_started'])) > date('Y-m-d' ,strtotime($adjustment_period['date_ended'])) )
                            {//move in after adjustment 
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 0;
                            }else{//move in before adjustment
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = date('d' ,strtotime($cut_off_adjustment_period['apr2020'])) - date('d' ,strtotime($date_range['date_started'])) /* + 1 */;
                            }

                        }

                        if(date('Y-m' ,strtotime($date_range['date_ended'])) == '2020-04' ){ // leave at april
                        
                            //move out meaning cut off end from move in date
                            $monthly_cut_off['date_ended'] =  date('Y-m-d' , strtotime($date_range['date_ended'])); 
                            
                            //leave before adjustment end
                            if(date('Y-m-d' ,strtotime($date_range['date_ended'])) > date('Y-m-d' ,strtotime($adjustment_period['date_started']))  )
                            {
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] =  date('d' ,strtotime($date_range['date_ended']));
                                
                            }else{//after during adjustment
                            
                                $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))] = 0;
                            }                       

                        }
    
                    }
            
            //cut of calculation second
            $temp;
            $reading_listing ;
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
        
            }

            if($reading_listing != null || in_array(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , $adjustment_months, true))
            {
                
                    $total_usage = 0;
                    if($reading_listing != null){
                        foreach ($reading_listing as $row) {
                            $total_usage += $row['total_usage'];
                        }
                    }

                    if(in_array(date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , $adjustment_months, true)){
            
                            $adjusted_day = $adjustment_months_period[date('Y-m' ,strtotime($monthly_cut_off['date_started']))];
                            
                            $temp_d = ['monthly_cut_off' => $monthly_cut_off    ,'reading_gap'=>$adjustment_period,'month' => date('Y-m' ,strtotime($monthly_cut_off['date_started'])) , 'adjusted_day' => $adjusted_day , 'usage' => $daily_adjustment_usage * $adjusted_day, 'from_reading' => $total_usage];
                            array_push( $adjustment_data , $temp_d);
                            
                            $total_usage += ($daily_adjustment_usage * $adjusted_day);
                                    
                    }       
                
            } 
            
            
            $temp['total_usage_kwh'] = $total_usage;
            $temp['date'] = $monthly_cut_off['date_started'];
            $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
            $total_payable_amount +=   $temp['total_payable_amount'];
            $total_usage_kwh +=  $total_usage;
            array_push($month_usage_summary , $temp);
            
            
            
        }
        
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
            }   
        }


            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;
        //echo "</table>";
            return $result;
    }
    

    public static function get_user_balance_credit_by_leaf_room_id_and_date_range_smVersion_live($leaf_room_id , $date_range , $customer_id=null){
        //
        $adjustment_data =array();
        //
        $current_subsidy_amount = 0;
        $adjusted_day = 0;
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);
        $meter_register_id = $meter_register_model->id;   
                           
        foreach($monthly_cut_off_listing as $monthly_cut_off){
           
    
            
            //cut of calculation second
            $temp;
            $reading_listing ;
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
        
            }



            
            $temp['total_usage_kwh'] = $total_usage;
            $temp['date'] = $monthly_cut_off['date_started'];
            $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
            $total_payable_amount +=   $temp['total_payable_amount'];
            $total_usage_kwh +=  $total_usage;
            array_push($month_usage_summary , $temp);
            
            
            
        }
        
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
                if(date('Y-m', strtotime($row['document_date']) == date('Y-m', strtotime('now'))))
                {
                    $current_subsidy_amount += $row['total_amount'];
                }
            }   
        }


            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;
            $result['current_subsidy_amount']   = $current_subsidy_amount;
            
        //echo "</table>";
            return $result;
    }

    public static function get_user_balance_credit_by_leaf_room_id_and_date_range_smVersion($leaf_room_id , $date_range , $customer_id=null){
        //
        $adjustment_data =array();
        //
        $current_subsidy_amount = 0;
        $adjusted_day = 0;
        $total_usage_kwh = 0;
        $total_payable_amount = 0;
        $total_paid_amount = 0 ;
        $total_subsidy_amount = 0;
        $month_usage_summary = array();
        $monthly_cut_off_listing = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=', $leaf_room_id)->first();
        $subsidy_listing    = MeterPaymentReceived::get_user_subsidy_by_leaf_room_id($leaf_room_id , $date_range);
        $meter_register_id = $meter_register_model->id;   
                           
        foreach($monthly_cut_off_listing as $monthly_cut_off){
            $adjusted_day = 0 ;
            $total_usage = 0;
    
            
            //cut of calculation second
            $temp;
            $reading_listing ;
            if(isset($meter_register_model->id)){
               $reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_register_model->id,$monthly_cut_off);
        
            }


            
            $temp['total_usage_kwh'] = $total_usage;
            $temp['date'] = $monthly_cut_off['date_started'];
            $temp['total_payable_amount'] = Setting::calculate_utility_fee($total_usage);
            $total_payable_amount +=   $temp['total_payable_amount'];
            $total_usage_kwh +=  $total_usage;
            array_push($month_usage_summary , $temp);
            
            
            
        }
        
     
        if(count($subsidy_listing) > 0){
            foreach ($subsidy_listing as $row) {
                $total_subsidy_amount += $row['total_amount'];
                if(date('Y-m', strtotime($row['document_date']) == date('Y-m', strtotime('now'))))
                {
                    $current_subsidy_amount += $row['total_amount'];
                }
            }   
        }


            $result['month_usage_summary']   = $month_usage_summary;
            $result['total_usage_kwh']       = $total_usage_kwh;
            $result['total_payable_amount']  = $total_payable_amount;
            $result['total_paid_amount']     = $total_paid_amount;
            $result['total_subsidy_amount']     = $total_subsidy_amount;
            $result['current_subsidy_amount']   = $current_subsidy_amount;
            
        //echo "</table>";
            return $result;
    }



    const not_null_fields = ['house_name' => '-'];

    //'id_house_member' => ''  ,'customer_name' => 'customer_name' ,
    const cpusModel_to_meter_payment_received_mapper = ['id' => 'customer_power_usage_summary_id' ,'leaf_room_id' => 'leaf_room_id' ,'leaf_house_id' => 'leaf_house_id' ,'leaf_id_user' => 'leaf_id_user' ,'house_name' => 'house_name' ,'meter_register_id' => 'meter_register_id' ,'customer_id' => 'customer_id'  , 'currency_id' => 'currency_id' ,'currency_code' => 'currency_code' ,'currency_rate' => 'currency_rate' ,'leaf_group_id' => 'leaf_group_id', 'id_house_member'=> 'id_house_member'];
    const utransaction_to_meter_payment_received_mapper = ['id' => 'utransaction_id' ,'customer_id' => 'customer_id' ,'leaf_house_id' => 'leaf_house_id' ,'leaf_room_id' => 'leaf_room_id' ,'customer_power_usage_summary_id' => 'customer_power_usage_summary_id' , 'leaf_payment_id' => 'leaf_payment_id' /*,'document_no' => ''*/ ,'reference_no' => 'reference_no' ,'document_date' => 'document_date' ,'description' => 'remark','leaf_id_user' => 'leaf_id_user'  ,'leaf_group_id' => 'leaf_group_id' , 'currency_id' => 'currency_id' ,'currency_code' => 'currency_code' , 'payment_method' => 'payment_method','meter_register_id' => 'meter_register_id' ,'payment_gateway_reference_no' => 'payment_gateway_reference_no' ,'customer_name' => 'customer_name' ];
    //,'amount' => 'amount' ,'total_amount' => 'amount' 
    // ,'payment_code' => '' ,'email' => '' ,'phone_no' => '' ,'customer_name' => 'customer_name' ,'shipping_fee_amount' => '' ,'store_id' => '' ,'is_tax_inclusive' => 'is_tax_inclusive' ,'is_sandbox' => '' ,'payment_items' => '' ,'payment_account_holder_name' => '' ,'payment_account_number' => '' ,'shipment_tracking_code' => '' ,'is_order_updated' => '' ,'is_send_success_notification' => '' ,'is_payment_processing' => '' ,'id_house_member' => ''
    //,'tax_class_id' => '' ,'tax_name' => '' ,'tax_percent' => '' ,'shipping_method' => '' ,'shipping_code' => '' ,
    // ,'payment_success_url' => '' ,'payment_failure_url' => '' ,'payment_cancel_url' => '' ,'payment_receipt_url' => '' ,'is_paid' => ''
    // ,'created_at' => '' ,'updated_at' => '' ,'ip_address' => '' ,'status' => '' ,
    // ,'user_id' => ''
    //,'transaction_charge_percent' => '' ,'transaction_charge' => '' ,'transaction_charge_gst_percent' => ''
    // ,'pay_by' => ''  ,'transaction_charge_gst' => '' ,'tax_amount' => '' ,'customer_group_id' => '' ,'' => '' ,currency_id
    const default_value_mapper = ['payment_method_id' => 0 , 'status' => 1 , 'sales_person' => '' , 'leaf_group_id' => 519];
    const amount_allocation_mapper = ['amount' => 'amount' , 'payment_amount' => 'total_amount' ,  'total_amount' => 'total_amount'];
    //const amount_allocation_mapper = ['payment_amount' => 'payment_total_amount' , 'amount' => 'payment_total_amount' ,  'total_amount' => 'payment_total_amount'];
    public static function saveOrUpdateModelByUtransactionModel($uTransactionModel , $customerPowerUsageSummaryModel)
    {   //dd('xx');
    //dd($uTransactionModel);
        if(!$uTransactionModel['is_paid'])
        {
            return false;
        }
//dd($uTransactionModel);
        //check for is created before
        $temp = static::get_model_by_leaf_payment_id_dev($uTransactionModel['leaf_payment_id']);
        //dd($temp);
        if($temp['id'])
        {

            //return false;
        }

        DB::beginTransaction();
        try {
            $model = isset($temp['id']) ? $temp : new MeterPaymentReceived();
            //dd($model);
            foreach (static::utransaction_to_meter_payment_received_mapper as $utransaction_key => $model_key)
            {

                 $model[$model_key] = isset($uTransactionModel[$utransaction_key]) ? ( is_array($uTransactionModel[$utransaction_key]) ? json_encode($uTransactionModel[$utransaction_key]) : $uTransactionModel[$utransaction_key] ) : '';
                   
            }

            foreach (static::cpusModel_to_meter_payment_received_mapper as $cpus_key => $model_key)
            {

                $is_need_to_add = true;
                if(is_integer($model[$model_key]))
                {
                    if($model[$model_key] == 0)
                    {
                        $model[$model_key] = isset($customerPowerUsageSummaryModel[$cpus_key]) ?   $customerPowerUsageSummaryModel[$cpus_key]  : 0;
                        $is_need_to_add = false;
                    }
                    
                }

                if( $is_need_to_add )
                {

                    $model[$model_key] = isset($customerPowerUsageSummaryModel[$utransaction_key]) ? ( is_array($customerPowerUsageSummaryModel[$cpus_key]) ? json_encode($customerPowerUsageSummaryModel[$cpus_key]) : $customerPowerUsageSummaryModel[$cpus_key] ) : '';

                }
            }
//dd($uTransactionModel);
            foreach (static::amount_allocation_mapper as $model_key => $utransaction_key)
            {

                $model[$model_key] = isset($uTransactionModel[$utransaction_key]) ?  $uTransactionModel[$utransaction_key]: 0;
            }

            foreach (static::default_value_mapper as $key => $value)
            {

                $model[$key] = $value;
            }

            $model['leaf_group_id'] = Company::get_group_id();


            if (!$model['id']) {
                $model['created_at']       =   date('Y-m-d h:m:s', strtotime('now')) ;
                $model['created_by']       =   Auth::id() ? Auth::id():0;
                $model['leaf_group_id']    =   Company::get_group_id();
            } else {
                $model['updated_at']       =   date('Y-m-d h:m:s', strtotime('now')) ;
                $model['updated_by']       =   Auth::id() ? Auth::id():0;
            }

            $doc_series =  date('m-Y');
            $model['document_no'] = $model->gen_document_no_by_doc_series($doc_series);
            $model['type'] = static::label_payment;
            //dd($uTransactionModel);
            $leaf_api = new LeafAPI();
            $result = $leaf_api->get_check_payment($uTransactionModel['leaf_payment_id']);
            if($result['status_code'])
            {
                $model['reference_no'] = $result['payment_identifier'];
                $model['payment_gateway_reference_no'] = $result['payment_identifier'];
                $model['document_date']       =   date('Y-m-d', strtotime($result['payment_entry_date'])) ;
            }else{
                $model['document_date']       =   date('Y-m-d', strtotime($uTransactionModel['created_at'])) ;
            }
            
            //dd($model);
            $model['document_date']       =   date('Y-m-d', strtotime($uTransactionModel['created_at'])) ;
//dd(date('Y-m-d', strtotime($uTransactionModel['payment_at'])));
            foreach (static::not_null_fields as $key => $value)
            {
                if($model[$key] == null)
                {
                    $model[$key] = $value;
                }
            }
  // dd( $model['document_date'] );
            $model->save();

            $uTransaction_update_key = ['is_checked' => true , 'is_order_updated' => true ];

            foreach($uTransaction_update_key as $key => $value)
            {
                $uTransactionModel[$key] = $value;
            }

            $uTransactionModel['payment_received_id'] = $model['id'] ;
    
            $uTransactionModel->save();

        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    
        return $model;

    }
}


