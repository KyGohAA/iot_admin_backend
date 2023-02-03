<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Schema;
use Cookie;
USE Auth;
use App\User;
use App\Setting;
use App\Language;

class LanguagesController extends Controller
{
    public function __construct()
    {
		$this->page_variables = [
                                    'page_title'   =>   Language::trans('Languages Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getIndex' ,
                                    'view_link' => class_basename($this).'@getIndex' ,
                                    'delete_link' => class_basename($this).'@getIndex',
                                    'new_file_link' => class_basename($this).'@getIndex' 
                                ];
    	$this->return_url   =   class_basename($this).'@getIndex';
        $this->new_file_link = class_basename($this).'@getNew';
        $this->page_title   =   Language::trans('Languages Page');
        //$this->middleware('auth_admin');
    }

    public function getLanguage(Request $request)
    {
    	$input = $request->input();
    	if(isset(Auth::user()->id)){
    		$user = User::find(Auth::user()->id);
      		$user['language_id'] = $input['language_code'];
      		$user->save();
    	}
      	
    	//Cookie::queue('language', $input['language_code'], 100000);
  		setcookie('language', $input['language_code']);

      if($_COOKIE['language'] != $input['language_code']){
        setcookie('language', $input['language_code']);
        if($_COOKIE['language'] != $input['language_code']){
        setcookie('language', $input['language_code']);
        }
        if($_COOKIE['language'] != $input['language_code']){
          setcookie('language', $input['language_code']);
        }
        if($_COOKIE['language'] != $input['language_code']){
          setcookie('language', $input['language_code']);
        }
      }
		/*Cookie::queue('language', $model['id'], 100000);
		setcookie('language', $model['id']);*/	
		return json_encode(true);
	}



    public function getIndexing()
    {
		// set all the word to inactive
		DB::table('translation_words')->update(['is_active'=>false]);

		//$this->scan_words('app/Http/Controllers', true);
		//$this->scan_words('app');
		//$this->scan_words('resources/views/_version_02', true);
		//$this->scan_words('resources/views/_version_02', true);
		//$this->scan_words('resources/views/_version_02/apps', true);
		//$this->scan_words('resources/views/_version_02/layouts', true);
		$this->scan_words('resources/lang', true);
		//$this->scan_table_columns();
		//remove unactive row
		DB::table('translation_words')->where('is_active','=',false)->delete();

		return redirect()->action('LanguagesController@getIndex');

    }

    public function getIndex()
    {
    	$page_variables = $this->page_variables;
		$ori_lang = Language::where('language_id','=','0')->get();
		return view('languages.form', array(
				'n'	=>	1,
				),compact('ori_lang','page_variables'));
    }

    public function postIndex(Request $request)
    {
		ini_set('max_input_vars', 2000);
		//save data
		$input = $request->all();

		//delete existing language converted
		Language::where('language_id','!=','0')->delete();

		//save new language converted by each row
		if (isset($input['translation'])) {
			foreach ($input['translation'] as $key => $value) {
				foreach ($value['type'] as $skey => $svalue) {
					if ($svalue) {
						$model 							=	new Language();
						$model->language_id				=	$skey;
						$model->word_str				=	$svalue;
						$model->translation_of_id_word	=	$key;
						$model->is_active				=	true;
						$model->save();
					}
				}
			}
		}

		// generate json file to storage/translation
		$lang_types = DB::table('translation_languages')->get();
		foreach ($lang_types as $lang_type) {
			$data_count = Language::where('language_id','=',$lang_type->id)->count();
			$jsonArray = array();
			if ($data_count > 0) {
				$datas = Language::where('language_id','=',$lang_type->id)->get();
				foreach ($datas as $row) {
					$jsonArray[$row->parent->word_str] = $row->word_str;
				}
			}
			// save to create json file
			if (!file_exists(storage_path('framework/translation/'.$lang_type->id.'.json'))) {
				mkdir(storage_path('framework/translation'), 0777, true);
			}
			file_put_contents(storage_path('framework/translation/'.$lang_type->id.'.json'), json_encode($jsonArray));
		}

		//return to dashboard
		return redirect()->action('LanguagesController@getIndex')
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, Language::trans('Data was successfully updated.'));
    }

    public function scan_table_columns()
    {
		$tables = DB::select('SHOW TABLES');

		$fdata = [];
		$db_name = 'Tables_in_leaf_webview';
		foreach ($tables as $table) {
			$except_table = ['migrations','translation_languages','translation_words'];
			if (!in_array($table->$db_name, $except_table)) {
				$columns = Schema::getColumnListing($table->$db_name);
				$except_col = ['id','token','leaf_id_user','leaf_id_group','language_id','is_checked','created_at','updated_at','created_by','updated_by'];
				foreach ($columns as $col) {
					if (!in_array($col, $except_col)) {
						$col = str_replace('_', ' ', $col);
						$fdata[$col] = $col;
					}
				}
			}
		}
		list($keys, $values) = array_divide($fdata);
		foreach ($values as $word) {
			if (!$check_point = DB::table('translation_words')->where('word_str','=',$word)->first()) {
				DB::table('translation_words')->insert(
						['language_id'=>'0','word_str'=>$word,'is_active'=>true]
					);
			}
		}
    }

    public function scan_words($path, $scan_folders=false)
    {
		// scan language file in view folders
		$folders = array_diff(scandir(realpath(base_path($path))), array('.','..'));

		foreach ($folders as $file) {
			if (strpos($file, '.php') !== false) {
				$contents = file_get_contents(realpath(base_path($path.DIRECTORY_SEPARATOR.$file)));
				preg_match_all("/(Language::trans)\([\'|\"](.*?)[\'|\"]\)/u", $contents, $matches);
				$words = $matches[2];
				$words = array_unique($words);
				foreach ($words as $word) {
					if (!$data = DB::table('translation_words')->where('word_str', '=', $word)->first()) {
						DB::table('translation_words')->insert(
						    ['language_id' => '0', 'word_str' => $word, 'is_active' => true]
						);
					} else {
						DB::table('translation_words')->where('word_str', '=', $word)->update(array('is_active'=>true));
						DB::table('translation_words')->where('translation_of_id_word', '=', $data->id)->update(array('is_active'=>true));
					}
				}
			} else {
				if ($scan_folders == true && $file != '.DS_Store') {
					$sub_folders = array_diff(scandir(realpath(base_path($path.DIRECTORY_SEPARATOR.$file))), array('.','..'));
					foreach ($sub_folders as $sub_folder) {
						$contents = file_get_contents(realpath(base_path($path.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.$sub_folder)));
						preg_match_all("/(Language::trans)\([\'|\"](.*?)[\'|\"]\)/u", $contents, $matches);
						$words = $matches[2];
						$words = array_unique($words);
						foreach ($words as $word) {
							if (!$data = DB::table('translation_words')->where('word_str', '=', $word)->first()) {
								DB::table('translation_words')->insert(
								    ['language_id' => '0', 'word_str' => $word, 'is_active' => true]
								);
							} else {
								DB::table('translation_words')->where('word_str', '=', $word)->update(array('is_active'=>true));
								DB::table('translation_words')->where('translation_of_id_word', '=', $data->id)->update(array('is_active'=>true));
							}
						}
					}
				}
			}
		}
    }
}
