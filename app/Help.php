<?php

namespace App;

use DB;
use Auth;
use Schema;
use Validator;

use Illuminate\Database\Eloquent\Builder;

class Help extends ExtendModel
{
    protected $table = 'helps';
    public $timestamps = true;
    protected $listing_except_columns = ['answers','question','created_by','updated_by','created_at','updated_at','leaf_group_id','language_code'];

    protected $guarded = [];

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

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's listing
    |--------------------------------------------------------------------------
    |
    */

    public static function combobox()
    {
        return static::ofAvailable('status',true)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select help...'), '');
    }

    public static function find_model_by_leaf_group_id($id)
    {
        $model = static::where('leaf_group_id' , '=',$id)
                    ->where('status','=',true)
                    ->select('content','leaf_group_id')
                    ->get();

        return $model;
    }

    public static function get_faq($leaf_group_id=null)
    {
        $return = array();
        $temp_list = static::where('leaf_group_id' , '=',$leaf_group_id)
                    ->where('status','=',true)
                    ->select('content','description','leaf_group_id')
                    ->get();

        foreach ($temp_list as $faq)
        {   
            if($faq['content'] == '' ||  $faq['description'] == ''){continue;}
            $title = json_decode($faq['description']);
            $content = json_decode($faq['content']);
            $temp['title'] = $title->english;
            $temp['content'] = $content->english;
            array_push($return , $temp);
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
        $except = $this->listing_except_columns;

        return array_diff(Schema::getColumnListing($this->table), $except);
    }

    public function listing_header()
    {
        return array_diff($this->table_cols(), $this->listing_except_columns);
    }

    public function scopeListing($query) 
    {
        return $query->select(array_diff($this->table_cols(), $this->listing_except_columns));
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of validation & save form
    |--------------------------------------------------------------------------
    |
    */

    public function validate_form($input)
    {
        $rules = [
                    'content'      =>  'required|unique:helps,content,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['content'] = 'required|unique:helps,content,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

    	$validator = Validator::make($input, $rules);

    	if ($validator->fails()) {
    		return $validator;
    	}
    	return false;
    }

    const excluded_keys = ['status'];
    const to_json_mappers = ['description','content'];
    public function save_form($input)
    {
    	DB::beginTransaction();
    	try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && !in_array($key , static::to_json_mappers) && !in_array($key , static::excluded_keys)) {
                    $this->$key = (string) $value;
                }elseif(in_array($key, static::to_json_mappers))
                {
                    $this->$key = json_encode($value);
                }elseif(in_array($key, static::excluded_keys))
                {
                    //$this->$key = $value == 'on' ? true : false;
                    $this->$key = $value;
                }
            }
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }
    		$this->save();
    	} catch (Exception $e) {
    		throw $e;
    		DB::rollBack();
    	}
    	DB::commit();
    }

}