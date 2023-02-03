<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;
use App\Company;
use App\Room;
use App\PowerMeterModel\House;
use App\PowerMeterModel\MeterRegister;
use App\Setting;
use App\Language;
use App\LeafAPI;

use Illuminate\Database\Eloquent\Builder;

class StartUp extends ExtendModel
{
   
    public $timestamps = true;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id());
        });
    }

    public static function saveOrUpdateHouseRoom($leaf_group_id)
    {
        echo 'test <br>';
        $leaf_api = new LeafAPI();
        $c= new Company();
        $c->set_group_id($leaf_group_id);

        $temp_local_houses = House::where('leaf_group_id', '=',$leaf_group_id)->get();
        $temp_local_rooms = Room::where('leaf_group_id', '=',$leaf_group_id)->get();
        $temp_local_meter_registers = MeterRegister::where('leaf_group_id', '=',$leaf_group_id)->get();
        //dd(Company::get_group_id());
        //dd($temp_local_rooms[0]);

        $meter_registers = array();
        $local_houses = array();
        $local_rooms = array();   
        foreach($temp_local_houses as $house)
        {
            if($house['leaf_group_id'] != $leaf_group_id){continue;}
            $local_houses[$house['id_house']] = $house;
        }     

        foreach($temp_local_rooms as $room)
        {
            if($room['leaf_group_id'] != $leaf_group_id){continue;}
            $local_rooms[$room['id_house_room']] = $room;
        }   

        foreach($temp_local_meter_registers as $meter_register)
        {
            if($meter_register['leaf_group_id'] != $leaf_group_id){continue;}
            //echo $meter_register['leaf_room_id'].'<br>';
            $dedicated_room = isset($local_rooms[$meter_register['leaf_room_id']]) ? $local_rooms[$meter_register['leaf_room_id']] : false;
            if($dedicated_room == false)
            {
                echo 'Room data missing :'.json_encode($meter_register).'<br>';
                continue;
            }

            $dedicated_house = isset($local_houses[$dedicated_room['id_house']]) ? $local_houses[$dedicated_room['id_house']] : false;

            if($dedicated_house == false)
            {
                echo 'House data missing :'.json_encode($dedicated_room).'<br>';
                continue;
            }
            echo json_encode($meter_register).'<br>';
            if($meter_register['house_id'] == 0 && $dedicated_house['id'] != 0)
            {
                $meter_register['house_id'] = $dedicated_house['id'];
                $meter_register->save();
            }
            if($meter_register['room_id'] == 0 && $dedicated_room['id'] != 0)
            {
                $meter_register['room_id'] = $dedicated_room['id'];
                $meter_register->save();
            }

            dd($meter_register);
        } 
        dd('So');
        $houses = $leaf_api->get_houses(true,$leaf_group_id);   
        //dd($houses);

        if($houses['status_code'] == false){ return false;}
        foreach($houses['house'] as $house)
        {
            if($house['id_house'] == 33998)
            {
                dd($house['house_rooms']);
            }
            //$house['house_unit']
            foreach($house['house_rooms'] as $room)
            {
                //dd($room);
            }
        }
    }

   
}
