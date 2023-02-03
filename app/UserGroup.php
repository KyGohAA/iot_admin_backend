<?php

namespace App;

use DB;
use Auth;
use Schema;
use Validator;

use Illuminate\Database\Eloquent\Builder;

class UserGroup extends ExtendModel
{
    protected $table = 'user_groups';
    public $timestamps = true;
    protected $listing_except_columns = ['json_permissions','remark','leaf_group_id','remember_token','created_by','updated_by','created_at','updated_at'];

    protected $guarded = [];

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
    | Here to manage of getter and setter
    |--------------------------------------------------------------------------
    |
    */

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
                                ->prepend(Language::trans('Please select user group...'), '');
    }

    public function get_permissions($controller, $action)
    {
        if (!$allowlist = json_decode($this->json_permissions, true)) {
            return false;
        }
        if (!isset($allowlist[$controller])) {
            return false;
        }
        if (!in_array($action, $allowlist[$controller])) {
            return false;
        }
        return true;
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
                    'name'          =>  'required',
                    ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    const exclude_keys = ['user_assign','select_all'];
    const true_false_key = ['status','is_admin'];
    public function save_form($input)
    {
    	DB::beginTransaction();
    	try {
            foreach ($input as $key => $value) {
                if(in_array($key,static::exclude_keys))
                {
                    continue;
                }

                if(in_array($key,static::true_false_key))
                {
                    if($value == 'on')
                    {
                        $value = true;
                    }else{
                        $value = false;
                    }
                }
                if ($key != '_token' && $key != 'select_all') {
                    if ($key == 'permissions') {
                        $key    =   'json_permissions';
                        $value  =   json_encode($value);
                    }
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
