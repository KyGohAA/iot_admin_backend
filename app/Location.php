<?php

namespace App;

use DB;
use Auth;
use Schema;
use Validator;

use Illuminate\Database\Eloquent\Builder;

class Location extends ExtendModel
{
    protected $table = 'locations';
    public $timestamps = true;
    protected $listing_except_columns = ['remark','created_by','updated_by','created_at','updated_at','leaf_group_id'];

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
                                ->prepend(Language::trans('Please select location...'), '');
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
                    'code'      =>  'required|unique:locations,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'name'      =>  'required|unique:locations,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:locations,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:locations,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

    	$validator = Validator::make($input, $rules);

    	if ($validator->fails()) {
    		return $validator;
    	}
    	return false;
    }

    public function save_form($input)
    {
    	DB::beginTransaction();
    	try {
            foreach ($input as $key => $value) {
                if ($key != '_token') {
                    $this->$key = (string) $value;
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
