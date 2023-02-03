<?php

namespace App;

use App\Opencart\Product as OCProduct;
use App\Opencart\OCSetting as OCSetting;

class SkyNetAPI extends ExtendModel
{
	const method_get = 'get';
	const method_post = 'post';
	const skynet_token = 'P35E5530CB1ECAOCBFSP';
	const app_secret = 'P5lsZKtSyQ3oV9mIQvzEDL1crszSKc4kO6i1ob8HfRLVE8RmU5Ms0RW11caQ0aXu';
	const url = 'http://api.skynet.com.my/api/sn/pub/';
	const print_awb_url = 'http://api.skynet.com.my/api/Skynet/PrintAWB';
	//const create_delivery_order_url = 'http://stn.skynetict.com/api/Skynet/createdeliveryorder';
	const create_delivery_order_url = 'http://api.skynet.com.my/api/Skynet/createPickupRequest';
	const tracking_url = 'AWBTracking/';
	const create_do_mandatory_column = array("Awbnumber","Pieces","Weight","Price","PickupDate","ShipperName","ShipperAdd1","ShipperAdd2","ShipperDestCode","ConsigneeName","ConsigneeAdd1","ConsigneeAdd2","ConsigneeDestCode","ConsigneeTelNo");
	const create_do_column = array("ProductType","Remark","ShipperAdd3","ShipperAdd4","ConsigneeAdd3","ConsigneeAdd4","ConsigneeEmail");
	const print_awb_column = array("access_token","accountNo", "printFormat","shipperRef","ShipmentType","CODType","CODAmount", "Pieces","Weight","Contents","CurrencyCode", "Value","ShippingDate","ShipperName","ShipperAdd1","ShipperAdd2","ShipperAdd3","ShipperAdd4","ShipperTelNo","ShipperPostCode","ConsigneeName", "ConsigneeAdd1","ConsigneeAdd2","ConsigneeAdd3", "ConsigneeAdd4","ConsigneePostCode", "ConsigneeTelNo","DepartmentCode"
		);



	public static function save_base64_to_destination($base64_string , $output_file)
	{

		$ifp = fopen( $output_file, 'wb' ); 
	    // split the string on commas
	    // $data[ 0 ] == "data:image/png;base64"
	    // $data[ 1 ] == <actual base64 string>
	    $data = explode( ',', $base64_string );
	    // we could add validation here with ensuring count( $data ) > 1
	    fwrite( $ifp, base64_decode( $data[ 0 ] ) );
	    // clean up the file resource
	    fclose( $ifp ); 

	    return $output_file;

	}

  	const awb_to_oc_mappers = ['AWBNo'=>'awb_no','shipperRef'=>'shipper_ref','printAWB'=>'photo_base_64', 'awbImageFile' => 'photo_path' ,'order_id'=>'order_id'];
	public static function save_sticker_info($awb_success_return)
	{
		if($awb_success_return['status'] == 'OK')
		{
			$output_file = static::awb_sticker_folder.'/'.$awb_success_return['order_id'];
			$awb_success_return['awbImageFile'] = static::save_base64_to_destination($awb_success_return['printAWB'] , $output_file );

			foreach ($awb_to_oc_mappers as $key => $value)
			{
				$data[$value] = $awb_success_return[$key];
 			}

			$this->db->query("INSERT INTO e_delivery_sticker SET order_id = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', dedicated_delivery_order_id = '" . (isset($data['dedicated_delivery_order_id']) ? (int)$data['dedicated_delivery_order_id'] : 0) . "', photo_path = '" . (isset($data['photo_path']) ? $this->db->escape($data['photo_path']) : '') . "', photo_base_64 = '" . (isset($data['photo_base_64']) ? $this->db->escape($data['photo_base_64']) : '') . "', awb_no  = '" . (isset($data['awb_no']) ? $this->db->escape($data['awb_no']) : '') . "', shipper_ref = '" . (isset($data['shipper_ref']) ? $this->db->escape($data['shipper_ref']) : ''). "', created_at = NOW(), updated_at = NOW()");
			
			
		}
	}

