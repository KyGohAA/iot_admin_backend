<?php

namespace App;

use Auth;
use App\PowerMeterModel\MeterReading;
use App\PowerMeterModel\MeterRegister;
use App\House;
use App\Room;

class LeafAPI extends ExtendModel
{
	const method_get 			=	'get';
	const method_post 			=	'post';
	// const app_secret 			=	'UJx9ERfJ8tcM7Ip50uNo8zIXUPAS93JTC627X8R6C4IzC8uss8RBIpps4Za4SUYl';
	const main_app_secret 		=	'wLbJlCCBpQuOnullBPMJtszKr5cWCLN8UAJncHUWdp7S4u5MI00UzeG9Od3Oxrwi';
	const app_secret 			=	'P5lsZKtSyQ3oV9mIQvzEDL1crszSKc4kO6i1ob8HfRLVE8RmU5Ms0RW11caQ0aXu'; // webview secret token
	const url 					=	'https://cloud.leaf.com.my/api/';
	const label_session_token	=	'session_token';
	const label_module_cookie 	=	'modules_cookie';
	const label_accounting 		=	'accounting';
	const label_umrah 			=	'umrah';
	const label_power_meter 	=	'power_meter';
	const label_e_commerce		=	'e_commerce';
	const label_twin_room 		=	'twin';
	
	const payment_is_sandbox 	=	false;

	const leaf_payable_item     =    array("facility","programme","fee-type"); 
	const leaf_facility_label  = 	 'facility'; 
	const leaf_programme_label  = 	 'programme';
	const leaf_fee_type_label  = 	 'fee-type';

	const payment_gateway_ipay88 = "ipay88";
	const payment_gateway_molpay = "molpay";

	 /*
    |--------------------------------------------------------------------------
    | Api for Setia club house
    |--------------------------------------------------------------------------
    |
    */

    public function get_payment_service($payment_service=null)
    {
    	return isset($payment_service) ? $payment_service : static::payment_gateway_ipay88;
    }


    public static function get_leaf_product_category_by_leaf_product_model($leaf_product)
    {
    	//echo "------------------------------------Start------------------------------------<br>";
    	$category ="";
    	foreach (static::leaf_payable_item as $item) {
    		$category = $item;
			if($item == 'fee-type'){
				$category = 'fee_type';
			}
    		foreach ($leaf_product as $key => $value) {
    			//echo $key.'='.$category."<br>";
    			if(strpos($key, $category) !== false){
    					return $category;
    			}else{
    				break;
    			}
    		}
    	}

    }

    public static function get_leaf_product_category_word_by_category_label($category_label)
    {
    	//echo "------------------------------------Start------------------------------------<br>";
    	return ucwords(str_replace("_", " ", $category_label));
    	

    }

    public static function update_all_customer_from_leaf(){

    }

