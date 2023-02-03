<?php

namespace App\PowerMeterModel;

use Illuminate\Database\Eloquent\Model;

use DB;
use Schema;
use ReflectionClass;
use App\Inflect;
use App\Setting;
use App\Language;
class ExtendModel extends Model
{
    const paginate = 30;
    const session_alert_status	=	'alert-status';
    const session_alert_icon 	=	'alert-icon';
    const session_alert_msg 	=	'alert-message';

    /*
    |--------------------------------------------------------------------------
    | Here to manage of application settings
    |--------------------------------------------------------------------------
    |
    */

    public static function set_connection($connection_string){

        //return DB::connection($connection_string);
    }

    public static function app_title()
    {
        return 'Leaf Extend';
    }

    public static function trans($value)
    {
    	return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of set & get mutators
    |--------------------------------------------------------------------------
    |
    */
    public function getDayInWord($day)
    {
        $day = (int) $day;
        $st_days = array(1,21,31);
        $nd_days = array(2,12,22);
        $rd_days = array(3,13,23);
        switch($day)
        {
            case (in_array($day , $st_days)):
            return $day.' st';
            break;

            case (in_array($day , $nd_days)):
            return $day.' nd';
            break;

            case (in_array($day , $rd_days)):
            return $day.' rd';
            break;

            default:
            return $day.' th';
            break;

        }
    }

    public function getDouble($value)
    {
        $value = str_replace(',', '', ($value ? $value:0));
        return number_format($value, 2,'.','');
    }

    public function setDouble($value)
    {
        $value = str_replace(',', '', $value);
        return number_format((float)$value, 2,'.','');
    }

    public function getDate($value)
    {
        if ($value) {
            return date('d-m-Y', strtotime($value));
        }
    }

    public function setDate($value)
    {
        if ($value) {
            return date('Y-m-d', strtotime($value));
        }
    }

    public function setTime($value)
    {
        if ($value) {
            return date('H:i:s', strtotime($value));
        }
    }

    public function convert_house_no($value, $houses)
    {
        if (isset($houses['house']) && $houses = $houses['house']) {
            foreach ($houses as $house) {
                if (isset($house['house_rooms'])) {
                    foreach ($house['house_rooms'] as $room) {
                        if ($room['id_house_room'] == $value) {
                            return $house['house_unit'];
                        }
                    }
                }
            }
        }
        return null;
    }

    public function convert_room_no($value, $houses)
    {
        if (isset($houses['house']) && $houses = $houses['house']) {
            foreach ($houses as $house) {
                if (isset($house['house_rooms'])) {
                    foreach ($house['house_rooms'] as $room) {
                        if ($room['id_house_room'] == $value) {
                            return $room['house_room_name'];
                        }
                    }
                }
            }
        }
        return null;
    }

    public function convert_house_room_no($value, $houses)
    {
        if (isset($houses['house']) && $houses = $houses['house']) {
            foreach ($houses as $house) {
                if (isset($house['house_rooms'])) {
                    foreach ($house['house_rooms'] as $room) {  
                        if ($room['id_house_room'] == $value) {
                            return $house['house_unit'].'-'.$room['house_room_name'];
                        }
                    }
                }
            }
        }
        return null;
    }

    public function rooms_range_no($rooms, $started, $ended)
    {
        $array = [];
        if (count($rooms)) {
            foreach ($rooms as $id => $name) {
                if ($id >= $started && $id <= $ended) {
                    $array[$id] = $name;
                }
            }
        }
        return $array;
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    public function scopeOfAvailable($query, $col, $bool)
    {
        return $query->where($col, '=', $bool);
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of displayed data
    |--------------------------------------------------------------------------
    |
    */

    public function encrypt($value)
    {
        return encrypt($value);
    }

    public function decrypt($value)
    {
        return decrypt($value);
    }

    public function display_substr($value,$length=null)
    {
        return substr($value, (isset($length) ? $length : 50));
    }

    public function display_relation_class($parent, $class)
    {
        $parent = str_replace('_id', '', $parent);
        return ($this->$parent ? $this->$parent->$class():'');
    }

    public function display_relation_child($parent, $class)
    {
        $parent = str_replace('_id', '', $parent);
        return ($this->$parent ? $this->$parent->$class:[]);
    }

    public function display_relationed($parent, $col)
    {
        $parent = str_replace('_id', '', $parent);
        return ($this->$parent ? $this->$parent->$col:'');
    }

    public function display_answer_string($col)
    {
    	return ($this->$col ? Language::trans('Yes'):Language::trans('No'));
    }

    public function display_status_string($col)
    {
    	return ($col ? Language::trans('Enabled'):Language::trans('Disabled'));
    }
	
	public function display_room_type_string($col){

        $roomType = "";

        if($this->$col == 'single'){
            $roomType = Setting::SINGLE_ROOM;
        }else if($this->$col == 'twin'){
            $roomType = Setting::TWIN_ROOM;
        }

        return Language::trans($roomType) ;
    }

    public function display_date($value)
    {
    	if ($value) {
    		return date('d-m-Y', strtotime($value));
    	}
    }

    public static function status_true_word()
    {
        return Language::trans('Active');
    }

    public static function status_false_word()
    {
        return Language::trans('Inactive');
    }

    public static function answer_true_word()
    {
        return Language::trans('Yes');
    }

    public static function answer_false_word()
    {
        return Language::trans('No');
    }

    public static function get_module_name($class_name){

        $reflectionClass = new ReflectionClass($class_name);
        $reflectionProperty = $reflectionClass->getProperty('table');
        $reflectionProperty->setAccessible(true);
        $table_name = Inflect::singularize($reflectionProperty->getValue(new $class_name));

        return ucwords(str_replace('_', " ", $table_name));
    }

    public function display_datetime_wo_second($value)
    {
        if ($value) {
            return date('d-m-Y H:i', strtotime($value));
        }
    }

    public function display_datetime($value)
    {
    	if ($value) {
    		return date('d-m-Y H:i:s', strtotime($value));
    	}
    }

    public function display_time_wo_second($value)
    {
    	if ($value) {
    		return date('H:i', strtotime($value));
    	}
    }

    public function display_time($value)
    {
    	if ($value) {
    		return date('H:i:s', strtotime($value));
    	}
    }

    public function three_month_pass()
    {
        return date('m-Y', strtotime('-3 month'));
    }

    public function last_month()
    {
        return date('m-Y', strtotime('-1 month'));
    }

    public static function previous_one_year_combobox()
    {
        for ($i=24; $i>=0; $i--) { 
            $string = date('m-Y', strtotime('-'.$i.' month'));
            $return[(string) $string] = (string) $string;
        }
        return $return;
    }

    public static function next_one_year_combobox()
    {
        for ($i=0; $i<=24; $i++) { 
            $string = date('m-Y', strtotime('+'.$i.' month'));
            $return[(string) $string] = (string) $string;
        }
        return $return;
    }

}