	public  function print_awb_test()
	{
		$url = static::print_awb_url;
		$params['access_token'] = static::skynet_token;
		$params['accountNo'] = static::skynet_account_no;
		$params['printFormat'] = "Sticker";
		$params['shipperRef'] = "Goh Khai Yet";
		$params['ShipmentType'] = "Parcel";
		$params['CODType'] = "";
		$params['CODAmount'] = "";
		$params['Pieces'] = 1;
		$params['Weight'] = 15.00;
		$params['Contents'] = "clothes";
		$params['CurrencyCode'] = "RM";
		$params['Value'] = "100.00";
		$params['ShippingDate'] = "2020-09-13";
		$params['ShipperName'] = "Krystle Teoh";
		$params['ShipperAdd1'] = "Unit 3-11, Tower A";
		$params['ShipperAdd2'] = "";
		$params['ShipperAdd3'] = "";
		$params['ShipperAdd4'] = "";
		$params['ShipperTelNo'] = "0167758081";
		$params['ShipperPostCode'] = 81500;
		$params['ConsigneeName'] = "Krystle Te";
		$params['ConsigneeAdd1'] = "DH 567 ,";
		$params['ConsigneeAdd2'] = "811500 Pekan Nanas,";
		$params['ConsigneeAdd3'] = "Johor";
		$params['ConsigneeAdd4'] = "";
		$params['ConsigneePostCode'] = 81500;
		$params['ConsigneeTelNo'] = "0167758081";
		//$params['DepartmentCode'] =  "HR";
		//echo json_encode($params);
		//dd("S");

		return $this->decode($this->post($url, $params));
	}

	public /*static*/ function get_skynet_payable_by_volumetric_weight($volumetric_weight)
	{
		$delivery_type = 'parcel';
    	$charges = $delivery_type  == 'document' ?  static::calculate_document_fee($volumetric_weight) : static::calculate_parcel_fee($volumetric_weight) ;
    	$data = ['volumetric_weight'=>$volumetric_weight, 'charges'=> $charges , 'sst' => $charges * static::SST_RATE , 'total' => $charges + $charges * static::SST_RATE];

    	return $data;
	}

	public /*static*/ function get_skynet_payable_by_dimension($dimension)
	{
		$delivery_type = 'parcel';
		$volumetric_weight = static::get_volumetric_weight_by_dimension($dimension);
    	$charges = $delivery_type  == 'document' ?  static::calculate_document_fee($volumetric_weight) : static::calculate_parcel_fee($volumetric_weight) ;
    	$data = ['volumetric_weight'=>$volumetric_weight, 'charges'=> $charges , 'sst' => $charges * static::SST_RATE , 'total' => $charges + $charges * static::SST_RATE];

    	return $data;
	}

