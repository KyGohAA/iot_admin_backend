<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Schema;
use Auth;
use App\Setting;
use App\Language;
use App\User;
use App\Iot\Api;
use App\Iot\Device; 
use App\Iot\DeviceReading; 
use App\Iot\DeviceProfile;

use App\LeafAPI;
use App\Company;

class IOTUniversalsController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Cities Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        $this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        //$this->middleware('auth_admin');
    }

    public function getWPDashboardLabelData()
    {

        $api = new Api();   
        $door_sensor_id = '24e124141c141557';
        $ds = array();
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($door_sensor_id)) ;
        $ds_res = $this->extraReadingData($temp) ;
        $ds = is_array($temp) ? false :json_decode($temp,true);
        //dd($ds['deviceProfileName']);
        $sensor['door']['state'] =  isset($ds_res['state']) ? $ds_res['state'] : '-';
        $sensor['door']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        $entrance_sensor = array();
        
        $entrance_sensor_id = '24e124600c124993';
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($entrance_sensor_id));
        $ds = is_array($temp) ? false : json_encode($temp,true);
        //dd($ds->deviceProfileName);
        $ds_res = $this->extraReadingData($temp) ;
      
        
        $sensor['entrance']['in'] =  isset($ds_res['in']) ? $ds_res['in'] : '0';
        $sensor['entrance']['out'] =  isset($ds_res['out']) ? $ds_res['out'] : '0';
        $sensor['entrance']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        $entrance_sensor_id = '24e124136c225107';
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($entrance_sensor_id));
        $ds_res = $this->extraReadingData($temp) ;
        $ds = is_array($temp) ? false : json_decode($temp,true);
        //dd($ds);
        $sensor['environment']['temperature'] =  isset($ds_res['temperature']) ? $ds_res['temperature'] : '-';
        $sensor['environment']['humidity'] =  isset($ds_res['humidity']) ? $ds_res['humidity'] : '-';
        $sensor['environment']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        $entrance_sensor_id = '24e124538c019556';
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($entrance_sensor_id));
        $ds_res = $this->extraReadingData($temp) ;
        //dd($temp);
        $ds = is_array($temp) ? false : json_decode($temp, true);
        //dd($ds_res);
        $sensor['environment']['daylight'] =  isset($ds_res['daylight']) ? $ds_res['daylight'] : '-';
        $sensor['environment']['pir'] =  isset($ds_res['pir']) ? $ds_res['pir'] : '-';
        $sensor['environment']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';


         $return = array();
         $return['iot_on_off_count'] = ucfirst($sensor['door']['state']);
         $return['iot_temperature'] = $sensor['environment']['temperature'].' ℃' ;
         $return['iot_humidity'] = $sensor['environment']['humidity'].' %RH';
         $return['iot_brightness'] = ucfirst($sensor['environment']['daylight']);
         $return['iot_data_date_range'] = 'Data (2023/01/02 - '.date('Y/m/d',strtotime('now')).')';

         $null_checks = ['iot_on_off_count','iot_temperature','iot_humidity','iot_brightness'];

         foreach($null_checks as $check)
         {
            //echo $return[$check].'<br>';
            if(strpos($return[$check],'-')!==false)
            {
                $this->getWPDashboardLabelData();
            }
         }
        
         return json_encode($return);
    }

    public function getWPLabelData(Request $request)
    {
        //dd($request->all());
        $dev_eui = $request->get('dev_eui') != null ? $request->get('dev_eui') : false;
        //dd($dev_eui);
         
        if($dev_eui == false)
        {
            return 'No dev eui';
        }
        //$door_sensor_id = '24e124141c141557';

        $api = new Api();
        $ds = array();
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($dev_eui)) ;

        $ds_res = $this->extraReadingData($temp) ;
        $ds = is_array($temp) ? false :json_decode($temp,true);

        if(!isset($ds['deviceName']))
        {
            return 'No device name';
        }
        $var_to_read = $this->getDeviceVarsMapper($ds['deviceName']);
        //dd($var_to_read);
        //dd($ds['deviceProfileName']);
        $value =  isset($ds_res[$var_to_read]) ? $ds_res[$var_to_read] : '-';
            
        return $value;

        //$sensor['door']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';
    }

    public function getDeviceVarsMapper($device_name){

        $mappers = ['ws301' => 'state'];
        return isset($mappers[$device_name]) ? $mappers[$device_name] : false;
    }


    public function getTestWpConverter()
    {
        $device_id = isset($_GET['dev_eui']) ? $_GET['dev_eui']  : false ;
        if($device_id == false)
        {
            return '';
        }
        $device_id = '24e124136c225107';
        $reading_data = DeviceReading::getByDeviceEui($device_id);
        $summarize_data = $this->getIotSummaryData($reading_data);
        //dd($summarize_data);
        if($summarize_data['status_code'] != 1)
        {
            return '';
        }

        $summarize_data['request_type'] = $_GET['request_type'] != null ? $_GET['request_type']  : false ;
        if($summarize_data == false)
        {
            return '';
        }

        return $this->wpGraphConverter($summarize_data);

    }

    public function getTestWpConverterHumidity()
    {
        $device_id = '24e124136c225107';
        $reading_data = DeviceReading::getByDeviceEui($device_id);
        $summarize_data = $this->getIotSummaryData($reading_data);
        //dd($summarize_data);
        if($summarize_data['status_code'] != 1)
        {
            return '';
        }

        $summarize_data['request_type'] = 'humidity';
        $summarize_data['dev_eui'] = $device_id;
        if($summarize_data == false)
        {
            return '';
        }

        return $this->wpGraphConverter($summarize_data);

    }

    public function getTestWpConverterTemperature()
    {
        $device_id = '24e124136c225107';
        $reading_data = DeviceReading::getByDeviceEui($device_id);
        $summarize_data = $this->getIotSummaryData($reading_data);
        //dd($summarize_data);
        if($summarize_data['status_code'] != 1)
        {
            return '';
        }

        $summarize_data['request_type'] = 'temperature';
        $summarize_data['dev_eui'] = $device_id;
        if($summarize_data == false)
        {
            return '';
        }

        return $this->wpGraphConverter($summarize_data);

    }


    public function getTestWpConverterOnOff()
    {
        $device_id = '24e124141c141557';
        $reading_data = DeviceReading::getByDeviceEui($device_id);
        $summarize_data = $this->getIotSummaryData($reading_data);
       
        if($summarize_data['status_code'] != 1)
        {
            return '';
        }
     
        $summarize_data['request_type'] = 'state';
        if($summarize_data == false)
        {
            return '';
        }

        return $this->wpGraphOnOffConverter($summarize_data);

    }

       public function wpGraphConverter($summarize_data)
    {

          //x = date  , y = value
          $request_type = isset($summarize_data['request_type']) ? $summarize_data['request_type'] : false;
          if($request_type == false){
            return '';
          }   

          $device_name = Device::getDeviceNameByDevEui($summarize_data['dev_eui']);
            
          $data = ['name'=>$device_name ,'data' => array()];
          $category = array();
          $r_data = array();
     
          foreach($summarize_data['data']['data'] as $type => $readings)
          {
            //echo $type.'<br>';
            if($type != $request_type){
                continue;
            }
            foreach($readings as $key => $value)
            {
                $new = number_format((float)$value[0], 2, '.', '');
                //echo $key.' : '.$value[0].'<br>';
                array_push($r_data,$new);
                array_push($category,$key);
            }

          }


        $t_data  = [
                        'name' => $device_name ,
                        'data' => $r_data
                    ];
        $final_data = array();
        array_push($final_data , $t_data);

        $return = ['data' => $final_data , 'category' => $category];
          

        return json_encode($return);


    }


    /*return '{"data":[{"name":"Open","data":[0,2,4,5,6,7]},{"name":"Close","data":[0,2,4,5,6,7]}],"category":["2022-12-13","2022-12-18","2022-12-20","2022-12-21","2022-12-22","2022-12-23","2022-12-24","2022-12-25","2022-12-26","2022-12-27","2022-12-28","2022-12-29","2022-12-30"]}';*/
    public function wpGraphOnOffConverter($summarize_data)
    {

          //x = date  , y = value
          $data_mappers = ['Open','Close'];
          $request_type = isset($summarize_data['request_type']) ? $summarize_data['request_type'] : false;
          if($request_type == false){
            return '';
          }   
            
          $data = ['name'=>'Superman','data' => array()];
          $category = array();
          //$category = ['Open','Close'];
          $r_data = array();
          $raw_data = array();
     
          foreach($summarize_data['data']['data'] as $type => $readings)
          {
            //echo $type.'<br>';
            if($type != $request_type){
                continue;
            }
            //dd($readings);
            //process category
            foreach($readings as $key => $value)
            {  
                $cat = substr($key, 0, 10);
                 //dd(date('Y-m-d' ,  strtotime($key)));
                $var = key($value) == 0 ? 'Close' : 'Open';
                $new = $value[0];
                //echo $key.' : '.$value[0].'<br>';
                $raw_data[$cat][$var] = isset($raw_data[$cat][$var]) ? $raw_data[$cat][$var] : array();
                array_push($raw_data[$cat][$var],$new);
                array_push($r_data,$new);
                if(!in_array($cat,$category))
                {
                    array_push($category,$cat);
                }
                
            }

          }
          //dd($raw_data);
          $g_data = array();
          foreach($raw_data as $x_axis => $y_axis_data)
          {
                foreach($data_mappers as $dm)
                {
                    //echo $dm.'<br>';
                    $val = 0 ;
                    if(isset($y_axis_data[$dm]))
                    {
                        $val = count($y_axis_data[$dm]);
                    }
                    $g_data[$dm] = isset($g_data[$dm]) ? $g_data[$dm] : array();
                    array_push( $g_data[$dm] , $val);
                }
                //dd($y_axis_data);
          }
//echo 'Show';
          //dd($g_data);
       
        $final_data = array();
        foreach($g_data as $x_axis => $gd)
        {
            $t_data  = [
                    'name' => $x_axis,
                    'data' => $gd
                ];

            array_push($final_data , $t_data);
        }
       

        $return = ['data' => $final_data , 'category' => $category];
          

        return json_encode($return);


    }

    public function generatePageVariable($request_type)
    {
        $name = ucwords(str_replace('_', ' ', $request_type));
        $this->page_variables = [
                                    'page_title'   =>   Language::trans( $name.' Page')
                                ];
        return  $this->page_variables;

    }

    public function getGraphInfo($keys)
    {
        //$_info = ['title' => '','graph_id' => '','x_axis' => '','y_axis' => '','symbol' => ''];
        $return = array();
        $data = ['humidity','temperature','dayligth','battery',"power","power_sum","current","voltage","factor","state"];
        $humidity_info = ['title' => 'Humidity','graph_id' => 'humidity','x_axis' => 'Time','y_axis' => 'Humidity','symbol' => ''];
        $temperature_info = ['title' => 'Temperature','graph_id' => 'temperature','x_axis' => 'Time','y_axis' => 'Temperateture (℃)','symbol' => '℃'];

        $battery_info = ['title' => 'Battery Usage','graph_id' => 'battery','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];

        $power_info = ['title' => 'Power','graph_id' => 'power','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];

        $power_sum_info = ['title' => 'Power Sum','graph_id' => 'power_sum','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];


        $voltage_info = ['title' => 'Voltage','graph_id' => 'voltage','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];

        $current_info = ['title' => 'Current','graph_id' => 'current','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];

        $factor_info = ['title' => 'Factor','graph_id' => 'factor','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];

        $state_info = ['title' => 'State','graph_id' => 'state','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];
 

        $daily_average_info = ['title' => 'Daily Average','graph_id' => 'daily_average','x_axis' => '','y_axis' => '','symbol' => '%'];
 
        //$state_info = ['title' => 'State','graph_id' => 'state','x_axis' => 'Time','y_axis' => '%','symbol' => '%'];
 



        $avarage_centers = ['humidity' => 50 , 'temperature' => 15];
        //array_push( $return ,$avarage_center);
        $dayligth_info = ['title' => '','graph_id' => '','x_axis' => '','y_axis' => '','symbol' => ''];
        $excluded = ['state'];
        //echo json_encode($keys).'<br>';
        foreach($keys as $key)
        {

            if(in_array($key,$excluded)){continue;}
            $var_name = $key.'_info';
            if(isset($$var_name))
            {

                array_push($return,array_merge($$var_name,$avarage_centers));
            }
            
        }

       

        //$return = array_merge( $return ,$avarage_center);

        //dd($return);
        return $return;
        

    }

    public function getSymbols()
    {
        $return = ['humidity' => '%RH' , 'temperature' => '℃'];
        return $return;
    }

    public function getDashboardChartData($is_continue=true)
    {
        if($is_continue == false){ return ;}
        
        $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not found.'),
                    'data'   =>  [],
                    ];
        $d_keys = ['dark' , 'light'];
        $total_record = 0;
        $temp_cpcd = array();
        $composition_pie_chart_data = array();
        $bar_chart_data = array();
        $device_id = '24e124538c019556';
        $feeds = DeviceReading::getByDeviceEui($device_id);
        $response = $this->getIotSummaryData($feeds);
        //dd($response);
        if($response['status_code'] == true)
        {
            $readings = isset($response['data']['daylight_data']) ? $response['data']['daylight_data'] : false;
            ///dd($readings);
           if($readings != false)
            {
                foreach($readings as $date => $vals)
                {   //dd($readings);
                    $dark_count = 0;
                    $light_count = 0;
                    foreach($vals as $v_key => $v_val)
                    {
                        $counter_name = $v_key.'_count';
                        $$counter_name = $$counter_name + $v_val;
                        //dd($vals); 
                    }

                    $temp = ['x'=> date('Y-m-d' ,  strtotime($date)) ,'y'=>$light_count ,'z'=>$dark_count];
                    array_push($bar_chart_data,$temp);

                    //$temp_cpcd +=

                }
                //dd($bar_chart_data);
                $dark_count = 0;
                $light_count = 0;
                foreach($readings as $date => $vals)
                {
                   
                    foreach($vals as $v_key => $v_val)
                    {
                        $counter_name = $v_key.'_count';
                        $$counter_name = $$counter_name + $v_val;
                        $total_record += $v_val;
                    }

                    
                }
                foreach($d_keys as $d_key)
                {
                    //dd($d_keys);
                    $counter_name = $d_key.'_count';                   
                    $tv = ($$counter_name/$total_record) * 100;
                    $composition_pie_chart_data[$d_key] = number_format((float)$tv, 2, '.', '');
                }

                $data = ['pie_chart' => $composition_pie_chart_data ,'bar_chart' => $bar_chart_data];
            }
        }

         $fdata = [
                    'status_code'   =>  count($data) > 0 ? true : false,
                    'data'   =>  $data,
                    ];

        return $fdata;
    }


    public function getIotSummaryData($data=null)
    {
        $d_id = ''; 
        $result;
        $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not found.'),
                    'data'   =>  [],
                    ];

        
        //$data = $data == null ? ( $_GET['dev_eui'] != null ? $_GET['dev_eui']  : null ) : null;
        //for calling
        if( $data == null)
        {
            $device_id = '24e124136c225107'; 
            $data = DeviceReading::getByDeviceEui($device_id);
        }
        

       
        if($data == null)
        {
            return $fdata;
        }

        $excluded = [];
        $date = '2022-12-20';
        //dd($data);
        $raw_daily_average = array();
        $graph_keys  = array();
        $final_data = array();
        /*$start_date = date('Y-m-d h a', strtotime($data[1]['created_at']));
        $end_date = date('Y-m-d h a', strtotime($data[count($data)-1]['created_at']));*/
        $start_date = isset($data[1]) ?  date('Y-m-d ', strtotime($data[1]['created_at'])) : false ;
        $end_date = isset($data[count($data)-1]) ?  date('Y-m-d ', strtotime($data[count($data)-1]['created_at'])) : false ;

        $date_range = ['start' => $start_date, 'end' => $end_date];
        //dd($start_date);
        foreach($data as $row){
             //dd($data);
             $h_key = date('Y-m-d H', strtotime('-7 day', strtotime($row['created_at'])));

             $raw_daily_average[$h_key] = isset($raw_daily_average[$h_key])  ? $raw_daily_average[$h_key] : array();
             array_push($raw_daily_average[$h_key] , $row);

             //extra key
            $reading = isset($row['reading_data']) ? json_decode($row['reading_data'],true) : false;
            if($reading != false)
            {
                foreach($reading as $key => $value)
                {
                    $reading_arr[$key] = isset($reading_arr[$key]) ? $reading_arr[$key] : array();
                    array_push($reading_arr[$key] , $value);
                    if(!in_array($key,$graph_keys))
                    {
                         if(in_array($key,$excluded)){continue;}
                        array_push($graph_keys , $key);
                    }
                }
            }
            
        }

        $graph_info = array();
        $temp_graph_info = $this->getGraphInfo(['daily_average']);

       
        foreach($graph_keys as $key)
        {
            $$key = array();

            foreach($temp_graph_info as $info)
            {
                $info['title'] = ucfirst($key).' '.$info['title'] ;
                $info['graph_id'] = $key.'_'.$info['graph_id'] ;
                array_push( $graph_info , $info);
            }

        }

        //dd($raw_daily_average);
        //start calculation
        $dayLightData = array();
        foreach($raw_daily_average as $time => $rows){
            //declare
            foreach($graph_keys as $key)
            {
                $var_name  = $key.'_total';
                $var_name_counter  = $key.'_counter';
                $$var_name = 0;
                $$var_name_counter = 0;
            }

            foreach($rows as $row){
                $d_id = isset($row['dev_eui']) ? $row['dev_eui'] : '';
                //dd($rows);
                $reading = isset($row['reading_data']) ? json_decode($row['reading_data'],true) : false;
                if($reading != false)
                {
                    foreach(json_decode($row['reading_data'],true) as $key => $value)
                    {
                        //echo $key.' : '.$value.'<br>';
                        //match declare and calculate total
                        $var_name  = $key.'_total';
                        $var_name_counter  = $key.'_counter';
                        //echo $$var_name.'<br>';
                        if(!is_numeric($value) )
                        {
                            if($key == 'daylight'){
                                //dd($row);
                                $dayLightData[date("Y-m-d" , strtotime($row['created_at']))][$value] = isset($dayLightData[date("Y-m-d" , strtotime($row['created_at']))][$value]) ? $dayLightData[date("Y-m-d" , strtotime($row['created_at']))][$value] : 0;
                                $dayLightData[date("Y-m-d" , strtotime($row['created_at']))][$value] +=1;
                                //dd($dayLightData);
                            }
                           
                        }else{
                             $$var_name += $value;
                        }
                       
                        $$var_name_counter ++ ;
                    }
                }

                 
            }
            //dd($dark);
           
            foreach($graph_keys as $key)
            {
                    $var_name  = $key.'_total';
                    $var_name_counter  = $key.'_counter';
                    $avg = $$var_name != 0 ? $$var_name/$$var_name_counter : 0;
                    $final_data[$key][$time] = isset($final_data[$key][$time]) ? $final_data[$key][$time] : array();
                    array_push($final_data[$key][$time] , $avg);
            }
          
        }

        $graph_average = array();
        foreach($final_data as $key => $fd_data)
        {
            $t  = 0;
            foreach($fd_data as $date => $value)
            {

                $t +=  $value[0];
            }

            $graph_average[$key] = $t/count($fd_data);
        }

   if($d_id == '24e124538c019556')
   {
        //dd($dayLightData);
   }
       
//24e124538c019556
        $fdata = [
                    'status_code'   =>  1,
                    'status_msg'    =>  Language::trans('Complete.'),
                    'data'   =>  ['labels' => $this->labelMapper($graph_keys) , 'data' => $final_data , 'graph_keys' => $graph_keys, 'graph_info' => $graph_info, 'date_range' => $date_range,
                    'graph_average' => $graph_average, 'symbols' => $this->getSymbols() , 'daylight_data' => $dayLightData /*, 'customize_graph_data' => $this->getDashboardChartData()*/
                    ]
                ];
       
        //dd($fdata);
        return $fdata;

          
    }

    public function labelMapper($keys)
    {
        $return = array();
        foreach($keys as $key)
        {

        }

        return $return ;
        
    }

    public function getLineChart()
    {
        $excluded = ['state'];
        $result;
        $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not found.'),
                    'data'   =>  [],
                    ];


        //$date_range     = array('date_started' => date('Y-m-d', strtotime('-7 day', strtotime('now'))) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
   
        $dev_eui = $_GET['dev_eui'] != null ? $_GET['dev_eui'] : false; 
        $label_arr =  array();
        $reading_arr =  array();
        $graph_keys = array();
        $feeds = DeviceReading::getByDeviceEui($dev_eui);
        
        foreach ($feeds as $row)
        {   
            //dd($row['reading_data']);
            //$row = (array) $row;
       
            array_push($label_arr,  $row['created_at']->format('Y-m-d H:i:s'));

            foreach(json_decode($row['reading_data'],true) as $key => $value)
            {
                $reading_arr[$key] = isset($reading_arr[$key]) ? $reading_arr[$key] : array();
                array_push($reading_arr[$key] , $value);
                if(!in_array($key,$graph_keys))
                {
                     if(in_array($key,$excluded)){continue;}
                    array_push($graph_keys , $key);
                }
            }
            
        }

        $graph_info = array();
        $temp_graph_info = $this->getGraphInfo($graph_keys);
        foreach($graph_keys as $key)
        {
            foreach($temp_graph_info as $info){
                if($key == $info['graph_id'])
                {
                    $graph_info[$key] = $info;
                }
            }
        } 

        
        //dd($reading_arr);
        $fdata = [
                    'status_code'   =>  1,
                    'status_msg'    =>  Language::trans('Data not found.'),
                    'data'   =>  ['labels' => $label_arr , 'data' => $reading_arr , 'graph_keys' => $graph_keys,'graph_info'=>$graph_info],
                    ];

        return $fdata;

    }

    public function getIndex($request_type)
    {
        //dd('xx');
        //request_type = $_GET['request_type'] !== null ? $_GET['request_type'] : 'false';
        //dd($request_type);
        //get class name
        $is_show_action = true;
        $model_class = 'App\\Iot\\'.str_replace( ' ', '',ucwords(str_replace( '_', ' ', $request_type))).'';
        //dd($model_class);
        //get page name
        $page_variables = $this->generatePageVariable($request_type);
        //dd($page_variables);
        $i              =   1;
        $model          =   new $model_class;
        //$model          =   $model_class::all();
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);
        $is_model_page  = true;


        $graph_info = array();
        $device_ids = ['24e124136c225107','24e124141c141557','24e124141c147463','24e124148b495286','24e124535b316056','24e124538c019556','24e124600c124993'];
        foreach($device_ids as $device_id)
        {
            $feeds = DeviceReading::getByDeviceEui($device_id);
            $tg_info = $this->getIotSummaryData($feeds);
            array_push($graph_info , $tg_info);
        }
        //dd($graph_info);
        //$feeds = DeviceReading::getByDeviceEui($device_id);
        //$graph_infox = $this->getIotSummaryData($feeds);

        return view(Setting::UI_VERSION.'iot.layouts.index', compact('model','i','page_variables','cols','graph_info','is_show_action'));
        
    }

    public function getDeviceInfo($device_id)
    {
          //dd('xx info');
        //request_type = $_GET['request_type'] !== null ? $_GET['request_type'] : 'false';
        //dd($request_type);
        //get class name
        //$eus = ['24e124136c225107','24e124600c124993','24e124535c266588','24e124148b495286','24e124538c019556','24e124141c141557'];
        //$device_id = '24e124538c019556';
        $device_id  = substr($device_id,1,strlen($device_id));
        $api = new Api();
        //get page name
        $page_variables = $this->generatePageVariable('Device Info');
        //dd($page_variables);
        //dd($api->callAPI(Api::setGetDeviceDataUrl($device_id)));
        //echo json_encode($api->callAPI(Api::setGetDeviceDataUrl($device_id))).'<br>';
        $response = $api->callAPI(Api::setGetDeviceDataUrl($device_id));
        if(is_array($response))
        {
            
            //echo 'Pass :'.json_encode($response).'<br>';
            if(sizeof($response) == 0){
               //echo 'Size check fail <br>';
              //continue;
                $model = Device::getByDevEui('x'.$device_id);
            }
         }else{
            $model =  json_decode($response,true);
         }


        //dd($model);

         $tables = ['main','rxInfo','txInfo','loRaModulationInfo'];
         $main_display = ['applicationName' => 'Application Name','deviceName' => 'Device Name'];
         //,'rxInfo' => 'RX Info','txInfo' => 'Tx Info'
         $rxInfo_display =['gatewayID'=> 'Gateway Id','rssi'=> 'RSSI','loRaSNR'=> 'LoRa SNR','channel'=> 'Channel','rfChain'=> 'Rf Chain','board'=> 'Board','antenna'=> 'Antenna','crcStatus'=> 'Crc Status','dr'=> 'dr','fCnt'=> 'fCnt','fPort'=> 'fPort'];
         $txInfo_display =['frequency' => 'Frequency','modulation' => 'Modulation'];
         $loRaModulationInfo_display =['bandwidth'  => 'Bandwidth','spreadingFactor'  => 'Spreading Factor','codeRate'  => 'Code Rate','polarizationInversion'  => 'Polarization Inversion'];

         $detail_info = array();
         foreach($tables as $table)
         {
            $var = $table.'_display';
            $detail_info[$table] = isset($detail_info[$table]) ? $detail_info[$table] : array();
            array_push($detail_info[$table],$$var);
         }


        $data = json_decode($model['objectJSON'],true);
        //dd($data);
        $feeds = DeviceReading::getByDeviceEui($device_id);
        //$line_chart_data = $this->getLineChart($device_id);
        if(isset($feeds[0]['reading_data']))
        {
            $keys = array_keys(json_decode($feeds[0]['reading_data'],true));
        }else{
            $keys = array();
        }
        
        $graph_info = $this->getGraphInfo($keys);

        $graph_infox = $this->getIotSummaryData($feeds);
        //dd($graph_infox);
        $is_model_page  = true;
        $dev_eui = $device_id;
        return view(Setting::UI_VERSION.'iot.device.info', compact('model','data','page_variables','is_model_page','feeds','dev_eui','graph_info','detail_info','tables'));
    }

    public function getNew()
    {
        $page_variables = $this->page_variables;
        $model = new City();

        return view(Setting::UI_VERSION.'commons.cities.form', compact('model','page_variables'));
    }

    public function postNew(Request $request)
    {
        $model = new City();
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());

        return redirect()->action('CitiesController@getEdit', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully created.'));
    }

    public function getView($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $model->country_id = $model->display_relationed('state_id', 'country_id');

        return view(Setting::UI_VERSION.'commons.cities.view', compact('model','page_variables'));
    }

    public function getEdit($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $model->country_id = (old('country_id') ? old('country_id'):$model->display_relationed('state_id', 'country_id'));

        return view(Setting::UI_VERSION.'commons.cities.form', compact('model','page_variables'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('CitiesController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully updated.'));
    }

    public function getDelete($id)
    {
        if (!$model = City::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $temp = $model->name;
        $model->delete();
        return redirect()->action('CitiesController@getIndex')
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $temp.Language::trans(' was successfully deleted.'));
    }

    public function extraReadingData($temp)
    {
        $return = false;
        if(is_array($temp))
        {
            
            //echo 'Pass :'.json_encode($response).'<br>';
            if(sizeof($temp) == 0){
               //echo 'Size check fail <br>';
              //continue;
              
            }
         }else{
             $ds =  json_decode($temp , true);
             $return = is_string($ds['objectJSON']) ? json_decode($ds['objectJSON'],true) : false ;
         }



         return $return;
    }

    public function getDashboard(Request $request)
    {   
        $index = 1;
        $api = new Api();
        //dd(Company::get_group_id());
        if(Company::get_group_id() == null)
        {
            Company::setGroupId(Setting::SUNWAY_GROUP_ID);
        }
        if(isset($_GET['leaf_group_id']))
        {
            Company::setGroupId($_GET['leaf_group_id']);
        }

        $is_model_page  = false;
        $page_variables = $this->page_variables;
        $company            =   new Company();
        // $company            =   $company->self_profile();

        $company = Company::get_model_by_leaf_group_id(Company::get_group_id());
        if (!Auth::check()) {
         
            return redirect()->action('OpencartUsersController@getLogin');
        }

        $door_sensor = array();
        $graph_info = array();
        $device_ids = ['24e124136c225107'/*,'24e124141c141557','24e124141c147463','24e124148b495286','24e124535b316056','24e124538c019556','24e124600c124993'*/];
        foreach($device_ids as $device_id)
        {
            $feeds = DeviceReading::getByDeviceEui($device_id);
            $tg_info = $this->getIotSummaryData($feeds);
            array_push($graph_info , $tg_info);
        }

        $door_sensor_id = '24e124141c141557';
        $ds = array();
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($door_sensor_id)) ;
        $ds_res = $this->extraReadingData($temp) ;
        $ds = is_array($temp) ? false :json_decode($temp,true);
        //dd($ds['deviceProfileName']);
        $sensor['door']['state'] =  isset($ds_res['state']) ? $ds_res['state'] : '-';
        $sensor['door']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        $entrance_sensor = array();
        
        $entrance_sensor_id = '24e124600c124993';
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($entrance_sensor_id));
        $ds = is_array($temp) ? false : json_encode($temp,true);
        //dd($ds->deviceProfileName);
        $ds_res = $this->extraReadingData($temp) ;
      
        
        $sensor['entrance']['in'] =  isset($ds_res['in']) ? $ds_res['in'] : '0';
        $sensor['entrance']['out'] =  isset($ds_res['out']) ? $ds_res['out'] : '0';
        $sensor['entrance']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        $entrance_sensor_id = '24e124136c225107';
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($entrance_sensor_id));
        $ds_res = $this->extraReadingData($temp) ;
        $ds = is_array($temp) ? false : json_decode($temp,true);
        //dd($ds);
        $sensor['environment']['temperature'] =  isset($ds_res['temperature']) ? $ds_res['temperature'] : '-';
        $sensor['environment']['humidity'] =  isset($ds_res['humidity']) ? $ds_res['humidity'] : '-';
        $sensor['environment']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        $entrance_sensor_id = '24e124538c019556';
        $temp = $api->callAPI(Api::setGetDeviceDataUrl($entrance_sensor_id));
        $ds_res = $this->extraReadingData($temp) ;
        //dd($temp);
        $ds = is_array($temp) ? false : json_decode($temp, true);
        //dd($ds_res);
        $sensor['environment']['daylight'] =  isset($ds_res['daylight']) ? $ds_res['daylight'] : '-';
        $sensor['environment']['pir'] =  isset($ds_res['pir']) ? $ds_res['pir'] : '-';
        $sensor['environment']['name'] =  isset($ds['deviceProfileName']) ? $ds['deviceProfileName'] : '-';

        //dd($this->getDashboardChartData(123));
        //dd($ds_res);
        //$door_sensor = 
        //dd($graph_info);
        //dd('x');
        //$module_status_listing = OperationRule::get_module_operation_status_by_leaf_group_id(Company::get_group_id());
        return view(Setting::UI_VERSION.'iot.dashboards.index', compact('index','page_variables','graph_info','sensor'));
    }

    public function getTableData($request_type=null)
    {
        $data = array();
        $request_type = 'device';
        //dd('xx');
        //request_type = $_GET['request_type'] !== null ? $_GET['request_type'] : 'false';
        //dd($request_type);
        //get class name
        $model_class = 'App\\Iot\\'.str_replace( ' ', '',ucwords(str_replace( '_', ' ', $request_type))).'';
        //dd($model_class);
        //get page name
        $page_variables = $this->generatePageVariable($request_type);
        //dd($page_variables);
        $model          =   new $model_class;
        //$model          =   $model_class::all();
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);
        $is_model_page  = true;
        $counter = 1;
        foreach($model as $index => $row)
        {
            $t_data = array(); 
            $included = ['x24e124136c225107','x24e124141c147463','x24e124141c141557','x24e124148b495286','x24e124535b316056','x24e124538c019556','x24e124600c124993'];

            if(isset($row['dev_eui']))
            {
                if(!in_array($row['dev_eui'],$included)){continue;}
            }
            
            array_push($t_data,$counter);                      
            foreach($row->toArray() as $key => $value)
            {

                if(!in_array($key,$cols))
                {
                    continue;
                }
            
                if($key == 'device_profile_id'){
                    continue;
                }      
                                                    

                    if($key == 'dev_eui')
                    {
                            $vars = ['dev_eui','device_name'];
                            $dp = DeviceProfile::getById($row['device_profile_id']);
                            foreach($vars as $var)
                            {
                               $$var = '';
                            }
                            if(isset($dp['name']))
                            {
                                $dev_eui = substr($value,1,strlen($value));
                                $device_name = $dp['name'];
                            }

                            foreach($vars as $var)
                            {
                                array_push($t_data,$$var) ;
                            }
                          

                    }else if($key == 'status')
                    {
                            array_push($t_data , $row->display_status_string($key));
                    }elseif($key != 'user_id' && $key != 'id')
                    {
                            array_push($t_data ,  $value);
                    }
            }
           
            array_push($data,$t_data);                         
            $counter ++;
                                      
        }

        //dd($data);
        $graph_info = array();
        $device_ids = ['24e124136c225107','24e124141c141557','24e124141c147463','24e124148b495286','24e124535b316056','24e124538c019556','24e124600c124993'];

        foreach($device_ids as $device_id)
        {
            $feeds = DeviceReading::getByDeviceEui($device_id);
            $tg_info = $this->getIotSummaryData($feeds);
            array_push($graph_info , $tg_info);
        }

        $headers = array();
        foreach($cols as $col){
            $temp = '';
            if($col == 'device_profile_id'){
                continue;
            }      

            if($col == 'id'){
                $temp = '#';
            }else if($col == 'dev_eui'){
                $temp_cols = ['#','Device Eui','Name'];
                foreach( $temp_cols as $tc)
                {
                    array_push($headers,$tc);
                }
            }else if(str_contains($col, '_id')){
                $temp = Language::trans(ucwords(str_replace('_id', '', $col)));
            
            }else{
                $temp = Language::trans(ucwords(str_replace('_', ' ', $col)));
            }
            

            if($col != 'dev_eui'){
                array_push($headers,$temp);
            }
    
        }

        $t_data  = [
                        'thead' => $headers,
                        'tbody' => $data
                    ];

        $return = ['data' => $t_data];


        return json_encode($return);
        
    }
}
