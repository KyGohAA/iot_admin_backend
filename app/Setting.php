<?php

namespace App;

use DB;
use Schema;
use Config;

use App\BackendData;
use App\NclAPI;
use App\MembershipModel\ARInvoice;
use App\MembershipModel\ARPaymentReceived;
use App\PowerMeterModel\MeterReading;
use DateTime;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Shared_Font;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_IOFactory;
use PHPExcel_Settings;


class Setting extends ExtendModel
{
	const lazy_load_item_increment = 20;
	const MOBILE_ICON_PATH		= '/leaf_acconting_mobile/img/icon/';
	const LABEL_STATUS_PROGRESS = 'IN PROGRESS';
    const LABEL_STATUS_APPROVED = 'APPROVED';
	const LANGUAGE_CODE_ARRY = array(0=> 'English',1=> 'Chinese Simplified',2=> 'Chinese Traditional',3=> 'Malay');
	// googlemap API key = AIzaSyAVDf0Zb4J1wRnsMZDTZv4nng9arKWP6oo
	const app_secret 		=	'oo238fj928u99odj92897t81726he87e8712t63183y';
	const cart_label 		=	'leaf_webview_cart';
	const wishlist_label 	=	'leaf_webview_wishlist';
	const update_flag 		=  	false;
	//Payment method
	const PAYMENT_METHOD_IPAY_88_WORD = "iPay88";
	const PAYMENT_METHOD_CASH_WORD = "Cash";
	const PAYMENT_METHOD_MOL_PAY_WORD = "MOLPay";
	const PAYMENT_METHOD_CREDIT_CARD_WORD = "Credit Card";
	const PAYMENT_METHOD_ETF_WORD = "ETF";
	const PAYMENT_METHOD_CHEQUE_WORD = "Cheque";
	const PAYMENT_METHOD_OTHERS_WORD = "Others";

	const PAYMENT_METHOD_IPAY_88 = "ipay88";
	const PAYMENT_METHOD_CASH = "cash";
	const PAYMENT_METHOD_MOL_PAY = "molpay";
	const PAYMENT_METHOD_CREDIT_CARD = "credit_card";
	const PAYMENT_METHOD_ETF = "etf";
	const PAYMENT_METHOD_CHEQUE = "cheque";
	const PAYMENT_METHOD_OTHERS = "other";

	const SUNWAY_MONTHLY_USAGE_REPORT = "sunway_monthly_usage_report";
	const SUNWAY_MONTHLY_SALES_REPORT = "sunway_monthly_sales_report";
 	const SUNWAY_SALES_REPORT = "sunway_sales_report";
 	const SUNWAY_ROOM_USAGE_REPORT = "sunway_room_usage_report";
 	const SUNWAY_NO_METER_FOUND_LABEL = "No reading is captured at the period.";
 	//const SUNWAY_NO_METER_FOUND_LABEL = "Meter is no yet registered in database or room";

	const DATA_TABLE_DEFAULT_RECORD_PER_PAGE	= 100;
	const DOUGNUT_CHART_BALANCE_COLOR_SAFE 		= "#00c0ef";
	const DOUGNUT_CHART_BALANCE_COLOR_ALERT 	= "#00c0ef";
	const DOUGNUT_CHART_BALANCE_COLOR_URGENT 	= "#00c0ef";
	const DOUGNUT_CHART_USAGE_COLOR 			= "#ff5959";
	const DOUGNUT_CHART_BALANCE_COLOR 			= "#23d806";
	const DOUGNUT_CHART_USAGE_COLOR_SAFE 		= "#3c8dbc";
	const DOUGNUT_CHART_USAGE_COLOR_ALERT 		= "#3c8dbc";
	const DOUGNUT_CHART_USAGE_COLOR_URGENT 		= "#3c8dbc";
	const CHARGING_RATE_200  					= '0.2180' ;
	const CHARGING_RATE_300  					= '0.3340' ;
	const CHARGING_RATE_600  					= '0.5160' ;
	const CHARGING_RATE_900  					= '0.5460' ;
	const CHARGING_RATE_900_ABOVE  				= '0.5710' ;
	const SUNWAY_LOGO_PATH 						= "\img\sunway_logo.png";
	const LATE_PAYMENT_PENALTY					= '0.01';
	const KWTBB_RATE							= '0.0016';
	const DEFAULT_GST_PERCENT					= '0.06';
	const NO_ROOM 								= "NO_ROOM";
	const NO_POWER_METER 						= "NO_POWER_METER";
	const SINGLE_ROOM 							= "Single Room";
	const TWIN_ROOM 							= "Twin Room";
	const AR_PAYMENT_RECEIVED 					= "AR_PAYMENT_RECEIVED";
	const AR_REFUND 							= "AR_REFUND";
	const AR_INVOICE 							= "AR_INVOICE";
	const SP_SETIA_PAYMENT_RECEIVED_WORKFLOW 	= ['Payee detail',/*'Payment type','Deposit To',*/'Trasaction Detail','Payment Detail','Payment Item'/*,'Check Detail'*/,'Confirmation'];
	const SP_SETIA_PAYMENT_RECEIVED_WORKFLOW_MOBILE 		= ['Payee Info And Payment Method','Payment Detail','Transaction Detail'];
	const SP_SETIA_INVOICING_WORKFLOW 						= ['Payee detail','Payment type','Deposit To','Payment Detail','Payment Item'];
	const SP_SETIA_PAYMENT_RECEIVED_WORKFLOW_STEP_1_ITEM	=  ['Payee detail','Payment type','Deposit To','Payment Detail','Payment Item','Check Detail'];
	const SP_SETIA_MEMBERSHIP_PAYMENT_RECEIVED_WORKFLOW_MOBILE =  ['1','2','3'];
	const SP_SETIA_PAYMENT_PORTAL_CANCEL_PAYMENT_URL = "";
	const SP_SETIA_PAYMENT_PORTAL_RETURN_PAYMENT_URL = "";
	const SP_SETIA_PRODUCT_PROGRAMME_LABEL = "programme";
	const SP_SETIA_PRODUCT_FACILITY_LABEL = "facility";
	const SP_SETIA_PRODUCT_FEE_TYPE_LABEL = "fee_type";
	const LANGUAGE_MALAY = "Malay";
	const LANGUAGE_ENGLISH = "English";
	const LANGUAGE_CHINESE_TRADITIONAL = "Chinese Traditional";
	const LANGUAGE_CHINESE_SIMPLIFIED = "Chinese Simplified";

	const SETIA_GROUP_ID = 285;
	const SUNWAY_GROUP_ID = 519;
	const SUNWAY_WATERFRONT_GROUP_ID = 519;
	const SUNWAY_GROUP_ID_ARR = [282,519];

	const SETIA_PRODUCT_DEFAULT_PHOTO = "img/upload/products/setia/";


	const TURN_OFF_PNG  = "/img/utility_charges/off.png";
	const TURN_ON_PNG  = "/img/utility_charges/on.png";
	const LOADING_GIF  = "/img/loading.gif";
	const UI_VERSION = "_version_02.";
	const UI_VERSION_UI = "_version_02";

	const LABEL_POWER_METER_OPERATIONAL_SETTING = "power_meter_operational_setting";
	const LABEL_MODULE_ACCOUNTING = "accounting";
	const LABEL_MODULE_ACCOUNTING_LEAF = "accounting_leaf";
	const LABEL_MODULE_POWER_MANAGEMENT = "power_management";
	const LABEL_MODULE_E_COMMERCE = "e_commerce";