	public  function get_delivery_fee_info_by_cart_product($cart_product_listing , $shipping_flag=null)
	{
		$shipping_flag = isset($shipping_flag) ? $shipping_flag : true;
		$cart_subtotal =0;
		$cart_delivery_subtotal =0;
		/*echo json_encode($cart_product_listing);
		dd("!2");*/
		$delivery_info_by_shop = array();
		$this->load->model('customerpartner/information');
		$this->load->model('leaf/sky_net_api');
		foreach ($cart_product_listing as $product){
			$customer_partner_model = $this->model_customerpartner_information->getSellerInformationsByProductId($product['product_id']);
			$customer_partner_model[0] = isset($customer_partner_model[0]) ? $customer_partner_model[0] : 0;
			$dimension['length'] = $product['length'];
			$dimension['width'] = $product['width'];
			$dimension['height'] = $product['height'];
			$volumetric_weight = $this->model_leaf_sky_net_api->get_volumetric_weight_by_dimension($dimension);
			$delivery_info_by_shop[$customer_partner_model[0]['customer_id']]['information'] = isset($delivery_info_by_shop[$customer_partner_model[0]['customer_id']]['information']) ? $delivery_info_by_shop[$customer_partner_model[0]['customer_id']]['information'] : array();

			$temp = ['dimension' => $dimension , 'product_id' => $product['product_id']  , 'actual_total' => $product['actual_total'] , 'volumetric_weight' => $volumetric_weight * $product['quantity']  , 'customer_id' => $customer_partner_model[0]['customer_id'] ];
			array_push($delivery_info_by_shop[$customer_partner_model[0]['customer_id']]['information'], $temp );

		}

		$delivery_fee_info_arr = array();
		foreach ($delivery_info_by_shop as $key => $information_listing)
		{
			$product_subtotal= 0;
			$total_volumetric_weight  = 0;
			foreach($information_listing as $seller_information)
			{

				foreach($seller_information as $information)
				{
						$delivery_fee_info_arr[$key] = isset($result[$key]) ? $result[$key] : array();

						$delivery_fee_info_arr[$key] = [
											'cart_delivery_subtotal'		=> isset($result[$key]['cart_delivery_subtotal']) ? $result[$key]['cart_delivery_subtotal'] : 0 ,
											'cart_delivery_subtotal_txt'		=> isset($result[$key]['cart_delivery_subtotal_txt']) ? $result[$key]['cart_delivery_subtotal_txt'] : '' ,
											'product_subtotal'		=> isset($result[$key]['product_subtotal']) ? $result[$key]['product_subtotal'] : 0 ,
											'product_subtotal_txt'		=> isset($result[$key]['product_subtotal_txt']) ? $result[$key]['product_subtotal_txt'] : '' ,
											'total_volumetric_weight' => isset($result[$key]['total_volumetric_weight']) ? $result[$key]['total_volumetric_weight'] : 0 ,
											'total_payable' => isset($result[$key]['total_payable']) ? $result[$key]['total_payable'] : 0 ,
											'total_payable_txt' => isset($result[$key]['total_payable']) ? $result[$key]['total_payable'] : '' ,
											'payment_info'  => isset($result[$key]['payment_info']) ? $result[$key]['payment_info'] : '',
											'customer_id'	=> isset($result[$key]['paymentcustomer_id_info']) ? $result[$key]['customer_id'] : $key,
										];

						$product_subtotal +=   $information['actual_total'];
						$total_volumetric_weight += $information['volumetric_weight'];
						/*echo $information['product_id'].'='. $information['volumetric_weight']."<br>";
						echo $product_total."<br>";*/		
				}

				$payable  = $this->model_leaf_sky_net_api->get_skynet_payable_by_volumetric_weight($total_volumetric_weight);
				
				$delivery_fee_info_arr[$key]['total_volumetric_weight'] =   $shipping_flag == true ? number_format($total_volumetric_weight, 3, '.', '') : '-';
				$delivery_fee_info_arr[$key]['total_payable'] +=  $payable['total'];
				$delivery_fee_info_arr[$key]['total_payable_txt'] =   $shipping_flag == true ? $this->currency->format( $payable['total'],$this->session->data['currency']) : '-';
				$delivery_fee_info_arr[$key]['product_subtotal'] = $product_subtotal;
				$delivery_fee_info_arr[$key]['product_subtotal_txt'] =   $this->currency->format( $product_subtotal,$this->session->data['currency']);
				
				$delivery_fee_info_arr[$key]['payment_info'] =  $payable;
				$delivery_fee_info_arr[$key]['cart_delivery_subtotal'] =  $shipping_flag == true ? $delivery_fee_info_arr[$key]['total_payable'] + $delivery_fee_info_arr[$key]['product_subtotal'] : $delivery_fee_info_arr[$key]['product_subtotal_txt'] ;
				$delivery_fee_info_arr[$key]['cart_delivery_subtotal_txt'] =  $this->currency->format( $delivery_fee_info_arr[$key]['cart_delivery_subtotal'],$this->session->data['currency']);

			}
		}

		return $delivery_fee_info_arr;
	}

	public static function get_volumetric_weight_by_dimension($dimension)
	{// echo json_encode($dimension)."<br>";
		$volumetric_weight = 0;
		$counter = 0;

		foreach($dimension as $key => $value){
			if($counter == 2){
				$volumetric_weight = ($dimension['length'] * $dimension['width'] * $dimension['height'])/5000 ;
			}else if($value == 0){
				return 0;
			}
			$counter ++;
		}

		return $volumetric_weight;
	}

	public static function get_delivery_fee_by_cart_item($cart_items)
	{
		$return = array();
		$fdata = [
						'status_code' => false,
						'data' => $return
					];
		foreach($cart_items as $item)
		{
			$temp = [
						'product_id' => isset($item['product_id']) ? $item['product_id']: 0 ,
						'status_code' => false,
						'data' => ['volumetric_weight' =>0 ,  'charges'=> 0 , 'sst' => 0 , 'total' => 0 ]

					];

			$product_model = OCProduct::find($item['product_id']);
			if(isset($product_model['product_id'])){
				$dimension = ['height' => $product['height'], 'width' => $product['width'] , 'length' => $product['length']];
				$volumetric_weight = SkynetAPI::get_volumetric_weight_by_dimension($dimension);
				$temp['data'] = ['volumetric_weight'=>$volumetric_weight, 'charges'=> $charges , 'sst' => $charges * OCSetting::SST_RATE , 'total' => $charges + $charges * OCSetting::SST_RATE];

			}

			array_push($return , $temp);
		}

		$fdata = [
						'status_code' => true,
						'data' => $fdata
					];

		return $fdata;
	}



	public static function get_awb_no_array_from_input($input)
	{
		$return = array();
		$listing = explode(',', $input);
		foreach ($listing as $row) {
			$temp['awbnumber'] = $row;
			array_push($return, $temp);
		}

		return $return;
	}

