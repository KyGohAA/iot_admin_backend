<?php

namespace App;

use Auth;
use Cookie;
use Request;

class Language extends ExtendModel
{
	public $table = 'translation_words';
	public $timestamps = false;

    public static function trans($string)
    {
     	if (!isset($_COOKIE['language'])) {
    		//setcookie('language', '0');
     	}

     	if(isset(Auth::user()->language_id)){

     		$file = storage_path('framework/translation/'.Auth::user()->language_id.'.json');

			if(is_readable($file)){
				//echo "read <br>";
				$datas = json_decode(file_get_contents($file), true);
				if ($con_lang = array_get($datas, $string)) {
					
					return $con_lang;
				}
			}
			
     	}else if (isset($_COOKIE['language']) && $_COOKIE['language']) {
	
		 	$file = storage_path('framework/translation/'.$_COOKIE['language'].'.json');

			if(is_readable($file)){
				//echo "read <br>";
				$datas = json_decode(file_get_contents($file), true);
				if ($con_lang = array_get($datas, $string)) {
					
					return $con_lang;
				}
			}
		 		//echo "not <br>";
	    }
    	return $string;
    }

    public static function selected()
    {
    	if (isset($_COOKIE['language'])) {
    		return array_get(static::type(), $_COOKIE['language']);
    	}
    	return 'en';
    }

	public function parent()
	{
		return $this->belongsTo('App\Language','translation_of_id_word');
	}

	public static function type()
	{
		return array('en','my','ch');
	}

	public static function combo_box()
	{
		return array('0' => Language::trans('English')/*,'my' => '',*/ , '3' => Language::trans('Chinese Simplified'));
	}
}
