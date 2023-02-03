<?php

namespace App\PowerMeterModel;

use DB;
use Validator;
use App\Setting;
//for meter register table , meter id is the id of main meter , while other room having it own [id]
class MeterReadingDaily extends ExtendModel
{
    protected $table = 'meter_reading_dailys';
    public $timestamps = true;
    protected $listing_except_columns = ['created_by','updated_by','created_at','updated_at','leaf_group_id'];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's
    |--------------------------------------------------------------------------
    |
    */
    public static function get_monthly_meter_reading_by_id($meter_register_id)
    {
        $model = MeterReadingDaily::where('meter_register_id','=',$meter_register_id)
                                    ->leftJoin('meter_registers','meter_registers.id','=','meter_reading_dailys.meter_register_id')
                                    ->whereBetween('current_date', [date('Y-m').'-01', date('Y-m-d')])
                                    ->groupBy('meter_register_id')
                                    ->selectRaw('sum(meter_reading_dailys.current_usage) as current_usage, meter_registers.leaf_room_id as room_id,meter_registers.reading_status as reading_status , meter_registers.id as meter_register_id')
                                    ->get();

        return isset($model['current_usage']) ? $model['current_usage'] : 0;
    }


    public static function get_monthly_meter_reading_by_id_single_table($meter_register_id)
    {
        $model = MeterReadingDaily::where('meter_register_id','=',$meter_register_id)
                                    ->whereBetween('current_date', [date('Y-m').'-01', date('Y-m-d')])
                                    ->groupBy('meter_register_id')
                                    ->selectRaw('sum(current_usage) as current_usage, meter_register_id as meter_register_id')
                                    ->first();
  
        return  isset($model) > 0 ?  $model['current_usage'] : 0;
    }

    public static function get_total_meter_reading_by_id_single_table($meter_register_id,$starting_date=null)
    {
        $current_date = date('Y-m-d', strtotime('now'));
        $starting_date = isset($starting_date) ? $starting_date : '1970-01-01';
        $model = MeterReadingDaily::where('meter_register_id','=',$meter_register_id)
                                    ->whereBetween('current_date', [$starting_date, $current_date])
                                    ->groupBy('meter_register_id')
                                    ->selectRaw('sum(current_usage) as current_usage, meter_register_id as meter_register_id')
                                    ->first();

        return isset($model) > 0 ? $model['current_usage'] : 0;
    }

    public static function get_monthly_reading_summary_by_group_id($group_id = null , $date_range=null)
    {
        $listing = DB::select('SELECT `current_date` as month_year, SUM(`current_usage`) as total_amount FROM `meter_reading_dailys` WHERE `current_date` BETWEEN ? AND ? GROUP BY `current_date`', [$date_range('date_started'), $date_range('date_ended')]);
            $current_usage = '';
            if (count($listing)) {
                $current_usage .= '[';
                foreach ($listing as $row) {
                    $current_usage .= '[\''.date('d/m', strtotime($row->month_year)).'\', '.$row->month_year.'],';
                }
                $current_usage .= ']';
                trim($current_usage, ',');
            }
            if ($current_usage == '') {
                $current_usage = '[]';
            }

        return $listing;

    }

    public static function get_daily_reading_summary_by_meter_register_id($meter_register_id, $date_range=null)
    {
        $listing = DB::select('SELECT `current_date` as month_year, SUM(`current_usage`) as total_amount FROM `meter_reading_dailys` WHERE `meter_register_id` = ? AND `current_date` BETWEEN ? AND ? GROUP BY `current_date`', [ $meter_register_id , $date_range['date_started'], $date_range['date_ended']]);
            $current_usage = '';
            if (count($listing)) {
                $current_usage .= '[';
                foreach ($listing as $row) {
                    $current_usage .= '[\''.date('d/m', strtotime($row->month_year)).'\', '.$row->month_year.'],';
                }
                $current_usage .= ']';
                trim($current_usage, ',');
            }
            if ($current_usage == '') {
                $current_usage = '[]';
            }

        return $listing;

    }

    public static function get_monthly_reading_summary_by_group_id_and_date_range($group_id = null , $date_range=null)
    {
        $listing = DB::select('SELECT `current_date` as date_time, SUM(`current_usage`) as total_amount FROM `meter_reading_dailys` WHERE `current_date` BETWEEN ? AND ? GROUP BY YEAR(`current_date`), MONTH(`current_date`)', [$date_range['date_started'], $date_range['date_ended']]);
            $current_usage = '';
            if (count($listing)) {
                $current_usage .= '[';
                foreach ($listing as $row) {
                    $current_usage .= '[\''.date('d/m', strtotime($row->date_time)).'\', '.$row->date_time.'],';
                }
                $current_usage .= ']';
                trim($current_usage, ',');
            }
            if ($current_usage == '') {
                $current_usage = '[]';
            }

        return $listing;

    }


