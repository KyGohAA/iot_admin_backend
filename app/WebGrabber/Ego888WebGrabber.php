<?php 


namespace App\WebGrabber;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use yajra\Datatables\Datatables;
use App\Language;
use App\Opencart\Setting as OCSetting;
use App\Setting;
use DOMDocument;
use Image;
use File;
use DOMXPath;
use App\WebGrabber\WebGrabber;
use App\Opencart\Product as OCProduct;

class Ego888WebGrabber /*extends DOMXpath*/ {

	const domain = "http://www.ego888.com.tw";
	const get_product_by_id_url = "https://www.ego888.com.tw/ego/FrontviewController.do?action=ProductInfor&productid=";

	public static function test()
	{
		for($i = 1 ; $i < 99999 ; $i++)
		{
			static::get_product_by_product_id($i);
		}
	}

	public static function get_all_products_from_ego888()
	{
		for($i = 1 ; $i < 99999 ; $i++){
			static::get_product_by_product_id($i);
		}
		
	}

	public static function get_product_id_from_url($url)
	{
		$product_id = 0; 
		$url_arr = explode("&", $url);
		foreach ($url_arr as $row) {
			if(strpos($row,"productid") !== false && strlen($row) < 25){
				$product_id_arr = explode("=", $url);
				foreach ($product_id_arr as $id_row) {
					if(is_numeric($id_row)){
						$product_id = $id_row;
					}
				}
			}
		}

		return $product_id;
	}
	
	//infor = product title || meta keyword
	//infor3 = product code
	//infor2 = product description
	//infor25 = product description 2 

	public static function get_product_by_product_id($id,$is_update=false){

		$url = static::get_product_by_id_url.$id;
		$product_model = OCProduct::get_by_product_url($url);
		if(isset($product_model['product_id']) && $is_update == false){
			return;
		}else if(isset($product_model['product_id']) && $is_update == true){
			$product['id'] = $product_model['id'];
			$product['product_id'] = $product_model['id'];
		}

		$returned_content = WebGrabber::get_url_contents($url); 
		$doc = new DOMDocument();
			
		if ($returned_content === FALSE || $returned_content == "") {
			echo $id.' : problem getting url <br>';
			return false;
		}else{
			echo $id.' : retrieving <br>';
			$selling_price = 0;
			$price = 0;
			$cost = 0;
			$image_url = "";
			$name = "";
			$keywords = "";
			$meta = "";

			libxml_use_internal_errors(true);
			$doc->loadHTML($returned_content);
			$xpath = new DOMXPath($doc);
			$product_code_td = $xpath->query("//*[@class='infor3']")->item(0);
			$product_code = isset($product_code_td->nodeValue) ? $product_code_td->nodeValue : "";
			$title_array = $xpath->query('/html/head/meta[@name="description"]/@content');
			foreach ($title_array as $row) {
				if(strlen($row->value) > 3){
					$name = $row->value;
				}
			}

			$keywords_array = $xpath->query('/html/head/meta[@name="keywords"]/@content');
			foreach ($keywords_array as $row) {
				if(strlen($row->value) > 3){
					$keywords = $row->value;
				}
			}

			$cover_photo_arr = $xpath->query('//img/@src');

			//Process product image
			$product_image_arr = array();
			$image_sort_order_counter = 0;
			foreach ($cover_photo_arr as $row) {
				if(strpos($row->nodeValue,"ViewHelperController") !== false || strpos($row->nodeValue,"view?action=showImage") !== false){
					
					if(strpos($row->nodeValue,"ViewHelperController") !== false ){
						$image_url = static::domain.$row->nodeValue;
					}
					
					$temp['sort_order'] = $image_sort_order_counter;
					$temp['image'] = static::domain.$row->nodeValue;
					array_push($product_image_arr, $temp);
					$image_sort_order_counter++;
				}
			}

			$meta_keyword_array = $xpath->query('/html/head/meta[@name="keywords"]/@content');
			foreach ($meta_keyword_array as $row) {	
				if(strlen($row->value) > 3){
					$meta = $row->value;
				}
			}


			$product_description_td = $xpath->query("//*[@class='infor2']")->item(0);
			$product_description = isset($product_description_td->nodeValue) ? $product_description_td->nodeValue : "";

			$product_description_td_long = $xpath->query("//*[@class='infor25']");
			$product_description_2 = "";
			foreach ($product_description_td_long as $td) {
				$product_description_2 = $product_description_2.$td->nodeValue."<br> ";
				
			}

			$product_detail_td = $xpath->query("//*[@class='infor']");
			$i = 0;
			foreach ($product_detail_td as $td) {
			
				if(strpos($td->nodeValue,  '市價') !== false && strlen($td->nodeValue) < 100){

					$selling_price_arr = explode(" ",  str_replace( "\t",  " " ,  str_replace("\r\n", " ", $td->nodeValue)));
					foreach ($selling_price_arr as $row) {
						if(strpos($row,  '市價') === false && strlen($row) > 2){
							$selling_price = $row;
						}
					}
				}

				if(strpos($td->nodeValue,  '網路價') !== false && strlen($td->nodeValue) < 100){

					$price_arr = explode(" ",  str_replace( "\t",  " " ,  str_replace("\r\n", " ", $td->nodeValue)));
					foreach ($price_arr as $row) {
						if(strpos($row,  '網路價') === false && strlen($row) > 2){
							$price = $row;
						}
					}
				}
			}

			//dd("Done");
			// for printing the whole html table just type: print $xml->saveXML($table); 

			//Save product
			$product['model'] = $name;
			//$product['minimum']= 0 ; 
 			$product['product_url'] = $url;
			$product['sku'] = $product_code;
			$product['selling_price'] = $selling_price;
			$product['price'] = $price;
			$product['cost'] = $cost;
			$product['status'] = false;
			$product['image'] = $image_url;
			$product['e_store_name'] = OCSetting::EGO888_SHOP_NAME;

			//image arr
			$product_description_arr = array();
			foreach (WebGrabber::default_language_id_arr as $language_id) 
			{
				$temp = array();
				$temp['name']  = $name;
				$temp['tag']  = "";
				$temp['language_id']  = $language_id;
				$temp['meta_keyword']  = $keywords;
				$temp['meta_title']  = $name;
				$temp['description']  = $product_description.'<br> <br>'.$product_description_2;
				array_push($product_description_arr, $temp);
			}
			
			$product['product_description'] = $product_description_arr;
			$product['product_image'] = $product_image_arr;

			$return  = OCProduct::save_model_to_store($product);
			return $return ;
			/*$product['keyword']
			$product['meta']*/
	
		}
	}
		
/*getAttribute('href')
$link->nodeValue*/
}


/*infor2  description
"infor3" code
"infor3" prodcutInfo
*/