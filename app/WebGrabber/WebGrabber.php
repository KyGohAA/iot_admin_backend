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

class WebGrabber /*extends DOMXpath*/ {


	const domain = "http://www.ego888.com.tw/";
	const path = "";
	const default_language_id = 1;
	const default_language_id_arr = array(1,2,3);

	public static  function get_url_contents($url){

            $crl = curl_init();
            $timeout = 5;
            curl_setopt ($crl, CURLOPT_URL,$url);
            curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
            $ret = curl_exec($crl);
            curl_close($crl);
            return $ret;
     }


     //Alternative Image Saving Using cURL seeing as allow_url_fopen is disabled - bummer
       public static function save_image($img1,$fullpath=''){
          try {
            if($fullpath==''){
                $fullpath = basename($img1);
            }
            $ch = curl_init ($img1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
            $rawdata=curl_exec($ch);
            curl_close ($ch);
            if(file_exists($fullpath)){
                unlink($fullpath);
            }
            $fp = fopen($fullpath,'x');
            fwrite($fp, $rawdata);
            fclose($fp);


            	} catch (Exception $e) {
				
				}
        }

		public function url($path=null)
		{
			return static::domain.static::path.$path;
		}

		public function html_content($path=null)
		{
			return file_get_contents($this->url($path));
		}

		public function scan_page($path=null)
		{
			$page = $this->html_content($path);
			@$dom = DOMDocument::loadHTML($page);
			if (isset($dom)) {
				return $dom;
			}
			return false;
		}

		public function query_content($dom, $element)
		{
			$xpath = new DOMXpath($dom);
			$links = $xpath->query($element);
			return $links;		
		}

		public function main_page()
		{
			$page = $this->scan_page();
			return $this->query_content($page, '//div[contains(@class, "inverted menu")]//a[@class = "item"]');
		}

		public function continue_page($path)
		{
			// child page
		    $page = $this->scan_page($path);
		    $links = $this->query_content($page, '//div[contains(@class, "inverted menu")]//a[@class = "item"]');
		    foreach ($links as $link) {
			    echo '&#09;&nbsp;'.'URL Path: <a target="_blank" href="'.$this->url($link->getAttribute('href')).'">'.$link->getAttribute('href').'</a> Name : '.$link->nodeValue.'<br><br>';
				    // $this->continue_page($link->getAttribute('href'));
		    }
		}

		public function end_page()
		{
			return "this is end page, get the photo/grahp here...";
		}

			
	  public static function getGrabWebSiteItem($url){

	

		do{


		//$url='http://www.fg-site.net/archives/'.$i; 
			//$url='http://www.fg-site.net/archives/post_old/'.$i; 
			
			//$url = 'http://www.fg-site.net/archives/3491657';
		 try {
					$returned_content = static::get_url_contents($url); 
		        /* gets the data from a URL */
			        $doc = new DOMDocument();
				
			/*if($returned_content != '\n\r\n\r\n \r\n \r\n \r\n \r\n'){*/
					
					 if ($returned_content === FALSE) {
					    echo 'problem getting url';
					    return false;
					  }else{

					  	libxml_use_internal_errors(true);
			    	    $doc->loadHTML($returned_content);




			    	    	//solution 2
			    	 	    $xpath = new DOMXPath($doc);
							$table =$xpath->query("//*[@id='myid']")->item(0);
							$rows = $table->getElementsByTagName("tr");
							dd($row);
							// for printing the whole html table just type: print $xml->saveXML($table); 



						//solution 1
			    	    $imageTags = $doc->getElementsByTagName('tr');
			    	       dd();
						$img1 = array();

								        foreach($imageTags as $tag) {

								            $img1[] = $tag->getAttribute('src');

								        }
					       
					         
					        foreach($img1 as $i){
					        	if(strpos($i, '.jpg') !== false){
					        		static::save_image($i);
					          		dd(getimagesize(basename($i)));
						            if(getimagesize(basename($i))){
						                echo '<h3 style="color: green;">Image ' . basename($i) . ' Downloaded OK</h3>';
						            }else{
						                echo '<h3 style="color: red;">Image ' . basename($i) . ' Download Failed</h3>';
						            }
					      	 	 }
						    }

					  }
			      
	/*	}*/


	    } catch (Exception $e) {
				
				$i++ ;
		}


    	$i++ ;

		}while($i < 34912657);


        
	}


}