	const LABEL_ACCOUNTING_SYSTEM_WINZ = "WINZ";
	const LABEL_ACCOUNTING_SYSTEM_NCL = "NCL";

	public static function array_msort($array, $cols)
	{
	    $colarr = array();
	    foreach ($cols as $col => $order) {
	        $colarr[$col] = array();
	        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
	    }
	    $eval = 'array_multisort(';
	    foreach ($cols as $col => $order) {
	        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
	    }
	    $eval = substr($eval,0,-1).');';
	    eval($eval);
	    $ret = array();
	    foreach ($colarr as $col => $arr) {
	        foreach ($arr as $k => $v) {
	            $k = substr($k,1);
	            if (!isset($ret[$k])) $ret[$k] = $array[$k];
	            $ret[$k][$col] = $array[$k][$col];
	        }
	    }
	    return $ret;

	}

	
	public static function compare_order($a, $b)
	{
	  return strtotime($a['house_room_member_start_date']) - strtotime($b['house_room_member_start_date']);
	}

	public static function asc_key_sort_by_function($array)
	{
		return usort($array, 'static::compare_by_timeStamp');
	}


	public static function compare_by_timeStamp_date_range_date_started($time1, $time2) 
	{ 
	    if (strtotime($time1['date_range']['date_started']) < strtotime($time2['date_range']['date_started'])) 
	        return 1; 
	    else if (strtotime($time1['date_range']['date_started']) > strtotime($time2['date_range']['date_started']))  
	        return -1; 
	    else
	        return 0; 
	} 

	public static function compare_by_timeStamp($time1, $time2) 
	{ 
	    if (strtotime($time1['house_room_member_start_date']) < strtotime($time2['house_room_member_start_date'])) 
	        return 1; 
	    else if (strtotime($time1['house_room_member_start_date']) > strtotime($time2['house_room_member_start_date']))  
	        return -1; 
	    else
	        return 0; 
	} 


	public static function compare_by_timeline($time1, $time2) 
	{ 
	    if (strtotime($time1['timeline_date']) > strtotime($time2['timeline_date'])) 
	        return 1; 
	    else if (strtotime($time1['timeline_date']) < strtotime($time2['timeline_date']))  
	        return -1; 
	    else
	        return 0; 
	}  

	public static function compare_by_created_at($time1, $time2) 
	{ 
	    if (strtotime($time1['created_at']) > strtotime($time2['created_at'])) 
	        return 1; 
	    else if (strtotime($time1['created_at']) < strtotime($time2['created_at']))  
	        return -1; 
	    else
	        return 0; 
	} 

	public static function compare_by_column($item_1, $item_2) 
	{ 

	    if ($item_1['result_type'] > $item_2['result_type']) 
	        return 1; 
	    else if ($item_1['result_type'] < $item_2['result_type'])  
	        return -1; 
	    else
	        return 0; 
	} 

	public static function compare_by_room_name($room1, $room2) 
	{ //echo json_encode($room1)."<br>";
	//cho json_encode($room2)."<br>";
	    if ($room1['house_room_name'] > $room2['house_room_name']) 
	        return 1; 
	    else if ($room1['house_room_name'] < $room2['house_room_name'])  
	        return -1; 
	    else
	        return 0; 
	} 

	public static function asc_key_sort(&$array, $key, $type=null) 
	{
	    $sorter=array();
	    $ret=array();
	    reset($array);

	    foreach ($array as $ii => $va) {
	        $sorter[]=$va[$key];
	    }
	}

	public static function ar_sort($sorter)
	{
	    foreach ($sorter as $ii => $va) {
	        $ret[]=$array[$ii];
	    }

	    return $array=$ret;
	}

	public static function calculate_age($dob, $delimeter=null)
	{ 
		return  $dob == '0000-00-00' ? 0 : floor((time() - strtotime($dob)) / 31556926);
	}


	const CODE_SUNMED_IPAY88 = 'ipay88_sunmed';
	const CODE_SUNMED_WATERFRONT_IPAY88 = 'ipay88_sunwaymonashwaterfront';
	const CODE_MONASH_IPAY88 = 'ipay88_sunwaymonash';
	const CODE_LEAF_IPAY88   = 'ipay88';

	const LABEL_MONASH_IPAY88 = 'Sunway Monash University IPay88 Account';
	const LABEL_SUNMED_IPAY88 = 'Sunmed Residential IPay88 Account';
	const LABEL_SUNMED_WATERFRONT_IPAY88 = 'Sunway Water Front Residential IPay88 Account';
	const LABEL_LEAF_IPAY88   = 'LEAF Default IPay88 Account';

	public static function getPaymentGatewayAccountHolderName($payment_gateway)
	{
		$temp = static::payment_gateway_combobox();
		return $temp[$payment_gateway];
	}

	public static function status_combobox()
	{
		return [true=>'Yes',false=>'No'];
	}

	public static function payment_gateway_combobox()
	{
		return [static::CODE_SUNMED_IPAY88=>static::LABEL_SUNMED_IPAY88,static::CODE_MONASH_IPAY88=>static::LABEL_MONASH_IPAY88,static::CODE_LEAF_IPAY88=>static::LABEL_LEAF_IPAY88,static::CODE_SUNMED_WATERFRONT_IPAY88=>static::LABEL_SUNMED_WATERFRONT_IPAY88];
	}
	

	const LABEL_HOUSE_ROOM_DATA = 'House Room Data'; 
	public static function data_type_combobox()
	{
		return [static::LABEL_HOUSE_ROOM_DATA=>static::LABEL_HOUSE_ROOM_DATA];
	}
	

	public static function integrated_accounting_system_combobox()
	{
		return [static::LABEL_ACCOUNTING_SYSTEM_WINZ=>static::LABEL_ACCOUNTING_SYSTEM_WINZ,static::LABEL_ACCOUNTING_SYSTEM_NCL=>static::LABEL_ACCOUNTING_SYSTEM_NCL];
	}
	
	public static function module_combobox()
	{
		return ['accounting'=>'Accounting','power_management'=>'Power Management','e_commerce'=>'E-Commerce'];
	}

	public static function get_sunway_logo_path(){

		return public_path(static::SUNWAY_LOGO_PATH);
	}

	public static function get_system_language_array(){

		return ['english' =>static::LANGUAGE_ENGLISH ,'Malay'=>static::LANGUAGE_MALAY,'chinese_simplified'=>static::LANGUAGE_CHINESE_SIMPLIFIED,'chinese_traditional'=>static::LANGUAGE_CHINESE_TRADITIONAL];
	}

	

	public static function get_chat_link($leaf_acc)
	{
		// return 'http://cloud.leaf.com.my/web/chat?id_user='.$leaf_acc;
		return 'https://cloud.leaf.com.my/web/chat.php?id_user='.$leaf_acc.'&initial_text=Hi';
	}

	public static function get_setia_payment_success_url()
	{
		return static::SP_SETIA_PAYMENT_PORTAL_RETURN_PAYMENT_URL;
	}

	public static function get_setia_payment_cancel_url()
	{
		return static::SP_SETIA_PAYMENT_PORTAL_CANCEL_PAYMENT_URL;
	}

	public static  function set_dynamic_connection($schema_name=null)
    {
    	$schema_name = isset($schema_name) ? $schema_name :   'mysql';
        DB::setDefaultConnection($schema_name);
    }