	public static function calculate_document_fee($volumetric_weight )
	{
		$is_first = true;
		$first_1kg = 6.12;
		$onward_1kg = 1.7;
		$charges = 0; 
		do{
			if($is_first){
				$charges += $first_1kg ; 
				$is_first = false;
				$volumetric_weight -= 1;
			}else {
				$charges +=  $onward_1kg; 
				$volumetric_weight -= 1;
			}

		}while($volumetric_weight < 0);

		return $charges;
	}

	public static function calculate_parcel_fee($volumetric_weight )
	{
		$is_first = true;
		$charges = 0; 
		$first_5kg = 10.88;
		$onward_1kg = 1.7;	

		do{
			//echo 'Weight:'.$volumetric_weight."<br>";
			if($is_first == true){
				$charges += $first_5kg ; 
				$is_first = 0;
				$volumetric_weight -= 5;
				//echo $charges." status :".$is_first."<br>";
			}else {
				$charges +=  $onward_1kg;  
				$volumetric_weight -= 1;
				//echo $charges."<br>";
			}

		}while($volumetric_weight > 0);

		return $charges;

		
	}




	public static function save_do_by_leaf_do_model()
	{
		
	}


	const skynet_account_no = 'SMD0119103899';
	const skynet_print_awb_default_value_mapper = ['Contents'=>'Foods','printFormat'=>'Sticker','ShipmentType'=>'Parcel','CurrencyCode'=>'RM',/*'DepartmentCode'=>'',*/'CODAmount'=>'',''=>'',''=>'',];
	const skynet_print_awb_to_oc_order_mapper = ['ShipperAdd1'=> 'shipping_address_1' , 'ShipperAdd2'=> 'shipping_address_2' , 'ShipperAdd3'=> 'shipping_city' , 'ShipperAdd4'=> '' , 'ShipperTelNo'=> 'telephone' , 'ShipperPostCode'=> 'shipping_postcode' , 'ConsigneeAdd1'=> '' , 'ConsigneeAdd2'=> '' , 'ConsigneeAdd3'=> '' , 'ConsigneeAdd4'=> '' , 'ConsigneePostCode'=> '' , 'ConsigneeTelNo' => 'telephone' , 'Value'=>'total'];
	const skynet_recipient_names = ['ShipperName','ConsigneeName'];
	public  function print_awb($go_leaf_order)
	{
		$url = static::print_awb_url;
		$params['access_token'] = static::skynet_token;
		$params['accountNo'] = static::skynet_account_no;

		
		$params['shipperRef'] = "CustomerAX";
		$params['Pieces'] = "1";
		$params['Weight'] = "15.00";

		$params['ShippingDate'] = "2020-08-25";

		$recipient_name = $go_leaf_order['shipping_firstname'].' '.$go_leaf_order['shipping_lastname'];
		foreach ($skynet_recipient_names as $name)
		{
			$params[$name] = $recipient_name;
		}
		
		foreach (static::skynet_print_awb_default_value_mapper as $key => $value) {
			$param[$key] = $value;
		}

		foreach (static::skynet_print_awb_to_oc_order_mapper as $key => $value) {
			$param[$key] = $go_leaf_order[$value];
		}

		return $this->decode($this->post($url, $params));
	}

	public function create_delivery_order()
	{
		$url = static::create_delivery_order_url;
		//$params['access_token'] = static::skynet_token;
		$params['token_access'] =  static::skynet_token;
		//$params['TokenAccess'] =  "***************" ;
		$params['CustomerAccountNo'] =  "SMD0119103899" ;
		$params['SNOrderList'] =  ["AWBNum"=> "2223878471083", "ShipmentType"=> "Parcel", "CODType"=> "COD", "CODAmount"=> "12.20", "Pieces"=> "2", "Weight"=> "2", "Remark"=> "Test", "CurrencyCode"=> "RM", "Value"=> "12.30", "PickupDate"=> "2021-10-03 00=>00=>00.000", "ShipperName"=> "Haziq", "ShipperAdd1"=> "ss 14", "ShipperAdd2"=> "jalan g/1", "ShipperCity"=> "subang jaya", "ShipperState"=> "Selangor", "ShipperPostcode"=> "45000", "ShipperTelNo"=> "0112312312", "ShipperRef"=> "this shipement refre", "ConsigneeName"=> "Test", "ConsigneeAdd1"=> " ss 14, jalan g/1", "ConsigneeAdd2"=> "45000 subang jaya", "ConsigneeCity"=> "subang jaya", "ConsigneeState"=> "selangor", "ConsigneePostcode"=> "232344", "ConsigneeTelNo"=> "12313131", "ConsigneeEmail"=> "abc123@gmail.com" ]  ;
		//dd($params);
		echo json_encode($params)."<br> <br>";
		return $this->decode($this->post($url, $params));

	}