    public static function update_today_record_by_leaf_group_id($leaf_group_id=null)
    {
        $today_reading_listing = static::get_today_reading_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        $meter_register_listing = MeterRegister::get_active_meter_register_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
        $date_range = ['date_started' => date('Y-m-d', strtotime('now')) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];

        if(count($meter_register_listing) > 0){
            foreach ($meter_register_listing as $meter_row) {
                $is_record_update = false;
                foreach ($today_reading_listing as $reading_row) {
                    if($reading_row['meter_register_id'] == $meter_row['id']){
                        $daily_meter_reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($meter_row['id'],$date_range);
                        $reading_row['current_usage'] = $daily_meter_reading_listing[0]['current_usage'];
                        $reading_row['updated_at'] = date('Y-m-d h:m:s', strtotime('now'));
                        $reading_row->save();
                        $is_record_update = true;
                    }
                }

                if(!$is_record_update){
                    static::save_daily_meter_reading_by_meter_register_id($meter_row['id'] , $date_range);
                }
                
            }
        }


        return true;
    }

    public static function get_consumption_summary_by_leaf_room_id_and_date_range($leaf_room_id,$date_range,$leaf_group_id=null)
    {
        $period = Setting::get_company_monthly_cut_off_date_range_by_date_started_and_date_ended($date_range['date_started'],$date_range['date_ended']);
        $meter = MeterRegister::get_meter_register_by_leaf_room_id($leaf_room_id);
        $result = array();
        foreach ($period as $row) {
            $month_usage = static::where('leaf_group_id','=' , Setting::get_leaf_group_id($leaf_group_id))
                        ->where('meter_register_id','=',  $meter['id'])
                        ->groupBy(DB::raw("MONTH(current_date)"))
                        ->whereBetween('current_date',[$row['date_started'],$row['date_ended']])
                        ->selectRaw('sum(current_usage) as current_usage, meter_register_id, CONCAT(YEAR(current_date),"-",MONTH(current_date)) as month')
                        ->get();

            if(count($month_usage) == 0){
                $month_usage['current_usage'] = 0;
                $month_usage['meter_register_id'] = $meter['id'];
                $month_usage['month'] = date('Y-m', strtotime($row['date_ended']));
            }
            array_push($result, $month_usage);       
        }

        return $result;
    }

    public static function get_today_reading_by_leaf_group_id($leaf_group_id=null){
        
        $result = static::where('leaf_group_id','=' , Setting::get_leaf_group_id($leaf_group_id))
                ->where('current_date','=', date('Y-m-d', strtotime('now')))
                ->get();

        return $result;
    }

    public static function get_model_by_meter_register_id_and_current_date($meter_register_id,$current_date){
        $model = static::where('current_date','=', $current_date)
               ->where('meter_register_id','=',$meter_register_id)
               ->get();

        return $model;
    }

    public static function save_daily_meter_reading_by_meter_register_id($id,$date_range=null){

        $daily_meter_reading_listing = MeterReading::get_daily_meter_reading_by_meter_register_id($id,$date_range);
        if(is_object($daily_meter_reading_listing) == true){
          if(count($daily_meter_reading_listing) > 0){
                foreach ($daily_meter_reading_listing as $row) {         
                    print_r(json_encode($row)); 
                    echo '<br>';
                    echo date('Y-m-d', strtotime($row['created_at']));
                    $model = static::get_model_by_meter_register_id_and_current_date($row['meter_register_id'], date('Y-m-d', strtotime($row['created_at'])));         
                    print_r(isset($model['id']));
                    if(isset($model['id']) == true){
                        echo '<br>';
                        echo '============================================== Existed : '.$model['current_date'].' ==============================================';
                        continue;
                    }
                    $model = isset($model['id']) ? $model : new MeterReadingDaily();
                   // dd($model);
                    $model->save_form_by_meter_reading_daily_summary($row);
                }
            }
        }else{

        }
        
    }

    public static function save_daily_meter_reading_by_leaf_group_id($leaf_group_id,$date_range=null){

        $meter_register_listing = MeterRegister::get_active_meter_register_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));

        if(count($meter_register_listing) > 0){
            foreach ($meter_register_listing as $row) {
                static::save_daily_meter_reading_by_meter_register_id($row['id'],$date_range);
            }
        }

        return false;

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
                    'meter_register_id'     =>  'required|numeric',
                    'current_date'           =>  'required|date',
                    'time_started'          =>  'required',
                    'time_ended'            =>  'required',
                    'current_usage'           =>  'required|numeric',
                    'leaf_group_id'         =>  'required|numeric',
                    ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public function save_form_by_meter_reading_daily_summary($row)
    {   
        DB::beginTransaction();
        try {
                foreach ($row->getAttributes() as $key => $value) {
                    if ($key != 'created_at') {
                        $this->$key = (string) $value;
                    }else if($key == 'created_at'){
                        $this->current_date = date('Y-m-d', strtotime($value));
                    }
                }

                if (!$this->id) {
                    $this->created_at       =   date('Y-m-d h:m:s', strtotime('now'));
                } else {
                    $this->updated_at       =   date('Y-m-d h:m:s', strtotime('now'));
                }

                $this->save();
            
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }
}
