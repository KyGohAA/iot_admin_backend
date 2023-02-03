<?php

namespace App\Iot;

class Api extends ExtendModel
{
	const method_get = 'get';
	const method_post = 'post';
	const app_secret = '9abb1f575d724373e93dd780e0f985f1';
	const success_code = '200';
	const success_msg = 'Success';
	const error_array = ['400'=>'Unknown Error','401'=>'Authentication Failed','404'=>'Invalid Request'];
	const cost_method = ['Fixed Cost','Weighted Average','FIFO'];

	public static function setGetDeviceDataUrl($device_id){

		 $url = '3.1.183.153:8090/api/devices/'.$device_id.'/events';
		 return $url;

	}

	public function callAPI($url, $data=null){
		    
		    $this->result = array();
		    $curl = curl_init();

		    // curl_setopt($curl, CURLOPT_POST, 1);
		    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET' );
		    // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		        // 'Authorization: Bearer QdsavGda1hb3a7fhH2zty1a0211', //key for restapimodule
		        'Grpc-Metadata-Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlfa2V5X2lkIjoiMmJhMjAxNjQtZWY4ZS00YTk1LWJjNzMtMDUzNDMxYzE5Nzk3IiwiYXVkIjoiYXMiLCJpc3MiOiJhcyIsIm5iZiI6MTY2NjI1MDYyMiwic3ViIjoiYXBpX2tleSJ9.Zvzoj9aeXNNyIMsOpaexqZYtp6xIePOwt-qDwdeb_hg', // key for webapimodule
		        'Content-Type: application/json',
		     ));
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
		    curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		    curl_setopt($curl, CURLOPT_WRITEFUNCTION, function($curl, $data) {
		      $data_decoded = json_decode($data, true);
		      // var_dump($data['result']['payloadJSON']);
		      if(isset($data_decoded['result']))
		      {
		      	  $f_data = $data_decoded['result']['payloadJSON'];
			      $this->result = $f_data;
			      //echo $data;
			      //dd($f_data);
			      
			      // echo count($data['result']);
			      // echo json_encode($data);
			      //$this->insertData($f_data, '24e124136c225107');
			      ob_flush();
			      flush();
			      //dd($f_data);
			      // echo 'first';
			      return $f_data;
			      //return strlen($f_data);
		      }
		      
		    });
		    // EXECUTE:
		    //echo '2';
		    curl_exec($curl);
		    // if(!$result){die("Connection Failure");}
		    // return $result;
		    curl_close($curl);

		    return  $this->result;
  }


	public function response($result, $status)
	{
		$result = $this->decode($result);
		$fdata['status'] = 0;
		$fdata['status_msg'] = '';
		$fdata['register_id'] = '';
		$fdata['listing'] = [];
		if ($status == "200") {	
			$return_code = $result["returncode"]["code"];
			if ($return_code == 1){
				$fdata['status']		=	true;
				if (isset($result["returndata"]["attribute"]["recordid"])) {
					$fdata['register_id'] 	=	$result["returndata"]["attribute"]["recordid"];
					$fdata['status_msg']	=	'Data successfully saved.';
				}
				foreach ($result["returndata"]["data"] as $key => $array) {
					$fdata['listing']		=	$array;
					$fdata['status_msg']	=	'Data was successfully query.';
				}
			}
			else {
				$fdata['status_msg']	=	$result["returncode"]["error_message"];
			}
		}
		else {
			$result_string = "Error - " . $status;
			$fdata['status_msg']	=	$result_string;
		}
		return $fdata;
	}

	public function decode($json)
	{
		return json_decode($json, true);
	}

	public function get($request_type, $params=null)
	{
		return $this->curl(static::method_get, $request_type, $params);
	}

	public function post($request_type, $params=null)
	{
		return $this->curl(static::method_post, $request_type, $params);
	}

	public function curl($method, $request_type, $input=null)
	{
		if (isset($input['status'])) {
			$input['status'] = $input['status'] ? true:false;
		}

		$params['vendor'] = static::vendor;
		$params['apikey'] = static::app_secret;
		$params['companyid'] = static::company_id;
		$params['request_data']['attribute']['request_type'] = $request_type;
		$params['request_data']['data'] = $input;
        // create curl resource 
        $ch = curl_init(); 
        $url = static::url;

        if ($method == static::method_get) {
        	$url .= '?';
        	foreach ($params as $key => $value) {
        		$url .= $key.'='.$value.'&';
        	}
        }
        // set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($method == static::method_post) {
			$params = json_encode($params);
			$header = ['Content-Type: application/json','Content-Length: ' . strlen($params)];
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        // $result contains the output string 
        $result = curl_exec($ch); 
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // close curl resource to free up system resources 
        curl_close($ch);

        return $this->response($result, $status);
	}
}
