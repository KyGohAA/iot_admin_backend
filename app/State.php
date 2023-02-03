<?php

namespace App;

use DB;
use Auth;
use Schema;
use Validator;

use Illuminate\Database\Eloquent\Builder;

class State extends ExtendModel
{
    protected $table = 'states';
    public $timestamps = true;
    protected $listing_except_columns = ['created_by','updated_by','created_at','updated_at','leaf_group_id'];

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

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
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
    | Here to manage of data's displayed
    |--------------------------------------------------------------------------
    |
    */

    public static function combobox($country_id=null)
    {
        return static::ofAvailable('status',true)
                                ->where('country_id','=',$country_id)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select state...'), '');
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
        $company = new Company();
        $rules = [
                    'country_id'  =>  'required',
                    'code'        =>  'required|unique:states,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'name'        =>  'required|unique:states,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:states,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:states,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

    	$validator = Validator::make($input, $rules);

    	if ($validator->fails()) {
            dd($validator);
    		return $validator;
    	}
    	return false;
    }

    const excluded_keys = ['status'];
    public function save_form($input)
    {
    	DB::beginTransaction();
    	try {
            foreach ($input as $key => $value) {
                if ($key != '_token' && !in_array($key , static::excluded_keys)) {
                    $this->$key = (string) $value;
                }elseif(in_array($key, static::excluded_keys))
                {
                    $this->$key = $value == 'on' ? true : false;
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
