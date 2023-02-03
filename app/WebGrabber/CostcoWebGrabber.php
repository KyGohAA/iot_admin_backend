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
use App\Setting;
use DOMDocument;
use Image;
use File;
use DOMXPath;
use App\WebGrabber\WebGrabber;
use App\Opencart\Product as OCProduct;

class CostcoWebGrabber /*extends DOMXpath*/ {

	const domain = "https://www.costco.com.tw";
	const get_product_category_by_id_url = "https://www.costco.com.tw/c/";
	const get_product_by_id_url = "http://www.costco.com.tw/p/";

	public static function test()
	{
		for($i = 1 ; $i < 99999 ; $i++)
		{
			static::get_product_by_url($i);
		}
	}

	public static function get_all_products_from_costco()
	{
		static::get_product_category();
		dd("s");
		for($i = 1 ; $i < 99999 ; $i++){
			$i="Ss";
			static::get_product_by_url($i,false);
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

	public static function get_product_by_url($url=null,$is_update=null){

		$url = "https://www.costco.com.tw/Health-Beauty/Vitamins-Herbals-Dietary-Supplements/Dietary-Supplements-Probiotics/WEIDER-Probiotics-Granule-90-Sachets/p/994049";
		$product_model = OCProduct::get_by_product_url($url);
		//dd($product_model);
		if(isset($product_model['product_id']) && $is_update == false){
			return;
		}else if(isset($product_model['product_id']) && $is_update == true){
			$product['id'] = $product_model['id'];
			$product['product_id'] = $product_model['id'];
		}

		$returned_content = WebGrabber::get_url_contents($url); 
		$doc = new DOMDocument();

		if ($returned_content === FALSE || $returned_content == "") {
			echo $url.' : problem getting url <br>';
			return false;
		}else{

			$selling_price = 0;
			$price = 0;
			$cost = 0;
			$image_url = "";
			$title = "";
			$keywords = "";
			$meta = "";

			libxml_use_internal_errors(true);
			$doc->loadHTML($returned_content);
			$xpath = new DOMXPath($doc);
			$product_name = $xpath->query("//*[@class='product-name']")->item(0)->nodeValue;
			$product_english_name = $xpath->query("//*[@class='product-english-name']")->item(0)->nodeValue;
			
			$product_code_paragraph = $xpath->query("//*[@class='product-code']")->item(0);
			$product_code_temp = isset($product_code_paragraph->nodeValue) ? $product_code_paragraph->nodeValue : "";
			$product_code_arr= explode(":", $product_code_temp);
			$product_code = trim(str_replace("\n","",str_replace('"""', "", $product_code_arr[1])));

			$product_description_detail = $xpath->query("//*[@class='product-details-content-wrapper']")->item(0)->nodeValue;
			$product_description_specs = $xpath->query("//*[@class='product-classifications']")->item(0)->nodeValue;

			$price_div = $xpath->query("//*[@class='price-original']");
			foreach ($price_div as $row) {
				$price = str_replace("$","",trim($row->nodeValue));
				$selling_price = $price;
			}

			$product_category_li = $xpath->query("//*[@class='show-sub-menu']");
			foreach ($product_category_li as $row) {
				echo $row->nodeValue."<br>";
				/*foreach ($row as $item) {
					echo $item->nodeValue."<br>";
				}*/
			}

			//dd($product_category_li);
		
			$description ="";
			$description_array = $xpath->query('/html/head/meta[@name="description"]/@content');
			foreach ($description_array as $row) {
				if(strlen($row->value) > 3){
					$description = $row->value;
				}
			}

			$title_array = $xpath->query('/html/head/meta[@name="title"]/@content');
			foreach ($title_array as $row) {
				if(strlen($row->value) > 3){
					$title = $row->value;
				}
			}

			$keywords_array = $xpath->query('/html/head/meta[@name="keywords"]/@content');
			foreach ($keywords_array as $row) {
				if(strlen($row->value) > 3){
					$keywords = $row->value;
				}
			}

			$lazy_owl_image_arr = explode("\t\t\t", $returned_content."");
			$searchword = 'lazyOwl';
			$matches = array();
			foreach($lazy_owl_image_arr as $k=>$v) {
			    if(preg_match("/\b$searchword\b/i", $v)) {
			        array_push($matches,$v);
			    }
			}

			$image_sort_order_counter = 0; 
			$temp_product_img_arr = explode('<img class="lazyOwl"', $matches[0]."");
			$product_image_arr = array();
			$image_sort_order_counter = 0;
			foreach ($temp_product_img_arr as $row) {
				if(strpos($row,"data-src=") !== false) {
					$temp_img = explode("data-zoom-image=",$row);
					foreach ($temp_img as $img) {
						if(strpos($img,"data-src=") !== false) {
							$img_xz['image'] = static::domain.'/'.trim(str_replace('data-src=',"",str_replace('"', "", $img)));
							$img_xz['sort_order'] = $image_sort_order_counter;
							array_push($product_image_arr,$img_xz);

							if($image_sort_order_counter == 0){
								$image_url = $img_xz['image'];
							}
							$image_sort_order_counter ++;
						}
					}
			    }
			}

			

			//Save product
			$product['model'] = $product_english_name;
			//$product['minimum']= 0 ; 
 			$product['product_url'] = $url;
			$product['sku'] = $product_code;
			$product['selling_price'] = $selling_price;
			$product['price'] = $price;
			$product['cost'] = $cost;
			$product['status'] = false;
			$product['image'] = $image_url;
			$product['e_store_name'] = OCSetting::COSTCO_SHOP_NAME;

			//image arr
			$product_description_arr = array();
			foreach (WebGrabber::default_language_id_arr as $language_id) 
			{
				$temp = array();
				$temp['name']  = $product_name;
				$temp['tag']  = "";
				$temp['language_id']  = $language_id;
				$temp['meta_keyword']  = $keywords;
				$temp['meta_title']  = $product_name;
				$temp['description']  = $product_description_detail.'<br> '.$product_description_specs;
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

	public static function get_product_category($url=null,$is_update=null){

		$url = "https://www.costco.com.tw/";
		$returned_content = WebGrabber::get_url_contents($url); 
		$doc = new DOMDocument();

		if ($returned_content === FALSE || $returned_content == "") {
			echo $url.' : problem getting url <br>';
			return false;
		}else{

			libxml_use_internal_errors(true);
			$doc->loadHTML($returned_content);
			$xpath = new DOMXPath($doc);

			$is_main = false;
			$product_category_li = $xpath->query("//*[@class='show-sub-menu']");	
			$ul_listing = $doc->getElementsByTagName('ul');	
			$temp_description = array();
			$main = "";
			$id = "";
			
			foreach ($ul_listing as $row_li) {	
				if($row_li->getAttribute("id") == "theMenu"){
					foreach ($row_li->getElementsByTagName('li') as $row_li_item) {
				       
				       if(strpos($row_li_item->getAttribute("class"), "topmenu") !== false){
				       		$is_main = true;   
				       } 
			     	   foreach ($row_li_item->getElementsByTagName('a') as $row) {
			     	   	   $category = trim($row->nodeValue);     
				     	   if($category == ""){
				     	   	 continue;
				     	   }
				     		
				     	   if($is_main){
				     	    	$id = "Big Cat :".$row->getAttribute('data-category');
				     	   		$main = $id."::".$category;
				    			array_push($temp_description, $main);
				    			$is_main = false;   

				     	   }else if(strpos($row->getAttribute("class"), "visible-xs visible-sm cat-trigger") !== false){
				     	    	$id = $row->getAttribute('data-category');
				     	   		$main = $id."::".$category;
				    			array_push($temp_description, $main);
				     	   }elseif($row->getAttribute("class") == null){		   				
				   				$category = $main."==".$category;
				  				array_push($temp_description, $category);
				     	   } 
			     	   }

			     	           
				    }         
		   		}
			}
			dd($temp_description);


			foreach ($ul_listing as $row_li) {	
				if($row_li->getAttribute("id") == "theMenu"){
					foreach ($row_li->getElementsByTagName('a') as $row) {
				       //echo $row->getAttribute('data-category')."=".$row->getAttribute("class")."=".$row->nodeValue.'<br />';      
			     	   $category = trim($row->nodeValue);     
			     	   if($category == ""){
			     	   	 continue;
			     	   }
			     		
			     	   if(strpos($row->getAttribute("class"), "topmenu") !== false){
			     	    	$id = $row->getAttribute('data-category');
			     	   		$main = $id."::".$category;
			    			array_push($temp_description, $main);

			     	   }else if(strpos($row->getAttribute("class"), "visible-xs visible-sm cat-trigger") !== false){
			     	    	$id = $row->getAttribute('data-category');
			     	   		$main = $id."::".$category;
			    			array_push($temp_description, $main);
			     	   }elseif($row->getAttribute("class") == null){		   				
			   				$category = $main."==".$category;
			  				array_push($temp_description, $category);
			     	   }            
				    }         
		   		}
			}
		}
	}

}