    public static function get_room_history_by_leaf_room_id($leaf_room_id){

		 $house   =   new House();
		 $fdata      =   $house->get_houses(true);
		 $listing    =   array('period_member' => array(),'start_date_sequence' => array());
		 $stay_timeline = array();

		if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
						foreach($house['house_rooms'] as $room){ 
		
							if($room['id_house_room'] == $leaf_room_id){
								foreach ($room['house_room_members'] as $member) {
									

										$temp['house_rooms'] = $room;
										$temp['id_house_room'] = $room['id_house_room'];
										$temp['id_house'] = $house['id_house'];
										$temp['house_unit'] = $house['house_unit'];
										$temp['house_subgroup'] = $house['house_subgroup'];
										$temp['house_room_member_start_date'] = $member['house_room_member_start_date'];
										$temp['house_room_member_end_date'] = $member['house_room_member_end_date'];
										$temp['house_room_member_deleted'] = $member['house_room_member_deleted'];
										array_push($listing, $temp);

										$temp['timeline_date'] = $member['house_room_member_start_date'];				
										array_push( $stay_timeline, $temp);
										$temp['timeline_date'] = $member['house_room_member_end_date'];
										array_push( $stay_timeline, $temp);
										//house_room_members
										/*if($member['house_room_member_deleted'] == true){
											//deleted member capture leave date
											$period_member[date('Y-m', strtotime($member['house_room_member_end_date']))] = !isset($period_member[date('Y-m', strtotime($member['house_room_member_end_date']))]) ?  array() : $period_member[date('Y-m', strtotime($member['house_room_member_end_date']))];	
											array_push($period_member[date('Y-m', strtotime($member['house_room_member_end_date']))], $member) ;
				
										}else{
											$period_member[date('Y-m', strtotime($member['house_room_member_start_date']))] = !isset($period_member[date('Y-m', strtotime($member['house_room_member_start_date']))]) ? array() : $period_member[date('Y-m', strtotime($member['house_room_member_start_date']))];	
											array_push($period_member[date('Y-m', strtotime($member['house_room_member_start_date']))], $member) ;
			
										}*/
										
								}
								$stay_history = $room['house_room_members'];
								
								usort($stay_history, 'App\Setting::compare_by_timeStamp');
							}	
						}
					}
				}
			}
			
			/*foreach ($period_member as $row) {
				usort($row, 'App\Setting::compare_by_timeStamp');
			}*/
			//dd($stay_timeline);
			usort($stay_timeline, 'App\Setting::compare_by_timeline');
			foreach ($stay_timeline as $row) {
				array_push($timeline, $row['timeline_date']);
				echo $row['house_unit']."---:".$row['timeline_date']."<br>";
			}
			//dd("Checked :");
			//dd($period_member);
			$listing['timeline'] = $timeline;
		return $listing;
	}

	public function get_prepare_payment($desc, $total, $customer_name, $customer_email, $success_url=null, $cancel_url=null,$payment_method=null)
	{
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$params['app_secret'] = self::main_app_secret;
		
		$params['payment_service'] = 'ipay88';
		$params['id_user'] = '2701';
		$params['id_house'] = '34016';
		$params['id_house_member'] = '319604';
		$params['id_group'] = '282';

		$params['payment_service'] = $this->get_payment_service($payment_method);
		$params['payment_account_holder_name'] = 'sunway medical center';
		$params['payment_account_number'] = '00000000';
		$params['payment_currency_code'] = 'MYR';
		$params['payment_total_amount'] = number_format($total,2,'.','');
		$params['payment_item_name'] = $desc;
		$params['payment_success_url'] = $success_url;
		$params['payment_cancel_url'] =  $cancel_url;
		$params['payment_is_sandbox'] = static::payment_is_sandbox;
		$params['payment_customer_name'] = $customer_name;
		$params['payment_customer_email'] = $customer_email;
		//dd($params);
		return $this->decode($this->post('payment/new', $params));
	}

	public function get_prepare_payment_power_meter($desc, $total, $customer_name, $customer_email, $success_url=null, $cancel_url=null)
	{
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$params['app_secret'] = self::main_app_secret;

		$params['payment_service'] = 'ipay88_sunmed';
		$params['payment_account_holder_name'] = 'Sunway Medical Center';
		$params['payment_account_number'] = '00000000';
		$params['payment_currency_code'] = 'MYR';
		$params['payment_total_amount'] = number_format($total,2,'.','');
		$params['payment_item_name'] = $desc;
		$params['payment_success_url'] = $success_url;
		$params['payment_cancel_url'] = $cancel_url;
		$params['payment_is_sandbox'] = static::payment_is_sandbox;
		$params['payment_customer_name'] = $customer_name;
		$params['payment_customer_email'] = $customer_email;
		//dd($params);
		return $this->decode($this->post('payment/new', $params));
	}

	public function get_prepare_payment_universal($payment_gateway,$desc, $total, $customer_name, $customer_email, $success_url=null, $cancel_url=null)
	{
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$params['app_secret'] = self::main_app_secret;

		$params['payment_service'] = $payment_gateway != '' ? $payment_gateway : Setting::CODE_LEAF_IPAY88;
		$params['payment_account_holder_name'] = Setting::getPaymentGatewayAccountHolderName($payment_gateway);
		$params['payment_account_number'] = '00000000';
		$params['payment_currency_code'] = 'MYR';
		$params['payment_total_amount'] = number_format($total,2,'.','');
		$params['payment_item_name'] = $desc;
		$params['payment_success_url'] = $success_url;
		$params['payment_cancel_url'] = $cancel_url;
		$params['payment_is_sandbox'] = static::payment_is_sandbox;
		$params['payment_customer_name'] = $customer_name;
		$params['payment_customer_email'] = $customer_email;
		$params['payment_call_success_url_on_success'] = true;
		//dd($params);
		return $this->decode($this->post('payment/new', $params));
	}

	public function get_check_payment($payment_id)
	{
		return $this->decode($this->post('payment/view/'.$payment_id));
	}

	public function post_payment_method($leaf_id_payment, $payment_method)
	{
		$data_to_post['app_secret'] = static::main_app_secret;
		$db = $this->decode($this->get('payment/view/'.$leaf_id_payment, $data_to_post));
		$data_to_post = array();
		$data_to_post['app_secret'] = static::main_app_secret;
		$data_to_post['payment_method'] = $payment_method;
		$data_to_post['id_user'] = $db['id_user'];
		$data_to_post['payment_service'] = ($payment_method == 'paypal' || $payment_method == 'ecpay' ? $payment_method:'ipay88');
		$data_to_post['payment_account_holder_name'] = $db['payment_account_holder_name'];
		$data_to_post['payment_account_number'] = $db['payment_account_number'];
		$data_to_post['payment_currency_code'] = $db['payment_currency_code'];
		$data_to_post['payment_total_amount'] = $db['payment_total_amount'];
		$data_to_post['payment_item_name'] = $db['payment_item_name'];
		$data_to_post['payment_success_url'] = $db['payment_success_url'];
		$data_to_post['payment_cancel_url'] = $db['payment_cancel_url'];
		$data_to_post['payment_is_sandbox'] = $db['payment_is_sandbox'];

		return $this->decode($this->post('payment/edit/'.$leaf_id_payment, $data_to_post));
	}

	public function upload_or_delete_member_face_photo_by_id_house_member($photo_path , $id_house_member , $operation = null)
	{
		$operation = isset($operation) ? $operation : 'new';
		$params['photo'] = $photo_path;
		return $this->decode($this->get('/group/'.Company::get_group_id().'/house-member/'.$id_house_member.'/face-photo/new', $params));
	}

	public function upload_or_delete_member_ic_passport_photo_by_id_house_member($photo_path , $id_house_member , $operation = null)
	{
		$operation = isset($operation) ? $operation : 'new';
		$params['photo'] = $photo_path;
		return $this->decode($this->get('/group/'.Company::get_group_id().'/house-member/'.$id_house_member.'/ic-photo/new', $params));
	}

	/*public function upload_or_delete_member_ic_passport_photo_by_id_house_member($photo_path , $id_house_member , $operation = null)
	{
		$operation = isset($operation) ? $operation : 'new';
		$params['photo'] = $photo_path;
		return $this->decode($this->get('/group/'.Company::get_group_id().'/house-member/'.$id_house_member.'/other-photo/new', $params));
	}*/


	public function send_email($email, $title, $html)
	{		
		$params['app_secret'] 		=	static::app_secret;
		$params['email_title'] 		=	$title;
		$params['email_html'] 		=	$html;
		$params['email_address'] 	=	$email;
		$params['email_mode'] 		=	'synchronous';
		return $this->decode($this->post('email/new', $params));
	}

	public function get_user_by_email($user_email)
	{
		return $this->decode($this->get('user/search/email/'.$user_email));
	}

	public function get_groups()
	{
		if (isset($_COOKIE[static::label_session_token])) {
			$params[static::label_session_token] = $_COOKIE[static::label_session_token];
			$result = $this->decode($this->get('user/group', $params));
			if ($result['status_code'] == 1) {
				return $result['group'];
			}
		}
		return [];
	}

	public function get_modules()
	{
		$listing = $this->get_groups();
		$result = [];
		foreach ($listing as $row) {
			if ($row['id_group'] == Company::get_group_id()) {
				if ($row['group_module_power_meter_enabled'] == true) {
					$result[] = static::label_power_meter;
				}
				if ($row['group_module_umrah_enabled']) {
					$result[] = static::label_umrah;
				}
				if ($row['group_module_accounting_enabled']) {
					$result[] = static::label_accounting;
				}
			}
		}
		return $result;
	}

	public function set_cookie_modules()
	{
		setcookie(self::label_module_cookie, json_encode($this->get_modules()));
	}

	public static function get_cookie_modules()
	{
		if (isset($_COOKIE[static::label_module_cookie])) {
			return json_decode($_COOKIE[static::label_module_cookie], true);
		}
		return [];
	}

	public static function get_module_status($module=[])
	{
		foreach ($module as $row) {
			if (in_array($row, self::get_cookie_modules())) {
				return true;
			}
		}
		return false;
	}

	public function get_customer_list()
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;

		return $this->decode($this->get('group/'.Company::get_group_id().'/house-member', $params));
	}

	public function get_customer_list_with_update($date)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$params['since_last_update'] = date('Y-m-d', strtotime($date));
		return $this->decode($this->get('group/'.Company::get_group_id().'/house-member', $params));
	}

	public function get_customer_combobox()
	{
		$listing = $this->get_customer_list();
		$fdata = [''=>Language::trans('Please select customer...')];
		if (isset($listing['status_code']) && $listing['status_code']) {
			if (isset($listing['house'])) {
				foreach ($listing['house'] as $house) {
					if (isset($house['house_members']) && $house['house_members']) {
						foreach ($house['house_members'] as $member) {
							$fdata[$member['house_member_id_user']] = $member['house_member_name'];
						}
					}
				}
			}
		}
		return $fdata;
	}

	public function get_house_member_list() {

	    $members = $this->get_customer_list();
	    $member_listing = array();

	    foreach($members['house'] as $house) {
	        foreach($house['house_members'] as $member) {
	            array_push($member_listing, $member);
	        }
	    }

	    return $member_listing;
	}

	public function get_house_member_by_user_id($user_id,$last_update=null) 
	{
		$customer = new Customer();
	    $members = isset($last_update) ? $this->get_customer_list_with_update($last_update) : $this->get_customer_list();

	    foreach($members['house'] as $house)
	    {
	    	if(count($house['house_members']) == 0)
	    	{
	    	    continue;

	    	}else if(count($house['house_members']) == 1){

		        if ($house['house_members'][0]['house_member_id_user'] == $user_id) {
		            return $house['house_members'][0];
		        }
		        
	    	}else{
	    		
	    		foreach($house['house_members'] as $member){

		            if ($member['house_member_id_user'] == $user_id) {
		                    return $member;
		            }
		
	        	}
	    	}
	       
	    }

	}


	public function get_house_member_by_leaf_id_house_member($id_house_member,$last_update=null) 
	{
		$customer = new Customer();
	    $members = isset($last_update) ? $this->get_customer_list_with_update($last_update) : $this->get_customer_list();
	    $member_listing = array();
//dd($members);
	    foreach($members['house'] as $house)
	    {
	    	//dd($house);
	    	if(!is_array($house['house_members']))
	    	{
	    		continue;
	    	}

	    	if(count($house['house_members']) == 0)
	    	{
	    		continue;

	    	}else if(count($house['house_members']) == 1)
	    	{
		        if ($house['house_members'][0]['id_house_member'] == $id_house_member) {
		        
		            return $house['house_members'][0];
		        }

	    	}else{
	    		
	    		foreach($house['house_members'] as $member){

		                if ($member['id_house_member'] == $id_house_member) {

		                    return $member;
		                }
		
		            
	        	}
	    	}
	    }
	}

	public function get_house_membership_detail_by_house_id($house_id)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;	
		$owner ;
		$house = $this->decode($this->get('group/'.Company::get_group_id().'/house-member/'.$house_id, $params));
		if($house['status_code']){
			if(count($house['house_members']) > 0 ){
			foreach($house['house_members'] as $member)
		    {
		    	if($member['house_member_is_owner'] == true)
		    	{
		    		$owner = $member;
		    		break;
		    	}
		    }
		
		}else{

		}
	}

		 $fdata = [
                    'house_fee_items'   =>  $house['house_fee_items'],
                    'owner'    =>  $owner,
                    'member'   =>  $house['house_members'],
                    'address'   =>  [
                    					'unit_no'  => $house['house_unit'],
					                    'address1' => $house['house_address1'],
					                    'address2' => $house['house_address2'],
					                    'postcode' => $house['house_postcode'],
					                    'city' => $house['house_city'],
					                    'state' => $house['house_state'],
					                    'country' => $house['house_country'],
                     				],
                    ];

		return $fdata;
	
	}

	public function get_fee_type()
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		//leaf test
		$c= new Company();
		$c->set_group_id(285);
		return $this->decode($this->get('group/'.Company::get_group_id().'/fee-type', $params));
	}

	public function get_payment_info($id_payment)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		return $this->decode($this->get('payment/view/'.$id_payment, $params));
	}

	public function get_fee_type_by_group_id($group_id=null)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$group_id = isset($group_id) ? $group_id : Company::get_group_id();
		return $this->decode($this->get('group/'.$group_id.'/fee-type', $params));
	}

	public function get_programme_by_group_id($group_id=null)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$group_id = isset($group_id) ? $group_id : Company::get_group_id();
		return $this->decode($this->get('group/'.$group_id.'/programme', $params));
	}

	public function get_facility_by_group_id($group_id=null)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$group_id = isset($group_id) ? $group_id : Company::get_group_id();
		return $this->decode($this->get('group/'.$group_id.'/facility', $params));
	}

	//wip here
	public function get_product_by_leaf_product_id_and_category($leaf_product_id ,$category=null)
	{

		$group_id = Company::get_group_id();
		$listing =  $this->get_all_leaf_payable_item_model_by_group_id($group_id);
		if(isset($category))
		{
			foreach ($listing as $item) {
				if($item['id_'.$category] == $leaf_product_id){
					return $item;
				}
			}
		}else
		{
			foreach ($listing as $item) {
				foreach ($item as $key => $value) {
					if(strpos($key, 'id') !== false){
						if($value == $leaf_product_id){
							return $item;
						}else{
							break;
						}					
					}
				}
				
			}
		}
	}
		

	/*	 With expiry check
	  0 => "facility"
	  1 => "programme"
	  2 => "fee-type"
	*/
	public function set_product_from_leaf_by_group_id($group_id=null)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$payable_item = static::leaf_payable_item;
		$group_id = isset($group_id) ? $group_id : Company::get_group_id();
		$return  = array();

		foreach($payable_item as $item){
			$temp = $this->decode($this->get('group/'.$group_id.'/'.$item, $params));
			if(isset($temp)){
				$category = $item;
				if($item == 'fee-type'){
					$category = 'fee_type';
				}
				foreach($temp[$category] as $temp_item){
					$product = Product::save_product_from_leaf($temp_item,$category);
					//$product->mandatory_columns_check_by_id($product['id']);
				}
			}
		}

		return $return;
	}

	//with expiry check
	public function get_all_leaf_payable_item_by_group_id($group_id=null)
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$payable_item = static::leaf_payable_item;
		$group_id = isset($group_id) ? $group_id : Company::get_group_id();
		$return  = array();
	
		foreach($payable_item as $item){
			$temp = $this->decode($this->get('group/'.$group_id.'/'.$item, $params));
			if(isset($temp)){
				$category = $item;
				if($item == 'fee-type'){
					$category = 'fee_type';
				}
				foreach($temp[$category] as $temp_item){
					$temp_product['id'] = $temp_item['id_'.$category];
					$temp_product['type'] = $category;
					$temp_product['name'] = $temp_item[$category.'_name'];
					array_push($return, $temp_product);
				}
			}
		}

		return $return;
	}

	public function get_all_leaf_payable_item_model_by_group_id($group_id=null)
	{
    	$params['offset'] = 0;
		$params['limit'] = 999999999;
		$payable_item = static::leaf_payable_item;
		$group_id = isset($group_id) ? $group_id : Company::get_group_id();
		$return  = array();
	
		foreach($payable_item as $item){
			$temp = $this->decode($this->get('group/'.$group_id.'/'.$item, $params));
			if(isset($temp)){
				$category = $item;
				if($item == 'fee-type'){
					$category = 'fee_type';
				}
				foreach($temp[$category] as $temp_item){
					array_push($return, $temp_item);
				}
			}
		}

		return $return;	
    }

	//wip
	public function get_all_leaf_payable_item_combobox($house_id=null)
	{
		$fdata;
		$listing = isset($house_id) ? null :   $this->get_all_leaf_payable_item_by_group_id();
		if(isset($house_id)){
			$listing =  $this->get_house_membership_detail_by_house_id($house_id)['house_fee_items'][0];
			$fdata[$listing['id_fee_type']] = $listing['fee_type_name'];
		}else{
			$fdata = [''=>Language::trans('Please select fee type...')];
			foreach ($listing as $item) {
				$fdata[$item['id']] = $item['name'];
			}
		}

		return $fdata;
	}

	public function get_single_fee_type($id=null)
	{
		$listing = $this->get_fee_type();
		$fdata = [''=>Language::trans('Please select fee type...')];
		if (isset($listing['status_code']) && $listing['status_code']) {
			if (isset($listing['fee_type'])) {
				foreach ($listing['fee_type'] as $fee_type) {
					if ($fee_type['id_fee_type'] == $id) {
						return $fee_type;
					}
				}
			}
		}
	}

	public function get_fee_type_combobox()
	{
		$listing = $this->get_fee_type();
		$fdata = [''=>Language::trans('Please select fee type...')];
		if (isset($listing['status_code']) && $listing['status_code']) {
			if (isset($listing['fee_type'])) {
				foreach ($listing['fee_type'] as $fee_type) {
					$fdata[$fee_type['id_fee_type']] = $fee_type['fee_type_name'];
				}
			}
		}
		return $fdata;
	}

	public function get_member_list()
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		return $this->decode($this->get('group/'.Company::get_group_id().'/member', $params));
	}

	public function get_country_list()
	{
		$params['offset'] = 0;
		$params['limit'] = 999999999;
		$listing = $this->decode($this->get('address/country', $params));
		if ($listing['status_code']) {
			foreach ($listing['country'] as $row) {
				if (isset($row['active']) && $row['active'] == true) {
					$datas[$row['id_country']] = $row['country_name'];
				}
			}
			return $datas;
		}
		return [];
	}

	public function get_country_name($country_id)
	{
		$listing = $this->get_country_list();
		foreach ($listing as $key => $value) {
			if ($key == $country_id) {
				return $value;
			}
		}
	}

	public function get_countries_combobox()
	{
		$listing = $this->get_country_list();
		$datas = [];
		$datas[] = [
			'id'	=>	'',
			'text'	=>	'----------',
			];
		foreach ($listing as $key => $value) {
			$datas[] = [
				'id'	=>	$key,
				'text'	=>	$value,
				];
		}
		return $datas;
	}

	public function get_state_list($country_id)
	{
		$params['offset'] = 1;
		$params['limit'] = 999999999;
		$datas = [];
		$listing = $this->decode($this->get('address/country/'.$country_id.'/state', $params));
		if ($listing['status_code']) {
			foreach ($listing['state'] as $row) {
				$datas[$row['id_state']] = $row['state_name'];
			}
			return $datas;
		}
		return [];
	}

	public function get_state_name($state_id, $country_id)
	{
		$listing = $this->get_state_list($country_id);
		foreach ($listing as $key => $value) {
			if ($key == $state_id) {
				return $value;
			}
		}
	}

	public function get_states_combobox($country_id)
	{
		$listing = $this->get_state_list($country_id);
		$datas = [];
		$datas[] = [
			'id'	=>	'',
			'text'	=>	'----------',
			];
		foreach ($listing as $key => $value) {
			$datas[] = [
				'id'	=>	$key,
				'text'	=>	$value,
				];
		}
		return $datas;
	}

	public function get_city_list($id_state)
	{
		$params['offset'] = 1;
		$params['limit'] = 999999999;
		$datas = [];
		$listing = $this->decode($this->get('address/state/'.$id_state.'/city', $params));
		if ($listing['status_code']) {
			foreach ($listing['city'] as $row) {
				$datas[$row['id_city']] = $row['city_name'];
			}
			return $datas;
		}
		return [];
	}

	public function get_cities_combobox()
	{
		$this->get_city_list();
		$datas = [];
		$datas[] = [
			'id'	=>	'',
			'text'	=>	'----------',
			];
		foreach ($listing as $key => $value) {
			$datas[] = [
				'id'	=>	$key,
				'text'	=>	$value,
				];
		}
		return $datas;
	}

	//wip
	public function get_new_customer_from_leaf_house_by_house_id_or_all($leaf_house_id=null){

		if(isset($leaf_house_id)){
			$house = $this->get_house_by_house_id($leaf_house_id);
			Customer::save_customer_from_leaf_house($house);
		}else{

			$listing = $this->get_houses();
			foreach($listing['house'] as $houses){
				foreach($listing['houses'] as $house){
						Customer::save_customer_from_leaf_house($house);
				}
			}
		}
	}

	public function getUserSessionTokenByEmail($email)
	{ 
		//echo $email;
        $user = $this->get_user_profile_by_email($email);
       
        if($user['status_code'] != 1)
        {
        	return;
        }
        $params =array();
		return $this->decode($this->post('user/get-token/'.$user['id_user'], $params));;		
	}


	public function get_user_profile($session_token)
	{
		$params['session_token'] = $session_token;
		return $this->decode($this->post('user/view', $params));	
	}

	public function get_user_profile_by_email($email)
	{
		return $this->decode($this->post('user/search/email/'.$email));		
	}

	public function get_houses($is_deleted = null,$leaf_group_id=null)
	{
		$params['offset']	=	0;
		$params['limit']	=	9999;
		$leaf_group_id = isset($leaf_group_id) ? $leaf_group_id : Company::get_group_id();
		if(isset($is_deleted)){
			$params['include_deleted_room_member'] = true;
		}
		
		return $this->decode($this->get('group/'.$leaf_group_id.'/house-room', $params));
	}


	public function get_user_house_membership_detail_by_leaf_id_user($leaf_id_user)
	{
		$listing = $this->get_customer_list();
		//dd($listing);
		if($listing['status_code']){
			$house_count = 0;
			foreach($listing['house'] as $house){	
				foreach($house['house_members'] as $member){
					//echo json_encode($member)."<br>";
					if ($member['house_member_id_user'] == $leaf_id_user) {
						//dd($member);
						//for the member with membership
						
						$member = [
										'id_house_member'			=> isset($member['id_house_member']) ? $member['id_house_member'] : 0,
										'leaf_house_id'				=> $house['id_house'],
										'id_house'					=> $house['id_house'],
										'member_detail'				=> $member,
										'is_payable_member'			=> $member['house_member_is_owner'] == true ? true : ($member['house_member_can_make_payment'] == true ? true : false),
										
										];


						if(isset($member['house_fee_items'])){

							$member = [
										'house_fee_items' 	    	=> isset($house['house_fee_items'][0]) ? $house['house_fee_items'][0]  : null ,
										'membership_type' 			=> $house['house_fee_items'][0]['fee_type_name'],
										'membership_price' 			=> $house['house_fee_items'][0]['fee_type_amount'],
										'membership_start_date' 	=> $house['house_fee_items'][0]['fee_type_start_date'],
										'membership_end_date'   	=> $house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_period'			=> $house['house_fee_items'][0]['fee_type_start_date'].' - '.$house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_extend_to_date' =>  date('Y-m-d', strtotime('+'.$house['house_fee_items'][0]['fee_type_frequency_value'].' '.$house['house_fee_items'][0]['fee_type_frequency_unit'], strtotime($house['house_fee_items'][0]['fee_type_expire_date']))),
										'membership_is_valid'   	=> strtotime($house['house_fee_items'][0]['fee_type_expire_date']) - time() > 0 ?  true : false,

									];


							
						}/*else{*/
			
						$room = static::get_room_by_id_house_member($member['id_house_member']);
		
						if(isset($room['id_house_room']))
						{		
								$member['leaf_room_id']			= $room != "None" ? $room['id_house_room'] : 0;
								$membe['is_payable_member']		= true;
											   
						}
						
						/*}*/

						return $member;
					}
				}			
			}
		}	
	}

	public function get_user_house_membership_detail_by_leaf_id_user_live($leaf_id_user , $id_house=null)
	{
		$listing = $this->get_customer_list();
		//dd($listing);
		$id_house = isset($id_house) ? $id_house : 0;
		if($listing['status_code']){
			$house_count = 0;
			foreach($listing['house'] as $house){	
				if($id_house !== 0)
				{
					if($house['id_house'] !== $id_house)
					{
						continue;
					}
				}	
				
				foreach($house['house_members'] as $member){

					if ($member['house_member_id_user'] == $leaf_id_user) {
						if($id_house != 0)
						{
							if( $house['id_house'] != $id_house ){
								continue;
							}
						}
						//for the member with membership
						$member = [
										'id_house_member'			=> isset($member['id_house_member']) ? $member['id_house_member'] : 0,
										'leaf_house_id'				=> $house['id_house'],
										'id_house'					=> $house['id_house'],
										'member_detail'				=> $member,
										'is_payable_member'			=> $member['house_member_is_owner'] == true ? true : ($member['house_member_can_make_payment'] == true ? true : false),
										
										];


						if(isset($member['house_fee_items'])){

							$member = [
										'house_fee_items' 	    	=> isset($house['house_fee_items'][0]) ? $house['house_fee_items'][0]  : null ,
										'membership_type' 			=> $house['house_fee_items'][0]['fee_type_name'],
										'membership_price' 			=> $house['house_fee_items'][0]['fee_type_amount'],
										'membership_start_date' 	=> $house['house_fee_items'][0]['fee_type_start_date'],
										'membership_end_date'   	=> $house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_period'			=> $house['house_fee_items'][0]['fee_type_start_date'].' - '.$house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_extend_to_date' =>  date('Y-m-d', strtotime('+'.$house['house_fee_items'][0]['fee_type_frequency_value'].' '.$house['house_fee_items'][0]['fee_type_frequency_unit'], strtotime($house['house_fee_items'][0]['fee_type_expire_date']))),
										'membership_is_valid'   	=> strtotime($house['house_fee_items'][0]['fee_type_expire_date']) - time() > 0 ?  true : false,

									];


							
						}/*else{*/
			
						$room = static::get_room_by_id_house_member_live($member['id_house_member']);

						$member_model ;
						if(isset($room['house_room_members'])){
							foreach ($room['house_room_members'] as $house_member) {
								if($member['id_house_member'] == $house_member['id_house_member']){
									 $house_member_model = $house_member;
								}
							}
						}


						if(isset($room['id_house_room']))
						{		
								$member['leaf_room_id']			= $room != "None" ? $room['id_house_room'] : 0;
								$house_member_model['is_payable_member']		= true;
								$house_member_model['leaf_room_id'] = isset($member['leaf_room_id']) ? $member['leaf_room_id'] : 0 ;
								$house_member_model['leaf_house_id'] =  isset($member['leaf_house_id']) ? $member['leaf_house_id'] : 0 ;
								$member['house_member_detail'] = $house_member_model;
						}
						
						/*}*/

						return $member;
					}
				}			
			}//end of list foreach
		}	
	}

	//wip
	public function get_user_house_membership_detail_by_user_id($user_id){
	
		$listing = $this->get_customer_list();
		if($listing['status_code']){
			foreach($listing['house'] as $house){	
				foreach($house['house_members'] as $member){
					if ($member['house_member_id_user'] == $user_id) {

						//for the member with membership
						//if(isset($house['house_fee_items'])){
					if(isset($house['house_fee_items'][0]['id_fee_type'])){

						$product = $this->get_product_by_leaf_product_id_and_category($house['house_fee_items'][0]['id_fee_type']);
					}
					
					$member = [
						'id_house'					=> $house['id_house'],
						'member_detail'				=> $member,
						'is_payable_member'			=> $member['house_member_is_owner'] == true ? true : ($member['house_member_can_make_payment'] == true ? true : false),
						'membership_user_age_ranges'=> isset($product['fee_type_user_age_ranges']),
						'house_fee_items' 	    	=> isset($house['house_fee_items'][0]) ? $house['house_fee_items'][0]  : null ,
						'membership_type' 			=> isset($house['house_fee_items'][0]['fee_type_name']) ? $house['house_fee_items'][0]['fee_type_name'] :  '',
						'membership_price' 			=> isset($house['house_fee_items'][0]['fee_type_amount']) ? $house['house_fee_items'][0]['fee_type_amount'] :  '',
						'membership_start_date' 	=> isset($house['house_fee_items'][0]['fee_type_start_date']) ? $house['house_fee_items'][0]['fee_type_start_date'] :  '',
						'membership_end_date'   	=> isset($house['house_fee_items'][0]['fee_type_expire_date']) ? $house['house_fee_items'][0]['fee_type_expire_date'] :  '',
						'membership_period'			=> isset($house['house_fee_items'][0]['fee_type_start_date']) ? $house['house_fee_items'][0]['fee_type_start_date'].' - '.$house['house_fee_items'][0]['fee_type_expire_date'] : '',
						'membership_extend_to_date' => isset($house['house_fee_items'][0]['fee_type_frequency_value']) ? date('Y-m-d', strtotime('+'.$house['house_fee_items'][0]['fee_type_frequency_value'].' '.$house['house_fee_items'][0]['fee_type_frequency_unit'], strtotime($house['house_fee_items'][0]['fee_type_expire_date']))) : '',
						'membership_is_valid'   	=> isset($house['house_fee_items'][0]['fee_type_expire_date']) ? ((strtotime($house['house_fee_items'][0]['fee_type_expire_date']) - time()) > 0 ?  true : false) : false,
						'members'					=> isset($house['house_members']) ? $house['house_members'] : '',
						];

						return $member;
					}
				}
			}
		}	
	}

	//backhere
	public function get_user_house_membership_detail_by_leaf_house_member_id_for_register($leaf_house_member_id)
	{
		$listing = $this->get_customer_list();
		if($listing['status_code']){
			$house_count = 0;
			foreach($listing['house'] as $house){	
				foreach($house['house_members'] as $member){

					if ($member['id_house_member'] == $leaf_house_member_id) {
						
						//for the member with membership
						if(isset($house['house_fee_items'][0]['id_fee_type'])){
		
							$product = $this->get_product_by_leaf_product_id_and_category($house['house_fee_items'][0]['id_fee_type']);
							$member = [
										'id_house'					=> $house['id_house'],
										'member_detail'				=> $member,
										'is_payable_member'			=> $member['house_member_is_owner'] == true ? true : ($member['house_member_can_make_payment'] == true ? true : false),
										'house_fee_items' 	    	=> isset($house['house_fee_items'][0]) ? $house['house_fee_items'][0]  : null ,
										'membership_user_age_ranges' => $product['fee_type_user_age_ranges'],
										'membership_type' 			=> $house['house_fee_items'][0]['fee_type_name'],
										'membership_price' 			=> $house['house_fee_items'][0]['fee_type_amount'],
										'membership_start_date' 	=> $house['house_fee_items'][0]['fee_type_start_date'],
										'membership_end_date'   	=> $house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_period'			=> $house['house_fee_items'][0]['fee_type_start_date'].' - '.$house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_extend_to_date' =>  date('Y-m-d', strtotime('+'.$house['house_fee_items'][0]['fee_type_frequency_value'].' '.$house['house_fee_items'][0]['fee_type_frequency_unit'], strtotime($house['house_fee_items'][0]['fee_type_expire_date']))),
										'membership_is_valid'   	=> strtotime($house['house_fee_items'][0]['fee_type_expire_date']) - time() > 0 ?  true : false,
										];
						}else{
							$room = static::get_room_by_id_house_member($member['id_house_member']);
							$member_model ;
							if(isset($room['house_room_members'])){
								foreach ($room['house_room_members'] as $member) {
									if($member['id_house_member'] == $leaf_house_member_id){
										 $member_model = $member;
									}
								}
							}
							
							$member = [
										'id_house'					=> $house['id_house'],
										'leaf_room_id'				=> $room != "None" ? $room['id_house_room'] : 0,
										'member_detail'				=> isset($member_model) ? $member_model : "",
										'is_payable_member'			=> true,
							];
						}
						return $member;
					}
				}			
			}
		}	
	}

	public function get_user_house_membership_detail_by_leaf_id_user_for_register($leaf_id_user)
	{

		$listing = $this->get_customer_list();
		
		if($listing['status_code']){
			$house_count = 0;
			foreach($listing['house'] as $house){	
				foreach($house['house_members'] as $member){

					if ($member['house_member_id_user'] == $leaf_id_user) {
						//for the member with membership
						if(isset($house['house_fee_items'][0]['id_fee_type'])){
							$member = [
										'id_house'					=> $house['id_house'],
										'member_detail'				=> $member,
										'is_payable_member'			=> $member['house_member_is_owner'] == true ? true : ($member['house_member_can_make_payment'] == true ? true : false),
										'house_fee_items' 	    	=> isset($house['house_fee_items'][0]) ? $house['house_fee_items'][0]  : null ,
										'membership_type' 			=> $house['house_fee_items'][0]['fee_type_name'],
										'membership_price' 			=> $house['house_fee_items'][0]['fee_type_amount'],
										'membership_start_date' 	=> $house['house_fee_items'][0]['fee_type_start_date'],
										'membership_end_date'   	=> $house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_period'			=> $house['house_fee_items'][0]['fee_type_start_date'].' - '.$house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_extend_to_date' =>  date('Y-m-d', strtotime('+'.$house['house_fee_items'][0]['fee_type_frequency_value'].' '.$house['house_fee_items'][0]['fee_type_frequency_unit'], strtotime($house['house_fee_items'][0]['fee_type_expire_date']))),
										'membership_is_valid'   	=> strtotime($house['house_fee_items'][0]['fee_type_expire_date']) - time() > 0 ?  true : false,
										];
						}else{
							$room = static::get_room_by_id_house_member($member['id_house_member']);
							$member_model ;
							foreach ($room['house_room_members'] as $member) {
								if($member['house_member_id_user'] == $leaf_id_user){
									 $member_model = $member;
								}
							}
							$member = [
										'id_house'					=> $house['id_house'],
										'leaf_room_id'				=> $room != "None" ? $room['id_house_room'] : 0,
										'member_detail'				=> $member_model,
										'is_payable_member'			=> true,
							];
						}
						return $member;
					}
				}			
			}
		}	
	}


	public function get_user_house_membership_detail_by_leaf_id_user_for_register_2($leaf_id_user,$is_live=null)
	{
		$listing = $this->get_customer_list();
		if($listing['status_code']){
			$house_count = 0;
			foreach($listing['house'] as $house){	
				foreach($house['house_members'] as $member){

					if ($member['house_member_id_user'] == $leaf_id_user) {
						//for the member with membership
						$member = [
										'id_house'					=> $house['id_house'],
										'member_detail'				=> $member,
								  ];

						if(isset($house['house_fee_items'][0]['id_fee_type'])){
							$member = [
										'is_payable_member'			=> $member['house_member_is_owner'] == true ? true : ($member['house_member_can_make_payment'] == true ? true : false),
										'house_fee_items' 	    	=> isset($house['house_fee_items'][0]) ? $house['house_fee_items'][0]  : null ,
										'membership_type' 			=> $house['house_fee_items'][0]['fee_type_name'],
										'membership_price' 			=> $house['house_fee_items'][0]['fee_type_amount'],
										'membership_start_date' 	=> $house['house_fee_items'][0]['fee_type_start_date'],
										'membership_end_date'   	=> $house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_period'			=> $house['house_fee_items'][0]['fee_type_start_date'].' - '.$house['house_fee_items'][0]['fee_type_expire_date'],
										'membership_extend_to_date' =>  date('Y-m-d', strtotime('+'.$house['house_fee_items'][0]['fee_type_frequency_value'].' '.$house['house_fee_items'][0]['fee_type_frequency_unit'], strtotime($house['house_fee_items'][0]['fee_type_expire_date']))),
										'membership_is_valid'   	=> strtotime($house['house_fee_items'][0]['fee_type_expire_date']) - time() > 0 ?  true : false,
										];
						}/*else{*/
							$room = static::get_room_by_leaf_id_user($leaf_id_user,$is_live);
//dd($room);
							$member_model ;
							if(isset($room['id_house_room']))
							{
				
									if($room == 'None')
									{
										$member_model = 'None';		
									}else{
										$meter = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);
										foreach ($room['house_room_members'] as $member) {
											if($member['house_member_id_user'] == $leaf_id_user){
												$member_model = $member;
											}
										}

										$room['house_subgroup'] = $house['house_subgroup'];
										$room['house_city'] = $house['house_city'];
										$room['house_unit'] = $house['house_unit'];
									}

									$member = [
												'room'						=> $room,
												'id_house'					=> $house['id_house'],
												'leaf_room_id'				=> $room != "None" ? $room['id_house_room'] : 0,
												'member_detail'				=> $member_model,
												'house_room_type'			=> $room['house_room_type'],
												'is_payable_member'			=> true,
												'house_room_members'         => $room['house_room_members'],
												'meter'						=> isset($meter['id'])	 ? $meter : null,
											  ];
									
							}
						

							
						/*}*/
						return $member;
					}
				}			
			}
		}	
	}

	public static function  get_room_by_leaf_id_user($leaf_id_user,$is_live=null)
	{
		if ($leaf_id_user!=0) {
		        
		        $house   =   new House();
				$leaf_api = new LeafAPI();

				if(!isset($is_live))
				{
					$fdata      =   $house->get_houses(true);
				}else{
					$fdata      =   $leaf_api->get_houses(true);
				}

		        if ($fdata['status_code']) {
		            if (isset($fdata['house']) && $houses = $fdata['house']) {
		                foreach ($houses as $house) {
		                    foreach ($house['house_rooms'] as $room) {
		                    	foreach ($room['house_room_members'] as $member) {
		                    		if ($member['house_member_id_user'] == $leaf_id_user) {
	                    				return $room;
	                    			}
		                    	}	
		                    }
		                }
		            }
		        }
			}
			return "None";
	}

	
	
	//temp
	public static function get_all_stayed_room_by_id_house_member($id_house_member){

		 $house   =   new House();
		 $fdata      =   $house->get_houses(true);
		 $listing    =   array();

		if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
						foreach($house['house_rooms'] as $room){ 
							foreach ($room['house_room_members'] as $member) {
								if($member['id_house_member'] == $id_house_member){

									$temp['house_rooms'] = $room;
									$temp['house_room_type'] = $room['house_room_type'];
									$temp['id_house_room'] = $room['id_house_room'];
									$temp['id_house'] = $house['id_house'];
									$temp['house_unit'] = $house['house_unit'];
									$temp['house_subgroup'] = $house['house_subgroup'];
									$temp['house_room_member_start_date'] = $member['house_room_member_start_date'];
									$temp['house_room_member_end_date'] = $member['house_room_member_end_date'];
									$temp['house_room_member_deleted'] = $member['house_room_member_deleted'];
									
									array_push($listing, $temp);
								}
							}
						}
					}
				}
			}

		return $listing;
	}

	public static function get_all_stayed_room_by_leaf_id_user($leaf_id_user){

		 $house   =   new House();
		 $fdata      =   $house->get_houses(true);
		 $listing    =   array();

		if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
						foreach($house['house_rooms'] as $room){ 
							foreach ($room['house_room_members'] as $member) {
								if($member['house_member_id_user'] == $leaf_id_user){
				
									$temp['house_rooms'] = $room;
									$temp['house_room_type'] = $room['house_room_type'];
									$temp['id_house_room'] = $room['id_house_room'];
									$temp['id_house'] = $house['id_house'];
									$temp['house_unit'] = $house['house_unit'];
									$temp['house_subgroup'] = $house['house_subgroup'];
									$temp['house_room_member_start_date'] = $member['house_room_member_start_date'];
									$temp['house_room_member_end_date'] = $member['house_room_member_end_date'];
									$temp['house_room_member_deleted'] = $member['house_room_member_deleted'];
									
									array_push($listing, $temp);
								}
							}
						}
					}
				}
			}

		return $listing;
	}

	public function get_houses_with_meter_register_detail($house_id=null,$is_report=null){

		  $house   =   new House();
		  if(isset($is_report)){
		  	$fdata      =   $this->get_houses(true);
		  }else{
		  	$fdata      =   $house->get_houses(true);
		  }
		  

		  $meter_reading = new MeterReading();
		  $listing = [];

		  if ($fdata['status_code']) {

            if (isset($fdata['house']) && $houses = $fdata['house']) {

                foreach ($houses as $house) {

						$i = 0;
				
						if(!isset($house['house_rooms'])){continue;}
						foreach($house['house_rooms'] as $room){

							$meter = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);

							if(isset($meter)){
							
								$house['house_rooms'][$i]['meter'] = $meter;
								//added
								//$house['house_rooms'][$i]['meter']['last_reading_at'] = '';
								//$house['house_rooms'][$i]['meter']['last_reading'] =  0;
								//$meter_reading_model = $meter_reading->get_last_meter_reading_model($meter['id']);


								//$house['house_rooms'][$i]['meter']['monthly_usages'] =  MeterReading::get_monthly_meter_reading_by_id($meter['id']);   
								$house['house_rooms'][$i]['meter']['monthly_usages'] = 0;
								/*if(isset($meter_reading_model) ){
									$house['house_rooms'][$i]['meter']['last_reading_at'] = $meter_reading_model['created_at'];
									$house['house_rooms'][$i]['meter']['last_reading'] 	  = $meter_reading_model['current_meter_reading'];
								}*/


								//$house['house_rooms'][$i]['meter']['monthly_usages'] =  0;   

								/*if(isset($meter_reading_model) ){
									$house['house_rooms'][$i]['meter']['last_reading_at'] = 0;
									$house['house_rooms'][$i]['meter']['last_reading'] 	  = 0;
								}*/

							}else{
								$house['house_rooms'][$i]['meter'] = null;
							}
			
							$i ++;
						}
						
						if(isset($house_id)){
							if($house['id_house'] == $house_id){
								return $house;
							}
						}

						

						array_push($listing , $house);
						
                }
            }

			return $listing;
        }

		return null;
	}

	public function get_user_room_profile()
	{
		return $this->decode($this->get('group/'.Company::get_group_id().'/house-room'));
	}




	public static function get_self_houses($is_live = null)
	{

		$house   =   new House();
		$leaf_api = new LeafAPI();

		if(!isset($is_live))
		{
			$fdata      =   $house->get_houses(true);
		}else{
			$fdata      =   $leaf_api->get_houses(true);
		}


        $return['']	=   Language::trans('Please select room no...');
        
        if ($fdata['status_code']) {
            if (isset($fdata['house']) && $houses = $fdata['house']) {
                foreach ($houses as $house) {
                    foreach ($house['house_rooms'] as $room) {
                    	foreach ($room['house_room_members'] as $member) {
                    		if ($member['house_member_id_user'] == Auth::user()->leaf_id_user) {
                    			$return[$room['house_room_name']] = $room['house_room_name'];
                    		}
                    	}
                    }
                }
            }
        }
        return $return;
	}

	public function convert_room_name_to_id($room_name=null)
	{
		if ($room_name) {
	        $house   =   new House();
		    $fdata      =   $house->get_houses(true);
	        $return['']	=   Language::trans('Please select room no...');
	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
                    		if ($room['house_room_name'] == $room_name) {
                    			return $room['id_house_room'];
                    		}
	                    }
	                }
	            }
	        }
		}
		return 0;
	}


	/*public static function  get_member_detail_by_member_id($leaf_room_id , $is_live =null)
	{
		if ($leaf_room_id!=0) {
			

			$house   =   new House();
			$leaf_api = new LeafAPI();
			$meter_reading = new MeterReading();

			if(!isset($is_live))
			{
				$fdata      =   $house->get_houses(true);
			}else{
				$fdata      =   $leaf_api->get_houses(true);
			}

	        $return['']	=   Language::trans('Please select room no...');

	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
                    		if ($room['id_house_room'] == $leaf_room_id) {
                    			return $house['house_unit'].' '.Language::trans('room').' '.$room['house_room_name'];
                    		}
	                    }
	                }
	            }
	        }
		}
		return "None";
	}*/


	public static function  get_room_by_leaf_room_id($leaf_room_id)
	{
		if ($leaf_room_id!=0) {
	        $house   =   new House();
		    $fdata      =   $house->get_houses(true);
	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
                    		if ($room['id_house_room'] == $leaf_room_id) {
                    			$room['house_unit'] = $house['house_unit'];
                    			return $room;
                    		}
	                    }
	                }
	            }
	        }
		}
		return "None";
	}

	public static function  get_room_by_id_house_member_live($id_house_member)
	{
		if ($id_house_member!=0) {
	        $house   =   new House();
	        $leaf_api = new LeafAPI();
		    $fdata      =   $leaf_api->get_houses(true);

	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
	                    	foreach ($room['house_room_members'] as $member) {
	                    		if ($member['id_house_member'] == $id_house_member && $member['house_room_member_deleted'] == 0) {
	                    			$room['member'] = $member;
                    				return $room;
                    			}
	                    	}	
	                    }
	                }
	            }
	        }
		}
		return "None";
	}

	public static function  get_room_by_id_house_member($id_house_member)
	{
		if ($id_house_member!=0) {
	        $house   =   new House();
		    $fdata      =   $house->get_houses(true);

	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
	                    	foreach ($room['house_room_members'] as $member) {
	                    		if ($member['id_house_member'] == $id_house_member) {
	                    			$room['member'] = $member;
                    				return $room;
                    			}
	                    	}	
	                    }
	                }
	            }
	        }
		}
		return "None";
	}

	public static function get_house_by_house_id($id)
	{
        $house   =   new House();
		$fdata      =   $house->get_houses(true);
        $meter_reading = new MeterReading();

        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
            	$houses = $fdata['house'];
                foreach ($houses as $house) {
					if( $house['id_house'] == $id){
						$i = 0;
						foreach($house['house_rooms'] as $room){
							$meter = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);
							if(isset($meter['id'])){
								$house['house_rooms'][$i]['meter'] = $meter;
							}

							$member_count = 0;
							foreach ($room['house_room_members'] as $member) {
								//$house['house_rooms'][$i]['house_room_members'][$member_count]['id_house_room'] = (string) $room['id_house_room'];
								$member_count ++;
							}
							/*$meter_reading_model = $meter_reading->get_last_meter_reading_model($meter['id']);
							$house['house_rooms'][$i]['meter']['last_reading_at'] = $meter_reading_model != false ? $meter_reading_model['current_meter_reading'] : '';
							$house['house_rooms'][$i]['meter']['last_reading'] 	  = $meter_reading_model != false ? $meter_reading_model['created_at'] : '';
							dd($house);*/
							$i ++;
						}

						return $house;
					}
                }
            }
        }
        return null;
	}

	public static function get_room_meter_by_leaf_room_id($leaf_room_id)
	{
        $house   =   new House();
		$fdata      =   $house->get_houses(true);
        $meter_reading = new MeterReading();

        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
            	$houses = $fdata['house'];
                foreach ($houses as $house) {
					foreach($house['house_rooms'] as $room){
						if($room['id_house_room'] == $leaf_room_id){
							$meter = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);
							$room['meter'] = $meter;
							$room['house'] = $house;
							return $room;
						}
					}	
                }
            }
        }
        
        return null;
	}


	public static function get_house_by_room_id($id)
	{
        $house   =   new House();
		$fdata      =   $house->get_houses(true);
        $meter_reading = new MeterReading();

        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
            	$houses = $fdata['house'];
                foreach ($houses as $house) {
                	foreach($house['house_rooms'] as $room){
                	    if($room['id_house_room'] == $id){
							return $house;
						}
                	}
                }
            }
        }
        return null;
	}

	public static function get_house_by_member_id($id , $is_live =null)
	{
		$house   =   new House();
		$leaf_api = new LeafAPI();
		$meter_reading = new MeterReading();

		if(!isset($is_live))
		{
			$fdata      =   $house->get_houses(true);
		}else{
			$fdata      =   $leaf_api->get_houses(true);
		}
		
        
        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
            	$houses = $fdata['house'];
                foreach ($houses as $house) {
                	
                	foreach($house['house_rooms'] as $room){
                		foreach ($room['house_room_members'] as $member) {
                			if($member['id_house_member'] == $id){
								return $house;
							}
                		}   
                	}
                }
            }
        }
        return null;
	}

	public static function get_member_detail_list($is_live =null)
	{
		$return = array();
        $house   =   new House();
		$leaf_api = new LeafAPI();
		$meter_reading = new MeterReading();

		if(!isset($is_live))
		{
			$fdata      =   $house->get_houses(true);
		}else{
			$fdata      =   $leaf_api->get_houses(true);
		}

        //dd($fdata);
        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
            	$houses = $fdata['house'];
                foreach ($houses as $house) {
                	$i = 0;
                	//dd($house);
                	foreach($house['house_rooms'] as $room){
                		$member_count = 0;
						foreach ($room['house_room_members'] as $member) {
							//echo $member['house_member_name'].":".$member['id_house_member']."<br>";	
							//$house['house_rooms'][$i]['house_room_members'][$member_count]['id_house_room'] = $room['id_house_room'];
							//$house['house_rooms'][$i]['house_room_members'][$member_count]['leaf_room_id'] = $room['id_house_room'];
							$return[$member['id_house_member']]['member'] = $member;
            				$return[$member['id_house_member']]['leaf_house_id']	= $house['id_house'];
            				$return[$member['id_house_member']]['leaf_room_id'] = $room['id_house_room'];
            				$return[$member['id_house_member']]['room'] = $room;
							
							
							$member_count ++;
						}  
                	}

                	$i++;
                }
            }
        }
        return $return;
	}


	public static function get_member_detail_by_member_id($id , $is_live =null)
	{
        $house   =   new House();
		$leaf_api = new LeafAPI();
		$meter_reading = new MeterReading();

		if(!isset($is_live))
		{
			$fdata      =   $house->get_houses(true);
		}else{
			$fdata      =   $leaf_api->get_houses(true);
		}

        //dd($fdata);
        if ($fdata['status_code']) {
            if (isset($fdata['house'])) {
            	$houses = $fdata['house'];
                foreach ($houses as $house) {
                	$i = 0;
                	//dd($house);
                	foreach($house['house_rooms'] as $room){
                		$member_count = 0;
						foreach ($room['house_room_members'] as $member) {
							//echo $member['house_member_name'].":".$member['id_house_member']."<br>";
							if($member['id_house_member'] == $id){
								
								//$house['house_rooms'][$i]['house_room_members'][$member_count]['id_house_room'] = $room['id_house_room'];
								//$house['house_rooms'][$i]['house_room_members'][$member_count]['leaf_room_id'] = $room['id_house_room'];
                				$member['leaf_house_id']	= $house['id_house'];
                				$member['leaf_room_id'] = $room['id_house_room'];
                				$member['room'] = $room;
								return $member;
							}
							$member_count ++;
						}  
                	}

                	$i++;
                }
            }
        }
        return null;
	}

	public static function  get_user_stay_detail_for_twin_room_by_leaf_room_id($leaf_room_id  , $is_live =null)
	{
		$return = array();

		//$house   =   new House();
		$leaf_api = new LeafAPI();

		if(!isset($is_live))
		{
			$fdata      =   $house->get_houses(true);
		}else{
			$fdata      =   $leaf_api->get_houses(true);
		}

		//dd($fdata);
		if ($leaf_room_id!=0) {
	       // $house   =   new House();
			//$fdata   =   $house->get_houses(true);     
	        
	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
	                    	//dd($room);
                    		if ($room['id_house_room'] == $leaf_room_id) {
								//if($room['house_room_type'] == static::label_twin_room){
									foreach($room['house_room_members'] as $member){
										$temp['leaf_id_user'] = $member['house_member_id_user'] ;
										$temp['start_date'] =  $member['house_room_member_start_date'];
										$temp['leaf_room_id'] =  $room['id_house_room'];
										array_push($return , $temp);
									}			
								//}
								//dd($return);
                    			return $return;
                    		}
	                    }
	                }
	            }
	        }
		}
		return "None";
	}


	public function get_user_room($session_token)
	{
		$params['session_token'] = $session_token;
		return $this->decode($this->post('user/house-room', $params));		
	}

	public function decode($json)
	{
		return json_decode($json, true);
	}

	public function get($url, $params=null)
	{
		return $this->curl(static::method_get, $url, $params);
	}

	public function getPowerManagement($url, $params=null)
	{
		return $this->curlPowerManagement(static::method_get, $url, $params);
	}

	public function post($url, $params=null)
	{
		return $this->curl(static::method_post, $url, $params);
	}

	public function peter_login()
	{
		$params['user_email'] = 'peterooi83@gmail.com';
		$params['user_password'] = '123123';
		return $this->login($params);
	}

	public function sunway_tester()
	{
		$params['user_email'] = 'peterooi83@gmail.com';
		$params['user_password'] = '123123';
		return $this->login($params);
	}

	public function post_member_status_update_by_leaf_product_id_and_id_house($leaf_product_id , $id_house){

		 $house 	            = $this->get_house_by_house_id($id_house);
		 $leaf_product_to_update = ""	;
		 $house_membership = $house['house_fee_items'];

		 if(count($house_membership) >= 1)
		 {  //case for single item
		 	foreach ($house_membership as $item) {
		 		if($item['id_fee_type'] == $leaf_product_id){
		 			$leaf_product_to_update = $item;
		 			break;
		 		}
		 	}		 	
		 }

		 if($leaf_product_to_update == null){
		 	return false;
		 }

  		 $expire_date           = date('Y-m-d', strtotime('+'.$leaf_product_to_update['fee_type_frequency_value'].' '.$leaf_product_to_update['fee_type_frequency_unit'], strtotime($leaf_product_to_update['fee_type_expire_date'])));
  		 $params["expire_date"] = $expire_date;
  		 $params["id_fee_type"] = $leaf_product_to_update['id_fee_type'];

  		 return $this->decode($this->curl(static::method_post, 'group/'.Company::get_group_id().'/house/'.$id_house.'/fee-type-extend-expire', $params));

	}

	public function post_membership_status_by_payment_receipt_id($payment_received_id){

		$payment_received_model = ARPaymentReceived::find($payment_received_id);
		$customer = Customer::find($payment_received_model['customer_id']);

		if($customer)
		{
			$model_item = $payment_received_model->items;
			foreach ($model_item as $item) {
				$product = $item->product;
				//method below ll check if the product is membership item per house
				$this->post_member_status_update_by_leaf_product_id_and_id_house($product['leaf_product_id'],$customer['id_house']);
				
			}

		}else
		{
			return null;
		}
		
	}



	public function login($params)
	{
		return $this->decode($this->curl(static::method_post, 'user/login', $params));
	}

	public function curl($method, $url, $params=null)
	{
		$params['app_secret'] = static::app_secret;
		//echo json_encode($params)."<br>";
        // create curl resource 
        $ch = curl_init(); 
        $url = static::url.$url;

        if ($method == static::method_get) {
        	$url .= '?';
        	foreach ($params as $key => $value) {
        		$url .= $key.'='.$value.'&';
        	}
        }

        // set url 
        //echo $url."<br>";
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == static::method_post) {
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        // $result contains the output string 
        $result = curl_exec($ch);      
//dd($result);
        //echo json_encode($result)."<br>";
        // close curl resource to free up system resources 
        curl_close($ch);
      
        return $result;
	}

	public function curlPowerManagement($method, $url, $params=null)
	{
		$params['app_secret'] = static::app_secret;
        // create curl resource 
        $ch = curl_init(); 

        if ($method == static::method_get) {
        	$url .= '?';
        	foreach ($params as $key => $value) {
        		$url .= $key.'='.$value.'&';
        	}
        }

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == static::method_post) {
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        // $result contains the output string 
        $result = curl_exec($ch);      

        // close curl resource to free up system resources 
        curl_close($ch);
      
        return $result;
	}

	public static function  get_room_name_by_leaf_room_id($leaf_room_id , $is_live =null)
	{
		if ($leaf_room_id!=0) {
			

			$house   =   new House();
			$leaf_api = new LeafAPI();
			$meter_reading = new MeterReading();

			if(!isset($is_live))
			{
				$fdata      =   $house->get_houses(true);
			}else{
				$fdata      =   $leaf_api->get_houses(true);
			}


	        //$house   =   new House();
		   // $fdata      =   $house->get_houses(true);
	        $return['']	=   Language::trans('Please select room no...');

	        if ($fdata['status_code']) {
	            if (isset($fdata['house']) && $houses = $fdata['house']) {
	                foreach ($houses as $house) {
	                    foreach ($house['house_rooms'] as $room) {
                    		if ($room['id_house_room'] == $leaf_room_id) {
                    			return $house['house_unit'].' '.Language::trans('room').' '.$room['house_room_name'];
                    		}
	                    }
	                }
	            }
	        }
		}
		return "None";
	}

}