	public static function get_notification_by_leaf_group_id($leaf_group_id=null)
	{
		$leaf_group_id = Setting::get_leaf_group_id($leaf_group_id);
		$company = Company::get_model_by_leaf_group_id($leaf_group_id);
		$selected_module = json_decode($company['selected_module']);
		$notification_array = array();
		$msg = "";
		$temp_counter = 0;


		if($temp_counter = Customer::get_today_new_record() > 0){
			$msg = $temp_counter.' '.Language::trans("customer record(s) created.");
			array_push($notification_array, $msg);
		}

		if(isset($selected_module)){
			if(count($selected_module) > 0){
				
				foreach ($selected_module as $module) {
					
					if(strpos($module, static::LABEL_MODULE_ACCOUNTING) !== false){
						if(($temp_counter = ARPaymentReceived::get_today_new_record()) > 0){
							$msg = $temp_counter.' '.Language::trans("payment(s) received.");
							array_push($notification_array, $msg);
						}

						if(($temp_counter = ARInvoice::get_today_new_record()) > 0){
							$msg = $temp_counter.' '.Language::trans("Invoice(s) created.");
							array_push($notification_array, $msg);
						}

						if(($temp_counter = Ticket::get_today_new_record()) > 0){
							$msg = $temp_counter.' '.Language::trans("ticket(s) received.");
							array_push($notification_array, $msg);
						}
				
				
					}

					if(strpos($module, static::LABEL_MODULE_POWER_MANAGEMENT) !== false){

					}

					if(strpos($module, static::LABEL_MODULE_E_COMMERCE) !== false){

					}

				
				}
			}
		}
		

		return $notification_array;

	}

	//Here to manage of Common UI data formation

	/*
    |--------------------------------------------------------------------------
    | Here to manage of comobobox element
    |--------------------------------------------------------------------------
    |
    */
	
	public static function get_area_chart_data_by_leaf_groud_id($leaf_group_id)
    {
            $leaf_group_id       = isset($leaf_group_id) ? $leaf_group_id : Company::get_group_id();
            $backend_data_model 		 = BackendData::get_model_by_leaf_group_id($leaf_group_id);
            $monthly_data = "";
            $daily_data   = "";

            $current_date = date('Y-m-d', strtotime('now'));
            $date_ended   = $current_date;
            //$date_ended = '2018-08-12';
            $date_started = date('Y-m-d',strtotime($date_ended.'- 30 days'));

            $month_ended   = $current_date;
            $month_started = date('Y-m-d',strtotime($month_ended.'- 12 months'));
            

            $monthly_date_range = ['date_started'=> $month_started, 'date_ended' => $month_ended];
            $daily_date_range = ['date_started'=> $date_started, 'date_ended' => $date_ended];
          
            $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not found.'),	
                    'data'   =>  [],
                    ];


            //$leaf_group_id_2 = in_array($leaf_group_id, static::SUNWAY_GROUP_ID_ARR) ? 282 : $leaf_group_id;

            switch ($leaf_group_id) {

                        case static::SETIA_GROUP_ID:
                            $monthly_data = ARPaymentReceived::get_monthly_transaction_data_by_leaf_group_id_and_date_range( $leaf_group_id , $monthly_date_range);    
                            $daily_data = ARPaymentReceived::get_daily_reading_summary_by_group_id_and_date_range( $leaf_group_id , $monthly_date_range);                            
                            break;

                        case static::SUNWAY_GROUP_ID :

                            $is_need_update = false;

                            $monthly_data;
                            $daily_data;
                            
                            if(isset($backend_data_model['id']))
                            {
                            	
                            	$monthly_data = json_decode($backend_data_model['monthly_usage_summary']);
                            	$daily_data = json_decode($backend_data_model['daily_usage_summary']);
                            	if(!is_array($daily_data))
                            	{
                            		$is_need_update = true;
                            	}else{
                            		if(count($daily_data) == 0)
                            		{
                            			$is_need_update = true;
                            		}
                            	}
                            }else{
                            	$is_need_update = true;
                            	$backend_data_model = new BackendData();
                            }

                            //$is_need_update = Setting::update_flag;
                            $is_need_update = true;
                            if($is_need_update == true)
                            {

                            	$monthly_data = MeterReading::get_monthly_reading_summary_by_group_id_and_date_range($leaf_group_id,$monthly_date_range);
                            	$daily_data = MeterReading::get_daily_reading_summary_by_group_id_and_date_range($leaf_group_id,$daily_date_range);

                            	$backend_data_model['monthly_usage_summary'] = json_encode($monthly_data);
                            	$backend_data_model['daily_usage_summary'] = json_encode($daily_data);
                            	$backend_data_model->save();
                            }
 
                            //$daily_data = ARPaymentReceived::get_daily_transaction_data_by_leaf_group_id($date_started,$date_ended,$leaf_group_id);   
                            //$monthly_data = ARPaymentReceived::get_monthly_transaction_data_by_leaf_group_id($month_started,$month_ended,$group_id);                             
                            break;
                        
                        default:
                            
                            break;
            }        

		    $label_data = array();
		    $data_set = array();
		    	//dd($daily_data);
		    if(isset($monthly_data)){
		    	if($monthly_data != ''){

			        foreach ($monthly_data as $data) {
				        array_push($label_data, $data->date_time);
				        array_push($data_set, $data->total_amount);
			        }

			        $fdata['status_code'] = true;
					$fdata['monthly_data']['labels'] = $label_data ;
					$fdata['monthly_data']['datasets'][0] = 
					[
						'label'               => 'Electronics',
						'fillColor'           => 'rgba(210, 214, 222, 1)',
						'strokeColor'         => 'rgba(210, 214, 222, 1)',
						'pointColor'          => 'rgba(210, 214, 222, 1)',
						'pointStrokeColor'    => '#c1c7d1',
						'pointHighlightFill'  => '#fff',

						'pointHighlightStroke'=> 'rgba(220,220,220,1)',
						'data'                => $data_set
					];
		        }
   			}

   			$daily_label_data = array();
   			$daily_data_set = array();
   			if(isset($daily_data)){
		    	if($daily_data != ''){
			        foreach ($daily_data as $data) {
				        array_push($daily_label_data, $data->date_time);
				        array_push($daily_data_set, $data->total_amount);
			        }

			        $fdata['status_code'] = true;
					$fdata['daily_data']['labels'] = $daily_label_data ;
					$fdata['daily_data']['datasets'][0] = 
					[
						'label'               => 'Electronics',
						'fillColor'           => 'rgba(210, 214, 222, 1)',
						'strokeColor'         => 'rgba(210, 214, 222, 1)',
						'pointColor'          => 'rgba(210, 214, 222, 1)',
						'pointStrokeColor'    => '#c1c7d1',
						'pointHighlightFill'  => '#fff',
						'pointHighlightStroke'=> 'rgba(220,220,220,1)',
						'data'                => $daily_data_set
					];
		        }
   			}
                   
