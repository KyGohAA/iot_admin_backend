<?php

namespace App\PowerMeterModel;

use DB;
use Auth;
use Schema;
use Validator;

use App\Company;
use App\LeafAPI;
use App\PaymentTestingAllowList;
use Illuminate\Database\Eloquent\Builder;

class PowerMeterSetting extends ExtendModel
{
    public static function get_charging_date_range_by_user_detail($user_detail)
    {
        
        $room  = $user_detail['room'];
        $member_detail = $user_detail['member_detail'];
        $leaf_group_id = Company::get_group_id();
        $is_allow_to_pay          = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($member_detail['house_member_id_user'],$leaf_group_id);
        $is_allow_to_pay =  $is_allow_to_pay == true ? true : false;

        $new_converted_single_room_aug_staff_id_house_member_arr = array(314212,314207,314241);
        $new_converted_single_room_aug_staff_id_arr = array(16083,30763,16167,18446,16164,16090,16178,16083,16192,16166,16088,16081,31126,16094,16224,16170,24806,16082);
        $converted_single_room_aug_staff_id_arr = array(21869,22764, 21853,21853,26491,21948,25262,19006,18618,18505,30440,22677,18241,22842,22678,21546,25208,19973,20121,21457,26467,18688,20944,20692,20239,25139,18302,20085,19964,19980,19966,19971,18867);
        $converted_twin_room_aug_staff_id_arr = array(18125,19275,19785,18187,26298,18121,18108,20673,26842,20098,20662,18699,29876,25901,19947,21497,17340,18544,18649,16096);
        $staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);

        $date_started = "";

        if(in_array($member_detail['house_member_id_user'],$staff_id_arr)){
            $date_started = '2019-04-01';
        }else if(in_array($member_detail['house_member_id_user'],$converted_single_room_aug_staff_id_arr)){
            $date_started = '2019-08-01';
        }else if(in_array($member_detail['house_member_id_user'],$new_converted_single_room_aug_staff_id_arr)){
            $date_started = '2019-08-01';
        }else if(in_array($member_detail['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
            $date_started = '2019-08-01';
        }else if(in_array($member_detail['house_member_id_user'],$converted_twin_room_aug_staff_id_arr)){
            $date_started = '2019-08-01';
        }else if($is_allow_to_pay == false){
            
            $date_started = $member_detail['house_room_member_start_date'];
            //echo $date_started < Company::get_system_live_date($leaf_group_id);
            //dd($date_started < Company::get_system_live_date($leaf_group_id));
            if(($date_started < Company::get_system_live_date($leaf_group_id)) == true){
                $date_started = Company::get_system_live_date($leaf_group_id);
            }
                
            if($date_started == ""){
                $date_started = Company::get_system_live_date($leaf_group_id);
            }
            
            
        }else{
            $date_started = $member_detail['house_room_member_start_date'];
            if($date_started == ""){
                $date_started = '2019-03-01';
            }
        }


        if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
                $start_date_1 = "";
                $start_date_2 = "";
                $counter = 1;
                foreach($user_detail['house_room_members'] as $member){
                    if($counter == 1){
                        $start_date_1 = $member['house_room_member_start_date'];
                    }else{
                        $start_date_2 = $member['house_room_member_start_date'];
                    }               
                    $counter ++;
                }
                if(date('Y-m', strtotime($start_date_1)) == date('Y-m', strtotime($start_date_2))){
                    if($start_date_1 < $member['house_room_member_start_date']){
                        $date_started = $start_date_1;
                    }
                    if($start_date_2 < $member['house_room_member_start_date']){
                        $date_started = $start_date_2;
                    }
                }                   

        }

        $date_range     = array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));

        return $date_range;
    
    }
}
