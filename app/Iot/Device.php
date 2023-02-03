<?php

namespace App\Iot;

use DB;
use Auth;
use Schema;
use Validator;
use App\Company;

use Illuminate\Database\Eloquent\Builder;

class Device extends ExtendModel
{
    //protected $primaryKey = 'dev_eui';
    protected $table = 'device';
    public $timestamps = true;
     protected $listing_except_columns = ['updated_at','service_profile_id','routing_profile_id','skip_fcnt_check'];



    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function application()
    {
        return $this->belongsTo('App\Application', 'application_id');
    }

    public function device_profile()
    {
        return $this->belongsTo('App\DeviceProfile', 'device_profile_id');
    }

    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id');
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

        /*static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id());
        });*/
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's
    |--------------------------------------------------------------------------
    |
    */

    public static function combobox($state_id=null)
    {
        return static::ofAvailable('status',true)
                                ->where('state_id','=',$state_id)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select city...'), '');
    }

    public static function getByDevEui($dev_eui)
    {
        return static::where('dev_eui','=',$dev_eui)
                                ->first();
    }

    public static function getDeviceNameByDevEui($dev_eui)
    {
        $device = static::where('dev_eui','=',$dev_eui)
                                ->first();

        if(isset($device['device_profile_id']))
        {
            $device_profile = DeviceProfile::getById($device['device_profile_id']);     
            return $device_profile['name'];    
        }
                           

        return '-';

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
                    'state_id'  =>  'required',
                    'code'      =>  'required|unique:device,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'name'      =>  'required|unique:device,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:device,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:device,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
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
                if ($key != '_token' && $key != 'country_id' && !in_array($key , static::excluded_keys)) {
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