            return json_encode($fdata);
    }
  


    /*
    |--------------------------------------------------------------------------
    | Here to manage of comobobox element
    |--------------------------------------------------------------------------
    |
    */
	public static function costing_method()
	{
		return [''=>'Please select costing method...','fifo'=>'FIFO','standard'=>'Standard','average'=>'Average'];
	}

	public static function payment_received_type()
	{
		return [''=>'Payment received type...','invoice_receipt'=>'Invoice Receiept','security_deposit'=>'Security Deposit'];
	}

	public static function payment_method()
	{
		return [''=>'Payment method...','cheque'=>'Cheque','credit_card'=>'Credit card' , 'cash'=>'Cash' , 'eft'=> 'EFT' , 'others' => 'Others'];
	}

	public static function convert_payment_method_to_ncl_payment_method($payment_method)
	{
		return str_replace('_', ' ', ucwords($payment_method));
	}

	public static function bank_or_cash_combobox(){
		
		$NclApi = new NclAPI();
		$array = array();
		$listing = $NclApi->get_bank_account_list();
		 foreach ($listing['listing'] as $key => $value) {
			$item = $value['code'].'   '.$value['name'];
			$array[$value['code']] = $item;
			//array_push($array , $item);
        }

		return json_decode(json_encode($array)) ;
	}

	public static function gl_account_combobox(){
		
		$NclApi = new NclAPI();
		$array = array();
		$listing = $NclApi->get_gl_account_list();
		 foreach ($listing['listing'] as $key => $value) {
			$item = $value['code'].'   '.$value['name'];
			array_push($array , $item);
        }

		return json_decode(json_encode($array)) ;
	}

	public static function select_days_combobox(){

		$days = [];
		for($i = 1; $i <= 31; $i++){
		    $val = ($i < 10) ? '0'.$i : $i;
		    $days[$val] = $val;
		}

		return $days;
	}

	public static function room_type_combobox()
	{
		return ['single'=>static::SINGLE_ROOM,'twin'=>static::TWIN_ROOM];
	}

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Power Management Element
    |--------------------------------------------------------------------------
    |
    */
    public static function get_month_in_word($month_integer)
    {
    	if($month_integer > 0 && $month_integer <= 12)
    	{
    		$date_object   = DateTime::createFromFormat('!m', $month_integer);
			$month_in_word = $date_object->format('F'); 
			return $month_in_word;
    	}

    	return "";
    }

	public static function photo_place($value)
	{
		if ($value) {
			return $value;
		}
		return 'https://cloud.leaf.com.my/web/images/no_profile.jpg';
	}

	public function convert_encoding($string)
	{
		return $string;
    }
  
	public static function electric_meter_photo()
    {
		return "{{asset('/img/electric_meter.png')}}";
	}

	public static function googlemap_key()
	{
		return 'AIzaSyA9Xcjhr2TvXv7sGzcQXZCrE4pcZ_aDI6w';
	}

	public static function version()
	{
		return '1.3.1.1';
	}

	public static function error_404()
	{
		return 'error 404';
	}

	public static function convert_percentage($percent)
	{
		return 100/str_replace('%', '', $percent);
	}

	public static function gst_calculate($percentage, $amount)
	{
		if (str_contains($percentage, '%')) {
			$percentage = self::convert_percentage($percentage);
		}
		$total = $amount*$percentage;
		if (Company::get_is_inclusive()) {
			$inclu_percentage = 1+$percentage;
			$total = $total/$inclu_percentage;
		}
		return $total;
	}

	public static function gst_amount_calculate($percentage, $amount)
	{
		if (str_contains($percentage, '%')) {
			$percentage = self::convert_percentage($percentage);
		}
		$total = $amount;
		if (Company::get_is_inclusive()) {
			$inclu_percentage = 1+$percentage;
			$total = $total/$inclu_percentage;
		}
		return $total;
	}

	public static function power_meter_reading_status_combobox()
	{
		return ['Normal'=>'Normal','Extreme Value - bigger reading'=>'Extreme Value - bigger reading','Extreme Value - smaller reading'=>'Extreme Value - smaller reading'];
	}

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Power management method
    |--------------------------------------------------------------------------
    |
    */
	public static function is_negative($value)
	{
		if (isset($value)){
		    if (substr(strval($value), 0, 1) == "-"){
		   	     return true;
			}else {
			    return false;
			}
		}
	}

	public static function usage_bar_chart_setting()
	{
 
 		$chart_setting  = array(
 			'title' =>  "Average Per Day (kWh)"  , 
 			'dougnut_chart_balace_color' =>  Setting::DOUGNUT_CHART_BALANCE_COLOR  , 
 			'dougnut_chart_usage_color' =>  Setting::DOUGNUT_CHART_USAGE_COLOR  , 
 			'hAxis_title' =>  'Date', 
 			'hAxis_titleTextStyle' =>  '#333', 
 			'vAxis_min' =>  0, 
 			'height' =>  300);
 
 		return $chart_setting;
 	}

	public static function get_current_chargable_datetime()
	{

 		return $datetime;
 	}

 	public static function calculate_utility_fee($usage)
 	{
 		if(Company::get_group_id() != static::SUNWAY_WATERFRONT_GROUP_ID)
 		{
 			$charges = Setting::calculate_utility_fee_tnb_charging($usage);
 			return $charges;
 		}

 		$amount = 0;
 		$stage = 0;
 		$usageToCount = 0;
 		$accAmount = 0;
 		do{
 			if($usage > 901){
				//$stage = 5;
				$stage = 2;
				$amountToDeduct = 900;	
 			/*}else if($usage > 601){
 				$stage = 4;
				$amountToDeduct = 600;
 			}else if($usage > 301){
 				$stage = 3;
				$amountToDeduct = 300;
 			}else if($usage > 201){
 				$stage = 2;
				$amountToDeduct = 200;*/
 			}else{
 				$stage = 1;
				$amountToDeduct = 0;
 			}

 			$usageToCount = $usage - $amountToDeduct;
 			$usage =$usage - $usageToCount;

 			if($stage == 1){
 				//$amount +=  $usageToCount * 0.2180;
 				$amount +=  $usageToCount * 0.365;
 			}else if($stage == 2){
 				//$amount +=  $usageToCount * 0.3340;
 				$amount +=  $usageToCount * 0.365;
 			}/*else if($stage == 3){
 				$amount +=  $usageToCount * 0.5160;
 			}else if($stage == 4){
 				$amount +=  $usageToCount * 0.5460;
 			}else if($stage == 5){

 				$amount +=  $usageToCount * 0.5710;
 			}*/

 		}while($usage > 0);
 		
 		return round($amount,2);
 	}

 	public static function calculate_utility_fee_tnb_charging($usage)
 	{
 		$amount = 0;
 		$stage = 0;
 		$usageToCount = 0;
 		$accAmount = 0;
 		do{
 			if($usage > 901){
				$stage = 5;
				$amountToDeduct = 900;	
 			}else if($usage > 601){
 				$stage = 4;
				$amountToDeduct = 600;
 			}else if($usage > 301){
 				$stage = 3;
				$amountToDeduct = 300;
 			}else if($usage > 201){
 				$stage = 2;
				$amountToDeduct = 200;
 			}else{
 				$stage = 1;
				$amountToDeduct = 0;
 			}

 			$usageToCount = $usage - $amountToDeduct;
 			$usage =$usage - $usageToCount;

 			if($stage == 1){
 				$amount +=  $usageToCount * 0.2180;
 			}else if($stage == 2){
 				$amount +=  $usageToCount * 0.3340;
 			}else if($stage == 3){
 				$amount +=  $usageToCount * 0.5160;
 			}else if($stage == 4){
 				$amount +=  $usageToCount * 0.5460;
 			}else if($stage == 5){
 				$amount +=  $usageToCount * 0.5710;
 			}

 		}while($usage > 0);
 		
 		return round($amount,2);

 	}

 	public static function generate_billing_charges_item($usage)
 	{
 		$amount = 0;
 		$stage = 0;
 		$usageToCount = 0;
 		$accAmount = 0;
 		$rate= 0;
 		$consumption_block = "";
 		$billing_charge_item = array();

 		do{
 			if($usage > 901){
				$stage = 5;
				$amountToDeduct = 900;	
				$rate = static::CHARGING_RATE_900_ABOVE;
				$consumption_block = ">".$amountToDeduct;
				$block_level = "> 900";
 			}else if($usage > 601){
 				$stage = 4;
				$amountToDeduct = 600;
				$rate = static::CHARGING_RATE_900;
				$consumption_block = ">".$amountToDeduct;
				$block_level = 300;
 			}else if($usage > 301){
 				$stage = 3;
				$amountToDeduct = 300;
				$rate = static::CHARGING_RATE_600;
				$consumption_block = ">".$amountToDeduct;
				$block_level = 300;
 			}else if($usage > 201){
 				$stage = 2;
				$amountToDeduct = 200;
				$rate = static::CHARGING_RATE_300;
				$consumption_block = ">".$amountToDeduct;
				$block_level = 100;
 			}else{
 				$stage = 1;
				$amountToDeduct = 0;
				$rate = static::CHARGING_RATE_200;
				$consumption_block = "200";
				$block_level = 200;
 			}

 			$usageToCount = $usage - $amountToDeduct;
 			$usage =$usage - $usageToCount;
 			$amount =  $usageToCount * $rate;

 			$item['block_level'] = $block_level;
 			$item["consumption_block"] = $consumption_block ; 
 			$item["prorated_block"] = $usageToCount; 
 			$item["rate"] = $rate ; 
 			$item["amount"] = round($amount,2);
 			array_push($billing_charge_item, $item);

 		} while ($usage > 0 && $stage != 1);
 		
 		return array_reverse($billing_charge_item);
 	}

	public static function getUserMonthlyUsagePaymentByCustomerId($id)
	{

		$model;
 		$listing = array();
 		$meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=',$room['id_house_room'])->first();
	
		if(!isset($meter_register_model)){
				return view('utility_charges.apps.user_info');
		}
	
		$statistic['currentUsageKwh']    =  round(DB::table('meter_readings')->where('meter_register_id','=',$meter_register_model->id)
										 ->whereBetween('current_date', ['2018-03-01', date('Y-m-d', strtotime('now'))])
										 ->value(DB::raw("SUM(current_meter_reading) - SUM(last_meter_reading)")),2);
 		
 		foreach ($monthListing as $month) {

 			$invoiceListing = ARInvoice::getAllInvoiceByCustomerIdAndDateRange($id,$dataRange);
 			$paymentReceivedListing = ARPaymentReceived::getAllPaymentReceivedByCustomerIdAndDateRange($id,$dataRange);
			$model['total_payable_amount']  = $invoiceListing;
			$model['total_outstanding_amount']  = $invoiceListing;
			$model['total_paid_amount']  = $paymentReceivedListing;
 			$model['month']  = $month ;
 			$model['total_usage_kwh']  = $meterReadingListing;
 			array_push($listing, $model);
 		}

 		return $listing;

 	}

 	public static function get_hex_color_code_by_emergency_percent($percent){
 		return '';
 	}

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Report Code
    |--------------------------------------------------------------------------
    |
    */
 	public static function init_php_excelinit_php_excel(){

		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        PHPExcel_Shared_Font::setTrueTypeFontPath(public_path('fonts/extra/'));    
        //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);  
	}

	public static function electricBillStageCheckByCurrentStageAndBalance($currentStage , $amount){
		return static::is_negative($amount) ? $currentStage : ( $currentStage + 1 )  ; 
	}

	public static function convert_balance_to_kwh_by_current_usage_and_balance_flat_rate($currentMonthUsage,$amount){

	}

	public static function convert_balance_to_kwh_by_current_usage_and_balance($currentMonthUsage,$amount){
	
		if(Company::get_group_id() == static::SUNWAY_WATERFRONT_GROUP_ID)
		{
			$kwh = Setting::convert_balance_to_kwh_by_current_usage_and_balance_flat_rate();
			return $kwh;
		}

 		$usageToCount = 0;

 		$usage = $currentMonthUsage;
 		$remainingAmount = 0;
 		$currentLevelRemainingKwh= 0;
 		$currentStage= 0;
 		$stage = 0;
 		$kwh = 0;

 		do{
 			if($usage > 901 || $stage == 4){
				$stage = 4;
				$lowestLimit = 900;
				$rate = static::CHARGING_RATE_900_ABOVE;
				$kwh  += $amount / $rate;
				$amount -= $amount;

 			}else if($usage > 601 || $stage == 3){
				
				$upperLimit = 300;	
				$rate = static::CHARGING_RATE_900;
				$remainingCurrentLvKwhAmount = $upperLimit - $usage;
				$remainingKwhPrice = $remainingCurrentLvKwhAmount * $rate;	
				$totalBuyableKwh = $remainingKwhPrice > $amount ?  $amount / $rate : $remainingCurrentLvKwhAmount;
				$amount =  $remainingKwhPrice > $amount ? 0 : $amount - $remainingKwhPrice ;
				$stage = static::electricBillStageCheckByCurrentStageAndBalance( $stage,$amount);
				$usage = $usage > 0 ? ($usage - $upperLimit < 0 ? 0:$usage - $upperLimit ) : 0 ;
				$kwh  += $totalBuyableKwh;

 			}else if($usage > 301 || $stage == 2){
				
				$upperLimit = 300;
				$rate = static::CHARGING_RATE_600;
				$remainingCurrentLvKwhAmount = $upperLimit - $usage;
				$remainingKwhPrice = $remainingCurrentLvKwhAmount * $rate;	
				$totalBuyableKwh = $remainingKwhPrice > $amount ?  $amount / $rate : $remainingCurrentLvKwhAmount;
				$amount =  $remainingKwhPrice > $amount ? 0 : $amount - $remainingKwhPrice ;
				$stage = static::electricBillStageCheckByCurrentStageAndBalance( $stage,$amount);
				$usage = $usage > 0 ? ($usage - $upperLimit < 0 ? 0:$usage - $upperLimit ) : 0 ;
				$kwh  += $totalBuyableKwh;

 			}else if($usage > 201 || $stage == 1 ){
				
				$upperLimit = 100;
				$rate = static::CHARGING_RATE_300;
				$remainingCurrentLvKwhAmount = $upperLimit - $usage;
				$remainingKwhPrice = $remainingCurrentLvKwhAmount * $rate;	
				$totalBuyableKwh = $remainingKwhPrice > $amount ?  $amount / $rate : $remainingCurrentLvKwhAmount;
				$amount =  $remainingKwhPrice > $amount ? 0 : $amount - $remainingKwhPrice ;
				$stage = static::electricBillStageCheckByCurrentStageAndBalance( $stage,$amount);
				$usage = $usage > 0 ? ($usage - $upperLimit < 0 ? 0:$usage - $upperLimit ) : 0 ;
				$kwh  += $totalBuyableKwh;

 			}else{
				
				$upperLimit = 200;
				$rate = static::CHARGING_RATE_200;
				$remainingCurrentLvKwhAmount = $upperLimit - $usage;
				$remainingKwhPrice = $remainingCurrentLvKwhAmount * $rate;	
				$totalBuyableKwh = $remainingKwhPrice > $amount ?  $amount / $rate : $remainingCurrentLvKwhAmount;
				$amount =  $remainingKwhPrice > $amount ? 0 : $amount - $remainingKwhPrice ;
				$stage = static::electricBillStageCheckByCurrentStageAndBalance( $stage,$amount);
				$usage = $usage > 0 ? ($usage - $upperLimit < 0 ? 0:$usage - $upperLimit ) : 0 ;
				$kwh  += $totalBuyableKwh	;
 			}
 			echo $kwh.':'.$stage.'='.$amount."<br>";
 			
 		}while($amount > 0 || $stage == 0);
 		
 		return number_format($kwh, 4, '.', ' ');
 	}
	
	public static function getPaymentSummarybyTotalPaymentAmount($total_payment){

 		$paymentSummary['gst_charges_gst']  = round(static::gst_calculate(static::DEFAULT_GST_PERCENT,$total_payment),2) ;
		$paymentSummary['kwtbb_charges_gst']  = round($total_payment  * static::KWTBB_RATE,2) ;
		$paymentSummary['late_payment_charges_gst']  = round($total_payment  * static::LATE_PAYMENT_PENALTY,2) ;
	 	$paymentSummary['gst_charges_with_gst']  = round(static::gst_calculate(static::DEFAULT_GST_PERCENT,$total_payment),2) ;
		$paymentSummary['kwtbb_charges_with_gst']  = round($total_payment  * static::KWTBB_RATE,2) ;
		$paymentSummary['late_payment_charges_with_gst']  = round($total_payment  * static::LATE_PAYMENT_PENALTY,2) ;
 		
 		return $paymentSummary;
 	}

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Setia work flow element
    |--------------------------------------------------------------------------
    |
    */
 	public static function get_workflow_stepper($work_flow_title_listing){

 		$isFirst = true;
 		$workFlowTitle = $work_flow_title_listing;
 		$workFlowListing = array();
 		foreach($workFlowTitle as $title){
 			$temp['title'] = $title;
 			$temp['status']  = $isFirst == true ? 'done' : '';		
 			array_push($workFlowListing, $temp);
 			$isFirst = false;
 		}


 		return array('work_flow' => $workFlowListing , 'total_step'=>count($workFlowListing));
 	}

 	public static function get_workflow_ui($work_flow_title_listing){

 		$isFirst = true;
 		$workFlowTitle = $work_flow_title_listing;
 		$workFlowListing = array();
 		$counter = 1;
 		foreach($workFlowTitle as $title){
 			$temp['title'] = $title;
 			$temp['step_item'] = static::SP_SETIA_PAYMENT_RECEIVED_WORKFLOW_STEP_1_ITEM;
 			$temp['step_no']  = $counter;
 			array_push($workFlowListing, $temp);
 			$isFirst = false;
 			$counter++;
 		}


 		return array('work_flow' => $workFlowListing , 'total_step'=>count($workFlowListing));
 	}

 	public static function get_document_title_by_type_and_customer_id($type,$customer_id,$document_model =null)
 	{
 		$model = Customer::find($customer_id);
 		$doc = "";
 		$reference_no = isset($reference_no) ? $reference_no : '#';

 		switch ($type) {
 			case static::AR_INVOICE:
 				$doc = Language::trans('Invoice');
 				break;

 				case static::AR_PAYMENT_RECEIVED:
 				$doc = Language::trans('Receipt');
 				break;

 				case static::AR_REFUND:
 				$doc = Language::trans('Refund');
 				break;
 			
 			default:
 				$doc = Language::trans('Document');
 				break;
 		}

 		return $doc.'_'.date('Y-m-d', strtotime('now')).'_'.$model['name'].'_'.$document_model['document_no'];
 	}

 	public static function get_sunway_report_title_by_type_and_date_range($type,$date_range =null)
 	{

 		$document_date = $date_range['date_started'].' '.Language::trans('to').' '.$date_range['date_ended'];
 		switch ($type) {
 			case static::SUNWAY_MONTHLY_USAGE_REPORT:
 				$doc = Language::trans('Monthly Usage Report');
 				break;

 			case static::SUNWAY_MONTHLY_SALES_REPORT:
 				$doc = Language::trans('Monthly Sales Report');
 				break;

 			case static::SUNWAY_SALES_REPORT:
 				$doc = Language::trans('Sales Report');
 				break;

 				case static::SUNWAY_ROOM_USAGE_REPORT:
 				$doc = Language::trans('Room Usage');
 				break;
 			
 			default:
 				$doc = Language::trans('Document');
 				break;
 		}

 		return $doc.' ('.$document_date.' )_'.date('Y-m-d', strtotime('now'));
 	}

 	

 	public static function payment_method_to_word($payment_method){

 		$return = "";
 		switch($payment_method){
 			case static::PAYMENT_METHOD_IPAY_88:
 				$return = Language::trans(static::PAYMENT_METHOD_IPAY_88_WORD);
 				break;

			case static::PAYMENT_METHOD_CASH:
				$return = Language::trans(static::PAYMENT_METHOD_CASH_WORD);
				break;

			case static::PAYMENT_METHOD_MOL_PAY:
				$return = Language::trans(static::PAYMENT_METHOD_MOL_PAY_WORD);
				break;

			case static::PAYMENT_METHOD_CREDIT_CARD:
				$return = Language::trans(static::PAYMENT_METHOD_CREDIT_CARD_WORD);
				break;

			case static::PAYMENT_METHOD_ETF:
				$return = Language::trans(static::PAYMENT_METHOD_ETF_WORD);
				break;

			case static::PAYMENT_METHOD_OTHERS:
				$return = Language::trans(static::PAYMENT_METHOD_OTHERS_WORD);
				break;

			case static::PAYMENT_METHOD_CHEQUE:
				$return = Language::trans(static::PAYMENT_METHOD_CHEQUE_WORD);
				break;
			default:
				$return =  null;
				break;
 		}
 		return $return;

 	}

 	public static function credit_card_masking($number, $maskingCharacter="*") {
 		if(strlen($number) < 8){
 			return $number;
 		}
    	return substr($number, 0, 4) . str_repeat($maskingCharacter, strlen($number) - 8) . substr($number, -4);
	}

 	public static function setCompany($id=null){

 		  $c= new Company();
		  $c->set_group_id($id);
		  // /print_r($c::get_group_id());
 	}

 	public static function set_company($id=null){
 		  
 		  $c= new Company();
		  $c->set_group_id($id);
		  // /print_r($c::get_group_id());
 	}

 	public static function number_to_word( $num = '' )
	{
		$numArr = explode('.', $num);
		$numWord = '';
		$isFirst = true;

		foreach ($numArr as $num) {
			$num    = ( string ) ( ( int ) $num );

		if( ( int ) ( $num ) && ctype_digit( $num ) )
		{
			$words  = array( );
		   
			$num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
		   
			$list1  = array('','one','two','three','four','five','six','seven',
				'eight','nine','ten','eleven','twelve','thirteen','fourteen',
				'fifteen','sixteen','seventeen','eighteen','nineteen');
		   
			$list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
				'seventy','eighty','ninety','hundred');
		   
			$list3  = array('','thousand','million','billion','trillion',
				'quadrillion','quintillion','sextillion','septillion',
				'octillion','nonillion','decillion','undecillion',
				'duodecillion','tredecillion','quattuordecillion',
				'quindecillion','sexdecillion','septendecillion',
				'octodecillion','novemdecillion','vigintillion');
		   
			$num_length = strlen( $num );
			$levels = ( int ) ( ( $num_length + 2 ) / 3 );
			$max_length = $levels * 3;
			$num    = substr( '00'.$num , -$max_length );
			$num_levels = str_split( $num , 3 );
		   
			foreach( $num_levels as $num_part )
			{
				$levels--;
				$hundreds   = ( int ) ( $num_part / 100 );
				$hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '' );
				$tens       = ( int ) ( $num_part % 100 );
				$singles    = '';
			   
				if( $tens < 20 )
				{
					$tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
				}
				else
				{
					$tens   = ( int ) ( $tens / 10 );
					$tens   = ' ' . $list2[$tens] . ' ';
					$singles    = ( int ) ( $num_part % 10 );
					$singles    = ' ' . $list1[$singles] . ' ';
				}
				$words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
			}
		   
			$commas = count( $words );
		   
			if( $commas > 1 )
			{
				$commas = $commas - 1;
			}
		   
			$words  = implode( ', ' , $words );
		   
			//Some Finishing Touch
			//Replacing multiples of spaces with one space
			// $words  = trim( str_replace( ' ,' , ',' , trim_all( ucwords( $words ) ) ) , ', ' );
			
			$words = trim($words);
			
			$words  = str_replace( '  ' , ' ' , $words );
			if( $commas )
			{
				if (substr($words, -1) == ',') {
					$words  = str_replace( ' ,' , '' , $words );
				}else{
					$words  = str_replace( ' ,' , ' and' , $words );
				}
				
			}
			
			//return $words;
			if($isFirst == false){
				$numWord = $numWord.' AND '.$words;
			}else{
				$isFirst = false;
				$numWord = $words;
			}
			
		}
		else if( ! ( ( int ) $num ) && count($numArr) == 1 )
		{


			return 'Zero';
		}
		}
		return $numWord;
		//return '';
	}


	public static function strpos_array($haystack, $needle, $offset=0)
	{
	    if(!is_array($needle)) $needle = array($needle);
	    foreach($needle as $query) {
	        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
	    }
	    return false;
	}

	public static function get_leaf_group_id($leaf_group_id=null)
	{
		return isset($leaf_group_id) ? $leaf_group_id : Company::get_group_id();
	}

	public static function get_company_monthly_cut_off_date_range_by_date_started_and_date_ended_test($date_started,$date_ended,$leaf_group_id=null)
	{
		echo $date_started.'='.$date_ended."<br>";
		$is_first = true;
		$result = array();
		$next_cut_off_date = '';
		$date_started_string = strtotime($date_started);
		$cut_off_day = Company::get_monthly_cut_off_day_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
		echo $cut_off_day.' vs '.date("d", $date_started_string)."<br>";
		if($cut_off_day < date("d", $date_started_string)){
			$first_cut_off_date = date("Y-m-".$cut_off_day, $date_started_string);
			//dd($first_cut_off_date);
			$next_cut_off_date = date("Y-m-d", strtotime("+1 month",  strtotime($first_cut_off_date)));
			echo 'Smaller :'.$first_cut_off_date."<br>";
		}else{
			$first_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", strtotime($first_cut_off_date)));
			$next_cut_off_date = $first_cut_off_date;
			echo 'Bigger :'.$first_cut_off_date."<br>";
		}
	
		echo 'Next cut off :'.$next_cut_off_date.'<br>';	
		for ($i=0; $i < 100; $i++) { 

			if((strtotime($first_cut_off_date) <  strtotime($date_started))){
				//Move in after first cut off , start first cut end before second cut
				if($is_first){
					$temp['date_started'] = date("Y-m-d", strtotime($first_cut_off_date));
					$temp['date_ended'] = date("Y-m-d", strtotime("-1 day", strtotime($next_cut_off_date)));
					$is_first = false;

				}else{
					//get for subsequent cut
					$temp['date_started'] = $next_cut_off_date;
					$temp['date_ended'] = 	date("Y-m-d", strtotime("+1 month -1 day", strtotime($next_cut_off_date)));
					$next_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", strtotime($next_cut_off_date)));
				}
				array_push($result, $temp);
				
				//for the condition of first month
			}else if((strtotime($next_cut_off_date) >  strtotime('now')) && date("m",$date_started_string) ==  date("m", strtotime('now')) && $is_first == true ){
			
				$temp['date_started'] = date("Y-m-d", $date_started_string);
				$temp['date_ended'] = 	date("Y-m-d", strtotime("-1 day", strtotime($next_cut_off_date)));
				$is_first = false;
				array_push($result, $temp);
				
			}else{
				break;
			}


			/*if((strtotime($next_cut_off_date) <  strtotime('now'))){
		
				if($is_first){
					$temp['date_started'] = date("Y-m-d", strtotime($date_started));
					$temp['date_ended'] = date("Y-m-d", strtotime("-1 day", strtotime($first_cut_off_date)));
					$is_first = false;

				}else{
					$temp['date_started'] = $next_cut_off_date;
					$temp['date_ended'] = 	date("Y-m-d", strtotime("+1 month -1 day", strtotime($next_cut_off_date)));
					$next_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", strtotime($next_cut_off_date)));
				}
				array_push($result, $temp);
				
				//for the condition of first month
			}else if((strtotime($next_cut_off_date) >  strtotime('now')) && date("m",$date_started_string) ==  date("m", strtotime('now')) && $is_first == true ){
			
				$temp['date_started'] = date("Y-m-d", $date_started_string);
				$temp['date_ended'] = 	date("Y-m-d", strtotime("-1 day", strtotime($next_cut_off_date)));
				$is_first = false;
				array_push($result, $temp);
				
			}else{
				break;
			}*/
		}

		return $result;
	}


	public static function get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_started,$date_ended,$leaf_group_id=null)
	{
		$is_first = true;
		$result = array();
		$next_cut_off_date = '';
		$date_started_string = strtotime($date_started);
		$cut_off_day = Company::get_monthly_cut_off_day_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
		if($cut_off_day < date("d", $date_started_string)){
			$first_cut_off_date = date("Y-m-".$cut_off_day, $date_started_string);
			//dd($first_cut_off_date);
			$next_cut_off_date = date("Y-m-d", strtotime("+1 month",  strtotime($first_cut_off_date)));
		}else{
			$first_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", strtotime($first_cut_off_date)));
			$next_cut_off_date = $first_cut_off_date;
		}
	
		for ($i=0; $i < 100; $i++) { 

			if((strtotime($first_cut_off_date) <  strtotime($date_started))){
				//Move in after first cut off , start first cut end before second cut
				if($is_first){
					$temp['date_started'] = date("Y-m-d", strtotime($first_cut_off_date));
					$temp['date_ended'] = date("Y-m-d", strtotime("-1 day", strtotime($next_cut_off_date)));
					$is_first = false;

				}else{
					//get for subsequent cut
					$temp['date_started'] = $next_cut_off_date;
					$temp['date_ended'] = 	date("Y-m-d", strtotime("+1 month -1 day", strtotime($next_cut_off_date)));
					$next_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", strtotime($next_cut_off_date)));
				}
				array_push($result, $temp);
				
				//for the condition of first month
			}else if((strtotime($next_cut_off_date) >  strtotime('now')) && date("m",$date_started_string) ==  date("m", strtotime('now')) && $is_first == true ){
			
				$temp['date_started'] = date("Y-m-d", $date_started_string);
				$temp['date_ended'] = 	date("Y-m-d", strtotime("-1 day", strtotime($next_cut_off_date)));
				$is_first = false;
				array_push($result, $temp);
				
			}else{
				break;
			}

		}

		return $result;
	}

	public static function get_company_monthly_cut_off_date_range_by_date_started_and_date_ended_previous($date_started,$date_ended,$leaf_group_id=null)
	{
		$is_first = true;
		$result = array();
		$date_started_string = strtotime($date_started);
		$cut_off_day = Company::get_monthly_cut_off_day_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
		
		if($cut_off_day > date("d", strtotime($date_started_string))){
			$first_cut_off_date = date("Y-m-".$cut_off_day, $date_started_string);
		}else{
			$first_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", $date_started_string));
		}
	
		$next_cut_off_date = $first_cut_off_date;
		for ($i=0; $i < 100; $i++) { 

			if((strtotime($next_cut_off_date) <  strtotime('now'))){
		
				if($is_first){
					$temp['date_started'] = date("Y-m-d", strtotime($date_started));
					$temp['date_ended'] = date("Y-m-d", strtotime("-1 day", strtotime($first_cut_off_date)));
					$is_first = false;

				}else{
					$temp['date_started'] = $next_cut_off_date;
					$temp['date_ended'] = 	date("Y-m-d", strtotime("+1 month -1 day", strtotime($next_cut_off_date)));
					$next_cut_off_date = date("Y-m-".$cut_off_day, strtotime("+1 month", strtotime($next_cut_off_date)));
				}
				array_push($result, $temp);
				
				//for the condition of first month
			}else if((strtotime($next_cut_off_date) >  strtotime('now')) && date("m",$date_started_string) ==  date("m", strtotime('now')) && $is_first == true ){
			
				$temp['date_started'] = date("Y-m-d", $date_started_string);
				$temp['date_ended'] = 	date("Y-m-d", strtotime("-1 day", strtotime($next_cut_off_date)));
				$is_first = false;
				array_push($result, $temp);
				
			}else{
				break;
			}
		}

		return $result;
	}

	public static function get_date_different_in_day($date_started,$date_ended){

		$start_date = strtotime($date_started); 
		$end_date = strtotime($date_ended); 
		  
		// Get the difference and divide into  
		// total no. seconds 60/60/24 to get  
		// number of days 
		return ($end_date - $start_date)/60/60/24; 
	}

	public static function date_differrent($time1, $time2, $precision = 6) 
	{
	    // If not numeric then convert texts to unix timestamps
	    if (!is_int($time1)) {
	      $time1 = strtotime($time1);
	    }
	    if (!is_int($time2)) {
	      $time2 = strtotime($time2);
	    }

	    // If time1 is bigger than time2
	    // Then swap time1 and time2
	    if ($time1 > $time2) {
	      $ttime = $time1;
	      $time1 = $time2;
	      $time2 = $ttime;
	    }

	    // Set up intervals and diffs arrays
	    $intervals = array('year','month','day','hour','minute','second');
	    $diffs = array();

	    // Loop thru all intervals
	    foreach ($intervals as $interval) {
	      // Create temp time from time1 and interval
	      $ttime = strtotime('+1 ' . $interval, $time1);
	      // Set initial values
	      $add = 1;
	      $looped = 0;
	      // Loop until temp time is smaller than time2
	      while ($time2 >= $ttime) {
	        // Create new temp time from time1 and interval
	        $add++;
	        $ttime = strtotime("+" . $add . " " . $interval, $time1);
	        $looped++;
	      }
	 
	      $time1 = strtotime("+" . $looped . " " . $interval, $time1);
	      $diffs[$interval] = $looped;
	    }
	    
	    $count = 0;
	    $times = array();
	    // Loop thru all diffs
	    foreach ($diffs as $interval => $value) {
	      // Break if we have needed precission
	      if ($count >= $precision) {
	        break;
	      }
	      // Add value and interval 
	      // if value is bigger than 0
	      if ($value > 0) {
	        // Add s if value is not 1
	        if ($value != 1) {
	          $interval .= "s";
	        }
	        // Add value and interval to times array
	        $times[] = $value . " " . $interval;
	        $count++;
	      }
	    }

	    // Return string with times
	    return implode(", ", $times);
  }

  public static function convert_to_unix_timestamp($datetime)
  {
  	return strtotime($datetime);
  }

  public static function convert_to_unix_timestamp_to_date_time($unix_timestamp)
  {
	return gmdate("Y-m-d\ T H:i:s\ Z" , $unix_timestamp);
  }

  public static function convert_to_unix_timestamp_to_date_time_2($unix_timestamp)
  {
	$date_time = new DateTime();
	$date_time = DateTime::createFromFormat( 'U', $unix_timestamp );
	$return = $date_time->format( 'Y-m-d' );
	return $return;
  }
  

  public static function convert_date_range_string_to_array($date_range_string){
  
   $date_range_arr = explode('-', $date_range_string);
   $date_range = [
				   'date_started' =>  date("Y-m-d", strtotime($date_range_arr[0])),
				   'date_ended' =>  date("Y-m-d", strtotime($date_range_arr[1])),
				   ];

  	return $date_range;
  }

  public static function fix_serialize_data($bad_data){
  	$fixed_data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {      
                        return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
                    },$bad_data );
  }

  public static function get_array_element_with_column_and_value($model_listing, $column, $value)
  {
  	//dd($model_listing);
	//echo $column.'='.$value."<br>";
  	foreach ($model_listing as $model) {
                if($model[$column] == $value ){
                    return $model;
                }
    }

    return null;
  }


  public static function remove_invalid_array_element_with_model_listing_and_column($column_array, $model_listing, $column)
  {
  	foreach ($model_listing as $model) {
                if(in_array($model[$column] , $column_array )){
                    //search delete value
                    if (($key = array_search($model[$column], $column_array)) !== false) {
                        unset($column_array[$key]);
                    }
                }
    }

    return $column_array;
  }

  public static function base64_to_jpeg($base64_string, $output_file) 
  {
		    // open the output file for writing
		    $ifp = fopen( $output_file, 'wb' ); 

		    // split the string on commas
		    // $data[ 0 ] == "data:image/png;base64"
		    // $data[ 1 ] == <actual base64 string>
		    $data = explode( ',', $base64_string );

		    // we could add validation here with ensuring count( $data ) > 1
		    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

		    // clean up the file resource
		    fclose( $ifp ); 

		    return $output_file; 
	}

  const power_meter_site_url = 'http://webview.leaf.com.my/utility_adjustment_check_by_email_second?email=';
  public static function generate_power_meter_testing_barcode_url($user_email)
  {
  	 return static::power_meter_site_url.$user_email;
  }

  public static function mail_engine()
  {
  		return ['smptp'=> 'SMTP' , 'mail' => 'Mail'];
  }

  public static function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
  }

  public static function month_year_combobox($total_year=1)
    {
        $total_months = $total_year * 12;
        $start = 0 - $total_months/3;
        $end = $total_months/3;
        for ($i=$start; $i<=$end; $i++) { 
        	//dd($i);
            $action = $i < 0 ? '- '.abs($i) : '+ '.$i;
            $string = date('m-Y', strtotime($action.' month'));
            $return[(string) $string] = (string) $string;
        }
        return $return;
    }

    public static function date_sort($a, $b) {
	    return strtotime('01-'.$a) - strtotime('01-'.$b);
	}

	public static function sortByDateColumn( $model_listing,$column_name=null)
	{
		$keys = array_keys($model_listing);
		usort($keys, "App\Setting::date_sort");

		$return = array();
		foreach($keys as $key)
		{
			$return[$key] = isset($model_listing[$key]) ? $model_listing[$key] : array();
		}
		return $return;
	}

	public function setCookie($value,$name){
      $minutes = 60000;
      cookie($name, $value, $minutes);
   }

   	public static function getIntervalInMinutes($interval)
   	{
		$intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($interval)->getTimeStamp();
		$intervalInMinutes = $intervalInSeconds/60; // and so on
		return $intervalInMinutes;
   	}

}		




