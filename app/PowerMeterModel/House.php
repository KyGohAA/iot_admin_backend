<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;

use App\Room;
use App\Company;

use Illuminate\Database\Eloquent\Builder;

class House extends ExtendModel
{
    protected $table = 'houses';
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

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id');
    }

    public function rooms()
    {
        return $this->hasMany('App\Room', 'house_id');
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
    | Here to manage of data's
    |--------------------------------------------------------------------------
    |
    */

    public static function combobox($state_id=null)
    {
        return static::ofAvailable('status',true)
                                ->where('state_id','=',$state_id)
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select House...'), '');
    }

    public static function get_houses($flag=null)
    {
        $houses = static::ofAvailable('status',true)
                 ->orderBy('house_unit' , 'asc')
                 ->get();

        $all_room_listing = Room::all();
        $room_listing = array();
        foreach($all_room_listing as $room){
            //dd($room);

            $room['house_room_members'] = json_decode($room['house_room_members']);
            $room['house_room_members'] = array_map(function($member) { return (array) $member; }, $room['house_room_members']);
            $room_listing[$room['id_house']] = isset($room_listing[$room['id_house']]) ?  $room_listing[$room['id_house']] : array();
            array_push($room_listing[$room['id_house']] , $room);
        }
      
        foreach($houses as $house){
           $house['house_rooms'] = $room_listing[$house['id_house']];
        }

        return ['status_code' => count($houses) > 0 ? 1 : 0 ,'house' => $houses->toarray()];
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
                    
                    ];

        if ($this->id) {
           
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public static function save_house_room($house)
    {
        DB::beginTransaction();
        try {
                $house_model = new House();
                $house_model->save_form($house);

                foreach($house['house_rooms'] as $room){
                   $room['id_house'] = $house['id_house'];
                   $room_model = new Room(); 
                   $room_model->save_form($room);
                }

             } catch (Exception $e) {
                throw $e;
                DB::rollBack();
            }
            DB::commit();
        
        
    }

    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            //dd($input);
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'house_rooms'  && $key != 'house_subgroups' && $key != 'house_country' && $key != 'house_state' && $key != 'house_fee_items') {
                    $this->$key = (string) $value;
                }else if($key == 'house_subgroups' || $key == 'house_fee_items')
                {
                      $this->$key = json_encode($value);
                }else if($key == 'house_country' || $key == 'house_state')
                {
                     $new_key = $key.'_id';
                     $this->$new_key = $value;
                }
            }

            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->status = 1;
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