	public static function get_track_status_array(){

		$event = array('PUCHECIN' => 'Arrived At Sorting Facility'	, 
						'LINEHAUL' => 'Departed to HUB' , 
						'HUB CHECKIN' => 'Arrived HUB',
						'HUB LINEHAUL' => 'Departed to event' ,
						'CHECKIN' => 'Arrived destination' ,
						'OFD' => 'Out for Delivery',
						'POD' => 'Delivered');


		$description = array('PUCHECIN'=>'Collection at pick-up Station',
							'LINEHAUL'=>'Line haul to HUB',
							'HUB CHECKIN'=>'Checked in at HUB',
							'HUB LINEHAUL'=>'Line haul to destination station',
							'CHECKIN'=>'Checked in at arrival station',
							'OFD'=>'Shipment out for Delivery',
							'POD' => 'Shipment delivered to Customer');

		return array('event' => $event , 'description' => $description);
	}

	public static function get_error_code_array(){

		return  array('200' => array('General Tracking' => 'Invalid AWB Number' , 'Pickup' => 'Invalid Order Number' , 'Domestic Quotation' => 'Invalid Parameter Value' ) ,
						'300' => 'Invalid Token',
						'400' => 'Parameter is missing');
	
							
	}

	public static function get_track_status_by_code($code){
		
	}

	public function post_tracking_result($awb_no)
	{

		$params['access_token'] = static::skynet_token;
		$params['awbs']		=	static::get_awb_no_array_from_input($awb_no);
	//	$params['token']		=	static::skynet_token;

		$url = static::url.static::tracking_url;

		return $this->decode($this->post($url, $params));
	}

	public function post_contact($input)
	{
		$params['controller']	=	'post_address';
		$params['id_user']		=	$this->id_user;
		foreach ($input as $key => $value) {
			if ($key == 'country_id' || $key == 'state_id') {
				$key = $this->convert_id($key);
			}
			$params[$key]	=	$value;
		}
		return $this->decode($this->post(null, $params));
	}

	public function get_api_url($type)
	{

	}

	public function response($result, $status)
	{	echo json_encode($result)."<br>";

		$result = $this->decode($result);
		$fdata['status'] = 0;
		$fdata['status_msg'] = '';
		$fdata['listing'] = [];
		if ($status == "200") {	

			if ($result != ''){
				$fdata['return']  = $result;
				$fdata['status']		=	1;
				
			}
			else {
				$fdata['status_msg']	=	"Success";
			}
		}
		else if($status == "300") {
			$result_string = "Error - " . $status;
			$fdata['status_msg']	=	$result_string;
		}
		else if($status == "400") {
			$result_string = "Error - " . $status;
			$fdata['status_msg']	=	$result_string;
		}
		return $fdata;
	}


	public function decode($json)
	{
		return json_decode($json, true);
	}

	public function convert_id($value)
	{
		$value = str_replace('_id', '', $value);
		$value = 'id_'.$value;
		return $value;
	}

	public function get($url, $params=null)
	{
		return $this->curl(static::method_get, $url, $params);
	}

	public function post($url, $params=null)
	{
		return $this->curl(static::method_post, $url, $params);
	}

	public function curl($method, $url, $params=null)
	{
		
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
	        //dd($params);
	        //dd(json_encode($params));
	        //dd(json_encode($params));

	        $params = json_encode($params);
	

	        //Encode the array into JSON.
			$jsonDataEncoded = substr(str_replace('\"','"' , json_encode($params)), 1, -1);;
			 //dd($jsonDataEncoded);
			//Tell cURL that we want to send a POST request.
			curl_setopt($ch, CURLOPT_POST, 1);
			 
			//Attach our encoded JSON string to the POST fields.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
			 
			//Set the content type to application/json
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 


	        //curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        }

        // New ----------------------------------------------------------------
    

        if (curl_errno($ch)) {
		    $error_msg = curl_error($ch);
		    echo "Error : ----------------------------";
		    dd($error_msg);
		}



		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // New ----------------------------------------------------------------

        // $result contains the output string 
        $result = curl_exec($ch); 
       // dd($result);
        // close curl resource to free up system resources 
        curl_close($ch);
        return $result;
	}

}
