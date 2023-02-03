<?php

namespace App\PowerMeterModel;

use DB;
use Validator;
use App\Company;
use App\LeafAPI;
use App\PowerMeterModel\MeterRegister;
use App\Setting;

use Illuminate\Database\Eloquent\Builder;

class MeterReading extends ExtendModel
{
    protected $table = 'meter_readings';
    public $timestamps = true;
    protected $listing_except_columns = ['created_by','updated_by','created_at','updated_at','leaf_group_id'];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            //$builder->where('leaf_group_id', '=', Company::get_group_id());
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
        return $this->listing_only_columns;
    }

    public function listing_header()
    {
        return $this->listing_only_columns;
    }

    public function scopeListing($query) 
    {
        return $query->select($this->listing_only_columns);
    }



    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's
    |--------------------------------------------------------------------------
    |
    */
    public function get_last_meter_reading($meter_register_id)
    {
        if ($meter_register_id) {
            # code...
        }
        if ($model = self::where('meter_register_id','=',$meter_register_id)->orderBy('id','desc')->first()) {
            return $model->current_meter_reading;
        }
        return 0;
    }

    public static function get_monthly_meter_reading_by_id($meter_register_id)
    {
        $model = MeterReading::where('meter_register_id','=',$meter_register_id)
                                    ->leftJoin('meter_registers','meter_registers.id','=','meter_readings.meter_register_id')
                                    ->whereBetween('current_date', [date('Y-m').'-01', date('Y-m-d')])
                                    ->groupBy('meter_register_id')
                                    ->selectRaw('sum(meter_readings.current_usage) as total_usage, meter_registers.leaf_room_id as room_id,meter_registers.reading_status as reading_status , meter_registers.id as meter_register_id')
                                    ->get();

        return isset($model['total_usage']) ? $model['total_usage'] : 0;
    }

    public static function get_selected_monthly_meter_reading_from_now($meter_register_ids=null , $number_of_months_from_now=null)
    {
        $date_range = ['date_started' => date('Y-m-d', strtotime('- '.$number_of_months_from_now.' month',  strtotime('now'))) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
        
        if($meter_register_ids == null)
        {   

             $listing = DB::select('SELECT `current_date` as date_time ,( SELECT COUNT(*) FROM meter_readings m2 WHERE `current_date` BETWEEN concat(year(`current_date`) , "-" , month(`current_date`) , "-01") AND concat(year(`current_date`) , "-" , month(`current_date`), "-31" ) AND m2.meter_register_id = meter_register_id  ) as total_hours,  `meter_register_id` , concat(year(`current_date`)  , "-"  , month(`current_date`)  ) as month_year, SUM(`current_usage`) as total_amount , MIN(`current_usage`) as min_usage , MAX(`current_usage`) as max_usageFROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY `meter_register_id` , YEAR(`current_date`), MONTH(`current_date`) ', [$date_range['date_started'], $date_range['date_ended']]);
        }else{


     /*       inner join ( 
    select  customer, count(*) as num_row, sum(duration) duration 
    from my_table 
    group by customer 
  ) t on t.customer  = a.customer
            */
             $meter_register_ids = $meter_ids = trim( trim( json_encode( $meter_register_ids) , '[' ), ']') ;
            /* $listing = DB::select('SELECT `current_date` as date_time , ( SELECT COUNT(*) FROM meter_readings m2 WHERE `current_date` BETWEEN concat(year(`current_date`) , "-" , month(`current_date`) , "-01") AND concat(year(`current_date`) , "-" , month(`current_date`), "-31")  AND m2.meter_register_id = meter_register_id  ) as total_hours,  `meter_register_id` , concat(year(`current_date`)  , "-"  , month(`current_date`)  ) as month_year, SUM(`current_usage`) as total_amount , MIN(`current_usage`) as min_usage , MAX(`current_usage`) as max_usage  FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? AND `meter_register_id` IN ('.$meter_register_ids.') GROUP BY `meter_register_id` , YEAR(`current_date`), MONTH(`current_date`) ', [$date_range['date_started'], $date_range['date_ended'] ]);*/

             $listing = DB::select('SELECT `current_date` as date_time ,  `meter_register_id` , concat(year(`current_date`)  , "-"  , month(`current_date`)  ) as month_year, SUM(`current_usage`) as total_amount , MIN(`current_usage`) as min_usage , MAX(`current_usage`) as max_usage  FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? AND `meter_register_id` IN ('.$meter_register_ids.') GROUP BY `meter_register_id` , YEAR(`current_date`), MONTH(`current_date`) inner join ( SELECT  COUNT(*) as total_hours FROM meter_readings   ) m2 on m2.meter_register_id = or.meter_register_id'/* AND `current_date` BETWEEN concat(year(`current_date`) , "-" , month(`current_date`) , "-01") AND concat(year(`current_date`) , "-" , month(`current_date`), "-31")*/ , [$date_range['date_started'], $date_range['date_ended'] ]);

        }
     
        //$listing = DB::select('SELECT month(`current_date`) as month_day, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? AND `meter_register_id` = ? GROUP BY month(`month_day`)', [$date_range['date_started'], $date_range['date_ended'], $meter_register_id]);
//dd($listing[0]);
        return  $listing;

    }

    public static function get_monthly_meter_reading_from_now($meter_register_id=null , $number_of_months_from_now=null)
    {
        $date_range = ['date_started' => date('Y-m-d', strtotime('- '.$number_of_months_from_now.' month',  strtotime('now'))) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
        
        if($meter_register_id == null)
        {   
             $listing = DB::select('SELECT `current_date` as date_time,  `meter_register_id` , concat(year(`current_date`)  , "-"  , month(`current_date`)  ) as month_year, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY `meter_register_id` , YEAR(`current_date`), MONTH(`current_date`) ', [$date_range['date_started'], $date_range['date_ended']]);
        }else{
             $listing = DB::select('SELECT `current_date` as date_time,  `meter_register_id` , concat(year(`current_date`)  , "-"  , month(`current_date`)  ) as month_year, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? AND `meter_register_id` = ? GROUP BY `meter_register_id` , YEAR(`current_date`), MONTH(`current_date`) ', [$date_range['date_started'], $date_range['date_ended'] , $meter_register_id]);
        }
       
        //$listing = DB::select('SELECT month(`current_date`) as month_day, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? AND `meter_register_id` = ? GROUP BY month(`month_day`)', [$date_range['date_started'], $date_range['date_ended'], $meter_register_id]);

        return  $listing;

    }

    public static function get_monthly_meter_reading_by_id_single_table($meter_register_id)
    {
        $model = MeterReading::where('meter_register_id','=',$meter_register_id)
                                    ->whereBetween('current_date', [date('Y-m').'-01', date('Y-m-d')])
                                    ->groupBy('meter_register_id')
                                    ->selectRaw('sum(current_usage) as total_usage, meter_register_id as meter_register_id')
                                    ->first();
  
        return  isset($model) > 0 ?  $model['total_usage'] : 0;
    }

    public static function get_daily_meter_reading_by_meter_register_id($meter_register_id,$date_range=null){
        //$meter_register_id = 84;
        $result = MeterReading::where('meter_register_id','=',$meter_register_id)
                                    ->groupBy('meter_register_id')
                                    ->groupBy('current_date');
        if(isset($date_range)){
            $result =  $result ->whereBetween('current_date', [$date_range['date_started'], $date_range['date_ended']]);
        }
       
        $result=$result->selectRaw('sum(current_usage) as total_usage, meter_register_id as meter_register_id , created_at , leaf_group_id')
                                    ->get();
    
        return  count($result) > 0 ?  $result: 0;
    }

    public static function get_total_meter_reading_by_id_single_table($meter_register_id,$starting_date=null)
    {
        $current_date = date('Y-m-d', strtotime('now'));
        $starting_date = isset($starting_date) ? $starting_date : '1970-01-01';
        $model = MeterReading::where('meter_register_id','=',$meter_register_id)
                                    ->whereBetween('current_date', [$starting_date, $current_date])
                                    ->groupBy('meter_register_id')
                                    ->selectRaw('sum(current_usage) as total_usage, meter_register_id as meter_register_id')
                                    ->first();

        return isset($model) > 0 ? $model['total_usage'] : 0;
    }


    public static function get_meter_register_daily_reading($date_range , $meter_register_id)
    {
        $listing = DB::select('SELECT `current_date` as month_day, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? AND `meter_register_id` = ? GROUP BY `current_date`', [$date_range['date_started'], $date_range['date_ended'], $meter_register_id]);
            $total_usage = '';
            if (count($listing)) {
                $total_usage .= '[';
                foreach ($listing as $row) {
                    $total_usage .= '[\''.date('d/m', strtotime($row->month_day)).'\', '.$row->month_day.'],';
                }
                $total_usage .= ']';
                trim($total_usage, ',');
            }
            if ($total_usage == '') {
                $total_usage = '[]';
            }

        return $listing;

    }
      
    public static function get_user_reading_in_period($period , $meter_register_id , $leaf_group_id=null)
    {
        $listing = MeterReading::whereBetween('meter_readings.created_at', [$period['starting_date_time'], $period['ending_date_time']])
                       ->where('meter_readings.leaf_group_id' , '=' , $meter_register_id)  
                       ->where('meter_readings.leaf_group_id' , '=' , Setting::get_leaf_group_id($leaf_group_id))  
                       ->leftJoin('meter_registers', 'meter_readings.meter_register_id', '=', 'meter_registers.id')
                       ->select('meter_readings.id','meter_readings.meter_register_id','meter_readings.current_date','meter_readings.time_started','meter_readings.time_ended','meter_readings.current_meter_reading' , 'meter_readings.current_usage','meter_readings.created_at','meter_readings.leaf_group_id','meter_registers.account_no','meter_registers.contract_no','meter_registers.meter_id','meter_registers.meter_class_id','meter_registers.ip_address','meter_registers.leaf_room_id','meter_registers.utility_charge_id')
                       ->orderBy('meter_register_id' ,'asc')
                       ->orderBy('created_at' ,'asc')
                       ->get();

        return $listing;
    }


    public static function get_reading_in_period($period , $leaf_group_id=null)
    {
        $listing = MeterReading::whereBetween('meter_readings.created_at', [$period['starting_date_time'], $period['ending_date_time']])
                       ->where('meter_readings.leaf_group_id' , '=' , Setting::get_leaf_group_id($leaf_group_id))  
                       ->leftJoin('meter_registers', 'meter_readings.meter_register_id', '=', 'meter_registers.id')
                       ->select('meter_readings.id','meter_readings.meter_register_id','meter_readings.current_date','meter_readings.time_started','meter_readings.time_ended','meter_readings.current_meter_reading' , 'meter_readings.current_usage','meter_readings.created_at','meter_readings.leaf_group_id','meter_registers.account_no','meter_registers.contract_no','meter_registers.meter_id','meter_registers.meter_class_id','meter_registers.ip_address','meter_registers.leaf_room_id','meter_registers.utility_charge_id')
                       ->orderBy('meter_register_id' ,'asc')
                       ->orderBy('created_at' ,'asc')
                       ->get();

        return $listing;
    }

    public static function get_meter_reading_time_frame($listing,$leaf_group_id=null){


        if(count($listing) > 0){
            

            /*$meter_register_listing = MeterRegister::get_active_meter_register_by_leaf_group_id(Setting::get_leaf_group_id($leaf_group_id));
            foreach ($meter_register_listing as $meter_register_model) {
                
            }*/
            dd($meter_register_listing);
        }

        return null;
    }

    


    public static function get_monthly_reading_summary_by_group_id($group_id = null , $date_range=null)
    {
        $listing = DB::select('SELECT `current_date` as month_year, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY `current_date`', [$date_range('date_started'), $date_range('date_ended')]);
            $total_usage = '';
            if (count($listing)) {
                $total_usage .= '[';
                foreach ($listing as $row) {
                    $total_usage .= '[\''.date('d/m', strtotime($row->month_year)).'\', '.$row->month_year.'],';
                }
                $total_usage .= ']';
                trim($total_usage, ',');
            }
            if ($total_usage == '') {
                $total_usage = '[]';
            }

        return $listing;

    }

    public static function get_monthly_reading_summary_by_group_id_and_date_range($group_id = null , $date_range=null)
    {
        $listing = DB::select('SELECT `current_date` as date_time, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY YEAR(`current_date`), MONTH(`current_date`)', [$date_range['date_started'], $date_range['date_ended']]);
            $total_usage = '';
            if (count($listing)) {
                $total_usage .= '[';
                foreach ($listing as $row) {
                    $total_usage .= '[\''.date('d/m', strtotime($row->date_time)).'\', '.$row->date_time.'],';
                }
                $total_usage .= ']';
                trim($total_usage, ',');
            }
            if ($total_usage == '') {
                $total_usage = '[]';
            }

        return $listing;

    }

    public static function get_daily_reading_summary_by_group_id_and_date_range($group_id = null , $date_range=null)
    {
        
        $listing = DB::select('SELECT `current_date` as date_time, SUM(`current_usage`) as total_amount FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY `current_date` ORDER BY `current_date`', [$date_range['date_started'], $date_range['date_ended']]);
            $total_usage = '';
            if (count($listing)) {
                $total_usage .= '[';
                foreach ($listing as $row) {
                    $total_usage .= '[\''.date('d/m', strtotime($row->date_time)).'\', '.$row->date_time.'],';
                }
                $total_usage .= ']';
                trim($total_usage, ',');
            }
            if ($total_usage == '') {
                $total_usage = '[]';
            }

        return $listing;

    }

    public static function get_last_meter_reading_model($meter_register_id)
    {
        if ($meter_register_id) {
            # code...
        }
        if ($model = self::where('meter_register_id','=',$meter_register_id)->orderBy('id','desc')->first()) {
            return $model;
        }
        return null;
    }


    public function get_last_meter_reading_update($meter_register_id)
    {
        if ($meter_register_id) {
            # code...
        }
        if ($model = self::where('meter_register_id','=',$meter_register_id)->orderBy('id','desc')->first()) {
            return $model->updated_at;
        }
        return 0;
    }

    public static function get_current_usage_by_year_month_and_meter_id($year,$month,$id){
        
        $result['currentUsageKwh']  =  round(static::where('meter_register_id','=',$id)
                                             ->whereBetween('current_date', [ date($year.'-'.$month.'-1'), date($year.'-'.$month.'-31')])
                                             ->value(DB::raw("SUM(current_meter_reading) - SUM(last_meter_reading)")),2);

        return $result;
    }

    public static function get_group_last_update_time_by_leaf_group_id($leaf_group_id=null)
    {
        $leaf_group_id = isset($leaf_group_id) ? $leaf_group_id : Company::get_group_id();
        $result = static::where('leaf_group_id','=' , $leaf_group_id)
                ->select('created_at')
                ->orderBy('created_at','desc')
                ->first();

        return $result['created_at'];
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
                    'ip_address'            =>  'required|ip',
                    'meter_id'              =>  'required|numeric',
                    'current_date'          =>  'required|date',
                    'time_started'          =>  'required',
                    'time_ended'            =>  'required',
                    'current_meter_reading' =>  'required|numeric',
                    'leaf_group_id'         =>  'required|numeric',
                    ];

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
            if ($this->meter_register_id    =   MeterRegister::get_id($input)) {
                foreach ($input as $key => $value) {
                    if ($key != '_token' && $key != 'ip_address' && $key != 'meter_id' && $key != 'app_secret') {
                        $this->$key = (string) $value;
                    }
                }
                $this->last_meter_reading   =   $this->get_last_meter_reading($this->meter_register_id);
                $this->current_usage        =   $this->current_meter_reading - $this->last_meter_reading;
                $this->save();

                $meter_register_model = MeterRegister::find($this->meter_register_id );

                $leaf_api= new LeafAPI();
                //$leaf_api = $leaf_api->peter_login();
                Setting::set_company(282);
                if(Company::get_group_id() != 282){
                     Setting::set_company(282);
                }
                                
                $meter_register_model = MeterRegister::find($this->meter_register_id);
                $meter_register_model['last_reading_at'] = date('Y-m-d h:i:s', strtotime('now')) ;
                $meter_register_model['last_reading'] = date('Y-m-d h:i:s', strtotime('now')) ;

                if(isset($meter_register_model['id'])){
                    $meter_register_model->update();
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }
}
