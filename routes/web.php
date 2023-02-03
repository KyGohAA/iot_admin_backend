<?php

/*use DB;
use Auth;
use Schema;*/

use App\Language;
use App\PowerMeterModel\MeterPaymentReceived;
use App\Company;
use App\Setting;
use App\LeafAPI;
use App\Customer;
use App\MembershipModel\ARPaymentReceived;
use Dompdf\Dompdf;
use App\Setia\PaymentReceivedRPdf ;
use App\webGrabber\Ego888WebGrabber ;
use App\webGrabber\CostcoWebGrabber;

use App\MembershipModel\ARInvoice;
use App\PowerMeterModel\MeterSubsidiary;
use App\MembershipModel\ARInvoiceItem;
use App\PowerMeterModel\MeterRegister;
use App\PowerMeterModel\MeterReading;
use App\PowerMeterModel\DeviceError;


use App\MembershipModel\ARPaymentReceivedItem;
use App\Product;
use App\User;
use App\Currency;
use App\PowerMeterModel\PowerMeterAccount;
use App\PowerMeterModel\PowerMeterSetting;
use App\Setia\PaymentReceivedPdf;
use App\Inflect;
use App\OperationRule;
use App\FileIOHelper;
use App\OpencartLanguageTranslator;
use App\PaymentTestingAllowList;
use App\PowerMeterModel\MeterReadingDaily;
use App\PowerMeterModel\MeterReadingMonthly;
use App\ProjectModelMapping;
use App\UTransaction;
use App\PowerMeterModel\CustomerPowerUsageSummary;
use App\NclAPI;
use App\Membership;
use App\WinzAPI;
use App\WinzAPI_2;
use App\SkyNetAPI;
use App\City;
use App\House;
use App\Room;
use App\webGrabber\AirbnbWebGrabber;

use App\Opencart\Product as OCProduct;
use App\Opencart\Category as OCCategory;
use App\Opencart\Setting as OCSetting;
use App\WPModel\UrbanAPI;
use App\WPModel\WpPost;
use App\WPModel\WpPostmeta;
use App\WPModel\WpOption;
use App\OcTranslationsWord;

use App\APIClient;
use App\BackendData;
use App\UserAssign;
use App\UtilityCharge;
use App\PowerMeterModel\StartUp;
use App\Iot\Api;
use App\Iot\Device;
use App\Iot\DeviceReading;

use App\IotProjectSetup\ProjectCreationHelper;
use App\IotProjectSetup\Post;

Route::get('updateProjectData', function (){
  //dd('mco');
  $target = 'http://localhost/wordpress_iot';
  $data = 'http://localhost/wordpress_iot_copy';
  //$key = 'guid';
  //$iotPost = new  Post();
  //$iotPost->updateProjectData($target,$data,$key);

  $g = new ProjectCreationHelper();
  $g->updateProjectData($target,$data);
  dd('end');
});


//'guid'=>' IotPost'

Route::get('cloneProject', function (){
  dd('cc');
  $pcHelper = new  ProjectCreationHelper();
  $pcHelper->clone_project(123);
  dd('end');
});


Route::get('testGraph', function (){
  //x = date 
  //y = value
  $t = array();
  $x["name"] = "Test 5";
  $x["data"] = array(87,56,45,43,56,76);

  $t['data'] = isset($t['data']) ? $t['data'] : array();
  array_push($t['data'],  $x);
  $t["category"] = array("Jan","Feb","Mar","Apr","May","Jun");
  return json_encode($t);

  $data = ['name'=>'Superman','data' => array()];

  
  $processed_data = array();
  $x_temp = ["10-04-2020 10:00","10-04-2020 11:00","10-04-2020 12:00"];
  $y_temp = [[10,40,20,30],[45,85,21,20],[ 36,85,57,52]];
  for($x=0;$x<3;$x++){
      $temp['x'] = $x_temp[$x];
      $temp['y'] = $y_temp[$x];
      array_push($processed_data,$temp);
    }

    $data['data'] = $processed_data;

    return json_encode($data);
  
  });


Route::get('callTest', function (){
      
     // $devices_queue = '13.214.31.177:8090/api/devices';
     // Api::callAPI($devices_queue);
       //dd('xx');
      $api = new Api();
      $devices = Device::all();
      foreach($devices as $device)
      {
         //dd($device);
         echo $device['dev_eui'].'<br>';
         $dev_eui = substr($device['dev_eui'],1,strlen($device['dev_eui']));
         echo $dev_eui.'<br>';
         $url = '13.214.31.177:8090/api/devices/'.$dev_eui.'/events';
         //$raw_data =  json_decode($api->callAPI(Api::setGetDeviceDataUrl($dev_eui)),true);
         $response =  $api->callAPI(Api::setGetDeviceDataUrl($dev_eui));

         if(is_array($response))
         {
            
            //echo 'Pass :'.json_encode($response).'<br>';
            if(sizeof($response) == 0){
               //echo 'Size check fail <br>';
              continue;
            }
         }

         /*if(is_string($response))
         {
            continue;
         }*/

         echo json_encode($response).'<br>';
         $raw_data =  json_decode($response,true);

         if(isset($raw_data['objectJSON']))
         {
            //echo 'Save <br>';
            $reading_data = json_decode($raw_data['objectJSON'],true);
            $device_reading = new DeviceReading();
            $device_reading['dev_eui'] = $dev_eui ;
            $device_reading['raw_data'] = json_encode($raw_data);
            $device_reading['reading_data'] = json_encode($reading_data);
            $device_reading['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
            $device_reading->save();
            
         }else{
           // echo 'No Save <br>';
         }
       


         // echo 'Show :'.json_encode($data).'<br><br><br><br>';
      }
     

  
});


Route::get('matchBack', function (){
    //$leaf_group_id = 519;
    //$checkAndRepairtSet = ['519'=>'282'];
    $checkAndRepairtSet = ['282'];
    $dateToCheck = ['2021-01-01',date('Y-m-d', strtotime ('now'))];
    foreach($checkAndRepairtSet as $leaf_group_id_to_update)
    {
        
        //dd($dataToCheck);
        //$dataToCheck = [date('Y-m-d',(strtotime ( '-3 day' , strtotime ('now') ) )) , date('Y-m-d', strtotime ('now')) ];
        $wrongPairTransaction = UTransaction::leftJoin('meter_payment_receiveds', 'utransactions.id', '=', 'meter_payment_receiveds.utransaction_id')
                                 ->whereBetween('utransactions.created_at',$dateToCheck)
                                 ->where('utransactions.is_paid','=',true)
                                 ->where('utransactions.leaf_group_id','=',$leaf_group_id_to_update)
                                 ->where('meter_payment_receiveds.leaf_group_id','!=',$leaf_group_id_to_update)
                                 ->get()
                                 ->toArray();
                                 //dd($wrongPairTransaction);
        $transaction_ids = array_column($wrongPairTransaction,'utransaction_id');
        //dd($transaction_ids);
        $wrongMeterReceiveds = MeterPaymentReceived::whereIn('utransaction_id',$transaction_ids)
                            ->get();
        dd($wrongMeterReceiveds);
        foreach($wrongMeterReceiveds as $row)
        {
            $row['leaf_group_id'] = $leaf_group_id_to_update;
            $row->save();
            //dd($row);
        }
    }
    
    dd('End');
    //dd($wrongMeterReceiveds);

});


Route::get('errorDevice', function (){

        $time_to_check = '2022-08-18';
        $leaf_group_id = 282;
        Company::setGroupId($leaf_group_id);   
        $houses = array();
        $rooms = Room::all();
        $temp_houses = House::all();
        $meter_registers = MeterRegister::all();

        $key_to_save = ['id_house','house_room_name','id_house_room'];
        $room_data = array();
        foreach($temp_houses as $house)
        {
          $houses[$house['id_house']] = $house;
        }
        foreach($rooms as $room)
        {
          //echo $room['leaf_group_id'].'<br>';
          if($room['leaf_group_id'] != $leaf_group_id){continue;}
          $temp = array();
          foreach($key_to_save as $key)
          {
            $temp[$key]  = isset($room[$key]) ? $room[$key] : '';
          }
           
          $temp_house = isset($houses[$room['id_house']]) ? $houses[$room['id_house']] : false;
          //echo $room['id_house'];dd();
          $temp['room_name'] =  $temp_house  != false ? $temp_house['house_unit'].' : room '.$room['house_room_name'] : 'room '.$room['house_room_name'];
          $room_data[$room['id_house_room']] = $temp;

        }

        $error_devices = array();
        //echo $time
        $temp_error_devices = DeviceError::WHERE('created_at' ,'like', '%'.$time_to_check.'%')->get();

        //dd($temp_error_devices);
        foreach($temp_error_devices as $error)
        {
           //dd($error);
           $error_devices[$error['meter_register_id']] = isset($error_devices[$error['meter_register_id']]) ? $error_devices[$error['meter_register_id']] : array();
          $error_devices[$error['meter_register_id']]['room_name'] = isset($room_data[$error['meter_register_id']]) ? $room_data[$error['meter_register_id']]['room_name'] :  '';

          $error_devices[$error['meter_register_id']]['ip_address'] = isset($error['ip_address']) ? $error['ip_address'] :  '';
           $temp = array();
           $temp = $error;
           array_push($error_devices[$error['meter_register_id']],$temp);
        }

        $cols = ['No.','IP Address','Room Name','Time Fail To Connect'];
        $r_cols = ['index','ip_address','room_name','error_count'];

         echo '<table border="1">';
         echo '<thead>';
         foreach($cols as $col)
         {
            echo '<th>'.$col.'</th>';
         }  
         
        echo '</thead>';

        $index = 1;
        foreach($error_devices as $e)
        {
           //dd($e);
           $error_count = count($e)-1;
           $room_name = isset($e['room_name']) ? $e['room_name'] : '';
           $ip_address = isset($e['ip_address']) ? $e['ip_address'] : '';
           
           if($error_count >= 3)
           {
                 echo '<tr>';
                 foreach($r_cols as $r_col)
                 {
                    echo '<td>'.$$r_col.'</td>';
                 }
                 echo '</tr>';
                 //echo $e['room_name'].' Time fail to get reading :'.($error_count-1).'<br>';
                  $index ++;
           }
        }
        echo '</table>';

});


Route::get('CheckErrorPayment', function (){
    
 
    $l = new LeafAPI();
    $setting = new Setting();

    $leaf_group_id =  282 ;
    $date = ['start' => '01-01-2022' , 'end' => '19-08-2022'];
    $u_all = Utransaction::whereBetween('created_at', [$setting->setDate($date['start']), $setting->setDate($date['end'])])
                         ->where('leaf_group_id', '=',$leaf_group_id)
                         ->get();
    $cols = ['No.','Account','Payment Date','Transaction ID','Reference No','Description','Amount'];
    $r_cols = ['payment_account_holder_name','payment_paid_date','payment_identifier','payment_reference','payment_item_name','payment_total_amount'];

     echo '<table border="1">';
     echo '<thead>';
     foreach($cols as $col)
     {
        echo '<th>'.$col.'</th>';
     }  
     
    echo '</thead>';
    $index = 1;

    foreach($u_all as $u)
    {
       $r = $l->get_check_payment($u['leaf_payment_id']);

       if($r['payment_paid'] == true)
       {
         echo '<tr> <tr>'.$index.'</tr>';
         foreach($r_cols as $r_col)
         {
            echo '<td>'.$r[$col].'</td>';
         }  
         echo '</tr>';
       }
       echo '</table>';
    }

});



Route::get('newTest', function (){
    

    $amount=  10 ;
    $usage = 215;

    echo Company::get_group_id().'<br>';
    $r = Setting::calculate_utility_fee($usage);
    dd($r);


    Setting::convert_balance_to_kwh_by_current_usage_and_balance($usage,$amount);


    $l = UserAssign::groupCombobox();
    dd($l);
      $leaf_group_id = 282;
      echo 'Start';
      $leaf_api = StartUp::saveOrUpdateHouseRoom($leaf_group_id);

});

Route::get('testCurrency', function (){
  
      $kwh = 31.265000000000015;
      $amount =  443.59 ;

      $leaf_api = Setting::convert_balance_to_kwh_by_current_usage_and_balance($kwh , $amount);

});

Route::get('paymentCheck', function (){
  
      $leaf_api = new LeafAPI();
      //$paymentId = '7cce14e121b18a0430cfb9fdca1edc4d';
      //$paymentId = '96fb4b3f9ae559d17e3466d667cebe96';
       //$paymentId = '0511d27928423172563077df630bb8eb';
       $paymentId = '1dc12846cfddf852d6c89238a0ceec20';
      
      $result = $leaf_api->get_check_payment($paymentId);
      dd($result);
});


Route::get('filterColumns', function (){

  dd(date('Y-m-d', strtotime('now')) <= date('Y-m-d', strtotime('2021-06-28')));
    $excluded_keys = ['overview','table','package','fields','name','updated by','type','label','relationship','mapped by','integer','localdatetime','string','boolean','user','long','attributes','import id','created on','unit type','created by','unit','manytoone','label to printing' , 'updated on'];
    $oriString = 'Overview
Name
Table
Package
Fields
Name
Type
Label
Relationship
Mapped by
updatedBy
User
Updated by
ManyToOne
labelToPrinting
String
Label To Printing
updatedOn
LocalDateTime
Updated on
unitTypeSelect
Integer
Unit type
createdOn
LocalDateTime
Created on
version
Integer
attrs
String
Attributes
archived
Boolean
importId
String
Import ID
createdBy
User
Created by
ManyToOne
name
String
Unit
id
Long';
    $temp =preg_split('/\n|\r\n?/', $oriString);
    //dd($temp);
    $index = 1;
    foreach ($temp as $key)
    {
      if(in_array(strtolower($key), $excluded_keys)){
        continue;
      }
      echo 'key '.$index.' : '. trim($key)."<br>";
      $index++;
    }
      echo "<br><br><br><br>";
    foreach ($temp as $key)
    {
      if(in_array(strtolower($key), $excluded_keys)){
        continue;
      }
      echo trim($key)."<br>";
    }


    dd('End');
});

Route::get('power_meter_payment_checker', function (){

   $leaf_group_id = 519;
   $leaf_api = new LeafAPI();
   $company = Company::get_model_by_leaf_group_id($leaf_group_id);
   $un_u_model_listing = array();
   $listing = UTransaction::where('is_double_check' , '=' , false)
              ->get();

   foreach ($listing as $u_model)
   {
      if($u_model['is_paid'] == true)
      {
          $payment_model = MeterPaymentReceived::where('utransaction_id' , '=' , $u_model['id'])->first();
          if(!isset($payment_model['id']))
          {
              array_push($un_u_model_listing , $u_model);
          }
      }else{


         $paymentid = $u_model['leaf_payment_id'];
         $result = $leaf_api->get_check_payment($paymentid);

          //paid case record as fail 
          if (isset($result['payment_paid']) && $result['payment_paid']) 
          {
               $u_model['is_paid'] = true;
               $u_model->save(); 
               array_push($un_u_model_listing , $u_model);
          }


      }
   }



dd($un_u_model_listing);
   
   foreach ($un_u_model_listing as $u_model){

        $paymentid = $u_model['leaf_payment_id'];
        $result = $leaf_api->get_check_payment($paymentid);
        

        if (isset($result['payment_paid']) && $result['payment_paid']) {
            UTransaction::where('leaf_payment_id','=',$paymentid)->update(['is_paid'=>true]);
            $model = UTransaction::where('leaf_payment_id','=',$paymentid)->first();

             if(isset($model['id'])){
                //dd(Auth::user()->email);
                $backend_data = $company->backend_data ;
                MeterPaymentReceived::saveOrUpdateModelByUtransactionModel($model ,  CustomerPowerUsageSummary::find(Auth::user()->getCustomerPowerUsageSummaryId()));
                $power_meter_payment_success_email = json_decode($backend_data['power_meter_payment_success_email'] , true);
                $email_response = $leaf_api->send_email(Auth::user()->email, $power_meter_payment_success_email[$default_language]['title'], $power_meter_payment_success_email[$default_language]['content']);
                //dd($email_response);
                $model['is_checked'] = true;
                if($email_response['status_code'])
                {
                    $model['is_send_success_notification'] = true;
                }
                
                $model['is_double_check'] = true;
                $model->save();

             }
        }
    }


});


Route::get('extremeFinder1', function(){

      $order_info['order_status_id'] = 10;
      $excluded_order_status = [1,3,11,12,16,5,15,13,17,9];
      if(in_array($order_info['order_status_id'], $excluded_order_status)){ 
        dd('Out '); 
      }

      dd('Done');
     /* ini_set('max_execution_time', 3000);
      ini_set('memory_limit', '4096M'); */
      $leaf_group_id = 519;
      $meter_registers = array();
      $temp_meter_registers = MeterRegister::where('leaf_group_id', '=' , $leaf_group_id)->get();
      

      $rooms = array();
      $temp_rooms = Room::where('leaf_group_id', '=' , $leaf_group_id)->get();

      foreach ($temp_rooms as $mr)
      {
        $rooms[$mr['id_house_room']] = $mr;
      }

   
      foreach ($temp_meter_registers as $mr)
      {
        $meter_registers[$mr['id']]['meter'] = $mr;
        $meter_registers[$mr['id']]['room'] = isset($rooms[$mr['leaf_room_id']]) ? $rooms[$mr['leaf_room_id']] : false;
      }
      
      $setting = new Setting();

      $date_started = isset($_GET['date_started'])  ?  $_GET['date_started'] : '2021-05-23';
      $date_ended = isset($_GET['date_ended'])  ?  $_GET['date_ended'] : '2021-05-31';
      $usage_threshold = isset($_GET['usage_threshold'])  ?  $_GET['usage_threshold'] : 15;
      


      $date_range = ['date_started' => $date_started  , 'date_ended' => $date_ended  ];
      $issue_listing= array();
      $temp_listing = MeterReading::whereBetween('current_date', [$setting->setDate($date_range['date_started']), $setting->setDate($date_range['date_ended'])])
                    //->where('current_usage','=>',20)
                    ->orderBy('meter_register_id','ASC')
                    ->orderBy('current_date','ASC')
                    ->orderBy('created_at','ASC')
                    ->get();

      $meter_register_ids = MeterReading::select(DB::raw('DISTINCT meter_register_id'))
                    ->whereBetween('current_date', [$setting->setDate($date_range['date_started']), $setting->setDate($date_range['date_ended'])])
                     ->orderBy('meter_register_id','ASC')
                    //->where('current_usage','=>',20)
                    ->get();
      $leaf_api = new LeafAPI();
      $final_data = array();
      $houses_listing = array();
      $houses = $leaf_api->get_houses_with_meter_register_detail(null,true);
      foreach($houses as $house)
      {
          $houses_listing[$house['id_house']] = $house;
          //if(!isset($house['house_rooms']))
          foreach($house['house_rooms'] as $room)
          {
            $room_meter = $room['meter'];
            //dd($room_meter);
            if(isset($room_meter['id']))
            {
                $final_data[$room_meter['id']]['meter'] =  $room_meter;
            }

            foreach($room['house_room_members'] as $room_member)
            {
                if($room_member['house_room_member_deleted'] == 0)
                {
                   if(isset($room_meter['id']))
                   {
                      $final_data[$room_meter['id']]['member'] =  $room_member;
                   }
                }

            }
          }
      }     


        
    

      //dd($houses_listing);

      foreach ($meter_register_ids as $row_id) {
           $item_before ;
           $new_current_usage = 0 ; 
           $new_last_meter_reading = 0 ; 
           foreach ($temp_listing as $row) {
               // dd($row);
                //if($row['current_usage'] > 15)
                if($row['meter_register_id'] ==  $row_id['meter_register_id'])
                {

                  if($row['current_usage'] > $usage_threshold)
                  {
                    $temp_issue = array();
                    $temp_issue['before'] = $row;
                    //echo '-------------------------------  Spot issue '.$row['id'].'-------------------------------  <br>';
                    //echo 'Before :'.json_encode($item_before)."<br>";
                    //echo 'Issue Item :'.json_encode($row)."<br>";
                    
                    //echo 'Before '.$item_before['id'].' :'.$item_before['last_meter_reading'].' -> '.$item_before['current_meter_reading'].' = '.$item_before['current_usage']."<br>";
                    //echo 'Issue Item '.$row['id'].' :'.$row['last_meter_reading'].' -> '.$row['current_meter_reading'].' = '.$row['current_usage']."<br>";

                    $new_last_meter_reading = $item_before['current_meter_reading'];
                    $new_current_usage =  $row['current_meter_reading'] - $new_last_meter_reading  ;
                    //echo 'Repair data :'.$new_last_meter_reading.' -> '.$row['current_meter_reading'].' = '.$new_current_usage."<br>";
                    //echo '-------------------------------  Spot issue end -------------------------------  <br><br><br>';
                    $row['last_meter_reading'] = $new_last_meter_reading;
                    $row['current_usage'] = $new_current_usage;
                    //$row->save();

                   
                    $temp_issue['meter_details'] = $meter_registers[$row['meter_register_id']];
                    $temp_issue['after'] = $row;
                    
                    array_push($issue_listing,$temp_issue);
                   // dd($issue_listing);
                    unset($temp_issue);
 
                  }
                  $item_before = $row;

                   //echo 'Reading :'.$row['current_date'].'='.$row['time_started'].'='.$row['time_ended'].'='.$row['last_meter_reading'].'='.$row['current_meter_reading']."<br>";
                   //echo $i.':'.$row['id'].'='.$row['meter_register_id'].'='.$row['time_started'].'='.$row['last_meter_reading'].'='.$row['current_meter_reading'].'=>'.$row['current_usage']."<br>";
                   //$i++;
                }
             
            }


       }

       echo '<table border=1> <thead> <tr>';
       $header = ['#' , 'Room Name', 'Name' ,'Affected Time' , 'After Repair (kWh)' ,  'Usage Before Repair (kWh)' ,'Usage After Repair (kWh)' ];
       foreach ($header as $head){
            echo '<td>'.$head."</td>";
       }

       echo '</tr></thead> ';
       $index = 1;
       foreach ($issue_listing as $row)
       {
            $member = isset($final_data[$row['before']['meter_register_id']]['member']) ? $final_data[$row['before']['meter_register_id']]['member'] : false;
            $detail = $row['meter_details'];
            $house = isset($houses_listing[$detail['room']['house_id']]) ? $houses_listing[$detail['room']['house_id']] : false;
            echo '<tr>';
            echo '<td>'.$index.'</td>';
            echo '<td>'.$house['house_unit'].'-'.$detail['room']['house_room_name'].'</td>';
            if($member != false){
                echo '<td>'.$member['house_member_name'].'</td>';
            }else{
                echo '<td> - </td>';
            }
            
            echo '<td>'.$row['before']['created_at'].'</td>';
            echo '<td>'.$row['after']['current_meter_reading'].'</td>';
            echo '<td>'.$row['before']['current_usage'].'</td>';
            echo '<td>'.$row['after']['current_usage'].'</td>';
            
            echo '</tr>';
            $index++;
       }


       echo '</table> ';



 dd('Finish');
      $i = 1;
      foreach ($temp_listing as $row) {
         // dd($row);
          //if($row['current_usage'] > 15)
          if($row['meter_register_id'] == 1848)
          {
              echo 'Reading :'.$row['current_date'].'='.$row['time_started'].'='.$row['time_ended'].'='.$row['last_meter_reading'].'='.$row['current_meter_reading']."<br>";
             //echo $i.':'.$row['id'].'='.$row['meter_register_id'].'='.$row['time_started'].'='.$row['last_meter_reading'].'='.$row['current_meter_reading'].'=>'.$row['current_usage']."<br>";
             $i++;
          }
       
      }

      dd('Done');

});


Route::get('getHouseMeterDetail', function(){

      $id_house = isset($_GET['id_house']) ? $_GET['id_house'] : 64187;
      $r_list = Room::select('id_house_room')->where('id_house' , '=' , $id_house)->get()->toArray();

      $rids = '[';
      $index = 0;
      foreach ($r_list as $r)
      {
          if($index != 0 )
          {
            $rids .= ',';
          }
           

           $rids .= $r['id_house_room'];
           $index++;
      }

      $rids .= ']';
      //dd($rids);
      //dd(jsonncode($rids));
      //dd(array_column($r_list,'id_house_room'));
      //dd($r_list);
      //json_encode(array_column($r_list,'id_house_room'))
      $m_list = MeterRegister::whereIn('leaf_room_id', json_decode($rids))->get();

      dd($m_list);

}); 

Route::get('billRepair', function(){

      ini_set('max_execution_time', 300);
      ini_set('memory_limit', '4096M'); 

      $leaf_api = new LeafAPI();
      $setting = new Setting();
      $abnormal_units = array();
      $refined_abnormal_units = array();
      $date_started = '2021-03-01';
      $date_ended = '2021-04-30';
      $date_range = ['date_started' => $date_started  , 'date_ended' => $date_ended  ];
      $houses = $leaf_api->get_houses_with_meter_register_detail(null,true);
      //dd($rooms[0]['house_rooms']);
      $temp_listing = MeterReading::whereBetween('current_date', [$setting->setDate($date_range['date_started']), $setting->setDate($date_range['date_ended'])])->get();
   
      foreach($temp_listing as $row)
      {
          
          if($row['current_usage'] > 50)
          {
              array_push($abnormal_units , $row);
          }
      }
      unset($temp_listing);

      foreach($abnormal_units as $row)
      {
         //echo 'Id :'.$row['meter_register_id'].'='.$row['current_date'].' => '.$row['current_usage']."<br>";
      }

      foreach($abnormal_units as $row)
      {
        if(!isset($refined_abnormal_units[$row['meter_register_id']]))
        {
          $refined_abnormal_units[$row['meter_register_id']] = array();
        }
       // dd($row);
         array_push($refined_abnormal_units[$row['meter_register_id']] , $row);
        
      }

      $final_data = array();
      foreach($refined_abnormal_units as $meter_register_id => $data)
      {
        if(count($data) == 1)
        {
            $final_data[$meter_register_id]['reading_data'] = $data;
        }
      }

      foreach($houses as $house)
      {
          //if(!isset($house['house_rooms']))
          foreach($house['house_rooms'] as $room)
          {
            $room_meter = $room['meter'];
            //dd($room_meter);
            if(isset($room_meter['id']))
            {
                $final_data[$room_meter['id']]['meter'] =  $room_meter;
            }

            foreach($room['house_room_members'] as $room_member)
            {
                if($room_member['house_room_member_deleted'] == 0)
                {
                   if(isset($room_meter['id']))
                   {
                      $final_data[$room_meter['id']]['member'] =  $room_member;
                   }
                }

            }
          }
      }      


      $miss_reading_units = array();
      $adjustment_period = array();
      foreach ($final_data as $meter_register_id => $info) {
        if(isset($info['member'])){
//dd($info['reading_data'][0]['current_date']);

          if(!isset($info['reading_data'][0]['current_date']))
          {
             array_push($miss_reading_units,$info);
          }else{
            dd($info);
               $starting_date =  $info['member']['house_room_member_start_date'];
               $ending_date =  isset($info['reading_data'][0]['current_date']) ? $info['reading_data'][0]['current_date'] : 'Wrong';
               $adjustment_period[$meter_register_id]['date_range'] = ['date_started' => $date_started, 'date_ended' => $date_ended];
               $diff = abs(strtotime($date_ended) - strtotime($date_started) ); 
               $years = floor($diff / (365*60*60*24)); 
               $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24)); 
               $days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
               $adjustment_period[$meter_register_id]['adjusted_day'] =  $days;
               $adjustment_period[$meter_register_id]['total_usage'] =  $info['reading_data'][0]['current_usage'] ;
               $adjustment_period[$meter_register_id]['average_usage'] =  $info['reading_data'][0]['current_usage'] / $days;
          }
        }
      }



        
      foreach($adjustment_period as $row)
      {
        echo json_encode($row)."<br>";
      }

      dd($adjustment_period);
      
});

Route::get('updateHouse', function ()
{ 
    House::saveAllHouseRoom(519);
    dd('Done');

}); 

Route::get('getMeterCheck', function ()
{  

$part1 = ["'D-02-01', '172.16.32.66'  ,'iEM3155' ,1 ,150 ,'E207D2C9'" , "'','','',      2 ,153 ,'E207D4D2'" , "'','','',      3 ,151 ,'E207D4BD'" , "'','','',      4 ,155 ,'E207D4B9'" , "'','','',      5 ,154 ,'E207D2C7'" , "'','','',      6 ,152 ,'E207D4C2'" , "'D-02-02', '172.16.32.67'  ,'iEM3155' ,1 ,152 ,'E207DCCD'" , "'','','',      2 ,155 ,'E207DCD8'" , "'','','',      3 ,153 ,'E207DCCB'" , "'','','',      4 ,154 ,'E207DC9F'" , "'','','',      5 ,150 ,'E207DCA1'" , "'','','',      6 ,151 ,'E207DCC9'" , "'D-02-03', '172.16.32.68'  ,'iEM3155' ,1 ,155 ,'E207DCDE'" , "'','','',      2 ,153 ,'E207DCE1'" , "'','','',      3 ,154 ,'E207DCDF'" , "'','','',      4 ,151 ,'E207DCC2'" , "'','','',      5 ,150 ,'E207DCC5'" , "'','','',      6 ,152 ,'E207DCD0'" , "'D-02-04', '172.16.32.69'  ,'iEM3155' ,1 ,152 ,'E207DCD7'" , "'','','',      2 ,153 ,'E207DCCA'" , "'','','',      3 ,150 ,'E207DCE8'" , "'','','',      4 ,155 ,'E207DCDC'" , "'','','',      5 ,154 ,'E207DCDB'" , "'','','',      6 ,151 ,'E207DCE9'" , "'D-02-05', '172.16.32.70'  ,'iEM3155' ,1 ,152 ,'E207DCD2'" , "'','','',      2 ,153 ,'E207DCE4'" , "'','','',      3 ,150 ,'E207DCA7'" , "'','','',      4 ,155 ,'E207DCD9'" , "'','','',      5 ,151 ,'E207DCB4'" , "'','','',      6 ,154 ,'E207DCC0'" , "'D-02-06', '172.16.32.71'  ,'iEM3155' ,1 ,154 ,'E207DCCC'" , "'','','',      2 ,155 ,'E207DCA6'" , "'','','',      3 ,150 ,'E207DCD1'" , "'','','',      4 ,153 ,'E207DCD5'" , "'','','',      5 ,151 ,'E207DCC6'" , "'','','',      6 ,152 ,'E207DCC4'" , "'D-02-07', '172.16.32.72'  ,'iEM3155' ,1 ,150 ,'E207DCE3'" , "'','','',      2 ,152 ,'E207DCCF'" , "'','','',      3 ,153 ,'E207DCDD'" , "'','','',      4 ,151 ,'E207DCE5'" , "'','','',      5 ,155 ,'E207DCE6'" , "'','','',      6 ,154 ,'E207DCE0'" , "'D-02-08', '172.16.32.73'  ,'iEM3155' ,1 ,152 ,'E207DCC1'" , "'','','',      2 ,155 ,'E207DCAE'" , "'','','',      3 ,154 ,'E207DCCE'" , "'','','',      4 ,150 ,'E207DCBF'" , "'','','',      5 ,153 ,'E207DCBF'" , "'','','',      6 ,151 ,'E207DCD3'" , "'D-02-09', '172.16.32.74'  ,'iEM3155' ,1 ,151 ,'E207557B'" , "'','','',      2 ,150 ,'E207460D'" , "'','','',      3 ,154 ,'E20758C7'" , "'','','',      4 ,153 ,'E20758BA'" , "'','','',      5 ,152 ,'E20758BA'" , "'D-02-10', '172.16.32.75'  ,'iEM3155' ,1 ,155 ,'E207DCC7'" , "'','','',      2 ,154 ,'E207DCE7'" , "'','','',      3 ,153 ,'E207DCBB'" , "'','','',      4 ,152 ,'E207DCAF'" , "'','','',      5 ,151 ,'E207DCEA'" , "'','','',      6 ,150 ,'E207DCE2'" , "'D-03-01', '172.16.32.46'  ,'iEM3155' ,1 ,154 ,'E207D4C7'" , "'','','',      2 ,150 ,'E207D2B4'" , "'','','',      3 ,152 ,'E207D4CE'" , "'','','',      4 ,151 ,'E207D2CD'" , "'','','',      5 ,155 ,'E207D4C5'" , "'','','',      6 ,153 ,'E207D4D1'" , "'D-03-02', '172.16.32.47'  ,'iEM3155' ,1 ,154 ,'E207D4C3'" , "'','','',      2 ,152 ,'E207D4D0'" , "'','','',      3 ,155 ,'E207D4D3'" , "'','','',      4 ,150 ,'E207D4BF'" , "'','','',      5 ,151 ,'E207D2BA'" , "'','','',      6 ,153 ,'E207D4C9'" , "'D-03-03', '172.16.32.48'  ,'iEM3155' ,1 ,153 ,'E207D4CF'" , "'','','',      2 ,150 ,'E207D4C8'" , "'','','',      3 ,155 ,'E207D4C4'" , "'','','',      4 ,154 ,'E207D4D5'" , "'','','',      5 ,151 ,'E207D2CB'" , "'','','',      6 ,152 ,'E207D4CD'" , "'D-03-04', '172.16.32.49'  ,'iEM3155' ,1 ,150 ,'E207D23D'" , "'','','',      2 ,152 ,'E207D235'" , "'','','',      3 ,153 ,'E207D271'" , "'','','',      4 ,151 ,'E207D242'" , "'','','',      5 ,155 ,'E207D276'" , "'','','',      6 ,154 ,'E207D274'" , "'D-03-05', '172.16.32.50'  ,'iEM3155' ,1 ,152 ,'E207D2B7'" , "'','','',      2 ,151 ,'E207D241'" , "'','','',      3 ,153 ,'E207D256'" , "'','','',      4 ,155 ,'E207D2C2'" , "'','','',      5 ,150 ,'E207D2C2'" , "'','','',      6 ,154 ,'E207D249'" , "'D-03-06', '172.16.32.51'  ,'iEM3155' ,1 ,152 ,'E207D2B3'" , "'','','',      2 ,151 ,'E207D2B6'" , "'','','',      3 ,150 ,'E207D25C'" , "'','','',      4 ,153 ,'E207D25F'" , "'','','',      5 ,154 ,'E207D25E'" , "'','','',      6 ,155 ,'E207D2B2'" , "'D-03-07', '172.16.32.52'  ,'iEM3155' ,1 ,154 ,'E207D2C5'" , "'','','',      2 ,153 ,'E207D2B0'" , "'','','',      3 ,155 ,'E207D2C6'" , "'','','',      4 ,150 ,'E207D4CA'" , "'','','',      5 ,151 ,'E207D2B8'" , "'','','',      6 ,152 ,'E207D2AF'" , "'D-03-08', '172.16.32.53'  ,'iEM3155' ,1 ,151 ,'E207D4CC'" , "'','','',      2 ,152 ,'E207D4D4'" , "'','','',      3 ,154 ,'E207D4C1'" , "'','','',      4 ,150 ,'E207D4CB'" , "'','','',      5 ,155 ,'E207D4C6'" , "'','','',      6 ,153 ,'E207D4C6'" , "'D-03-09', '172.16.32.54'  ,'iEM3155' ,1 ,154 ,'E20758A5'" , "'','','',      2 ,150 ,'E20758A8'" , "'','','',      3 ,152 ,'E2074608'" , "'','','',      4 ,151 ,'E2075580'" , "'','','',      5 ,153 ,'E20758B7'" , "'D-03-10', '172.16.32.55'  ,'iEM3155' ,1 ,150 ,'E207D2B5'" , "'','','',      2 ,152 ,'E207D2BD'" , "'','','',      3 ,154 ,'E207D2C3'" , "'','','',      4 ,151 ,'E207D2C3'" , "'','','',      5 ,153 ,'E207D2C3'" , "'','','',      6 ,155 ,'E207D2C1'" , "'D-04-01', '172.16.32.36'  ,'iEM3155' ,1 ,154 ,'E207D5A2'" , "'','','',      2 ,151 ,'E207D5A3'" , "'','','',      3 ,153 ,'E207D5AE'" , "'','','',      4 ,152 ,'E207D596'" , "'','','',      5 ,155 ,'E207D59B'" , "'','','',      6 ,150 ,'E207D5A1'" , "'D-04-02', '172.16.32.37'  ,'iEM3155' ,1 ,150 ,'E207D5A8'" , "'','','',      2 ,155 ,'E207D59C'" , "'','','',      3 ,151 ,'E207D5AA'" , "'','','',      4 ,154 ,'E207D5AD'" , "'','','',      5 ,152 ,'E207D591'" , "'','','',      6 ,153 ,'E207D581'" , "'D-04-03', '172.16.32.38'  ,'iEM3155' ,1 ,150 ,'E207D268'" , "'','','',      2 ,153 ,'E207D26E'" , "'','','',      3 ,151 ,'E207D25A'" , "'','','',      4 ,152 ,'E207D236'" , "'','','',      5 ,155 ,'E207D236'" , "'','','',      6 ,154 ,'E207D24A'" , "'D-04-04', '172.16.32.39'  ,'iEM3155' ,1 ,155 ,'E207D25D'" , "'','','',      2 ,150 ,'E207D278'" , "'','','',      3 ,154 ,'E207D263'" , "'','','',      4 ,153 ,'E207D260'" , "'','','',      5 ,152 ,'E207D257'" , "'','','',      6 ,151 ,'E207D243'" , "'D-04-05', '172.16.32.40'  ,'iEM3155' ,1 ,154 ,'E207D24D'" , "'','','',      2 ,150 ,'E207D5AC'" , "'','','',      3 ,155 ,'E207D5B1'" , "'','','',      4 ,153 ,'E207D5B1'" , "'','','',      5 ,151 ,'E207D57C'" , "'','','',      6 ,152 ,'E207D5A9'" , "'D-04-06', '172.16.32.41'  ,'iEM3155' ,1 ,155 ,'E207D59F'" , "'','','',      2 ,154 ,'E207D59E'" , "'','','',      3 ,151 ,'E207D593'" , "'','','',      4 ,153 ,'E207D5AB'" , "'','','',      5 ,150 ,'E207D5A0'" , "'','','',      6 ,152 ,'E207D597'" , "'D-04-07', '172.16.32.42'  ,'iEM3155' ,1 ,153 ,'E207D589'" , "'','','',      2 ,151 ,'E207D58F'" , "'','','',      3 ,154 ,'E207D5B0'" , "'','','',      4 ,155 ,'E207D58B'" , "'','','',      5 ,150 ,'E207D58B'" , "'','','',      6 ,152 ,'E207D5A6'" , "'D-04-08', '172.16.32.43'  ,'iEM3155' ,1 ,150 ,'E207D253'" , "'','','',      2 ,153 ,'E207D262'" , "'','','',      3 ,155 ,'E207D261'" , "'','','',      4 ,152 ,'E207D255'" , "'','','',      5 ,151 ,'E207D252'" , "'','','',      6 ,154 ,'E207D24F'" , "'D-04-09', '172.16.32.44'  ,'iEM3155' ,1 ,151 ,'E207D195'" , "'','','',      2 ,152 ,'E207D186'" , "'','','',      3 ,154 ,'E207DBF6'" , "'','','',      4 ,150 ,'E20758CD'" , "'','','',      5 ,155 ,'E207DCA0'" , "'D-04-10', '172.16.32.45'  ,'iEM3155' ,1 ,153 ,'E207D244'" , "'','','',      2 ,152 ,'E207D258'" , "'','','',      3 ,154 ,'E207D25B'" , "'','','',      4 ,150 ,'E207D251'" , "'','','',      5 ,155 ,'E207D245'" , "'','','',      6 ,151 ,'E207D259'" , "'D-05-01', '172.16.32.26'  ,'iEM3155' ,1 ,150 ,'E207D592'" , "'','','',      2 ,152 ,'E207D265'" , "'','','',      3 ,154 ,'E207D277'" , "'','','',      4 ,153 ,'E207D594'" , "'','','',      5 ,151 ,'E207D267'" , "'','','',      6 ,155 ,'E207D272'" , "'D-05-02', '172.16.32.27'  ,'iEM3155' ,1 ,155 ,'E207D586'" , "'','','',      2 ,151 ,'E207D58D'" , "'','','',      3 ,152 ,'E207D57D'" , "'','','',      4 ,153 ,'E207D580'" , "'','','',      5 ,154 ,'E207D57F'" , "'','','',      6 ,150 ,'E207D1F7'" , "'D-05-03', '172.16.32.28'  ,'iEM3155' ,1 ,152 ,'E207D1F9'" , "'','','',      2 ,151 ,'E207D205'" , "'','','',      3 ,155 ,'E207D210'" , "'','','',      4 ,150 ,'E207D1DF'" , "'','','',      5 ,153 ,'E207D206'" , "'','','',      6 ,154 ,'E207D1FA'" , "'D-05-04', '172.16.32.29'  ,'iEM3155' ,1 ,151 ,'E207D1FA'" , "'','','',      2 ,150 ,'E207D1FA'" , "'','','',      3 ,152 ,'E207D595'" , "'','','',      4 ,154 ,'E207D595'" , "'','','',      5 ,153 ,'E207D587'" , "'','','',      6 ,155 ,'E207D585'" , "'D-05-05', '172.16.32.30'  ,'iEM3155' ,1 ,152 ,'E207D57E'" , "'','','',      2 ,155 ,'E207D58A'" , "'','','',      3 ,150 ,'E207D5A5'" , "'','','',      4 ,154 ,'E207D588'" , "'','','',      5 ,151 ,'E207D590'" , "'','','',      6 ,153 ,'E207D599'" , "'D-05-06', '172.16.32.31'  ,'iEM3155' ,1 ,151 ,'E207D1F2'" , "'','','',      2 ,153 ,'E207D207'" , "'','','',      3 ,150 ,'E207D1F6'" , "'','','',      4 ,155 ,'E207D20D'" , "'','','',      5 ,154 ,'E207D1FE'" , "'','','',      6 ,152 ,'E207D20C'" , "'D-05-07', '172.16.32.32'  ,'iEM3155' ,1 ,154 ,'E207D1E7'" , "'','','',      2 ,152 ,'E207D1E6'" , "'','','',      3 ,155 ,'E207D1DB'" , "'','','',      4 ,150 ,'E207D1E1'" , "'','','',      5 ,151 ,'E207D1E4'" , "'','','',      6 ,153 ,'E207D1E2'" , "'D-05-08', '172.16.32.33'  ,'iEM3155' ,1 ,150 ,'E207D1E2'" , "'','','',      2 ,154 ,'E207D1F3'" , "'','','',      3 ,152 ,'E207D208'" , "'','','',      4 ,155 ,'E207D1FF'" , "'','','',      5 ,153 ,'E207D1E0'" , "'','','',      6 ,151 ,'E207D203'" , "'D-05-09', '172.16.32.34'  ,'iEM3155' ,1 ,150 ,'E20758C1'" , "'','','',      2 ,153 ,'E2075563'" , "'','','',      3 ,154 ,'E207462B'" , "'','','',      4 ,152 ,'E20758C8'" , "'','','',      5 ,151 ,'E20745FF'" , "'D-05-10', '172.16.32.35'  ,'iEM3155' ,1 ,153 ,'E207D1F4'" , "'','','',      2 ,150 ,'E207D209'" , "'','','',      3 ,151 ,'E207D201'" , "'','','',      4 ,154 ,'E207D1DC'" , "'','','',      5 ,155 ,'E207D20E'" , "'','','',      6 ,152 ,'E207D1F1'" , "'D-06-01', '172.16.32.16'  ,'iEM3155' ,1 ,151 ,'E207D192'" , "'','','',      2 ,154 ,'E207D19D'" , "'','','',      3 ,153 ,'E207D157'" , "'','','',      4 ,152 ,'E207D17C'" , "'','','',      5 ,150 ,'E207D11E'" , "'','','',      6 ,155 ,'E207D175'" , "'D-06-02', '172.16.32.17'  ,'iEM3155' ,1 ,152 ,'E207D1DA'" , "'','','',      2 ,154 ,'E207D1ED'" , "'','','',      3 ,150 ,'E207D1F0'" , "'','','',      4 ,153 ,'E207D1EB'" , "'','','',      5 ,155 ,'E207D1EF'" , "'','','',      6 ,151 ,'E207D1E3'" , "'D-06-03', '172.16.32.18'  ,'iEM3155' ,1 ,153 ,'E207D16F'" , "'','','',      2 ,155 ,'E207D173'" , "'','','',      3 ,152 ,'E207DC67'" , "'','','',      4 ,154 ,'E207D19A'" , "'','','',      5 ,150 ,'E207D19A'" , "'','','',      6 ,151 ,'E207D18C'" , "'D-06-04', '172.16.32.19'  ,'iEM3155' ,1 ,152 ,'E207D189'" , "'','','',      2 ,150 ,'E207D170'" , "'','','',      3 ,151 ,'E207D195'" , "'','','',      4 ,153 ,'E207D17D'" , "'','','',      5 ,155 ,'E207D197'" , "'','','',      6 ,154 ,'E207D194'" , "'D-06-05', '172.16.32.20'  ,'iEM3155' ,1 ,150 ,'E207D1A1'" , "'','','',      2 ,152 ,'E207D15D'" , "'','','',      3 ,154 ,'E207D186'" , "'','','',      4 ,155 ,'E207D126'" , "'','','',      5 ,151 ,'E207D1A0'" , "'','','',      6 ,153 ,'E207D174'" , "'D-06-06', '172.16.32.21'  ,'iEM3155' ,1 ,151 ,'E207DC85'" , "'','','',      2 ,150 ,'E207DC80'" , "'','','',      3 ,155 ,'E207DC89'" , "'','','',      4 ,152 ,'E207DC80'" , "'','','',      5 ,153 ,'E207DC8A'" , "'','','',      6 ,154 ,'E207DC77'" , "'D-06-07', '172.16.32.22'  ,'iEM3155' ,1 ,154 ,'E207D199'" , "'','','',      2 ,153 ,'E207D196'" , "'','','',      3 ,151 ,'E207D193'" , "'','','',      4 ,150 ,'E207DC77'" , "'','','',      5 ,152 ,'E207D171'" , "'','','',      6 ,155 ,'E207D176'" , "'D-06-08', '172.16.32.23'  ,'iEM3155' ,1 ,154 ,'E207D1E8'" , "'','','',      2 ,150 ,'E207D1DD'" , "'','','',      3 ,155 ,'E207D1EA'" , "'','','',      4 ,151 ,'E207D204'" , "'','','',      5 ,152 ,'E207D1E9'" , "'','','',      6 ,153 ,'E207D1EC'" , "'D-06-09', '172.16.32.24'  ,'iEM3155' ,1 ,151 ,'E20758C0'" , "'','','',      2 ,150 ,'E20758AD'" , "'','','',      3 ,153 ,'E20744FF'" , "'','','',      4 ,152 ,'E20758CE'" , "'','','',      5 ,154 ,'E2074609'" , "'D-06-10', '172.16.32.25'  ,'iEM3155' ,1 ,151 ,'E207DC6F'" , "'','','',      2 ,154 ,'E207DC73'" , "'','','',      3 ,150 ,'E207DC6D'" , "'','','',      4 ,153 ,'E207DC72'" , "'','','',      5 ,152 ,'E207DC71'" , "'','','',      6 ,155 ,'E207DC75'" , "'D-07-01', '172.16.32.12'  ,'iEM3155' ,1 ,152 ,'E207DC7C'" , "'','','',      2 ,153 ,'E207DC86'" , "'','','',      3 ,154 ,'E207DC7A'" , "'','','',      4 ,155 ,'E207DC7B'" , "'','','',      5 ,150 ,'E207DC6C'" , "'','','',      6 ,151 ,'E207DC74'" , "'D-07-02', '172.16.32.11'  ,'iEM3155' ,1 ,154 ,'E207DC61'" , "'','','',      2 ,155 ,'E207DC59'" , "'','','',      3 ,153 ,'E207DC6'" , "'','','',      4 ,150 ,'E207DC64'" , "'','','',      5 ,152 ,'E207DC69'" , "'','','',      6 ,151 ,'E207DC5B'" , "'D-07-03', '172.16.32.10'  ,'iEM3155' ,1 ,155 ,'E207DC30'" , "'','','',      2 ,152 ,'E207D1DE'" , "'','','',      3 ,150 ,'E207DC32'" , "'','','',      4 ,151 ,'E207DC5E'" , "'','','',      5 ,154 ,'E207DC62'" , "'','','',      6 ,153 ,'E207DC5A'" , "'D-07-04','172.16.32.9' ,'iEM3155' ,1 ,154 ,'E207D198'" , "'','','',      2 ,152 ,'E207D18D'" , "'','','',      3 ,150 ,'E207D191'" , "'','','',      4 ,153 ,'E207D18B'" , "'','','',      5 ,155 ,'E207D17E'" , "'','','',      6 ,151 ,'E207D177'" , "'D-07-05' ,'172.16.32.8' ,'iEM3155' ,1 ,154 ,'E207DC5D'" , "'','','',      2 ,151 ,'E207DC31'" , "'','','',      3 ,152 ,'E207DC2B'" , "'','','',      4 ,153 ,'E207DC60'" , "'','','',      5 ,155 ,'E207DC5C'" , "'','','',      6 ,150 ,'E207DC5C'" , "'D-07-06','172.16.32.7' ,'iEM3155' ,1 ,150 ,'E207D18A'" , "'','','',      2 ,154 ,'E207D179'" , "'','','',      3 ,151 ,'E207D16E'" , "'','','',      4 ,155 ,'E207D19C'" , "'','','',      5 ,152 ,'E207D179'" , "'','','',      6 ,153 ,'E207D178'" , "'D-07-07' , '172.16.32.6' ,'iEM3155' ,1 ,152 ,'E207D18E'" , "'','','',      2 ,150 ,'E207D18E'" , "'','','',      3 ,155 ,'E207DBEF'" , "'','','',      4 ,153 ,'E207DBEC'" , "'','','',      5 ,154 ,'E207DBF2'" , "'','','',      6 ,151 ,'E207DC1C'" , "'D-07-08', '172.16.32.13'  ,'iEM3155' ,1 ,154 ,'E207DC78'" , "'','','',      2 ,152 ,'E207D20A'" , "'','','',      3 ,151 ,'E207DC6B'" , "'','','',      4 ,155 ,'E207DC6E'" , "'','','',      5 ,153 ,'E207DC68'" , "'','','',      6 ,150 ,'E207DC7E'" , "'D-07-09', '172.16.32.14'  ,'iEM3155' ,1 ,151 ,'E2074612'" , "'','','',      2 ,152 ,'E207557A'" , "'','','',      3 ,154 ,'E207555B'" , "'','','',      4 ,153 ,'E207556E'" , "'','','',      5 ,150 ,'E2075575'" , "'D-07-10', '172.16.32.15'  ,'iEM3155' ,1 ,150 ,'E207D162'" , "'','','',      2 ,152 ,'E207D19F'" , "'','','',      3 ,153 ,'E207D18F'" , "'','','',      4 ,154 ,'E207D18F'" , "'','','',      5 ,151 ,'E207D151'" , "'','','',      6 ,155 ,'E207D188'"];




 $part2 = ["'D-08-01' ,'172.16.32.56'  ,'iEM3155' ,1 ,152 ,'E207D5FA'", "'','','',      2 ,153 ,'E207D5EF'", "'','','',      3 ,151 ,'E207D5F5'", "'','','',      4 ,155 ,'E207D5FC'", "'','','',      5 ,150 ,'E207D5F2'", "'','','',      6 ,154 ,'E207D5E7'", "'D-08-02' ,'172.16.32.57'  ,'iEM3155' ,1 ,152 ,'E207DBF6'", "'','','',      2 ,151 ,'E207DC07'", "'','','',      3 ,155 ,'E207D604'", "'','','',      4 ,150 ,'E207DC03'", "'','','',      5 ,153 ,'E207D5FF'", "'','','',      6 ,154 ,'E207D5FE'", "'D-08-03' ,'172.16.32.58'  ,'iEM3155' ,1 ,151 ,'E207D5FE'", "'','','',      2 ,152 ,'E207D619'", "'','','',      3 ,150 ,'E207D614'", "'','','',      4 ,154 ,'E207D60A'", "'','','',      5 ,153 ,'E207D618'", "'','','',      6 ,150 ,'E207D60D'", "'D-08-04' ,'172.16.32.59'  ,'iEM3155' ,1 ,150 ,'E207DBF1'", "'','','',      2 ,155 ,'E207DC11'", "'','','',      3 ,152 ,'E207DBED'", "'','','',      4 ,154 ,'E207DBE9'", "'','','',      5 ,153 ,'E207DBF7'", "'','','',      6 ,151 ,'E207DC17'", "'D-08-05' ,'172.16.32.60'  ,'iEM3155' ,1 ,150 ,'E207DC14'", "'','','',      2 ,155 ,'E207DC10'", "'','','',      3 ,152 ,'E207DC09'", "'','','',      4 ,154 ,'E207DBFA'", "'','','',      5 ,151 ,'E207DBF3'", "'','','',      6 ,153 ,'E207DC1D'", "'D-08-06' ,'172.16.32.61'  ,'iEM3155' ,1 ,155 ,'E207DC12'", "'','','',      2 ,154 ,'E207DBFD'", "'','','',      3 ,152 ,'E207DC1F'", "'','','',      4 ,151 ,'E207DC05'", "'','','',      5 ,150 ,'E207DC15'", "'','','',      6 ,153 ,'E207DC15'", "'D-08-07' ,'172.16.32.62'  ,'iEM3155' ,1 ,155 ,'E207DBFC'", "'','','',      2 ,154 ,'E207DBF9'", "'','','',      3 ,151 ,'E207DC00'", "'','','',      4 ,152 ,'E207DC04'", "'','','',      5 ,150 ,'E207DC01'", "'','','',      6 ,153 ,'E207DC1A'", "'D-08-08' ,'172.16.32.63'  ,'iEM3155' ,1 ,151 ,'E207DC1A'", "'','','',      2 ,150 ,'E207DC19'", "'','','',      3 ,154 ,'E207DC0E'", "'','','',      4 ,153 ,'E207DC0F'", "'','','',      5 ,155 ,'E207DBFE'", "'','','',      6 ,152 ,'E207DC0A'", "'D-08-09' ,'172.16.32.64'  ,'iEM3155' ,1 ,153 ,'E2075562'", "'','','',      2 ,150 ,'E2075579'", "'','','',      3 ,152 ,'E20758C6'", "'','','',      4 ,151 ,'E20758CB'", "'','','',      5 ,154 ,'E2075566'", "'D-08-10' ,'172.16.32.65'  ,'iEM3155' ,1 ,154 ,'E207DC1E'", "'','','',      2 ,152 ,'E207DC08'", "'','','',      3 ,155 ,'E207DC08'", "'','','',      4 ,153 ,'E207DBEB'", "'','','',      5 ,151 ,'E207DC02'", "'','','',      6 ,150 ,'E207DC16'", "'D-09-01' ,'172.16.32.151' ,'iEM3155' ,1 ,151 ,'E207D5EE'", "'','','',      2 ,153 ,'E207D5E9'", "'','','',      3 ,150 ,'E207D5E8'", "'','','',      4 ,152 ,'E207D5F3'", "'','','',      5 ,154 ,'E207D5FD'", "'','','',      6 ,155 ,'E207D5FB'", "'D-09-02' ,'172.16.32.152' ,'iEM3155' ,1 ,151 ,'E207D60F'", "'','','',      2 ,150 ,'E207D5EB'", "'','','',      3 ,152 ,'E207D5F1'", "'','','',      4 ,155 ,'E207D612'", "'','','',      5 ,154 ,'E207D610'", "'','','',      6 ,153 ,'E207D609'", "'D-09-03' ,'172.16.32.153' ,'iEM3155' ,1 ,150 ,'E207DDB2'", "'','','',      2 ,152 ,'E207DDB6'", "'','','',      3 ,154 ,'E207DDC1'", "'','','',      4 ,153 ,'E207DDDD'", "'','','',      5 ,155 ,'E207DDAD'", "'','','',      6 ,151 ,'E207DDBE'", "'D-09-04' ,'172.16.32.154' ,'iEM3155' ,1 ,153 ,'E207D611'", "'','','',      2 ,155 ,'E207D5F4'", "'','','',      3 ,154 ,'E207D601'", "'','','',      4 ,151 ,'E207D605'", "'','','',      5 ,152 ,'E207D5ED'", "'','','',      6 ,150 ,'E207D5F9'", "'D-09-05' ,'172.16.32.155' ,'iEM3155' ,1 ,153 ,'E207D607'", "'','','',      2 ,150 ,'E207D608'", "'','','',      3 ,151 ,'E207D606'", "'','','',      4 ,154 ,'E207D602'", "'','','',      5 ,155 ,'E207D5EC'", "'','','',      6 ,152 ,'E207D617'", "'D-09-06' ,'172.16.32.156' ,'iEM3155' ,1 ,159 ,'E207DDD4'", "'','','',      2 ,156 ,'E207D5F6'", "'','','',      3 ,157 ,'E207DDDC'", "'','','',      4 ,158 ,'E207DDCE'", "'','','',      5 ,155 ,'E207DDCF'", "'','','',      6 ,150 ,'E207DDC5'", "'D-09-07' ,'172.16.32.157' ,'iEM3155' ,1 ,153 ,'E207D29A'", "'','','',      2 ,150 ,'E207DD07'", "'','','',      3 ,151 ,'E207D285'", "'','','',      4 ,153 ,'E20744FA'", "'','','',      5 ,152 ,'E207556D'", "'','','',      6 ,154 ,'E207D5D4'", "'D-09-08' ,'172.16.32.158' ,'iEM3155' ,1 ,152 ,'E207DC06'", "'','','',      2 ,154 ,'E207DBFB'", "'','','',      3 ,155 ,'E207D60B'", "'','','',      4 ,150 ,'E207DBFF'", "'','','',      5 ,151 ,'E207D615'", "'','','',      6 ,153 ,'E207DC13'", "'D-09-09' ,'172.16.32.159' ,'iEM3355' ,1 ,151 ,'E207556B'", "'','','',      2 ,150 ,'E2075569'", "'','','',      3 ,153 ,'E2075571'", "'','','',      4 ,154 ,'E2075574'", "'','','',      5 ,152 ,'E207556D'", "'D-09-10' ,'172.16.32.160' ,'iEM3155' ,1 ,153 ,'E207DDCB'", "'','','',      2 ,155 ,'E207DDD5'", "'','','',      3 ,152 ,'E207D0DB'", "'','','',      4 ,151 ,'E207DDD1'", "'','','',      5 ,150 ,'E207D0C9'", "'','','',      6 ,154 ,'E207D0C4'", "'D-10-01' ,'172.16.32.161' ,'iEM3155' ,1 ,154 ,'E207D61B'", "'','','',      2 ,153 ,'E207D613'", "'','','',      3 ,152 ,'E207D5F8'", "'','','',      4 ,155 ,'E207D5EA'", "'','','',      5 ,150 ,'E207D60C'", "'','','',      6 ,151 ,'E207D61A'", "'D-10-02' ,'172.16.32.162' ,'iEM3155' ,1 ,152 ,'E207D0B9'", "'','','',      2 ,150 ,'E207D0D8'", "'','','',      3 ,151 ,'E207D0D3'", "'','','',      4 ,153 ,'E207D0D9'", "'','','',      5 ,156 ,'E207D0BF'", "'','','',      6 ,155 ,'E207D0CB'", "'D-10-03' ,'172.16.32.163' ,'iEM3155' ,1 ,152 ,'E207DDD6'", "'','','',      2 ,150 ,'E207DDBA'", "'','','',      3 ,153 ,'E207DDB4'", "'','','',      4 ,151 ,'E207DDC2'", "'','','',      5 ,155 ,'E207DDAE'", "'','','',      6 ,154 ,'E207DDD0'", "'D-10-04' ,'172.16.32.164' ,'iEM3155' ,1 ,151 ,'E207DDB5'", "'','','',      2 ,152 ,'E207DD88'", "'','','',      3 ,153 ,'E207DDC9'", "'','','',      4 ,155 ,'E207DDAF'", "'','','',      5 ,154 ,'E207DDB3'", "'','','',      6 ,150 ,'E207DDDB'", "'D-10-05' ,'172.16.32.165' ,'iEM3155' ,1 ,150 ,'E207DDB7'", "'','','',      2 ,152 ,'E207DDDA'", "'','','',      3 ,153 ,'E207DDC4'", "'','','',      4 ,154 ,'E207DDBB'", "'','','',      5 ,151 ,'E207DDC6'", "'','','',      6 ,156 ,'E207DDD3'", "'D-10-06' ,'172.16.32.166' ,'iEM3155' ,1 ,152 ,'E207D0D7'", "'','','',      2 ,151 ,'E207D0D4'", "'','','',      3 ,155 ,'E207D0CF'", "'','','',      4 ,150 ,'E207D0D0'", "'','','',      5 ,154 ,'E207D0C7'", "'','','',      6 ,153 ,'E207D0CA'", "'D-10-07' ,'172.16.32.167' ,'iEM3155' ,1 ,155 ,'E207D0BD'", "'','','',      2 ,153 ,'E207D0BB'", "'','','',      3 ,150 ,'E207D0B0'", "'','','',      4 ,152 ,'E207D0D6'", "'','','',      5 ,156 ,'E207D0CD'", "'','','',      6 ,159 ,'E207D0BE'", "'D-10-08' ,'172.16.32.168' ,'iEM3155' ,1 ,152 ,'E207D0D5'", "'','','',      2 ,159 ,'E207DDB0'", "'','','',      3 ,156 ,'E207D0D1'", "'','','',      4 ,151 ,'E207DDD8'", "'','','',      5 ,155 ,'E207D0CC'", "'','','',      6 ,150 ,'E207DDD9'", "'D-10-09' ,'172.16.32.169' ,'iEM3155' ,1 ,152 ,'E20758B4'", "'','','',      2 ,150 ,'E20758C4'", "'','','',      3 ,154 ,'E20744DD'", "'','','',      4 ,151 ,'E20758BB'", "'','','',      5 ,153 ,'E20758CA'", "'D-10-10' ,'172.16.32.170' ,'iEM3155' ,1 ,153 ,'E207D0C6'", "'','','',      2 ,151 ,'E207D0D2'", "'','','',      3 ,155 ,'E207D0BC'", "'','','',      4 ,154 ,'E207D0B1'", "'','','',      5 ,150 ,'E207D0C8'", "'','','',      6 ,152 ,'E207D0CE'", "'D-11-01' ,'172.16.32.171' ,'iEM3155' ,1 ,158 ,'E207D0C2'", "'','','',      2 ,150 ,'E207D0DC'", "'','','',      3 ,160 ,'E207D0B3'", "'','','',      4 ,153 ,'E207D0B6'", "'','','',      5 ,159 ,'E207D0DA'", "'','','',      6 ,151 ,'E207D0BA'", "'D-11-02' ,'172.16.32.172' ,'iEM3155' ,1 ,153 ,'E207DC96'", "'','','',      2 ,155 ,'E207DC25'", "'','','',      3 ,154 ,'E207DCAB'", "'','','',      4 ,152 ,'E207DCA2'", "'','','',      5 ,151 ,'E207DCAC'", "'','','',      6 ,150 ,'E207DC9E'", "'D-11-03' ,'172.16.32.173' ,'iEM3155' ,1 ,151 ,'E207DCAA'", "'','','',      2 ,150 ,'E207DCB9'", "'','','',      3 ,154 ,'E207DCA8'", "'','','',      4 ,153 ,'E207DCA9'", "'','','',      5 ,155 ,'E207DCB8'", "'','','',      6 ,152 ,'E207DCB1'", "'D-11-04' ,'172.16.32.174' ,'iEM3155' ,1 ,155 ,'E207DDBC'", "'','','',      2 ,153 ,'E207DDCA'", "'','','',      3 ,156 ,'E207DDD2'", "'','','',      4 ,151 ,'E207DDC7'", "'','','',      5 ,150 ,'E207DDC0'", "'','','',      6 ,152 ,'E207DC99'", "'D-11-05' ,'172.16.32.175' ,'iEM3155' ,1 ,158 ,'E207DCBD'", "'','','',      2 ,157 ,'E207DCB7'", "'','','',      3 ,153 ,'E207DCBC'", "'','','',      4 ,150 ,'E207DCBA'", "'','','',      5 ,151 ,'E207DC95'", "'','','',      6 ,160 ,'E207DDB8'", "'D-11-06' ,'172.16.32.176' ,'iEM3155' ,1 ,150 ,'E207DCA3'", "'','','',      2 ,153 ,'E207DCB5'", "'','','',      3 ,157 ,'E207DCB0'", "'','','',      4 ,155 ,'E207DCB2'", "'','','',      5 ,151 ,'E207DCB3'", "'','','',      6 ,156 ,'E207DCAD'", "'D-11-07' ,'172.16.32.177' ,'iEM3155' ,1 ,153 ,'E207D0C5'", "'','','',      2 ,154 ,'E207D0B8'", "'','','',      3 ,151 ,'E207D0B4'", "'','','',      4 ,150 ,'E207D0AF'", "'','','',      5 ,155 ,'E207D0C3'", "'','','',      6 ,152 ,'E207D0C1'", "'D-11-08' ,'172.16.32.178' ,'iEM3155' ,1 ,151 ,'E207D0AD'", "'','','',      2 ,153 ,'E207D0B5'", "'','','',      3 ,152 ,'E207D0C0'", "'','','',      4 ,154 ,'E207D0B7'", "'','','',      5 ,155 ,'E207D0B2'", "'','','',      6 ,150 ,'E207D0AE'", "'D-11-09' ,'172.16.32.179' ,'iEM3155' ,1 ,150 ,'E20744F9'", "'','','',      2 ,151 ,'E20744DB'", "'','','',      3 ,152 ,'E20744E4'", "'','','',      4 ,154 ,'E20758B0'", "'','','',      5 ,153 ,'E20758CC'", "'D-11-10' ,'172.16.32.180' ,'iEM3155' ,1 ,154 ,'E207DC97'", "'','','',      2 ,155 ,'E207DC27'", "'','','',      3 ,151 ,'E207DC93'", "'','','',      4 ,152 ,'E207DC8F'", "'','','',      5 ,153 ,'E207DC2C'", "'','','',      6 ,150 ,'E207DC8C'", "'D-12-01' ,'172.16.32.181' ,'iEM3155' ,1 ,154 ,'E207DAFF'", "'','','',      2 ,153 ,'E207DBC0'", "'','','',      3 ,151 ,'E207DBDE'", "'','','',      4 ,155 ,'E207DB7C'", "'','','',      5 ,150 ,'E207DBF0'", "'','','',      6 ,152 ,'E207DBBA'", "'D-12-02' ,'172.16.32.182' ,'iEM3155' ,1 ,155 ,'E207DBE3'", "'','','',      2 ,159 ,'E207DBB6'", "'','','',      3 ,158 ,'E207DBE2'", "'','','',      4 ,157 ,'E207DBE6'", "'','','',      5 ,160 ,'E207DBE1'", "'','','',      6 ,161 ,'E207DBE5'", "'D-12-03' ,'172.16.32.183' ,'iEM3155' ,1 ,150 ,'E207DBE7'", "'','','',      2 ,151 ,'E207DBB7'", "'','','',      3 ,152 ,'E207DBC1'", "'','','',      4 ,153 ,'E207DBB4'", "'','','',      5 ,154 ,'E207DBB8'", "'','','',      6 ,155 ,'E207DBEE'", "'D-12-04' ,'172.16.32.184' ,'iEM3155' ,1 ,153 ,'E207DC63'", "'','','',      2 ,156 ,'E207DC90'", "'','','',      3 ,154 ,'E207DC8E'", "'','','',      4 ,151 ,'E207DC7D'", "'','','',      5 ,150 ,'E207DC92'", "'','','',      6 ,155 ,'E207DC94'", "'D-12-05' ,'172.16.32.185' ,'iEM3155' ,1 ,152 ,'E207DBCE'", "'','','',      2 ,155 ,'E207DBD1'", "'','','',      3 ,151 ,'E207DBF4'", "'','','',      4 ,150 ,'E207DBDF'", "'','','',      5 ,153 ,'E207DBD4'", "'','','',      6 ,154 ,'E207DBD6'", "'D-12-06' ,'172.16.32.186' ,'iEM3155' ,1 ,155 ,'E207DC23'", "'','','',      2 ,156 ,'E207DC9D'", "'','','',      3 ,153 ,'E207DC9B'", "'','','',      4 ,151 ,'E207DC9A'", "'','','',      5 ,152 ,'E207DC91'", "'','','',      6 ,154 ,'E207DC9C'", "'D-12-07' ,'172.16.32.187' ,'iEM3155' ,1 ,155 ,'E207DC84'", "'','','',      2 ,150 ,'E207DC83'", "'','','',      3 ,154 ,'E207DBDB'", "'','','',      4 ,151 ,'E207DBB9'", "'','','',      5 ,153 ,'E207DC88'", "'','','',      6 ,152 ,'E207DBDD'", "'D-12-08' ,'172.16.32.188' ,'iEM3155' ,1 ,152 ,'E207DBDC'", "'','','',      2 ,155 ,'E207DBBF'", "'','','',      3 ,150 ,'E207DBDA'", "'','','',      4 ,154 ,'E207DC2D'", "'','','',      5 ,151 ,'E207DC65'", "'','','',      6 ,153 ,'E207DC8B'", "'D-12-09' ,'172.16.32.189' ,'iEM3155' ,1 ,154 ,'E20744DC'", "'','','',      2 ,152 ,'E20758D2'", "'','','',      3 ,150 ,'E20758CD'", "'','','',      4 ,153 ,'E207460B'", "'','','',      5 ,151 ,'E2074507'", "'D-12-10' ,'172.16.32.190' ,'iEM3155' ,1 ,152 ,'E207DBE4'", "'','','',      2 ,150 ,'E207DBCD'", "'','','',      3 ,155 ,'E207DBC7'", "'','','',      4 ,154 ,'E207DBE0'", "'','','',      5 ,151 ,'E207DBC5'", "'','','',      6 ,153 ,'E207DBE8'", "'D-13-01' ,'172.16.32.191' ,'iEM3155' ,1 ,151 ,'E207DD15'", "'','','',      2 ,153 ,'E207DD0D'", "'','','',      3 ,152 ,'E207DD2D'", "'','','',      4 ,154 ,'E207DD13'", "'','','',      5 ,155 ,'E207DD2E'", "'','','',      6 ,150 ,'E207DD29'", "'D-13-02' ,'172.16.32.192' ,'iEM3155' ,1 ,160 ,'E207DB7B'", "'','','',      2 ,153 ,'E207DB95'", "'','','',      3 ,159 ,'E207DBA3'", "'','','',      4 ,157 ,'E207DBAA'", "'','','',      5 ,158 ,'E207DB8E'", "'','','',      6 ,150 ,'E207DB94'", "'D-13-03' ,'172.16.32.193' ,'iEM3155' ,1 ,155 ,'E207DBB3'", "'','','',      2 ,151 ,'E207DBAF'", "'','','',      3 ,152 ,'E207DB8D'", "'','','',      4 ,150 ,'E207DB63'", "'','','',      5 ,153 ,'E207DBB0'", "'','','',      6 ,154 ,'E207DBA1'", "'D-13-04' ,'172.16.32.194' ,'iEM3155' ,1 ,153 ,'E207DB8B'", "'','','',      2 ,150 ,'E207DBBD'", "'','','',      3 ,151 ,'E207DBBE'", "'','','',      4 ,154 ,'E207DB9B'", "'','','',      5 ,152 ,'E207DB8A'", "'','','',      6 ,155 ,'E207DBCA'", "'D-13-05' ,'172.16.32.195' ,'iEM3155' ,1 ,154 ,'E207DB88'", "'','','',      2 ,150 ,'E207DB9D'", "'','','',      3 ,152 ,'E207DBAD'", "'','','',      4 ,155 ,'E207DBCB'", "'','','',      5 ,151 ,'E207DBA8'", "'','','',      6 ,153 ,'E207DB85'", "'D-13-06' ,'172.16.32.196' ,'iEM3155' ,1 ,152 ,'E207DBC3'", "'','','',      2 ,150 ,'E207DB8C'", "'','','',      3 ,151 ,'E207DB97'", "'','','',      4 ,153 ,'E207DB84'", "'','','',      5 ,155 ,'E207DB99'", "'','','',      6 ,154 ,'E207DBA7'", "'D-13-07' ,'172.16.32.197' ,'iEM3155' ,1 ,151 ,'E207DBBC'", "'','','',      2 ,156 ,'E207DBAE'", "'','','',      3 ,154 ,'E207DB98'", "'','','',      4 ,153 ,'E207DBA0'", "'','','',      5 ,150 ,'E207DB77'", "'','','',      6 ,160 ,'E207DBC8'", "'D-13-08' ,'172.16.32.198' ,'iEM3155' ,1 ,153 ,'E207DBCF'", "'','','',      2 ,151 ,'E207DBCC'", "'','','',      3 ,154 ,'E207DBD0'", "'','','',      4 ,155 ,'E207DBA9'", "'','','',      5 ,150 ,'E207DBD2'", "'','','',      6 ,152 ,'E207DBD8'", "'D-13-09' ,'172.16.32.199' ,'iEM3155' ,1 ,154 ,'E207556A'", "'','','',      2 ,153 ,'E20744EE'", "'','','',      3 ,150 ,'E20744FA'", "'','','',      4 ,152 ,'E20758B9'", "'','','',      5 ,151 ,'E20758C3'", "'D-13-10' ,'172.16.32.200' ,'iEM3155' ,1 ,154 ,'E207DB9F'", "'','','',      2 ,153 ,'E207DB89'", "'','','',      3 ,151 ,'E207DB93'", "'','','',      4 ,150 ,'E207DBBB'", "'','','',      5 ,152 ,'E207DBB5'", "'','','',      6 ,156 ,'E207DBC2'", "'D-14-01' ,'172.16.32.201' ,'iEM3155' ,1 ,151 ,'E207DD0E'", "'','','',      2 ,153 ,'E207DD2A'", "'','','',      3 ,154 ,'E207DD2C'", "'','','',      4 ,152 ,'E207DD25'", "'','','',      5 ,150 ,'E207DD14'", "'','','',      6 ,155 ,'E207DD0F'", "'D-14-02' ,'172.16.32.202' ,'iEM3155' ,1 ,152 ,'E207DD0C'", "'','','',      2 ,155 ,'E207DD1E'", "'','','',      3 ,153 ,'E207DCD6'", "'','','',      4 ,156 ,'E207DD04'", "'','','',      5 ,154 ,'E207DD0B'", "'','','',      6 ,150 ,'E207DC98'", "'D-14-03' ,'172.16.32.203' ,'iEM3155' ,1 ,152 ,'E207D297'", "'','','',      2 ,154 ,'E207D29A'", "'','','',      3 ,151 ,'E207DCF9'", "'','','',      4 ,150 ,'E207D292'", "'','','',      5 ,155 ,'E207D29C'", "'','','',      6 ,153 ,'E207D5C3'", "'D-14-04' ,'172.16.32.204' ,'iEM3155' ,1 ,150 ,'E207DCFC'", "'','','',      2 ,151 ,'E207DD0A'", "'','','',      3 ,152 ,'E207DCF0'", "'','','',      4 ,155 ,'E207DD07'", "'','','',      5 ,154 ,'E207DCEE'", "'','','',      6 ,153 ,'E207DCFF'"];


$part3 = [ "'D-14-05' ,'172.16.32.205' ,'iEM3155' ,1 ,153 ,'E207D2A2'", "'','','',      2 ,156 ,'E207DD01'", "'','','',      3 ,157 ,'E207D2A7'", "'','','',      4 ,154 ,'E207DCA5'", "'','','',      5 ,158 ,'E207D5C5'", "'','','',      6 ,159 ,'E207D5BC'", "'D-14-06' ,'172.16.32.206' ,'iEM3155' ,1 ,150 ,'E207DD00'", "'','','',      2 ,153 ,'E207DCF2'", "'','','',      3 ,156 ,'E207DCFB'", "'','','',      4 ,154 ,'E207DCF1'", "'','','',      5 ,151 ,'E207DD10'", "'','','',      6 ,155 ,'E207DD18'", "'D-14-07' ,'172.16.32.207' ,'iEM3155' ,1 ,155 ,'E207DBC9'", "'','','',      2 ,157 ,'E207DBD9'", "'','','',      3 ,152 ,'E207DBC6'", "'','','',      4 ,153 ,'E207DBD7'", "'','','',      5 ,150 ,'E207DBD3'", "'','','',      6 ,151 ,'E207DBD5'", "'D-14-08' ,'172.16.32.208' ,'iEM3155' ,1 ,152 ,'E207DB9A'", "'','','',      2 ,156 ,'E207DB8F'", "'','','',      3 ,154 ,'E207DB66'", "'','','',      4 ,151 ,'E207DD23'", "'','','',      5 ,155 ,'E207DB64'", "'','','',      6 ,150 ,'E207DCC8'", "'D-14-09' ,'172.16.32.209' ,'iEM3155' ,1 ,155 ,'E20758AF'", "'','','',      2 ,157 ,'E20758B3'", "'','','',      3 ,152 ,'E20758B8'", "'','','',      4 ,153 ,'E20758C2'", "'','','',      5 ,150 ,'E20758BF'", "'D-14-10' ,'172.16.32.210' ,'iEM3155' ,1 ,154 ,'E207DCDA'", "'','','',      2 ,155 ,'E207DCB6'", "'','','',      3 ,156 ,'E207DCA0'", "'','','',      4 ,153 ,'E207DD05'", "'','','',      5 ,152 ,'E207DD08'", "'','','',      6 ,150 ,'E207DD06'", "'D-15-01' ,'172.16.32.211' ,'iEM3155' ,1 ,150 ,'E207D287'", "'','','',      2 ,152 ,'E207D5C8'", "'','','',      3 ,156 ,'E207DA2F'", "'','','',      4 ,154 ,'E207D27A'", "'','','',      5 ,151 ,'E207D28A'", "'','','',      6 ,153 ,'E207D286'", "'D-15-02' ,'172.16.32.212' ,'iEM3155' ,1 ,151 ,'E207DA54'", "'','','',      2 ,150 ,'E207D289'", "'','','',      3 ,153 ,'E207DAB9'", "'','','',      4 ,155 ,'E207D27D'", "'','','',      5 ,152 ,'E207D283'", "'','','',      6 ,154 ,'E207DAE2'", "'D-15-03' ,'172.16.32.213' ,'iEM3155' ,1 ,155 ,'E207DABE'", "'','','',      2 ,151 ,'E207DAEC'", "'','','',      3 ,153 ,'E207DA94'", "'','','',      4 ,154 ,'E207DAC8'", "'','','',      5 ,152 ,'E207D9BF'", "'','','',      6 ,150 ,'E207DAC9'", "'D-15-04' ,'172.16.32.214' ,'iEM3155' ,1 ,150 ,'E207D28E'", "'','','',      2 ,151 ,'E207D28B'", "'','','',      3 ,152 ,'E207D285'", "'','','',      4 ,154 ,'E207D290'", "'','','',      5 ,153 ,'E207D288'", "'','','',      6 ,155 ,'E207D27E'", "'D-15-05' ,'172.16.32.215' ,'iEM3155' ,1 ,153 ,'E207DAF3'", "'','','',      2 ,155 ,'E207DAE3'", "'','','',      3 ,152 ,'E207DADF'", "'','','',      4 ,151 ,'E207DAD2'", "'','','',      5 ,150 ,'E207DA5E'", "'','','',      6 ,154 ,'E207DAF5'", "'D-15-06' ,'172.16.32.216' ,'iEM3155' ,1 ,151 ,'E207DAF0'", "'','','',      2 ,160 ,'E207DAEF'", "'','','',      3 ,153 ,'E207DAD3'", "'','','',      4 ,155 ,'E207DA85'", "'','','',      5 ,150 ,'E207DA93'", "'','','',      6 ,156 ,'E207DAF6'", "'D-15-07' ,'172.16.32.217' ,'iEM3155' ,1 ,151 ,'E207DAC7'", "'','','',      2 ,158 ,'E207DAD9'", "'','','',      3 ,156 ,'E207DAF2'", "'','','',      4 ,153 ,'E207DAF4'", "'','','',      5 ,154 ,'E207DAAD'", "'','','',      6 ,150 ,'E207DAF7'", "'D-15-08' ,'172.16.32.218' ,'iEM3155' ,1 ,153 ,'E207DD09'", "'','','',      2 ,154 ,'E207DCD4'", "'','','',      3 ,152 ,'E207DCEF'", "'','','',      4 ,150 ,'E207DCEC'", "'','','',      5 ,155 ,'E207DD03'", "'','','',      6 ,151 ,'E207DCFD'", "'D-15-09' ,'172.16.32.219' ,'iEM3155' ,1 ,152 ,'E2074616'", "'','','',      2 ,150 ,'E20758AA'", "'','','',      3 ,153 ,'E20758B6'", "'','','',      4 ,154 ,'E20744FB'", "'','','',      5 ,151 ,'E20758BE'", "'D-15-10' ,'172.16.32.220' ,'iEM3155' ,1 ,151 ,'E207D27B'", "'','','',      2 ,150 ,'E207D27F'", "'','','',      3 ,154 ,'E207D282'", "'','','',      4 ,153 ,'E207D28F'", "'','','',      5 ,152 ,'E207D27C'", "'','','',      6 ,155 ,'E207D5C7'", "'D-16-01' ,'172.16.32.221' ,'iEM3155' ,1 ,154 ,'E207C309'", "'','','',      2 ,152 ,'E207DACC'", "'','','',      3 ,156 ,'E207DAE5'", "'','','',      4 ,150 ,'E207DA48'", "'','','',      5 ,155 ,'E207DAEB'", "'','','',      6 ,153 ,'E207D9FD'", "'D-16-02' ,'172.16.32.222' ,'iEM3155' ,1 ,158 ,'E207DADE'", "'','','',      2 ,151 ,'E207DAE6'", "'','','',      3 ,155 ,'E207DAED'", "'','','',      4 ,156 ,'E207D9DC'", "'','','',      5 ,153 ,'E207DADC'", "'','','',      6 ,150 ,'E207DAEA'", "'D-16-03' ,'172.16.32.223' ,'iEM3155' ,1 ,153 ,'E207DA8C'", "'','','',      2 ,152 ,'E207DA15'", "'','','',      3 ,150 ,'E207C302'", "'','','',      4 ,155 ,'E207DA0D'", "'','','',      5 ,151 ,'E207DAC2'", "'','','',      6 ,156 ,'E207DAD7'", "'D-16-04' ,'172.16.32.224' ,'iEM3155' ,1 ,153 ,'E207DACB'", "'','','',      2 ,150 ,'E207DAC3'", "'','','',      3 ,154 ,'E207DAE8'", "'','','',      4 ,151 ,'E207DAF1'", "'','','',      5 ,152 ,'E207DA34'", "'','','',      6 ,155 ,'E207DAE9'", "'D-16-05' ,'172.16.32.225' ,'iEM3155' ,1 ,156 ,'E207DB50'", "'','','',      2 ,158 ,'E207DB4D'", "'','','',      3 ,153 ,'E207DB52'", "'','','',      4 ,157 ,'E207DB4B'", "'','','',      5 ,152 ,'E207DB00'", "'','','',      6 ,150 ,'E207DB51'", "'D-16-06' ,'172.16.32.226' ,'iEM3155' ,1 ,155 ,'E207DA43'", "'','','',      2 ,158 ,'E207DAD1'", "'','','',      3 ,157 ,'E207DAD6'", "'','','',      4 ,151 ,'E207DADD'", "'','','',      5 ,150 ,'E207DA84'", "'','','',      6 ,152 ,'E207DAD5'", "'D-16-07' ,'172.16.32.227' ,'iEM3155' ,1 ,151 ,'E207DAB5'", "'','','',      2 ,155 ,'E207D9F2'", "'','','',      3 ,152 ,'E207DA0E'", "'','','',      4 ,153 ,'E207DADB'", "'','','',      5 ,154 ,'E207D9FA'", "'','','',      6 ,150 ,'E207D9EB'", "'D-16-08' ,'172.16.32.228' ,'iEM3155' ,1 ,154 ,'E207DA8D'", "'','','',      2 ,151 ,'E207D9DA'", "'','','',      3 ,153 ,'E207DA06'", "'','','',      4 ,150 ,'E207D9F4'", "'','','',      5 ,155 ,'E207D9D2'", "'','','',      6 ,152 ,'E207DAC5'", "'D-16-09' ,'172.16.32.229' ,'iEM3355' ,1 ,154 ,'E20744EB'", "'','','',      2 ,153 ,'E20744D9'", "'','','',      3 ,152 ,'E20744D7'", "'','','',      4 ,150 ,'E20744E5'", "'','','',      5 ,151 ,'E20744F1'", "'D-16-10' ,'172.16.32.230' ,'iEM3155' ,1 ,154 ,'E207DAE4'", "'','','',      2 ,155 ,'E207DAE1'", "'','','',      3 ,153 ,'E207DAD0'", "'','','',      4 ,151 ,'E207DACE'", "'','','',      5 ,152 ,'E207DACF'", "'','','',      6 ,150 ,'E207DADA'", "'D-17-01' ,'172.16.32.231' ,'iEM3155' ,1 ,158 ,'E207D5BF'", "'','','',      2 ,152 ,'E207D5D2'", "'','','',      3 ,154 ,'E207D5B4'", "'','','',      4 ,151 ,'E207D5B5'", "'','','',      5 ,160 ,'E207D5C2'", "'','','',      6 ,155 ,'E207D5B3'", "'D-17-02' ,'172.16.32.232' ,'iEM3155' ,1 ,152 ,'E207D5BB'", "'','','',      2 ,153 ,'E207D5BE'", "'','','',      3 ,150 ,'E207D5B2'", "'','','',      4 ,155 ,'E207D5D7'", "'','','',      5 ,154 ,'E207D5C0'", "'','','',      6 ,151 ,'E207D5B9'", "'D-17-03' ,'172.16.32.233' ,'iEM3155' ,1 ,151 ,'E207D85C'", "'','','',      2 ,155 ,'E207D851'", "'','','',      3 ,150 ,'E207D85F'", "'','','',      4 ,153 ,'E207D852'", "'','','',      5 ,154 ,'E207D835'", "'','','',      6 ,152 ,'E207DA0A'", "'D-17-04' ,'172.16.32.234' ,'iEM3155' ,1 ,152 ,'E207DB5C'", "'','','',      2 ,150 ,'E207DB43'", "'','','',      3 ,151 ,'E207DB4C'", "'','','',      4 ,154 ,'E207DB4F'", "'','','',      5 ,153 ,'E207DB58'", "'','','',      6 ,155 ,'E207DB53'", "'D-17-05' ,'172.16.32.235' ,'iEM3155' ,1 ,153 ,'E207D828'", "'','','',      2 ,150 ,'E207D831'", "'','','',      3 ,159 ,'E207D841'", "'','','',      4 ,156 ,'E207D847'", "'','','',      5 ,151 ,'E207D84E'", "'','','',      6 ,161 ,'E207D84D'", "'D-17-06' ,'172.16.32.236' ,'iEM3155' ,1 ,157 ,'E207D850'", "'','','',      2 ,156 ,'E207D82F'", "'','','',      3 ,154 ,'E207D855'", "'','','',      4 ,153 ,'E207D862'", "'','','',      5 ,151 ,'E207D848'", "'','','',      6 ,150 ,'E207D858'", "'D-17-07' ,'172.16.32.237' ,'iEM3155' ,1 ,151 ,'E207DB4A'", "'','','',      2 ,155 ,'E207DB47'", "'','','',      3 ,150 ,'E207DB5B'", "'','','',      4 ,153 ,'E207DB4E'", "'','','',      5 ,152 ,'E207DB48'", "'','','',      6 ,154 ,'E207DB59'", "'D-17-08' ,'172.16.32.238' ,'iEM3155' ,1 ,154 ,'E207DB5E'", "'','','',      2 ,155 ,'E207DB57'", "'','','',      3 ,151 ,'E207DB5D'", "'','','',      4 ,152 ,'E207DB24'", "'','','',      5 ,153 ,'E207DB45'", "'','','',      6 ,150 ,'E207DB54'", "'D-17-09' ,'172.16.32.239' ,'iEM3155' ,1 ,152 ,'E20744E0'", "'','','',      2 ,151 ,'E20744D8'", "'','','',      3 ,150 ,'E2074505'", "'','','',      4 ,153 ,'E20744E9'", "'','','',      5 ,154 ,'E20744E1'", "'D-17-10' ,'172.16.32.240' ,'iEM3155' ,1 ,153 ,'E207DA91'", "'','','',      2 ,151 ,'E207DA29'", "'','','',      3 ,152 ,'E207D837'", "'','','',      4 ,150 ,'E207DAB6'", "'','','',      5 ,154 ,'E207DAC0'", "'','','',      6 ,155 ,'E207D84A'", "'D-18-01' ,'172.16.32.241' ,'iEM3155' ,1 ,155 ,'E207DB38'", "'','','',      2 ,150 ,'E207DB3C'", "'','','',      3 ,151 ,'E207DB01'", "'','','',      4 ,154 ,'E207DAFE'", "'','','',      5 ,152 ,'E207DB02'", "'','','',      6 ,153 ,'E207DB08'", "'D-18-02' ,'172.16.32.242' ,'iEM3155' ,1 ,155 ,'E207D5E3'", "'','','',      2 ,151 ,'E207DAD4'", "'','','',      3 ,150 ,'E207D9F8'", "'','','',      4 ,152 ,'E207D5CC'", "'','','',      5 ,154 ,'E207D5D3'", "'','','',      6 ,153 ,'E207D9F9'", "'D-18-03' ,'172.16.32.243' ,'iEM3155' ,1 ,154 ,'E207D114'", "'','','',      2 ,152 ,'E207D15B'", "'','','',      3 ,150 ,'E207D826'", "'','','',      4 ,151 ,'E207DD69'", "'','','',      5 ,155 ,'E207DD62'", "'','','',      6 ,153 ,'E207DD72'", "'D-18-04' ,'172.16.32.244' ,'iEM3155' ,1 ,155 ,'E207D5CA'", "'','','',      2 ,152 ,'E207D5BA'", "'','','',      3 ,150 ,'E207D5CF'", "'','','',      4 ,154 ,'E207D5D4'", "'','','',      5 ,153 ,'E207D5E5'", "'','','',      6 ,151 ,'E207D5DA'", "'D-18-05' ,'172.16.32.245' ,'iEM3155' ,1 ,150 ,'E207DD6B'", "'','','',      2 ,151 ,'E207DD5B'", "'','','',      3 ,154 ,'E207DD57'", "'','','',      4 ,152 ,'E207D183'", "'','','',      5 ,153 ,'E207DD1C'", "'','','',      6 ,155 ,'E207DD5E'", "'D-18-06' ,'172.16.32.246' ,'iEM3155' ,1 ,151 ,'E207D834'", "'','','',      2 ,150 ,'E207D846'", "'','','',      3 ,155 ,'E207D81A'", "'','','',      4 ,154 ,'E207D861'", "'','','',      5 ,158 ,'E207D838'", "'','','',      6 ,157 ,'E207D849'", "'D-18-07' ,'172.16.32.247' ,'iEM3155' ,1 ,155 ,'E207D5C1'", "'','','',      2 ,150 ,'E207DAC6'", "'','','',      3 ,152 ,'E207D5DE'", "'','','',      4 ,153 ,'E207D9FE'", "'','','',      5 ,151 ,'E207D5DF'", "'','','',      6 ,154 ,'E207DA2B'", "'D-18-08' ,'172.16.32.248' ,'iEM3155' ,1 ,151 ,'E207DB55'", "'','','',      2 ,155 ,'E207DB11'", "'','','',      3 ,154 ,'E207DB0E'", "'','','',      4 ,153 ,'E207DB61'", "'','','',      5 ,152 ,'E207DB5F'", "'','','',      6 ,150 ,'E207DB46'", "'D-18-09' ,'172.16.32.249' ,'iEM3355' ,1 ,153 ,'E20744E7'", "'','','',      2 ,152 ,'E2074502'", "'','','',      3 ,151 ,'E20744F2'", "'','','',      4 ,150 ,'E20744F6'", "'','','',      5 ,154 ,'E20744EA'", "'D-18-10' ,'172.16.32.250' ,'iEM3155' ,1 ,152 ,'E207D85B'", "'','','',      2 ,150 ,'E207D867'", "'','','',      3 ,158 ,'E207DAB0'", "'','','',      4 ,157 ,'E207D857'", "'','','',      5 ,153 ,'E207D85A'", "'','','',      6 ,155 ,'E207D813'", "'D-19-01' ,'172.16.33.11'  ,'iEM3155' ,1 ,157 ,'E207D5B7'", "'','','',      2 ,153 ,'E207DB3E'", "'','','',      3 ,160 ,'E207D5C6'", "'','','',      4 ,159 ,'E207DB40'", "'','','',      5 ,151 ,'E207D5D9'", "'','','',      6 ,152 ,'E207DAFA'", "'D-19-02' ,'172.16.33.12'  ,'iEM3155' ,1 ,155 ,'E207DB39'", "'','','',      2 ,152 ,'E207DB3B'", "'','','',      3 ,150 ,'E207DB3D'", "'','','',      4 ,151 ,'E207DB3A'", "'','','',      5 ,153 ,'E207DB37'", "'','','',      6 ,154 ,'E207DB0F'", "'D-19-03' ,'172.16.33.13'  ,'iEM3155' ,1 ,151 ,'E207D14B'", "'','','',      2 ,150 ,'E207D123'", "'','','',      3 ,154 ,'E207D15E'", "'','','',      4 ,152 ,'E207D161'", "'','','',      5 ,155 ,'E207D169'", "'','','',      6 ,153 ,'E207D168'", "'D-19-04' ,'172.16.33.14'  ,'iEM3155' ,1 ,155 ,'E207DAFD'", "'','','',      2 ,161 ,'E207DB44'", "'','','',      3 ,156 ,'E207D5B6'", "'','','',      4 ,159 ,'E207DB3F'", "'','','',      5 ,152 ,'E207D5B8'", "'','','',      6 ,151 ,'E207D5E4'", "'D-19-05' ,'172.16.33.15'  ,'iEM3155' ,1 ,150 ,'E207DA2E'", "'','','',      2 ,155 ,'E207DABC'", "'','','',      3 ,151 ,'E207DA7E'", "'','','',      4 ,153 ,'E207DA7B'", "'','','',      5 ,152 ,'E207D836'", "'','','',      6 ,154 ,'E207D860'", "'D-19-06' ,'172.16.33.16'  ,'iEM3155' ,1 ,153 ,'E207DAA8'", "'','','',      2 ,158 ,'E207D842'", "'','','',      3 ,151 ,'E207DAB7'", "'','','',      4 ,159 ,'E207DACD'", "'','','',      5 ,157 ,'E207D83B'", "'','','',      6 ,150 ,'E207D84C'", "'D-19-07' ,'172.16.33.17'  ,'iEM3155' ,1 ,150 ,'E207D11B'", "'','','',      2 ,153 ,'E207D16D'", "'','','',      3 ,151 ,'E207D14C'", "'','','',      4 ,154 ,'E207D16A'", "'','','',      5 ,155 ,'E207D11F'", "'','','',      6 ,152 ,'E207D16C'", "'D-19-08' ,'172.16.33.18'  ,'iEM3155' ,1 ,152 ,'E207D83C'", "'','','',      2 ,155 ,'E207DA70'", "'','','',      3 ,154 ,'E207DAAA'", "'','','',      4 ,153 ,'E207DA86'", "'','','',      5 ,151 ,'E207DABA'", "'','','',      6 ,150 ,'E207DABB'", "'D-19-09' ,'172.16.33.19'  ,'iEM3155' ,1 ,155 ,'E20744E8'", "'','','',      2 ,154 ,'E20744F7'", "'','','',      3 ,153 ,'E20744FC'", "'','','',      4 ,156 ,'E20744F3'", "'','','',      5 ,152 ,'E20744EC'", "'D-19-10' ,'172.16.33.20'  ,'iEM3155' ,1 ,151 ,'E207D5CE'", "'','','',      2 ,152 ,'E207D5CD'", "'','','',      3 ,153 ,'E207D5E1'", "'','','',      4 ,155 ,'E207D5D5'", "'','','',      5 ,150 ,'E207D9F1'", "'','','',      6 ,154 ,'E207DAA2'", "'D-20-01' ,'172.16.33.21'  ,'iEM3155' ,1 ,151 ,'E207D116'", "'','','',      2 ,150 ,'E207D833'", "'','','',      3 ,152 ,'E207D122'", "'','','',      4 ,153 ,'E207D17B'", "'','','',      5 ,155 ,'E207D110'", "'','','',      6 ,154 ,'E207D182'", "'D-20-02' ,'172.16.33.22'  ,'iEM3155' ,1 ,155 ,'E207D117'", "'','','',      2 ,151 ,'E207D840'", "'','','',      3 ,150 ,'E207D812'", "'','','',      4 ,152 ,'E207D112'", "'','','',      5 ,154 ,'E207D159'", "'','','',      6 ,153 ,'E207D160'", "'D-20-03' ,'172.16.33.23'  ,'iEM3155' ,1 ,154 ,'E207D121'", "'','','',      2 ,151 ,'E207D15A'", "'','','',      3 ,155 ,'E207D185'", "'','','',      4 ,150 ,'E207D10E'", "'','','',      5 ,152 ,'E207D165'", "'','','',      6 ,153 ,'E207D832'", "'D-20-04' ,'172.16.33.24'  ,'iEM3155' ,1 ,151 ,'E207DAB8'", "'','','',      2 ,150 ,'E207D83F'", "'','','',      3 ,152 ,'E207DABF'", "'','','',      4 ,153 ,'E207DA7C'", "'','','',      5 ,154 ,'E207D84B'", "'','','',      6 ,155 ,'E207D9B4'", "'D-20-05' ,'172.16.33.25'  ,'iEM3155' ,1 ,155 ,'E207D164'", "'','','',      2 ,153 ,'E207D153'", "'','','',      3 ,150 ,'E207D145'", "'','','',      4 ,152 ,'E207D163'", "'','','',      5 ,151 ,'E207D16B'", "'','','',      6 ,154 ,'E207D184'", "'D-20-06' ,'172.16.33.26'  ,'iEM3155' ,1 ,151 ,'E207D172'", "'','','',      2 ,155 ,'E207D7D5'", "'','','',      3 ,150 ,'E207D15C'", "'','','',      4 ,154 ,'E207D15F'", "'','','',      5 ,153 ,'E207D7C1'", "'','','',      6 ,152 ,'E207D839'", "'D-20-07' ,'172.16.33.27'  ,'iEM3155' ,1 ,153 ,'E207DAC1'", "'','','',      2 ,152 ,'E207DA14'", "'','','',      3 ,156 ,'E207D9EC'", "'','','',      4 ,150 ,'E207DA05'", "'','','',      5 ,151 ,'E207D5E0'", "'','','',      6 ,154 ,'E207D5DB'", "'D-20-08' ,'172.16.33.28'  ,'iEM3155' ,1 ,154 ,'E207D9F6'", "'','','',      2 ,151 ,'E207D5DC'", "'','','',      3 ,152 ,'E207D9F0'", "'','','',      4 ,153 ,'E207D5C9'", "'','','',      5 ,150 ,'E207D5CB'", "'','','',      6 ,155 ,'E207DA3F'", "'D-20-09' ,'172.16.33.29'  ,'iEM3155' ,1 ,153 ,'E2074506'", "'','','',      2 ,150 ,'E20744E6'", "'','','',      3 ,154 ,'E20744DF'", "'','','',      4 ,151 ,'E20744F4'", "'','','',      5 ,152 ,'E2074500'", "'D-20-10' ,'172.16.33.30'  ,'iEM3155' ,1 ,153 ,'E207D5D8'", "'','','',      2 ,150 ,'E207D9E3'", "'','','',      3 ,151 ,'E207D5D6'", "'','','',      4 ,155 ,'E207D9EF'", "'','','',      5 ,152 ,'E207D9E9'", "'','','',      6 ,154 ,'E207D9ED'"];



$part4 = ["'C-02-12' , '172.16.32.83' , 'iEM3155'  ,1 ,151 ,'E207D24C'" , "'' , '' , '' ,        2 ,154 ,'E207D24B'" , "'' , '' , '' ,        3 ,152 ,'E207D226'" , "'' , '' , '' ,        4 ,155 ,'E207D23C'" , "'' , '' , '' ,        5 ,150 ,'E207D23F'" , "'' , '' , '' ,        6 ,153 ,'E207D246'" , "'C-02-13' , '172.16.32.84' , 'iEM3155'  ,1 ,151 ,'E207D223'" , "'' , '' , '' ,        2 ,150 ,'E207D239'" , "'' , '' , '' ,        3 ,157 ,'E207DCFE'" , "'' , '' , '' ,        4 ,160 ,'E207D221'" , "'' , '' , '' ,        5 ,156 ,'E207D228'" , "'' , '' , '' ,        6 ,155 ,'E207D230'" , "'C-02-14' , '172.16.32.85' , 'iEM3155'  ,1 ,150 ,'E207D238'" , "'' , '' , '' ,        2 ,154 ,'E207D240'" , "'' , '' , '' ,        3 ,155 ,'E207D247'" , "'' , '' , '' ,        4 ,152 ,'E207D21E'" , "'' , '' , '' ,        5 ,153 ,'E207D248'" , "'' , '' , '' ,        6 ,151 ,'E207D248'" , "'C-02-15' , '172.16.32.86' , 'iEM3155'  ,1 ,152 ,'E207DD43'" , "'' , '' , '' ,        2 ,157 ,'E207DD26'" , "'' , '' , '' ,        3 ,158 ,'E207D237'" , "'' , '' , '' ,        4 ,160 ,'E207DD58'" , "'' , '' , '' ,        5 ,161 ,'E207DD28'" , "'' , '' , '' ,        6 ,154 ,'E207DD54'" , "'C-02-16' , '172.16.32.87' , 'iEM3155'  ,1 ,154 ,'E207DD1B'" , "'' , '' , '' ,        2 ,153 ,'E207DD3B'" , "'' , '' , '' ,        3 ,151 ,'E207DD45'" , "'' , '' , '' ,        4 ,155 ,'E207DD49'" , "'' , '' , '' ,        5 ,150 ,'E207DD39'" , "'' , '' , '' ,        6 ,152 ,'E207DD16'" , "'C-02-17' , '172.16.32.88' , 'iEM3155'  ,1 ,154 ,'E207DD22'" , "'' , '' , '' ,        2 ,150 ,'E207DD1F'" , "'' , '' , '' ,        3 ,152 ,'E207DD41'" , "'' , '' , '' ,        4 ,151 ,'E207DD52'" , "'' , '' , '' ,        5 ,157 ,'E207DD42'" , "'' , '' , '' ,        6 ,156 ,'E207DD3C'" , "'C-02-18' , '172.16.32.89' , 'iEM3155'  ,1 ,154 ,'E207D2A4'" , "'' , '' , '' ,        2 ,153 ,'E207D299'" , "'' , '' , '' ,        3 ,150 ,'E207D2AE'" , "'' , '' , '' ,        4 ,152 ,'E207D2A3'" , "'' , '' , '' ,        5 ,151 ,'E207D2A9'" , "'' , '' , '' ,        6 ,155 ,'E207D284'" , "'C-02-01' , '172.16.32.76' , 'iEM3155'  ,1 ,150 ,'E207DD66'" , "'' , '' , '' ,        2 ,155 ,'E207DD76'" , "'' , '' , '' ,        3 ,152 ,'E207DD76'" , "'' , '' , '' ,        4 ,154 ,'E207DD63'" , "'' , '' , '' ,        5 ,151 ,'E207DD4B'" , "'' , '' , '' ,        6 ,153 ,'E207DD5F'" , "'C-02-02' , '172.16.32.77' , 'iEM3155'  ,1 ,152 ,'E207DD6F'" , "'' , '' , '' ,        2 ,153 ,'E207DD64'" , "'' , '' , '' ,        3 ,151 ,'E207DD47'" , "'' , '' , '' ,        4 ,154 ,'E207DD5D'" , "'' , '' , '' ,        5 ,155 ,'E207DD6C'" , "'' , '' , '' ,        6 ,150 ,'E207DD59'" , "'C-02-03' , '172.16.32.78' , 'iEM3155'  ,1 ,151 ,'E207DD73'" , "'' , '' , '' ,        2 ,152 ,'E207DD12'" , "'' , '' , '' ,        3 ,153 ,'E207DD75'" , "'' , '' , '' ,        4 ,154 ,'E207DD46'" , "'' , '' , '' ,        5 ,150 ,'E207DD59'" , "'' , '' , '' ,        6 ,155 ,'E207DD70'" , "'C-02-04' , '172.16.32.79' , 'iEM3155'  ,1 ,150 ,'E207DD74'" , "'' , '' , '' ,        2 ,152 ,'E207BADB'" , "'' , '' , '' ,        3 ,151 ,'E207BAE4'" , "'' , '' , '' ,        4 ,153 ,'E207DD5A'" , "'' , '' , '' ,        5 ,154 ,'E207BAE7'" , "'' , '' , '' ,        6 ,155 ,'E207BAF3'" , "'C-02-05' , '172.16.32.80' , 'iEM3155'  ,1 ,150 ,'E207D29B'" , "'' , '' , '' ,        2 ,154 ,'E207D293'" , "'' , '' , '' ,        3 ,155 ,'E207D29F'" , "'' , '' , '' ,        4 ,151 ,'E207D296'" , "'' , '' , '' ,        5 ,152 ,'E207D28D'" , "'' , '' , '' ,        6 ,153 ,'E207D294'" , "'C-02-06' , '172.16.32.81' , 'iEM3155'  ,1 ,153 ,'E207D52F'" , "'' , '' , '' ,        2 ,154 ,'E207D532'" , "'' , '' , '' ,        3 ,155 ,'E207D510'" , "'' , '' , '' ,        4 ,151 ,'E207D52A'" , "'' , '' , '' ,        5 ,152 ,'E207D52E'" , "'' , '' , '' ,        6 ,150 ,'E207D528'" , "'C-02-07' , '172.16.32.82' , 'iEM3155'  ,1 ,154 ,'E207BAF6'" , "'' , '' , '' ,        2 ,155 ,'E207DD27'" , "'' , '' , '' ,        3 ,150 ,'E207BAF8'" , "'' , '' , '' ,        4 ,153 ,'E207DD77'" , "'' , '' , '' ,        5 ,151 ,'E207BACB'" , "'' , '' , '' ,        6 ,152 ,'E207BADD'" , "'C-04-01' , '172.16.32.90' , 'iEM3155'  ,1 ,152 ,'E207D21D'" , "'' , '' , '' ,        2 ,153 ,'E207D211'" , "'' , '' , '' ,        3 ,150 ,'E207D213'" , "'' , '' , '' ,        4 ,151 ,'E207D220'" , "'' , '' , '' ,        5 ,155 ,'E207D231'" , "'' , '' , '' ,        6 ,154 ,'E207D21A'" , "'C-04-02' , '172.16.32.91' , 'iEM3155'  ,1 ,151 ,'E207D22A'" , "'' , '' , '' ,        2 ,150 ,'E207D218'" , "'' , '' , '' ,        3 ,158 ,'E207D212'" , "'' , '' , '' ,        4 ,155 ,'E207D229'" , "'' , '' , '' ,        5 ,161 ,'E207D21B'" , "'' , '' , '' ,        6 ,160 ,'E207D22F'" , "'C-04-03' , '172.16.32.92' , 'iEM3155'  ,1 ,152 ,'E207D229'" , "'' , '' , '' ,        2 ,151 ,'E207D22E'" , "'' , '' , '' ,        3 ,155 ,'E207D215'" , "'' , '' , '' ,        4 ,153 ,'E207D22B'" , "'' , '' , '' ,        5 ,150 ,'E207D227'" , "'' , '' , '' ,        6 ,154 ,'E207D23A'" , "'C-04-04' , '172.16.32.93' , 'iEM3155'  ,1 ,152 ,'E207D23E'" , "'' , '' , '' ,        2 ,155 ,'E207D232'" , "'' , '' , '' ,        3 ,154 ,'E207D21F'" , "'' , '' , '' ,        4 ,157 ,'E207D224'" , "'' , '' , '' ,        5 ,153 ,'E207D222'" , "'' , '' , '' ,        6 ,150 ,'E207D225'" , "'C-04-05', '172.16.32.94',  'iEM3155'  ,1 ,151 ,'E207DE59'" , "'' , '' , '' ,        2 ,155 ,'E207DE6D'" , "'' , '' , '' ,        3 ,150 ,'E207DE77'" , "'' , '' , '' ,        4 ,152 ,'E207DE18'" , "'' , '' , '' ,        5 ,153 ,'E207DE7C'" , "'' , '' , '' ,        6 ,154 ,'E207DE76'" , "'C-04-06', '172.16.32.95',  'iEM3155'  ,1 ,157 ,'E207DE76'" , "'' , '' , '' ,        2 ,153 ,'E207D214'" , "'' , '' , '' ,        3 ,152 ,'E207D24E'" , "'' , '' , '' ,        4 ,150 ,'E207D217'" , "'' , '' , '' ,        5 ,155 ,'E207D22D'" , "'' , '' , '' ,        6 ,151 ,'E207D21C'" ];

  $part5 = [ "'C-04-07', '172.16.32.96',  'iEM3155'  ,1 ,154 ,'E207D531'" , "'' , '' , '' ,        2 ,151 ,'E207D537'" , "'' , '' , '' ,        3 ,155 ,'E207D52B'" , "'' , '' , '' ,        4 ,150 ,'E207D541'" , "'' , '' , '' ,        5 ,152 ,'E207D542'" , "'' , '' , '' ,        6 ,153 ,'E207D534'" , "'C-04-08', '172.16.32.97',  'iEM3155'  ,1 ,155 ,'E207DE5C'" , "'' , '' , '' ,        2 ,152 ,'E207DE73'" , "'' , '' , '' ,        3 ,154 ,'E207DE24'" , "'' , '' , '' ,        4 ,150 ,'E207DE67'" , "'' , '' , '' ,        5 ,153 ,'E207DE60'" , "'' , '' , '' ,        6 ,151 ,'E207DE7A'" , "'C-04-09', '172.16.32.98',  'iEM3155'  ,1 ,150 ,'E207DE6F'" , "'' , '' , '' ,        2 ,153 ,'E207DE22'" , "'' , '' , '' ,        3 ,154 ,'E207DE6A'" , "'' , '' , '' ,        4 ,151 ,'E207DE47'" , "'' , '' , '' ,        5 ,155 ,'E207DE78'" , "'' , '' , '' ,        6 ,152 ,'E207DE70'" , "'C-04-10', '172.16.32.99',  'iEM3155'  ,1 ,153 ,'E207DB17'" , "'' , '' , '' ,        2 ,150 ,'E207DAFB'" , "'' , '' , '' ,        3 ,152 ,'E207DAD8'" , "'' , '' , '' ,        4 ,155 ,'E207DB1C'" , "'' , '' , '' ,        5 ,154 ,'E207DA96'" , "'' , '' , '' ,        6 ,151 ,'E207DAE7'" , "'C-04-11', '172.16.32.100', 'iEM3155'  ,1 ,160 ,'E207DE65'" , "'' , '' , '' ,        2 ,151 ,'E207DE50'" , "'' , '' , '' ,        3 ,152 ,'E207DE63'" , "'' , '' , '' ,        4 ,153 ,'E207DE69'" , "'' , '' , '' ,        5 ,156 ,'E207DE58'" , "'' , '' , '' ,        6 ,150 ,'E207DE5F'" , "'C-04-12', '172.16.32.101', 'iEM3155'  ,1 ,152 ,'E207DB2E'" , "'' , '' , '' ,        2 ,153 ,'E207DB2E'" , "'' , '' , '' ,        3 ,150 ,'E207DAFC'" , "'' , '' , '' ,        4 ,154 ,'E207DB23'" , "'' , '' , '' ,        5 ,155 ,'E207DB28'" , "'' , '' , '' ,        6 ,151 ,'E207DB17'" , "'C-04-13', '172.16.32.102', 'iEM3155'  ,1 ,152 ,'E207D53E'" , "'' , '' , '' ,        2 ,150 ,'E207D522'" , "'' , '' , '' ,        3 ,153 ,'E207D525'" , "'' , '' , '' ,        4 ,151 ,'E207D543'" , "'' , '' , '' ,        5 ,154 ,'E207D53A'" , "'' , '' , '' ,        6 ,155 ,'E207D540'" , "'C-04-14', '172.16.32.103', 'iEM3155'  ,1 ,157 ,'E207DB32'" , "'' , '' , '' ,        2 ,156 ,'E207DB36'" , "'' , '' , '' ,        3 ,160 ,'E207DB16'" , "'' , '' , '' ,        4 ,158 ,'E207DB10'" , "'' , '' , '' ,        5 ,155 ,'E207DB29'" , "'' , '' , '' ,        6 ,161 ,'E207DB12'" , "'C-04-15', '172.16.32.104', 'iEM3155'  ,1 ,151 ,'E207DB2F'" , "'' , '' , '' ,        2 ,154 ,'E207DA6B'" , "'' , '' , '' ,        3 ,155 ,'E207DB26'" , "'' , '' , '' ,        4 ,152 ,'E207DB05'" , "'' , '' , '' ,        5 ,153 ,'E207DB25'" , "'' , '' , '' ,        6 ,150 ,'E207DB2A'" , "'C-04-16', '172.16.32.105', 'iEM3155'  ,1 ,158 ,'E207DE3E'" , "'' , '' , '' ,        2 ,157 ,'E207DE5A'" , "'' , '' , '' ,        3 ,155 ,'E207DE5D'" , "'' , '' , '' ,        4 ,156 ,'E207DE56'" , "'' , '' , '' ,        5 ,151 ,'E207DE5B'" , "'' , '' , '' ,        6 ,154 ,'E207DE61'" , "'C-04-17', '172.16.32.106', 'iEM3155'  ,1 ,153 ,'E207DB0C'" , "'' , '' , '' ,        2 ,152 ,'E207DB35'" , "'' , '' , '' ,        3 ,151 ,'E207DB18'" , "'' , '' , '' ,        4 ,155 ,'E207DB31'" , "'' , '' , '' ,        5 ,159 ,'E207DB06'" , "'' , '' , '' ,        6 ,150 ,'E207DAE0'" , "'C-04-18', '172.16.32.107', 'iEM3155'  ,1 ,153 ,'E207DB14'" , "'' , '' , '' ,        2 ,151 ,'E207DB21'" , "'' , '' , '' ,        3 ,150 ,'E207DB33'" , "'' , '' , '' ,        4 ,155 ,'E207DA95'" , "'' , '' , '' ,        5 ,152 ,'E207DB2B'" , "'' , '' , '' ,        6 ,154 ,'E207DB27'" , "'C-06-01', '172.16.32.108', 'iEM3155'  ,1 ,153 ,'E207DB1A'" , "'' , '' , '' ,        2 ,154 ,'E207DAEE'" , "'' , '' , '' ,        3 ,152 ,'E207DB13'" , "'' , '' , '' ,        4 ,151 ,'E207DB03'" , "'' , '' , '' ,        5 ,150 ,'E207DB20'" , "'' , '' , '' ,        6 ,155 ,'E207DB04'" , "'C-06-02', '172.16.32.109', 'iEM3155'  ,1 ,154 ,'E207DE74'" , "'' , '' , '' ,        2 ,152 ,'E207DE7B'" , "'' , '' , '' ,        3 ,155 ,'E207DE75'" , "'' , '' , '' ,        4 ,150 ,'E207DE62'" , "'' , '' , '' ,        5 ,151 ,'E207DE68'" , "'' , '' , '' ,        6 ,153 ,'E207DE4D'" , "'C-06-03', '172.16.32.110', 'iEM3155'  ,1 ,158 ,'E207DE72'" , "'' , '' , '' ,        2 ,159 ,'E207DE0D'" , "'' , '' , '' ,        3 ,152 ,'E207DE6C'" , "'' , '' , '' ,        4 ,154 ,'E207DE71'" , "'' , '' , '' ,        5 ,161 ,'E207DE51'" , "'' , '' , '' ,        6 ,157 ,'E207DE0E'" , "'C-06-04', '172.16.32.111', 'iEM3155'  ,1 ,154 ,'E207DE5E'" , "'' , '' , '' ,        2 ,155 ,'E207DE79'" , "'' , '' , '' ,        3 ,151 ,'E207DE6E'" , "'' , '' , '' ,        4 ,156 ,'E207DE64'" , "'' , '' , '' ,        5 ,158 ,'E207DE66'" , "'' , '' , '' ,        6 ,152 ,'E207DE40'" , "'C-06-05', '172.16.32.112', 'iEM3155'  ,1 ,152 ,'E207DB1D'" , "'' , '' , '' ,        2 ,153 ,'E207DB15'" , "'' , '' , '' ,        3 ,154 ,'E207DB1F'" , "'' , '' , '' ,        4 ,151 ,'E207DB1E'" , "'' , '' , '' ,        5 ,155 ,'E207DB2C'" , "'' , '' , '' ,        6 ,150 ,'E207DB22'" , "'C-06-06', '172.16.32.113', 'iEM3155'  ,1 ,155 ,'E207D536'" , "'' , '' , '' ,        2 ,152 ,'E207D53C'" , "'' , '' , '' ,        3 ,154 ,'E207D52D'" , "'' , '' , '' ,        4 ,151 ,'E207D539'" , "'' , '' , '' ,        5 ,150 ,'E207D533'" , "'' , '' , '' ,        6 ,153 ,'E207D53D'" , "'C-06-07', '172.16.32.114', 'iEM3155'  ,1 ,154 ,'E207D524'" , "'' , '' , '' ,        2 ,150 ,'E207D53F'" , "'' , '' , '' ,        3 ,152 ,'E207D535'" , "'' , '' , '' ,        4 ,153 ,'E207D526'" , "'' , '' , '' ,        5 ,155 ,'E207D538'" , "'' , '' , '' ,        6 ,151 ,'E207D53B'" , "'C-06-08', '172.16.32.115', 'iEM3155'  ,1 ,150 ,'E207D683'" , "'' , '' , '' ,        2 ,151 ,'E207D67A'" , "'' , '' , '' ,        3 ,155 ,'E207D654'" , "'' , '' , '' ,        4 ,153 ,'E207D66C'" , "'' , '' , '' ,        5 ,154 ,'E207D66F'" , "'' , '' , '' ,        6 ,152 ,'E207D67C'" , "'C-06-09', '172.16.32.116', 'iEM3155'  ,1 ,155 ,'E207D530'" , "'' , '' , '' ,        2 ,153 ,'E207D51A'" , "'' , '' , '' ,        3 ,152 ,'E207D519'" , "'' , '' , '' ,        4 ,154 ,'E207D521'" , "'' , '' , '' ,        5 ,150 ,'E207D523'" , "'' , '' , '' ,        6 ,151 ,'E207D51E'" , "'C-06-10', '172.16.32.117', 'iEM3155'  ,1 ,153 ,'E207D67D'" , "'' , '' , '' ,        2 ,154 ,'E207D671'" , "'' , '' , '' ,        3 ,150 ,'E207D684'" , "'' , '' , '' ,        4 ,155 ,'E207D66B'" , "'' , '' , '' ,        5 ,151 ,'E207D675'" , "'' , '' , '' ,        6 ,152 ,'E207D682'" , "'C-06-11', '172.16.32.118', 'iEM3155'  ,1 ,155 ,'E207D686'" , "'' , '' , '' ,        2 ,154 ,'E207D669'" , "'' , '' , '' ,        3 ,153 ,'E207D659'" , "'' , '' , '' ,        4 ,152 ,'E207D677'" , "'' , '' , '' ,        5 ,151 ,'E207D681'" , "'' , '' , '' ,        6 ,150 ,'E207D676'" , "'C-06-12', '172.16.32.119', 'iEM3155'  ,1 ,155 ,'E207D672'" , "'' , '' , '' ,        2 ,151 ,'E207D660'" , "'' , '' , '' ,        3 ,154 ,'E207D67E'" , "'' , '' , '' ,        4 ,153 ,'E207D66E'" , "'' , '' , '' ,        5 ,150 ,'E207D674'" , "'' , '' , '' ,        6 ,152 ,'E207D673'" , "'C-06-13', '172.16.32.120', 'iEM3155'  ,1 ,161 ,'E207D99B'" , "'' , '' , '' ,        2 ,150 ,'E207D2AB'" , "'' , '' , '' ,        3 ,157 ,'E207D99F'" , "'' , '' , '' ,        4 ,160 ,'E207D997'" , "'' , '' , '' ,        5 ,151 ,'E207D2A5'" , "'' , '' , '' ,        6 ,152 ,'E207D970'" , "'C-06-14', '172.16.32.121', 'iEM3155'  ,1 ,150 ,'E207D2A8'" , "'' , '' , '' ,        2 ,154 ,'E207D99C'" , "'' , '' , '' ,        3 ,155 ,'E207DB7D'" , "'' , '' , '' ,        4 ,152 ,'E207D2AA'" , "'' , '' , '' ,        5 ,151 ,'E207D96C'" , "'' , '' , '' ,        6 ,153 ,'E207D999'" , "'C-06-15', '172.16.32.122', 'iEM3155'  ,1 ,159 ,'E207D679'" , "'' , '' , '' ,        2 ,150 ,'E207D687'" , "'' , '' , '' ,        3 ,157 ,'E207D670'" , "'' , '' , '' ,        4 ,152 ,'E207D685'" , "'' , '' , '' ,        5 ,154 ,'E207D67F'" , "'' , '' , '' ,        6 ,153 ,'E207D51D'" , "'C-06-16', '172.16.32.123', 'iEM3155'  ,1 ,154 ,'E207D98A'" , "'' , '' , '' ,        2 ,152 ,'E207D972'" , "'' , '' , '' ,        3 ,153 ,'E207D975'" , "'' , '' , '' ,        4 ,155 ,'E207D8CF'" , "'' , '' , '' ,        5 ,150 ,'E207D995'" , "'' , '' , '' ,        6 ,151 ,'E207D973'" , "'C-06-17', '172.16.32.124', 'iEM3155'  ,1 ,156 ,'E207D97F'" , "'' , '' , '' ,        2 ,153 ,'E207D980'" , "'' , '' , '' ,        3 ,155 ,'E207D979'" , "'' , '' , '' ,        4 ,151 ,'E207D982'" , "'' , '' , '' ,        5 ,152 ,'E207D985'" , "'' , '' , '' ,        6 ,150 ,'E207D98E'" , "'C-06-18', '172.16.32.125', 'iEM3155'  ,1 ,154 ,'E207D983'" , "'' , '' , '' ,        2 ,155 ,'E207D97B'" , "'' , '' , '' ,        3 ,153 ,'E207DC49'" , "'' , '' , '' ,        4 ,152 ,'E207DC4C'" , "'' , '' , '' ,        5 ,150 ,'E207D298'" , "'' , '' , '' ,        6 ,151 ,'E207D29D'" , "'C-08-10', '172.16.32.35',  'iEM3155'  ,1 ,153 ,'E207DC34'" , "'' , '' , '' ,        2 ,151 ,'E207DC53'" , "'' , '' , '' ,        3 ,152 ,'E207DB2D'" , "'' , '' , '' ,        4 ,155 ,'E207DC33'" , "'' , '' , '' ,        5 ,154 ,'E207DC28'" , "'' , '' , '' ,        6 ,150 ,'E207DC4A'" , "'C-08-11', '172.16.32.36',  'iEM3155'  ,1 ,150 ,'E207D2A6'" , "'' , '' , '' ,        2 ,152 ,'E207D2A1'" , "'' , '' , '' ,        3 ,153 ,'E207D29E'" , "'' , '' , '' ,        4 ,154 ,'E207D295'" , "'' , '' , '' ,        5 ,151 ,'E207D2A0'" , "'' , '' , '' ,        6 ,155 ,'E207D2AD'" , "'C-08-12', '172.16.32.37',  'iEM3155'  ,1 ,150 ,'E207DC45'" , "'' , '' , '' ,        2 ,155 ,'E207DC4F'" , "'' , '' , '' ,        3 ,151 ,'E207DC2F'" , "'' , '' , '' ,        4 ,152 ,'E207DC40'" , "'' , '' , '' ,        5 ,153 ,'E207DC3A'" , "'' , '' , '' ,        6 ,154 ,'E207DC48'" , "'C-08-13', '172.16.32.138', 'iEM3155'  ,1 ,160 ,'E207DC54'" , "'' , '' , '' ,        2 ,152 ,'E207DC43'" , "'' , '' , '' ,        3 ,161 ,'E207DC39'" , "'' , '' , '' ,        4 ,155 ,'E207DC52'" , "'' , '' , '' ,        5 ,157 ,'E207DC3C'" , "'' , '' , '' ,        6 ,154 ,'E207DC51'" , "'C-08-14', '172.16.32.139', 'iEM3155'  ,1 ,154 ,'E207D989'" , "'' , '' , '' ,        2 ,155 ,'E207DC35'" , "'' , '' , '' ,        3 ,152 ,'E207D981'" , "'' , '' , '' ,        4 ,151 ,'E207DC4D'" , "'' , '' , '' ,        5 ,153 ,'E207D984'" , "'' , '' , '' ,        6 ,150 ,'E207D987'" , "'C-08-15', '172.16.32.140', 'iEM3155'  ,1 ,153 ,'E207D991'" , "'' , '' , '' ,        2 ,152 ,'E207D994'" , "'' , '' , '' ,        3 ,155 ,'E207DC50'" , "'' , '' , '' ,        4 ,154 ,'E207D971'" , "'' , '' , '' ,        5 ,151 ,'E207DC3B'" , "'' , '' , '' ,        6 ,156 ,'E207DC37'" , "'C-08-16', '172.16.32.141', 'iEM3155'  ,1 ,150 ,'E207DC18'" , "'' , '' , '' ,        2 ,153 ,'E207DC56'" , "'' , '' , '' ,        3 ,151 ,'E207DC41'" , "'' , '' , '' ,        4 ,152 ,'E207DC3F'" , "'' , '' , '' ,        5 ,154 ,'E207DC4B'" , "'' , '' , '' ,        6 ,155 ,'E207DC46'" , "'C-08-17', '172.16.32.142', 'iEM3155'  ,1 ,160 ,'E207D98C'" , "'' , '' , '' ,        2 ,156 ,'E207D97A'" , "'' , '' , '' ,        3 ,155 ,'E207D990'" , "'' , '' , '' ,        4 ,151 ,'E207D996'" , "'' , '' , '' ,        5 ,161 ,'E207DC58'" , "'' , '' , '' ,        6 ,154 ,'E207D8CE'" , "'C-08-18', '172.16.32.143', 'iEM3155'  ,1 ,155 ,'E207D992'" , "'' , '' , '' ,        2 ,154 ,'E207D993'" , "'' , '' , '' ,        3 ,152 ,'E207D988'" , "'' , '' , '' ,        4 ,153 ,'E207D974'" , "'' , '' , '' ,        5 ,151 ,'E207D986'" , "'' , '' , '' ,        6 ,150 ,'E207D97E'"];
    //dd($part1);
  $no_meter_room = array();
  $no_meter_params = array();
    $final = array();
    $part_keys = [1,2,3,4,5];
    foreach($part_keys as $key)
    {
      $arr_1 = 'part'.$key ;
      //dd($$arr_1);
      $final = array_merge($final , $$arr_1);
    }
   // dd($final);

    $return['bulk-upload'] = array();
    $temp ;
    $mapper = [0=>'unit' , 1=>'ip_address' , 2=>'device_name' , 3=>'room_no' , 4=>'modbus_address' , 5=>'rf_id'];
    $counter= 0;
    //dd($data);

    $main_data = array();
    foreach($final as $item)
    {
      //echo $item."<br>";
      $temp_1 = explode("," ,$item);

      if(!is_array($temp_1))
      {
        continue;
      } 
      //echo json_encode($temp_1)."<br>";
      if($temp_1[0] != '' && trim($temp_1[0]) !=  "''")
      {
       // echo '|'.$temp_1[0].'|'."<br>";
        $main_data = [0 =>$temp_1[0],1 =>$temp_1[1],2 =>$temp_1[2] ];
        //dd($main_data);
      }else{
       // echo 'Else';
        foreach($main_data as $key => $value)
        {
          $temp_1[$key] = $value;
        }
       // dd($temp_1);
      }
      //echo json_encode($temp_1)."<br>";
      $room = Room::getRoomByHouseRoomName( trim(str_replace("'", "",$temp_1[0])) ,  trim(str_replace("'", "",$temp_1[3])));
//dd($room);
      if(!isset($room['id_house_room']))
      { // json_encode($temp_1)
        /*dd($room);
        echo 'Room not found :'.str_replace("'", "",$temp_1[0]).'='. str_replace("'", "",$temp_1[3])."<br>";
        continue;*/
      }

      if($room['id_house_room'] == '')
        {
          echo  trim(str_replace("'", "",$meter_value)).'<br>';
        }
        $m = MeterRegister::get_meter_register_by_leaf_room_id($room['id_house_room']);
//dd($room);
//dd($m);
if(!isset($m['id']))
{
  array_push($no_meter_room , $room);
   array_push($no_meter_params , $temp_1);
  
}
     // dd($room);
      foreach ($temp_1 as $mapper_key => $meter_value)
      {
        //echo $mapper_key.'='.json_encode($meter_value)."<br>";
        if(!isset($room['id_house_room']))
        {
          //echo 'Room not found :'.json_encode($temp_1)."<br>";
          continue;
        }

        
        //$temp[$room['id']][$mapper[$mapper_key]] = trim(str_replace("'", "",$meter_value));

        $temp['bulk_upload'][$room['id_house_room']][$mapper[$mapper_key]] = trim(str_replace("'", "",$meter_value));
      }

     
          $temp['bulk_upload'][$room['id_house_room']]['leaf_room_id'] = $room['id_house_room']; 
      
    
      
      //echo $counter."<br>";$counter++;
      
    }

    foreach($no_meter_params as $row)
    {
      echo json_encode($row)."<br>";
    }
    dd($no_meter_params);
dd($temp);



      $x = MeterRegister::saveOrUpdateMeterRegisters($temp);

dd($part1);

});
Route::get('initSub', function ()
{ /* MeterPaymentReceived::get_remaining_subsidy_member_id_by_meter_subsidiary_id(14);
  dd("Stop");*/
  $date_range['starting_date'] =  "2019-03";
  $date_range['ending_date']  = "2019-05";
  $subsidy_listing = MeterSubsidiary::get_subsidy_by_leaf_group_id(519);
  //dd($subsidy_listing);
  foreach ($subsidy_listing as $row) {
   
    if($row['code'] !== 'Test'){continue;}
   //  dd($row);
    MeterPaymentReceived::create_subsidy_meter_payment_received_model($row['id'], $date_range);
    //MeterPaymentReceived::create_subsidy_meter_payment_received_model_patching($row['id'], $date_range);
  }
  
  dd("end");
});


Route::get('testReading', function(){


      $cpus_listing = CustomerPowerUsageSummary::all();//CustomerPowerUsageSummary::getAllByLeafGroupId($company_model['leaf_group_id']);
      if(count($cpus_listing) > 0)
      {
          foreach($cpus_listing as $cpus_model)
          {
              $cpus_model->updateAccountUsage();


          }
      }

});




Route::get('arrayMerge', function(){


      $a = array(1,2,3,4);
      $b = array(5,6,7,8);
      $c = array_merge($a , $b);
      dd($c);

});


Route::get('testSendEmail', function(){

    $default_language  = 'english';
    $company_model = new Company();
    $leaf_api = new LeafAPI();
    $company_model = $company_model->self_profile();
    $backend_data = $company_model->backend_data;
    $power_meter_low_credit_reminder = json_decode($backend_data['power_meter_low_credit_reminder'] , true);

    $email = 'adelfried1227a@hotmail.com';
    dd($leaf_api->send_email($email, $power_meter_low_credit_reminder[$default_language]['title'], $power_meter_low_credit_reminder[$default_language]['content']));
    dd('Done');

});


Route::get('resetAcc', function(){

    $cpus_listing = CustomerPowerUsageSummary::all();//CustomerPowerUsageSummary::getAllByLeafGroupId($company_model['leaf_group_id']);
    //dd($cpus_listing);
    if(count($cpus_listing) > 0)
    {
        foreach($cpus_listing as $cpus_model)
        {
           $cpus_model->reset_warning_counter();
           continue;
         }
    }
    dd('Done');

});

Route::get('autoCron1', function(){
    //power_meter_mailbox_setting
    $default_language  = 'english';
    $company_model = new Company();
    $leaf_api = new LeafAPI();
    $company_model = $company_model->self_profile();
    //dd($company_model);
    $backend_data = $company_model->backend_data;
    //dd($backend_data);
    $power_meter_turn_off_meter_email = json_decode($backend_data['power_meter_turn_off_meter_email'] , true);
    $power_meter_low_credit_reminder = json_decode($backend_data['power_meter_low_credit_reminder'] , true);
    $power_meter_power_supply_restore_email = json_decode($backend_data['power_meter_power_supply_restore_email'] , true);

    //dd($power_meter_low_credit_reminder);
    $power_meter_op_setting = json_decode($company_model['power_meter_operational_setting'], true);
    //dd($power_meter_op_setting);
    $tester_list = $power_meter_op_setting['uat_tester_list'];
    //dd($tester_list);
    /*$email = 'adelfried1227a@hotmail.com';
    dd($leaf_api->send_email($email, $power_meter_low_credit_reminder[$default_language]['title'], $power_meter_low_credit_reminder[$default_language]['content']));*/
   // $tester_list = 
   // dd($power_meter_op_setting['uat_tester_list']);

    if($power_meter_op_setting['power_supply_on_off_automation'] == false)
    {
      dd('Stop');
    }

    if($power_meter_op_setting['is_auto_turn_off_meter'] == true)
    {
        
        //['user_preferred_language'];
        $cpus_listing = CustomerPowerUsageSummary::all();//CustomerPowerUsageSummary::getAllByLeafGroupId($company_model['leaf_group_id']);
        //dd($cpus_listing);
        if(count($cpus_listing) > 0)
        {
            foreach($cpus_listing as $cpus_model)
            {
               /*$cpus_model->reset_warning_counter();
               continue;*/
                  if( $power_meter_op_setting['is_in_uat'] == true)
                  {
                      if(!in_array($cpus_model['id'] , $tester_list ))
                      {
                          //skip if no tester
                          continue;
                      }else{

                          //dd($cpus_model);
                      }

                  }
                  //$cpus_model->reset_warning_counter();
                  if($cpus_model['leaf_id_user'] == 0){continue;}
                    $user_model = $cpus_model->getCurrentAccountUser();
                    $email = $user_model['email'];

                    echo 'Target email :'.$email."<br>";
                  //dd($cpus_model);
                    $cpus_model->getOrUpdateMeterRegister();
                    $language = $cpus_model->getUserPreferLanguage();
                    $total_balance = $cpus_model['current_credit_amount'] + $cpus_model['total_subsidy_amount'];
                    echo 'Total b :'.$total_balance.' vs '.$power_meter_op_setting['credit_threshold']."<br>";
                    if( $total_balance < $power_meter_op_setting['credit_threshold']){
                        echo 'Below credit '.$cpus_model['below_credit_notification_count'] .' == '.$power_meter_op_setting['warning_email_number'].' <br>';

                        // termination flow
                        if($cpus_model['warning_email_number'] >= $power_meter_op_setting['warning_email_number']){
                           echo 'Termination <br>';
                          //send termination email
                         // $this->updateTerminationHistory();
                         // dd('?');
                         // 
                        //dd(strlen($cpus_model['stop_supply_termination_time']));
                         //  dd($cpus_model['stop_supply_termination_time']);
                            if(strlen($cpus_model['stop_supply_termination_time']) > 5 )
                            {
echo 'First step <br>';
//dd($power_meter_op_setting);
                                  if($power_meter_op_setting['is_auto_turn_off_meter'] == true)
                                  { echo 'Second step <br>';
                                //echo $cpus_model['stop_supply_termination_time'].' = '.date('Y-m-d H:i:s', strtotime('now'));dd($cpus_model['stop_supply_termination_time'] <= date('Y-m-d H:i:s', strtotime('now')));
                                      if( $cpus_model['stop_supply_termination_time'] <= date('Y-m-d H:i:s', strtotime('now'))){
                                          echo 'Trigger termination <br>';
                                          if($cpus_model['is_power_supply_on'] == true)
                                             echo 'Call method <br>';
                                            $cpus_model->terminate_power_supply();
                                          }

                                      
                                      
                                  }

                            }else{


                                  $email_response = $leaf_api->send_email($email, $power_meter_turn_off_meter_email[$default_language]['title'], $power_meter_turn_off_meter_email[$default_language]['content']);

                                  echo 'Termination email :'.json_encode($email_response)."<br>";
                                 if($email_response['status_code'])
                                 {
                                      echo 'Email send out successfully <br>';
                                      $cpus_model['below_credit_notification_count'] = $cpus_model['below_credit_notification_count'] + 1;
                                      $cpus_model['warning_email_number'] +=  1; 
                                      $cpus_model['stop_supply_termination_time'] = date('Y-m-d H:i:s', strtotime("+".$power_meter_op_setting['grace_period_before_stop_supply']." minutes", strtotime('now')));
                                      $cpus_model->save();

                            
                                      
                                 }

                            }
                           

                         
                          
                          // non terminate add count and email
                        }else{

                            $next_email_time = '';
                            //email sender checker
                            echo 'Look add count <br>';
                            echo  $cpus_model['last_below_credit_notification_email_at'] >= date('Y-m-d H:i:s', strtotime('now'))."<br>";
                            if($cpus_model['last_below_credit_notification_email_at'] <= date('Y-m-d H:i:s', strtotime('now')))
                            {
                                if($cpus_model['below_credit_notification_count'] > $cpus_model['warning_email_number'])
                                {
                                    $email_response= $leaf_api->send_email($email, $power_meter_low_credit_reminder[$default_language]['title'], $power_meter_low_credit_reminder[$default_language]['content']);
                                    $cpus_model['warning_email_number'] += 1 ;
                                    $cpus_model->save();
                                }
                            }

                            //notification checker
                            echo 'Add notification count '.$cpus_model['last_below_credit_notification_email_at']. ' <br>';
                            if($cpus_model['last_below_credit_notification_email_at'] == null || $cpus_model['last_below_credit_notification_email_at'] ==  '')
                            {
                                $next_email_time = date('Y-m-d H:i:s', strtotime('now'));
                            }else if($cpus_model['below_credit_notification_count'] == $cpus_model['warning_email_number']){
                                
                                $next_email_time = date('Y-m-d H:i:s', strtotime("+".$power_meter_op_setting['warning_email_interval']." minutes", strtotime($cpus_model['last_below_credit_notification_email_at']) ));
                                  
                            }

                            echo 'Add notification count next time'.$next_email_time.'<br>';
                            if($next_email_time !== '')
                            {
                                if(date('Y-m-d H:i:s', strtotime('now')) <= $next_email_time ){
                                    echo 'Go update'.$next_email_time.'<br>';
                                    $cpus_model->updateNotificationHistory($next_email_time);
                                }
                            }
                            
                            
                        }
                        
                         
                    }else{
                        echo 'Restore count: '.$cpus_model['below_credit_notification_count']."<br>";
                        if($cpus_model['below_credit_notification_count'] > 0)
                        {
                            echo 'Reset counter <br>';
                            if($cpus_model->restore_power_supply()){
                                echo 'Reset variable M <br>';
                                $cpus_model->reset_warning_counter();
                                //send restoration email
                                $email_response= $leaf_api->send_email($email, $power_meter_power_supply_restore_email[$default_language]['title'], $power_meter_power_supply_restore_email[$default_language]['content']);
                               

                            }
                            
                        }
                    }

                    echo json_encode($cpus_model)."<br>";
            }


        }

    }
    dd($power_meter_op_setting);
});





Route::get('populateMeterRelay', function(){

/*


$update_arr = ["'C-02-01','172.16.34.71',' need check back'","'C-02-02','172.16.34.72',' 3/3/2021'","'C-02-03','172.16.34.73',' 3/3/2021'","'C-02-04','172.16.34.74',' 3/3/2021'","'C-02-05','172.16.34.75',' 3/3/2021'","'C-02-06','172.16.34.76',' 3/3/2021'","'C-02-07','172.16.34.77',' 3/3/2021'","'C-02-12','172.16.34.78',' n/s'","'C-02-13','172.16.34.79',' n/s'","'C-02-14','172.16.34.80',' 3/3/2021'","'C-02-15','172.16.34.81',' 1/3/2021'","'C-02-16','172.16.34.82',' 3/3/2021'","'C-02-17','172.16.34.83',' 3/3/2021'","'C-02-18','172.16.34.84',' 1/3/2021'","'C-06-01','172.16.34.108','3/3/2021'","'C-06-02','172.16.34.109','n/s'","'C-06-03','172.16.34.110','3/3/2021'","'C-06-04','172.16.34.111','3/3/2021'","'C-06-05','172.16.34.112','n/s'","'C-06-06','172.16.34.113','3/3/2021'","'C-06-07','172.16.34.114','3/3/2021'","'C-06-08','172.16.34.115','3/3/2021'","'C-06-09','172.16.34.116','3/3/2021'","'C-06-10','172.16.34.117','3/3/2021'","'C-06-11','172.16.34.118','3/3/2021'","'C-06-12','172.16.34.119','3/3/2021'","'C-06-13','172.16.34.120','3/3/2021'","'C-06-14','172.16.34.121','3/3/2021'","'C-06-15','172.16.34.121','3/3/2021'","'C-06-16','172.16.34.123','n/s'","'C-06-17','172.16.34.124','3/3/2021'","'C-06-18','172.16.34.125','3/3/2021'","'C-04-01','172.16.34.85',' 3/3/2021'","'C-04-02','172.16.34.86',' 3/3/2021'","'C-04-03','172.16.34.87',' 3/3/2021'","'C-04-04','172.16.34.88',' 3/3/2021'","'C-04-05','172.16.34.89',' 3/3/2021'","'C-04-06','172.16.34.90',' n/s'","'C-04-07','172.16.34.91',' 3/3/2021'","'C-04-08','172.16.34.92',' 3/3/2021'","'C-04-09','172.16.34.93',' n/s'","'C-04-10','172.16.34.94',' n/s'","'C-04-11','172.16.34.95',' 3/3/2021'","'C-04-12','172.16.34.96',' n/s'","'C-04-13','172.16.34.97',' n/s'","'C-04-14','172.16.34.98',' n/s'","'C-04-15','172.16.34.99',' 3/3/2021'","'C-04-16','172.16.34.100','3/3/2021'","'C-04-17','172.16.34.101','3/3/2021'","'C-04-18','172.16.34.102','3/3/2021'","'C-08-01','172.16.34.126','3/3/2021'","'C-08-02','172.16.34.127','n/s'","'C-08-03','172.16.34.128','3/3/2021'","'C-08-04','172.16.34.129','3/3/2021'","'C-08-05','172.16.34.130','n/s'","'C-08-06','172.16.34.131','3/3/2021'","'C-08-07','172.16.34.132','3/3/2021'","'C-08-08','172.16.34.133','3/3/2021'","'C-08-09','172.16.34.134','3/3/2021'","'C-08-10','172.16.34.135','3/3/2021'","'C-08-11','172.16.34.136','3/3/2021'","'C-08-12','172.16.34.137','3/3/2021'","'C-08-13','172.16.34.138','n/s'","'C-08-14','172.16.34.139','3/3/2021'","'C-08-15','172.16.34.140','3/3/2021'","'C-08-16','172.16.34.141','3/3/2021'","'C-08-17','172.16.34.142','3/3/2021'","'C-08-18','172.16.34.143','3/3/2021'","'D-02-01','172.16.34.61',' 2/3/2021'","'D-02-02','172.16.34.62',' 2/3/2021'","'D-02-03','172.16.34.63',' n/s'","'D-02-04','172.16.34.64',' 2/3/2021'","'D-02-05','172.16.34.65',' 2/3/2021'","'D-02-06','172.16.34.66',' 2/3/2021'","'D-02-07','172.16.34.67',' n/s'","'D-02-08','172.16.34.68',' n/s'","'D-02-09','172.16.34.69',' 2/3/2021'","'D-02-10','172.16.34.70',' 2/3/2021'","'D-04-01','172.16.34.41',' need check back'","'D-04-02','172.16.34.42',' need check back'","'D-04-03','172.16.34.43',' need check back'","'D-04-04','172.16.34.44',' need check back'","'D-04-05','172.16.34.45',' need check back'","'D-04-06','172.16.34.46',' need check back'","'D-04-07','172.16.34.47',' need check back'","'D-04-08','172.16.34.48',' n/s'","'D-04-09','172.16.34.49',' 2/3/2021'","'D-04-10','172.16.34.50',' need check back'","'D-06-01','172.16.34.21',' 2/3/2021'","'D-06-02','172.16.34.22',' 2/3/2021'","'D-06-03','172.16.34.23',' 2/3/2021'","'D-06-04','172.16.34.24',' 2/3/2021'","'D-06-05','172.16.34.25',' 2/3/2021'","'D-06-06','172.16.34.26',' 2/3/2021'","'D-06-07','172.16.34.27',' n/s'","'D-06-08','172.16.34.28',' 2/3/2021'","'D-06-09','172.16.34.29',' 2/3/2021'","'D-06-10','172.16.34.30',' 2/3/2021'","'D-08-01','172.16.34.1','need check back'","'D-08-02','172.16.34.2','need check back'","'D-08-03','172.16.34.3','2/3/2021'","'D-08-04','172.16.34.4','need check back'","'D-08-05','172.16.34.5','n/s'","'D-08-06','172.16.34.6','need check back'","'D-08-07','172.16.34.7','need check back'","'D-08-08','172.16.34.8','2/3/2021'","'D-08-09','172.16.34.9','need check back'","'D-08-10','172.16.34.10',' need check back'","'D-10-01','172.16.34.161','3/3/2021'","'D-10-02','172.16.34.162','3/3/2021'","'D-10-03','172.16.34.163','3/3/2021'","'D-10-04','172.16.34.164','3/3/2021'","'D-10-05','172.16.34.165','3/3/2021'","'D-10-06','172.16.34.166','3/3/2021'","'D-10-07','172.16.34.167','3/3/2021'","'D-10-08','172.16.34.168','3/3/2021'","'D-10-09','172.16.34.169','3/3/2021'","'D-10-10','172.16.34.170','3/3/2021'","'D-12-01','172.16.34.181','3/3/2021'","'D-12-02','172.16.34.182','3/3/2021'","'D-12-03','172.16.34.183','3/3/2021'","'D-12-04','172.16.34.184','3/3/2021'","'D-12-05','172.16.34.185','3/3/2021'","'D-12-06','172.16.34.186','3/3/2021'","'D-12-07','172.16.34.187','3/3/2021'","'D-12-08','172.16.34.188','3/3/2021'","'D-12-09','172.16.34.189','n/s'","'D-12-10','172.16.34.190','3/3/2021'","'D-03-01','172.16.34.51',' 2/3/2021'","'D-03-02','172.16.34.52',' 2/3/2021'","'D-03-03','172.16.34.53',' 2/3/2021'","'D-03-04','172.16.34.54',' n/s'","'D-03-05','172.16.34.55',' 2/3/2021'","'D-03-06','172.16.34.56',' n/s'","'D-03-07','172.16.34.57',' 2/3/2021'","'D-03-08','172.16.34.58',' need check back'","'D-03-09','172.16.34.59',' 2/3/2021'","'D-03-10','172.16.34.60',' 2/3/2021'","'D-05-01','172.16.34.31',' 2/3/2021'","'D-05-02','172.16.34.32',' 2/3/2021'","'D-05-03','172.16.34.33',' need check back'","'D-05-04','172.16.34.34',' 2/3/2021'","'D-05-05','172.16.34.35',' 2/3/2021'","'D-05-06','172.16.34.36',' 2/3/2021'","'D-05-07','172.16.34.37',' 2/3/2021'","'D-05-08','172.16.34.38',' n/s'","'D-05-09','172.16.34.39',' 2/3/2021'","'D-05-10','172.16.34.40',' 2/3/2021'","'D-07-01','172.16.34.11',' n/s'","'D-07-02','172.16.34.12',' need check back'","'D-07-03','172.16.34.13',' need check back'","'D-07-04','172.16.34.14',' 2/3/2021'","'D-07-05','172.16.34.15',' 2/3/2021'","'D-07-06','172.16.34.16',' 2/3/2021'","'D-07-07','172.16.34.17',' 2/3/2021'","'D-07-08','172.16.34.18',' 2/3/2021'","'D-07-09','172.16.34.19',' 2/3/2021'","'D-07-10','172.16.34.20',' 2/3/2021'","'D-09-01','172.16.34.151','3/3/2021'","'D-09-02','172.16.34.152','3/3/2021'","'D-09-03','172.16.34.153','3/3/2021'","'D-09-04','172.16.34.154','3/3/2021'","'D-09-05','172.16.34.155','3/3/2021'","'D-09-06','172.16.34.156','3/3/2021'","'D-09-07','172.16.34.157','n/s'","'D-09-08','172.16.34.158','3/3/2021'","'D-09-09','172.16.34.159','3/3/2021'","'D-09-10','172.16.34.160','3/3/2021'","'D-11-01','172.16.34.171','3/3/2021'","'D-11-02','172.16.34.172','n/s'","'D-11-03','172.16.34.173','3/3/2021'","'D-11-04','172.16.34.174','3/3/2021'","'D-11-05','172.16.34.175','3/3/2021'","'D-11-06','172.16.34.176','3/3/2021'","'D-11-07','172.16.34.177','3/3/2021'","'D-11-08','172.16.34.178','n/s'","'D-11-09','172.16.34.179','n/s'","'D-11-10','172.16.34.180','n/s'"];


$data_arr = ["'D-14-01','172.16.34.201'" ,"'D-14-02','172.16.34.202'" ,"'D-14-03','172.16.34.203'" ,"'D-14-04','172.16.34.204'" ,"'D-14-05','172.16.34.205'" ,"'D-14-06','172.16.34.206'" ,"'D-14-07','172.16.34.207'" ,"'D-14-08','172.16.34.208'" ,"'D-14-09','172.16.34.209'" ,"'D-14-10','172.16.34.210'" ,"'D-16-01','172.16.34.221'" ,"'D-16-02','172.16.34.222'" ,"'D-16-03','172.16.34.223'" ,"'D-16-04','172.16.34.224'" ,"'D-16-05','172.16.34.225'" ,"'D-16-06','172.16.34.226'" ,"'D-16-07','172.16.34.227'" ,"'D-16-08','172.16.34.228'" ,"'D-16-09','172.16.34.229'" ,"'D-16-10','172.16.34.230'" ,"'D-18-01','172.16.34.241'" ,"'D-18-02','172.16.34.242'" ,"'D-18-03','172.16.34.243'" ,"'D-18-04','172.16.34.244'" ,"'D-18-05','172.16.34.245'" ,"'D-18-06','172.16.34.246'" ,"'D-18-07','172.16.34.247'" ,"'D-18-08','172.16.34.248'" ,"'D-18-09','172.16.34.249'" ,"'D-18-10','172.16.34.250'" ,"'D-20-01','172.16.35.11'"  ,"'D-20-02','172.16.35.12'"  ,"'D-20-03','172.16.35.13'"  ,"'D-20-04','172.16.35.14'"  ,"'D-20-05','172.16.35.15'"  ,"'D-20-06','172.16.35.16'"  ,"'D-20-07','172.16.35.17'"  ,"'D-20-08','172.16.35.18'"  ,"'D-20-09','172.16.35.19'"  ,"'D-20-10','172.16.35.20'"  ,"'D-13-01','172.16.34.191'" ,"'D-13-02','172.16.34.192'" ,"'D-13-03','172.16.34.193'" ,"'D-13-04','172.16.34.194'" ,"'D-13-05','172.16.34.195'" ,"'D-13-06','172.16.34.196'" ,"'D-13-07','172.16.34.197'" ,"'D-13-08','172.16.34.198'" ,"'D-13-09','172.16.34.199'" ,"'D-13-10','172.16.34.200'" ,"'D-15-01','172.16.34.211'" ,"'D-15-02','172.16.34.212'" ,"'D-15-03','172.16.34.213'" ,"'D-15-04','172.16.34.214'" ,"'D-15-05','172.16.34.215'" ,"'D-15-06','172.16.34.216'" ,"'D-15-07','172.16.34.217'" ,"'D-15-08','172.16.34.218'" ,"'D-15-09','172.16.34.219'" ,"'D-15-10','172.16.34.220'" ,"'D-17-01','172.16.34.231'" ,"'D-17-02','172.16.34.232'" ,"'D-17-03','172.16.34.233'" ,"'D-17-04','172.16.34.234'" ,"'D-17-05','172.16.34.235'" ,"'D-17-06','172.16.34.236'" ,"'D-17-07','172.16.34.237'" ,"'D-17-08','172.16.34.238'" ,"'D-17-09','172.16.34.239'" ,"'D-17-10','172.16.34.240'" ,"'D-19-01','172.16.35.1'" ,"'D-19-02','172.16.35.2'" ,"'D-19-03','172.16.35.3'" ,"'D-19-04','172.16.35.4'" ,"'D-19-05','172.16.35.5'" ,"'D-19-06','172.16.35.6'" ,"'D-19-07','172.16.35.7'" ,"'D-19-08','172.16.35.8'" ,"'D-19-09','172.16.35.9'" ,"'D-19-10','172.16.35.10'"  ,"'D-21-01','172.16.35.21'"  ,"'D-21-02','172.16.35.22'"  ,"'D-21-03','172.16.35.23'"  ,"'D-21-04','172.16.35.24'"  ,"'D-21-05','172.16.35.25'"  ,"'D-21-06','172.16.35.26'"  ,"'D-21-07','172.16.35.27'"  ,"'D-21-08','172.16.35.28'"  ,"'D-21-09','172.16.35.29'"  ,"'D-21-10','172.16.35.30'"];
*/

/*$update_arr = ["'C-02-01','172.16.34.71', ' need check back'","'C-02-02','172.16.34.72', ' 3/3/2021'","'C-02-03','172.16.34.73', ' 3/3/2021'","'C-02-04','172.16.34.74', ' 3/3/2021'","'C-02-05','172.16.34.75', ' 3/3/2021'","'C-02-06','172.16.34.76', ' 3/3/2021'","'C-02-07','172.16.34.77', ' 3/3/2021'","'C-02-12','172.16.34.78', ' n/s'","'C-02-13','172.16.34.79', ' n/s'","'C-02-14','172.16.34.80', ' 3/3/2021'","'C-02-15','172.16.34.81', ' 1/3/2021'","'C-02-16','172.16.34.82', ' 3/3/2021'","'C-02-17','172.16.34.83', ' 3/3/2021'","'C-02-18','172.16.34.84', ' 1/3/2021'","'C-06-01','172.16.34.108', '3/3/2021'","'C-06-02','172.16.34.109', 'n/s'","'C-06-03','172.16.34.110', '3/3/2021'","'C-06-04','172.16.34.111', '3/3/2021'","'C-06-05','172.16.34.112', 'n/s'","'C-06-06','172.16.34.113', '3/3/2021'","'C-06-07','172.16.34.114', '3/3/2021'","'C-06-08','172.16.34.115', '3/3/2021'","'C-06-09','172.16.34.116', '3/3/2021'","'C-06-10','172.16.34.117', '3/3/2021'","'C-06-11','172.16.34.118', '3/3/2021'","'C-06-12','172.16.34.119', '3/3/2021'","'C-06-13','172.16.34.120', '3/3/2021'","'C-06-14','172.16.34.121', '3/3/2021'","'C-06-15','172.16.34.121', '3/3/2021'","'C-06-16','172.16.34.123', 'n/s'","'C-06-17','172.16.34.124', '3/3/2021'","'C-06-18','172.16.34.125', '3/3/2021'","'C-04-01','172.16.34.85', ' 3/3/2021'","'C-04-02','172.16.34.86', ' 3/3/2021'","'C-04-03','172.16.34.87', ' 3/3/2021'","'C-04-04','172.16.34.88', ' 3/3/2021'","'C-04-05','172.16.34.89', ' 3/3/2021'","'C-04-06','172.16.34.90', ' n/s'","'C-04-07','172.16.34.91', ' 3/3/2021'","'C-04-08','172.16.34.92', ' 3/3/2021'","'C-04-09','172.16.34.93', ' n/s'","'C-04-10','172.16.34.94', ' n/s'","'C-04-11','172.16.34.95', ' 3/3/2021'","'C-04-12','172.16.34.96', ' n/s'","'C-04-13','172.16.34.97', ' n/s'","'C-04-14','172.16.34.98', ' n/s'","'C-04-15','172.16.34.99', ' 3/3/2021'","'C-04-16','172.16.34.100', '3/3/2021'","'C-04-17','172.16.34.101', '3/3/2021'","'C-04-18','172.16.34.102', '3/3/2021'","'C-08-01','172.16.34.126', '3/3/2021'","'C-08-02','172.16.34.127', 'n/s'","'C-08-03','172.16.34.128', '3/3/2021'","'C-08-04','172.16.34.129', '3/3/2021'","'C-08-05','172.16.34.130', 'n/s'","'C-08-06','172.16.34.131', '3/3/2021'","'C-08-07','172.16.34.132', '3/3/2021'","'C-08-08','172.16.34.133', '3/3/2021'","'C-08-09','172.16.34.134', '3/3/2021'","'C-08-10','172.16.34.135', '3/3/2021'","'C-08-11','172.16.34.136', '3/3/2021'","'C-08-12','172.16.34.137', '3/3/2021'","'C-08-13','172.16.34.138', 'n/s'","'C-08-14','172.16.34.139', '3/3/2021'","'C-08-15','172.16.34.140', '3/3/2021'","'C-08-16','172.16.34.141', '3/3/2021'","'C-08-17','172.16.34.142', '3/3/2021'","'C-08-18','172.16.34.143', '3/3/2021'","'D-02-01','172.16.34.61', ' 2/3/2021'","'D-02-02','172.16.34.62', ' 2/3/2021'","'D-02-03','172.16.34.63', ' n/s'","'D-02-04','172.16.34.64', ' 2/3/2021'","'D-02-05','172.16.34.65', ' 2/3/2021'","'D-02-06','172.16.34.66', ' 2/3/2021'","'D-02-07','172.16.34.67', ' n/s'","'D-02-08','172.16.34.68', ' n/s'","'D-02-09','172.16.34.69', ' 2/3/2021'","'D-02-10','172.16.34.70', ' 2/3/2021'","'D-04-01','172.16.34.41', ' need check back'","'D-04-02','172.16.34.42', ' need check back'","'D-04-03','172.16.34.43', ' need check back'","'D-04-04','172.16.34.44', ' need check back'","'D-04-05','172.16.34.45', ' need check back'","'D-04-06','172.16.34.46', ' need check back'","'D-04-07','172.16.34.47', ' need check back'","'D-04-08','172.16.34.48', ' n/s'","'D-04-09','172.16.34.49', ' 2/3/2021'","'D-04-10','172.16.34.50', ' need check back'","'D-06-01','172.16.34.21', ' 2/3/2021'","'D-06-02','172.16.34.22', ' 2/3/2021'","'D-06-03','172.16.34.23', ' 2/3/2021'","'D-06-04','172.16.34.24', ' 2/3/2021'","'D-06-05','172.16.34.25', ' 2/3/2021'","'D-06-06','172.16.34.26', ' 2/3/2021'","'D-06-07','172.16.34.27', ' n/s'","'D-06-08','172.16.34.28', ' 2/3/2021'","'D-06-09','172.16.34.29', ' 2/3/2021'","'D-06-10','172.16.34.30', ' 2/3/2021'","'D-08-01','172.16.34.1', 'need check back'","'D-08-02','172.16.34.2', 'need check back'","'D-08-03','172.16.34.3', '2/3/2021'","'D-08-04','172.16.34.4', 'need check back'","'D-08-05','172.16.34.5', 'n/s'","'D-08-06','172.16.34.6', 'need check back'","'D-08-07','172.16.34.7', 'need check back'","'D-08-08','172.16.34.8', '2/3/2021'","'D-08-09','172.16.34.9', 'need check back'","'D-08-10','172.16.34.10', ' need check back'","'D-10-01','172.16.34.161', '3/3/2021'","'D-10-02','172.16.34.162', '3/3/2021'","'D-10-03','172.16.34.163', '3/3/2021'","'D-10-04','172.16.34.164', '3/3/2021'","'D-10-05','172.16.34.165', '3/3/2021'","'D-10-06','172.16.34.166', '3/3/2021'","'D-10-07','172.16.34.167', '3/3/2021'","'D-10-08','172.16.34.168', '3/3/2021'","'D-10-09','172.16.34.169', '3/3/2021'","'D-10-10','172.16.34.170', '3/3/2021'","'D-12-01','172.16.34.181', '3/3/2021'","'D-12-02','172.16.34.182', '3/3/2021'","'D-12-03','172.16.34.183', '3/3/2021'","'D-12-04','172.16.34.184', '3/3/2021'","'D-12-05','172.16.34.185', '3/3/2021'","'D-12-06','172.16.34.186', '3/3/2021'","'D-12-07','172.16.34.187', '3/3/2021'","'D-12-08','172.16.34.188', '3/3/2021'","'D-12-09','172.16.34.189', 'n/s'","'D-12-10','172.16.34.190', '3/3/2021'","'D-14-01','172.16.34.201', 'n/s'","'D-14-02','172.16.34.202', 'n/s'","'D-14-03','172.16.34.203', 'n/s'","'D-14-04','172.16.34.204', 'n/s'","'D-14-05','172.16.34.205', 'n/s'","'D-14-06','172.16.34.206', '4/3/2021'","'D-14-07','172.16.34.207', '4/3/2021'","'D-14-08','172.16.34.208', '4/3/2021'","'D-14-09','172.16.34.209', '4/3/2021'","'D-14-10','172.16.34.210', '4/3/2021'","'D-16-01','172.16.34.221', '4/3/2021'","'D-16-02','172.16.34.222', '4/3/2021'","'D-16-03','172.16.34.223', '4/3/2021'","'D-16-04','172.16.34.224', '4/3/2021'","'D-16-05','172.16.34.225', '4/3/2021'","'D-16-06','172.16.34.226', 'n/s'","'D-16-07','172.16.34.227', 'n/s'","'D-16-08','172.16.34.228', '4/3/2021'","'D-16-09','172.16.34.229', '4/3/2021'","'D-16-10','172.16.34.230', 'n/s'","'D-18-01','172.16.34.241', 'n/s'","'D-18-02','172.16.34.242', '4/3/2021'","'D-18-03','172.16.34.243', '4/3/2021'","'D-18-04','172.16.34.244', '4/3/2021'","'D-18-05','172.16.34.245', '4/3/2021'","'D-18-06','172.16.34.246', '4/3/2021'","'D-18-07','172.16.34.247', 'n/s'","'D-18-08','172.16.34.248', 'n/s'","'D-18-09','172.16.34.249', 'n/s'","'D-18-10','172.16.34.250', '4/3/2021'","'D-20-01','172.16.35.11', ' n/s'","'D-20-02','172.16.35.12', ' 4/3/2021'","'D-20-03','172.16.35.13', ' 4/3/2021'","'D-20-04','172.16.35.14', ' 4/3/2021'","'D-20-05','172.16.35.15', ' 4/3/2021'","'D-20-06','172.16.35.16', ' 4/3/2021'","'D-20-07','172.16.35.17', ' 4/3/2021'","'D-20-08','172.16.35.18', ' n/s'","'D-20-09','172.16.35.19', ' n/s'","'D-20-10','172.16.35.20', ' 4/3/2021'","'D-03-01','172.16.34.51', ' 2/3/2021'","'D-03-02','172.16.34.52', ' 2/3/2021'","'D-03-03','172.16.34.53', ' 2/3/2021'","'D-03-04','172.16.34.54', ' n/s'","'D-03-05','172.16.34.55', ' 2/3/2021'","'D-03-06','172.16.34.56', ' n/s'","'D-03-07','172.16.34.57', ' 2/3/2021'","'D-03-08','172.16.34.58', ' need check back'","'D-03-09','172.16.34.59', ' 2/3/2021'","'D-03-10','172.16.34.60', ' 2/3/2021'","'D-05-01','172.16.34.31', ' 2/3/2021'","'D-05-02','172.16.34.32', ' 2/3/2021'","'D-05-03','172.16.34.33', ' need check back'","'D-05-04','172.16.34.34', ' 2/3/2021'","'D-05-05','172.16.34.35', ' 2/3/2021'","'D-05-06','172.16.34.36', ' 2/3/2021'","'D-05-07','172.16.34.37', ' 2/3/2021'","'D-05-08','172.16.34.38', ' n/s'","'D-05-09','172.16.34.39', ' 2/3/2021'","'D-05-10','172.16.34.40', ' 2/3/2021'","'D-07-01','172.16.34.11', ' n/s'","'D-07-02','172.16.34.12', ' need check back'","'D-07-03','172.16.34.13', ' need check back'","'D-07-04','172.16.34.14', ' 2/3/2021'","'D-07-05','172.16.34.15', ' 2/3/2021'","'D-07-06','172.16.34.16', ' 2/3/2021'","'D-07-07','172.16.34.17', ' 2/3/2021'","'D-07-08','172.16.34.18', ' 2/3/2021'","'D-07-09','172.16.34.19', ' 2/3/2021'","'D-07-10','172.16.34.20', ' 2/3/2021'","'D-09-01','172.16.34.151', '3/3/2021'","'D-09-02','172.16.34.152', '3/3/2021'","'D-09-03','172.16.34.153', '3/3/2021'","'D-09-04','172.16.34.154', '3/3/2021'","'D-09-05','172.16.34.155', '3/3/2021'","'D-09-06','172.16.34.156', '3/3/2021'","'D-09-07','172.16.34.157', 'n/s'","'D-09-08','172.16.34.158', '3/3/2021'","'D-09-09','172.16.34.159', '3/3/2021'","'D-09-10','172.16.34.160', '3/3/2021'","'D-11-01','172.16.34.171', '3/3/2021'","'D-11-02','172.16.34.172', 'n/s'","'D-11-03','172.16.34.173', '3/3/2021'","'D-11-04','172.16.34.174', '3/3/2021'","'D-11-05','172.16.34.175', '3/3/2021'","'D-11-06','172.16.34.176', '3/3/2021'","'D-11-07','172.16.34.177', '3/3/2021'","'D-11-08','172.16.34.178', 'n/s'","'D-11-09','172.16.34.179', 'n/s'","'D-11-10','172.16.34.180', 'n/s'","'D-13-01','172.16.34.191', '4/3/2021'","'D-13-02','172.16.34.192', '4/3/2021'","'D-13-03','172.16.34.193', '4/3/2021'","'D-13-04','172.16.34.194', 'n/s'","'D-13-05','172.16.34.195', '4/3/2021'","'D-13-06','172.16.34.196', '4/3/2021'","'D-13-07','172.16.34.197', '4/3/2021'","'D-13-08','172.16.34.198', '4/3/2021'","'D-13-09','172.16.34.199', '4/3/2021'","'D-13-10','172.16.34.200', 'n/s'","'D-15-01','172.16.34.211', '4/3/2021'","'D-15-02','172.16.34.212', '4/3/2021'","'D-15-03','172.16.34.213', 'n/s'","'D-15-04','172.16.34.214', '4/3/2021'","'D-15-05','172.16.34.215', '4/3/2021'","'D-15-06','172.16.34.216', '4/3/2021'","'D-15-07','172.16.34.217', '4/3/2021'","'D-15-08','172.16.34.218', '4/3/2021'","'D-15-09','172.16.34.219', '4/3/2021'","'D-15-10','172.16.34.220', '4/3/2021'","'D-17-01','172.16.34.231', 'n/s'","'D-17-02','172.16.34.232', '4/3/2021'","'D-17-03','172.16.34.233', 'n/s'","'D-17-04','172.16.34.234', 'n/s'","'D-17-05','172.16.34.235', 'n/s'","'D-17-06','172.16.34.236', '4/3/2021'","'D-17-07','172.16.34.237', '4/3/2021'","'D-17-08','172.16.34.238', '4/3/2021'","'D-17-09','172.16.34.239', 'n/s'","'D-17-10','172.16.34.240', '4/3/2021'","'D-19-01','172.16.35.1', 'n/s'","'D-19-02','172.16.35.2', '4/3/2021'","'D-19-03','172.16.35.3', 'n/s'","'D-19-04','172.16.35.4', 'n/s'","'D-19-05','172.16.35.5', '4/3/2021'","'D-19-06','172.16.35.6', '4/3/2021'","'D-19-07','172.16.35.7', '4/3/2021'","'D-19-08','172.16.35.8', '4/3/2021'","'D-19-09','172.16.35.9', 'n/s'","'D-19-10','172.16.35.10', ' 4/3/2021'","'D-21-01','172.16.35.21', ' n/s'","'D-21-02','172.16.35.22', ' 4/3/2021'","'D-21-03','172.16.35.23', ' 4/3/2021'","'D-21-04','172.16.35.24', ' n/s'","'D-21-05','172.16.35.25', ' 4/3/2021'","'D-21-06','172.16.35.26', ' 4/3/2021'","'D-21-07','172.16.35.27', ' 4/3/2021'","'D-21-08','172.16.35.28', ' 4/3/2021'","'D-21-09','172.16.35.29', ' 4/3/2021'","'D-21-10','172.16.35.30', ' 4/3/2021'"];
*/
  $update_arr = ["'C-02-01','172.16.34.71', ' 10/3/2021'","'C-02-02','172.16.34.72', ' 3/3/2021'","'C-02-03','172.16.34.73', ' 3/3/2021'","'C-02-04','172.16.34.74', ' 3/3/2021'","'C-02-05','172.16.34.75', ' 3/3/2021'","'C-02-06','172.16.34.76', ' 3/3/2021'","'C-02-07','172.16.34.77', ' 3/3/2021'","'C-02-12','172.16.34.78', ' 10/3/2021'","'C-02-13','172.16.34.79', ' 10/3/2021'","'C-02-14','172.16.34.80', ' 3/3/2021'","'C-02-15','172.16.34.81', ' 1/3/2021'","'C-02-16','172.16.34.82', ' 3/3/2021'","'C-02-17','172.16.34.83', ' 3/3/2021'","'C-02-18','172.16.34.84', ' 1/3/2021'","'C-06-01','172.16.34.108', '3/3/2021'","'C-06-02','172.16.34.109', 'n/s'","'C-06-03','172.16.34.110', '3/3/2021'","'C-06-04','172.16.34.111', '3/3/2021'","'C-06-05','172.16.34.112', 'n/s'","'C-06-06','172.16.34.113', '3/3/2021'","'C-06-07','172.16.34.114', '3/3/2021'","'C-06-08','172.16.34.115', '3/3/2021'","'C-06-09','172.16.34.116', '3/3/2021'","'C-06-10','172.16.34.117', '3/3/2021'","'C-06-11','172.16.34.118', '3/3/2021'","'C-06-12','172.16.34.119', '3/3/2021'","'C-06-13','172.16.34.120', '3/3/2021'","'C-06-14','172.16.34.121', '3/3/2021'","'C-06-15','172.16.34.121', '3/3/2021'","'C-06-16','172.16.34.123', '3/3/2021'","'C-06-17','172.16.34.124', '3/3/2021'","'C-06-18','172.16.34.125', '3/3/2021'","'C-04-01','172.16.34.85', ' 3/3/2021'","'C-04-02','172.16.34.86', ' 3/3/2021'","'C-04-03','172.16.34.87', ' 3/3/2021'","'C-04-04','172.16.34.88', ' 3/3/2021'","'C-04-05','172.16.34.89', ' 3/3/2021'","'C-04-06','172.16.34.90', ' 15/3/2021'","'C-04-07','172.16.34.91', ' 3/3/2021'","'C-04-08','172.16.34.92', ' 3/3/2021'","'C-04-09','172.16.34.93', ' 15/3/2021'","'C-04-10','172.16.34.94', ' 15/3/2021'","'C-04-11','172.16.34.95', ' 3/3/2021'","'C-04-12','172.16.34.96', ' 15/3/2021'","'C-04-13','172.16.34.97', ' n/s'","'C-04-14','172.16.34.98', ' n/s'","'C-04-15','172.16.34.99', ' 3/3/2021'","'C-04-16','172.16.34.100', '3/3/2021'","'C-04-17','172.16.34.101', '3/3/2021'","'C-04-18','172.16.34.102', '3/3/2021'","'C-08-01','172.16.34.126', '3/3/2021'","'C-08-02','172.16.34.127', '3/3/2021'","'C-08-03','172.16.34.128', '3/3/2021'","'C-08-04','172.16.34.129', '3/3/2021'","'C-08-05','172.16.34.130', '3/3/2021'","'C-08-06','172.16.34.131', '3/3/2021'","'C-08-07','172.16.34.132', '3/3/2021'","'C-08-08','172.16.34.133', '3/3/2021'","'C-08-09','172.16.34.134', '3/3/2021'","'C-08-10','172.16.34.135', '3/3/2021'","'C-08-11','172.16.34.136', '3/3/2021'","'C-08-12','172.16.34.137', '3/3/2021'","'C-08-13','172.16.34.138', '15/3/2021'","'C-08-14','172.16.34.139', '3/3/2021'","'C-08-15','172.16.34.140', '3/3/2021'","'C-08-16','172.16.34.141', '3/3/2021'","'C-08-17','172.16.34.142', '3/3/2021'","'C-08-18','172.16.34.143', '3/3/2021'","'D-18-01','172.16.34.241', 'n/s'","'D-18-02','172.16.34.242', '4/3/2021'","'D-18-03','172.16.34.243', '4/3/2021'","'D-18-04','172.16.34.244', '4/3/2021'","'D-18-05','172.16.34.245', '4/3/2021'","'D-18-06','172.16.34.246', '4/3/2021'","'D-18-07','172.16.34.247', '4/3/2021'","'D-18-08','172.16.34.248', '4/3/2021'","'D-18-09','172.16.34.249', '4/3/2021'","'D-18-10','172.16.34.250', '4/3/2021'","'D-20-01','172.16.35.11', ' n/s'","'D-20-02','172.16.35.12', ' 4/3/2021'","'D-20-03','172.16.35.13', ' 4/3/2021'","'D-20-04','172.16.35.14', ' 4/3/2021'","'D-20-05','172.16.35.15', ' 4/3/2021'","'D-20-06','172.16.35.16', ' 4/3/2021'","'D-20-07','172.16.35.17', ' 4/3/2021'","'D-20-08','172.16.35.18', ' n/s'","'D-20-09','172.16.35.19', ' 4/3/2021'","'D-20-10','172.16.35.20', ' 4/3/2021'","'D-03-01','172.16.34.51', ' 2/3/2021'","'D-03-02','172.16.34.52', ' 2/3/2021'","'D-03-03','172.16.34.53', ' 2/3/2021'","'D-03-04','172.16.34.54', ' 15/3/2021'","'D-03-05','172.16.34.55', ' 2/3/2021'","'D-03-06','172.16.34.56', ' n/s'","'D-03-07','172.16.34.57', ' 2/3/2021'","'D-03-08','172.16.34.58', ' 10/3/2021'","'D-03-09','172.16.34.59', ' 2/3/2021'","'D-03-10','172.16.34.60', ' 2/3/2021'","'D-05-08','172.16.34.38', ' 15/3/2021'","'D-05-09','172.16.34.39', ' 2/3/2021'","'D-05-10','172.16.34.40', ' 2/3/2021'","'D-05-01','172.16.34.31', ' 2/3/2021'","'D-05-02','172.16.34.32', ' 2/3/2021'","'D-05-03','172.16.34.33', ' need check back'","'D-05-04','172.16.34.34', ' 2/3/2021'","'D-05-05','172.16.34.35', ' 2/3/2021'","'D-05-06','172.16.34.36', ' 2/3/2021'","'D-05-07','172.16.34.37', ' 2/3/2021'","'D-07-01','172.16.34.11', ' n/s'","'D-07-02','172.16.34.12', ' need check back'","'D-07-03','172.16.34.13', ' 15/3/2021'","'D-07-04','172.16.34.14', ' 2/3/2021'","'D-07-05','172.16.34.15', ' 2/3/2021'","'D-07-06','172.16.34.16', ' 2/3/2021'","'D-07-07','172.16.34.17', ' 2/3/2021'","'D-07-08','172.16.34.18', ' 2/3/2021'","'D-07-09','172.16.34.19', ' 2/3/2021'","'D-07-10','172.16.34.20', ' 2/3/2021'","'D-09-01','172.16.34.151', '3/3/2021'","'D-09-02','172.16.34.152', '3/3/2021'","'D-09-03','172.16.34.153', '3/3/2021'","'D-09-04','172.16.34.154', '3/3/2021'","'D-09-05','172.16.34.155', '3/3/2021'","'D-09-06','172.16.34.156', '3/3/2021'","'D-09-07','172.16.34.157', '15/3/2021'","'D-09-08','172.16.34.158', '3/3/2021'","'D-09-09','172.16.34.159', '3/3/2021'","'D-09-10','172.16.34.160', '3/3/2021'","'D-11-01','172.16.34.171', '3/3/2021'","'D-11-02','172.16.34.172', '15/3/2021'","'D-11-03','172.16.34.173', '3/3/2021'","'D-11-04','172.16.34.174', '3/3/2021'","'D-11-05','172.16.34.175', '3/3/2021'","'D-11-06','172.16.34.176', '3/3/2021'","'D-11-07','172.16.34.177', '3/3/2021'","'D-11-08','172.16.34.178', 'n/s'","'D-11-09','172.16.34.179', 'n/s'","'D-11-10','172.16.34.180', '15/3/2021'","'D-13-01','172.16.34.191', '4/3/2021'","'D-13-02','172.16.34.192', '4/3/2021'","'D-13-03','172.16.34.193', '4/3/2021'","'D-13-04','172.16.34.194', 'n/s'","'D-13-05','172.16.34.195', '4/3/2021'","'D-13-06','172.16.34.196', '4/3/2021'","'D-13-07','172.16.34.197', '4/3/2021'","'D-13-08','172.16.34.198', '4/3/2021'","'D-13-09','172.16.34.199', '4/3/2021'","'D-13-10','172.16.34.200', 'n/s'","'D-15-01','172.16.34.211', '4/3/2021'","'D-15-02','172.16.34.212', '4/3/2021'","'D-15-03','172.16.34.213', 'n/s'","'D-15-04','172.16.34.214', '4/3/2021'","'D-15-05','172.16.34.215', '4/3/2021'","'D-15-06','172.16.34.216', '4/3/2021'","'D-15-07','172.16.34.217', '4/3/2021'","'D-15-08','172.16.34.218', '4/3/2021'","'D-15-09','172.16.34.219', '4/3/2021'","'D-15-10','172.16.34.220', '4/3/2021'","'D-17-01','172.16.34.231', '4/3/2021'","'D-17-02','172.16.34.232', '4/3/2021'","'D-17-03','172.16.34.233', '4/3/2021'","'D-17-04','172.16.34.234', '4/3/2021'","'D-17-05','172.16.34.235', '4/3/2021'","'D-17-06','172.16.34.236', '4/3/2021'","'D-17-07','172.16.34.237', '4/3/2021'","'D-17-08','172.16.34.238', '4/3/2021'","'D-17-09','172.16.34.239', '4/3/2021'","'D-17-10','172.16.34.240', '4/3/2021'","'D-21-01','172.16.35.21', ' 4/3/2021'","'D-21-02','172.16.35.22', ' 4/3/2021'","'D-21-03','172.16.35.23', ' 4/3/2021'","'D-21-04','172.16.35.24', ' 4/3/2021'","'D-21-05','172.16.35.25', ' 4/3/2021'","'D-21-06','172.16.35.26', ' 4/3/2021'","'D-21-07','172.16.35.27', ' 4/3/2021'","'D-21-08','172.16.35.28', ' 4/3/2021'","'D-21-09','172.16.35.29', ' 4/3/2021'","'D-21-10','172.16.35.30', ' 4/3/2021'","'D-19-01','172.16.35.1', '4/3/2021'","'D-19-02','172.16.35.2', '4/3/2021'","'D-19-03','172.16.35.3', '4/3/2021'","'D-19-04','172.16.35.4', '4/3/2021'","'D-19-05','172.16.35.5', '4/3/2021'","'D-19-06','172.16.35.6', '4/3/2021'","'D-19-07','172.16.35.7', '4/3/2021'","'D-19-08','172.16.35.8', '4/3/2021'","'D-19-09','172.16.35.9', '4/3/2021'","'D-19-10','172.16.35.10', ' 4/3/2021'","'D-02-01','172.16.34.61', ' 2/3/2021'","'D-02-02','172.16.34.62', ' 2/3/2021'","'D-02-03','172.16.34.63', ' 15/3/2021'","'D-02-04','172.16.34.64', ' 2/3/2021'","'D-02-05','172.16.34.65', ' 2/3/2021'","'D-02-06','172.16.34.66', ' 2/3/2021'","'D-02-07','172.16.34.67', ' 15/3/2021'","'D-02-08','172.16.34.68', ' 15/3/2021'","'D-02-09','172.16.34.69', ' 2/3/2021'","'D-02-10','172.16.34.70', ' 2/3/2021'","'D-04-01','172.16.34.41', ' need check back'","'D-04-02','172.16.34.42', ' need check back'","'D-04-03','172.16.34.43', ' need check back'","'D-04-04','172.16.34.44', ' 10/3/2021'","'D-04-05','172.16.34.45', ' 10/3/2021'","'D-04-06','172.16.34.46', ' 10/3/2021'","'D-04-07','172.16.34.47', ' 10/3/2021'","'D-04-08','172.16.34.48', ' 10/3/2021'","'D-04-09','172.16.34.49', ' 2/3/2021'","'D-04-10','172.16.34.50', ' 10/3/2021'","'D-06-01','172.16.34.21', ' 2/3/2021'","'D-06-02','172.16.34.22', ' 2/3/2021'","'D-06-03','172.16.34.23', ' 2/3/2021'","'D-06-04','172.16.34.24', ' 2/3/2021'","'D-06-05','172.16.34.25', ' 2/3/2021'","'D-06-06','172.16.34.26', ' 2/3/2021'","'D-06-07','172.16.34.27', ' 15/3/2021'","'D-06-08','172.16.34.28', ' 2/3/2021'","'D-06-09','172.16.34.29', ' 2/3/2021'","'D-06-10','172.16.34.30', ' 2/3/2021'","'D-08-01','172.16.34.1', 'need check back'","'D-08-02','172.16.34.2', 'need check back'","'D-08-03','172.16.34.3', '2/3/2021'","'D-08-04','172.16.34.4', 'need check back'","'D-08-05','172.16.34.5', 'n/s'","'D-08-06','172.16.34.6', 'need check back'","'D-08-07','172.16.34.7', 'need check back'","'D-08-08','172.16.34.8', '2/3/2021'","'D-08-09','172.16.34.9', 'need check back'","'D-08-10','172.16.34.10', ' need check back'","'D-10-01','172.16.34.161', '3/3/2021'","'D-10-02','172.16.34.162', '3/3/2021'","'D-10-03','172.16.34.163', '3/3/2021'","'D-10-04','172.16.34.164', '3/3/2021'","'D-10-05','172.16.34.165', '3/3/2021'","'D-10-06','172.16.34.166', '3/3/2021'","'D-10-07','172.16.34.167', '3/3/2021'","'D-10-08','172.16.34.168', '3/3/2021'","'D-10-09','172.16.34.169', '3/3/2021'","'D-10-10','172.16.34.170', '3/3/2021'","'D-14-01','172.16.34.201', 'n/s'","'D-14-02','172.16.34.202', 'n/s'","'D-14-03','172.16.34.203', 'n/s'","'D-14-04','172.16.34.204', 'n/s'","'D-14-05','172.16.34.205', 'n/s'","'D-14-06','172.16.34.206', '4/3/2021'","'D-14-07','172.16.34.207', '4/3/2021'","'D-14-08','172.16.34.208', '4/3/2021'","'D-14-09','172.16.34.209', '4/3/2021'","'D-14-10','172.16.34.210', '4/3/2021'","'D-12-01','172.16.34.181', '3/3/2021'","'D-12-02','172.16.34.182', '3/3/2021'","'D-12-03','172.16.34.183', '3/3/2021'","'D-12-04','172.16.34.184', '3/3/2021'","'D-12-05','172.16.34.185', '3/3/2021'","'D-12-06','172.16.34.186', '3/3/2021'","'D-12-07','172.16.34.187', '3/3/2021'","'D-12-08','172.16.34.188', '3/3/2021'","'D-12-09','172.16.34.189', 'n/s'","'D-12-10','172.16.34.190', '3/3/2021'","'D-16-01','172.16.34.221', '4/3/2021'","'D-16-02','172.16.34.222', '4/3/2021'","'D-16-03','172.16.34.223', '4/3/2021'","'D-16-04','172.16.34.224', '4/3/2021'","'D-16-05','172.16.34.225', '4/3/2021'","'D-16-06','172.16.34.226', 'n/s'","'D-16-07','172.16.34.227', '4/3/2021'","'D-16-08','172.16.34.228', '4/3/2021'","'D-16-09','172.16.34.229', '4/3/2021'","'D-16-10','172.16.34.230', '4/3/2021'"];
  $fail_status = ['need check back', 'n/s' , 0];
    $input = array();
     $second_data = array();
  $mapper = [0=>'unit' , 1=>'ip_address' , 2=>'status'];
  foreach($update_arr as $item)
  {
    $temp_1 = explode("," ,$item);
    //dd($temp_1);
    foreach ($temp_1 as $mapper_key => $meter_value)
    {
      $temp_1[0] = str_replace("'", "", $temp_1[0]);
      $meter_value = trim(str_replace("'", "", $meter_value));
      $input[$temp_1[0]][$mapper[$mapper_key]] = $meter_value;
    }
    
  }
  //dd($input);

/*  foreach($data_arr as $item)
  {
    $temp_1 = explode("," ,$item);
    //dd($temp_1);
    foreach ($temp_1 as $mapper_key => $meter_value)
    {
      $temp_1[0] = str_replace("'", "", $temp_1[0]);
      $meter_value = trim(str_replace("'", "", $meter_value));
      $input[$temp_1[0]][$mapper[$mapper_key]] = $meter_value;
    }

    $input[$temp_1[0]]['status'] = 0;
    
  }*/
//dd($input);
  //dd($input);
/*dd($input);

  $input = arrey_merge($input , $second_data);*/
       $house_data = [];

       $bulk_upload_data = array();

      $house_room_listing = array_slice( Room::findByLeafGroupId(Company ::get_group_id()) , 0 , 100 );
      foreach($house_room_listing as $house)
      { $house = (array) $house;
          foreach ($input as $unit_name => $value)
          {//dd($unit_name);
              if($house['house_unit'] == $unit_name)
              {//dd('x');

                  $command_value = (int) ( '10'.((int) $house['house_room_name'] - 1));
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['unit_id'] = 1 ;
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['on_value'] = 0 ;
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['off_value'] = 1 ;
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['reference_no'] = $command_value ;
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['relay_controller_ip_address'] = $value['ip_address'] ; 
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['modbus_command'] = $command_value ;
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['is_remote_ready'] = in_array($value['status'], $fail_status) ? false : true;
                  $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['remote_status_comment'] = '';
                   $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['leaf_room_id'] = $house['leaf_room_id'];
                  if( $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['is_remote_ready'] == 0)
                  {
                    $bulk_upload_data['bulk_upload'][$house['leaf_room_id']]['remote_status_comment'] = 'Cabling and network issue , configuration in progress.';
                  }
                  
                  break;
              } 
          }
      }
//dd('end');

       $return = MeterRegister::saveOrUpdateMeterRegistersRemoteControl($bulk_upload_data);
       dd($return);
  });
   
/*foreach()



house_unit


  +"unit_id": 0
    +"reference_no": 0
    +"": null
    +"meter_relay_test_id": 0
    +"internal_id_house": 0
    +"": "C-02-01")

for*/



Route::get('populatePowerMeterReport', function(){
  
  ini_set('max_execution_time', 3000000); 
  $user_listing = User::all();
  $update_counter = 0;

  if(Company::get_group_id() == 0)
  {
    dd('Please access the power meter portal before update the report data.');
  }
    
    
  foreach($user_listing as $user)
  { //dd($user);
    $update_counter ++;
    if($update_counter == 10)
    {
      //break;
    }
          /*if($user['leaf_group_id'] != 255 )
          {
            continue;
          }
*/
        $result_data[$user['leaf_id_user']] = array();
        $result_data[$user['leaf_id_user']]['is_app_user'] = false;
        $email  = $user['email'];

        $room;
        $api  = new LeafAPI();
        $leaf_group_id = Setting::SUNWAY_GROUP_ID;
        $company = Company::get_model_by_leaf_group_id($leaf_group_id);
        Setting::setCompany($leaf_group_id);
        $report_title     =   "User Account Summary";
        $new_converted_single_room_aug_staff_id_house_member_arr = array(314212,314207,314241);
        $new_converted_single_room_aug_staff_id_arr = array(16083,30763,16167,18446,16164,16090,16178,16083,16192,16166,16088,16081,31126,16094,16224,16170,24806,16082);
        $converted_single_room_aug_staff_id_arr = array(21869,22764, 21853,21853,26491,21948,25262,19006,18618,18505,30440,22677,18241,22842,22678,21546,25208,19973,20121,21457,26467,18688,20944,20692,20239,25139,18302,20085,19964,19980,19966,19971,18867);
        $converted_twin_room_aug_staff_id_arr = array(18125,19275,19785,18187,26298,18121,18108,20673,26842,20098,20662,18699,29876,25901,19947,21497,17340,18544,18649,16096);
        $staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);
        $change_room_arry = array(21908);
        $change_room_arry_2020_04_21 = array(44468);
        $change_room_arry_2020_10_7 = array(16316);
        $remove_previous_record = array(18618);
        $new_rearrangement_record_2019_08_01 = array(16111);
        $twin_room_later_move_in_adjustment_2019_07_05 = array(31440);
        $payment_adjustment_user = array(19971);
        $twin_room_adjustment_2019_03_01 = array(16170,16173);
        
        $carry_credit_2020_06_10_42_32 = array(18618);
        $c2_1_1_july_2020_adjustment = array(38490);
        $twin_adjustment_2019_08_01 = array(39171);
        $remove_previous_payment_2019_08_01_user = array(19006);
        
        $c4_1_4_remove_payment_adjustment_2020_10_17 = array(16092);

        $result   = $api->get_user_by_email($email);
        if($result['status_code'] == -1){
          $result_data[$user['leaf_id_user']]['msg'] = 'Invalid Email';
          continue;
        }

        //$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
        $user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
        //dd($user_detail);
        if($user_detail['leaf_room_id'] == 0){
          $result_data[$user['leaf_id_user']]['msg'] = 'No stay at room';
        }


        if(Company::get_group_id() == 0){
          //setcookie(LeafAPI::label_session_token, $this->session_token);
        }


        if(!isset($user_detail['member_detail']['id_house_member']))
        {
          continue;
        }
        
        $room_listing = LeafAPI::get_all_stayed_room_by_id_house_member($user_detail['member_detail']['id_house_member']);

            $model        = new User();
            $setting      = new Setting();


            if (!$result['status_code']) {
                $result_data[$user['leaf_id_user']]['msg'] = 'User Not Found';
            } else {
                $user = $model->get_or_create_user_account($result);
                Auth::loginUsingId($user->id, true);
                $data['status']         =   true;
                $data['status_msg']     =   'Authorization successfully.';

            }

            $meter_register_model;
            foreach ($room_listing as $stay_room) {
              if($stay_room['house_room_member_deleted'] == true){
                continue;
              }
              $room = $stay_room ;
              $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=',$room['id_house_room'])->first();
            }
        
        if(!isset($meter_register_model)){
            $result_data[$user['leaf_id_user']]['msg'] = 'Meter Not Found';
            continue;
        }
        
        if(!isset($meter_register_model->id)){
            $result_data[$user['leaf_id_user']]['msg'] = 'Meter Not Found';
            continue;
        }

        //new code  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $user_profile               = $user;
            $user_profile['account_no']   = $meter_register_model->account_no;
        $user_profile['address']      = $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
        $user_profile_string        = json_encode($user_profile);   
        $is_allow_to_pay      = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

        $date_started = "";

        if(in_array($user_profile['leaf_id_user'], $c4_1_4_remove_payment_adjustment_2020_10_17)){
          $date_started = '2020-10-20';

        }else if(in_array($user_profile['leaf_id_user'], $twin_adjustment_2019_08_01)){
          $date_started = '2019-08-01';

        }else if(in_array($user_profile['leaf_id_user'], $twin_room_adjustment_2019_03_01)){
          $date_started = '2019-08-01';
        
        }else if(in_array($user_profile['leaf_id_user'], $twin_room_later_move_in_adjustment_2019_07_05)){
          $date_started = '2019-07-05';
          
        
        }else if(in_array($user_profile['leaf_id_user'], $change_room_arry_2020_10_7)){
          $date_started = '2020-10-07';
          
        
        }else if(in_array($user_profile['leaf_id_user'], $new_rearrangement_record_2019_08_01)){
          $date_started = '2019-08-01';
          
        
        }else if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
          $date_started = '2020-01-03';
          
        }else if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21)){
          $date_started = '2020-04-21';
        }else if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
          $date_started = '2019-04-01';
        }else if(in_array($user_profile['leaf_id_user'],$converted_single_room_aug_staff_id_arr)){
          $date_started = '2019-08-01';
        }else if(in_array($user_profile['leaf_id_user'],$new_converted_single_room_aug_staff_id_arr)){
          $date_started = '2019-08-01';
        }else if(in_array($user_profile['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
          $date_started = '2019-08-01';
        }else if(in_array($user_profile['leaf_id_user'],$converted_twin_room_aug_staff_id_arr)){
          $date_started = '2019-08-01';
        }else if($is_allow_to_pay == false){

          $date_started = $user_detail['member_detail']['house_room_member_start_date'];
          if(($date_started < Company::get_system_live_date($leaf_group_id)) == true){
            $date_started = Company::get_system_live_date($leaf_group_id);
          }
            
          if($date_started == ""){
            $date_started = Company::get_system_live_date($leaf_group_id);
          }
          
          
        }else{
          $date_started = $room['house_room_member_start_date'];
          if($date_started == ""){
            $date_started = '2019-03-01';
          }
        }
        
        //Recheck all paid and unpaid item
        Utransaction::update_utransaction_by_current_day_interval_leaf_user_id_or_all(300,$user_profile['leaf_id_user']);

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
      
        $date_range   = array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
      
        if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
          $date_range['date_started'] = '2020-06-10';
        
        }

        $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment_second( $room['id_house_room'] , $date_range);


        if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
          //echo "Twin scenario : <br>";
          $user_stay_detail = $api->get_user_stay_detail_for_twin_room_by_leaf_room_id($user_detail['leaf_room_id']);
          $user_stay_detail['date_range'] = $date_range;
          $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_user_start_stay_detail_all($user_stay_detail,$leaf_group_id);
        }else{
          $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
        }
        $subsidy_listing  = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
        //dd($payment_received_listing);
        $to_removed = array();
        $counter = 0 ;
        if(count($payment_received_listing) > 0){
                foreach ($payment_received_listing as $row) {
            
            
            if(in_array($user_profile['leaf_id_user'],$c4_1_4_remove_payment_adjustment_2020_10_17)){
              
              if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2020-11-01')) == true){
                array_push($to_removed,$counter);
                $counter++;
        
                continue;
              }else{
                $counter++;

              }
            
            }
            
            if(in_array($user_profile['leaf_id_user'],$remove_previous_payment_2019_08_01_user)){
              
              if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2019-08-01')) == true){
                array_push($to_removed,$counter);
                $counter++;
        
                continue;
              }else{
                $counter++;

              }
            
            }
            
            
            if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
              if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-06-10'){
                continue;
              }

            }
            
            if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_10_7)){
              if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-10-07'){
                continue;
              }

            }
            

            if(in_array($user_profile['leaf_id_user'],$payment_adjustment_user)){
              
              if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2019-07-17')) == true){
                array_push($to_removed,$counter);
                $counter++;
                continue;
              }else{
                $counter++;
              }
            }
            
            if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
              if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-07-01'){
                continue;
              }

            }
            
              
            if(in_array($user_profile['leaf_id_user'],$change_room_arry) || in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21) || in_array($user_profile['leaf_id_user'],$remove_previous_record)  ){
      
              if(date("Y-m-d", strtotime($row['document_date'])) <  $date_range['date_started']){
                continue;
              }
              
            }

                    $account_status['total_paid_amount'] += $row['total_amount'];

                }   
          }
        if(count($to_removed) > 0)
        {
          foreach($to_removed as $key => $value){ 
            unset($payment_received_listing[$value]);
          }
        }

        
        if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
          $account_status['total_paid_amount'] += 122;
        }


        if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_10_7)){
          $account_status['total_paid_amount'] += 44.04;
        }

        if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){ 
            $account_status['total_paid_amount'] += 42.32;
        }

        //Get statistic 

        $statistic['currentUsageKwh'] =  count($account_status['month_usage_summary']) > 0 ? $account_status['month_usage_summary'][count($account_status['month_usage_summary'])-1]['total_usage_kwh'] : 0; 
        $statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
        $statistic['balanceAmount'] = $account_status['total_paid_amount'] + $account_status['total_subsidy_amount'] -  $account_status['total_payable_amount'];  
        if($statistic['balanceAmount'] > 0 ){
             $statistic['currentBalanceKwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['currentUsageKwh'] , $statistic['balanceAmount']);
        }else{
            $statistic['currentBalanceKwh'] = 0;
        }

        if($user_profile['leaf_id_user']== '22764' ){
          $statistic['balanceAmount'] = $statistic['balanceAmount'] - 78.04 ;
        }
        //Get statistic 
        session(['statistic' =>  $statistic]);
        $last_reading_date_time     = date('jS F Y h:00 A', strtotime('+8 hours'));
            $month_usage_listing =    $account_status['month_usage_summary'];
      
        foreach($room_listing as $room){
          //echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
        }
        
        //echo 'date_range : '.json_encode($date_range)."<br>";
        
        //echo "Leaf id user :".$user_profile['leaf_id_user']."<br>";
        $result_data[$user['leaf_id_user']]['email']  = $user['email'];
        $result_data[$user['leaf_id_user']]['house_name'] = $room['house_unit'].'['.ucfirst($user_detail['house_room_type']).' room ]';
        $result_data[$user['leaf_id_user']]['customer_name'] = $user['fullname'];
        $result_data[$user['leaf_id_user']]['is_app_user'] = true;
        $result_data[$user['leaf_id_user']]['data'] = [
                                  'room_type' => $user_detail['house_room_type'] ,
                                  'date_range' => $date_range,
                                  'payment_received_listing' => json_encode($payment_received_listing) ,
                                  'subsidy_listing' =>  json_encode($subsidy_listing),
                                  'month_usage_listing' => json_encode($month_usage_listing) ,
                                  'latest_data' => json_encode($statistic)
                                ];
        
          
        //return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
        
      }
      
  //UserAccountSummaryData::truncate();   

  foreach($result_data as $index => $data)
  {
    //if($data') 
    if($data['is_app_user'] == false){
      continue;
    }
    
    $data['leaf_id_user'] = $index;
    UserAccountSummaryData::save_report_data($data);
  }

  dd('Data successfully update.');
    
});

Route::get('tempTest4', function ()
{ 
  
   $pid = '5112163327084615d77ec0135bf2bfe0';
    $description = 'Prepaid Reload For Electricity Bill';
        $amount = 1;
        $pg = 'ipay88_sunwaymonash';
$name ="Goh Khai Yet";
 $email = 'adelfried1227a886@gmail.com';
   $la = new LeafAPI();

   $x =$la->get_prepare_payment_universal($pg,$description,$amount ,$name , $email , null , null);
   dd($x);
  /* $c_model= CustomerPowerUsageSummary::find(1);

   $x =MeterPaymentReceived::saveOrUpdateModelByUtransactionModel($u_model , $c_model);
   dd($x);*/
}); 

Route::get('utTest', function ()
{ 
  
   $pid = '546a570d19423e5f94a02b1b4cc4d647';
   $u_model = Utransaction::saveOrUpdatePwUtransaction($pid);
  
   //$c_model= CustomerPowerUsageSummary::find(18);
   $c_model= CustomerPowerUsageSummary::find(7);
   //dd($u_model);
   $x =MeterPaymentReceived::saveOrUpdateModelByUtransactionModel($u_model , $c_model);
   dd($x);
}); 

Route::get('tempTest2', function ()
{ 
  
   $pid = '5112163327084615d77ec0135bf2bfe0';
   $u_model = Utransaction::saveOrUpdatePwUtransaction($pid);
   dd($u_model);
}); 

Route::get('tempTest', function ()
{ 
  
    $language_to_convert = ['Name','Tenant Detail','Check In Date','Total Consumption (kwh)','Reading Monitoring','Item','Last Reading At','Last Current Reading','Monthly Usage Reading','Meter register Detail','Meter ID','IP','Contact No','Created At','Meter Register Status','Save','No Meter Found','Register New Meter','Room History','Start Date','End Date','Total Consumption (Kwh)','Charges (RM)'];

    foreach($language_to_convert as $language_value)
    {
      //str_replace( ' ', '_' ,$language_value)
        $language[$language_value] = Language::trans($language_value);
    }
    dd($language);
}); 

Route::get('meterUsageTest', function ()
{ 
  Setting::setCompany(282);
  ini_set('max_execution_time', 300000);
  $test_kwh = 544;
  $current_balance = 0;

  $method_return = UtilityCharge::calculate_utility_fee($test_kwh  , 522);
  $setting_return = Setting::calculate_utility_fee($test_kwh );
  echo 'Setting :'.json_encode($setting_return)."<br>";
  echo 'Method :'.json_encode($method_return)."<br>";
  dd('Done');
  
}); 

Route::get('meterBalanceTest', function ()
{ 
  Setting::setCompany(282);
  ini_set('max_execution_time', 300000);
  $test_kwh = 544;
  $current_balance = 0;

  $method_return = UtilityCharge::convert_balance_to_kwh_by_current_usage_and_balance($test_kwh , $current_balance , 522);
  $setting_return = Setting::convert_balance_to_kwh_by_current_usage_and_balance($test_kwh , $current_balance);
  echo 'Setting :'.json_encode($setting_return)."<br>";
  echo 'Method :'.json_encode($method_return)."<br>";
  dd('Done');
  
}); 




Route::get('newUtransaction', function ()
{ 
  ini_set('max_execution_time', 300000);
  $leaf_payment_id ='5112163327084615d77ec0135bf2bfe0';
  $umodel = UTransaction::saveOrUpdatePwUtransaction($leaf_payment_id);
}); 


/*


 "status_code" => 1
  "id_payment" => "5112163327084615d77ec0135bf2bfe0"
  "payment_reference" => "18922"
  "id_user" => "0"
  "id_house" => "0"
  "id_house_member" => "0"
  "id_group" => "0"
  "payment_identifier" => "T041067932420"
  "payment_service" => "ipay88_sunmed"
  "payment_method" => ""
  "payment_entry_date" => "2020-12-29 14:02:29"
  "payment_currency_code" => "MYR"
  "payment_total_amount" => "1"
  "payment_tax_amount" => "0"
  "payment_tax_name" => ""
  "payment_transaction_fee_amount" => "0"
  "payment_item_name" => "Prepaid Reload For Electricity Bill"
  "payment_items" => []
  "payment_customer_name" => "Lee Weng Choun"
  "payment_customer_email" => "leewenc@sunway.com.my"
  "payment_customer_phone" => ""
  "payment_account_holder_name" => "Sunmed Residential IPay88 Account"
  "payment_account_number" => "00000000"
  "payment_paid" => true
  "payment_paid_date" => "2020-12-29 14:03:57"
  "payment_page_url" => "http://payment.sunwaymedical.com/web/payment-prepare.php?type=api&inapp=0&paymentid=5112163327084615d77ec0135bf2bfe0"
  "payment_confirm_page_url" => "http://payment.sunwaymedical.com/web/payment-prepare.php?type=confirm&inapp=0&paymentid=5112163327084615d77ec0135bf2bfe0"
  "payment_success_url" => "http://13.251.20.181/leaf_webview/public/index.php/utility_charges/dashboard/redirect/payment-gateway?status=up&paymentid=5112163327084615d77ec0135bf2bfe0"
  "payment_cancel_url" => "http://13.251.20.181/leaf_webview/public/index.php/utility_charges/dashboard/top/up?status=down&session_token=UBBPDz7toTLZhyfhvwQtyTKTy54GqyL4&paymentid=5112163 ▶"
  "payment_is_sandbox" => "1"
  "payment_is_tax_inclusive" => 1
  "payment_receipt_url" => ""
  "payment_is_brought_to_pay" => "1"
  "payment_brought_to_pay_date" => "2020-12-29 14:02:30"
  "payment_requery_response_raw" => "2020-12-29 14:03:57: 00"   */

Route::get('paymentMethodTest', function ()
{ 
  dd(Setting::getPaymentGatewayAccountHolderName(Setting::CODE_LEAF_IPAY88));
}); 







Route::get('createMeterRegisterx', function ()
{ 


$data = array("'D-09-01','172.16.32.151','iEM3155',1,151,'E207D5EE'","'D-09-01','172.16.32.151','iEM3155',2,153,'E207D5E9'","'D-09-01','172.16.32.151','iEM3155',3,150,'E207D5E8'","'D-09-01','172.16.32.151','iEM3155',4,152,'E207D5F3'","'D-09-01','172.16.32.151','iEM3155',5,154,'E207D5FD'","'D-09-01','172.16.32.151','iEM3155',6,155,'E207D5FB'","'D-09-02','172.16.32.152','iEM3155',1,151,'E207D60F'","'D-09-02','172.16.32.152','iEM3155',2,150,'E207D5EB'","'D-09-02','172.16.32.152','iEM3155',3,152,'E207D5F1'","'D-09-02','172.16.32.152','iEM3155',4,155,'E207D612'","'D-09-02','172.16.32.152','iEM3155',5,154,'E207D610'","'D-09-02','172.16.32.152','iEM3155',6,153,'E207D609'","'D-09-03','172.16.32.153','iEM3155',1,150,'E207DDB2'","'D-09-03','172.16.32.153','iEM3155',2,152,'E207DDB6'","'D-09-03','172.16.32.153','iEM3155',3,154,'E207DDC1'","'D-09-03','172.16.32.153','iEM3155',4,153,'E207DDDD'","'D-09-03','172.16.32.153','iEM3155',5,155,'E207DDAD'","'D-09-03','172.16.32.153','iEM3155',6,151,'E207DDBE'","'D-09-04','172.16.32.154','iEM3155',1,153,'E207D611'","'D-09-04','172.16.32.154','iEM3155',2,155,'E207D5F4'","'D-09-04','172.16.32.154','iEM3155',3,154,'E207D601'","'D-09-04','172.16.32.154','iEM3155',4,151,'E207D605'","'D-09-04','172.16.32.154','iEM3155',5,152,'E207D5ED'","'D-09-04','172.16.32.154','iEM3155',6,150,'E207D5F9'","'D-09-05','172.16.32.155','iEM3155',1,153,'E207D607'","'D-09-05','172.16.32.155','iEM3155',2,150,'E207D608'","'D-09-05','172.16.32.155','iEM3155',3,151,'E207D606'","'D-09-05','172.16.32.155','iEM3155',4,154,'E207D602'","'D-09-05','172.16.32.155','iEM3155',5,155,'E207D5EC'","'D-09-05','172.16.32.155','iEM3155',6,152,'E207D617'","'D-09-06','172.16.32.156','iEM3155',1,159,'E207DDD4'","'D-09-06','172.16.32.156','iEM3155',2,156,'E207D5F6'","'D-09-06','172.16.32.156','iEM3155',3,157,'E207DDDC'","'D-09-06','172.16.32.156','iEM3155',4,158,'E207DDCE'","'D-09-06','172.16.32.156','iEM3155',5,155,'E207DDCF'","'D-09-06','172.16.32.156','iEM3155',6,150,'E207DDC5'","'D-09-08','172.16.32.158','iEM3155',1,152,'E207DC06'","'D-09-08','172.16.32.158','iEM3155',2,154,'E207DBFB'","'D-09-08','172.16.32.158','iEM3155',3,155,'E207D60B'","'D-09-08','172.16.32.158','iEM3155',4,150,'E207DBFF'","'D-09-08','172.16.32.158','iEM3155',5,151,'E207D615'","'D-09-08','172.16.32.158','iEM3155',6,153,'E207DC13'","'D-09-10','172.16.32.160','iEM3155',1,153,'E207DDCB'","'D-09-10','172.16.32.160','iEM3155',2,155,'E207DDD5'","'D-09-10','172.16.32.160','iEM3155',3,152,'E207D0DB'","'D-09-10','172.16.32.160','iEM3155',4,151,'E207DDD1'","'D-09-10','172.16.32.160','iEM3155',5,150,'E207D0C9'","'D-09-10','172.16.32.160','iEM3155',6,154,'E207D0C4'","'D-10-01','172.16.32.161','iEM3155',1,154,'E207D61B'","'D-10-01','172.16.32.161','iEM3155',2,153,'E207D613'","'D-10-01','172.16.32.161','iEM3155',3,152,'E207D5F8'","'D-10-01','172.16.32.161','iEM3155',4,155,'E207D5EA'","'D-10-01','172.16.32.161','iEM3155',5,150,'E207D60C'","'D-10-01','172.16.32.161','iEM3155',6,151,'E207D61A'","'D-10-02','172.16.32.162','iEM3155',1,152,'E207D0B9'","'D-10-02','172.16.32.162','iEM3155',2,150,'E207D0D8'","'D-10-02','172.16.32.162','iEM3155',3,151,'E207D0D3'","'D-10-02','172.16.32.162','iEM3155',4,153,'E207D0D9'","'D-10-02','172.16.32.162','iEM3155',5,156,'E207D0BF'","'D-10-02','172.16.32.162','iEM3155',6,155,'E207D0CB'","'D-10-03','172.16.32.163','iEM3155',1,152,'E207DDD6'","'D-10-03','172.16.32.163','iEM3155',2,150,'E207DDBA'","'D-10-03','172.16.32.163','iEM3155',3,153,'E207DDB4'","'D-10-03','172.16.32.163','iEM3155',4,151,'E207DDC2'","'D-10-03','172.16.32.163','iEM3155',5,155,'E207DDAE'","'D-10-03','172.16.32.163','iEM3155',6,154,'E207DDD0'","'D-10-04','172.16.32.164','iEM3155',1,151,'E207DDB5'","'D-10-04','172.16.32.164','iEM3155',2,152,'E207DD88'","'D-10-04','172.16.32.164','iEM3155',3,153,'E207DDC9'","'D-10-04','172.16.32.164','iEM3155',4,155,'E207DDAF'","'D-10-04','172.16.32.164','iEM3155',5,154,'E207DDB3'","'D-10-04','172.16.32.164','iEM3155',6,150,'E207DDDB'","'D-10-05','172.16.32.165','iEM3155',1,150,'E207DDB7'","'D-10-05','172.16.32.165','iEM3155',2,152,'E207DDDA'","'D-10-05','172.16.32.165','iEM3155',3,153,'E207DDC4'","'D-10-05','172.16.32.165','iEM3155',4,154,'E207DDBB'","'D-10-05','172.16.32.165','iEM3155',5,151,'E207DDC6'","'D-10-05','172.16.32.165','iEM3155',6,156,'E207DDD3'","'D-10-06','172.16.32.166','iEM3155',1,152,'E207D0D7'","'D-10-06','172.16.32.166','iEM3155',2,151,'E207D0D4'","'D-10-06','172.16.32.166','iEM3155',3,155,'E207D0CF'","'D-10-06','172.16.32.166','iEM3155',4,150,'E207D0D0'","'D-10-06','172.16.32.166','iEM3155',5,154,'E207D0C7'","'D-10-06','172.16.32.166','iEM3155',6,153,'E207D0CA'","'D-10-07','172.16.32.167','iEM3155',1,155,'E207D0BD'","'D-10-07','172.16.32.167','iEM3155',2,153,'E207D0BB'","'D-10-07','172.16.32.167','iEM3155',3,150,'E207D0B0'","'D-10-07','172.16.32.167','iEM3155',4,152,'E207D0D6'","'D-10-07','172.16.32.167','iEM3155',5,156,'E207D0CD'","'D-10-07','172.16.32.167','iEM3155',6,159,'E207D0BE'","'D-10-08','172.16.32.168','iEM3155',1,152,'E207D0D5'","'D-10-08','172.16.32.168','iEM3155',2,159,'E207DDB0'","'D-10-08','172.16.32.168','iEM3155',3,156,'E207D0D1'","'D-10-08','172.16.32.168','iEM3155',4,151,'E207DDD8'","'D-10-08','172.16.32.168','iEM3155',5,155,'E207D0CC'","'D-10-08','172.16.32.168','iEM3155',6,150,'E207DDD9'","'D-10-10','172.16.32.170','iEM3155',1,153,'E207D0C6'","'D-10-10','172.16.32.170','iEM3155',2,151,'E207D0D2'","'D-10-10','172.16.32.170','iEM3155',3,155,'E207D0BC'","'D-10-10','172.16.32.170','iEM3155',4,154,'E207D0B1'","'D-10-10','172.16.32.170','iEM3155',5,150,'E207D0C8'","'D-10-10','172.16.32.170','iEM3155',6,152,'E207D0CE'","'D-11-01','172.16.32.171','iEM3155',1,158,'E207D0C2'","'D-11-01','172.16.32.171','iEM3155',2,150,'E207D0DC'","'D-11-01','172.16.32.171','iEM3155',3,160,'E207D0B3'","'D-11-01','172.16.32.171','iEM3155',4,153,'E207D0B6'","'D-11-01','172.16.32.171','iEM3155',5,159,'E207D0DA'","'D-11-01','172.16.32.171','iEM3155',6,151,'E207D0BA'","'D-11-02','172.16.32.172','iEM3155',1,153,'E207DC96'","'D-11-02','172.16.32.172','iEM3155',2,155,'E207DC25'","'D-11-02','172.16.32.172','iEM3155',3,154,'E207DCAB'","'D-11-02','172.16.32.172','iEM3155',4,152,'E207DCA2'","'D-11-02','172.16.32.172','iEM3155',5,151,'E207DCAC'","'D-11-02','172.16.32.172','iEM3155',6,150,'E207DC9E'","'D-11-03','172.16.32.173','iEM3155',1,151,'E207DCAA'","'D-11-03','172.16.32.173','iEM3155',2,150,'E207DCB9'","'D-11-03','172.16.32.173','iEM3155',3,154,'E207DCA8'","'D-11-03','172.16.32.173','iEM3155',4,153,'E207DCA9'","'D-11-03','172.16.32.173','iEM3155',5,155,'E207DCB8'","'D-11-03','172.16.32.173','iEM3155',6,152,'E207DCB1'","'D-11-04','172.16.32.174','iEM3155',1,155,'E207DDBC'","'D-11-04','172.16.32.174','iEM3155',2,153,'E207DDCA'","'D-11-04','172.16.32.174','iEM3155',3,156,'E207DDD2'","'D-11-04','172.16.32.174','iEM3155',4,151,'E207DDC7'","'D-11-04','172.16.32.174','iEM3155',5,150,'E207DDC0'","'D-11-04','172.16.32.174','iEM3155',6,152,'E207DC99'","'D-11-05','172.16.32.175','iEM3155',1,158,'E207DCBD'","'D-11-05','172.16.32.175','iEM3155',2,157,'E207DCB7'","'D-11-05','172.16.32.175','iEM3155',3,153,'E207DCBC'","'D-11-05','172.16.32.175','iEM3155',4,150,'E207DCBA'","'D-11-05','172.16.32.175','iEM3155',5,151,'E207DC95'","'D-11-05','172.16.32.175','iEM3155',6,160,'E207DDB8'","'D-11-06','172.16.32.176','iEM3155',1,150,'E207DCA3'","'D-11-06','172.16.32.176','iEM3155',2,153,'E207DCB5'","'D-11-06','172.16.32.176','iEM3155',3,157,'E207DCB0'","'D-11-06','172.16.32.176','iEM3155',4,155,'E207DCB2'","'D-11-06','172.16.32.176','iEM3155',5,151,'E207DCB3'","'D-11-06','172.16.32.176','iEM3155',6,156,'E207DCAD'","'D-11-07','172.16.32.177','iEM3155',1,153,'E207D0C5'","'D-11-07','172.16.32.177','iEM3155',2,154,'E207D0B8'","'D-11-07','172.16.32.177','iEM3155',3,151,'E207D0B4'","'D-11-07','172.16.32.177','iEM3155',4,150,'E207D0AF'","'D-11-07','172.16.32.177','iEM3155',5,155,'E207D0C3'","'D-11-07','172.16.32.177','iEM3155',6,152,'E207D0C1'","'D-11-08','172.16.32.178','iEM3155',1,151,'E207D0AD'","'D-11-08','172.16.32.178','iEM3155',2,153,'E207D0B5'","'D-11-08','172.16.32.178','iEM3155',3,152,'E207D0C0'","'D-11-08','172.16.32.178','iEM3155',4,154,'E207D0B7'","'D-11-08','172.16.32.178','iEM3155',5,155,'E207D0B2'","'D-11-08','172.16.32.178','iEM3155',6,150,'E207D0AE'","'D-11-09','172.16.32.179','iEM3155',1,150,'E20744F9'","'D-11-09','172.16.32.179','iEM3155',2,151,'E20744DB'","'D-11-09','172.16.32.179','iEM3155',3,152,'E20744E4'","'D-11-09','172.16.32.179','iEM3155',4,154,'E20758B0'","'D-11-09','172.16.32.179','iEM3155',5,153,'E20758CC'","'D-11-10','172.16.32.180','iEM3155',1,154,'E207DC97'","'D-11-10','172.16.32.180','iEM3155',2,155,'E207DC27'","'D-11-10','172.16.32.180','iEM3155',3,151,'E207DC93'","'D-11-10','172.16.32.180','iEM3155',4,152,'E207DC8F'","'D-11-10','172.16.32.180','iEM3155',5,153,'E207DC2C'","'D-11-10','172.16.32.180','iEM3155',6,150,'E207DC8C'","'D-12-01','172.16.32.181','iEM3155',1,154,'E207DAFF'","'D-12-01','172.16.32.181','iEM3155',2,153,'E207DBC0'","'D-12-01','172.16.32.181','iEM3155',3,151,'E207DBDE'","'D-12-01','172.16.32.181','iEM3155',4,155,'E207DB7C'","'D-12-01','172.16.32.181','iEM3155',5,150,'E207DBF0'","'D-12-01','172.16.32.181','iEM3155',6,152,'E207DBBA'","'D-12-02','172.16.32.182','iEM3155',1,155,'E207DBE3'","'D-12-02','172.16.32.182','iEM3155',2,159,'E207DBB6'","'D-12-02','172.16.32.182','iEM3155',3,158,'E207DBE2'","'D-12-02','172.16.32.182','iEM3155',4,157,'E207DBE6'","'D-12-02','172.16.32.182','iEM3155',5,160,'E207DBE1'","'D-12-02','172.16.32.182','iEM3155',6,161,'E207DBE5'","'D-12-03','172.16.32.183','iEM3155',1,150,'E207DBE7'","'D-12-03','172.16.32.183','iEM3155',2,151,'E207DBB7'","'D-12-03','172.16.32.183','iEM3155',3,152,'E207DBC1'","'D-12-03','172.16.32.183','iEM3155',4,153,'E207DBB4'","'D-12-03','172.16.32.183','iEM3155',5,154,'E207DBB8'","'D-12-03','172.16.32.183','iEM3155',6,155,'E207DBEE'","'D-12-04','172.16.32.184','iEM3155',1,153,'E207DC63'","'D-12-04','172.16.32.184','iEM3155',2,156,'E207DC90'","'D-12-04','172.16.32.184','iEM3155',3,154,'E207DC8E'","'D-12-04','172.16.32.184','iEM3155',4,151,'E207DC7D'","'D-12-04','172.16.32.184','iEM3155',5,150,'E207DC92'","'D-12-04','172.16.32.184','iEM3155',6,155,'E207DC94'","'D-12-05','172.16.32.185','iEM3155',1,152,'E207DBCE'","'D-12-05','172.16.32.185','iEM3155',2,155,'E207DBD1'","'D-12-05','172.16.32.185','iEM3155',3,151,'E207DBF4'","'D-12-05','172.16.32.185','iEM3155',4,150,'E207DBDF'","'D-12-05','172.16.32.185','iEM3155',5,153,'E207DBD4'","'D-12-05','172.16.32.185','iEM3155',6,154,'E207DBD6'","'D-12-06','172.16.32.186','iEM3155',1,155,'E207DC23'","'D-12-06','172.16.32.186','iEM3155',2,156,'E207DC9D'","'D-12-06','172.16.32.186','iEM3155',3,153,'E207DC9B'","'D-12-06','172.16.32.186','iEM3155',4,151,'E207DC9A'","'D-12-06','172.16.32.186','iEM3155',5,152,'E207DC91'","'D-12-06','172.16.32.186','iEM3155',6,154,'E207DC9C'","'D-12-07','172.16.32.187','iEM3155',1,155,'E207DC84'","'D-12-07','172.16.32.187','iEM3155',2,150,'E207DC83'","'D-12-07','172.16.32.187','iEM3155',3,154,'E207DBDB'","'D-12-07','172.16.32.187','iEM3155',4,151,'E207DBB9'","'D-12-07','172.16.32.187','iEM3155',5,153,'E207DC88'","'D-12-07','172.16.32.187','iEM3155',6,152,'E207DBDD'","'D-12-08','172.16.32.188','iEM3155',1,152,'E207DBDC'","'D-12-08','172.16.32.188','iEM3155',2,155,'E207DBBF'","'D-12-08','172.16.32.188','iEM3155',3,150,'E207DBDA'","'D-12-08','172.16.32.188','iEM3155',4,154,'E207DC2D'","'D-12-08','172.16.32.188','iEM3155',5,151,'E207DC65'","'D-12-08','172.16.32.188','iEM3155',6,153,'E207DC8B'","'D-12-09','172.16.32.189','iEM3155',1,154,'E20744DC'","'D-12-09','172.16.32.189','iEM3155',2,152,'E20758D2'","'D-12-09','172.16.32.189','iEM3155',3,150,'E20758CD'","'D-12-09','172.16.32.189','iEM3155',4,153,'E207460B'","'D-12-09','172.16.32.189','iEM3155',5,151,'E2074507'","'D-12-10','172.16.32.190','iEM3155',1,152,'E207DBE4'","'D-12-10','172.16.32.190','iEM3155',2,150,'E207DBCD'","'D-12-10','172.16.32.190','iEM3155',3,155,'E207DBC7'","'D-12-10','172.16.32.190','iEM3155',4,154,'E207DBE0'","'D-12-10','172.16.32.190','iEM3155',5,151,'E207DBC5'","'D-12-10','172.16.32.190','iEM3155',6,153,'E207DBE8'","'D-13-01','172.16.32.191','iEM3155',1,151,'E207DD15'","'D-13-01','172.16.32.191','iEM3155',2,153,'E207DD0D'","'D-13-01','172.16.32.191','iEM3155',3,152,'E207DD2D'","'D-13-01','172.16.32.191','iEM3155',4,154,'E207DD13'","'D-13-01','172.16.32.191','iEM3155',5,155,'E207DD2E'","'D-13-01','172.16.32.191','iEM3155',6,150,'E207DD29'","'D-13-02','172.16.32.192','iEM3155',1,160,'E207DB7B'","'D-13-02','172.16.32.192','iEM3155',2,153,'E207DB95'","'D-13-02','172.16.32.192','iEM3155',3,159,'E207DBA3'","'D-13-02','172.16.32.192','iEM3155',4,157,'E207DBAA'","'D-13-02','172.16.32.192','iEM3155',5,158,'E207DB8E'","'D-13-02','172.16.32.192','iEM3155',6,150,'E207DB94'","'D-13-03','172.16.32.193','iEM3155',1,155,'E207DBB3'","'D-13-03','172.16.32.193','iEM3155',2,151,'E207DBAF'","'D-13-03','172.16.32.193','iEM3155',3,152,'E207DB8D'","'D-13-03','172.16.32.193','iEM3155',4,150,'E207DB63'","'D-13-03','172.16.32.193','iEM3155',5,153,'E207DBB0'","'D-13-03','172.16.32.193','iEM3155',6,154,'E207DBA1'","'D-13-04','172.16.32.194','iEM3155',1,153,'E207DB8B'","'D-13-04','172.16.32.194','iEM3155',2,150,'E207DBBD'","'D-13-04','172.16.32.194','iEM3155',3,151,'E207DBBE'","'D-13-04','172.16.32.194','iEM3155',4,154,'E207DB9B'","'D-13-04','172.16.32.194','iEM3155',5,152,'E207DB8A'","'D-13-04','172.16.32.194','iEM3155',6,155,'E207DBCA'","'D-13-05','172.16.32.195','iEM3155',1,154,'E207DB88'","'D-13-05','172.16.32.195','iEM3155',2,150,'E207DB9D'","'D-13-05','172.16.32.195','iEM3155',3,152,'E207DBAD'","'D-13-05','172.16.32.195','iEM3155',4,155,'E207DBCB'","'D-13-05','172.16.32.195','iEM3155',5,151,'E207DBA8'","'D-13-05','172.16.32.195','iEM3155',6,153,'E207DB85'","'D-13-06','172.16.32.196','iEM3155',1,152,'E207DBC3'","'D-13-06','172.16.32.196','iEM3155',2,150,'E207DB8C'","'D-13-06','172.16.32.196','iEM3155',3,151,'E207DB97'","'D-13-06','172.16.32.196','iEM3155',4,153,'E207DB84'","'D-13-06','172.16.32.196','iEM3155',5,155,'E207DB99'","'D-13-06','172.16.32.196','iEM3155',6,154,'E207DBA7'","'D-13-07','172.16.32.197','iEM3155',1,151,'E207DBBC'","'D-13-07','172.16.32.197','iEM3155',2,156,'E207DBAE'","'D-13-07','172.16.32.197','iEM3155',3,154,'E207DB98'","'D-13-07','172.16.32.197','iEM3155',4,153,'E207DBA0'","'D-13-07','172.16.32.197','iEM3155',5,150,'E207DB77'","'D-13-07','172.16.32.197','iEM3155',6,160,'E207DBC8'","'D-13-08','172.16.32.198','iEM3155',1,153,'E207DBCF'","'D-13-08','172.16.32.198','iEM3155',2,151,'E207DBCC'","'D-13-08','172.16.32.198','iEM3155',3,154,'E207DBD0'","'D-13-08','172.16.32.198','iEM3155',4,155,'E207DBA9'","'D-13-08','172.16.32.198','iEM3155',5,150,'E207DBD2'","'D-13-08','172.16.32.198','iEM3155',6,152,'E207DBD8'","'D-13-09','172.16.32.199','iEM3155',1,154,'E207556A'","'D-13-09','172.16.32.199','iEM3155',2,153,'E20744EE'","'D-13-09','172.16.32.199','iEM3155',3,150,'E20744FA'","'D-13-09','172.16.32.199','iEM3155',4,152,'E20758B9'","'D-13-09','172.16.32.199','iEM3155',5,151,'E20758C3'","'D-13-10','172.16.32.200','iEM3155',1,154,'E207DB9F'","'D-13-10','172.16.32.200','iEM3155',2,153,'E207DB89'","'D-13-10','172.16.32.200','iEM3155',3,151,'E207DB93'","'D-13-10','172.16.32.200','iEM3155',4,150,'E207DBBB'","'D-13-10','172.16.32.200','iEM3155',5,152,'E207DBB5'","'D-13-10','172.16.32.200','iEM3155',6,156,'E207DBC2'","'D-14-01','172.16.32.201','iEM3155',1,151,'E207DD0E'","'D-14-01','172.16.32.201','iEM3155',2,153,'E207DD2A'","'D-14-01','172.16.32.201','iEM3155',3,154,'E207DD2C'","'D-14-01','172.16.32.201','iEM3155',4,152,'E207DD25'","'D-14-01','172.16.32.201','iEM3155',5,150,'E207DD14'","'D-14-01','172.16.32.201','iEM3155',6,155,'E207DD0F'","'D-14-02','172.16.32.202','iEM3155',1,152,'E207DD0C'");

$data1 = array("'D-14-02','172.16.32.202','iEM3155',2,155,'E207DD1E'","'D-14-02','172.16.32.202','iEM3155',3,153,'E207DCD6'","'D-14-02','172.16.32.202','iEM3155',4,156,'E207DD04'","'D-14-02','172.16.32.202','iEM3155',5,154,'E207DD0B'","'D-14-02','172.16.32.202','iEM3155',6,150,'E207DC98'","'D-14-03','172.16.32.203','iEM3155',1,152,'E207D297'","'D-14-03','172.16.32.203','iEM3155',2,154,'E207D29A'","'D-14-03','172.16.32.203','iEM3155',3,151,'E207DCF9'","'D-14-03','172.16.32.203','iEM3155',4,150,'E207D292'","'D-14-03','172.16.32.203','iEM3155',5,155,'E207D29C'","'D-14-03','172.16.32.203','iEM3155',6,153,'E207D5C3'","'D-14-04','172.16.32.204','iEM3155',1,150,'E207DCFC'","'D-14-04','172.16.32.204','iEM3155',2,151,'E207DD0A'","'D-14-04','172.16.32.204','iEM3155',3,152,'E207DCF0'","'D-14-04','172.16.32.204','iEM3155',4,155,'E207DD07'","'D-14-04','172.16.32.204','iEM3155',5,154,'E207DCEE'","'D-14-04','172.16.32.204','iEM3155',6,153,'E207DCFF'","'D-14-05','172.16.32.205','iEM3155',1,153,'E207D2A2'","'D-14-05','172.16.32.205','iEM3155',2,156,'E207DD01'","'D-14-05','172.16.32.205','iEM3155',3,157,'E207D2A7'","'D-14-05','172.16.32.205','iEM3155',4,154,'E207DCA5'","'D-14-05','172.16.32.205','iEM3155',5,158,'E207D5C5'","'D-14-05','172.16.32.205','iEM3155',6,159,'E207D5BC'","'D-14-06','172.16.32.206','iEM3155',1,150,'E207DD00'","'D-14-06','172.16.32.206','iEM3155',2,153,'E207DCF2'","'D-14-06','172.16.32.206','iEM3155',3,156,'E207DCFB'","'D-14-06','172.16.32.206','iEM3155',4,154,'E207DCF1'","'D-14-06','172.16.32.206','iEM3155',5,151,'E207DD10'","'D-14-06','172.16.32.206','iEM3155',6,155,'E207DD18'","'D-14-06','172.16.32.206','iEM3155',1,155,'E207DBC9'","'D-14-06','172.16.32.206','iEM3155',2,157,'E207DBD9'","'D-14-06','172.16.32.206','iEM3155',3,152,'E207DBC6'","'D-14-06','172.16.32.206','iEM3155',4,153,'E207DBD7'","'D-14-06','172.16.32.206','iEM3155',5,150,'E207DBD3'","'D-14-06','172.16.32.206','iEM3155',6,151,'E207DBD5'","'D-14-08','172.16.32.208','iEM3155',1,152,'E207DB9A'","'D-14-08','172.16.32.208','iEM3155',2,156,'E207DB8F'","'D-14-08','172.16.32.208','iEM3155',3,154,'E207DB66'","'D-14-08','172.16.32.208','iEM3155',4,151,'E207DD23'","'D-14-08','172.16.32.208','iEM3155',5,155,'E207DB64'","'D-14-08','172.16.32.208','iEM3155',6,150,'E207DCC8'","'D-14-09','172.16.32.209','iEM3155',1,155,'E20758AF'","'D-14-09','172.16.32.209','iEM3155',2,157,'E20758B3'","'D-14-09','172.16.32.209','iEM3155',3,152,'E20758B8'","'D-14-09','172.16.32.209','iEM3155',4,153,'E20758C2'","'D-14-09','172.16.32.209','iEM3155',5,150,'E20758BF'","'D-14-09','172.16.32.209','iEM3155',6,-,'-'","'D-14-10','172.16.32.210','iEM3155',1,154,'E207DCDA'","'D-14-10','172.16.32.210','iEM3155',2,155,'E207DCB6'","'D-14-10','172.16.32.210','iEM3155',3,156,'E207DCA0'","'D-14-10','172.16.32.210','iEM3155',4,153,'E207DD05'","'D-14-10','172.16.32.210','iEM3155',5,152,'E207DD08'","'D-14-10','172.16.32.210','iEM3155',6,150,'E207DD06'","'D-15-01','172.16.32.211','iEM3155',1,150,'E207D287'","'D-15-01','172.16.32.211','iEM3155',2,152,'E207D5C8'","'D-15-01','172.16.32.211','iEM3155',3,156,'E207DA2F'","'D-15-01','172.16.32.211','iEM3155',4,154,'E207D27A'","'D-15-01','172.16.32.211','iEM3155',5,151,'E207D28A'","'D-15-01','172.16.32.211','iEM3155',6,153,'E207D286'","'D-15-02','172.16.32.212','iEM3155',1,151,'E207DA54'","'D-15-02','172.16.32.212','iEM3155',2,150,'E207D289'","'D-15-02','172.16.32.212','iEM3155',3,153,'E207DAB9'","'D-15-02','172.16.32.212','iEM3155',4,155,'E207D27D'","'D-15-02','172.16.32.212','iEM3155',5,152,'E207D283'","'D-15-02','172.16.32.212','iEM3155',6,154,'E207DAE2'","'D-15-03','172.16.32.213','iEM3155',1,155,'E207DABE'","'D-15-03','172.16.32.213','iEM3155',2,151,'E207DAEC'","'D-15-03','172.16.32.213','iEM3155',3,153,'E207DA94'","'D-15-03','172.16.32.213','iEM3155',4,154,'E207DAC8'","'D-15-03','172.16.32.213','iEM3155',5,152,'E207D9BF'","'D-15-03','172.16.32.213','iEM3155',6,150,'E207DAC9'","'D-15-04','172.16.32.214','iEM3155',1,150,'E207D28E'","'D-15-04','172.16.32.214','iEM3155',2,151,'E207D28B'","'D-15-04','172.16.32.214','iEM3155',3,152,'E207D285'","'D-15-04','172.16.32.214','iEM3155',4,154,'E207D290'","'D-15-04','172.16.32.214','iEM3155',5,153,'E207D288'","'D-15-04','172.16.32.214','iEM3155',6,155,'E207D27E'","'D-15-05','172.16.32.215','iEM3155',1,153,'E207DAF3'","'D-15-05','172.16.32.215','iEM3155',2,155,'E207DAE3'","'D-15-05','172.16.32.215','iEM3155',3,152,'E207DADF'");

$data2 = array("'D-15-05','172.16.32.215','iEM3155',4,151,'E207DAD2'","'D-15-05','172.16.32.215','iEM3155',5,150,'E207DA5E'","'D-15-05','172.16.32.215','iEM3155',6,154,'E207DAF5'","'D-15-06','172.16.32.216','iEM3155',1,151,'E207DAF0'","'D-15-06','172.16.32.216','iEM3155',2,160,'E207DAEF'","'D-15-06','172.16.32.216','iEM3155',3,153,'E207DAD3'","'D-15-06','172.16.32.216','iEM3155',4,155,'E207DA85'","'D-15-06','172.16.32.216','iEM3155',5,150,'E207DA93'","'D-15-06','172.16.32.216','iEM3155',6,156,'E207DAF6'","'D-15-07','172.16.32.217','iEM3155',1,151,'E207DAC7'","'D-15-07','172.16.32.217','iEM3155',2,158,'E207DAD9'","'D-15-07','172.16.32.217','iEM3155',3,156,'E207DAF2'","'D-15-07','172.16.32.217','iEM3155',4,153,'E207DAF4'","'D-15-07','172.16.32.217','iEM3155',5,154,'E207DAAD'","'D-15-07','172.16.32.217','iEM3155',6,150,'E207DAF7'","'D-15-08','172.16.32.218','iEM3155',1,153,'E207DD09'","'D-15-08','172.16.32.218','iEM3155',2,154,'E207DCD4'","'D-15-08','172.16.32.218','iEM3155',3,152,'E207DCEF'","'D-15-08','172.16.32.218','iEM3155',4,150,'E207DCEC'","'D-15-08','172.16.32.218','iEM3155',5,155,'E207DD03'","'D-15-08','172.16.32.218','iEM3155',6,151,'E207DCFD'","'D-15-09','172.16.32.219','iEM3155',1,152,'E2074616'","'D-15-09','172.16.32.219','iEM3155',2,150,'E20758AA'","'D-15-09','172.16.32.219','iEM3155',3,153,'E20758B6'","'D-15-09','172.16.32.219','iEM3155',4,154,'E20744FB'","'D-15-09','172.16.32.219','iEM3155',5,151,'E20758BE'","'D-15-10','172.16.32.220','iEM3155',1,151,'E207D27B'","'D-15-10','172.16.32.220','iEM3155',2,150,'E207D27F'","'D-15-10','172.16.32.220','iEM3155',3,154,'E207D282'","'D-15-10','172.16.32.220','iEM3155',4,153,'E207D28F'","'D-15-10','172.16.32.220','iEM3155',5,152,'E207D27C'","'D-15-10','172.16.32.220','iEM3155',6,155,'E207D5C7'","'D-16-01','172.16.32.221','iEM3155',1,154,'E207C309'","'D-16-01','172.16.32.221','iEM3155',2,152,'E207DACC'","'D-16-01','172.16.32.221','iEM3155',3,156,'E207DAE5'","'D-16-01','172.16.32.221','iEM3155',4,150,'E207DA48'","'D-16-01','172.16.32.221','iEM3155',5,155,'E207DAEB'","'D-16-01','172.16.32.221','iEM3155',6,153,'E207D9FD'","'D-16-02','172.16.32.222','iEM3155',1,158,'E207DADE'","'D-16-02','172.16.32.222','iEM3155',2,151,'E207DAE6'","'D-16-02','172.16.32.222','iEM3155',3,155,'E207DAED'","'D-16-02','172.16.32.222','iEM3155',4,156,'E207D9DC'","'D-16-02','172.16.32.222','iEM3155',5,153,'E207DADC'","'D-16-02','172.16.32.222','iEM3155',6,150,'E207DAEA'","'D-16-03','172.16.32.223','iEM3155',1,153,'E207DA8C'","'D-16-03','172.16.32.223','iEM3155',2,152,'E207DA15'","'D-16-03','172.16.32.223','iEM3155',3,150,'E207C302'","'D-16-03','172.16.32.223','iEM3155',4,155,'E207DA0D'","'D-16-03','172.16.32.223','iEM3155',5,151,'E207DAC2'","'D-16-03','172.16.32.223','iEM3155',6,156,'E207DAD7'","'D-16-04','172.16.32.224','iEM3155',1,153,'E207DACB'","'D-16-04','172.16.32.224','iEM3155',2,150,'E207DAC3'","'D-16-04','172.16.32.224','iEM3155',3,154,'E207DAE8'","'D-16-04','172.16.32.224','iEM3155',4,151,'E207DAF1'","'D-16-04','172.16.32.224','iEM3155',5,152,'E207DA34'","'D-16-04','172.16.32.224','iEM3155',6,155,'E207DAE9'","'D-16-05','172.16.32.225','iEM3155',1,156,'E207DB50'","'D-16-05','172.16.32.225','iEM3155',2,158,'E207DB4D'","'D-16-05','172.16.32.225','iEM3155',3,153,'E207DB52'","'D-16-05','172.16.32.225','iEM3155',4,157,'E207DB4B'","'D-16-05','172.16.32.225','iEM3155',5,152,'E207DB00'","'D-16-05','172.16.32.225','iEM3155',6,150,'E207DB51'","'D-16-06','172.16.32.226','iEM3155',1,155,'E207DA43'","'D-16-06','172.16.32.226','iEM3155',2,158,'E207DAD1'","'D-16-06','172.16.32.226','iEM3155',3,157,'E207DAD6'","'D-16-06','172.16.32.226','iEM3155',4,151,'E207DADD'","'D-16-06','172.16.32.226','iEM3155',5,150,'E207DA84'","'D-16-06','172.16.32.226','iEM3155',6,152,'E207DAD5'","'D-16-07','172.16.32.227','iEM3155',1,151,'E207DAB5'","'D-16-07','172.16.32.227','iEM3155',2,155,'E207D9F2'","'D-16-07','172.16.32.227','iEM3155',3,152,'E207DA0E'","'D-16-07','172.16.32.227','iEM3155',4,153,'E207DADB'","'D-16-07','172.16.32.227','iEM3155',5,154,'E207D9FA'","'D-16-07','172.16.32.227','iEM3155',6,150,'E207D9EB'","'D-16-08','172.16.32.228','iEM3155',1,154,'E207DA8D'","'D-16-08','172.16.32.228','iEM3155',2,151,'E207D9DA'","'D-16-08','172.16.32.228','iEM3155',3,153,'E207DA06'","'D-16-08','172.16.32.228','iEM3155',4,150,'E207D9F4'","'D-16-08','172.16.32.228','iEM3155',5,155,'E207D9D2'","'D-16-08','172.16.32.228','iEM3155',6,152,'E207DAC5'","'D-16-09','172.16.32.229','iEM3355',1,154,'E20744EB'","'D-16-09','172.16.32.229','iEM3355',2,153,'E20744D9'","'D-16-09','172.16.32.229','iEM3355',3,152,'E20744D7'","'D-16-09','172.16.32.229','iEM3355',4,150,'E20744E5'","'D-16-09','172.16.32.229','iEM3355',5,151,'E20744F1'","'D-16-10','172.16.32.230','iEM3155',1,154,'E207DAE4'","'D-16-10','172.16.32.230','iEM3155',2,155,'E207DAE1'","'D-16-10','172.16.32.230','iEM3155',3,153,'E207DAD0'","'D-16-10','172.16.32.230','iEM3155',4,151,'E207DACE'","'D-16-10','172.16.32.230','iEM3155',5,152,'E207DACF'","'D-16-10','172.16.32.230','iEM3155',6,150,'E207DADA'","'D-17-01','172.16.32.231','iEM3155',1,158,'E207D5BF'","'D-17-01','172.16.32.231','iEM3155',2,152,'E207D5D2'","'D-17-01','172.16.32.231','iEM3155',3,154,'E207D5B4'","'D-17-01','172.16.32.231','iEM3155',4,151,'E207D5B5'","'D-17-01','172.16.32.231','iEM3155',5,160,'E207D5C2'","'D-17-01','172.16.32.231','iEM3155',6,155,'E207D5B3'","'D-17-02','172.16.32.232','iEM3155',1,152,'E207D5BB'","'D-17-02','172.16.32.232','iEM3155',2,153,'E207D5BE'","'D-17-02','172.16.32.232','iEM3155',3,150,'E207D5B2'","'D-17-02','172.16.32.232','iEM3155',4,155,'E207D5D7'","'D-17-02','172.16.32.232','iEM3155',5,154,'E207D5C0'","'D-17-02','172.16.32.232','iEM3155',6,151,'E207D5B9'","'D-17-03','172.16.32.233','iEM3155',1,151,'E207D85C'","'D-17-03','172.16.32.233','iEM3155',2,155,'E207D851'","'D-17-03','172.16.32.233','iEM3155',3,150,'E207D85F'","'D-17-03','172.16.32.233','iEM3155',4,153,'E207D852'","'D-17-03','172.16.32.233','iEM3155',5,154,'E207D835'","'D-17-03','172.16.32.233','iEM3155',6,152,'E207DA0A'","'D-17-04','172.16.32.234','iEM3155',1,152,'E207DB5C'","'D-17-04','172.16.32.234','iEM3155',2,150,'E207DB43'","'D-17-04','172.16.32.234','iEM3155',3,151,'E207DB4C'","'D-17-04','172.16.32.234','iEM3155',4,154,'E207DB4F'","'D-17-04','172.16.32.234','iEM3155',5,153,'E207DB58'","'D-17-04','172.16.32.234','iEM3155',6,155,'E207DB53'","'D-17-05','172.16.32.235','iEM3155',1,153,'E207D828'","'D-17-05','172.16.32.235','iEM3155',2,150,'E207D831'","'D-17-05','172.16.32.235','iEM3155',3,159,'E207D841'","'D-17-05','172.16.32.235','iEM3155',4,156,'E207D847'","'D-17-05','172.16.32.235','iEM3155',5,151,'E207D84E'","'D-17-05','172.16.32.235','iEM3155',6,161,'E207D84D'","'D-17-06','172.16.32.236','iEM3155',1,157,'E207D850'","'D-17-06','172.16.32.236','iEM3155',2,156,'E207D82F'","'D-17-06','172.16.32.236','iEM3155',3,154,'E207D855'","'D-17-06','172.16.32.236','iEM3155',4,153,'E207D862'","'D-17-06','172.16.32.236','iEM3155',5,151,'E207D848'","'D-17-06','172.16.32.236','iEM3155',6,150,'E207D858'","'D-17-07','172.16.32.237','iEM3155',1,151,'E207DB4A'","'D-17-07','172.16.32.237','iEM3155',2,155,'E207DB47'","'D-17-07','172.16.32.237','iEM3155',3,150,'E207DB5B'","'D-17-07','172.16.32.237','iEM3155',4,153,'E207DB4E'","'D-17-07','172.16.32.237','iEM3155',5,152,'E207DB48'","'D-17-07','172.16.32.237','iEM3155',6,154,'E207DB59'","'D-17-08','172.16.32.238','iEM3155',1,154,'E207DB5E'","'D-17-08','172.16.32.238','iEM3155',2,155,'E207DB57'","'D-17-08','172.16.32.238','iEM3155',3,151,'E207DB5D'","'D-17-08','172.16.32.238','iEM3155',4,152,'E207DB24'","'D-17-08','172.16.32.238','iEM3155',5,153,'E207DB45'","'D-17-08','172.16.32.238','iEM3155',6,150,'E207DB54'","'D-17-09','172.16.32.239','iEM3155',1,152,'E20744E0'","'D-17-09','172.16.32.239','iEM3155',2,151,'E20744D8'","'D-17-09','172.16.32.239','iEM3155',3,150,'E2074505'","'D-17-09','172.16.32.239','iEM3155',4,153,'E20744E9'","'D-17-09','172.16.32.239','iEM3155',5,154,'E20744E1'","'D-17-10','172.16.32.240','iEM3155',1,153,'E207DA91'","'D-17-10','172.16.32.240','iEM3155',2,151,'E207DA29'","'D-17-10','172.16.32.240','iEM3155',3,152,'E207D837'","'D-17-10','172.16.32.240','iEM3155',4,150,'E207DAB6'","'D-17-10','172.16.32.240','iEM3155',5,154,'E207DAC0'","'D-17-10','172.16.32.240','iEM3155',6,155,'E207D84A'","'D-18-01','172.16.32.241','iEM3155',1,155,'E207DB38'","'D-18-01','172.16.32.241','iEM3155',2,150,'E207DB3C'","'D-18-01','172.16.32.241','iEM3155',3,151,'E207DB01'","'D-18-01','172.16.32.241','iEM3155',4,154,'E207DAFE'","'D-18-01','172.16.32.241','iEM3155',5,152,'E207DB02'","'D-18-01','172.16.32.241','iEM3155',6,153,'E207DB08'","'D-18-02','172.16.32.242','iEM3155',1,155,'E207D5E3'","'D-18-02','172.16.32.242','iEM3155',2,151,'E207DAD4'","'D-18-02','172.16.32.242','iEM3155',3,150,'E207D9F8'","'D-18-02','172.16.32.242','iEM3155',4,152,'E207D5CC'","'D-18-02','172.16.32.242','iEM3155',5,154,'E207D5D3'","'D-18-02','172.16.32.242','iEM3155',6,153,'E207D9F9'","'D-18-03','172.16.32.243','iEM3155',1,154,'E207D114'","'D-18-03','172.16.32.243','iEM3155',2,152,'E207D15B'","'D-18-03','172.16.32.243','iEM3155',3,150,'E207D826'","'D-18-03','172.16.32.243','iEM3155',4,151,'E207DD69'","'D-18-03','172.16.32.243','iEM3155',5,155,'E207DD62'","'D-18-03','172.16.32.243','iEM3155',6,153,'E207DD72'","'D-18-04','172.16.32.244','iEM3155',1,155,'E207D5CA'","'D-18-04','172.16.32.244','iEM3155',2,152,'E207D5BA'","'D-18-04','172.16.32.244','iEM3155',3,150,'E207D5CF'","'D-18-04','172.16.32.244','iEM3155',4,154,'E207D5D4'","'D-18-04','172.16.32.244','iEM3155',5,153,'E207D5E5'","'D-18-04','172.16.32.244','iEM3155',6,151,'E207D5DA'","'D-18-05','172.16.32.245','iEM3155',1,150,'E207DD6B'","'D-18-05','172.16.32.245','iEM3155',2,151,'E207DD5B'","'D-18-05','172.16.32.245','iEM3155',3,154,'E207DD57'","'D-18-05','172.16.32.245','iEM3155',4,152,'E207D183'","'D-18-05','172.16.32.245','iEM3155',5,153,'E207DD1C'","'D-18-05','172.16.32.245','iEM3155',6,155,'E207DD5E'","'D-18-06','172.16.32.246','iEM3155',1,151,'E207D834'","'D-18-06','172.16.32.246','iEM3155',2,150,'E207D846'","'D-18-06','172.16.32.246','iEM3155',3,155,'E207D81A'","'D-18-06','172.16.32.246','iEM3155',4,154,'E207D861'","'D-18-06','172.16.32.246','iEM3155',5,158,'E207D838'","'D-18-06','172.16.32.246','iEM3155',6,157,'E207D849'","'D-18-07','172.16.32.247','iEM3155',1,155,'E207D5C1'","'D-18-07','172.16.32.247','iEM3155',2,150,'E207DAC6'","'D-18-07','172.16.32.247','iEM3155',3,152,'E207D5DE'","'D-18-07','172.16.32.247','iEM3155',4,153,'E207D9FE'","'D-18-07','172.16.32.247','iEM3155',5,151,'E207D5DF'","'D-18-07','172.16.32.247','iEM3155',6,154,'E207DA2B'","'D-18-08','172.16.32.248','iEM3155',1,151,'E207DB55'","'D-18-08','172.16.32.248','iEM3155',2,155,'E207DB11'","'D-18-08','172.16.32.248','iEM3155',3,154,'E207DB0E'","'D-18-08','172.16.32.248','iEM3155',4,153,'E207DB61'","'D-18-08','172.16.32.248','iEM3155',5,152,'E207DB5F'","'D-18-08','172.16.32.248','iEM3155',6,150,'E207DB46'","'D-18-09','172.16.32.249','iEM3355',1,153,'E20744E7'","'D-18-09','172.16.32.249','iEM3355',2,152,'E2074502'","'D-18-09','172.16.32.249','iEM3355',3,151,'E20744F2'","'D-18-09','172.16.32.249','iEM3355',4,150,'E20744F6'","'D-18-09','172.16.32.249','iEM3355',5,154,'E20744EA'","'D-18-10','172.16.32.250','iEM3155',1,152,'E207D85B'","'D-18-10','172.16.32.250','iEM3155',2,150,'E207D867'","'D-18-10','172.16.32.250','iEM3155',3,158,'E207DAB0'","'D-18-10','172.16.32.250','iEM3155',4,157,'E207D857'","'D-18-10','172.16.32.250','iEM3155',5,153,'E207D85A'","'D-18-10','172.16.32.250','iEM3155',6,155,'E207D813'","'D-19-01','172.16.33.11','iEM3155',1,157,'E207D5B7'","'D-19-01','172.16.33.11','iEM3155',2,153,'E207DB3E'","'D-19-01','172.16.33.11','iEM3155',3,160,'E207D5C6'","'D-19-01','172.16.33.11','iEM3155',4,159,'E207DB40'","'D-19-01','172.16.33.11','iEM3155',5,151,'E207D5D9'","'D-19-01','172.16.33.11','iEM3155',6,152,'E207DAFA'","'D-19-02','172.16.33.12','iEM3155',1,155,'E207DB39'","'D-19-02','172.16.33.12','iEM3155',2,152,'E207DB3B'","'D-19-02','172.16.33.12','iEM3155',3,150,'E207DB3D'","'D-19-02','172.16.33.12','iEM3155',4,151,'E207DB3A'","'D-19-02','172.16.33.12','iEM3155',5,153,'E207DB37'","'D-19-02','172.16.33.12','iEM3155',6,154,'E207DB0F'","'D-19-03','172.16.33.13','iEM3155',1,151,'E207D14B'","'D-19-03','172.16.33.13','iEM3155',2,150,'E207D123'","'D-19-03','172.16.33.13','iEM3155',3,154,'E207D15E'","'D-19-03','172.16.33.13','iEM3155',4,152,'E207D161'","'D-19-03','172.16.33.13','iEM3155',5,155,'E207D169'","'D-19-03','172.16.33.13','iEM3155',6,153,'E207D168'","'D-19-04','172.16.33.14','iEM3155',1,155,'E207DAFD'","'D-19-04','172.16.33.14','iEM3155',2,161,'E207DB44'","'D-19-04','172.16.33.14','iEM3155',3,156,'E207D5B6'","'D-19-04','172.16.33.14','iEM3155',4,159,'E207DB3F'","'D-19-04','172.16.33.14','iEM3155',5,152,'E207D5B8'","'D-19-04','172.16.33.14','iEM3155',6,151,'E207D5E4'","'D-19-05','172.16.33.15','iEM3155',1,150,'E207DA2E'","'D-19-05','172.16.33.15','iEM3155',2,155,'E207DABC'","'D-19-05','172.16.33.15','iEM3155',3,151,'E207DA7E'","'D-19-05','172.16.33.15','iEM3155',4,153,'E207DA7B'","'D-19-05','172.16.33.15','iEM3155',5,152,'E207D836'","'D-19-05','172.16.33.15','iEM3155',6,154,'E207D860'","'D-19-06','172.16.33.16','iEM3155',1,153,'E207DAA8'","'D-19-06','172.16.33.16','iEM3155',2,158,'E207D842'","'D-19-06','172.16.33.16','iEM3155',3,151,'E207DAB7'","'D-19-06','172.16.33.16','iEM3155',4,159,'E207DACD'","'D-19-06','172.16.33.16','iEM3155',5,157,'E207D83B'","'D-19-06','172.16.33.16','iEM3155',6,150,'E207D84C'","'D-19-07','172.16.33.17','iEM3155',1,150,'E207D11B'","'D-19-07','172.16.33.17','iEM3155',2,153,'E207D16D'","'D-19-07','172.16.33.17','iEM3155',3,151,'E207D14C'","'D-19-07','172.16.33.17','iEM3155',4,154,'E207D16A'","'D-19-07','172.16.33.17','iEM3155',5,155,'E207D11F'","'D-19-07','172.16.33.17','iEM3155',6,152,'E207D16C'","'D-19-08','172.16.33.18','iEM3155',1,152,'E207D83C'","'D-19-08','172.16.33.18','iEM3155',2,155,'E207DA70'","'D-19-08','172.16.33.18','iEM3155',3,154,'E207DAAA'","'D-19-08','172.16.33.18','iEM3155',4,153,'E207DA86'","'D-19-08','172.16.33.18','iEM3155',5,151,'E207DABA'","'D-19-08','172.16.33.18','iEM3155',6,150,'E207DABB'","'D-19-09','172.16.33.19','iEM3155',1,155,'E20744E8'","'D-19-09','172.16.33.19','iEM3155',2,154,'E20744F7'","'D-19-09','172.16.33.19','iEM3155',3,153,'E20744FC'","'D-19-09','172.16.33.19','iEM3155',4,156,'E20744F3'","'D-19-09','172.16.33.19','iEM3155',5,152,'E20744EC'","'D-19-10','172.16.33.20','iEM3155',1,151,'E207D5CE'","'D-19-10','172.16.33.20','iEM3155',2,152,'E207D5CD'","'D-19-10','172.16.33.20','iEM3155',3,153,'E207D5E1'","'D-19-10','172.16.33.20','iEM3155',4,155,'E207D5D5'","'D-19-10','172.16.33.20','iEM3155',5,150,'E207D9F1'","'D-19-10','172.16.33.20','iEM3155',6,154,'E207DAA2'");

$final = array_merge( array_merge($data , $data1) , $data2);
//dd($final);
/*  $data = array("'D-02-09','172.16.32.74','powerTag 1540',1,151,'E207557B'","'D-02-09','172.16.32.74','powerTag 1540',2,150,'E207460D'","'D-02-09','172.16.32.74','powerTag 1540',3,154,'E20758C7'","'D-02-09','172.16.32.74','powerTag 1540',4,153,'E20758BA'","'D-02-09','172.16.32.74','powerTag 1540',5,152,'E20758BA'","'D-03-09','172.16.32.54','powerTag 1540',1,154,'E20758A5'","'D-03-09','172.16.32.54','powerTag 1540',2,150,'E20758A8'","'D-03-09','172.16.32.54','powerTag 1540',3,152,'E2074608'","'D-03-09','172.16.32.54','powerTag 1540',4,151,'E2075580'","'D-03-09','172.16.32.54','powerTag 1540',5,153,'E20758B7'","'D-04-09','172.16.32.44','powerTag 1540',1,151,''","'D-04-09','172.16.32.44','powerTag 1540',2,152,''","'D-04-09','172.16.32.44','powerTag 1540',3,154,''","'D-04-09','172.16.32.44','powerTag 1540',4,150,''","'D-04-09','172.16.32.44','powerTag 1540',5,155,''","'C-06-09','172.16.32.116','powerTag 1520',1,155,'E207D530'","'C-06-09','172.16.32.116','powerTag 1520',2,153,'E207D51A'","'C-06-09','172.16.32.116','powerTag 1520',3,152,'E207D519'","'C-06-09','172.16.32.116','powerTag 1520',4,154,'E207D521'","'C-06-09','172.16.32.116','powerTag 1520',5,150,'E207D523'","'C-06-09','172.16.32.116','powerTag 1520',6,151,'E207D51E'","'D-07-04','172.16.32.9','powerTag 1520',1,154,''","'D-07-04','172.16.32.9','powerTag 1520',2,150,''","'D-07-04','172.16.32.9','powerTag 1520',3,155,''","'D-07-04','172.16.32.9','powerTag 1520',4,151,''","'D-07-04','172.16.32.9','powerTag 1520',5,153,''","'D-07-04','172.16.32.9','powerTag 1520',6,152,''","'D-07-09','172.16.32.14','powerTag 1540',1,151,'E2074612'","'D-07-09','172.16.32.14','powerTag 1540',2,152,'E207557A'","'D-07-09','172.16.32.14','powerTag 1540',3,154,'E207555B'","'D-07-09','172.16.32.14','powerTag 1540',4,153,'E207556E'","'D-07-09','172.16.32.14','powerTag 1540',5,150,'E2075575'","'D-08-09','172.16.32.64','powerTag 1540',1,153,'E2075562'","'D-08-09','172.16.32.64','powerTag 1540',2,150,'E2075579'","'D-08-09','172.16.32.64','powerTag 1540',3,152,'E20758C6'","'D-08-09','172.16.32.64','powerTag 1540',4,151,'E20758CB'","'D-08-09','172.16.32.64','powerTag 1540',5,154,'E2075566'");*/
  
  /*
//$data = array("'D-02-01','172.16.32.66','powerTag 1520',1,150,'E207D2C9'","'D-02-01','172.16.32.66','powerTag 1520',2,153,'E207D4D2'","'D-02-01','172.16.32.66','powerTag 1520',3,151,'E207D4BD'","'D-02-01','172.16.32.66','powerTag 1520',4,155,'E207D4B9'","'D-02-01','172.16.32.66','powerTag 1520',5,154,'E207D2C7'","'D-02-01','172.16.32.66','powerTag 1520',6,152,'E207D4C2'","'D-02-02','172.16.32.67','powerTag 1520',1,152,'E207DCCD'","'D-02-02','172.16.32.67','powerTag 1520',2,155,'E207DCD8'","'D-02-02','172.16.32.67','powerTag 1520',3,153,'E207DCCB'","'D-02-02','172.16.32.67','powerTag 1520',4,154,'E207DC9F'","'D-02-02','172.16.32.67','powerTag 1520',5,150,'E207DCA1'","'D-02-02','172.16.32.67','powerTag 1520',6,151,'E207DCC9'","'D-02-03','172.16.32.68','powerTag 1520',1,155,'E207DCDE'","'D-02-03','172.16.32.68','powerTag 1520',2,153,'E207DCE1'","'D-02-03','172.16.32.68','powerTag 1520',3,154,'E207DCDF'","'D-02-03','172.16.32.68','powerTag 1520',4,151,'E207DCC2'","'D-02-03','172.16.32.68','powerTag 1520',5,150,'E207DCC5'","'D-02-03','172.16.32.68','powerTag 1520',6,152,'E207DCD0'","'D-02-04','172.16.32.69','powerTag 1520',1,152,'E207DCD7'","'D-02-04','172.16.32.69','powerTag 1520',2,153,'E207DCCA'","'D-02-04','172.16.32.69','powerTag 1520',3,150,'E207DCE8'","'D-02-04','172.16.32.69','powerTag 1520',4,155,'E207DCDC'","'D-02-04','172.16.32.69','powerTag 1520',5,154,'E207DCDB'","'D-02-04','172.16.32.69','powerTag 1520',6,151,'E207DCE9'","'D-02-05','172.16.32.70','powerTag 1520',1,152,'E207DCD2'","'D-02-05','172.16.32.70','powerTag 1520',2,153,'E207DCE4'","'D-02-05','172.16.32.70','powerTag 1520',3,150,'E207DCA7'","'D-02-05','172.16.32.70','powerTag 1520',4,155,'E207DCD9'","'D-02-05','172.16.32.70','powerTag 1520',5,151,'E207DCB4'","'D-02-05','172.16.32.70','powerTag 1520',6,154,'E207DCC0'","'D-02-06','172.16.32.71','powerTag 1520',1,154,'E207DCCC'","'D-02-06','172.16.32.71','powerTag 1520',2,155,'E207DCA6'","'D-02-06','172.16.32.71','powerTag 1520',3,150,'E207DCD1'","'D-02-06','172.16.32.71','powerTag 1520',4,153,'E207DCD5'","'D-02-06','172.16.32.71','powerTag 1520',5,151,'E207DCC6'","'D-02-06','172.16.32.71','powerTag 1520',6,152,'E207DCC4'","'D-02-07','172.16.32.72','powerTag 1520',1,150,'E207DCE3'","'D-02-07','172.16.32.72','powerTag 1520',2,152,'E207DCCF'","'D-02-07','172.16.32.72','powerTag 1520',3,153,'E207DCDD'","'D-02-07','172.16.32.72','powerTag 1520',4,151,'E207DCE5'","'D-02-07','172.16.32.72','powerTag 1520',5,155,'E207DCE6'","'D-02-07','172.16.32.72','powerTag 1520',6,154,'E207DCE0'","'D-02-08','172.16.32.73','powerTag 1520',1,152,'E207DCC1'","'D-02-08','172.16.32.73','powerTag 1520',2,155,'E207DCAE'","'D-02-08','172.16.32.73','powerTag 1520',3,154,'E207DCCE'","'D-02-08','172.16.32.73','powerTag 1520',4,150,'E207DCBF'","'D-02-08','172.16.32.73','powerTag 1520',5,153,'E207DCBF'","'D-02-08','172.16.32.73','powerTag 1520',6,151,'E207DCD3'","'D-02-10','172.16.32.75','powerTag 1520',1,155,'E207DCC7'","'D-02-10','172.16.32.75','powerTag 1520',2,154,'E207DCE7'","'D-02-10','172.16.32.75','powerTag 1520',3,153,'E207DCBB'","'D-02-10','172.16.32.75','powerTag 1520',4,152,'E207DCAF'","'D-02-10','172.16.32.75','powerTag 1520',5,151,'E207DCEA'","'D-02-10','172.16.32.75','powerTag 1520',6,150,'E207DCE2'","'D-03-01','172.16.32.46','powerTag 1520',1,154,'E207D4C7'","'D-03-01','172.16.32.46','powerTag 1520',2,150,'E207D2B4'","'D-03-01','172.16.32.46','powerTag 1520',3,152,'E207D4CE'","'D-03-01','172.16.32.46','powerTag 1520',4,151,'E207D2CD'","'D-03-01','172.16.32.46','powerTag 1520',5,155,'E207D4C5'","'D-03-01','172.16.32.46','powerTag 1520',6,153,'E207D4D1'","'D-03-02','172.16.32.47','powerTag 1520',1,154,'E207D4C3'","'D-03-02','172.16.32.47','powerTag 1520',2,152,'E207D4D0'","'D-03-02','172.16.32.47','powerTag 1520',3,155,'E207D4D3'","'D-03-02','172.16.32.47','powerTag 1520',4,150,'E207D4BF'","'D-03-02','172.16.32.47','powerTag 1520',5,151,'E207D2BA'","'D-03-02','172.16.32.47','powerTag 1520',6,153,'E207D4C9'","'D-03-03','172.16.32.48','powerTag 1520',1,153,'E207D4CF'","'D-03-03','172.16.32.48','powerTag 1520',2,150,'E207D4C8'","'D-03-03','172.16.32.48','powerTag 1520',3,155,'E207D4C4'","'D-03-03','172.16.32.48','powerTag 1520',4,154,'E207D4D5'","'D-03-03','172.16.32.48','powerTag 1520',5,151,'E207D2CB'","'D-03-03','172.16.32.48','powerTag 1520',6,152,'E207D4CD'","'D-03-04','172.16.32.49','powerTag 1520',1,150,'E207D23D'","'D-03-04','172.16.32.49','powerTag 1520',2,152,'E207D235'","'D-03-04','172.16.32.49','powerTag 1520',3,153,'E207D271'","'D-03-04','172.16.32.49','powerTag 1520',4,151,'E207D242'","'D-03-04','172.16.32.49','powerTag 1520',5,155,'E207D276'","'D-03-04','172.16.32.49','powerTag 1520',6,154,'E207D274'","'D-03-05','172.16.32.50','powerTag 1520',1,152,'E207D2B7'","'D-03-05','172.16.32.50','powerTag 1520',2,151,'E207D241'","'D-03-05','172.16.32.50','powerTag 1520',3,153,'E207D256'","'D-03-05','172.16.32.50','powerTag 1520',4,155,'E207D2C2'","'D-03-05','172.16.32.50','powerTag 1520',5,150,'E207D2C2'","'D-03-05','172.16.32.50','powerTag 1520',6,154,'E207D249'","'D-03-06','172.16.32.51','powerTag 1520',1,152,'E207D2B3'","'D-03-06','172.16.32.51','powerTag 1520',2,151,'E207D2B6'","'D-03-06','172.16.32.51','powerTag 1520',3,150,'E207D25C'","'D-03-06','172.16.32.51','powerTag 1520',4,153,'E207D25F'","'D-03-06','172.16.32.51','powerTag 1520',5,154,'E207D25E'","'D-03-06','172.16.32.51','powerTag 1520',6,155,'E207D2B2'","'D-03-07','172.16.32.52','powerTag 1520',1,154,'E207D2C5'","'D-03-07','172.16.32.52','powerTag 1520',2,153,'E207D2B0'","'D-03-07','172.16.32.52','powerTag 1520',3,155,'E207D2C6'","'D-03-07','172.16.32.52','powerTag 1520',4,150,'E207D4CA'","'D-03-07','172.16.32.52','powerTag 1520',5,151,'E207D2B8'","'D-03-07','172.16.32.52','powerTag 1520',6,152,'E207D2AF'","'D-03-08','172.16.32.53','powerTag 1520',1,151,'E207D4CC'","'D-03-08','172.16.32.53','powerTag 1520',2,152,'E207D4D4'","'D-03-08','172.16.32.53','powerTag 1520',3,154,'E207D4C1'","'D-03-08','172.16.32.53','powerTag 1520',4,150,'E207D4CB'","'D-03-08','172.16.32.53','powerTag 1520',5,155,'E207D4C6'","'D-03-08','172.16.32.53','powerTag 1520',6,153,'E207D4C6'","'D-03-10','172.16.32.55','powerTag 1520',1,150,'E207D2B5'","'D-03-10','172.16.32.55','powerTag 1520',2,152,'E207D2BD'","'D-03-10','172.16.32.55','powerTag 1520',3,154,'E207D2C3'","'D-03-10','172.16.32.55','powerTag 1520',4,151,'E207D2C3'","'D-03-10','172.16.32.55','powerTag 1520',5,153,'E207D2C3'","'D-03-10','172.16.32.55','powerTag 1520',6,155,'E207D2C1'","'D-04-01','172.16.32.36','powerTag 1520',1,154,'E207D5A2'","'D-04-01','172.16.32.36','powerTag 1520',2,151,'E207D5A3'","'D-04-01','172.16.32.36','powerTag 1520',3,153,'E207D5AE'","'D-04-01','172.16.32.36','powerTag 1520',4,152,'E207D596'","'D-04-01','172.16.32.36','powerTag 1520',5,155,'E207D59B'","'D-04-01','172.16.32.36','powerTag 1520',6,150,'E207D5A1'","'D-04-02','172.16.32.37','powerTag 1520',1,150,'E207D5A8'","'D-04-02','172.16.32.37','powerTag 1520',2,155,'E207D59C'","'D-04-02','172.16.32.37','powerTag 1520',3,151,'E207D5AA'","'D-04-02','172.16.32.37','powerTag 1520',4,154,'E207D5AD'","'D-04-02','172.16.32.37','powerTag 1520',5,152,'E207D591'","'D-04-02','172.16.32.37','powerTag 1520',6,153,'E207D581'","'D-04-03','172.16.32.38','powerTag 1520',1,150,'E207D268'","'D-04-03','172.16.32.38','powerTag 1520',2,153,'E207D26E'","'D-04-03','172.16.32.38','powerTag 1520',3,151,'E207D25A'","'D-04-03','172.16.32.38','powerTag 1520',4,152,'E207D236'","'D-04-03','172.16.32.38','powerTag 1520',5,155,'E207D236'","'D-04-03','172.16.32.38','powerTag 1520',6,154,'E207D24A'","'D-04-04','172.16.32.39','powerTag 1520',1,155,'E207D25D'","'D-04-04','172.16.32.39','powerTag 1520',2,150,'E207D278'","'D-04-04','172.16.32.39','powerTag 1520',3,154,'E207D263'","'D-04-04','172.16.32.39','powerTag 1520',4,153,'E207D260'","'D-04-04','172.16.32.39','powerTag 1520',5,152,'E207D257'","'D-04-04','172.16.32.39','powerTag 1520',6,151,'E207D243'","'D-04-05','172.16.32.40','powerTag 1520',1,154,'E207D24D'","'D-04-05','172.16.32.40','powerTag 1520',2,150,'E207D5AC'","'D-04-05','172.16.32.40','powerTag 1520',3,155,'E207D5B1'","'D-04-05','172.16.32.40','powerTag 1520',4,153,'E207D5B1'","'D-04-05','172.16.32.40','powerTag 1520',5,151,'E207D57C'","'D-04-05','172.16.32.40','powerTag 1520',6,152,'E207D5A9'","'D-04-06','172.16.32.41','powerTag 1520',1,155,'E207D59F'","'D-04-06','172.16.32.41','powerTag 1520',2,154,'E207D59E'","'D-04-06','172.16.32.41','powerTag 1520',3,151,'E207D593'","'D-04-06','172.16.32.41','powerTag 1520',4,153,'E207D5AB'","'D-04-06','172.16.32.41','powerTag 1520',5,150,'E207D5A0'","'D-04-06','172.16.32.41','powerTag 1520',6,152,'E207D597'","'D-04-07','172.16.32.42','powerTag 1520',1,153,'E207D589'","'D-04-07','172.16.32.42','powerTag 1520',2,151,'E207D58F'","'D-04-07','172.16.32.42','powerTag 1520',3,154,'E207D5B0'","'D-04-07','172.16.32.42','powerTag 1520',4,155,'E207D58B'","'D-04-07','172.16.32.42','powerTag 1520',5,150,'E207D58B'","'D-04-07','172.16.32.42','powerTag 1520',6,152,'E207D5A6'","'D-04-08','172.16.32.43','powerTag 1520',1,150,'E207D253'","'D-04-08','172.16.32.43','powerTag 1520',2,153,'E207D262'","'D-04-08','172.16.32.43','powerTag 1520',3,155,'E207D261'","'D-04-08','172.16.32.43','powerTag 1520',4,152,'E207D255'","'D-04-08','172.16.32.43','powerTag 1520',5,151,'E207D252'","'D-04-08','172.16.32.43','powerTag 1520',6,154,'E207D24F'","'D-04-10','172.16.32.45','powerTag 1520',1,153,'E207D244'","'D-04-10','172.16.32.45','powerTag 1520',2,152,'E207D258'","'D-04-10','172.16.32.45','powerTag 1520',3,154,'E207D25B'","'D-04-10','172.16.32.45','powerTag 1520',4,150,'E207D251'","'D-04-10','172.16.32.45','powerTag 1520',5,155,'E207D245'","'D-04-10','172.16.32.45','powerTag 1520',6,151,'E207D259'","'D-05-01','172.16.32.26','powerTag 1520',1,150,'E207D592'","'D-05-01','172.16.32.26','powerTag 1520',2,152,'E207D265'","'D-05-01','172.16.32.26','powerTag 1520',3,154,'E207D277'","'D-05-01','172.16.32.26','powerTag 1520',4,153,'E207D594'","'D-05-01','172.16.32.26','powerTag 1520',5,151,'E207D267'","'D-05-01','172.16.32.26','powerTag 1520',6,155,'E207D272'","'D-05-02','172.16.32.27','powerTag 1520',1,155,'E207D586'","'D-05-02','172.16.32.27','powerTag 1520',2,151,'E207D58D'","'D-05-02','172.16.32.27','powerTag 1520',3,152,'E207D57D'","'D-05-02','172.16.32.27','powerTag 1520',4,153,'E207D580'","'D-05-02','172.16.32.27','powerTag 1520',5,154,'E207D57F'","'D-05-02','172.16.32.27','powerTag 1520',6,150,'E207D1F7'","'D-05-03','172.16.32.28','powerTag 1520',1,152,'E207D1F9'","'D-05-03','172.16.32.28','powerTag 1520',2,151,'E207D205'","'D-05-03','172.16.32.28','powerTag 1520',3,155,'E207D210'","'D-05-03','172.16.32.28','powerTag 1520',4,150,'E207D1DF'","'D-05-03','172.16.32.28','powerTag 1520',5,153,'E207D206'","'D-05-03','172.16.32.28','powerTag 1520',6,154,'E207D1FA'","'D-05-04','172.16.32.29','powerTag 1520',1,151,'E207D1FA'","'D-05-04','172.16.32.29','powerTag 1520',2,150,'E207D1FA'","'D-05-04','172.16.32.29','powerTag 1520',3,152,'E207D595'","'D-05-04','172.16.32.29','powerTag 1520',4,154,'E207D595'","'D-05-04','172.16.32.29','powerTag 1520',5,153,'E207D587'","'D-05-04','172.16.32.29','powerTag 1520',6,155,'E207D585'","'D-05-05','172.16.32.30','powerTag 1520',1,152,'E207D57E'","'D-05-05','172.16.32.30','powerTag 1520',2,155,'E207D58A'","'D-05-05','172.16.32.30','powerTag 1520',3,150,'E207D5A5'","'D-05-05','172.16.32.30','powerTag 1520',4,154,'E207D588'","'D-05-05','172.16.32.30','powerTag 1520',5,151,'E207D590'","'D-05-05','172.16.32.30','powerTag 1520',6,153,'E207D599'","'D-05-06','172.16.32.31','powerTag 1520',1,151,'E207D1F2'","'D-05-06','172.16.32.31','powerTag 1520',2,153,'E207D207'","'D-05-06','172.16.32.31','powerTag 1520',3,150,'E207D1F6'","'D-05-06','172.16.32.31','powerTag 1520',4,155,'E207D20D'","'D-05-06','172.16.32.31','powerTag 1520',5,154,'E207D1FE'",
"'D-05-06','172.16.32.31','powerTag 1520',6,152,'E207D20C'","'D-05-07','172.16.32.32','powerTag 1520',1,154,'E207D1E7'","'D-05-07','172.16.32.32','powerTag 1520',2,152,'E207D1E6'","'D-05-07','172.16.32.32','powerTag 1520',3,155,'E207D1DB'","'D-05-07','172.16.32.32','powerTag 1520',4,150,'E207D1E1'","'D-05-07','172.16.32.32','powerTag 1520',5,151,'E207D1E4'","'D-05-07','172.16.32.32','powerTag 1520',6,153,'E207D1E2'","'D-05-08','172.16.32.33','powerTag 1520',1,150,'E207D1E2'","'D-05-08','172.16.32.33','powerTag 1520',2,154,'E207D1F3'","'D-05-08','172.16.32.33','powerTag 1520',3,152,'E207D208'","'D-05-08','172.16.32.33','powerTag 1520',4,155,'E207D1FF'","'D-05-08','172.16.32.33','powerTag 1520',5,153,'E207D1E0'","'D-05-08','172.16.32.33','powerTag 1520',6,151,'E207D203'","'D-05-10','172.16.32.35','powerTag 1520',1,153,'E207D1F4'","'D-05-10','172.16.32.35','powerTag 1520',2,150,'E207D209'","'D-05-10','172.16.32.35','powerTag 1520',3,151,'E207D201'","'D-05-10','172.16.32.35','powerTag 1520',4,154,'E207D1DC'","'D-05-10','172.16.32.35','powerTag 1520',5,155,'E207D20E'","'D-05-10','172.16.32.35','powerTag 1520',6,152,'E207D1F1'","'D-06-01','172.16.32.16','powerTag 1520',1,151,'E207D192'","'D-06-01','172.16.32.16','powerTag 1520',2,154,'E207D19D'","'D-06-01','172.16.32.16','powerTag 1520',3,153,'E207D157'","'D-06-01','172.16.32.16','powerTag 1520',4,152,'E207D17C'","'D-06-01','172.16.32.16','powerTag 1520',5,150,'E207D11E'","'D-06-01','172.16.32.16','powerTag 1520',6,155,'E207D175'","'D-06-02','172.16.32.17','powerTag 1520',1,152,'E207D1DA'","'D-06-02','172.16.32.17','powerTag 1520',2,154,'E207D1ED'","'D-06-02','172.16.32.17','powerTag 1520',3,150,'E207D1F0'","'D-06-02','172.16.32.17','powerTag 1520',4,153,'E207D1EB'","'D-06-02','172.16.32.17','powerTag 1520',5,155,'E207D1EF'","'D-06-02','172.16.32.17','powerTag 1520',6,151,'E207D1E3'","'D-06-03','172.16.32.18','powerTag 1520',1,153,'E207D16F'","'D-06-03','172.16.32.18','powerTag 1520',2,155,'E207D173'","'D-06-03','172.16.32.18','powerTag 1520',3,152,'E207DC67'","'D-06-03','172.16.32.18','powerTag 1520',4,154,'E207D19A'","'D-06-03','172.16.32.18','powerTag 1520',5,150,'E207D19A'","'D-06-03','172.16.32.18','powerTag 1520',6,151,'E207D18C'","'D-06-04','172.16.32.19','powerTag 1520',1,152,'E207D189'","'D-06-04','172.16.32.19','powerTag 1520',2,150,'E207D170'","'D-06-04','172.16.32.19','powerTag 1520',3,151,'E207D195'","'D-06-04','172.16.32.19','powerTag 1520',4,153,'E207D17D'","'D-06-04','172.16.32.19','powerTag 1520',5,155,'E207D197'","'D-06-04','172.16.32.19','powerTag 1520',6,154,'E207D194'","'D-06-06','172.16.32.21','powerTag 1520',1,151,'E207DC85'","'D-06-06','172.16.32.21','powerTag 1520',2,150,'E207DC80'","'D-06-06','172.16.32.21','powerTag 1520',3,155,'E207DC89'","'D-06-06','172.16.32.21','powerTag 1520',4,152,'E207DC80'","'D-06-06','172.16.32.21','powerTag 1520',5,153,'E207DC8A'","'D-06-06','172.16.32.21','powerTag 1520',6,154,'E207DC77'","'D-06-07','172.16.32.22','powerTag 1520',1,154,'E207D199'","'D-06-07','172.16.32.22','powerTag 1520',2,153,'E207D196'","'D-06-07','172.16.32.22','powerTag 1520',3,151,'E207D193'","'D-06-07','172.16.32.22','powerTag 1520',4,150,'E207DC77'","'D-06-07','172.16.32.22','powerTag 1520',5,152,'E207D171'","'D-06-07','172.16.32.22','powerTag 1520',6,155,'E207D176'","'D-06-08','172.16.32.23','powerTag 1520',1,154,'E207D1E8'","'D-06-08','172.16.32.23','powerTag 1520',2,150,'E207D1DD'","'D-06-08','172.16.32.23','powerTag 1520',3,155,'E207D1EA'","'D-06-08','172.16.32.23','powerTag 1520',4,151,'E207D204'","'D-06-08','172.16.32.23','powerTag 1520',5,152,'E207D1E9'","'D-06-08','172.16.32.23','powerTag 1520',6,153,'E207D1EC'","'D-06-10','172.16.32.25','powerTag 1520',1,151,'E207DC6F'","'D-06-10','172.16.32.25','powerTag 1520',2,154,'E207DC73'","'D-06-10','172.16.32.25','powerTag 1520',3,150,'E207DC6D'","'D-06-10','172.16.32.25','powerTag 1520',4,153,'E207DC72'","'D-06-10','172.16.32.25','powerTag 1520',5,152,'E207DC71'","'D-06-10','172.16.32.25','powerTag 1520',6,155,'E207DC75'","'D-07-01','172.16.32.12','powerTag 1520',1,152,'E207DC7C'","'D-07-01','172.16.32.12','powerTag 1520',2,153,'E207DC86'","'D-07-01','172.16.32.12','powerTag 1520',3,154,'E207DC7A'","'D-07-01','172.16.32.12','powerTag 1520',4,155,'E207DC7B'","'D-07-01','172.16.32.12','powerTag 1520',5,150,'E207DC6C'","'D-07-01','172.16.32.12','powerTag 1520',6,151,'E207DC74'","'D-07-02','172.16.32.11','powerTag 1520',1,154,'E207DC61'","'D-07-02','172.16.32.11','powerTag 1520',2,155,'E207DC59'","'D-07-02','172.16.32.11','powerTag 1520',3,153,'E207DC6'","'D-07-02','172.16.32.11','powerTag 1520',4,150,'E207DC64'","'D-07-02','172.16.32.11','powerTag 1520',5,152,'E207DC69'","'D-07-02','172.16.32.11','powerTag 1520',6,151,'E207DC5B'","'D-07-03','172.16.32.10','powerTag 1520',1,155,'E207DC30'","'D-07-03','172.16.32.10','powerTag 1520',2,152,'E207D1DE'","'D-07-03','172.16.32.10','powerTag 1520',3,150,'E207DC32'","'D-07-03','172.16.32.10','powerTag 1520',4,151,'E207DC5E'","'D-07-03','172.16.32.10','powerTag 1520',5,154,'E207DC62'","'D-07-03','172.16.32.10','powerTag 1520',6,153,'E207DC5A'","'D-07-05','172.16.32.8','powerTag 1520,1,154,'E207DC5D'","'D-07-05','172.16.32.8','powerTag 1520,2,151,'E207DC31'","'D-07-05','172.16.32.8','powerTag 1520,3,152,'E207DC2B'","'D-07-05','172.16.32.8','powerTag 1520,4,153,'E207DC60'","'D-07-05','172.16.32.8','powerTag 1520,5,155,'E207DC5C'","'D-07-05','172.16.32.8','powerTag 1520,6,150,'E207DC5C'","'D-07-06','172.16.32.7','powerTag 1520,1,150,'E207D18A'","'D-07-06','172.16.32.7','powerTag 1520,2,154,'E207D179'","'D-07-06','172.16.32.7','powerTag 1520,3,151,'E207D16E'","'D-07-06','172.16.32.7','powerTag 1520,4,155,'E207D19C'","'D-07-06','172.16.32.7','powerTag 1520,5,152,'E207D179'","'D-07-06','172.16.32.7','powerTag 1520,6,153,'E207D178'","'D-07-07','172.16.32.6','PowerTag 1520,1,152,'E207D18E'","'D-07-07','172.16.32.6','PowerTag 1520,2,150,'E207D18E'","'D-07-07','172.16.32.6','PowerTag 1520,3,155,'E207DBEF'","'D-07-07','172.16.32.6','PowerTag 1520,4,153,'E207DBEC'","'D-07-07','172.16.32.6','PowerTag 1520,5,154,'E207DBF2'","'D-07-07','172.16.32.6','PowerTag 1520,6,151,'E207DC1C'","'D-07-08','172.16.32.13','powerTag 1520',1, 154,  'E207DC78'","'D-07-08','172.16.32.13','powerTag 1520',2,  152,  'E207D20A'","'D-07-08','172.16.32.13','powerTag 1520',3,  151,  'E207DC6B'","'D-07-08','172.16.32.13','powerTag 1520',4,  155,  'E207DC6E'","'D-07-08','172.16.32.13','powerTag 1520',5,  153,  'E207DC68'","'D-07-08','172.16.32.13','powerTag 1520',6,  150,  'E207DC7E'","'D-07-10','172.16.32.15','powerTag 1520',1,  150,  'E207D162'","'D-07-10','172.16.32.15','powerTag 1520',2,  152,  'E207D19F'","'D-07-10','172.16.32.15','powerTag 1520',3,  153,  'E207D18F'","'D-07-10','172.16.32.15','powerTag 1520',4,  154,  'E207D18F'","'D-07-10','172.16.32.15','powerTag 1520',5,  151,  'E207D151'","'D-07-10','172.16.32.15','powerTag 1520',6,  155,  'E207D188'","'D-08-01','172.16.32.56','powerTag 1520',1,  152,  'E207D5FA'","'D-08-01','172.16.32.56','powerTag 1520',2,  153,  'E207D5EF'","'D-08-01','172.16.32.56','powerTag 1520',3,  151,  'E207D5F5'","'D-08-01','172.16.32.56','powerTag 1520',4,  155,  'E207D5FC'","'D-08-01','172.16.32.56','powerTag 1520',5,  150,  'E207D5F2'","'D-08-01','172.16.32.56','powerTag 1520',6,  154,  'E207D5E7'","'D-08-02','172.16.32.57','powerTag 1520',1,  152,  'E207DBF6'","'D-08-02','172.16.32.57','powerTag 1520',2,  151,  'E207DC07'","'D-08-02','172.16.32.57','powerTag 1520',3,  155,  'E207D604'","'D-08-02','172.16.32.57','powerTag 1520',4,  150,  'E207DC03'","'D-08-02','172.16.32.57','powerTag 1520',5,  153,  'E207D5FF'","'D-08-02','172.16.32.57','powerTag 1520',6,  154,  'E207D5FE'","'D-08-03','172.16.32.58','powerTag 1520',1,  151,  'E207D5FE'","'D-08-03','172.16.32.58','powerTag 1520',2,  152,  'E207D619'","'D-08-03','172.16.32.58','powerTag 1520',3,  150,  'E207D614'","'D-08-03','172.16.32.58','powerTag 1520',4,  154,  'E207D60A'","'D-08-03','172.16.32.58','powerTag 1520',5,  153,  'E207D618'","'D-08-03','172.16.32.58','powerTag 1520',6,  150,  'E207D60D'","'D-08-04','172.16.32.59','powerTag 1520',1,  150,  'E207DBF1'","'D-08-04','172.16.32.59','powerTag 1520',2,  155,  'E207DC11'","'D-08-04','172.16.32.59','powerTag 1520',3,  152,  'E207DBED'","'D-08-04','172.16.32.59','powerTag 1520',4,  154,  'E207DBE9'","'D-08-04','172.16.32.59','powerTag 1520',5,  153,  'E207DBF7'","'D-08-04','172.16.32.59','powerTag 1520',6,  151,  'E207DC17'","'D-08-05','172.16.32.60','powerTag 1520',1,  150,  'E207DC14'","'D-08-05','172.16.32.60','powerTag 1520',2,  155,  'E207DC10'","'D-08-05','172.16.32.60','powerTag 1520',3,  152,  'E207DC09'","'D-08-05','172.16.32.60','powerTag 1520',4,  154,  'E207DBFA'","'D-08-05','172.16.32.60','powerTag 1520',5,  151,  'E207DBF3'","'D-08-05','172.16.32.60','powerTag 1520',6,  153,  'E207DC1D'","'D-08-06','172.16.32.61','powerTag 1520',1,  155,  'E207DC12'","'D-08-06','172.16.32.61','powerTag 1520',2,  154,  'E207DBFD'","'D-08-06','172.16.32.61','powerTag 1520',3,  152,  'E207DC1F'","'D-08-06','172.16.32.61','powerTag 1520',4,  151,  'E207DC05'","'D-08-06','172.16.32.61','powerTag 1520',5,  150,  'E207DC15'","'D-08-06','172.16.32.61','powerTag 1520',6,  153,  'E207DC15'","'D-08-07','172.16.32.62','powerTag 1520',1,  155,  'E207DBFC'","'D-08-07','172.16.32.62','powerTag 1520',2,  154,  'E207DBF9'","'D-08-07','172.16.32.62','powerTag 1520',3,  151,  'E207DC00'","'D-08-07','172.16.32.62','powerTag 1520',4,  152,  'E207DC04'","'D-08-07','172.16.32.62','powerTag 1520',5,  150,  'E207DC01'","'D-08-07','172.16.32.62','powerTag 1520',6,  153,  'E207DC1A'","'D-08-08','172.16.32.63','powerTag 1520',1,  151,  'E207DC1A'","'D-08-08','172.16.32.63','powerTag 1520',2,  150,  'E207DC19'","'D-08-08','172.16.32.63','powerTag 1520',3,  154,  'E207DC0E'","'D-08-08','172.16.32.63','powerTag 1520',4,  153,  'E207DC0F'","'D-08-08','172.16.32.63','powerTag 1520',5,  155,  'E207DBFE'","'D-08-08','172.16.32.63','powerTag 1520',6,  152,  'E207DC0A'","'D-08-10','172.16.32.65','powerTag 1520',1,  154,  'E207DC1E'","'D-08-10','172.16.32.65','powerTag 1520',2,  152,  'E207DC08'","'D-08-10','172.16.32.65','powerTag 1520',3,  155,  'E207DC08'","'D-08-10','172.16.32.65','powerTag 1520',4,  153,  'E207DBEB'","'D-08-10','172.16.32.65','powerTag 1520',5,  151,  'E207DC02'","'D-08-10','172.16.32.65','powerTag 1520',6,  150,  'E207DC16'");*/

  $return['bulk-upload'] = array();
  $temp ;
  $mapper = [0=>'unit' , 1=>'ip_address' , 2=>'device_name' , 3=>'room_no' , 4=>'modbus_address' , 5=>'rf_id'];
  $counter= 0;
  //dd($data);
  foreach($final as $item)
  {
    //echo $item."<br>";
    $temp_1 = explode("," ,$item);
    if(!is_array($temp_1))
    {
      continue;
    } 
    //echo json_encode($temp_1)."<br>";
    $room = Room::getRoomByHouseRoomName($temp_1[0] , $temp_1[3]);
    foreach ($temp_1 as $mapper_key => $meter_value)
    {
      //echo $mapper_key.'='.json_encode($meter_value)."<br>";
      if(!isset($room['id']))
      {
        continue;
      }

      //$temp[$room['id']][$mapper[$mapper_key]] = trim(str_replace("'", "",$meter_value));

      $temp['bulk_upload'][$room['id_house_room']][$mapper[$mapper_key]] = trim(str_replace("'", "",$meter_value));
    }

    $temp['bulk_upload'][$room['id_house_room']]['leaf_room_id'] = $room['id_house_room']; 
    
  
    
    //echo $counter."<br>";$counter++;
    
  }

    $x = MeterRegister::saveOrUpdateMeterRegisters($temp);
   // unset($temp);
   // unset($temp_1);
    //unset($room);

dd($x);
  dd("Done"); 

}); 

/*

Route::get('createAccount', function ()
{ 
   $house   =   new House();
   $fdata      =   $house->get_houses(true);
   $listing    =   array('period_member' => array(),'start_date_sequence' => array());
   const closing_account_no_included_keys = ['id' , 'created_at', 'updated_at' , 'status']
   const single_room_closing_account_reset_keys = ['total_usage_kwh' , 'total_payable_amount' , 'total_paid_amount' , 'total_outstanding_amount' , 'total_subsidy_amount' , 'current_credit_amount' , 'current_balance_kwh'];
   const new_room_true_keys = ['status','is_power_supply_on'];
  if ($fdata['status_code']) {
      if (isset($fdata['house']) && $houses = $fdata['house']) {
          foreach ($houses as $house) {
          foreach($house['house_rooms'] as $room){ 
dd($room);        if($room['house_room_type'] == 'single'){
              $closing_account;

              //rearrange member list sequences
              //$room['house_room_members']


              foreach ($room['house_room_members'] as $member)
              {
                //initialize change room model
                if(isset($closing_account['id']))
                { 
                  foreach ($closing_account as $key => $value)
                  {
                    if(!in_array($key , static::closing_account_no_included_keys))
                    {
                      $model[$key] = $value;
                    }
                  }

                  foreach (static::single_room_closing_account_reset_keys as $reset_key)
                  {
                    $model[$reset_key] = 0;
                  }

                  foreach (static::new_room_true_keys as $true_key)
                  {
                    $model[$true_key] = true;
                  }
                }



                //calculation
                $payment_listing  = MeterPaymentReceived::($room['leaf_room_id'], $date_range);
                $subsidy_listing  = MeterPaymentReceived::($room['leaf_room_id'], $date_range);
                $monthly_usage_listing = MeterReading::($date_range);
                foreach ($payment_listing as $row)
                {
                  $total_paid_amount += $row['amount'];
                }

                
                foreach ($monthly_usage_listing as $row)
                {
                  $total_usage_kwh += $row['amount'];
                }
                
                $model['subsidy_ids'] = array_column($subsidy_listing , 'id');
                //single room cancel credit when in ou't
                $model['total_usage_kwh'] = $total_usage_kwh ;
                $model['total_payable_amount'] = Setting::calculate_utility_fee($model['total_usage_kwh'];
                $model['total_paid_amount'] = $total_paid_amount ;
                $model['total_outstanding_amount'] = $model['total_paid_amount'] - $model['total_payable_amount'] < 0 ? 0 : $model['total_payable_amount'] - $model['total_paid_amount'] ;
                $model['total_outstanding_kwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($model['total_outstanding_amount']);
                //fix later
                $model['total_subsidy_amount'] = $total_subsidy_amount;
                $model['current_credit_amount'] = $model['total_paid_amount'] + $model['total_subsidy_amount'] - $model['total_payable_amount'];
                $model['current_balance_kwh'] =  Setting::convert_balance_to_kwh_by_current_usage_and_balance($model['current_credit_amount']);
                $model['room_type'] = 'single';
                $model['status'] = $member[''] == true ? false : true;
                $model->save();
                $closing_account = clone $model;

              }
              $power_meter_account = PowerMeterAccount::getModelByIdHouseRoom($room['id_house_room']);
              if($power_meter_account[])
            }else if($room['house_room_type'] == 'twin'){

            }
          }
        }
      }
    }

}); */




Route::get('createMeterRegister', function ()
{ 
  
  
$data = array(
"'D-06-05','172.16.32.20','powerTag 1520',1,150,''",  
"'D-06-05','172.16.32.20','powerTag 1520',2,152,''",  
"'D-06-05','172.16.32.20','powerTag 1520',3,154,''",  
"'D-06-05','172.16.32.20','powerTag 1520',4,155,''",  
"'D-06-05','172.16.32.20','powerTag 1520',5,151,''",  
"'D-06-05','172.16.32.20','powerTag 1520',6,153,''",  
"'D-07-04','172.16.32.9','powerTag 1520',1,154,''", 
"'D-07-04','172.16.32.9','powerTag 1520',2,150,''", 
"'D-07-04','172.16.32.9','powerTag 1520',3,155,''", 
"'D-07-04','172.16.32.9','powerTag 1520',4,151,''", 
"'D-07-04','172.16.32.9','powerTag 1520',5,153,''", 
"'D-07-04','172.16.32.9','powerTag 1520',6,152,''", 
"'D-02-01','172.16.32.66','powerTag 1520',1,150,'E207D2C9'","'D-02-01','172.16.32.66','powerTag 1520',2,153,'E207D4D2'","'D-02-01','172.16.32.66','powerTag 1520',3,151,'E207D4BD'","'D-02-01','172.16.32.66','powerTag 1520',4,155,'E207D4B9'","'D-02-01','172.16.32.66','powerTag 1520',5,154,'E207D2C7'","'D-02-01','172.16.32.66','powerTag 1520',6,152,'E207D4C2'","'D-02-02','172.16.32.67','powerTag 1520',1,152,'E207DCCD'","'D-02-02','172.16.32.67','powerTag 1520',2,155,'E207DCD8'","'D-02-02','172.16.32.67','powerTag 1520',3,153,'E207DCCB'","'D-02-02','172.16.32.67','powerTag 1520',4,154,'E207DC9F'","'D-02-02','172.16.32.67','powerTag 1520',5,150,'E207DCA1'","'D-02-02','172.16.32.67','powerTag 1520',6,151,'E207DCC9'","'D-02-03','172.16.32.68','powerTag 1520',1,155,'E207DCDE'","'D-02-03','172.16.32.68','powerTag 1520',2,153,'E207DCE1'","'D-02-03','172.16.32.68','powerTag 1520',3,154,'E207DCDF'","'D-02-03','172.16.32.68','powerTag 1520',4,151,'E207DCC2'","'D-02-03','172.16.32.68','powerTag 1520',5,150,'E207DCC5'","'D-02-03','172.16.32.68','powerTag 1520',6,152,'E207DCD0'","'D-02-04','172.16.32.69','powerTag 1520',1,152,'E207DCD7'","'D-02-04','172.16.32.69','powerTag 1520',2,153,'E207DCCA'","'D-02-04','172.16.32.69','powerTag 1520',3,150,'E207DCE8'","'D-02-04','172.16.32.69','powerTag 1520',4,155,'E207DCDC'","'D-02-04','172.16.32.69','powerTag 1520',5,154,'E207DCDB'","'D-02-04','172.16.32.69','powerTag 1520',6,151,'E207DCE9'","'D-02-05','172.16.32.70','powerTag 1520',1,152,'E207DCD2'","'D-02-05','172.16.32.70','powerTag 1520',2,153,'E207DCE4'","'D-02-05','172.16.32.70','powerTag 1520',3,150,'E207DCA7'","'D-02-05','172.16.32.70','powerTag 1520',4,155,'E207DCD9'","'D-02-05','172.16.32.70','powerTag 1520',5,151,'E207DCB4'","'D-02-05','172.16.32.70','powerTag 1520',6,154,'E207DCC0'","'D-02-06','172.16.32.71','powerTag 1520',1,154,'E207DCCC'","'D-02-06','172.16.32.71','powerTag 1520',2,155,'E207DCA6'","'D-02-06','172.16.32.71','powerTag 1520',3,150,'E207DCD1'","'D-02-06','172.16.32.71','powerTag 1520',4,153,'E207DCD5'","'D-02-06','172.16.32.71','powerTag 1520',5,151,'E207DCC6'","'D-02-06','172.16.32.71','powerTag 1520',6,152,'E207DCC4'","'D-02-07','172.16.32.72','powerTag 1520',1,150,'E207DCE3'","'D-02-07','172.16.32.72','powerTag 1520',2,152,'E207DCCF'","'D-02-07','172.16.32.72','powerTag 1520',3,153,'E207DCDD'","'D-02-07','172.16.32.72','powerTag 1520',4,151,'E207DCE5'","'D-02-07','172.16.32.72','powerTag 1520',5,155,'E207DCE6'","'D-02-07','172.16.32.72','powerTag 1520',6,154,'E207DCE0'","'D-02-08','172.16.32.73','powerTag 1520',1,152,'E207DCC1'","'D-02-08','172.16.32.73','powerTag 1520',2,155,'E207DCAE'","'D-02-08','172.16.32.73','powerTag 1520',3,154,'E207DCCE'","'D-02-08','172.16.32.73','powerTag 1520',4,150,'E207DCBF'","'D-02-08','172.16.32.73','powerTag 1520',5,153,'E207DCBF'","'D-02-08','172.16.32.73','powerTag 1520',6,151,'E207DCD3'","'D-02-10','172.16.32.75','powerTag 1520',1,155,'E207DCC7'","'D-02-10','172.16.32.75','powerTag 1520',2,154,'E207DCE7'","'D-02-10','172.16.32.75','powerTag 1520',3,153,'E207DCBB'","'D-02-10','172.16.32.75','powerTag 1520',4,152,'E207DCAF'","'D-02-10','172.16.32.75','powerTag 1520',5,151,'E207DCEA'","'D-02-10','172.16.32.75','powerTag 1520',6,150,'E207DCE2'","'D-03-01','172.16.32.46','powerTag 1520',1,154,'E207D4C7'","'D-03-01','172.16.32.46','powerTag 1520',2,150,'E207D2B4'","'D-03-01','172.16.32.46','powerTag 1520',3,152,'E207D4CE'","'D-03-01','172.16.32.46','powerTag 1520',4,151,'E207D2CD'","'D-03-01','172.16.32.46','powerTag 1520',5,155,'E207D4C5'","'D-03-01','172.16.32.46','powerTag 1520',6,153,'E207D4D1'","'D-03-02','172.16.32.47','powerTag 1520',1,154,'E207D4C3'","'D-03-02','172.16.32.47','powerTag 1520',2,152,'E207D4D0'","'D-03-02','172.16.32.47','powerTag 1520',3,155,'E207D4D3'","'D-03-02','172.16.32.47','powerTag 1520',4,150,'E207D4BF'","'D-03-02','172.16.32.47','powerTag 1520',5,151,'E207D2BA'","'D-03-02','172.16.32.47','powerTag 1520',6,153,'E207D4C9'","'D-03-03','172.16.32.48','powerTag 1520',1,153,'E207D4CF'","'D-03-03','172.16.32.48','powerTag 1520',2,150,'E207D4C8'","'D-03-03','172.16.32.48','powerTag 1520',3,155,'E207D4C4'","'D-03-03','172.16.32.48','powerTag 1520',4,154,'E207D4D5'","'D-03-03','172.16.32.48','powerTag 1520',5,151,'E207D2CB'","'D-03-03','172.16.32.48','powerTag 1520',6,152,'E207D4CD'","'D-03-04','172.16.32.49','powerTag 1520',1,150,'E207D23D'","'D-03-04','172.16.32.49','powerTag 1520',2,152,'E207D235'","'D-03-04','172.16.32.49','powerTag 1520',3,153,'E207D271'","'D-03-04','172.16.32.49','powerTag 1520',4,151,'E207D242'","'D-03-04','172.16.32.49','powerTag 1520',5,155,'E207D276'","'D-03-04','172.16.32.49','powerTag 1520',6,154,'E207D274'","'D-03-05','172.16.32.50','powerTag 1520',1,152,'E207D2B7'","'D-03-05','172.16.32.50','powerTag 1520',2,151,'E207D241'","'D-03-05','172.16.32.50','powerTag 1520',3,153,'E207D256'","'D-03-05','172.16.32.50','powerTag 1520',4,155,'E207D2C2'","'D-03-05','172.16.32.50','powerTag 1520',5,150,'E207D2C2'","'D-03-05','172.16.32.50','powerTag 1520',6,154,'E207D249'","'D-03-06','172.16.32.51','powerTag 1520',1,152,'E207D2B3'","'D-03-06','172.16.32.51','powerTag 1520',2,151,'E207D2B6'","'D-03-06','172.16.32.51','powerTag 1520',3,150,'E207D25C'","'D-03-06','172.16.32.51','powerTag 1520',4,153,'E207D25F'","'D-03-06','172.16.32.51','powerTag 1520',5,154,'E207D25E'","'D-03-06','172.16.32.51','powerTag 1520',6,155,'E207D2B2'","'D-03-07','172.16.32.52','powerTag 1520',1,154,'E207D2C5'","'D-03-07','172.16.32.52','powerTag 1520',2,153,'E207D2B0'","'D-03-07','172.16.32.52','powerTag 1520',3,155,'E207D2C6'","'D-03-07','172.16.32.52','powerTag 1520',4,150,'E207D4CA'","'D-03-07','172.16.32.52','powerTag 1520',5,151,'E207D2B8'","'D-03-07','172.16.32.52','powerTag 1520',6,152,'E207D2AF'","'D-03-08','172.16.32.53','powerTag 1520',1,151,'E207D4CC'","'D-03-08','172.16.32.53','powerTag 1520',2,152,'E207D4D4'","'D-03-08','172.16.32.53','powerTag 1520',3,154,'E207D4C1'","'D-03-08','172.16.32.53','powerTag 1520',4,150,'E207D4CB'","'D-03-08','172.16.32.53','powerTag 1520',5,155,'E207D4C6'","'D-03-08','172.16.32.53','powerTag 1520',6,153,'E207D4C6'","'D-03-10','172.16.32.55','powerTag 1520',1,150,'E207D2B5'","'D-03-10','172.16.32.55','powerTag 1520',2,152,'E207D2BD'","'D-03-10','172.16.32.55','powerTag 1520',3,154,'E207D2C3'","'D-03-10','172.16.32.55','powerTag 1520',4,151,'E207D2C3'","'D-03-10','172.16.32.55','powerTag 1520',5,153,'E207D2C3'","'D-03-10','172.16.32.55','powerTag 1520',6,155,'E207D2C1'","'D-04-01','172.16.32.36','powerTag 1520',1,154,'E207D5A2'","'D-04-01','172.16.32.36','powerTag 1520',2,151,'E207D5A3'","'D-04-01','172.16.32.36','powerTag 1520',3,153,'E207D5AE'","'D-04-01','172.16.32.36','powerTag 1520',4,152,'E207D596'","'D-04-01','172.16.32.36','powerTag 1520',5,155,'E207D59B'","'D-04-01','172.16.32.36','powerTag 1520',6,150,'E207D5A1'","'D-04-02','172.16.32.37','powerTag 1520',1,150,'E207D5A8'","'D-04-02','172.16.32.37','powerTag 1520',2,155,'E207D59C'","'D-04-02','172.16.32.37','powerTag 1520',3,151,'E207D5AA'","'D-04-02','172.16.32.37','powerTag 1520',4,154,'E207D5AD'","'D-04-02','172.16.32.37','powerTag 1520',5,152,'E207D591'","'D-04-02','172.16.32.37','powerTag 1520',6,153,'E207D581'","'D-04-03','172.16.32.38','powerTag 1520',1,150,'E207D268'","'D-04-03','172.16.32.38','powerTag 1520',2,153,'E207D26E'","'D-04-03','172.16.32.38','powerTag 1520',3,151,'E207D25A'","'D-04-03','172.16.32.38','powerTag 1520',4,152,'E207D236'","'D-04-03','172.16.32.38','powerTag 1520',5,155,'E207D236'","'D-04-03','172.16.32.38','powerTag 1520',6,154,'E207D24A'","'D-04-04','172.16.32.39','powerTag 1520',1,155,'E207D25D'","'D-04-04','172.16.32.39','powerTag 1520',2,150,'E207D278'","'D-04-04','172.16.32.39','powerTag 1520',3,154,'E207D263'","'D-04-04','172.16.32.39','powerTag 1520',4,153,'E207D260'","'D-04-04','172.16.32.39','powerTag 1520',5,152,'E207D257'","'D-04-04','172.16.32.39','powerTag 1520',6,151,'E207D243'","'D-04-05','172.16.32.40','powerTag 1520',1,154,'E207D24D'","'D-04-05','172.16.32.40','powerTag 1520',2,150,'E207D5AC'","'D-04-05','172.16.32.40','powerTag 1520',3,155,'E207D5B1'","'D-04-05','172.16.32.40','powerTag 1520',4,153,'E207D5B1'","'D-04-05','172.16.32.40','powerTag 1520',5,151,'E207D57C'","'D-04-05','172.16.32.40','powerTag 1520',6,152,'E207D5A9'","'D-04-06','172.16.32.41','powerTag 1520',1,155,'E207D59F'","'D-04-06','172.16.32.41','powerTag 1520',2,154,'E207D59E'","'D-04-06','172.16.32.41','powerTag 1520',3,151,'E207D593'","'D-04-06','172.16.32.41','powerTag 1520',4,153,'E207D5AB'","'D-04-06','172.16.32.41','powerTag 1520',5,150,'E207D5A0'","'D-04-06','172.16.32.41','powerTag 1520',6,152,'E207D597'","'D-04-07','172.16.32.42','powerTag 1520',1,153,'E207D589'","'D-04-07','172.16.32.42','powerTag 1520',2,151,'E207D58F'","'D-04-07','172.16.32.42','powerTag 1520',3,154,'E207D5B0'","'D-04-07','172.16.32.42','powerTag 1520',4,155,'E207D58B'","'D-04-07','172.16.32.42','powerTag 1520',5,150,'E207D58B'","'D-04-07','172.16.32.42','powerTag 1520',6,152,'E207D5A6'","'D-04-08','172.16.32.43','powerTag 1520',1,150,'E207D253'","'D-04-08','172.16.32.43','powerTag 1520',2,153,'E207D262'","'D-04-08','172.16.32.43','powerTag 1520',3,155,'E207D261'","'D-04-08','172.16.32.43','powerTag 1520',4,152,'E207D255'","'D-04-08','172.16.32.43','powerTag 1520',5,151,'E207D252'","'D-04-08','172.16.32.43','powerTag 1520',6,154,'E207D24F'","'D-04-10','172.16.32.45','powerTag 1520',1,153,'E207D244'","'D-04-10','172.16.32.45','powerTag 1520',2,152,'E207D258'","'D-04-10','172.16.32.45','powerTag 1520',3,154,'E207D25B'","'D-04-10','172.16.32.45','powerTag 1520',4,150,'E207D251'","'D-04-10','172.16.32.45','powerTag 1520',5,155,'E207D245'","'D-04-10','172.16.32.45','powerTag 1520',6,151,'E207D259'","'D-05-01','172.16.32.26','powerTag 1520',1,150,'E207D592'","'D-05-01','172.16.32.26','powerTag 1520',2,152,'E207D265'","'D-05-01','172.16.32.26','powerTag 1520',3,154,'E207D277'","'D-05-01','172.16.32.26','powerTag 1520',4,153,'E207D594'","'D-05-01','172.16.32.26','powerTag 1520',5,151,'E207D267'","'D-05-01','172.16.32.26','powerTag 1520',6,155,'E207D272'","'D-05-02','172.16.32.27','powerTag 1520',1,155,'E207D586'","'D-05-02','172.16.32.27','powerTag 1520',2,151,'E207D58D'","'D-05-02','172.16.32.27','powerTag 1520',3,152,'E207D57D'","'D-05-02','172.16.32.27','powerTag 1520',4,153,'E207D580'","'D-05-02','172.16.32.27','powerTag 1520',5,154,'E207D57F'","'D-05-02','172.16.32.27','powerTag 1520',6,150,'E207D1F7'","'D-05-03','172.16.32.28','powerTag 1520',1,152,'E207D1F9'","'D-05-03','172.16.32.28','powerTag 1520',2,151,'E207D205'","'D-05-03','172.16.32.28','powerTag 1520',3,155,'E207D210'","'D-05-03','172.16.32.28','powerTag 1520',4,150,'E207D1DF'","'D-05-03','172.16.32.28','powerTag 1520',5,153,'E207D206'","'D-05-03','172.16.32.28','powerTag 1520',6,154,'E207D1FA'","'D-05-04','172.16.32.29','powerTag 1520',1,151,'E207D1FA'","'D-05-04','172.16.32.29','powerTag 1520',2,150,'E207D1FA'","'D-05-04','172.16.32.29','powerTag 1520',3,152,'E207D595'","'D-05-04','172.16.32.29','powerTag 1520',4,154,'E207D595'","'D-05-04','172.16.32.29','powerTag 1520',5,153,'E207D587'","'D-05-04','172.16.32.29','powerTag 1520',6,155,'E207D585'","'D-05-05','172.16.32.30','powerTag 1520',1,152,'E207D57E'","'D-05-05','172.16.32.30','powerTag 1520',2,155,'E207D58A'","'D-05-05','172.16.32.30','powerTag 1520',3,150,'E207D5A5'","'D-05-05','172.16.32.30','powerTag 1520',4,154,'E207D588'","'D-05-05','172.16.32.30','powerTag 1520',5,151,'E207D590'","'D-05-05','172.16.32.30','powerTag 1520',6,153,'E207D599'","'D-05-06','172.16.32.31','powerTag 1520',1,151,'E207D1F2'","'D-05-06','172.16.32.31','powerTag 1520',2,153,'E207D207'","'D-05-06','172.16.32.31','powerTag 1520',3,150,'E207D1F6'","'D-05-06','172.16.32.31','powerTag 1520',4,155,'E207D20D'","'D-05-06','172.16.32.31','powerTag 1520',5,154,'E207D1FE'",
"'D-05-06','172.16.32.31','powerTag 1520',6,152,'E207D20C'","'D-05-07','172.16.32.32','powerTag 1520',1,154,'E207D1E7'","'D-05-07','172.16.32.32','powerTag 1520',2,152,'E207D1E6'","'D-05-07','172.16.32.32','powerTag 1520',3,155,'E207D1DB'","'D-05-07','172.16.32.32','powerTag 1520',4,150,'E207D1E1'","'D-05-07','172.16.32.32','powerTag 1520',5,151,'E207D1E4'","'D-05-07','172.16.32.32','powerTag 1520',6,153,'E207D1E2'","'D-05-08','172.16.32.33','powerTag 1520',1,150,'E207D1E2'","'D-05-08','172.16.32.33','powerTag 1520',2,154,'E207D1F3'","'D-05-08','172.16.32.33','powerTag 1520',3,152,'E207D208'","'D-05-08','172.16.32.33','powerTag 1520',4,155,'E207D1FF'","'D-05-08','172.16.32.33','powerTag 1520',5,153,'E207D1E0'","'D-05-08','172.16.32.33','powerTag 1520',6,151,'E207D203'","'D-05-10','172.16.32.35','powerTag 1520',1,153,'E207D1F4'","'D-05-10','172.16.32.35','powerTag 1520',2,150,'E207D209'","'D-05-10','172.16.32.35','powerTag 1520',3,151,'E207D201'","'D-05-10','172.16.32.35','powerTag 1520',4,154,'E207D1DC'","'D-05-10','172.16.32.35','powerTag 1520',5,155,'E207D20E'","'D-05-10','172.16.32.35','powerTag 1520',6,152,'E207D1F1'","'D-06-01','172.16.32.16','powerTag 1520',1,151,'E207D192'","'D-06-01','172.16.32.16','powerTag 1520',2,154,'E207D19D'","'D-06-01','172.16.32.16','powerTag 1520',3,153,'E207D157'","'D-06-01','172.16.32.16','powerTag 1520',4,152,'E207D17C'","'D-06-01','172.16.32.16','powerTag 1520',5,150,'E207D11E'","'D-06-01','172.16.32.16','powerTag 1520',6,155,'E207D175'","'D-06-02','172.16.32.17','powerTag 1520',1,152,'E207D1DA'","'D-06-02','172.16.32.17','powerTag 1520',2,154,'E207D1ED'","'D-06-02','172.16.32.17','powerTag 1520',3,150,'E207D1F0'","'D-06-02','172.16.32.17','powerTag 1520',4,153,'E207D1EB'","'D-06-02','172.16.32.17','powerTag 1520',5,155,'E207D1EF'","'D-06-02','172.16.32.17','powerTag 1520',6,151,'E207D1E3'","'D-06-03','172.16.32.18','powerTag 1520',1,153,'E207D16F'","'D-06-03','172.16.32.18','powerTag 1520',2,155,'E207D173'","'D-06-03','172.16.32.18','powerTag 1520',3,152,'E207DC67'","'D-06-03','172.16.32.18','powerTag 1520',4,154,'E207D19A'","'D-06-03','172.16.32.18','powerTag 1520',5,150,'E207D19A'","'D-06-03','172.16.32.18','powerTag 1520',6,151,'E207D18C'","'D-06-04','172.16.32.19','powerTag 1520',1,152,'E207D189'","'D-06-04','172.16.32.19','powerTag 1520',2,150,'E207D170'","'D-06-04','172.16.32.19','powerTag 1520',3,151,'E207D195'","'D-06-04','172.16.32.19','powerTag 1520',4,153,'E207D17D'","'D-06-04','172.16.32.19','powerTag 1520',5,155,'E207D197'","'D-06-04','172.16.32.19','powerTag 1520',6,154,'E207D194'","'D-06-06','172.16.32.21','powerTag 1520',1,151,'E207DC85'","'D-06-06','172.16.32.21','powerTag 1520',2,150,'E207DC80'","'D-06-06','172.16.32.21','powerTag 1520',3,155,'E207DC89'","'D-06-06','172.16.32.21','powerTag 1520',4,152,'E207DC80'","'D-06-06','172.16.32.21','powerTag 1520',5,153,'E207DC8A'","'D-06-06','172.16.32.21','powerTag 1520',6,154,'E207DC77'","'D-06-07','172.16.32.22','powerTag 1520',1,154,'E207D199'","'D-06-07','172.16.32.22','powerTag 1520',2,153,'E207D196'","'D-06-07','172.16.32.22','powerTag 1520',3,151,'E207D193'","'D-06-07','172.16.32.22','powerTag 1520',4,150,'E207DC77'","'D-06-07','172.16.32.22','powerTag 1520',5,152,'E207D171'","'D-06-07','172.16.32.22','powerTag 1520',6,155,'E207D176'","'D-06-08','172.16.32.23','powerTag 1520',1,154,'E207D1E8'","'D-06-08','172.16.32.23','powerTag 1520',2,150,'E207D1DD'","'D-06-08','172.16.32.23','powerTag 1520',3,155,'E207D1EA'","'D-06-08','172.16.32.23','powerTag 1520',4,151,'E207D204'","'D-06-08','172.16.32.23','powerTag 1520',5,152,'E207D1E9'","'D-06-08','172.16.32.23','powerTag 1520',6,153,'E207D1EC'","'D-06-10','172.16.32.25','powerTag 1520',1,151,'E207DC6F'","'D-06-10','172.16.32.25','powerTag 1520',2,154,'E207DC73'","'D-06-10','172.16.32.25','powerTag 1520',3,150,'E207DC6D'","'D-06-10','172.16.32.25','powerTag 1520',4,153,'E207DC72'","'D-06-10','172.16.32.25','powerTag 1520',5,152,'E207DC71'","'D-06-10','172.16.32.25','powerTag 1520',6,155,'E207DC75'","'D-07-01','172.16.32.12','powerTag 1520',1,152,'E207DC7C'","'D-07-01','172.16.32.12','powerTag 1520',2,153,'E207DC86'","'D-07-01','172.16.32.12','powerTag 1520',3,154,'E207DC7A'","'D-07-01','172.16.32.12','powerTag 1520',4,155,'E207DC7B'","'D-07-01','172.16.32.12','powerTag 1520',5,150,'E207DC6C'","'D-07-01','172.16.32.12','powerTag 1520',6,151,'E207DC74'","'D-07-02','172.16.32.11','powerTag 1520',1,154,'E207DC61'","'D-07-02','172.16.32.11','powerTag 1520',2,155,'E207DC59'","'D-07-02','172.16.32.11','powerTag 1520',3,153,'E207DC6'","'D-07-02','172.16.32.11','powerTag 1520',4,150,'E207DC64'","'D-07-02','172.16.32.11','powerTag 1520',5,152,'E207DC69'","'D-07-02','172.16.32.11','powerTag 1520',6,151,'E207DC5B'","'D-07-03','172.16.32.10','powerTag 1520',1,155,'E207DC30'","'D-07-03','172.16.32.10','powerTag 1520',2,152,'E207D1DE'","'D-07-03','172.16.32.10','powerTag 1520',3,150,'E207DC32'","'D-07-03','172.16.32.10','powerTag 1520',4,151,'E207DC5E'","'D-07-03','172.16.32.10','powerTag 1520',5,154,'E207DC62'","'D-07-03','172.16.32.10','powerTag 1520',6,153,'E207DC5A'","'D-07-05','172.16.32.8','powerTag 1520,1,154,'E207DC5D'","'D-07-05','172.16.32.8','powerTag 1520,2,151,'E207DC31'","'D-07-05','172.16.32.8','powerTag 1520,3,152,'E207DC2B'","'D-07-05','172.16.32.8','powerTag 1520,4,153,'E207DC60'","'D-07-05','172.16.32.8','powerTag 1520,5,155,'E207DC5C'","'D-07-05','172.16.32.8','powerTag 1520,6,150,'E207DC5C'","'D-07-06','172.16.32.7','powerTag 1520,1,150,'E207D18A'","'D-07-06','172.16.32.7','powerTag 1520,2,154,'E207D179'","'D-07-06','172.16.32.7','powerTag 1520,3,151,'E207D16E'","'D-07-06','172.16.32.7','powerTag 1520,4,155,'E207D19C'","'D-07-06','172.16.32.7','powerTag 1520,5,152,'E207D179'","'D-07-06','172.16.32.7','powerTag 1520,6,153,'E207D178'","'D-07-07','172.16.32.6','PowerTag 1520,1,152,'E207D18E'","'D-07-07','172.16.32.6','PowerTag 1520,2,150,'E207D18E'","'D-07-07','172.16.32.6','PowerTag 1520,3,155,'E207DBEF'","'D-07-07','172.16.32.6','PowerTag 1520,4,153,'E207DBEC'","'D-07-07','172.16.32.6','PowerTag 1520,5,154,'E207DBF2'","'D-07-07','172.16.32.6','PowerTag 1520,6,151,'E207DC1C'","'D-07-08','172.16.32.13','powerTag 1520',1, 154,  'E207DC78'","'D-07-08','172.16.32.13','powerTag 1520',2,  152,  'E207D20A'","'D-07-08','172.16.32.13','powerTag 1520',3,  151,  'E207DC6B'","'D-07-08','172.16.32.13','powerTag 1520',4,  155,  'E207DC6E'","'D-07-08','172.16.32.13','powerTag 1520',5,  153,  'E207DC68'","'D-07-08','172.16.32.13','powerTag 1520',6,  150,  'E207DC7E'","'D-07-10','172.16.32.15','powerTag 1520',1,  150,  'E207D162'","'D-07-10','172.16.32.15','powerTag 1520',2,  152,  'E207D19F'","'D-07-10','172.16.32.15','powerTag 1520',3,  153,  'E207D18F'","'D-07-10','172.16.32.15','powerTag 1520',4,  154,  'E207D18F'","'D-07-10','172.16.32.15','powerTag 1520',5,  151,  'E207D151'","'D-07-10','172.16.32.15','powerTag 1520',6,  155,  'E207D188'","'D-08-01','172.16.32.56','powerTag 1520',1,  152,  'E207D5FA'","'D-08-01','172.16.32.56','powerTag 1520',2,  153,  'E207D5EF'","'D-08-01','172.16.32.56','powerTag 1520',3,  151,  'E207D5F5'","'D-08-01','172.16.32.56','powerTag 1520',4,  155,  'E207D5FC'","'D-08-01','172.16.32.56','powerTag 1520',5,  150,  'E207D5F2'","'D-08-01','172.16.32.56','powerTag 1520',6,  154,  'E207D5E7'","'D-08-02','172.16.32.57','powerTag 1520',1,  152,  'E207DBF6'","'D-08-02','172.16.32.57','powerTag 1520',2,  151,  'E207DC07'","'D-08-02','172.16.32.57','powerTag 1520',3,  155,  'E207D604'","'D-08-02','172.16.32.57','powerTag 1520',4,  150,  'E207DC03'","'D-08-02','172.16.32.57','powerTag 1520',5,  153,  'E207D5FF'","'D-08-02','172.16.32.57','powerTag 1520',6,  154,  'E207D5FE'","'D-08-03','172.16.32.58','powerTag 1520',1,  151,  'E207D5FE'","'D-08-03','172.16.32.58','powerTag 1520',2,  152,  'E207D619'","'D-08-03','172.16.32.58','powerTag 1520',3,  150,  'E207D614'","'D-08-03','172.16.32.58','powerTag 1520',4,  154,  'E207D60A'","'D-08-03','172.16.32.58','powerTag 1520',5,  153,  'E207D618'","'D-08-03','172.16.32.58','powerTag 1520',6,  150,  'E207D60D'","'D-08-04','172.16.32.59','powerTag 1520',1,  150,  'E207DBF1'","'D-08-04','172.16.32.59','powerTag 1520',2,  155,  'E207DC11'","'D-08-04','172.16.32.59','powerTag 1520',3,  152,  'E207DBED'","'D-08-04','172.16.32.59','powerTag 1520',4,  154,  'E207DBE9'","'D-08-04','172.16.32.59','powerTag 1520',5,  153,  'E207DBF7'","'D-08-04','172.16.32.59','powerTag 1520',6,  151,  'E207DC17'","'D-08-05','172.16.32.60','powerTag 1520',1,  150,  'E207DC14'","'D-08-05','172.16.32.60','powerTag 1520',2,  155,  'E207DC10'","'D-08-05','172.16.32.60','powerTag 1520',3,  152,  'E207DC09'","'D-08-05','172.16.32.60','powerTag 1520',4,  154,  'E207DBFA'","'D-08-05','172.16.32.60','powerTag 1520',5,  151,  'E207DBF3'","'D-08-05','172.16.32.60','powerTag 1520',6,  153,  'E207DC1D'","'D-08-06','172.16.32.61','powerTag 1520',1,  155,  'E207DC12'","'D-08-06','172.16.32.61','powerTag 1520',2,  154,  'E207DBFD'","'D-08-06','172.16.32.61','powerTag 1520',3,  152,  'E207DC1F'","'D-08-06','172.16.32.61','powerTag 1520',4,  151,  'E207DC05'","'D-08-06','172.16.32.61','powerTag 1520',5,  150,  'E207DC15'","'D-08-06','172.16.32.61','powerTag 1520',6,  153,  'E207DC15'","'D-08-07','172.16.32.62','powerTag 1520',1,  155,  'E207DBFC'","'D-08-07','172.16.32.62','powerTag 1520',2,  154,  'E207DBF9'","'D-08-07','172.16.32.62','powerTag 1520',3,  151,  'E207DC00'","'D-08-07','172.16.32.62','powerTag 1520',4,  152,  'E207DC04'","'D-08-07','172.16.32.62','powerTag 1520',5,  150,  'E207DC01'","'D-08-07','172.16.32.62','powerTag 1520',6,  153,  'E207DC1A'","'D-08-08','172.16.32.63','powerTag 1520',1,  151,  'E207DC1A'","'D-08-08','172.16.32.63','powerTag 1520',2,  150,  'E207DC19'","'D-08-08','172.16.32.63','powerTag 1520',3,  154,  'E207DC0E'","'D-08-08','172.16.32.63','powerTag 1520',4,  153,  'E207DC0F'","'D-08-08','172.16.32.63','powerTag 1520',5,  155,  'E207DBFE'","'D-08-08','172.16.32.63','powerTag 1520',6,  152,  'E207DC0A'","'D-08-10','172.16.32.65','powerTag 1520',1,  154,  'E207DC1E'","'D-08-10','172.16.32.65','powerTag 1520',2,  152,  'E207DC08'","'D-08-10','172.16.32.65','powerTag 1520',3,  155,  'E207DC08'","'D-08-10','172.16.32.65','powerTag 1520',4,  153,  'E207DBEB'","'D-08-10','172.16.32.65','powerTag 1520',5,  151,  'E207DC02'","'D-08-10','172.16.32.65','powerTag 1520',6,  150,  'E207DC16'");

  $input = array();
  $mapper = [0=>'unit' , 1=>'ip_address' , 2=>'device_name' , 3=>'room_no' , 4=>'modbus_address' , 5=>'rf_id'];
  foreach($data as $item)
  {
    $temp_1 = explode("," ,$item);
    foreach ($temp_1 as $mapper_key => $meter_value)
    {
      $room = Room::getRoomByHouseRoomName($temp_1[0] , $temp_1[3]);
      $input[$room['id_house_room']][$mapper[$mapper_key]] = $meter_value;
    }
    
  }


  dd("Done"); 

}); 


Route::get('createDailyDataOld', function ()
{ 
  $data_to_daily_model_mappers = ['month_day' => 'current_data', 'total_amount' => 'current_usage'];
  $date_ended = '2019-02-01';
  $date_range   = array('date_started' => date('Y-m-d', strtotime('-7 day', strtotime($date_ended))) ,'date_ended' =>  date('Y-m-d', strtotime($date_ended)));
  $meter_register_listing = MeterRegister::all();
  foreach ($meter_register_listing as $meter_register){

      $label_arr =  array();
      $reading_arr =  array();
      MeterReadingDaily::save_daily_meter_reading_by_meter_register_id($meter_register['id']);
      //$listing = MeterReadingDaily::save_daily_meter_reading_by_meter_register_id($date_range , $meter_register['id']);
      /*dd($listing);
      foreach ($listing as $row)
      {   
        $input = array();
        $row = (array) $row;
        dd($row);
        $row = (array) $row;
        array_push($label_arr, $row['month_day']);
        array_push($reading_arr , $row['total_amount']);
        MeterReadingDaily::saveOrUpdateMeterReading($input);
        unset($input);
      }
      unset($meter_register);*/
      /*$c_model = CustomerPowerUsageSummary::findByMeteregisterId($meter_register['id']);
      $c_model =*/
  }
  

  dd("Done"); 

}); 



Route::get('createDailyData', function ()
{ 
  $data_to_daily_model_mappers = ['month_day' => 'current_data', 'total_amount' => 'current_usage'];
  $date_ended = '2019-02-01';
  $date_range   = array('date_started' => date('Y-m-d', strtotime('-7 day', strtotime($date_ended))) ,'date_ended' =>  date('Y-m-d', strtotime($date_ended)));
  $meter_register_listing = MeterRegister::all();
  foreach ($meter_register_listing as $meter_register){

      $label_arr =  array();
      $reading_arr =  array();
      $listing = MeterReading::get_meter_register_daily_reading($date_range , $meter_register['id']);
      dd($listing);
      foreach ($listing as $row)
      {   
        $input = array();
        $row = (array) $row;
        dd($row);
        $row = (array) $row;
        array_push($label_arr, $row['month_day']);
        array_push($reading_arr , $row['total_amount']);
        MeterReadingDaily::saveOrUpdateMeterReading($input);
        unset($input);
      }
      unset($meter_register);
      /*$c_model = CustomerPowerUsageSummary::findByMeteregisterId($meter_register['id']);
      $c_model =*/
  }
  

  dd("Done"); 

}); 


       


Route::get('createDashboardData', function ()
{ 

  dd(date('Y-m-d', strtotime('-100 days')).'='.date('Y-m-d', strtotime('now')));

  $leaf_group_id = 282;

  BackendData::createOrUpdateCompanyBackendData($leaf_group_id);
  dd("Done"); 

}); 



Route::get('meterAccountGenerator', function ()
{ 
  $result['id_user'] = 2701;
  $leaf_api= new LeafAPI();
  $member_detail = $leaf_api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
    $account_data = PowerMeterAccount::update_or_save_customer_summary_by_leaf_member_id($member_detail);
    dd("Done");
  $leaf_room_id = 343;
  PowerMeterAccount::getOrCreateMeterAccount($leaf_room_id);
  dd("Done"); 

}); 


Route::get('checkIpay', function ()
{ 
  $c = '1264be39cf8691b5958af0989f7b189e';
  $l = new LeafAPI();
  $r = $l->get_check_payment($c);
  dd($r); 

}); 



Route::get('create_counpon', function ()
{ 
  $sql_string = "(ID, '- RM 25  Discount', 'C_NUMBER', 'F', '25.0000', 1, 0, '250.0000', '2020-08-19', '2020-09-30', 1, '1', 1, '2009-01-27 13:55:03'),";
  $id = 27 ;
  $coupon_number = 223355001;
  for($id = 27 ; $id < 250 ; $id ++)
  {
    $new = str_replace('C_NUMBER' , $coupon_number , str_replace('ID' , $id , $sql_string));
    echo $new."<br>";
    $coupon_number ++;
  }
  

}); 


Route::get('stringString', function ()
{ 
  $date_range = ['date_started' => '2018-06-28' , 'date_ended' => '2018-06-30'];
  //dd($date_range);
  $meter_register_id = 15 ;

  $r = MeterReading::get_meter_register_daily_reading($date_range , $meter_register_id);
  dd($r);



    foreach ($split_string_arr as $string)
    {
      //echo $string."\n";
      $temp = explode("\t" , $string);

      $counter = 0;
      $tag = '';
      foreach ($temp as $item)
      {
        if($counter == 0 || $item == '')
        {
          $counter ++;
          continue;
        }

        $tag .= trim($item);
        if(count($temp) - $counter != 1)
        {
          $tag .=',';
        }
        $counter ++;
      }
      if(substr($tag, -1) == ',')
      {
        $tag = substr($tag, 0, -1);

      }
      echo $tag."<br>";

    }

}); 
//$leaf_split = explode(,)

Route::get('replaceTest', function ()
{ 
  $get = '"\"\\\"014100 074120\\\"\""';
  //dd(trim($get));

  $x = 
          preg_replace(
            array('#[\\s-]+#', '#[^A-Za-z0-9. -]+#'),
            array('\\', ''),
        ##     cleanString(
              urldecode($get)
        ##     )
        );
dd($x);
  dd(stripslashes($get));
}); 



  
Route::get('update_meter_adjustment_time', function(){
  ini_set('max_execution_time', 30000);
  $listing = MeterRegister::all();
  foreach($listing as $meter_register_model){
  $meter_register_id=$meter_register_model->id;
      if($meter_register_model->adjustment_usage_days == null){
              $feb_2020_adjustment_date = MeterReading::SELECT('meter_register_id','current_date')
                            
                            ->where('meter_register_id' , '=' , $meter_register_id)
                            ->where('current_date', 'like', '2020-02-%')
                            
                            ->orderByDesc('current_date')
                            ->first();
          
              $april_2020_adjustment_date = MeterReading::SELECT('meter_register_id','current_date')
                          
                            ->where('meter_register_id' , '=' , $meter_register_id)
                            ->where('current_date', 'like', '2020-04-%')
                            
                            ->orderBy('current_date')
                            ->first();
            
            $temp['feb2020'] = $feb_2020_adjustment_date['current_date'];
            $temp['apr2020'] = $april_2020_adjustment_date['current_date'];
            
            $meter_register_model_2 = MeterRegister::find($meter_register_model->id);
            $meter_register_model_2['adjustment_usage_days'] = json_encode($temp);
            $meter_register_model_2->save();
      }
  }
});

Route::get('updatePayment', function ()
{ 
  dd(UrbanAPI::update_booking_status(11));
}); 
Route::get('information_snmp', function ()
{ 
  $new_name_array = array();
  $new_icpm_array = array();
  $d_array = ['PTZ Camera','PTZ Camera','Ethernet Switch','Ethernet Switch','Wireless Antenna','Wireless Antenna','Wireless Antenna','Wireless Antenna','PC Workstation','PC Workstation','Server','Server','Server','Network Switch','Network Switch'];
  $d_name_array =['Cam 23','Cam 05','SW 23','SW 5','Ant 05-TX','Ant 05-RX','Ant 23-TX','Ant 23-RX','Township 1','Township 2','Main Server','Recording Server','NAS','Fiber Switch','Fiber Switch'];
  $l_array = ['Menara','Sunway Medical Center T-Junc','Menara','Sunway Medical Center T-Junc','Sunway Medical Center T-Junc','Menara Sunway Rooftop','Menara','Menara Sunway Rooftop','B2 Polis Bantuan Comand Center','B2 Polis Bantuan Comand Center','B2 Polis Bantuan Comand Center','B2 Polis Bantuan Comand Center','B2 Polis Bantuan Comand Center','Riser L20 Menara Sunway','B2 Polis Bantuan Comand Center'];
  $b_array = ['Truen','Truen','Lantech','Lantech','Suntor','Suntor','Suntor','Suntor','PC','PC','Server','Server','Server','CISCO Switch','HP Switch'];
  $m_array = ['TN-P1230CSLX','TN-P1230CSLX','IES-2008A','IES-2008A','ST58T8','ST58T8','ST58T8','ST58T8','x','x','x','x','x','WS-C2960-24TC-L','ProCurve 3500'];
  $vlan_array = [601,601,601,601,601,601,601,601,601,601,601,601,601];
  $device_ip_address_arr = ['10.1.9.66','10.1.9.91','10.1.9.69','10.1.9.92','10.1.9.93','10.1.9.94','10.1.9.57','10.1.9.58','10.1.9.16','10.1.9.5','10.1.9.13','10.1.9.14','10.1.9.15','10.7.1.250','10.7.1.253'];
  $content_mapping = ['l'=>'------------------------------------------------ <br> Location : ' ,'vlan' => 'VLAN : ', 'b' => '------------------------------------------------ <br> Brand : ' ,'m' => 'Model : ' , 'd'=>'Device : ','d_name' => 'Device Name : '  ];

  $counter =1 ;
  $ip_string = 'icmpping[IP_ADDRESS,4,200,,500]';
  echo '<table border=1> <tr> <th>#</th>  <th>Name</th> <th>Description</th> <th>ICPM</th> </tr>';
  foreach ($d_array as $key => $value){
    $content ='';
    foreach ($content_mapping as $c_key => $c_value){
      $array_name = $c_key.'_array';

      $ip_to_ping = isset($device_ip_address_arr[$key]) ? $device_ip_address_arr[$key] : '0.0.0.0';
      $icpm = str_replace('IP_ADDRESS' , $ip_to_ping , $ip_string);
      if(isset($$array_name[$key])){

        $content = $content.' <br> '.$c_value.' '.$$array_name[$key];
      }

    }
    
    echo '<tr> <td>'. $counter.'</td><td>'. $d_name_array[$key].' ( '.$l_array[$key].' ) </td><td>'. $content." <br> ------------------------------------------------ </td><td>" . $icpm ."</td><br>";
    array_push($new_icpm_array , $ip_to_ping);
    array_push($new_name_array , $content);
    $counter ++;
  }
echo '</table>';
dd($new_name_array);

});


Route::get('split_string', function ()
{ 
  dd(number_format('0.25514285714286',4));
  $to_split_1 = "`product_id`,`quantity`,`sku`,`upc`,`product_item_small_pack_quantity`,`product_item_small_pack_unit_id`,`per_product_unit`,`product_attribute_id`,`product_set_quantity`,`product_set_unit_id`, `import_cost`, `gathering`, `gathering_period`, `gathering_label`, `gathering_min_purchase`, `gathering_success_quantity`, `gathering_carton_discount`, `shipping_methods`, `remark`,`ean`,`jan`,`isbn`,`mpn`,`location`,`stock_status_id`,`model`,`manufacturer_id`,`image`,`shipping`,`price`,`points`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`small_pack_weight`,`small_pack_weight_class_id`,`status`,`tax_class_id`,`viewed`,`length`,`width`,`height`,`length_class_id`,`sort_order`,`subtract`,`minimum`";

  $to_split_2 = "356,500,'','','1','0','15','468','1','0','0','0','0','0','1','1','100','', 'Zhang Junya's younger sister and windy chicken pie (65 g / 15 packs / 1 box)', '','','','','',6,'(維力) 維力張君雅和風雞汁拉麵條餅 ( 65 g / 15 包 / 1 箱 )',18,'catalog/Products Description/Product ID/[356] (維力) 張君雅和風雞汁拉麵條餅 (15包 1箱)/118fe989030242ac110005.jpg',1,86.6502,0,'2020-04-20 20:03:14','2020-04-29 13:42:13','2020-04-20',1.1,1,1,1,1,9,0,39,28,13,'0','1','1','1'";

  $t1=explode(',',$to_split_1);
  $t2=explode(',',$to_split_2);

  foreach ($t1 as $key => $value)
  {
    echo $value.'='.$t2[$key]."<br>";
  }
  dd("end");


  $string ='';
  $str_array = explode("\r\n",$string);
  $to_save = array();
  foreach($str_array as $key => $value)
  { 
    $sub_arr = explode(" ",$value);
    if(strpos($sub_arr[0] , '*') !== false){
      //echo $sub_arr[0];
      $new_value = trim($sub_arr[0]);
      //dd($new_value);
    }else{
      $new_value = trim($str = preg_replace('/[^0-9.]+/', '', $sub_arr[0]));
      
      
    }

    array_push($to_save,$new_value);

  }

  foreach ($to_save as $key => $value)
  {
    echo $value."<br>";
  }
  dd($str_array);
  dd($str_array);


});


Route::get('save_or_edit_api_model', function ()
{ 

  foreach (APIClient::create_test_list() as $model){
    //dd($model);
    foreach($model as $key => $value)
    {
      echo $key."     &nbsp;  &nbsp;  &nbsp;  &nbsp;  varchar(255) , <br>";
    }
    dd("x");
//     APIClient::save_or_edit_model($model);
  }
  

});



Route::get('create_urban_invoice', function ()
{ 

   $invoice_id = 
  UrbanAPI::save_booking_to_urban_by_invoice_id($invoice_id);

});


Route::get('payment_test2', function ()
{ 

   
/*  $data_payment = array('id_product' => 13);

  $payload = json_encode($data_payment);

    $get_data = UrbanAPI::call_api('POST', 'https://52.74.34.195/webapimodule/getpayment', $payload);
    dd($get_data);*/
    $date_price_listing = array();
    foreach(json_decode($get_data) as $payment_model)
    {
      $temp['i'] = $payment_model->date_from;
      $temp['d'] = $payment_model->amount;
      
      array_push($date_price_listing, $temp);
    }
    //dd($date_price_listing);

    $return = '';
    foreach ($date_price_listing as $model)
    {
      $return .= 'i:'.$model['i'].';'.'d:'.$model['d'].';';
    }
    dd("{".$return."}");
  });



Route::get('update_date2', function ()
{ 
    $date_string = '{i:1580515200;d:100;i:1580601600;d:100;i:1580688000;d:100;i:1580774400;d:100;i:1580860800;d:100;i:1580947200;d:100;i:1581033600;d:100;i:1581120000;d:100;i:1581206400;d:100;i:1581292800;d:100;i:1581379200;d:100;i:1581465600;d:100;i:1581552000;d:100;i:1581638400;d:100;i:1581724800;d:100;i:1581811200;d:100;i:1581897600;d:1000;i:1581984000;d:1000;i:1582070400;d:1000;i:1582156800;d:1000;i:1582243200;d:1000;i:1582329600;d:1000;i:1582416000;d:1000;i:1582502400;d:1000;i:1582588800;d:1000;i:1582675200;d:1000;i:1582761600;d:1000;i:1582848000;d:1000;i:1582934400;d:1000;i:1583020800;d:1000;i:1583107200;d:1000;i:1583193600;d:1000;i:1583280000;d:1000;i:1583366400;d:1000;i:1583452800;d:1000;i:1583539200;d:1000;i:1583625600;d:1000;i:1583712000;d:1000;i:1583798400;d:1000;i:1583884800;d:1000;i:1583971200;d:1000;i:1584057600;d:1000;i:1584144000;d:1000;i:1584230400;d:1000;i:1584316800;d:1000;i:1584403200;d:1000;i:1584489600;d:1000;i:1584576000;d:1000;i:1584662400;d:1000;i:1584748800;d:1000;i:1584835200;d:1000;i:1584921600;d:1000;i:1585008000;d:1000;i:1585094400;d:1000;i:1585180800;d:1000;i:1585267200;d:1000;}';
    

  
    //date and price
    $price_list = array();
    $price_tag = ['date'=> '', 'price' =>''] ;
    $price_setting_array = explode( ';' ,   str_replace('{' , '' ,   str_replace('}' , ''  , $date_string ) ) );
    $back_track_ori = array();
    $back_track = array();
    foreach ($price_setting_array as $key => $value)
    { if($value ==''){continue;}    
      if(strpos( $value ,'i' ) !== false){
        $temp = explode(':' , $value);
        $price_tag['date'] = $temp[count($temp) -1];
        $temp_date=  gmdate("Y-m-d H:i:s",$price_tag['date']);
        $temp_string = $price_tag['date']." = ".$temp_date."<br>";
        array_push($back_track_ori , $temp_string);
        array_push($back_track,$temp_date);
        
      }else if(strpos( $value, 'd') !== false){
        $temp = explode( ':' , $value);
        $price_tag['price'] = $temp[count($temp) -1];
        array_push($price_list , $price_tag);
      }
    }

    $count = 0 ;
  //  echo '<table>';


  //  foreach($back_track as $key => $value)
//  //  {
//
  //    $date = DateTime::createFromFormat('Y-m-d H:i:s', $value);
  //     $date->getTimestamp(); // output: 1343219708
  //    echo '<tr>';
  //    //$expires = new DateTime($value);
  //    echo '<td>'.$back_track_ori[$count].'</td><td>'.' = './*date('Y-m-d H:i:s', strtotime($value))*/ date('F d, Y H:i' ,$value)."</td>";
  //    //echo $expires->format('U')."<br>";
  //    echo '</tr>';
  //    $count++;
  //  }
    //echo '</table>';

    dd($price_list);

});

Route::get('save_api_client', function (){
  dd(json_encode(APIClient::get_all_api_details()));
  APIClient::save_test_model();
    dd("21");
});

Route::get('update_urban_company', function (){
  Setting::set_dynamic_connection('hotel');
  $urban_api = new UrbanAPI();
  $company_model = $urban_api->get_company_detail();
  WpOption::update_group_contact_by_urban_company_model($company_model);
});

Route::get('getPostMeta', function ()
{Setting::set_dynamic_connection('hotel');

  $date = ['date_started' => '2020-01-09T00:00:00+00:00' , 'date_ended' => '2020-06-12T00:00:00+00:00'];
$return =   WpPostmeta::get_wp_booking_date_range_arr($date);
//dd($return);
foreach ($return as $date_string  ){
  $update[$date_string] = 35457;
}
  $post_model = WpPost::find(145);
  $post_meta_listing = WpPostmeta::where('post_id','=', $post_model['ID'])->get();
  echo "Next <br>";
  echo "<table boder=1>";
  echo "<tr><th>Key</th><th>Value</th></tr>";
  $new_booking_date = array();
  $new ;
  foreach($post_meta_listing as $model)
  {
    echo '<tr><td>'.$model['meta_key'].'</td><td>'.$model['meta_value'].'</td><td></tr>';
    if($model['meta_key'] == 'booking_dates'){
      
  //  dd(serialize($return));
      $model['meta_value'] = serialize($update);
      $model->save();
      $booking_date_arr = unserialize($model['meta_value']);

      dd($booking_date_arr);
      foreach($booking_date_arr as $key => $value)
      {
        //array_pusth($new_booking_date , $value);
        $date =  gmdate("Y-m-d H:i:s",$key);
        echo $date."<br>";
      }
//dd($model);
      //$model->save();
      //$new = $model;
      //dd($model);
      
      }
    
  }
  echo "</table>";
  dd("end");
});


Route::get('urbanTest4', function ()
{
  Setting::set_dynamic_connection('hotel');
  dd(UrbanAPI::clear_cache('https://i-urban.my'));
  dd("end");
  $date = ['date_started' => '2020-04-29T00:00:00+00:00' , 'date_ended' => '2020-05-30T00:00:00+00:00'];
$return =   WpPostmeta::get_wp_booking_date_range_arr($date);

  $post_model = WpPost::find(115);
  $post_meta_listing = WpPostmeta::where('post_id','=', $post_model['ID'])->get();
  echo "Next <br>";
  echo "<table boder=1>";
  echo "<tr><th>Key</th><th>Value</th></tr>";
  $new_booking_date = array();
  $new ;
  foreach($post_meta_listing as $model)
  {

    if($model['meta_key'] == 'booking_dates'){
      
  //  dd(serialize($return));
      $model['meta_value'] = serialize($return);
      //$booking_date_arr = unserialize($value['meta_value']);
      /*foreach($booking_date_arr as $key => $value)
      {*/
        //array_pusth($new_booking_date , $value);
      //  $date =  gmdate("Y-m-d H:i:s",$value);
        //echo $date."<br>";
      //}
//dd($model);
      $model->save();
      $new = $model;
      //dd($model);
      
    }
    
  }
  echo "</table>";
  //UrbanAPI::clear_cache('http://localhost/hotel');
  dd($new);
  
  $urban_api = new UrbanAPI();
  $hotel_listing = $urban_api->get_test_data();
  //echo "X";

  //dd(json_decode($hotel_listing));
  foreach (json_decode($hotel_listing)  as $key => $hotel){
    $data = (array) $hotel;
  //  dd($data);
    $price_listing = /*(array)*/ UrbanAPI::convert_to_array($data['specific_price']);
    
    WpPostmeta::convert_urban_price_model_listing_to_date_price_data($price_listing);
    dd($price_listing);
    $hotel_room = /*(array)*/ UrbanAPI::convert_to_array($data['ps_htl_room_type']);
    //dd($hotel_room);
    //dd($data);
    echo (isset($hotel_room['hotel_name']) ? $hotel_room['hotel_name'] : 'None')."<br>";
    if(!isset($hotel_room['hotel_name'])){
      //dd($data);
      echo 'Fail case :'.json_decode($data)."<br> <br> <br> <br>";
    }else{
      //if($hotel_room['hotel_name'] == 'Oikos Poshtel'){
        echo UrbanAPI::save_property_from_urban($hotel);
      //}
      //UrbanAPI::save_property_from_urban($hotel);
    }
    //$temp = (array)$hotel->specific_price;
    /*dd(WpPostmeta::convert_urban_price_model_listing_to_date_price_data($temp));
    dd($temp['customizable']);*/
    //UrbanAPI::save_property_from_urban($hotel);
  }

    $params['user_email'] = 'peterooi83@gmail.com';
    $params['user_password'] = '123123';
    $leaf_api = new LeafAPI();
    $return  = $leaf_api->login($params);
    dd($return);

  dd(UrbanAPI::preset_meta_value_array);

});


Route::get('getPropertyImage', function (){

  ini_set("memory_limit", '256M');
  $urban_api = new UrbanAPI();
  $hotel_listing = $urban_api->get_test_data();
  $edit_flag = false;
  $counter = 1;
  echo '<table border = 1> <tr> <th>#</th> <th>Name</th> <th>Url</th> <th>Size</th> <th>Capacity (Kb)</th> <th>Is Publish</th>  </tr>';
  foreach (json_decode($hotel_listing)  as $key => $hotel){
    $data = (array) $hotel;

    if(!isset($data['ps_product'])){ continue;}
    $urban_product_model = UrbanAPI::convert_to_array($data['ps_product']);
    if(!isset($data['ps_htl_room_type'])){
      continue;
    }
      
    if(!isset($data['ps_htl_room_type'])){
      continue;
    }

    $hotel_room = /*(array)*/ UrbanAPI::convert_to_array($data['ps_htl_room_type']);

    if(!isset($data['ps_product'])){continue;}
    $room_info = UrbanAPI::convert_to_array($data['ps_product']);
    $url = $urban_product_model['cover_img'];

    if(strpos($url, 'http') === false){
            $url = strpos($url, "http://") || strpos($url, "https://") ? $url : 'http://'.$url;
        }

    $size = getimagesize($url);
    dd($size);
    $img = get_headers($url, 1);
    echo '<tr><td>'.$counter.'</td>';
    echo '<td>'.(isset($hotel_room['hotel_name']) ? $hotel_room['hotel_name'] : 'None').'  '.$room_info['name'][1].'</td>';
    echo '<td>'.$url.'</td>';
    echo '<td>'.$size[3].'</td>';
    echo '<td>'.($img["Content-Length"]/102/1000).'</td>';
    echo '<td>'.$data['is_publish'].'</td></tr>';

    $counter ++;
  }
  
  echo ' </table>';
  dd("End");

});


Route::get('urbanTest5s', function (){
  ini_set('max_execution_time', 300000);
  ini_set("memory_limit", '256M');
  Setting::set_dynamic_connection('gostaymy_mysql');
  //Setting::set_dynamic_connection('usyncmanagement_mysql');
  //Setting::set_dynamic_connection('olago2u_mysql');
  //Setting::set_dynamic_connection('urban_intellengence_mysql');
  //dd(DB::connection() );
  $urban_api = new UrbanAPI();
  $hotel_listing = $urban_api->get_test_data();
  //dd($hotel_listing);
  //dd(count(json_decode($hotel_listing)));
  //dd(json_decode($hotel_listing));
  //echo "X";
  $edit_flag = false;
  $counter = 1;
  //dd(json_decode($hotel_listing));
  echo '<table border = 1> <tr> <th>#</th> <th>Name</th> <th>Url</th> <th>Size</th>  </tr>';
  foreach (json_decode($hotel_listing)  as $key => $hotel){
    $data = (array) $hotel;
    //dd($data);
    if(!isset($data['ps_product'])){ continue;}
    $urban_product_model = UrbanAPI::convert_to_array($data['ps_product']);
    if(!isset($data['ps_htl_room_type'])){
      continue;
    }
      
    if(!isset($data['ps_htl_room_type'])){
      continue;
    }

    

    $hotel_room = /*(array)*/ UrbanAPI::convert_to_array($data['ps_htl_room_type']);

    if(!isset($data['ps_product'])){continue;}
    $room_info = UrbanAPI::convert_to_array($data['ps_product']);
    //$room_info['name'][0]
    //dd($room_info['name']);
    
    $url = $urban_product_model['cover_img'];

    if(strpos($url, 'http') === false){
            $url = strpos($url, "http://") || strpos($url, "https://") ? $url : 'http://'.$url;
        }

    $size = getimagesize($url);
    
    echo '<tr><td>'.$counter.'</td>';

    echo '<td>'.(isset($hotel_room['hotel_name']) ? $hotel_room['hotel_name'] : 'None').'  '.$room_info['name'][1].'</td>';
    echo '<td>'.$url.'</td>';
    echo '<td>'.$size[3].'</td></tr>';

    //dd($room_info);
  //  dd($data);
    //$price_listing = /*(array)*/ UrbanAPI::convert_to_array($data['specific_price']);
    //dd($price_listing);
    //WpPostmeta::convert_urban_price_model_listing_to_date_price_data($price_listing);
    //dd($price_listing);
    
    //dd($hotel_room);
    //dd($data);
    //echo $counter."=".(isset($hotel_room['hotel_name']) ? $hotel_room['hotel_name'] : 'None')."<br>";

    if(!isset($hotel_room['hotel_name'])){
      //dd($data);
      //echo 'Fail case :'.json_decode($data)."<br> <br> <br> <br>";
    }else{
      // if($hotel_room['hotel_name'] == 'Oikos Poshtel'){
        //if($hotel_room['hotel_name'] == 'Dorsett Sri Hartamas Residence'){
          //if($hotel_room['hotel_name'] == 'The Atelier'){
      /*if($edit_flag == true){
            break;
          }*/
      //if($counter > 0 && $counter < 10){
      //if($counter > 10 && $counter < 19){
      //if($counter > 0 && $counter < 35 && $counter != 1){
        if($size[0] == 1200 && $size[1] ==960){
          echo UrbanAPI::save_property_from_urban($hotel);
        }
          

     // }else{
        // UrbanAPI::save_property_from_urban($hotel);
        //dd($data);
      // /}
      //}
      //UrbanAPI::save_property_from_urban($hotel);
    }
    //$temp = (array)$hotel->specific_price;
    /*dd(WpPostmeta::convert_urban_price_model_listing_to_date_price_data($temp));
    dd($temp['customizable']);*/
    //UrbanAPI::save_property_from_urban($hotel);
    $counter ++;
  }
  UrbanAPI::clear_cache('http://localhost/hotel');
  echo ' </table>';
  dd("End");
    $params['user_email'] = 'peterooi83@gmail.com';
    $params['user_password'] = '123123';
    $leaf_api = new LeafAPI();
    $return  = $leaf_api->login($params);
    dd($return);

  dd(UrbanAPI::preset_meta_value_array);

});



Route::get('cp_reader', function (){
  //$folder_name = '26_8_2020';

  $fileToRead = "C:\\Users\\KyGoh\\Desktop\\read";
  if(isset($_GET["folder"])){

    $folder_name = $_GET["folder"];
  }else{

    $folder_name = '14_9_2020';
  }
  
  ini_set('max_execution_time', 300000);
  $directory = new RecursiveDirectoryIterator($fileToRead);
  
  $iterator = new RecursiveIteratorIterator($directory);
  $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
  //dd($Iterator);
  $current_date = date('Y-m-d');
  foreach($iterator as $path) {
    if((strpos($path,'.php') !== false || strpos($path,'.js') !== false || strpos($path,'.twig') !== false ) || strpos($path,'.css') !== false  && $path != '') {
      $source = str_replace("C:/Users/KyGoh/Desktop/read" , "/var/www/goleaf.my/temp_upload/" , str_replace("\\", "/" ,$path));
      $project_destination = str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/read" , "/var/www/goleaf.my" , str_replace("\\", "/" ,'PROJECT/'.$path)));
      $temp_destination = str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/read" , "/var/www/storage/modification" , str_replace("\\", "/" ,'STORAGE/'.$path)));

      /*echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$project_destination."<br>";*/
      
      echo 'cp '.$temp_destination.' '.$temp_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$temp_destination."<br>";

      echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$project_destination."<br>";

      /*echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
       echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
*/
       //echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
       //echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
       $operation_parameter = [/*'language_listing' => $language_listing ,*/ 'file_path' => $path ,'type'=>'catalog'];
    }
  }

});

//opers.facebook.com/docs/facebook-pixel/implementation/conversion-tracking/
//https://developers.facebook.com/docs/facebook-pixel/reference/
//https://www.digishuffle.com/blogs/facebook-standard-events/#s3
Route::get('cp_copier', function (){

//C:\xampp\htdocs\goleaf\pos\main.js


/*catalog/model/leaf/r_language_api.php
catalog/model/journal3/filter.php*/




  ini_set('max_execution_time', 300000);
  $project_root = 'C:\xampp\htdocs\goleaf';
  $project_modification = 'C:\xampp\storage';
  $destination_folder_name = 'C:\Users\KyGoh\Desktop\\'.date('d_m_Y', strtotime('now'));


//admin/model/extension/module/lazada_sync.php

  //,'catalog/controller/journal3/layout.php','catalog/view/theme/journal3/js/journal.js'
  //'catalog/controller/api/order.php','catalog/controller/api/cart.php','catalog/model/leaf/api.php'

  //'system/library/journal3.php'

$root_folders = ['admin/view/template/extension/export_import.twig','admin/language/en-gb/extension/export_import.php','admin/controller/extension/export_import.php'];
//'admin/view/template/extension/module/marketplace.twig','admin/language/en-gb/extension/module/marketplace.php','catalog/model/leaf/r_language_api.php'
$modification_folders = [];

  //The name of the directory that we need to create.
  $copy_files = array();
  $copy_modification_files = array();
  foreach($root_folders as $file)
  {
    $temp['source_file'] = $project_root.'\\'.$file;
    $temp['destination_file'] = $destination_folder_name.'\\'.$file;
    array_push($copy_files , $temp);
  }

  foreach($copy_files as $copy_file_detail)
  { 
    $path = pathinfo($copy_file_detail['destination_file']);
      if (!file_exists($path['dirname'])) {
          mkdir($path['dirname'], 0777, true);
      }   
      if (!copy($copy_file_detail['source_file'],$copy_file_detail['destination_file'])) {
          echo "copy failed \n";
      }
  }


  foreach($modification_folders as $file)
  {
    $temp['source_file'] = $project_modification.'\\'.$file;
    $temp['destination_file'] = $destination_folder_name.'\\'.str_replace('modification/' , '', $file);
    array_push($copy_modification_files , $temp);
  }

  foreach($copy_modification_files as $copy_file_detail)
  { //dd($copy_file_detail);
    $path = pathinfo($copy_file_detail['destination_file']);
      if (!file_exists($path['dirname'])) {
          mkdir($path['dirname'], 0777, true);
      }   
      if (!copy($copy_file_detail['source_file'],$copy_file_detail['destination_file'])) {
          echo "copy failed \n";
      }
  }
  
  
  dd('Done');
  $iterator = new RecursiveIteratorIterator($directory);
  $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
  //dd($Iterator);
  $current_date = date('Y-m-d');
  foreach($iterator as $path) {
    if((strpos($path,'.php') !== false || strpos($path,'.js') !== false || strpos($path,'.twig') !== false ) || strpos($path,'.css') !== false  && $path != '') {
      $source = str_replace("C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my/temp_upload/" , str_replace("\\", "/" ,$path));
      $project_destination = str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,'PROJECT/'.$path)));
      $temp_destination = str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,'STORAGE/'.$path)));

      /*echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$project_destination."<br>";*/
      
      echo 'cp '.$temp_destination.' '.$temp_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$temp_destination."<br>";

      echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$project_destination."<br>";

      /*echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
       echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
*/
       //echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
       //echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
       $operation_parameter = [/*'language_listing' => $language_listing ,*/ 'file_path' => $path ,'type'=>'catalog'];
    }
  }

});


Route::get('cp_generator', function (){
  //$folder_name = '26_8_2020';

  if(isset($_GET["folder"])){

    $folder_name = $_GET["folder"];
  }else{

    $folder_name = '14_9_2020';
  }

  $folder_name =date('d_m_Y', strtotime('now'));
  
  ini_set('max_execution_time', 300000);
  $directory = new RecursiveDirectoryIterator("C:/Users/KyGoh/Desktop/".$folder_name);
  
  $iterator = new RecursiveIteratorIterator($directory);
  $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
  //dd($Iterator);
  $current_date = date('Y-m-d');
  foreach($iterator as $path) {
    if((strpos($path,'.php') !== false || strpos($path,'.js') !== false || strpos($path,'.twig') !== false ) || strpos($path,'.css') !== false  && $path != '') {
      $source = str_replace("C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my/temp_upload/" , str_replace("\\", "/" ,$path));
      $project_destination = str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,'PROJECT/'.$path)));
      $temp_destination = str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,'STORAGE/'.$path)));

      /*echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$project_destination."<br>";*/
      
      echo 'cp '.$temp_destination.' '.$temp_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$temp_destination."<br>";

      echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
      echo 'cp '.$source.' '.$project_destination."<br>";

      /*echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
       echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
*/
       //echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
       //echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
       $operation_parameter = [/*'language_listing' => $language_listing ,*/ 'file_path' => $path ,'type'=>'catalog'];
    }
  }

});


Route::get('cp_generator_list', function (){

  $search_path_arr = array();
  $folder_list = ['16_7_2020','17_7_2020','20_7_2020','28_7_2020','9_8_2020','26_8_2020','14_9_2020'];

  $source_folder_name = "C:/Users/KyGoh/Desktop/zabbix/log/img_ori/new/producterror/login error";
  foreach($folder_list as $folder)
  {
    $temp['folder_name'] = $folder;
    $temp['full_path'] = $source_folder_name.'\\'.$folder;
    array_push($search_path_arr  , $temp);
  }
  ini_set('max_execution_time', 300000);
  foreach($search_path_arr as $folder_to_check)
  {
    $directory = new RecursiveDirectoryIterator($folder_to_check['full_path']);
    $iterator = new RecursiveIteratorIterator($directory);
    $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
    //dd($Iterator);
    $current_date = date('Y-m-d');
    foreach($iterator as $path) {
      if((strpos($path,'.php') !== false || strpos($path,'.js') !== false || strpos($path,'.twig') !== false ) || strpos($path,'.css') !== false  && $path != '') {
        $source = str_replace($source_folder_name."/" , "/var/www/goleaf.my/temp_upload/" , str_replace("\\", "/" ,$path));
        $project_destination = str_replace($folder_to_check['folder_name'] , '' ,str_replace("PROJECT/".$source_folder_name.'/' , "/var/www/goleaf.my" , str_replace("\\", "/" ,'PROJECT/'.$path)));
        $temp_destination = str_replace($folder_to_check['folder_name'], '' ,str_replace("STORAGE/".$source_folder_name  , "/var/www/storage/modification" , str_replace("\\", "/" ,'STORAGE/'.$path)));

        /*echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
        echo 'cp '.$source.' '.$project_destination."<br>";*/
        
        echo 'cp '.$temp_destination.' '.$temp_destination.$current_date."<br>";
        echo 'cp '.$source.' '.$temp_destination."<br>";

        echo 'cp '.$project_destination.' '.$project_destination.$current_date."<br>";
        echo 'cp '.$source.' '.$project_destination."<br>";

        /*echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
         echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
  */
         //echo 'cp '.str_replace($folder_name , '' ,str_replace("PROJECT/C:/Users/KyGoh/Desktop/" , "/var/www/goleaf.my" , str_replace("\\", "/" ,$path.' PROJECT/'.$path)))."<br>";
         //echo 'cp '.str_replace($folder_name , '' ,str_replace("STORAGE/C:/Users/KyGoh/Desktop/" , "/var/www/storage/modification" , str_replace("\\", "/" ,$path.' STORAGE/'.$path)))."<br>";
         $operation_parameter = [/*'language_listing' => $language_listing ,*/ 'file_path' => $path ,'type'=>'catalog'];
      }
    }

  }
  

});

Route::get('translation', function (){

  ini_set('max_execution_time', 300000);
  $directory = new RecursiveDirectoryIterator("C:/xampp/htdocs/goleaf/catalog/language");
  $iterator = new RecursiveIteratorIterator($directory);
  $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
  //dd($Iterator);

  foreach($iterator as $path) {
    if(strpos($path,'.php') !== false && $path != '') {
       echo $path."<br>";
       $operation_parameter = [/*'language_listing' => $language_listing ,*/ 'file_path' => $path ,'type'=>'catalog'];
       OcTranslationsWord::save_all_content($path,$operation_parameter);
    }
  }
  dd("e");
  

dd("stop");
  //dd (date("Y-m-d", strtotime('11-10-2019')));
  
  //foreach ($language as $key => $value){
    $language_listing  = OpencartLanguageTranslator::get_oc_language_list();
    $file = 'C:\xampp\htdocs\goleaf\catalog\language\en-gb\account\affiliate.php';
    $content = OcTranslationsWord::get_langauge_file_content($file);

    
    dd("end");
  //}
});

Route::get('test_create_folder', function (){
  ini_set('max_execution_time', 300000);
    $r_dic = 'C:/xampp/htdocs/goleaf/image/new/product';
    OCProduct::create_product_image_directory($r_dic);
});

Route::get('test_create_folder2', function (){
  ini_set('max_execution_time', 300000);
  OCSetting::save_folder_as_zip('C:/xampp/htdocs/goleaf/image/new/product','C:\Users\KyGoh\Desktop\product2.zip');
  dd("Done");
    $r_dic = 'C:/xampp/htdocs/goleaf/image/new/product';
    OCProduct::create_product_image_directory($r_dic);
});


Route::get('save_zips', function (){
  //dd("12");
    OCSetting::save_folder_as_zip('C:/xampp/htdocs/goleaf/image/new/product','C:\Users\KyGoh\Desktop\product2.zip');
});

Route::get('calculate_fee', function (){

  dd(SkyNetAPI::calculate_parcel_fee(1600));
});


Route::get('admin_data', function (){

  $WpOption_model = 'wprentals_admin';
});


Route::get('skynetDO', function (){

  $sky_net_api = new SkyNetAPI();

  $result = $sky_net_api->create_delivery_order();
  dd($result);

  dd($result);
  $to_save = 'JVBERi0xLjMNCjEgMCBvYmoNClsvUERGIC9UZXh0IC9JbWFnZUIgL0ltYWdlQyAvSW1hZ2VJXQ0KZW5kb2JqDQo3IDAgb2JqDQo8PCAvTGVuZ3RoIDEzMTkgL0ZpbHRlciAvRmxhd';
  $destination = 'C:\Users\KyGoh\Desktop\Huawei\test.jpg';
  Setting::base64_to_jpeg($to_save , $destination );
});

Route::get('test_save64', function (){

  $sky_net_api = new SkyNetAPI();
  $result = $sky_net_api->print_awb_test();
  dd($result);
  $base64_string = $result['printAWB'];
  $output_file = 'C:\Users\KyGoh\Desktop\leaf_web_21_7_2020\test.pdf';
  $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 0 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    dd($output_file); 

  
  dd($result);
  $result = $sky_net_api->create_delivery_order();
  
  dd($result);
  $to_save = 'JVBERi0xLjMNCjEgMCBvYmoNClsvUERGIC9UZXh0IC9JbWFnZUIgL0ltYWdlQyAvSW1hZ2VJXQ0KZW5kb2JqDQo3IDAgb2JqDQo8PCAvTGVuZ3RoIDEzMTkgL0ZpbHRlciAvRmxhd';
  $destination = 'C:\Users\KyGoh\Desktop\Huawei\test.jpg';
  Setting::base64_to_jpeg($to_save , $destination );
});



Route::get('update_company_contact', function ()
{ 
   $urban_api = new UrbanAPI();
   $urban_company_model = (array)json_decode(substr( $urban_api->get_company_detail(), 1, -1 ) ) ;
   dd( $urban_company_model );
   echo "<br>";
   foreach ($urban_company_model as $key => $value)
   {
    echo $key.'='.$value."<br>";
   }
   dd("stop");
   $option_value =  'a:3:{i:1;a:8:{s:5:\"title\";s:7:\"Contact\";s:12:\"address_info\";s:82:\"COMPANY_ADDRESS\";s:8:\"phone_no\";s:14:\"COMPANY_PHONE_NO\";s:6:\"fax_no\";s:0:\"\";s:5:\"email\";s:20:\"COMPANY_EMAIL\";s:5:\"skype\";s:0:\"\";s:11:\"website_url\";s:26:\"COMPANY_WEBSITE\";s:16:\"website_url_text\";s:10:\"COMPANY_NAME\";}i:2;a:8:{s:5:\"title\";s:7:\"Contact\";s:12:\"address_info\";s:82:\"COMPANY_ADDRESS\";s:8:\"phone_no\";s:14:\"(305) 555-4446\";s:6:\"fax_no\";s:14:\"COMPANY_FAX_NO\";s:5:\"email\";s:20:\"COMPANY_EMAIL\";s:5:\"skype\";s:11:\"SKYPE_ID\";s:11:\"website_url\";s:26:\"COMPANY_WEBSITE\";s:16:\"website_url_text\";s:10:\"COMPANY_NAME\";}s:12:\"_multiwidget\";i:1;}';
  
   $key_look_up_array =  ['COMPANY_ADDRESS' => 'hotel_address' ,'COMPANY_PHONE_NO' => 'hotel_phone' ,'COMPANY_EMAIL' => 'hotel_email' ,'COMPANY_WEBSITE' => 'social_facebook' ,'COMPANY_NAME' => 'web_source' ];

   foreach ($key_look_up_array as $look_up_key => $model_key)
   {
    echo "<br>".$look_up_key.'='.$model_key;
    $option_value = str_replace($look_up_key, $urban_company_model[$model_key], $option_value);
    //$option_value = str_replace($look_up_key, $urban_company_model['$model_key'], $option_value);
   }
   //$array = explode(';',$option_value);
   
   dd($option_value);
   //dd($urban_company_model);

});



Route::get('update_date', function ()
{ 
    $date_string = '{i:1580515200;d:100;i:1580601600;d:100;i:1580688000;d:100;i:1580774400;d:100;i:1580860800;d:100;i:1580947200;d:100;i:1581033600;d:100;i:1581120000;d:100;i:1581206400;d:100;i:1581292800;d:100;i:1581379200;d:100;i:1581465600;d:100;i:1581552000;d:100;i:1581638400;d:100;i:1581724800;d:100;i:1581811200;d:100;i:1581897600;d:1000;i:1581984000;d:1000;i:1582070400;d:1000;i:1582156800;d:1000;i:1582243200;d:1000;i:1582329600;d:1000;i:1582416000;d:1000;i:1582502400;d:1000;i:1582588800;d:1000;i:1582675200;d:1000;i:1582761600;d:1000;i:1582848000;d:1000;i:1582934400;d:1000;i:1583020800;d:1000;i:1583107200;d:1000;i:1583193600;d:1000;i:1583280000;d:1000;i:1583366400;d:1000;i:1583452800;d:1000;i:1583539200;d:1000;i:1583625600;d:1000;i:1583712000;d:1000;i:1583798400;d:1000;i:1583884800;d:1000;i:1583971200;d:1000;i:1584057600;d:1000;i:1584144000;d:1000;i:1584230400;d:1000;i:1584316800;d:1000;i:1584403200;d:1000;i:1584489600;d:1000;i:1584576000;d:1000;i:1584662400;d:1000;i:1584748800;d:1000;i:1584835200;d:1000;i:1584921600;d:1000;i:1585008000;d:1000;i:1585094400;d:1000;i:1585180800;d:1000;i:1585267200;d:1000;}';
    //date and price
    $price_list = array();
    $price_tag = ['date'=> '', 'price' =>''] ;
    $price_setting_array = explode( ';' ,   str_replace('{' , '' ,   str_replace('}' , ''  , $date_string ) ) );
    
    foreach ($price_setting_array as $key => $value)
    { if($value ==''){continue;}    
      if(strpos( $value ,'i' ) !== false){
        $temp = explode(':' , $value);
        $price_tag['date'] = $temp[count($temp) -1];
        
      }else if(strpos( $value, 'd') !== false){
        $temp = explode( ':' , $value);
        $price_tag['price'] = $temp[count($temp) -1];
        array_push($price_list , $price_tag);
      }
    }

    dd($price_list);

});
Route::get('basicTest', function ()
{ 


    $data_payment = array(
    'id_product' => 13,
);

$payload = json_encode($data_payment);

  $get_data = UrbanAPI::call_api('POST', 'http://i-urban.my/webapimodule/getpayment', $payload);
  $date_price_listing = array();
  foreach(json_decode($get_data) as $payment_model)
  {
    $temp['i'] = $payment_model->date_from;
    $temp['d'] = $payment_model->amount;
    
    array_push($date_price_listing, $temp);
  }
  //dd($date_price_listing);

  $return = '';
  foreach ($date_price_listing as $model)
  {
    $return .= 'i:'.$model['i'].';'.'d:'.$model['d'].';';
  }
  dd("{".$return."}");
});



Route::get('testMethod', function ()
{
  //$wp_model = new WpPost();
  $url = 'https://vignette.wikia.nocookie.net/gundam/images/6/66/Gfas-x1.jpg/revision/latest/scale-to-width-down/310?cb=20061223105459';
  dd(UrbanAPI::save_property_file_from_urban_url($url));
  $wp_model = WpPost::find(137);
  dd($wp_model);

});



Route::get('IpayFinalex', function ()
{

  $days_trans_id = array('T003070254619','T003070035320','T002981728520','T002981680520','T002971971319','T002944731320','T002926499720','T002926492219','T002926443920','T002926436419','T002924392820','T002924100020','T002924118920','T002885436020','T002839702820','T002830872919','T002830004720','T002806272920','T002749209919','T002682419420','T002669651120','T002664220520','T002663985020','T002654172320','T002650479319','T002611736420','T002598498320','T002596731020','T002596440320','T002591240719','T002591235620','T002591228719','T002591061020','T002580665420','T002572341620','T002572065320','T002572008320','T002571996019','T002571899120','T002571756920','T002571747020','T002563540220','T002563502120','T002555230220','T002554917920','T002554791020','T002554784120','T002554772720','T002550152719','T002537004320','T002536986020','T002496772820','T002496701420','T002462011520','T002422785620','T002355549620','T002344593020','T002337525020','T002333245520','T002332140920','T002332122320','T002320496120','T002320468520','T002319285020','T002316625219','T002316516019','T002316064219','T002310298519','T002297646919','T002297579719','T002289779719','T002289474019','T002286204619','T002281556719','T002272233919','T002270877019','T002267792719','T002267337319','T002266107619','T002263040119','T002261620219','T002249112319','T002160176719','T002028801919','T001951979119','T001913673319','T213939591219','T213924444219','T213852016119','T213820403619','T213792389619','T213777128319','T213767237320','T213765613119','T213765189819','T213739109919','T213733375719','T213732295719','T213697831719','T213637726419','T213631673919','T213553187920','T213553107219','T213549808419','T213547112019','T213547110220','T213494653720','T213494583820','T213494305719','T213487175619','T213476250819','T213475859619','T213431156919','T213431073819','T213393963819','T213390035619','T213389855019','T213378962919','T213372531819','T213345020020','T213334858419','T213334593819','T213334388619','T213330179619','T213325576420','T213318919719','T213287393919','T213233650419','T212798741919','T212768949219','T212732724820','T212727024519','T212726410419','T212726051619','T212725786719','T212725701219','T212724822819','T212691182019','T212635138719','T212621351320','T212621326120','T212617912119','T212590877920','T212580955419','T212573319519','T212544287019','T212525159619','T212506257819','T212497520619','T212497458219','T212496279219','T212495714619','T212495474019','T212493819519','T212490683319','T212476129719','T212470109020','T212469750520','T212464968819','T212429436519','T212416720119','T212383224220','T212299018419','T212289272019','T212233788519','T212222277819','T212211215919','T212211083019','T212193550419','T212183803419','T212180875419','T212126107119','T212120858319','T212120731419','T212120670219','T212108320419','T212107878519','T212092523319','T212082468219','T212046803919','T212045163219','T212042355519','T212024447019','T212009123019','T212001732219','T211966768419','T211909743519','T211909303419','T211882142619','T211848956919','T211832488719','T211823411919','T211823200419','T211808284119','T211793680119','T211770273219','T211768508619','T211766205219','T211742484819','T211742316519','T211724522619','T211713846519','T211690017819','T211639599819','T211625638419','T211621431219','T211595230719','T211594990719','T211590342819','T211577753319','T211577448519','T211575556119','T211571674119','T211546615419','T211544834619','T211544820219','T211493259519','T211492895919','T211492283019','T211461224619','T211457403519','T211456548819','T211446557319','T211439705019','T211384048419','T211383865419','T211383609219','T211374637719','T211366494519','T211362891819','T211362867219','T211353263619','T211353138219','T211339220319','T211337573019','T211298963319','T211286197419','T211280181519','T211279161219','T211276233819','T211274974419','T211271956419','T211256879319','T211252503819','T211246581219','T211098534519','T211072150119','T211041225219','T203689944519','T203740706019','T203868788919','T203878427019','T203802240819','T203804998419','T203805116319','T203895659019','T203896532319','T202566261819','T202634263719','T202574157819','T202574288319','T202656024519','T202597818219','T203909417019','T204095073519','T204056087319','T204239507019','T204216208119','T204216231819','T204318090519','T202820365419','T202831477719','T202837806219','T202854157419','T202854369819','T202867658919','T202869809319','T202869911619','T202871689719','T202873281219','T203083566219','T203083701519','T203083812819','T202881726219','T203146259019','T203171830719','T203077069719','T203083342119','T203213535519','T203213834019','T203214181419','T203214518019','T203239895019','T203246146719','T203182590519','T203263886019','T203314631319','T203345989419','T203420862819','T203432333919','T203432604819','T203455600419','T203460882219','T203461406019','T203497500219','T203463226119','T203532580419','T203561189919');
  
    
  $days_user_name = array('Dashinipriya','darshini a/ kalimuthu','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Siti Najiha Binti Mohd Razali','Alissa Shamsudin','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Nurul Faezah binti Badri','Nurul Hidayah Roslan','Nornazifah binti Ahmad Sapri','Siti Najiha Binti Mohd Razali','Nurmeymeng zalia','Nur Shakirah binti Kamal Ariffin','Nurul Akmal Fatihah bt Abd Hadi','Yap Tai Loong','Gan ChinTeng','Gan ChinTeng','syafiqhairunazmi','Gan ChinTeng','Sheba Solomi Moses Vejaya Kumar','Dhashini Devi A/p Sinniah','ainun shahria','ainun shahria','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Mohd Amsyar Bin Bacho','ERNIE DUSILY','Vijy Balan','farah hanis','farah hanis','farah hanis','Nur Fazira Binti Jusoh','Nur Fazira Binti Jusoh','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Faiz Asni','Faiz Asni','Faiz Asni','Faiz Asni','Ng Jing Tien','ainin azyyati','ainin azyyati','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Elizabeth Elly','wenqi','Shaheen Saleam','Jiesee Yong','NURUL SYAFIKA','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Hasmizah Khalid','Hasmizah Khalid','Deepaah Subramamian','Choo Yuen Seng','Choo Yuen Seng','Choo Yuen Seng','liyana binti abdullah','Nur Shakirah binti Kamal Ariffin','Nur Shakirah binti Kamal Ariffin','hijrah md isa','Remorn anak Jipong','Nur Atiqah Binti Manan','Mohammad Amirul Ashraf','nurfarahanim','Zulaikha Mohd Taib','Siva Gamy','Nur Fazieraa Binti Jaafar','Siti Najiha Binti Mohd Razali','Femmy CL','NIVARSHINI','Muhammad Safuan Bin Ahmad Jalaludin','IHSAN ISMAIL','Nurul Ain','Rasnih Nuil','Nur Atiqah Binti Manan','Siti H','nur afrina','yap lee Kei','ROSSHEILAWANI BT MOHD RAZALI','Hanna Hii','noraini binti mohd zaidi','Geetha Nair Sundaram','darshini darshu','Dashinipriya','nor athirah bt ibrahim @ azizi','Mohd Fadeli','Hasmizah Khalid','Ruzan Jehan Mohd Ayob','Khoo Er Teng','Amin Nazir','liew choon cheuan','liew choon cheuan','noor azira','Shalini Karinalili','Shalini Karinalili','Ling Hui Jin','Ling Hui Jin','Maginei Misaa','Thong Ying Hoong','Tilasini Jagathesan','Tilasini Jagathesan','Nur Izzati','Nur Izzati','crystal Lau','Lee Chi Yi','Dan Qing Khaw','Stella Tiong','victoria anak iyip','Goh','Saran Dorai','Sheba Solomi Moses Vejaya Kumar','Sheba Solomi Moses Vejaya Kumar','Nur Syahidah binti Mohaidi','Geetha Nair Sundaram','Lilian Lim','Nurmeymeng zalia','Nurul Hidayah Roslan','Peai Hui','irene smilewan','Anjum Anwar','jimah','jimah','jimah','jimah','jimah','Saran Dorai','Nurul Syahirah','Hoexinjing','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Ateng Roslan','Aylvin Wong','JANESSA anakTERANG','Nurul Nadhirah Binti Hamzah','Liew yue xuan','Soo Jin Gui','Rubini Maniam','Fatin Athira','fairuza munirah bt mazlan','syarmimi rima yolanda','fathi yahya','fathi yahya','Charles97','Alissa Shamsudin','ERNIE DUSILY','Fatin Najihah Abdullah','Fatin Najihah Abdullah','Lilian Lim','Nor Nadzirah Bt Shaari','Haniff Zakwan','Ooi Man Thing','Palanikumar Kamaraj','Zasmin Aisha Binti Naumul','Lim Siow Yin','Shi Ring','Lim Siow Yin','Lim Siow Yin','Sharifah Hazirah Binti Syed Ahmad','Yap Tai Loong','Thong Ying Hoong','Kaiting Lim','rafidah','rafidah','rafidah','Siva Gamy','Siva Gamy','Goh Quo Yee','Nurul Akmal Fatihah bt Abd Hadi','Ana Razaly','Yap Tai Loong','liyana binti abdullah','Amin Nazir','Siti Najiha Binti Mohd Razali','marlia syuhada','Mohamad Jafni','Amila Solihan','Amila Solihan','Deanna Chua Li Ann','farah hanis','Aimi Nabila','Hammsavaally Ganesan','Siti Hajiah Binti Rani','Monica Bandi','Fazleen Izwana Masrom','Mohd Firdaus Bin Ibrahim','Nurul Ain','mohamad humam bin mohamad isa','casterchu','casterchu','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','Altwkzh Wardah','Ros anis farhanah','noraini binti mohd zaidi','Nur Hidayah Bt Ahmad Faizal','wong mei yee','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Eline Tie','aidy md dzahir','aidy md dzahir','Mohd Firdaus Bin Ibrahim','Mohd Khairulamirin','Nur Fazieraa Binti Jaafar','nooradira noordin','Siti Nadia Binti Sapari','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Muhammad Izzat','alice elizabeth','Nursyamimi binti Mazri','mohamad humam bin mohamad isa','Amin Nazir','nur aena','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Syaziana Binti Ali Kabar','Alissa Shamsudin','nurfarahanim','irene smilewan','hijrah md isa','hijrah md isa','Noor Syafiqah','ROZANA BINTI SAHRI','Anne Felicia Paul','Muhamad Hasri Shafee','Nurhafizah Mat Nafi','noraini binti mohd zaidi','Nurmeymeng zalia','Amown Daebak Sieyrien','Hemaa Abby','maisarah','Zulaikha Mohd Taib','Daranica','Wong Pei Ti','yap lee Kei','Tharshini Muthusamy','Ahmad Hilman Affandi','Tee Jiong Rui Jane','Amin Nazir','hameeza','Nur Syahidah binti Mohaidi','Nurul Najihah','Nurul Najihah','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Nur mizah','ERNIE DUSILY','Remorn anak Jipong','Remorn anak Jipong','Norazlin Binti Iskan','JANESSA anakTERANG','norsyakila yaacob','hew Lee sin','melita','Yung Ying Hsia','Ivory Chin Ai Wei','Ivory Chin Ai Wei','Ainul Mardiah Binti Ideris','Ling Hui Jin','crystal tan','Geetha Nair Sundaram','Siva Gamy','Siva Gamy','Muhammad Safuan Bin Ahmad Jalaludin','Aimi Nabila','siti aisyah','Zulaikha Mohd Taib','wenqi','Nurulfidya Syafika Binti Mohd Shopi','Normarini Morad','Nurulfidya Syafika Binti Mohd Shopi','Nurul Akmal Fatihah bt Abd Hadi','Tan Wen Li','Anis Sabirah','Leong Shwu Jye','Nurulfidya Syafika Binti Mohd Shopi','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Nurul Hidayah Roslan','nor hazwani bt ahmad tarmidi','Celine Ying','Han Yee Chen','Siti Najiha Binti Mohd Razali','ainun shahria','Audry Chieng Wen Wen','Ahmad Kamil Bin Kelin','Shalini Karinalili','Nurul Syahirah','Siti Najiha Binti Mohd Razali','nur shamimi shuhada binti rahimi','Anjum Anwar','Siti Najiha Binti Mohd Razali','Siti Najiha Binti Mohd Razali','liew choon cheuan','Arisya Shahirah');






  $days_date = array('29-1-2020 03:11:26 PM','29-1-2020 03:09:08 PM','28-1-2020 03:22:21 PM','28-1-2020 03:21:47 PM','28-1-2020 01:52:40 PM','28-1-2020 09:38:25 AM','27-1-2020 10:41:20 PM','27-1-2020 10:41:12 PM','27-1-2020 10:40:28 PM','27-1-2020 10:40:21 PM','27-1-2020 10:13:57 PM','27-1-2020 10:11:25 PM','27-1-2020 10:10:49 PM','27-1-2020 01:41:48 PM','26-1-2020 11:57:46 PM','26-1-2020 09:30:36 PM','26-1-2020 09:18:26 PM','26-1-2020 03:42:08 PM','25-1-2020 07:34:43 PM','24-1-2020 11:07:14 PM','24-1-2020 08:29:58 PM','24-1-2020 07:22:27 PM','24-1-2020 07:19:34 PM','24-1-2020 05:16:47 PM','24-1-2020 04:34:03 PM','24-1-2020 12:05:27 PM','24-1-2020 09:55:01 AM','24-1-2020 09:37:16 AM','24-1-2020 09:33:34 AM','24-1-2020 08:20:26 AM','24-1-2020 08:20:20 AM','24-1-2020 08:20:14 AM','24-1-2020 08:17:17 AM','24-1-2020 02:55:33 AM','23-1-2020 10:52:10 PM','23-1-2020 10:48:19 PM','23-1-2020 10:47:29 PM','23-1-2020 10:47:20 PM','23-1-2020 10:46:02 PM','23-1-2020 10:43:56 PM','23-1-2020 10:43:47 PM','23-1-2020 09:11:29 PM','23-1-2020 09:11:05 PM','23-1-2020 07:39:09 PM','23-1-2020 07:35:07 PM','23-1-2020 07:33:34 PM','23-1-2020 07:33:29 PM','23-1-2020 07:33:21 PM','23-1-2020 06:38:43 PM','23-1-2020 04:21:08 PM','23-1-2020 04:20:53 PM','23-1-2020 03:18:36 PM','23-1-2020 03:17:53 PM','23-1-2020 12:21:34 PM','23-1-2020 09:52:51 AM','22-1-2020 03:58:36 PM','22-1-2020 02:20:41 PM','22-1-2020 01:18:26 PM','22-1-2020 12:39:53 PM','22-1-2020 12:30:29 PM','22-1-2020 12:30:18 PM','22-1-2020 10:47:57 AM','22-1-2020 10:47:44 AM','22-1-2020 10:37:25 AM','22-1-2020 10:14:22 AM','22-1-2020 10:13:38 AM','22-1-2020 10:10:28 AM','22-1-2020 09:17:30 AM','22-1-2020 04:44:57 AM','22-1-2020 04:37:37 AM','21-1-2020 11:11:25 PM','21-1-2020 11:07:11 PM','21-1-2020 10:25:44 PM','21-1-2020 09:39:05 PM','21-1-2020 07:50:14 PM','21-1-2020 07:32:40 PM','21-1-2020 06:54:17 PM','21-1-2020 06:48:01 PM','21-1-2020 06:32:36 PM','21-1-2020 05:54:33 PM','21-1-2020 05:36:37 PM','21-1-2020 03:13:12 PM','20-1-2020 04:21:47 PM','19-1-2020 07:16:06 PM','18-1-2020 07:35:20 PM','18-1-2020 11:46:49 AM','16-1-2020 12:32:29 PM','16-1-2020 10:22:50 AM','15-1-2020 03:02:48 PM','15-1-2020 09:36:00 AM','14-1-2020 08:23:22 PM','14-1-2020 04:47:29 PM','14-1-2020 02:40:37 PM','14-1-2020 02:20:46 PM','14-1-2020 02:15:57 PM','14-1-2020 09:20:56 AM','14-1-2020 07:54:40 AM','14-1-2020 07:26:51 AM','13-1-2020 08:37:48 PM','13-1-2020 12:34:04 AM','12-1-2020 10:17:34 PM','11-1-2020 08:14:43 PM','11-1-2020 08:13:38 PM','11-1-2020 07:25:21 PM','11-1-2020 06:43:58 PM','11-1-2020 06:43:56 PM','10-1-2020 11:57:40 PM','10-1-2020 11:55:55 PM','10-1-2020 11:49:13 PM','10-1-2020 09:49:37 PM','10-1-2020 07:24:42 PM','10-1-2020 07:19:00 PM','10-1-2020 10:48:49 AM','10-1-2020 10:47:56 AM','9-1-2020 11:16:44 PM','9-1-2020 10:14:16 PM','9-1-2020 10:11:40 PM','9-1-2020 07:46:32 PM','9-1-2020 06:14:18 PM','9-1-2020 12:44:01 PM','9-1-2020 10:59:14 AM','9-1-2020 10:56:34 AM','9-1-2020 10:54:33 AM','9-1-2020 10:13:44 AM','9-1-2020 09:28:50 AM','9-1-2020 08:24:11 AM','8-1-2020 07:05:51 PM','8-1-2020 09:32:02 AM','6-1-2020 12:05:29 PM','5-1-2020 11:37:45 PM','5-1-2020 03:01:35 PM','5-1-2020 01:49:52 PM','5-1-2020 01:42:41 PM','5-1-2020 01:38:20 PM','5-1-2020 01:35:09 PM','5-1-2020 01:34:14 PM','5-1-2020 01:23:56 PM','4-1-2020 10:35:40 PM','4-1-2020 10:47:57 AM','4-1-2020 06:28:26 AM','4-1-2020 06:26:04 AM','4-1-2020 12:58:41 AM','3-1-2020 06:25:31 PM','3-1-2020 04:21:54 PM','3-1-2020 02:51:01 PM','3-1-2020 09:40:45 AM','2-1-2020 10:41:28 PM','2-1-2020 06:32:43 PM','2-1-2020 04:39:17 PM','2-1-2020 04:38:30 PM','2-1-2020 04:24:29 PM','2-1-2020 04:18:20 PM','2-1-2020 04:15:36 PM','2-1-2020 03:57:02 PM','2-1-2020 03:21:34 PM','2-1-2020 12:47:11 PM','2-1-2020 11:46:51 AM','2-1-2020 11:43:22 AM','2-1-2020 10:58:21 AM','2-1-2020 03:31:42 AM','1-1-2020 09:50:03 PM','1-1-2020 03:22:54 PM','31-12-2019 05:49:00 PM','31-12-2019 04:12:15 PM','31-12-2019 03:49:52 AM','30-12-2019 10:23:02 PM','30-12-2019 08:17:26 PM','30-12-2019 08:15:53 PM','30-12-2019 04:38:05 PM','30-12-2019 02:57:44 PM','30-12-2019 02:26:50 PM','30-12-2019 09:18:45 AM','30-12-2019 08:02:07 AM','30-12-2019 07:59:10 AM','30-12-2019 07:57:51 AM','29-12-2019 10:39:40 PM','29-12-2019 10:32:40 PM','29-12-2019 07:01:10 PM','29-12-2019 04:36:24 PM','29-12-2019 07:47:52 AM','29-12-2019 06:09:40 AM','29-12-2019 01:19:47 AM','28-12-2019 08:11:55 PM','28-12-2019 04:46:19 PM','28-12-2019 03:13:49 PM','28-12-2019 06:11:26 AM','27-12-2019 01:26:30 PM','27-12-2019 01:21:45 PM','27-12-2019 08:22:31 AM','26-12-2019 11:21:33 PM','26-12-2019 07:39:02 PM','26-12-2019 05:37:16 PM','26-12-2019 05:34:42 PM','26-12-2019 02:48:38 PM','26-12-2019 12:22:32 PM','26-12-2019 08:47:25 AM','26-12-2019 08:22:32 AM','26-12-2019 07:32:40 AM','25-12-2019 11:25:50 PM','25-12-2019 11:22:39 PM','25-12-2019 07:12:45 PM','25-12-2019 04:45:09 PM','25-12-2019 12:02:35 PM','24-12-2019 07:28:29 PM','24-12-2019 04:39:46 PM','24-12-2019 03:54:56 PM','24-12-2019 11:41:06 AM','24-12-2019 11:38:56 AM','24-12-2019 10:57:30 AM','24-12-2019 08:40:35 AM','24-12-2019 08:36:30 AM','24-12-2019 08:08:33 AM','24-12-2019 05:38:09 AM','23-12-2019 10:57:41 PM','23-12-2019 10:31:20 PM','23-12-2019 10:31:08 PM','23-12-2019 02:26:44 PM','23-12-2019 02:23:14 PM','23-12-2019 02:17:15 PM','23-12-2019 09:33:55 AM','23-12-2019 08:51:59 AM','23-12-2019 08:42:10 AM','23-12-2019 01:01:24 AM','22-12-2019 10:43:30 PM','22-12-2019 11:19:55 AM','22-12-2019 11:17:44 AM','22-12-2019 11:14:37 AM','22-12-2019 08:57:21 AM','22-12-2019 12:50:39 AM','21-12-2019 11:26:46 PM','21-12-2019 11:26:13 PM','21-12-2019 09:14:30 PM','21-12-2019 09:13:04 PM','21-12-2019 06:26:01 PM','21-12-2019 06:05:13 PM','21-12-2019 11:01:14 AM','21-12-2019 07:54:14 AM','21-12-2019 01:22:45 AM','21-12-2019 12:45:25 AM','20-12-2019 11:41:35 PM','20-12-2019 11:20:50 PM','20-12-2019 10:38:45 PM','20-12-2019 07:43:19 PM','20-12-2019 06:52:14 PM','20-12-2019 05:44:40 PM','19-12-2019 10:38:31 AM','18-12-2019 10:50:22 PM','18-12-2019 04:43:51 PM','10-12-2019 01:37:55 PM','10-12-2019 03:23:37 PM','11-12-2019 04:36:11 PM','11-12-2019 06:25:57 PM','11-12-2019 06:26:37 AM','11-12-2019 07:47:47 AM','11-12-2019 07:50:11 AM','11-12-2019 09:45:39 PM','11-12-2019 09:55:43 PM','1-12-2019 01:02:48 AM','1-12-2019 07:03:10 PM','1-12-2019 08:16:30 AM','1-12-2019 08:18:47 AM','1-12-2019 11:04:57 PM','1-12-2019 12:42:45 PM','12-12-2019 01:32:43 AM','13-12-2019 03:21:06 PM','13-12-2019 06:45:27 AM','15-12-2019 01:11:27 PM','15-12-2019 06:30:04 AM','15-12-2019 06:32:43 AM','16-12-2019 11:26:25 AM','3-12-2019 01:29:16 PM','3-12-2019 03:19:52 PM','3-12-2019 04:28:09 PM','3-12-2019 07:35:23 PM','3-12-2019 07:37:57 PM','3-12-2019 09:56:24 PM','3-12-2019 10:21:53 PM','3-12-2019 10:23:03 PM','3-12-2019 10:46:18 PM','3-12-2019 11:08:28 PM','4-12-2019 01:00:13 PM','4-12-2019 01:01:30 PM','4-12-2019 01:02:35 PM','4-12-2019 06:13:49 AM','4-12-2019 06:25:21 PM','4-12-2019 11:50:34 PM','4-12-2019 11:58:38 AM','4-12-2019 12:58:01 PM','5-12-2019 01:17:34 PM','5-12-2019 01:20:14 PM','5-12-2019 01:23:15 PM','5-12-2019 01:26:14 PM','5-12-2019 05:46:34 PM','5-12-2019 06:56:19 PM','5-12-2019 08:08:07 AM','5-12-2019 10:07:15 PM','6-12-2019 01:55:17 PM','6-12-2019 07:25:31 PM','7-12-2019 03:58:32 PM','7-12-2019 06:22:14 PM','7-12-2019 06:25:46 PM','7-12-2019 11:24:54 PM','8-12-2019 02:06:41 AM','8-12-2019 02:47:32 AM','8-12-2019 03:14:13 PM','8-12-2019 06:36:10 AM','8-12-2019 10:34:50 PM','9-12-2019 08:41:26 AM');

  



$days_reference_no = array('13320','13319','13318','13317','13316','13315','13314','13314','13313','13313','13312','13311','13310','13308','13307','13306','13305','13303','13302','13300','13299','13298','13297','13294','13293','13292','13291','13290','13289','13286','13286','13286','13285','13284','13281','13280','13278','13278','13277','13275','13275','13274','13274','13272','13271','13270','13270','13270','13269','13268','13268','13267','13267','13263','13262','13261','13258','13257','13256','13255','13255','13254','13254','13252','13251','13250','13248','13247','13246','13245','13244','13243','13242','13241','13239','13238','13236','13235','13234','13233','13232','13230','13229','13226','13223','13221','13216','13215','13210','13207','13205','13204','13202','13201','13200','13197','13195','13194','13192','13190','13185','13181','13180','13178','13177','13177','13173','13172','13171','13169','13167','13165','13163','13162','13160','13159','13157','13156','13155','13153','13151','13149','13148','13146','13145','13144','13142','13140','13131','13129','13128','13126','13125','13124','13123','13122','13121','13118','13116','13115','13114','13113','13111','13109','13106','13101','13099','13097','13096','13093','13092','13091','13090','13089','13088','13087','13083','13080','13079','13076','13073','13067','13063','13061','13060','13059','13058','13057','13056','13052','13051','13046','13043','13042','13039','13037','13036','13035','13034','13031','13030','13028','13027','13026','13025','13024','13022','13021','13020','13018','13017','13016','13015','13014','13013','13010','13009','13008','13004','13003','13000','12999','12997','12996','12995','12993','12992','12991','12990','12989','12988','12987','12986','12981','12979','12980','12978','12977','12975','12974','12972','12971','12968','12966','12963','12962','12961','12959','12958','12956','12955','12954','12953','12948','12945','12942','12941','12936','12935','12934','12933','12932','12931','12930','12929','12919','12916','12915','12877','12879','12884','12886','12880','12881','12882','12891','12895','12808','12812','12809','12810','12813','12811','12896','12900','12899','12906','12904','12905','12910','12815','12819','12820','12821','12822','12824','12825','12826','12828','12829','12834','12835','12836','12830','12837','12838','12832','12833','12841','12842','12843','12844','12847','12849','12840','12850','12852','12854','12855','12858','12859','12860','12861','12863','12866','12864','12868','12871');



$days_amount = array('20.00','40.00','30.00','30.00','9.00','20.00','10.00','10.00','5.46','5.46','30.00','30.00','30.00','40.00','33.50','134.00','8.00','52.06','55.00','30.00','50.00','17.00','6.00','10.00','6.00','2.00','20.00','6.70','9.70','20.00','20.00','20.00','20.00','35.00','20.00','20.00','45.00','45.00','45.00','15.99','15.99','11.60','11.60','20.00','11.58','11.58','11.58','11.58','50.00','146.94','146.94','30.00','30.00','10.00','30.00','13.30','42.51','36.49','44.00','44.00','44.00','5.00','5.00','10.00','30.00','30.00','20.00','6.50','45.00','45.00','50.00','25.00','17.39','84.00','60.00','25.00','9.75','21.00','6.00','50.00','22.00','10.00','100.00','30.00','30.00','3.47','20.00','6.00','5.00','8.00','110.00','12.95','150.00','20.00','20.00','100.00','10.00','5.00','71.67','35.00','6.00','59.07','59.07','1.00','10.00','10.00','30.00','30.00','150.00','10.00','22.71','22.71','20.00','20.00','10.00','30.00','20.00','22.00','8.00','10.00','30.00','30.00','200.00','20.00','150.00','5.00','20.00','17.00','10.00','36.95','25.00','35.00','36.00','36.00','36.50','36.50','30.00','6.48','81.75','17.00','17.00','35.82','200.00','10.00','12.00','10.00','82.00','16.76','50.00','25.00','120.00','10.00','20.00','17.27','10.00','15.00','50.00','50.00','3.00','17.00','100.00','7.85','20.00','9.43','50.00','50.00','50.00','50.00','15.00','20.00','10.00','200.00','10.00','35.00','15.00','39.25','39.25','100.00','8.00','30.00','25.00','4.70','5.00','6.00','58.00','200.00','7.00','10.00','30.00','40.00','10.00','30.00','8.00','7.00','10.00','14.70','30.00','25.00','14.00','14.00','30.00','50.00','60.00','20.00','5.27','20.00','10.00','10.00','50.00','100.00','200.00','14.30','35.00','20.00','30.00','50.00','10.00','9.00','12.43','157.61','50.00','200.00','3.00','20.00','2.10','2.10','3.70','60.00','11.00','50.00','7.55','50.00','50.00','100.00','153.15','64.34','27.00','21.95','10.90','100.00','200.00','30.00','4.20','50.00','30.00','23.59','5.00','37.00','5.00','30.00','1.00','101.73','20.00','10.00','10.00','20.00','20.00','24.00','20.00','21.70','21.70','17.67','10.00','30.00','100.00','22.53','30.00','40.00','40.00','4.86','30.00','10.00','100.00','25.88','25.88','10.00','65.67','52.00','50.00','25.00','13.48','13.48','13.48','6.00','13.95','7.00','13.00','13.48','9.00','9.00','9.00','9.00','28.00','100.00','29.80','3.00','20.00','58.10','2.00','20.00','6.39','3.00','40.00','40.00','3.00','3.00','42.74','6.36');


$days_status = array('Success','Success','Success','Customer Drop the Transaction','Success','Success','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Success','Success','Fail','Fail','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Success','Success','Success','Success','Customer Drop the Transaction','Fail','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Customer Drop the Transaction','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Customer Drop the Transaction','Success','Success','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Fail','Fail','Fail','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Fail','Success','Success','Success','Success','Fail','Success','Fail','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Fail','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Fail','Fail','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success');


  $days_model_arr = array();
  for($x = 0; $x< count($days_date) ; $x ++){

    $temp = array('trans_id'=>$days_trans_id[$x],'reference_no'=> $days_reference_no[$x], 'document_date' => $days_date[$x], 'amount' => $days_amount[$x], 'name' => $days_user_name[$x], 'status' => $days_status[$x] ,'is_success' => $days_status[$x] == 'Success' ? 1 : 0 , 'date' =>
      date('Y-m-d', strtotime($days_date[$x])), 'created_at' => date('Y-m-d h', strtotime($days_date[$x])));
    array_push($days_model_arr,$temp);
  }

  ini_set('max_execution_time', 300000);

  $leaf_api  = new LeafAPI();
  $result = $leaf_api->get_check_payment($model['leaf_payment_id']);
  $uTransactionModel = UTransaction::all();
  $result_listing = array();
  $update_listing = array();



  foreach ($uTransactionModel as $model) {
    
    foreach($days_model_arr as $temp){
      if($temp['amount'] == $model['amount']  && $temp['date'] == $model['document_date'] && $temp['created_at'] ==  date('Y-m-d h', strtotime($model['created_at'])))
      {
        $model['trans_id'] = $temp['trans_id'];
        $model['real_result'] = $temp['status'];
        $model['is_success'] = $temp['status'];
        $model['model_name'] = $temp['name'];
        $model['reference_no'] = $temp['reference_no'];
      }
    }
    

    $result = $leaf_api->get_check_payment($model['leaf_payment_id']);

    if($model['reference_no'] != $result['payment_reference']){
      continue  ;
    }

    $meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
    $model['ie_is_paid']    = $model['is_paid'] ;
    $model['payment_paid']    = $result['payment_paid'] ;
    $model['payment_customer_name'] = $result['payment_customer_name'];
    $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
    $model['query_payment_rereference'] = $result['payment_reference'];
 
    //if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
    //dd($meter_payment_model);

    if($result['payment_paid'] == false){
      if($model['is_paid'] == true){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [wrong ie null model]';
          //array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'revert_item [wrong ie remove model]';
          //array_push($result_listing,$model);
        }
        
      }else{
        if(isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [null ie remove model]';
          //array_push($result_listing,$model);
        }
      }

      
    }

    if($result['payment_paid'] == true){
      if($model['ie_is_paid'] == false){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [null ie null model]';
          //array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'non_capture [wrong ie with model]';
          //array_push($result_listing,$model);
        }
        
      }else{
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [true ie null model]';
          //array_push($result_listing,$model);
        }
        
      }

      
    }


    if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
      
      $model['result_type'] = 'success payment - wrong ie - no model';
      
      if($result['payment_paid'] == true){
        $error_model = UTransaction::find($model['id']);
        if($error_model['is_paid'] == false){
          $error_model['is_paid'] = true ;
          
          $error_model->save();
          array_push($update_listing , $error_model);
          ProjectModelMapping::leaf_to_meter_payment_received_mapper($error_model,true);
        }
      }             
    }

    array_push($result_listing,$model);
}



  echo '<table>';
  echo '<tr>
        <th width="50px;">Utransaction ID</th>
          <th width="50px;">Leaf reference</th>
            <th width="50px;">Ipay Reference</th>
        <th width="50px;">Payment number</th>
          <th width="50px;">Document number</th>
        <th width="50px;">Model Name</th>
          <th width="50px;">Name</th>
          <th width="50px;">Utransaction ID</th>
          <th width="50px;">Payment Amount</th>
          <th width="50px;">Created At</th>      
         <th width="50px;">Sunmed Ipay88 Status</th>
         <th width="50px;">Sunmed Ipay88 Result</th>
         <th width="50px;">requery Status</th>
          <th width="50px;">Ie Status</th>
          <th width="50px;">Is with Model</th>
          <th width="50px;">Status</th>
     </tr>';

  
  usort($result_listing, 'App\Setting::compare_by_created_at');
  usort($result_listing, 'App\Setting::compare_by_column');
  usort($result_listing, 'App\Setting::compare_by_column');

  $to_display = array();
  foreach($result_listing as $result){
    if(!isset($result['real_result'])){continue;}
    array_push($to_display,$result);
  }

  $final_listing = array();
  foreach($to_display as $display){
    foreach($to_display as $check){
      if($display['trans_id'] == $check['trans_id']){
        array_push($final_listing,$check);
      }
    }
  }


  foreach($final_listing as $result)
  { 
    if(!isset($result['real_result'])){continue;}
    echo '<tr>
        <td>'.$result['id'].'</td>
        <td>'.$result['query_payment_rereference'].'</td>
        <td>'.$result['reference_no'].'</td>
        <td>'.$result['leaf_payment_id'].'</td>
          <td>'.$result['document_no'].'</td>
        <td>'.$result['model_name'].'</td>
          <td>'.$result['payment_customer_name'].'</td>
          <td>'.$result['trans_id'].'</td>
          <td>'.$result['amount'].'</td>
          <td>'.$result['created_at'].'</td>
          <td>'.$result['real_result'].'</td>
          <td>'.$result['is_success'].'</td>
          <td>'.$result['payment_paid'].'</td>
          <td>'.$result['ie_is_paid'].'</td>
          <td>'.$result['is_payment_model_created'].'</td>
          <td>'.$result['result_type'].'</td>
     </tr>';
    //echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
  }
  echo '</table>';

  echo 'Total '.count($result_listing).' records. <br>  <br> <br>';
  echo 'Update record . <br>  <br> <br>';
  foreach ($error_listing as $error_model){
    echo $error_model['id'].'='.$error_model['leaf_payment_id']."<br>";
  }
  
  dd('End');


  });


Route::get('Ipay88PaymentCheckCrossCheck_new2', function ()
{

  $days_reference_no = array('13320','13319','13318','13317','13316','13315','13314','13314','13313','13313','13312','13311','13310','13308','13307','13306','13305','13303','13302','13300','13299','13298','13297','13294','13293','13292','13291','13290','13289','13286','13286','13286','13285','13284','13281','13280','13278','13278','13277','13275','13275','13274','13274','13272','13271','13270','13270','13270','13269','13268','13268','13267','13267','13263','13262','13261','13258','13257','13256','13255','13255','13254','13254','13252','13251','13250','13248','13247','13246','13245','13244','13243','13242','13241','13239','13238','13236','13235','13234','13233','13232','13230','13229','13226','13223','13221','13216','13215','13210','13207','13205','13204','13202','13201','13200','13197','13195','13194','13192','13190','13185','13181','13180','13178','13177','13177','13173','13172','13171','13169','13167','13165','13163','13162','13160','13159','13157','13156','13155','13153','13151','13149','13148','13146','13145','13144','13142','13140','13131','13129','13128','13126','13125','13124','13123','13122','13121','13118','13116','13115','13114','13113','13111','13109','13106','13101','13099','13097','13096','13093','13092','13091','13090','13089','13088','13087','13083','13080','13079','13076','13073','13067','13063','13061','13060','13059','13058','13057','13056','13052','13051','13046','13043','13042','13039','13037','13036','13035','13034','13031','13030','13028','13027','13026','13025','13024','13022','13021','13020','13018','13017','13016','13015','13014','13013','13010','13009','13008','13004','13003','13000','12999','12997','12996','12995','12993','12992','12991','12990','12989','12988','12987','12986','12981','12979','12980','12978','12977','12975','12974','12972','12971','12968','12966','12963','12962','12961','12959','12958','12956','12955','12954','12953','12948','12945','12942','12941','12936','12935','12934','12933','12932','12931','12930','12929','12919','12916','12915');


  $days_trans_id = array('T003070254619','T003070035320','T002981728520','T002981680520','T002971971319','T002944731320','T002926499720','T002926492219','T002926443920','T002926436419','T002924392820','T002924100020','T002924118920','T002885436020','T002839702820','T002830872919','T002830004720','T002806272920','T002749209919','T002682419420','T002669651120','T002664220520','T002663985020','T002654172320','T002650479319','T002611736420','T002598498320','T002596731020','T002596440320','T002591240719','T002591235620','T002591228719','T002591061020','T002580665420','T002572341620','T002572065320','T002572008320','T002571996019','T002571899120','T002571756920','T002571747020','T002563540220','T002563502120','T002555230220','T002554917920','T002554791020','T002554784120','T002554772720','T002550152719','T002537004320','T002536986020','T002496772820','T002496701420','T002462011520','T002422785620','T002355549620','T002344593020','T002337525020','T002333245520','T002332140920','T002332122320','T002320496120','T002320468520','T002319285020','T002316625219','T002316516019','T002316064219','T002310298519','T002297646919','T002297579719','T002289779719','T002289474019','T002286204619','T002281556719','T002272233919','T002270877019','T002267792719','T002267337319','T002266107619','T002263040119','T002261620219','T002249112319','T002160176719','T002028801919','T001951979119','T001913673319','T213939591219','T213924444219','T213852016119','T213820403619','T213792389619','T213777128319','T213767237320','T213765613119','T213765189819','T213739109919','T213733375719','T213732295719','T213697831719','T213637726419','T213631673919','T213553187920','T213553107219','T213549808419','T213547112019','T213547110220','T213494653720','T213494583820','T213494305719','T213487175619','T213476250819','T213475859619','T213431156919','T213431073819','T213393963819','T213390035619','T213389855019','T213378962919','T213372531819','T213345020020','T213334858419','T213334593819','T213334388619','T213330179619','T213325576420','T213318919719','T213287393919','T213233650419','T212798741919','T212768949219','T212732724820','T212727024519','T212726410419','T212726051619','T212725786719','T212725701219','T212724822819','T212691182019','T212635138719','T212621351320','T212621326120','T212617912119','T212590877920','T212580955419','T212573319519','T212544287019','T212525159619','T212506257819','T212497520619','T212497458219','T212496279219','T212495714619','T212495474019','T212493819519','T212490683319','T212476129719','T212470109020','T212469750520','T212464968819','T212429436519','T212416720119','T212383224220','T212299018419','T212289272019','T212233788519','T212222277819','T212211215919','T212211083019','T212193550419','T212183803419','T212180875419','T212126107119','T212120858319','T212120731419','T212120670219','T212108320419','T212107878519','T212092523319','T212082468219','T212046803919','T212045163219','T212042355519','T212024447019','T212009123019','T212001732219','T211966768419','T211909743519','T211909303419','T211882142619','T211848956919','T211832488719','T211823411919','T211823200419','T211808284119','T211793680119','T211770273219','T211768508619','T211766205219','T211742484819','T211742316519','T211724522619','T211713846519','T211690017819','T211639599819','T211625638419','T211621431219','T211595230719','T211594990719','T211590342819','T211577753319','T211577448519','T211575556119','T211571674119','T211546615419','T211544834619','T211544820219','T211493259519','T211492895919','T211492283019','T211461224619','T211457403519','T211456548819','T211446557319','T211439705019','T211384048419','T211383865419','T211383609219','T211374637719','T211366494519','T211362891819','T211362867219','T211353263619','T211353138219','T211339220319','T211337573019','T211298963319','T211286197419','T211280181519','T211279161219','T211276233819','T211274974419','T211271956419','T211256879319','T211252503819','T211246581219','T211098534519','T211072150119','T211041225219');

  $days_status = array('Success','Success','Success','Customer Drop the Transaction','Success','Success','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Success','Success','Fail','Fail','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Success','Success','Success','Success','Customer Drop the Transaction','Fail','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Customer Drop the Transaction','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Customer Drop the Transaction','Success','Success','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Fail','Fail','Fail','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Fail','Success','Success','Success','Success','Fail','Success','Fail','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success');
    
  $days_user_name = array('Dashinipriya','darshini a/ kalimuthu','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Siti Najiha Binti Mohd Razali','Alissa Shamsudin','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Nurul Faezah binti Badri','Nurul Hidayah Roslan','Nornazifah binti Ahmad Sapri','Siti Najiha Binti Mohd Razali','Nurmeymeng zalia','Nur Shakirah binti Kamal Ariffin','Nurul Akmal Fatihah bt Abd Hadi','Yap Tai Loong','Gan ChinTeng','Gan ChinTeng','syafiqhairunazmi','Gan ChinTeng','Sheba Solomi Moses Vejaya Kumar','Dhashini Devi A/p Sinniah','ainun shahria','ainun shahria','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Mohd Amsyar Bin Bacho','ERNIE DUSILY','Vijy Balan','farah hanis','farah hanis','farah hanis','Nur Fazira Binti Jusoh','Nur Fazira Binti Jusoh','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Faiz Asni','Faiz Asni','Faiz Asni','Faiz Asni','Ng Jing Tien','ainin azyyati','ainin azyyati','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Elizabeth Elly','wenqi','Shaheen Saleam','Jiesee Yong','NURUL SYAFIKA','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Hasmizah Khalid','Hasmizah Khalid','Deepaah Subramamian','Choo Yuen Seng','Choo Yuen Seng','Choo Yuen Seng','liyana binti abdullah','Nur Shakirah binti Kamal Ariffin','Nur Shakirah binti Kamal Ariffin','hijrah md isa','Remorn anak Jipong','Nur Atiqah Binti Manan','Mohammad Amirul Ashraf','nurfarahanim','Zulaikha Mohd Taib','Siva Gamy','Nur Fazieraa Binti Jaafar','Siti Najiha Binti Mohd Razali','Femmy CL','NIVARSHINI','Muhammad Safuan Bin Ahmad Jalaludin','IHSAN ISMAIL','Nurul Ain','Rasnih Nuil','Nur Atiqah Binti Manan','Siti H','nur afrina','yap lee Kei','ROSSHEILAWANI BT MOHD RAZALI','Hanna Hii','noraini binti mohd zaidi','Geetha Nair Sundaram','darshini darshu','Dashinipriya','nor athirah bt ibrahim @ azizi','Mohd Fadeli','Hasmizah Khalid','Ruzan Jehan Mohd Ayob','Khoo Er Teng','Amin Nazir','liew choon cheuan','liew choon cheuan','noor azira','Shalini Karinalili','Shalini Karinalili','Ling Hui Jin','Ling Hui Jin','Maginei Misaa','Thong Ying Hoong','Tilasini Jagathesan','Tilasini Jagathesan','Nur Izzati','Nur Izzati','crystal Lau','Lee Chi Yi','Dan Qing Khaw','Stella Tiong','victoria anak iyip','Goh','Saran Dorai','Sheba Solomi Moses Vejaya Kumar','Sheba Solomi Moses Vejaya Kumar','Nur Syahidah binti Mohaidi','Geetha Nair Sundaram','Lilian Lim','Nurmeymeng zalia','Nurul Hidayah Roslan','Peai Hui','irene smilewan','Anjum Anwar','jimah','jimah','jimah','jimah','jimah','Saran Dorai','Nurul Syahirah','Hoexinjing','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Ateng Roslan','Aylvin Wong','JANESSA anakTERANG','Nurul Nadhirah Binti Hamzah','Liew yue xuan','Soo Jin Gui','Rubini Maniam','Fatin Athira','fairuza munirah bt mazlan','syarmimi rima yolanda','fathi yahya','fathi yahya','Charles97','Alissa Shamsudin','ERNIE DUSILY','Fatin Najihah Abdullah','Fatin Najihah Abdullah','Lilian Lim','Nor Nadzirah Bt Shaari','Haniff Zakwan','Ooi Man Thing','Palanikumar Kamaraj','Zasmin Aisha Binti Naumul','Lim Siow Yin','Shi Ring','Lim Siow Yin','Lim Siow Yin','Sharifah Hazirah Binti Syed Ahmad','Yap Tai Loong','Thong Ying Hoong','Kaiting Lim','rafidah','rafidah','rafidah','Siva Gamy','Siva Gamy','Goh Quo Yee','Nurul Akmal Fatihah bt Abd Hadi','Ana Razaly','Yap Tai Loong','liyana binti abdullah','Amin Nazir','Siti Najiha Binti Mohd Razali','marlia syuhada','Mohamad Jafni','Amila Solihan','Amila Solihan','Deanna Chua Li Ann','farah hanis','Aimi Nabila','Hammsavaally Ganesan','Siti Hajiah Binti Rani','Monica Bandi','Fazleen Izwana Masrom','Mohd Firdaus Bin Ibrahim','Nurul Ain','mohamad humam bin mohamad isa','casterchu','casterchu','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','Altwkzh Wardah','Ros anis farhanah','noraini binti mohd zaidi','Nur Hidayah Bt Ahmad Faizal','wong mei yee','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Eline Tie','aidy md dzahir','aidy md dzahir','Mohd Firdaus Bin Ibrahim','Mohd Khairulamirin','Nur Fazieraa Binti Jaafar','nooradira noordin','Siti Nadia Binti Sapari','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Muhammad Izzat','alice elizabeth','Nursyamimi binti Mazri','mohamad humam bin mohamad isa','Amin Nazir','nur aena','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Syaziana Binti Ali Kabar','Alissa Shamsudin','nurfarahanim','irene smilewan','hijrah md isa','hijrah md isa','Noor Syafiqah','ROZANA BINTI SAHRI','Anne Felicia Paul','Muhamad Hasri Shafee','Nurhafizah Mat Nafi','noraini binti mohd zaidi','Nurmeymeng zalia','Amown Daebak Sieyrien','Hemaa Abby','maisarah','Zulaikha Mohd Taib','Daranica','Wong Pei Ti','yap lee Kei','Tharshini Muthusamy');

  $days_date = array('29-1-2020 03:11:26 PM','29-1-2020 03:09:08 PM','28-1-2020 03:22:21 PM','28-1-2020 03:21:47 PM','28-1-2020 01:52:40 PM','28-1-2020 09:38:25 AM','27-1-2020 10:41:20 PM','27-1-2020 10:41:12 PM','27-1-2020 10:40:28 PM','27-1-2020 10:40:21 PM','27-1-2020 10:13:57 PM','27-1-2020 10:11:25 PM','27-1-2020 10:10:49 PM','27-1-2020 01:41:48 PM','26-1-2020 11:57:46 PM','26-1-2020 09:30:36 PM','26-1-2020 09:18:26 PM','26-1-2020 03:42:08 PM','25-1-2020 07:34:43 PM','24-1-2020 11:07:14 PM','24-1-2020 08:29:58 PM','24-1-2020 07:22:27 PM','24-1-2020 07:19:34 PM','24-1-2020 05:16:47 PM','24-1-2020 04:34:03 PM','24-1-2020 12:05:27 PM','24-1-2020 09:55:01 AM','24-1-2020 09:37:16 AM','24-1-2020 09:33:34 AM','24-1-2020 08:20:26 AM','24-1-2020 08:20:20 AM','24-1-2020 08:20:14 AM','24-1-2020 08:17:17 AM','24-1-2020 02:55:33 AM','23-1-2020 10:52:10 PM','23-1-2020 10:48:19 PM','23-1-2020 10:47:29 PM','23-1-2020 10:47:20 PM','23-1-2020 10:46:02 PM','23-1-2020 10:43:56 PM','23-1-2020 10:43:47 PM','23-1-2020 09:11:29 PM','23-1-2020 09:11:05 PM','23-1-2020 07:39:09 PM','23-1-2020 07:35:07 PM','23-1-2020 07:33:34 PM','23-1-2020 07:33:29 PM','23-1-2020 07:33:21 PM','23-1-2020 06:38:43 PM','23-1-2020 04:21:08 PM','23-1-2020 04:20:53 PM','23-1-2020 03:18:36 PM','23-1-2020 03:17:53 PM','23-1-2020 12:21:34 PM','23-1-2020 09:52:51 AM','22-1-2020 03:58:36 PM','22-1-2020 02:20:41 PM','22-1-2020 01:18:26 PM','22-1-2020 12:39:53 PM','22-1-2020 12:30:29 PM','22-1-2020 12:30:18 PM','22-1-2020 10:47:57 AM','22-1-2020 10:47:44 AM','22-1-2020 10:37:25 AM','22-1-2020 10:14:22 AM','22-1-2020 10:13:38 AM','22-1-2020 10:10:28 AM','22-1-2020 09:17:30 AM','22-1-2020 04:44:57 AM','22-1-2020 04:37:37 AM','21-1-2020 11:11:25 PM','21-1-2020 11:07:11 PM','21-1-2020 10:25:44 PM','21-1-2020 09:39:05 PM','21-1-2020 07:50:14 PM','21-1-2020 07:32:40 PM','21-1-2020 06:54:17 PM','21-1-2020 06:48:01 PM','21-1-2020 06:32:36 PM','21-1-2020 05:54:33 PM','21-1-2020 05:36:37 PM','21-1-2020 03:13:12 PM','20-1-2020 04:21:47 PM','19-1-2020 07:16:06 PM','18-1-2020 07:35:20 PM','18-1-2020 11:46:49 AM','16-1-2020 12:32:29 PM','16-1-2020 10:22:50 AM','15-1-2020 03:02:48 PM','15-1-2020 09:36:00 AM','14-1-2020 08:23:22 PM','14-1-2020 04:47:29 PM','14-1-2020 02:40:37 PM','14-1-2020 02:20:46 PM','14-1-2020 02:15:57 PM','14-1-2020 09:20:56 AM','14-1-2020 07:54:40 AM','14-1-2020 07:26:51 AM','13-1-2020 08:37:48 PM','13-1-2020 12:34:04 AM','12-1-2020 10:17:34 PM','11-1-2020 08:14:43 PM','11-1-2020 08:13:38 PM','11-1-2020 07:25:21 PM','11-1-2020 06:43:58 PM','11-1-2020 06:43:56 PM','10-1-2020 11:57:40 PM','10-1-2020 11:55:55 PM','10-1-2020 11:49:13 PM','10-1-2020 09:49:37 PM','10-1-2020 07:24:42 PM','10-1-2020 07:19:00 PM','10-1-2020 10:48:49 AM','10-1-2020 10:47:56 AM','9-1-2020 11:16:44 PM','9-1-2020 10:14:16 PM','9-1-2020 10:11:40 PM','9-1-2020 07:46:32 PM','9-1-2020 06:14:18 PM','9-1-2020 12:44:01 PM','9-1-2020 10:59:14 AM','9-1-2020 10:56:34 AM','9-1-2020 10:54:33 AM','9-1-2020 10:13:44 AM','9-1-2020 09:28:50 AM','9-1-2020 08:24:11 AM','8-1-2020 07:05:51 PM','8-1-2020 09:32:02 AM','6-1-2020 12:05:29 PM','5-1-2020 11:37:45 PM','5-1-2020 03:01:35 PM','5-1-2020 01:49:52 PM','5-1-2020 01:42:41 PM','5-1-2020 01:38:20 PM','5-1-2020 01:35:09 PM','5-1-2020 01:34:14 PM','5-1-2020 01:23:56 PM','4-1-2020 10:35:40 PM','4-1-2020 10:47:57 AM','4-1-2020 06:28:26 AM','4-1-2020 06:26:04 AM','4-1-2020 12:58:41 AM','3-1-2020 06:25:31 PM','3-1-2020 04:21:54 PM','3-1-2020 02:51:01 PM','3-1-2020 09:40:45 AM','2-1-2020 10:41:28 PM','2-1-2020 06:32:43 PM','2-1-2020 04:39:17 PM','2-1-2020 04:38:30 PM','2-1-2020 04:24:29 PM','2-1-2020 04:18:20 PM','2-1-2020 04:15:36 PM','2-1-2020 03:57:02 PM','2-1-2020 03:21:34 PM','2-1-2020 12:47:11 PM','2-1-2020 11:46:51 AM','2-1-2020 11:43:22 AM','2-1-2020 10:58:21 AM','2-1-2020 03:31:42 AM','1-1-2020 09:50:03 PM','1-1-2020 03:22:54 PM','31-12-2019 05:49:00 PM','31-12-2019 04:12:15 PM','31-12-2019 03:49:52 AM','30-12-2019 10:23:02 PM','30-12-2019 08:17:26 PM','30-12-2019 08:15:53 PM','30-12-2019 04:38:05 PM','30-12-2019 02:57:44 PM','30-12-2019 02:26:50 PM','30-12-2019 09:18:45 AM','30-12-2019 08:02:07 AM','30-12-2019 07:59:10 AM','30-12-2019 07:57:51 AM','29-12-2019 10:39:40 PM','29-12-2019 10:32:40 PM','29-12-2019 07:01:10 PM','29-12-2019 04:36:24 PM','29-12-2019 07:47:52 AM','29-12-2019 06:09:40 AM','29-12-2019 01:19:47 AM','28-12-2019 08:11:55 PM','28-12-2019 04:46:19 PM','28-12-2019 03:13:49 PM','28-12-2019 06:11:26 AM','27-12-2019 01:26:30 PM','27-12-2019 01:21:45 PM','27-12-2019 08:22:31 AM','26-12-2019 11:21:33 PM','26-12-2019 07:39:02 PM','26-12-2019 05:37:16 PM','26-12-2019 05:34:42 PM','26-12-2019 02:48:38 PM','26-12-2019 12:22:32 PM','26-12-2019 08:47:25 AM','26-12-2019 08:22:32 AM','26-12-2019 07:32:40 AM','25-12-2019 11:25:50 PM','25-12-2019 11:22:39 PM','25-12-2019 07:12:45 PM','25-12-2019 04:45:09 PM','25-12-2019 12:02:35 PM','24-12-2019 07:28:29 PM','24-12-2019 04:39:46 PM','24-12-2019 03:54:56 PM','24-12-2019 11:41:06 AM','24-12-2019 11:38:56 AM','24-12-2019 10:57:30 AM','24-12-2019 08:40:35 AM','24-12-2019 08:36:30 AM','24-12-2019 08:08:33 AM','24-12-2019 05:38:09 AM','23-12-2019 10:57:41 PM','23-12-2019 10:31:20 PM','23-12-2019 10:31:08 PM','23-12-2019 02:26:44 PM','23-12-2019 02:23:14 PM','23-12-2019 02:17:15 PM','23-12-2019 09:33:55 AM','23-12-2019 08:51:59 AM','23-12-2019 08:42:10 AM','23-12-2019 01:01:24 AM','22-12-2019 10:43:30 PM','22-12-2019 11:19:55 AM','22-12-2019 11:17:44 AM','22-12-2019 11:14:37 AM','22-12-2019 08:57:21 AM','22-12-2019 12:50:39 AM','21-12-2019 11:26:46 PM','21-12-2019 11:26:13 PM','21-12-2019 09:14:30 PM','21-12-2019 09:13:04 PM','21-12-2019 06:26:01 PM','21-12-2019 06:05:13 PM','21-12-2019 11:01:14 AM','21-12-2019 07:54:14 AM','21-12-2019 01:22:45 AM','21-12-2019 12:45:25 AM','20-12-2019 11:41:35 PM','20-12-2019 11:20:50 PM','20-12-2019 10:38:45 PM','20-12-2019 07:43:19 PM','20-12-2019 06:52:14 PM','20-12-2019 05:44:40 PM','19-12-2019 10:38:31 AM','18-12-2019 10:50:22 PM','18-12-2019 04:43:51 PM');

  $days_amount = array('20.00','40.00','30.00','30.00','9.00','20.00','10.00','10.00','5.46','5.46','30.00','30.00','30.00','40.00','33.50','134.00','8.00','52.06','55.00','30.00','50.00','17.00','6.00','10.00','6.00','2.00','20.00','6.70','9.70','20.00','20.00','20.00','20.00','35.00','20.00','20.00','45.00','45.00','45.00','15.99','15.99','11.60','11.60','20.00','11.58','11.58','11.58','11.58','50.00','146.94','146.94','30.00','30.00','10.00','30.00','13.30','42.51','36.49','44.00','44.00','44.00','5.00','5.00','10.00','30.00','30.00','20.00','6.50','45.00','45.00','50.00','25.00','17.39','84.00','60.00','25.00','9.75','21.00','6.00','50.00','22.00','10.00','100.00','30.00','30.00','3.47','20.00','6.00','5.00','8.00','110.00','12.95','150.00','20.00','20.00','100.00','10.00','5.00','71.67','35.00','6.00','59.07','59.07','1.00','10.00','10.00','30.00','30.00','150.00','10.00','22.71','22.71','20.00','20.00','10.00','30.00','20.00','22.00','8.00','10.00','30.00','30.00','200.00','20.00','150.00','5.00','20.00','17.00','10.00','36.95','25.00','35.00','36.00','36.00','36.50','36.50','30.00','6.48','81.75','17.00','17.00','35.82','200.00','10.00','12.00','10.00','82.00','16.76','50.00','25.00','120.00','10.00','20.00','17.27','10.00','15.00','50.00','50.00','3.00','17.00','100.00','7.85','20.00','9.43','50.00','50.00','50.00','50.00','15.00','20.00','10.00','200.00','10.00','35.00','15.00','39.25','39.25','100.00','8.00','30.00','25.00','4.70','5.00','6.00','58.00','200.00','7.00','10.00','30.00','40.00','10.00','30.00','8.00','7.00','10.00','14.70','30.00','25.00','14.00','14.00','30.00','50.00','60.00','20.00','5.27','20.00','10.00','10.00','50.00','100.00','200.00','14.30','35.00','20.00','30.00','50.00','10.00','9.00','12.43','157.61','50.00','200.00','3.00','20.00','2.10','2.10','3.70','60.00','11.00','50.00','7.55','50.00','50.00','100.00','153.15','64.34','27.00','21.95','10.90','100.00','200.00','30.00','4.20','50.00','30.00','23.59','5.00','37.00');

  $days_model_arr = array();
  for($x = 0; $x< count($days_date) ; $x ++){

    $temp = array('trans_id'=>$days_trans_id[$x],'reference_no'=> $days_reference_no[$x], 'document_date' => $days_date[$x], 'amount' => $days_amount[$x], 'name' => $days_user_name[$x], 'status' => $days_status[$x] ,'is_success' => $days_status[$x] == 'Success' ? 1 : 0 , 'date' =>
      date('Y-m-d', strtotime($days_date[$x])), 'created_at' => date('Y-m-d h', strtotime($days_date[$x])));
    array_push($days_model_arr,$temp);
  }

  ini_set('max_execution_time', 300000);
  $leaf_api  = new LeafAPI();
  $uTransactionModel = UTransaction::all();
  $result_listing = array();



  foreach ($uTransactionModel as $model) {
    
    foreach($days_model_arr as $temp){
      if($temp['amount'] == $model['amount']  && $temp['date'] == $model['document_date'] && $temp['created_at'] ==  date('Y-m-d h', strtotime($model['created_at'])))
      {
        $model['trans_id'] = $temp['trans_id'];
        $model['real_result'] = $temp['status'];
        $model['is_success'] = $temp['status'];
        $model['model_name'] = $temp['name'];
        $model['reference_no'] = $temp['reference_no'];
      }
    }
    

    $result = $leaf_api->get_check_payment($model['leaf_payment_id']);

    if($model['reference_no'] != $result['payment_reference']){
      continue  ;
    }

    $meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
    $model['ie_is_paid']    = $model['is_paid'] ;
    $model['is_paid']   = $result['payment_paid'] ;
    $model['payment_customer_name'] = $result['payment_customer_name'];
    $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
    $model['query_payment_rereference'] = $result['payment_reference'];
 
    //if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
    //dd($meter_payment_model);

    if($result['payment_paid'] == false){
      if($model['is_paid'] == true){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [wrong ie null model]';
          //array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'revert_item [wrong ie remove model]';
          //array_push($result_listing,$model);
        }
        
      }else{
        if(isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [null ie remove model]';
          //array_push($result_listing,$model);
        }
      }

      
    }

    if($result['payment_paid'] == true){
      if($model['is_paid'] == false){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [null ie null model]';
          //array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'non_capture [wrong ie with model]';
          //array_push($result_listing,$model);
        }
        
      }else{
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [true ie null model]';
          //array_push($result_listing,$model);
        }
        
      }

      
    }


    if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
      
        $model['result_type'] = 'success payment - wrong ie - no model';
        

      if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
        //ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
      }
    }

    array_push($result_listing,$model);
}



  echo '<table>';
  echo '<tr>
        <th width="50px;">Utransaction ID</th>
          <th width="50px;">Leaf reference</th>
            <th width="50px;">Ipay Reference</th>
        <th width="50px;">Payment number</th>
          <th width="50px;">Document number</th>
        <th width="50px;">Model Name</th>
          <th width="50px;">Name</th>
          <th width="50px;">Utransaction ID</th>
          <th width="50px;">Payment Amount</th>
          <th width="50px;">Created At</th>      
         <th width="50px;">Sunmed Ipay88 Status</th>
         <th width="50px;">Sunmed Ipay88 Result</th>
         <th width="50px;">requery Status</th>
          <th width="50px;">Ie Status</th>
          <th width="50px;">Is with Model</th>
          <th width="50px;">Status</th>
     </tr>';

  
  usort($result_listing, 'App\Setting::compare_by_created_at');
  usort($result_listing, 'App\Setting::compare_by_column');
  usort($result_listing, 'App\Setting::compare_by_column');

  $to_display = array();
  foreach($result_listing as $result){
    if(!isset($result['real_result'])){continue;}
    array_push($to_display,$result);
  }

  $final_listing = array();
  foreach($to_display as $display){
    foreach($to_display as $check){
      if($display['trans_id'] == $check['trans_id']){
        array_push($final_listing,$check);
      }
    }
  }


  foreach($final_listing as $result)
  { 
    if(!isset($result['real_result'])){continue;}
    echo '<tr>
        <td>'.$result['id'].'</td>
        <td>'.$result['query_payment_rereference'].'</td>
        <td>'.$result['reference_no'].'</td>
        <td>'.$result['leaf_payment_id'].'</td>
          <td>'.$result['document_no'].'</td>
        <td>'.$result['model_name'].'</td>
          <td>'.$result['payment_customer_name'].'</td>
          <td>'.$result['trans_id'].'</td>
          <td>'.$result['amount'].'</td>
          <td>'.$result['created_at'].'</td>
          <td>'.$result['real_result'].'</td>
          <td>'.$result['is_success'].'</td>
                <td>'.$result['is_paid'].'</td>
          <td>'.$result['ie_is_paid'].'</td>
          <td>'.$result['is_payment_model_created'].'</td>
          <td>'.$result['result_type'].'</td>
     </tr>';
    //echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
  }
  echo '</table>';

  echo 'Total '.count($result_listing).' records.';
  dd('End');


  });


Route::get('Ipay88PaymentCheckCrossCheck', function ()
{
  $days_status = array('Success','Success','Success','Customer Drop the Transaction','Success','Success','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Success','Success','Fail','Fail','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Success','Success','Success','Success','Customer Drop the Transaction','Fail','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Customer Drop the Transaction','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Customer Drop the Transaction','Success','Success','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Fail','Fail','Fail','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Fail','Success','Success','Success','Success','Fail','Success','Fail','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success');
    
  $days_user_name = array('Dashinipriya','darshini a/ kalimuthu','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Siti Najiha Binti Mohd Razali','Alissa Shamsudin','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Nurul Faezah binti Badri','Nurul Hidayah Roslan','Nornazifah binti Ahmad Sapri','Siti Najiha Binti Mohd Razali','Nurmeymeng zalia','Nur Shakirah binti Kamal Ariffin','Nurul Akmal Fatihah bt Abd Hadi','Yap Tai Loong','Gan ChinTeng','Gan ChinTeng','syafiqhairunazmi','Gan ChinTeng','Sheba Solomi Moses Vejaya Kumar','Dhashini Devi A/p Sinniah','ainun shahria','ainun shahria','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Mohd Amsyar Bin Bacho','ERNIE DUSILY','Vijy Balan','farah hanis','farah hanis','farah hanis','Nur Fazira Binti Jusoh','Nur Fazira Binti Jusoh','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Faiz Asni','Faiz Asni','Faiz Asni','Faiz Asni','Ng Jing Tien','ainin azyyati','ainin azyyati','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Elizabeth Elly','wenqi','Shaheen Saleam','Jiesee Yong','NURUL SYAFIKA','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Hasmizah Khalid','Hasmizah Khalid','Deepaah Subramamian','Choo Yuen Seng','Choo Yuen Seng','Choo Yuen Seng','liyana binti abdullah','Nur Shakirah binti Kamal Ariffin','Nur Shakirah binti Kamal Ariffin','hijrah md isa','Remorn anak Jipong','Nur Atiqah Binti Manan','Mohammad Amirul Ashraf','nurfarahanim','Zulaikha Mohd Taib','Siva Gamy','Nur Fazieraa Binti Jaafar','Siti Najiha Binti Mohd Razali','Femmy CL','NIVARSHINI','Muhammad Safuan Bin Ahmad Jalaludin','IHSAN ISMAIL','Nurul Ain','Rasnih Nuil','Nur Atiqah Binti Manan','Siti H','nur afrina','yap lee Kei','ROSSHEILAWANI BT MOHD RAZALI','Hanna Hii','noraini binti mohd zaidi','Geetha Nair Sundaram','darshini darshu','Dashinipriya','nor athirah bt ibrahim @ azizi','Mohd Fadeli','Hasmizah Khalid','Ruzan Jehan Mohd Ayob','Khoo Er Teng','Amin Nazir','liew choon cheuan','liew choon cheuan','noor azira','Shalini Karinalili','Shalini Karinalili','Ling Hui Jin','Ling Hui Jin','Maginei Misaa','Thong Ying Hoong','Tilasini Jagathesan','Tilasini Jagathesan','Nur Izzati','Nur Izzati','crystal Lau','Lee Chi Yi','Dan Qing Khaw','Stella Tiong','victoria anak iyip','Goh','Saran Dorai','Sheba Solomi Moses Vejaya Kumar','Sheba Solomi Moses Vejaya Kumar','Nur Syahidah binti Mohaidi','Geetha Nair Sundaram','Lilian Lim','Nurmeymeng zalia','Nurul Hidayah Roslan','Peai Hui','irene smilewan','Anjum Anwar','jimah','jimah','jimah','jimah','jimah','Saran Dorai','Nurul Syahirah','Hoexinjing','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Ateng Roslan','Aylvin Wong','JANESSA anakTERANG','Nurul Nadhirah Binti Hamzah','Liew yue xuan','Soo Jin Gui','Rubini Maniam','Fatin Athira','fairuza munirah bt mazlan','syarmimi rima yolanda','fathi yahya','fathi yahya','Charles97','Alissa Shamsudin','ERNIE DUSILY','Fatin Najihah Abdullah','Fatin Najihah Abdullah','Lilian Lim','Nor Nadzirah Bt Shaari','Haniff Zakwan','Ooi Man Thing','Palanikumar Kamaraj','Zasmin Aisha Binti Naumul','Lim Siow Yin','Shi Ring','Lim Siow Yin','Lim Siow Yin','Sharifah Hazirah Binti Syed Ahmad','Yap Tai Loong','Thong Ying Hoong','Kaiting Lim','rafidah','rafidah','rafidah','Siva Gamy','Siva Gamy','Goh Quo Yee','Nurul Akmal Fatihah bt Abd Hadi','Ana Razaly','Yap Tai Loong','liyana binti abdullah','Amin Nazir','Siti Najiha Binti Mohd Razali','marlia syuhada','Mohamad Jafni','Amila Solihan','Amila Solihan','Deanna Chua Li Ann','farah hanis','Aimi Nabila','Hammsavaally Ganesan','Siti Hajiah Binti Rani','Monica Bandi','Fazleen Izwana Masrom','Mohd Firdaus Bin Ibrahim','Nurul Ain','mohamad humam bin mohamad isa','casterchu','casterchu','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','Altwkzh Wardah','Ros anis farhanah','noraini binti mohd zaidi','Nur Hidayah Bt Ahmad Faizal','wong mei yee','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Eline Tie','aidy md dzahir','aidy md dzahir','Mohd Firdaus Bin Ibrahim','Mohd Khairulamirin','Nur Fazieraa Binti Jaafar','nooradira noordin','Siti Nadia Binti Sapari','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Muhammad Izzat','alice elizabeth','Nursyamimi binti Mazri','mohamad humam bin mohamad isa','Amin Nazir','nur aena','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Syaziana Binti Ali Kabar','Alissa Shamsudin','nurfarahanim','irene smilewan','hijrah md isa','hijrah md isa','Noor Syafiqah','ROZANA BINTI SAHRI','Anne Felicia Paul','Muhamad Hasri Shafee','Nurhafizah Mat Nafi','noraini binti mohd zaidi','Nurmeymeng zalia','Amown Daebak Sieyrien','Hemaa Abby','maisarah','Zulaikha Mohd Taib','Daranica','Wong Pei Ti','yap lee Kei','Tharshini Muthusamy');

  $days_date = array('29-1-2020 03:11:26 PM','29-1-2020 03:09:08 PM','28-1-2020 03:22:21 PM','28-1-2020 03:21:47 PM','28-1-2020 01:52:40 PM','28-1-2020 09:38:25 AM','27-1-2020 10:41:20 PM','27-1-2020 10:41:12 PM','27-1-2020 10:40:28 PM','27-1-2020 10:40:21 PM','27-1-2020 10:13:57 PM','27-1-2020 10:11:25 PM','27-1-2020 10:10:49 PM','27-1-2020 01:41:48 PM','26-1-2020 11:57:46 PM','26-1-2020 09:30:36 PM','26-1-2020 09:18:26 PM','26-1-2020 03:42:08 PM','25-1-2020 07:34:43 PM','24-1-2020 11:07:14 PM','24-1-2020 08:29:58 PM','24-1-2020 07:22:27 PM','24-1-2020 07:19:34 PM','24-1-2020 05:16:47 PM','24-1-2020 04:34:03 PM','24-1-2020 12:05:27 PM','24-1-2020 09:55:01 AM','24-1-2020 09:37:16 AM','24-1-2020 09:33:34 AM','24-1-2020 08:20:26 AM','24-1-2020 08:20:20 AM','24-1-2020 08:20:14 AM','24-1-2020 08:17:17 AM','24-1-2020 02:55:33 AM','23-1-2020 10:52:10 PM','23-1-2020 10:48:19 PM','23-1-2020 10:47:29 PM','23-1-2020 10:47:20 PM','23-1-2020 10:46:02 PM','23-1-2020 10:43:56 PM','23-1-2020 10:43:47 PM','23-1-2020 09:11:29 PM','23-1-2020 09:11:05 PM','23-1-2020 07:39:09 PM','23-1-2020 07:35:07 PM','23-1-2020 07:33:34 PM','23-1-2020 07:33:29 PM','23-1-2020 07:33:21 PM','23-1-2020 06:38:43 PM','23-1-2020 04:21:08 PM','23-1-2020 04:20:53 PM','23-1-2020 03:18:36 PM','23-1-2020 03:17:53 PM','23-1-2020 12:21:34 PM','23-1-2020 09:52:51 AM','22-1-2020 03:58:36 PM','22-1-2020 02:20:41 PM','22-1-2020 01:18:26 PM','22-1-2020 12:39:53 PM','22-1-2020 12:30:29 PM','22-1-2020 12:30:18 PM','22-1-2020 10:47:57 AM','22-1-2020 10:47:44 AM','22-1-2020 10:37:25 AM','22-1-2020 10:14:22 AM','22-1-2020 10:13:38 AM','22-1-2020 10:10:28 AM','22-1-2020 09:17:30 AM','22-1-2020 04:44:57 AM','22-1-2020 04:37:37 AM','21-1-2020 11:11:25 PM','21-1-2020 11:07:11 PM','21-1-2020 10:25:44 PM','21-1-2020 09:39:05 PM','21-1-2020 07:50:14 PM','21-1-2020 07:32:40 PM','21-1-2020 06:54:17 PM','21-1-2020 06:48:01 PM','21-1-2020 06:32:36 PM','21-1-2020 05:54:33 PM','21-1-2020 05:36:37 PM','21-1-2020 03:13:12 PM','20-1-2020 04:21:47 PM','19-1-2020 07:16:06 PM','18-1-2020 07:35:20 PM','18-1-2020 11:46:49 AM','16-1-2020 12:32:29 PM','16-1-2020 10:22:50 AM','15-1-2020 03:02:48 PM','15-1-2020 09:36:00 AM','14-1-2020 08:23:22 PM','14-1-2020 04:47:29 PM','14-1-2020 02:40:37 PM','14-1-2020 02:20:46 PM','14-1-2020 02:15:57 PM','14-1-2020 09:20:56 AM','14-1-2020 07:54:40 AM','14-1-2020 07:26:51 AM','13-1-2020 08:37:48 PM','13-1-2020 12:34:04 AM','12-1-2020 10:17:34 PM','11-1-2020 08:14:43 PM','11-1-2020 08:13:38 PM','11-1-2020 07:25:21 PM','11-1-2020 06:43:58 PM','11-1-2020 06:43:56 PM','10-1-2020 11:57:40 PM','10-1-2020 11:55:55 PM','10-1-2020 11:49:13 PM','10-1-2020 09:49:37 PM','10-1-2020 07:24:42 PM','10-1-2020 07:19:00 PM','10-1-2020 10:48:49 AM','10-1-2020 10:47:56 AM','9-1-2020 11:16:44 PM','9-1-2020 10:14:16 PM','9-1-2020 10:11:40 PM','9-1-2020 07:46:32 PM','9-1-2020 06:14:18 PM','9-1-2020 12:44:01 PM','9-1-2020 10:59:14 AM','9-1-2020 10:56:34 AM','9-1-2020 10:54:33 AM','9-1-2020 10:13:44 AM','9-1-2020 09:28:50 AM','9-1-2020 08:24:11 AM','8-1-2020 07:05:51 PM','8-1-2020 09:32:02 AM','6-1-2020 12:05:29 PM','5-1-2020 11:37:45 PM','5-1-2020 03:01:35 PM','5-1-2020 01:49:52 PM','5-1-2020 01:42:41 PM','5-1-2020 01:38:20 PM','5-1-2020 01:35:09 PM','5-1-2020 01:34:14 PM','5-1-2020 01:23:56 PM','4-1-2020 10:35:40 PM','4-1-2020 10:47:57 AM','4-1-2020 06:28:26 AM','4-1-2020 06:26:04 AM','4-1-2020 12:58:41 AM','3-1-2020 06:25:31 PM','3-1-2020 04:21:54 PM','3-1-2020 02:51:01 PM','3-1-2020 09:40:45 AM','2-1-2020 10:41:28 PM','2-1-2020 06:32:43 PM','2-1-2020 04:39:17 PM','2-1-2020 04:38:30 PM','2-1-2020 04:24:29 PM','2-1-2020 04:18:20 PM','2-1-2020 04:15:36 PM','2-1-2020 03:57:02 PM','2-1-2020 03:21:34 PM','2-1-2020 12:47:11 PM','2-1-2020 11:46:51 AM','2-1-2020 11:43:22 AM','2-1-2020 10:58:21 AM','2-1-2020 03:31:42 AM','1-1-2020 09:50:03 PM','1-1-2020 03:22:54 PM','31-12-2019 05:49:00 PM','31-12-2019 04:12:15 PM','31-12-2019 03:49:52 AM','30-12-2019 10:23:02 PM','30-12-2019 08:17:26 PM','30-12-2019 08:15:53 PM','30-12-2019 04:38:05 PM','30-12-2019 02:57:44 PM','30-12-2019 02:26:50 PM','30-12-2019 09:18:45 AM','30-12-2019 08:02:07 AM','30-12-2019 07:59:10 AM','30-12-2019 07:57:51 AM','29-12-2019 10:39:40 PM','29-12-2019 10:32:40 PM','29-12-2019 07:01:10 PM','29-12-2019 04:36:24 PM','29-12-2019 07:47:52 AM','29-12-2019 06:09:40 AM','29-12-2019 01:19:47 AM','28-12-2019 08:11:55 PM','28-12-2019 04:46:19 PM','28-12-2019 03:13:49 PM','28-12-2019 06:11:26 AM','27-12-2019 01:26:30 PM','27-12-2019 01:21:45 PM','27-12-2019 08:22:31 AM','26-12-2019 11:21:33 PM','26-12-2019 07:39:02 PM','26-12-2019 05:37:16 PM','26-12-2019 05:34:42 PM','26-12-2019 02:48:38 PM','26-12-2019 12:22:32 PM','26-12-2019 08:47:25 AM','26-12-2019 08:22:32 AM','26-12-2019 07:32:40 AM','25-12-2019 11:25:50 PM','25-12-2019 11:22:39 PM','25-12-2019 07:12:45 PM','25-12-2019 04:45:09 PM','25-12-2019 12:02:35 PM','24-12-2019 07:28:29 PM','24-12-2019 04:39:46 PM','24-12-2019 03:54:56 PM','24-12-2019 11:41:06 AM','24-12-2019 11:38:56 AM','24-12-2019 10:57:30 AM','24-12-2019 08:40:35 AM','24-12-2019 08:36:30 AM','24-12-2019 08:08:33 AM','24-12-2019 05:38:09 AM','23-12-2019 10:57:41 PM','23-12-2019 10:31:20 PM','23-12-2019 10:31:08 PM','23-12-2019 02:26:44 PM','23-12-2019 02:23:14 PM','23-12-2019 02:17:15 PM','23-12-2019 09:33:55 AM','23-12-2019 08:51:59 AM','23-12-2019 08:42:10 AM','23-12-2019 01:01:24 AM','22-12-2019 10:43:30 PM','22-12-2019 11:19:55 AM','22-12-2019 11:17:44 AM','22-12-2019 11:14:37 AM','22-12-2019 08:57:21 AM','22-12-2019 12:50:39 AM','21-12-2019 11:26:46 PM','21-12-2019 11:26:13 PM','21-12-2019 09:14:30 PM','21-12-2019 09:13:04 PM','21-12-2019 06:26:01 PM','21-12-2019 06:05:13 PM','21-12-2019 11:01:14 AM','21-12-2019 07:54:14 AM','21-12-2019 01:22:45 AM','21-12-2019 12:45:25 AM','20-12-2019 11:41:35 PM','20-12-2019 11:20:50 PM','20-12-2019 10:38:45 PM','20-12-2019 07:43:19 PM','20-12-2019 06:52:14 PM','20-12-2019 05:44:40 PM','19-12-2019 10:38:31 AM','18-12-2019 10:50:22 PM','18-12-2019 04:43:51 PM');

  $days_amount = array('20.00','40.00','30.00','30.00','9.00','20.00','10.00','10.00','5.46','5.46','30.00','30.00','30.00','40.00','33.50','134.00','8.00','52.06','55.00','30.00','50.00','17.00','6.00','10.00','6.00','2.00','20.00','6.70','9.70','20.00','20.00','20.00','20.00','35.00','20.00','20.00','45.00','45.00','45.00','15.99','15.99','11.60','11.60','20.00','11.58','11.58','11.58','11.58','50.00','146.94','146.94','30.00','30.00','10.00','30.00','13.30','42.51','36.49','44.00','44.00','44.00','5.00','5.00','10.00','30.00','30.00','20.00','6.50','45.00','45.00','50.00','25.00','17.39','84.00','60.00','25.00','9.75','21.00','6.00','50.00','22.00','10.00','100.00','30.00','30.00','3.47','20.00','6.00','5.00','8.00','110.00','12.95','150.00','20.00','20.00','100.00','10.00','5.00','71.67','35.00','6.00','59.07','59.07','1.00','10.00','10.00','30.00','30.00','150.00','10.00','22.71','22.71','20.00','20.00','10.00','30.00','20.00','22.00','8.00','10.00','30.00','30.00','200.00','20.00','150.00','5.00','20.00','17.00','10.00','36.95','25.00','35.00','36.00','36.00','36.50','36.50','30.00','6.48','81.75','17.00','17.00','35.82','200.00','10.00','12.00','10.00','82.00','16.76','50.00','25.00','120.00','10.00','20.00','17.27','10.00','15.00','50.00','50.00','3.00','17.00','100.00','7.85','20.00','9.43','50.00','50.00','50.00','50.00','15.00','20.00','10.00','200.00','10.00','35.00','15.00','39.25','39.25','100.00','8.00','30.00','25.00','4.70','5.00','6.00','58.00','200.00','7.00','10.00','30.00','40.00','10.00','30.00','8.00','7.00','10.00','14.70','30.00','25.00','14.00','14.00','30.00','50.00','60.00','20.00','5.27','20.00','10.00','10.00','50.00','100.00','200.00','14.30','35.00','20.00','30.00','50.00','10.00','9.00','12.43','157.61','50.00','200.00','3.00','20.00','2.10','2.10','3.70','60.00','11.00','50.00','7.55','50.00','50.00','100.00','153.15','64.34','27.00','21.95','10.90','100.00','200.00','30.00','4.20','50.00','30.00','23.59','5.00','37.00');

  $days_model_arr = array();
  for($x = 0; $x< count($days_date) ; $x ++){

    $temp = array( 'document_date' => $days_date[$x], 'amount' => $days_amount[$x], 'name' => $days_user_name[$x], 'status' => $days_status[$x] ,'is_success' => $days_status[$x] == 'Success' ? 1 : 0 , 'date' =>
      date('Y-m-d', strtotime($days_date[$x])));
    array_push($days_model_arr,$temp);
  }

  ini_set('max_execution_time', 300000);
  $leaf_api  = new LeafAPI();
  $uTransactionModel = UTransaction::all();
  $result_listing = array();



  foreach ($uTransactionModel as $model) {
    
    foreach($days_model_arr as $temp){
      
      if($temp['amount'] == $model['amount']  && $temp['date'] == $model['document_date'])
      {
        $model['real_result'] = $temp['status'];
        $model['is_success'] = $temp['status'];
        $model['model_name'] = $temp['name'];
      }
    }
    

    $result = $leaf_api->get_check_payment($model['leaf_payment_id']);
    $meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
    $model['ie_is_paid']    = $model['is_paid'] ;
    $model['is_paid']   = $result['payment_paid'] ;
    $model['payment_customer_name'] = $result['payment_customer_name'];
    $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;


    //if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
    //dd($meter_payment_model);

    if($result['payment_paid'] == false){
      if($model['is_paid'] == true){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [wrong ie null model]';
          array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'revert_item [wrong ie remove model]';
          array_push($result_listing,$model);
        }
        
      }else{
        if(isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [null ie remove model]';
          array_push($result_listing,$model);
        }
      }

      
    }

    if($result['payment_paid'] == true){
      if($model['is_paid'] == false){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [null ie null model]';
          array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'non_capture [wrong ie with model]';
          array_push($result_listing,$model);
        }
        
      }else{
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [true ie null model]';
          array_push($result_listing,$model);
        }
        
      }

      
    }


    if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
      
        $model['result_type'] = 'success payment - wrong ie - no model';
        array_push($result_listing,$model);

      if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
        //ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
      }
    }
}


  echo '<table>';
  echo '<tr>
        <th width="50px;">Utransaction ID</th>
        <th width="50px;">Payment number</th>
          <th width="50px;">Document number</th>
        <th width="50px;">Model Name</th>
          <th width="50px;">Name</th>
          <th width="50px;">Payment Amount</th>
          <th width="50px;">Created At</th>
        
         
         <th width="50px;">Sunmed Ipay88 Status</th>
         <th width="50px;">Sunmed Ipay88 Result</th>
          <th width="50px;">requery Status</th>
          <th width="50px;">Ie Status</th>
          <th width="50px;">Is with Model</th>
         
         
          <th width="50px;">Status</th>
     </tr>';

  
  usort($result_listing, 'App\Setting::compare_by_created_at');
  usort($result_listing, 'App\Setting::compare_by_column');
  foreach($result_listing as $result)
  { 
    if(!isset($result['real_result'])){
      continue;
    }
    echo '<tr>
        <td style="font-size:16px">'.$result['id'].'</th>
        <td style="font-size:16px">'.$result['leaf_payment_id'].'</th>
          <td style="font-size:16px">'.$result['document_no'].'</th>
        <td style="font-size:16px">'.$result['model_name'].'</th>
          <td style="font-size:16px">'.$result['payment_customer_name'].'</th>
          <td style="font-size:16px">'.$result['amount'].'</th>
          <td style="font-size:16px">'.$result['created_at'].'</th>
         
          <td style="font-size:16px">'.$result['real_result'].'</th>
          <td style="font-size:16px">'.$result['is_success'].'</th>
          <td style="font-size:16px">'.$result['is_paid'].'</th>
          <td style="font-size:16px">'.$result['ie_is_paid'].'</th>
           <td style="font-size:16px">'.$result['is_payment_model_created'].'</th>
          
          <td style="font-size:16px">'.$result['result_type'].'</th>
     </tr>';
    //echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
  }
  echo '</table>';

  echo 'Total '.count($result_listing).' records.';
  dd('End');


  });


Route::get('Ipay88PaymentCheckCrossCheck', function ()
{
  

  $days_status = array('Success','Success','Success','Customer Drop the Transaction','Success','Success','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Success','Success','Fail','Fail','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Success','Success','Success','Success','Customer Drop the Transaction','Fail','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Customer Drop the Transaction','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Customer Drop the Transaction','Success','Success','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Fail','Fail','Fail','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Fail','Success','Success','Success','Success','Fail','Success','Fail','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success');
    
  $days_user_name = array('Dashinipriya','darshini a/ kalimuthu','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Siti Najiha Binti Mohd Razali','Alissa Shamsudin','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Nurul Faezah binti Badri','Nurul Hidayah Roslan','Nornazifah binti Ahmad Sapri','Siti Najiha Binti Mohd Razali','Nurmeymeng zalia','Nur Shakirah binti Kamal Ariffin','Nurul Akmal Fatihah bt Abd Hadi','Yap Tai Loong','Gan ChinTeng','Gan ChinTeng','syafiqhairunazmi','Gan ChinTeng','Sheba Solomi Moses Vejaya Kumar','Dhashini Devi A/p Sinniah','ainun shahria','ainun shahria','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Mohd Amsyar Bin Bacho','ERNIE DUSILY','Vijy Balan','farah hanis','farah hanis','farah hanis','Nur Fazira Binti Jusoh','Nur Fazira Binti Jusoh','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Faiz Asni','Faiz Asni','Faiz Asni','Faiz Asni','Ng Jing Tien','ainin azyyati','ainin azyyati','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Elizabeth Elly','wenqi','Shaheen Saleam','Jiesee Yong','NURUL SYAFIKA','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Hasmizah Khalid','Hasmizah Khalid','Deepaah Subramamian','Choo Yuen Seng','Choo Yuen Seng','Choo Yuen Seng','liyana binti abdullah','Nur Shakirah binti Kamal Ariffin','Nur Shakirah binti Kamal Ariffin','hijrah md isa','Remorn anak Jipong','Nur Atiqah Binti Manan','Mohammad Amirul Ashraf','nurfarahanim','Zulaikha Mohd Taib','Siva Gamy','Nur Fazieraa Binti Jaafar','Siti Najiha Binti Mohd Razali','Femmy CL','NIVARSHINI','Muhammad Safuan Bin Ahmad Jalaludin','IHSAN ISMAIL','Nurul Ain','Rasnih Nuil','Nur Atiqah Binti Manan','Siti H','nur afrina','yap lee Kei','ROSSHEILAWANI BT MOHD RAZALI','Hanna Hii','noraini binti mohd zaidi','Geetha Nair Sundaram','darshini darshu','Dashinipriya','nor athirah bt ibrahim @ azizi','Mohd Fadeli','Hasmizah Khalid','Ruzan Jehan Mohd Ayob','Khoo Er Teng','Amin Nazir','liew choon cheuan','liew choon cheuan','noor azira','Shalini Karinalili','Shalini Karinalili','Ling Hui Jin','Ling Hui Jin','Maginei Misaa','Thong Ying Hoong','Tilasini Jagathesan','Tilasini Jagathesan','Nur Izzati','Nur Izzati','crystal Lau','Lee Chi Yi','Dan Qing Khaw','Stella Tiong','victoria anak iyip','Goh','Saran Dorai','Sheba Solomi Moses Vejaya Kumar','Sheba Solomi Moses Vejaya Kumar','Nur Syahidah binti Mohaidi','Geetha Nair Sundaram','Lilian Lim','Nurmeymeng zalia','Nurul Hidayah Roslan','Peai Hui','irene smilewan','Anjum Anwar','jimah','jimah','jimah','jimah','jimah','Saran Dorai','Nurul Syahirah','Hoexinjing','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Ateng Roslan','Aylvin Wong','JANESSA anakTERANG','Nurul Nadhirah Binti Hamzah','Liew yue xuan','Soo Jin Gui','Rubini Maniam','Fatin Athira','fairuza munirah bt mazlan','syarmimi rima yolanda','fathi yahya','fathi yahya','Charles97','Alissa Shamsudin','ERNIE DUSILY','Fatin Najihah Abdullah','Fatin Najihah Abdullah','Lilian Lim','Nor Nadzirah Bt Shaari','Haniff Zakwan','Ooi Man Thing','Palanikumar Kamaraj','Zasmin Aisha Binti Naumul','Lim Siow Yin','Shi Ring','Lim Siow Yin','Lim Siow Yin','Sharifah Hazirah Binti Syed Ahmad','Yap Tai Loong','Thong Ying Hoong','Kaiting Lim','rafidah','rafidah','rafidah','Siva Gamy','Siva Gamy','Goh Quo Yee','Nurul Akmal Fatihah bt Abd Hadi','Ana Razaly','Yap Tai Loong','liyana binti abdullah','Amin Nazir','Siti Najiha Binti Mohd Razali','marlia syuhada','Mohamad Jafni','Amila Solihan','Amila Solihan','Deanna Chua Li Ann','farah hanis','Aimi Nabila','Hammsavaally Ganesan','Siti Hajiah Binti Rani','Monica Bandi','Fazleen Izwana Masrom','Mohd Firdaus Bin Ibrahim','Nurul Ain','mohamad humam bin mohamad isa','casterchu','casterchu','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','Altwkzh Wardah','Ros anis farhanah','noraini binti mohd zaidi','Nur Hidayah Bt Ahmad Faizal','wong mei yee','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Eline Tie','aidy md dzahir','aidy md dzahir','Mohd Firdaus Bin Ibrahim','Mohd Khairulamirin','Nur Fazieraa Binti Jaafar','nooradira noordin','Siti Nadia Binti Sapari','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Muhammad Izzat','alice elizabeth','Nursyamimi binti Mazri','mohamad humam bin mohamad isa','Amin Nazir','nur aena','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Syaziana Binti Ali Kabar','Alissa Shamsudin','nurfarahanim','irene smilewan','hijrah md isa','hijrah md isa','Noor Syafiqah','ROZANA BINTI SAHRI','Anne Felicia Paul','Muhamad Hasri Shafee','Nurhafizah Mat Nafi','noraini binti mohd zaidi','Nurmeymeng zalia','Amown Daebak Sieyrien','Hemaa Abby','maisarah','Zulaikha Mohd Taib','Daranica','Wong Pei Ti','yap lee Kei','Tharshini Muthusamy');

  $days_date = array('29-1-2020 03:11:26 PM','29-1-2020 03:09:08 PM','28-1-2020 03:22:21 PM','28-1-2020 03:21:47 PM','28-1-2020 01:52:40 PM','28-1-2020 09:38:25 AM','27-1-2020 10:41:20 PM','27-1-2020 10:41:12 PM','27-1-2020 10:40:28 PM','27-1-2020 10:40:21 PM','27-1-2020 10:13:57 PM','27-1-2020 10:11:25 PM','27-1-2020 10:10:49 PM','27-1-2020 01:41:48 PM','26-1-2020 11:57:46 PM','26-1-2020 09:30:36 PM','26-1-2020 09:18:26 PM','26-1-2020 03:42:08 PM','25-1-2020 07:34:43 PM','24-1-2020 11:07:14 PM','24-1-2020 08:29:58 PM','24-1-2020 07:22:27 PM','24-1-2020 07:19:34 PM','24-1-2020 05:16:47 PM','24-1-2020 04:34:03 PM','24-1-2020 12:05:27 PM','24-1-2020 09:55:01 AM','24-1-2020 09:37:16 AM','24-1-2020 09:33:34 AM','24-1-2020 08:20:26 AM','24-1-2020 08:20:20 AM','24-1-2020 08:20:14 AM','24-1-2020 08:17:17 AM','24-1-2020 02:55:33 AM','23-1-2020 10:52:10 PM','23-1-2020 10:48:19 PM','23-1-2020 10:47:29 PM','23-1-2020 10:47:20 PM','23-1-2020 10:46:02 PM','23-1-2020 10:43:56 PM','23-1-2020 10:43:47 PM','23-1-2020 09:11:29 PM','23-1-2020 09:11:05 PM','23-1-2020 07:39:09 PM','23-1-2020 07:35:07 PM','23-1-2020 07:33:34 PM','23-1-2020 07:33:29 PM','23-1-2020 07:33:21 PM','23-1-2020 06:38:43 PM','23-1-2020 04:21:08 PM','23-1-2020 04:20:53 PM','23-1-2020 03:18:36 PM','23-1-2020 03:17:53 PM','23-1-2020 12:21:34 PM','23-1-2020 09:52:51 AM','22-1-2020 03:58:36 PM','22-1-2020 02:20:41 PM','22-1-2020 01:18:26 PM','22-1-2020 12:39:53 PM','22-1-2020 12:30:29 PM','22-1-2020 12:30:18 PM','22-1-2020 10:47:57 AM','22-1-2020 10:47:44 AM','22-1-2020 10:37:25 AM','22-1-2020 10:14:22 AM','22-1-2020 10:13:38 AM','22-1-2020 10:10:28 AM','22-1-2020 09:17:30 AM','22-1-2020 04:44:57 AM','22-1-2020 04:37:37 AM','21-1-2020 11:11:25 PM','21-1-2020 11:07:11 PM','21-1-2020 10:25:44 PM','21-1-2020 09:39:05 PM','21-1-2020 07:50:14 PM','21-1-2020 07:32:40 PM','21-1-2020 06:54:17 PM','21-1-2020 06:48:01 PM','21-1-2020 06:32:36 PM','21-1-2020 05:54:33 PM','21-1-2020 05:36:37 PM','21-1-2020 03:13:12 PM','20-1-2020 04:21:47 PM','19-1-2020 07:16:06 PM','18-1-2020 07:35:20 PM','18-1-2020 11:46:49 AM','16-1-2020 12:32:29 PM','16-1-2020 10:22:50 AM','15-1-2020 03:02:48 PM','15-1-2020 09:36:00 AM','14-1-2020 08:23:22 PM','14-1-2020 04:47:29 PM','14-1-2020 02:40:37 PM','14-1-2020 02:20:46 PM','14-1-2020 02:15:57 PM','14-1-2020 09:20:56 AM','14-1-2020 07:54:40 AM','14-1-2020 07:26:51 AM','13-1-2020 08:37:48 PM','13-1-2020 12:34:04 AM','12-1-2020 10:17:34 PM','11-1-2020 08:14:43 PM','11-1-2020 08:13:38 PM','11-1-2020 07:25:21 PM','11-1-2020 06:43:58 PM','11-1-2020 06:43:56 PM','10-1-2020 11:57:40 PM','10-1-2020 11:55:55 PM','10-1-2020 11:49:13 PM','10-1-2020 09:49:37 PM','10-1-2020 07:24:42 PM','10-1-2020 07:19:00 PM','10-1-2020 10:48:49 AM','10-1-2020 10:47:56 AM','9-1-2020 11:16:44 PM','9-1-2020 10:14:16 PM','9-1-2020 10:11:40 PM','9-1-2020 07:46:32 PM','9-1-2020 06:14:18 PM','9-1-2020 12:44:01 PM','9-1-2020 10:59:14 AM','9-1-2020 10:56:34 AM','9-1-2020 10:54:33 AM','9-1-2020 10:13:44 AM','9-1-2020 09:28:50 AM','9-1-2020 08:24:11 AM','8-1-2020 07:05:51 PM','8-1-2020 09:32:02 AM','6-1-2020 12:05:29 PM','5-1-2020 11:37:45 PM','5-1-2020 03:01:35 PM','5-1-2020 01:49:52 PM','5-1-2020 01:42:41 PM','5-1-2020 01:38:20 PM','5-1-2020 01:35:09 PM','5-1-2020 01:34:14 PM','5-1-2020 01:23:56 PM','4-1-2020 10:35:40 PM','4-1-2020 10:47:57 AM','4-1-2020 06:28:26 AM','4-1-2020 06:26:04 AM','4-1-2020 12:58:41 AM','3-1-2020 06:25:31 PM','3-1-2020 04:21:54 PM','3-1-2020 02:51:01 PM','3-1-2020 09:40:45 AM','2-1-2020 10:41:28 PM','2-1-2020 06:32:43 PM','2-1-2020 04:39:17 PM','2-1-2020 04:38:30 PM','2-1-2020 04:24:29 PM','2-1-2020 04:18:20 PM','2-1-2020 04:15:36 PM','2-1-2020 03:57:02 PM','2-1-2020 03:21:34 PM','2-1-2020 12:47:11 PM','2-1-2020 11:46:51 AM','2-1-2020 11:43:22 AM','2-1-2020 10:58:21 AM','2-1-2020 03:31:42 AM','1-1-2020 09:50:03 PM','1-1-2020 03:22:54 PM','31-12-2019 05:49:00 PM','31-12-2019 04:12:15 PM','31-12-2019 03:49:52 AM','30-12-2019 10:23:02 PM','30-12-2019 08:17:26 PM','30-12-2019 08:15:53 PM','30-12-2019 04:38:05 PM','30-12-2019 02:57:44 PM','30-12-2019 02:26:50 PM','30-12-2019 09:18:45 AM','30-12-2019 08:02:07 AM','30-12-2019 07:59:10 AM','30-12-2019 07:57:51 AM','29-12-2019 10:39:40 PM','29-12-2019 10:32:40 PM','29-12-2019 07:01:10 PM','29-12-2019 04:36:24 PM','29-12-2019 07:47:52 AM','29-12-2019 06:09:40 AM','29-12-2019 01:19:47 AM','28-12-2019 08:11:55 PM','28-12-2019 04:46:19 PM','28-12-2019 03:13:49 PM','28-12-2019 06:11:26 AM','27-12-2019 01:26:30 PM','27-12-2019 01:21:45 PM','27-12-2019 08:22:31 AM','26-12-2019 11:21:33 PM','26-12-2019 07:39:02 PM','26-12-2019 05:37:16 PM','26-12-2019 05:34:42 PM','26-12-2019 02:48:38 PM','26-12-2019 12:22:32 PM','26-12-2019 08:47:25 AM','26-12-2019 08:22:32 AM','26-12-2019 07:32:40 AM','25-12-2019 11:25:50 PM','25-12-2019 11:22:39 PM','25-12-2019 07:12:45 PM','25-12-2019 04:45:09 PM','25-12-2019 12:02:35 PM','24-12-2019 07:28:29 PM','24-12-2019 04:39:46 PM','24-12-2019 03:54:56 PM','24-12-2019 11:41:06 AM','24-12-2019 11:38:56 AM','24-12-2019 10:57:30 AM','24-12-2019 08:40:35 AM','24-12-2019 08:36:30 AM','24-12-2019 08:08:33 AM','24-12-2019 05:38:09 AM','23-12-2019 10:57:41 PM','23-12-2019 10:31:20 PM','23-12-2019 10:31:08 PM','23-12-2019 02:26:44 PM','23-12-2019 02:23:14 PM','23-12-2019 02:17:15 PM','23-12-2019 09:33:55 AM','23-12-2019 08:51:59 AM','23-12-2019 08:42:10 AM','23-12-2019 01:01:24 AM','22-12-2019 10:43:30 PM','22-12-2019 11:19:55 AM','22-12-2019 11:17:44 AM','22-12-2019 11:14:37 AM','22-12-2019 08:57:21 AM','22-12-2019 12:50:39 AM','21-12-2019 11:26:46 PM','21-12-2019 11:26:13 PM','21-12-2019 09:14:30 PM','21-12-2019 09:13:04 PM','21-12-2019 06:26:01 PM','21-12-2019 06:05:13 PM','21-12-2019 11:01:14 AM','21-12-2019 07:54:14 AM','21-12-2019 01:22:45 AM','21-12-2019 12:45:25 AM','20-12-2019 11:41:35 PM','20-12-2019 11:20:50 PM','20-12-2019 10:38:45 PM','20-12-2019 07:43:19 PM','20-12-2019 06:52:14 PM','20-12-2019 05:44:40 PM','19-12-2019 10:38:31 AM','18-12-2019 10:50:22 PM','18-12-2019 04:43:51 PM');

  $days_amount = array('20.00','40.00','30.00','30.00','9.00','20.00','10.00','10.00','5.46','5.46','30.00','30.00','30.00','40.00','33.50','134.00','8.00','52.06','55.00','30.00','50.00','17.00','6.00','10.00','6.00','2.00','20.00','6.70','9.70','20.00','20.00','20.00','20.00','35.00','20.00','20.00','45.00','45.00','45.00','15.99','15.99','11.60','11.60','20.00','11.58','11.58','11.58','11.58','50.00','146.94','146.94','30.00','30.00','10.00','30.00','13.30','42.51','36.49','44.00','44.00','44.00','5.00','5.00','10.00','30.00','30.00','20.00','6.50','45.00','45.00','50.00','25.00','17.39','84.00','60.00','25.00','9.75','21.00','6.00','50.00','22.00','10.00','100.00','30.00','30.00','3.47','20.00','6.00','5.00','8.00','110.00','12.95','150.00','20.00','20.00','100.00','10.00','5.00','71.67','35.00','6.00','59.07','59.07','1.00','10.00','10.00','30.00','30.00','150.00','10.00','22.71','22.71','20.00','20.00','10.00','30.00','20.00','22.00','8.00','10.00','30.00','30.00','200.00','20.00','150.00','5.00','20.00','17.00','10.00','36.95','25.00','35.00','36.00','36.00','36.50','36.50','30.00','6.48','81.75','17.00','17.00','35.82','200.00','10.00','12.00','10.00','82.00','16.76','50.00','25.00','120.00','10.00','20.00','17.27','10.00','15.00','50.00','50.00','3.00','17.00','100.00','7.85','20.00','9.43','50.00','50.00','50.00','50.00','15.00','20.00','10.00','200.00','10.00','35.00','15.00','39.25','39.25','100.00','8.00','30.00','25.00','4.70','5.00','6.00','58.00','200.00','7.00','10.00','30.00','40.00','10.00','30.00','8.00','7.00','10.00','14.70','30.00','25.00','14.00','14.00','30.00','50.00','60.00','20.00','5.27','20.00','10.00','10.00','50.00','100.00','200.00','14.30','35.00','20.00','30.00','50.00','10.00','9.00','12.43','157.61','50.00','200.00','3.00','20.00','2.10','2.10','3.70','60.00','11.00','50.00','7.55','50.00','50.00','100.00','153.15','64.34','27.00','21.95','10.90','100.00','200.00','30.00','4.20','50.00','30.00','23.59','5.00','37.00');

  $days_model_arr = array();
  for($x = 0; $x< count($days_date) ; $x ++){

    $temp = array( 'document_date' => $days_date[$x], 'amount' => $days_amount[$x], 'name' => $days_user_name[$x], 'status' => $days_status[$x] ,'is_success' => $days_status[$x] == 'Success' ? 1 : 0 , 'date' =>
      date('Y-m-d', strtotime($days_date[$x])));
    array_push($days_model_arr,$temp);
  }

  ini_set('max_execution_time', 300000);
  $leaf_api  = new LeafAPI();
  $uTransactionModel = UTransaction::all();
  $result_listing = array();



  foreach ($uTransactionModel as $model) {
    
    foreach($days_model_arr as $temp){
      
      if($temp['amount'] == $model['amount']  && $temp['date'] == $model['document_date'])
      {
        $model['real_result'] = $temp['status'];
        $model['is_success'] = $temp['status'];
        $model['model_name'] = $temp['name'];
      }
    }
    

    $result = $leaf_api->get_check_payment($model['leaf_payment_id']);
    $meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
    $model['ie_is_paid']    = $model['is_paid'] ;
    $model['is_paid']   = $result['payment_paid'] ;
    $model['payment_customer_name'] = $result['payment_customer_name'];
    $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;


    //if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
    //dd($meter_payment_model);

    if($result['payment_paid'] == false){
      if($model['is_paid'] == true){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [wrong ie null model]';
          array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'revert_item [wrong ie remove model]';
          array_push($result_listing,$model);
        }
        
      }else{
        if(isset($meter_payment_model['id'])){
          $model['result_type'] = 'revert_item [null ie remove model]';
          array_push($result_listing,$model);
        }
      }

      
    }

    if($result['payment_paid'] == true){
      if($model['is_paid'] == false){
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [null ie null model]';
          array_push($result_listing,$model);
        }else{
          $model['result_type'] = 'non_capture [wrong ie with model]';
          array_push($result_listing,$model);
        }
        
      }else{
        if(!isset($meter_payment_model['id'])){
          $model['result_type'] = 'non_capture [true ie null model]';
          array_push($result_listing,$model);
        }
        
      }

      
    }


    if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
      
        $model['result_type'] = 'success payment - wrong ie - no model';
        array_push($result_listing,$model);

      if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
        //ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
      }
    }
}


  echo '<table>';
  echo '<tr>
        <th width="50px;">Utransaction ID</th>
        <th width="50px;">Payment number</th>
          <th width="50px;">Document number</th>
        <th width="50px;">Model Name</th>
          <th width="50px;">Name</th>
          <th width="50px;">Payment Amount</th>
          <th width="50px;">Created At</th>
        
         
         <th width="50px;">Sunmed Ipay88 Status</th>
         <th width="50px;">Sunmed Ipay88 Result</th>
          <th width="50px;">requery Status</th>
          <th width="50px;">Ie Status</th>
          <th width="50px;">Is with Model</th>
         
         
          <th width="50px;">Status</th>
     </tr>';

  
  usort($result_listing, 'App\Setting::compare_by_created_at');
  usort($result_listing, 'App\Setting::compare_by_column');
  foreach($result_listing as $result)
  { 
    if(!isset($result['real_result'])){
      continue;
    }
    echo '<tr>
        <td style="font-size:16px">'.$result['id'].'</th>
        <td style="font-size:16px">'.$result['leaf_payment_id'].'</th>
          <td style="font-size:16px">'.$result['document_no'].'</th>
        <td style="font-size:16px">'.$result['model_name'].'</th>
          <td style="font-size:16px">'.$result['payment_customer_name'].'</th>
          <td style="font-size:16px">'.$result['amount'].'</th>
          <td style="font-size:16px">'.$result['created_at'].'</th>
         
          <td style="font-size:16px">'.$result['real_result'].'</th>
          <td style="font-size:16px">'.$result['is_success'].'</th>
          <td style="font-size:16px">'.$result['is_paid'].'</th>
          <td style="font-size:16px">'.$result['ie_is_paid'].'</th>
           <td style="font-size:16px">'.$result['is_payment_model_created'].'</th>
          
          <td style="font-size:16px">'.$result['result_type'].'</th>
     </tr>';
    //echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
  }
  echo '</table>';

  echo 'Total '.count($result_listing).' records.';
  dd('End');


  });



Route::get('Ipay88PaymentCheck', function ()
{
  $success_name_list = array('Nur mizah','Remorn anak Jipong','ERNIE DUSILY','Norazlin Binti Iskan','crystal tan','Muhammad Safuan Bin Ahmad Jalaludin','Aimi Nabila','siti aisyah','Zulaikha Mohd Taib','wenqi','Nurul Akmal Fatihah bt Abd Hadi','Leong Shwu Jye','Normarini Morad','Nurulfidya Syafika Binti Mohd Shopi','Tan Wen Li','Anis Sabirah','Han Yee Chen','nor hazwani bt ahmad tarmidi','Celine Ying','Siti Najiha Binti Mohd Razali','ainun shahria','Audry Chieng Wen Wen','Ahmad Kamil Bin Kelin','Shalini Karinalili','Nurul Syahirah','Siti Najiha Binti Mohd Razali','nur shamimi shuhada binti rahimi','Anjum Anwar','Siti Najiha Binti Mohd Razali','Siti Najiha Binti Mohd Razali','Arisya Shahirah','Ahmad Hilman Affandi','Tee Jiong Rui Jane','Nur Syahidah binti Mohaidi','Nurul Najihah','Amin Nazir','hameeza','norsyakila yaacob','melita','hew Lee sin','Ivory Chin Ai Wei','Yung Ying Hsia','Ainul Mardiah Binti Ideris','Tharshini Muthusamy','yap lee Kei','Wong Pei Ti','Daranica','Zulaikha Mohd Taib','maisarah','Hemaa Abby','Amown Daebak Sieyrien','Nurmeymeng zalia','Nurhafizah Mat Nafi','Muhamad Hasri Shafee','Anne Felicia Paul','ROZANA BINTI SAHRI','Noor Syafiqah','hijrah md isa','irene smilewan','nurfarahanim','Alissa Shamsudin','Syaziana Binti Ali Kabar','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','nur aena','Amin Nazir','mohamad humam bin mohamad isa','Nursyamimi binti Mazri','Muhammad Izzat','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Siti Nadia Binti Sapari','nooradira noordin','Nur Fazieraa Binti Jaafar','Mohd Khairulamirin','aidy md dzahir','aidy md dzahir','Eline Tie','Mohamad Nuraliff Hafizin Bin Mastor','wong mei yee','noraini binti mohd zaidi','Ros anis farhanah','Altwkzh Wardah','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','casterchu','mohamad humam bin mohamad isa','Mohd Firdaus Bin Ibrahim','Fazleen Izwana Masrom','Monica Bandi','Siti Hajiah Binti Rani','Hammsavaally Ganesan','Aimi Nabila','farah hanis','Deanna Chua Li Ann','Amila Solihan','Amila Solihan','Mohamad Jafni','marlia syuhada','Siti Najiha Binti Mohd Razali','Amin Nazir','liyana binti abdullah','Ana Razaly','Nurul Akmal Fatihah bt Abd Hadi','Goh Quo Yee','Siva Gamy','rafidah','Kaiting Lim','Thong Ying Hoong','Yap Tai Loong','Sharifah Hazirah Binti Syed Ahmad','Shi Ring','Lim Siow Yin','Zasmin Aisha Binti Naumul','Palanikumar Kamaraj');

  ini_set('max_execution_time', 300000);
  $leaf_api  = new LeafAPI();
  $uTransactionModel = UTransaction::all();
  $result_listing = array();

  foreach ($uTransactionModel as $model) {
    
    $result = $leaf_api->get_check_payment($model['leaf_payment_id']);
    dd($result);
  
    $meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
    if( !isset($model['leaf_payment_id'])){
      dd($model['id']);
    }
    if( $model['leaf_payment_id'] == '096c3bd77ce7cdad99e9b44985f912e6'){
      echo json_encode($meter_payment_model)."<br>";
    }
    //if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
    //dd($meter_payment_model);
    if($model['is_paid'] == true){
      if($result['payment_paid'] == false){
        $model['result_type'] = 'revert_item [true ie wrong actual]';
        $model['is_paid']   = $result['payment_paid'] ;
        $model['payment_customer_name'] = $result['payment_customer_name'];
        $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
        $model['link_model'] ;
        array_push($result_listing,$model);
      }else if($result['payment_paid'] == true){
        if( !isset($meter_payment_model['id']))
        {
          if( $model['leaf_payment_id'] == '096c3bd77ce7cdad99e9b44985f912e6'){
            dd($meter_payment_model);
          }
          $model['result_type'] = 'null_pr_model_item [true ie true actual]';
          $model['is_paid']   = $result['payment_paid'] ;
          $model['payment_customer_name'] = $result['payment_customer_name'];
          $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
          $model['link_model'] ;
          array_push($result_listing,$model);
        }
        
      }else{  

          $model['result_type'] = 'Query no status [True ie]';
          $model['is_paid']   = $result['payment_paid'] ;
          $model['payment_customer_name'] = $result['payment_customer_name'];
          $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
          $model['link_model'] ;
          array_push($result_listing,$model);
      }

    }else if($result['payment_paid']  != false && $result['payment_paid']  != true ){
      $model['result_type'] = 'pending_update_item [wrong actual result]';
      $model['is_paid']   = $result['payment_paid'] ;
      $model['payment_customer_name'] = $result['payment_customer_name'];
      $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
      $model['link_model'] ;
      array_push($result_listing,$model);
    }

  

    if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
      
        $model['result_type'] = 'success payment - wrong ie - no model';
        $model['is_paid']   = $result['payment_paid'] ;
        $model['payment_customer_name'] = $result['payment_customer_name'];
        $model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
        $model['link_model'] ;
        array_push($result_listing,$model);

      if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
        ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
      }

    
    //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
  
    //get_model_by_leaf_payment_id($model['leaf_payment_id']);


    //echo 'Document date='.$result['payment_entry_date'].'||amount='.$result['payment_total_amount'].'||'.$result['payment_customer_name'].'='.$result['payment_customer_email']."=status=".$result['payment_paid'].'</br>';
    if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
      //ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
    }
    
  }
}


  echo '<table>';
  echo '<tr>
        <th width="50px;">Utransaction ID</th>
        <th width="50px;">Payment number</th>
          <th width="50px;">Document number</th>
          <th width="50px;">Name</th>
          <th width="50px;">Payment Amount</th>
          <th width="50px;">Created At</th>
          <th width="50px;">Is with Model</th>
          <th width="50px;">Is inside list</th>
          <th width="50px;">Status</th>
     </tr>';

  
  usort($result_listing, 'App\Setting::compare_by_created_at');
  usort($result_listing, 'App\Setting::compare_by_column');
  foreach($result_listing as $result)
  { echo '<tr>
        <th>'.$result['id'].'</th>
        <th>'.$result['leaf_payment_id'].'</th>
          <th>'.$result['document_no'].'</th>
          <th>'.$result['payment_customer_name'].'</th>
          <th>'.$result['amount'].'</th>
          <th>'.$result['created_at'].'</th>
          <th>'.$result['is_payment_model_created'].'</th>
          <th>'.in_array( $result['payment_customer_name'] , $success_name_list).'</th>
          <th>'.$result['result_type'].'</th>
     </tr>';
    //echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
  }
  echo '</table>';

  echo 'Total '.count($result_listing).' records.';
  dd('End');


  });





Route::get('airbnbtest', function ()
{ 
  $result = AirbnbWebGrabber::get_dashobord_content();
  dd($result);
});


Route::get('convert_sky_pricing', function ()
{ 
  $result = City::convert_delivery_pricing_excel();
  dd($result);
});


Route::get('sky', function ()
{ 
  $s =SkyNetAPI::get_track_status_array();
  $s_api = new SkyNetAPI();
  $result = $s_api->post_tracking_result('238289437072');
  dd($result);
});


Route::get('send_test', function ()
{ 
   $email  = "adelfried1227A886@gmail.com";
   $title = "test";
   $html = "test content";

  
  $api = new LeafAPI();
  $api->send_email($email, $title, $html);
  dd('done');
});

Route::get('setTestGroup', function ()
{ 
  $leaf_group_id = 519;
  $c = new Company();
  $c->set_group_id($leaf_group_id);
  $api = new LeafAPI();
  $api->set_cookie_modules();
  
  dd('done');
});

Route::get('power_meter_get_all_house', function ()
{ 
  ini_set('max_execution_time', 300000);
  $leaf_group_id = isset($_GET["leaf_group_id"]) ? $_GET["leaf_group_id"]  : 282 ;
  echo 'Set :'.$leaf_group_id."<br>";
  Setting::setCompany($leaf_group_id);
  $c = new Company();
  $c->set_group_id($leaf_group_id);
  $api = new LeafAPI();
  $api->set_cookie_modules();

  if(isset($_GET["leaf_group_id"]))
  {
    if($_GET["leaf_group_id"] != Company::get_group_id())
    {
      dd('Please click url again ='.Company::get_group_id());
    }
  }
  $leaf_api   =   new LeafAPI();
  $fdata      =   $leaf_api->get_houses(true,$leaf_group_id);
  //dd($fdata);
  if ($fdata['status_code']) {
    echo $fdata['status_code']."<br>";
        if (isset($fdata['house']) && $houses = $fdata['house']) {

            foreach ($houses as $house) {
              House::save_house_room($house);
            }
        }
    }
    dd('end');
});


Route::get('update_summary_test', function ()
{ 
   ini_set('max_execution_time', 300000);
   $house   =   new House();
   $fdata      =   $house->get_houses(true);
  // dd($fdata);
  // $listing    =   array('period_member' => array(),'start_date_sequence' => array());
   //$stay_timeline = array();

  if ($fdata['status_code']) {
        if (isset($fdata['house']) && $houses = $fdata['house']) {
            foreach ($houses as $house) {
              //dd($house);
                CustomerPowerUsageSummary::update_or_save_customer_summary_by_leaf_house($house);
          /*foreach($house['house_rooms'] as $room){ 
  
            if($room['id_house_room'] == $leaf_room_id){
              foreach ($room['house_room_members'] as $member) {
                
                dd($room);
                  $temp['house_rooms'] = $room;
                  $temp['id_house_room'] = $room['id_house_room'];
                  $temp['id_house'] = $house['id_house'];
                  $temp['house_unit'] = $house['house_unit'];
                  $temp['house_subgroup'] = $house['house_subgroup'];
                  $temp['house_room_member_start_date'] = $member['house_room_member_start_date'];
                  $temp['house_room_member_end_date'] = $member['house_room_member_end_date'];
                  $temp['house_room_member_deleted'] = $member['house_room_member_deleted'];
                  array_push($listing, $temp);

                  $temp['timeline_date'] = $member['house_room_member_start_date'];       
                  array_push( $stay_timeline, $temp);
                  $temp['timeline_date'] = $member['house_room_member_end_date'];
                  array_push( $stay_timeline, $temp);
            }
          }
        }*/
      }
    }
  }
dd('end');
});


Route::get('get_member_detail', function ()
{ 
  $house_member_arr = array();
  $checked_arr = array();
  $name_to_check_arr = ['Phong Fu Zheng','Leong Kok Fay','Kan Sheng Hao','Julian Ng Ding Sheng','Loh Zhi Xian','Kishwari A/P Selvakumar','Jasmine Lam Man Jing','Hew Wen Yi','Tan Siow An','Law Ee Li','Wah Jue Wei','Chan See Kei','Tan Yuen Xi','Loh Shu Min','Lim Yin Jing','Yap Khai Nee','Audry Chieng Wen Wen','Wee Ann Jie','Maneesha Devi A/P Sahasvaranathan','Grace Liu Hui Yin'];

  /*$name_to_check_arr = ['Rusmawati Binti Osman','Nur Sera Azwa Binti Md Khair','Siti Noratiqah bt Mohd Rahim','Nurul Akmal Fatihah Binti Abd Hadi','Anis Fasehah Binti Jamal','Vivian Ting Mee Siew','Anis Sabirah bt Ismail','Nur Izzati bt Razali','Hijrah Binti Md Isa','Syarmimi Rima Yolanda Binti Mohamad Sharif','Nur Hidayah Binti Hermanto','Nurul Ain Binti Abdul Rahim','Ram Mala Kansa','Ruhana bt Ghazali','Nor Hazwani bt Ahmad Tarmidi','Emily Chu Shi Tieng','Serena Tai Hui Yan','Melita a/p AntonySamy','Nursuhadah Binti Salme','Nur Syahidah Binti Sahran'];*/
  
  //$name_to_check_arr = ['Sariyuslina Binti Mohamad Shrihuddin','Nor Athirah Binti Ibrahim@Azizi','Siti Zurshaira Binti Zurkefli','Femmy C. Limos','Siti Norzakiah Binti Jamali','Nur Aimi Safura Binti Azlan','Norfarahim Bt Mat Yuki','Nurfairus Binti Zulhakim','Nurul Faezah Binti Badri','Fatriyani Binti Rodelio','Nor syuhada binti Rahisam','Nor Haslinda bt zulkeffli','Dashini A/P M.Gunasegaran','Nor Azyyati bt Mohd Zin','Khairun Nabilah bt Hashim','Nik Marlia Athirah Binti Nik Husin','Rozita Binti Mohd Nasar','Yusminzaliani Binti Mohd Pauzee','Nur Syaidatul Najwa Binti Mohd Salleh Chin','Siti Najiha Binti Mohd Razali','Nurul Hidayah Binti Roslan','Noraini Binti Mohd Zaidi','Siti Noraishah Binti Mohd Hasan','Nur Afrina bt Husin','Kong Le Wen','Lee SuMei','Ling Sing Jie','Nur Shakirah bt Kamal Ariffin','Lizbenenna Benjamin','Nurul Ardini bt Rahim','Marlia Syuhada bt Che Omar','Mimi Shahira bt Zamri','Erranorisa bt Mat Salin','Chong Siew Theng'];
  $api  = new LeafAPI();

  LeafAPI::get_room_history_by_leaf_room_id(343);
dd("x");
  $house_list = $api->get_houses();
  //dd($house_list);
  $i = 1;
  echo '<table>';
  echo '<th> No </th><th>  Name </th><th> house_member_id </th>';
  foreach ($house_list['house'] as $house) {
    //dd($house);
    foreach ($house['house_rooms'] as $room) {
      foreach ($room['house_room_members'] as $member) {
        foreach ($name_to_check_arr as $name) {
          if($member['house_member_name'] == 'Kishwari A/P Selvakumar' || $member['house_member_name'] == 'Yap Khai Nee'){
            echo json_encode($member).'<br> <br>';
          }
          if($name == $member['house_member_name'])
          {  
            echo "<tr><td>".$i."</td><td style='text-align: center;'>".$member['house_member_name']."</td><td style='text-align: center; width='70%';>".$member['house_member_id_user']."</td></tr>";
            array_push($house_member_arr, $member['house_member_id_user']);
            array_push($checked_arr,$name);
            $i ++;
          }
        }
        
      }
    }
  }
  echo '</table>';

  $name_to_check_arr = \array_diff($name_to_check_arr, $checked_arr);
    
  
  dd($name_to_check_arr);
  dd($result);
});


Route::get('WinzAPI_getPRTest', function ()
{
  $winz_api  = new WinzAPI_2();
  $result = $winz_api->getGR();
  dd("e");
  dd($result);
});



Route::get('power_meter_mobile_test', function ()
{
    $email = '';
    $leaf_api     = new LeafAPI();
    if(isset($_GET["email"])){
      $email = $_GET["email"];
    }else{
      echo "No email enter , please add '?email=EmailToCheck', after power_meter_mobile_test.<br>";
      dd("http://webview.leaf.com.my/power_meter_mobile_test?email=abc@gmail.com");
    }

    $result   = $leaf_api->get_user_by_email($email);
    dd($result);
    if($result['status_code'] == -1){
      dd("Invalid email.");
    }


    //$room_listing = LeafAPI::get_all_stayed_room_by_id_house_member($user_detail['member_detail']['id_house_member']);
  
        $model        = new User();
        $setting      = new Setting();

    //getStarting of MONTH || USER Move in date
       /*if ($fdata['status_code']) {
            if (isset($fdata['house_room'])) {
                
        foreach ($fdata['house_room'] as $self_room) {
          $room =  $self_room; 
          break;
        }

        if(!isset($room)){
          return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
        }

            }else{      
        return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
      }
        }else{
      return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
    }*/

        if (!$result['status_code']) {
            $data['status']         = false;
            $data['status_msg']   = $result['error'];
        } else {
            $user = $model->get_or_create_user_account($result);
            Auth::loginUsingId($user->id, true);
            $data['status']         =   true;
            $data['status_msg']     =   'Authorization successfully.';
            //setcookie(LeafAPI::label_session_token, $this->session_token);
        }

        $meter_register_model;



    $user_detail =  $leaf_api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($user['leaf_id_user']);


    $page_variables =  [
                                    'page_title'   =>    Language::trans('Power Management'),
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

         $no_room_notice = [
                                    'title'   =>   Language::trans('You have no room register under your account.'),
                                    'detail' => Language::trans('If you are tenant here , please contact management office for room registration <br>, If you are not tenant here , this module is not open for non-tenant')
                                    ];
        $refresh_notice = [
                                    'title'   =>   Language::trans('Please refresh'),
                                    'detail' => Language::trans('Dear user, please press refresh to proceed.')
                                    ];

        $maintenance_notice = [
                                    'title'   =>   Language::trans('Maintenance'),
                                    'detail' => Language::trans('Dear value user, System is currently under maintenance , please stay tune with us.')
                                    ];
                                                            
    $leaf_group_id = Setting::SUNWAY_GROUP_ID;
    $company = Company::get_model_by_leaf_group_id($leaf_group_id);
    Setting::setCompany($leaf_group_id);
    //$page_title     =   $this->page_title;
    
    if(Company::get_group_id() == 0){
      //setcookie(LeafAPI::label_session_token, $this->session_token);
      $notice = $refresh_notice;
      return view(Setting::UI_VERSION.'utility_charges.mobile_apps.maintenance',compact('notice','user','page_variables'));
    }

    $room ;
    
        $model        = new User();
        $setting      = new Setting();
    /*$session_token  =   $this->session_token;
        $result     =   $leaf_api->get_user_profile($session_token);
    $fdata        =   $leaf_api->get_user_room($session_token);*/
    //getStarting of MONTH || USER Move in date

    //dd($user_detail);
       if($user_detail['leaf_room_id'] == 0){
          $notice = $no_room_notice;
            if($user_detail['leaf_room_id'] == 0){
    
        return view(Setting::UI_VERSION.'utility_charges.mobile_apps.informations.user_info',compact('notice','user','page_variables'));
      }
        }/*else{
          $notice = $no_room_notice;
      return view(Setting::UI_VERSION.'utility_charges.mobile_apps.informations.user_info',compact('notice','user','page_variables'));
    }*/

    $room = $user_detail['room'];

        if (!$result['status_code']) {
            $data['status']         = false;
            $data['status_msg']   = $result['error'];
        } else {
            $user = $model->get_or_create_user_account($result);
            Auth::loginUsingId($user->id, true);
            $data['status']         =   true;
            $data['status_msg']     =   'Authorization successfully.';
            //setcookie(LeafAPI::label_session_token, $this->session_token);
        }

        
        $room = $user_detail['room'];
    $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=',$room['id_house_room'])->first();
    if(!isset($meter_register_model)){
        return view(Setting::UI_VERSION.'utility_charges.mobile_apps.informations.user_info',compact('notice','user','page_variables'));
    }

    //new code  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $user_profile               = $user;
        $user_profile['account_no']   = $meter_register_model->account_no;
    $user_profile['address']      = $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
    $user_profile_string        = json_encode($user_profile);   
    $is_allow_to_pay      = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);
    $is_allow_to_pay =  $is_allow_to_pay == true ? true : false;
    $date_started = "";
    

    $date_started = "";


    $date_range = PowerMeterSetting::get_charging_date_range_by_user_detail($user_detail);

    //Recheck all paid and unpaid item
    Utransaction::update_utransaction_by_current_day_interval_leaf_user_id_or_all(300,$user_profile['leaf_id_user']);
    
    $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $room['id_house_room'] , $date_range);
    
    if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
  
      $user_stay_detail = $leaf_api->get_user_stay_detail_for_twin_room_by_leaf_room_id($user_detail['leaf_room_id']);
      $user_stay_detail['date_range'] = $date_range;
      $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_user_start_stay_detail_all($user_stay_detail,$leaf_group_id);
      
    }else{
      $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
    }
    
  
    $subsidy_listing  = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
    if(count($payment_received_listing) > 0){
            foreach ($payment_received_listing as $row) {
                $account_status['total_paid_amount'] += $row['total_amount'];
            }   
      }
      
    //Get statistic 
    $statistic['currentUsageKwh'] =  count($account_status['month_usage_summary']) > 0 ? $account_status['month_usage_summary'][count($account_status['month_usage_summary'])-1]['total_usage_kwh'] : 0; 
    $statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
    $statistic['balanceAmount'] = $account_status['total_paid_amount'] + $account_status['total_subsidy_amount'] -  $account_status['total_payable_amount'];  

    if($statistic['balanceAmount'] > 0 ){
         $statistic['currentBalanceKwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['currentUsageKwh'] , $statistic['balanceAmount']);
    }else{
        $statistic['currentBalanceKwh'] = 0;
    }
    //Get statistic 
    session(['statistic' =>  $statistic]);
    $last_reading_date_time     = date('jS F Y h:00 A', strtotime('+0 hours'));
        $month_usage_listing =    $account_status['month_usage_summary'];
        $session_token ='';
    return view(Setting::UI_VERSION.'utility_charges.mobile_apps.home.index', compact('is_allow_to_pay','status_msg','page_variables', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing','user'));
});


Route::get('get_summary_account_info_by_email', function(){
    
    $room;
    $api  = new LeafAPI();
    $email = "";
    $leaf_group_id = Setting::SUNWAY_GROUP_ID;
    $company = Company::get_model_by_leaf_group_id($leaf_group_id);
    Setting::setCompany($leaf_group_id);
    $page_title     =   "Testing";
    $staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123,16265,16179,16196);


    if(isset($_GET["email"])){
      $email = $_GET["email"];
    }else{
      echo "No email enter , please add '?email=EmailToCheck', after get_summary_account_info_by_email.<br>";
      dd("http://webview.leaf.com.my/get_summary_account_info_by_email?email=abc@gmail.com");
    }
    
    
    $result   = $api->get_user_by_email($email);
    if($result['status_code'] == -1){
      dd("Invalid email.");
    }

    $user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
    //dd($user_detail);
    if($user_detail['leaf_room_id'] == 0){
      dd("Currently did not stay at any room.");
    }
    $page_title = 'No user found';
    
    if(isset($user['id'])){
      return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
    }

    
    
    if(Company::get_group_id() == 0){
      setcookie(LeafAPI::label_session_token, $this->session_token);
      return view(Setting::UI_VERSION.'utility_charges.apps.user_payment_requirement',compact('page_title'));
    }
    //$member_detail = $user_detail['member_detail'];
    $room_listing = LeafAPI::get_all_stayed_room_by_id_house_member($user_detail['member_detail']['id_house_member']);
  
        $model        = new User();
        $setting      = new Setting();

    //getStarting of MONTH || USER Move in date
       /*if ($fdata['status_code']) {
            if (isset($fdata['house_room'])) {
                
        foreach ($fdata['house_room'] as $self_room) {
          $room =  $self_room; 
          break;
        }

        if(!isset($room)){
          return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
        }

            }else{      
        return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
      }
        }else{
      return view(Setting::UI_VERSION.'utility_charges.apps.user_info',compact('page_title'));
    }*/

        if (!$result['status_code']) {
            $data['status']         = false;
            $data['status_msg']   = $result['error'];
        } else {
            $user = $model->get_or_create_user_account($result);
            Auth::loginUsingId($user->id, true);
            $data['status']         =   true;
            $data['status_msg']     =   'Authorization successfully.';
            //setcookie(LeafAPI::label_session_token, $this->session_token);
        }

        $meter_register_model;
        foreach ($room_listing as $stay_room) {
          if($stay_room['house_room_member_deleted'] == true){
            continue;
          }
          $room = $stay_room ;
          $meter_register_model = DB::table('meter_registers')->where('leaf_room_id','=',$room['id_house_room'])->first();
        }
    
    if(!isset($meter_register_model)){
        return view(Setting::UI_VERSION.'utility_charges.apps.user_info');
    }

    //new code  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $user_profile               = $user;
        $user_profile['account_no']   = $meter_register_model->account_no;
    $user_profile['address']      = $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
    $user_profile_string        = json_encode($user_profile);   
    $is_allow_to_pay      = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

    $date_started = "";
    //$date_started = $room['house_room_member_start_date'];
    //  echo $date_started."=".Company::get_system_live_date($leaf_group_id);
      //dd($date_started < Company::get_system_live_date($leaf_group_id));
      
    if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
      $date_started = '2019-04-01';
    }else if($is_allow_to_pay == false){
      
      $date_started = $room['house_room_member_start_date'];
      //echo $date_started < Company::get_system_live_date($leaf_group_id);
      //dd($date_started < Company::get_system_live_date($leaf_group_id));
      if(($date_started < Company::get_system_live_date($leaf_group_id)) == true){
        $date_started = Company::get_system_live_date($leaf_group_id);
      }
        
      if($date_started == ""){
        $date_started = Company::get_system_live_date($leaf_group_id);
      }
      
      
    }else{
      $date_started = $room['house_room_member_start_date'];
      if($date_started == ""){
        $date_started = '2019-03-01';
      }
    }

    //Recheck all paid and unpaid item
    Utransaction::update_utransaction_by_current_day_interval_leaf_user_id_or_all(300,$user_profile['leaf_id_user']);
  
    $date_range   = array('date_started' => $date_started ,'date_ended' =>  date('Y-m-d', strtotime('now')));
    $account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $room['id_house_room'] , $date_range);
    $payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
    $subsidy_listing  = MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id($user['leaf_id_user'] ,$meter_register_model->id ,$leaf_group_id);
      

    if(count($payment_received_listing) > 0){
            foreach ($payment_received_listing as $row) {
                $account_status['total_paid_amount'] += $row['total_amount'];
            }   
      }
      
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //$credit = MeterPaymentReceived::getUserBalanceCreditBLeafRoomIdAndDateRange($room['id_house_room'], $date_range , $user->leaf_id_user);
    //$statistic = MeterReading::convertUserCreditToMeterReadingStatistic($credit);

    //Get statistic 
    $statistic['currentUsageKwh'] =  count($account_status['month_usage_summary']) > 0 ? $account_status['month_usage_summary'][count($account_status['month_usage_summary'])-1]['total_usage_kwh'] : 0; 
    $statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
    $statistic['balanceAmount'] = $account_status['total_paid_amount'] + $account_status['total_subsidy_amount'] -  $account_status['total_payable_amount'];  

    if($statistic['balanceAmount'] > 0 ){
         $statistic['currentBalanceKwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['currentUsageKwh'] , $statistic['balanceAmount']);
    }else{
        $statistic['currentBalanceKwh'] = 0;
    }
    //Get statistic 
    session(['statistic' =>  $statistic]);
    $last_reading_date_time     = date('jS F Y h:00 A', strtotime('+8 hours'));
        $month_usage_listing =    $account_status['month_usage_summary'];
  
    return view(Setting::UI_VERSION.'utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
  });
  
  

Route::get('sunmed_utility_summary_test', function(){
  $staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);
  $api = new LeafAPI();
  $house_listing= $api->get_customer_list();
  if($house_listing['status_code']){
    foreach ($house_listing['house'] as $house) {
      echo "<br>".$house['house_unit']."<br>";
      echo "============================================= <br>";
      foreach ($house['house_members'] as $member) {
        echo $member['house_member_name']."=".$member['house_member_email']."<br>";
        $result= MeterPaymentReceived::get_user_account_status_by_leaf_id_user_and_date_started($member['house_member_id_user'] , Company::get_system_live_date( Setting::get_leaf_group_id()));
        if(count($result)){
          foreach ($result as $item) {
            echo "====================================  Summanry  ==================================== <br>";
            echo $item['date_range']['date_started']."-".$item['date_range']['date_ended']."<br>";
            //echo "Total monthly Usage: ".$item['month_usage_summary']."<br>";
            foreach ($item['month_usage_summary'] as $month_usage) {
              echo "  |  ".$month_usage['date']."  | Ttl Usage : ".$month_usage['total_usage_kwh']."  | Ttl Payable : ".$month_usage['total_payable_amount']."  |  <br>";
            }
            echo "Total usage: ".$item['total_usage_kwh']."<br>";
            echo "Total payable : ".$item['total_payable_amount']."<br>";
            echo "Total paid : ".$item['total_paid_amount']."<br>";
            echo "Total subsidy : ".$item['total_subsidy_amount']."<br>";
            echo "==================================================================================== <br>";
          }
        }
        
      }
    }
  }

  //echo "============================================= <br>";

});

Route::get('get_sunmed_utility_by_user_email', function(){

  $email = $_GET["email"];
  echo "Check email  :".$email."<br> <br>";
  echo "-------------------------------------------------------------------------------------------------------------------------------------------------------------- <br>";
  
  $staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);
  $api = new LeafAPI();
  $user = $api->get_user_by_email($email);
  if(isset($user['id_user'])){
    foreach ($user as $key => $value) {
      echo "| ".$key." - ".$value."<br>";
    }
  }else{
    echo "No user found <br> <br>";
  }
  
  
  echo "<br> -------------------------------------------------------------------------------- Result -------------------------------------------------------------------------------- <br>";
  $result= MeterPaymentReceived::get_user_account_status_by_leaf_id_user_and_date_started($user['id_user'] , Company::get_system_live_date( Setting::get_leaf_group_id()));
  if(count($result)){
    foreach ($result as $item) {
      echo "--------------------------------------------------------------------------------  Summanry  ------------------------------------------------------------------------ <br>";
      echo $item['date_range']['date_started']."-".$item['date_range']['date_ended']."<br>";
      //echo "Total monthly Usage: ".$item['month_usage_summary']."<br>";
      foreach ($item['month_usage_summary'] as $month_usage) {
        echo "  |  ".$month_usage['date']."  | Ttl Usage : ".$month_usage['total_usage_kwh']."  | Ttl Payable : ".$month_usage['total_payable_amount']."  |  <br>";
      }
      echo "Total usage: ".$item['total_usage_kwh']."<br>";
      echo "Total payable : ".$item['total_payable_amount']."<br>";
      echo "Total paid : ".$item['total_paid_amount']."<br>";
      echo "Total subsidy : ".$item['total_subsidy_amount']."<br>";
      echo "---------------------------------------------------------------------------------------------------------------------------------------------------------------------- <br>";
    }
  }

  dd("End of checking");
  

});   



Route::get('update_product_customer', function(){

  /*foreach ($variable as $key => $value) {
    
  }*/
  //CustomerAddress::create_or_update_customer_address();

  $test_id = 2701;
  $api = new LeafAPI();
  //$result = LeafAPI::get_all_stayed_room_by_id_house_member($test_id);
  //dd($result);

  //$list = $api->get_houses();
  echo "Live Date :".Company::get_system_live_date(Setting::get_leaf_group_id())."<br>";
  echo "LG id : ".Setting::get_leaf_group_id()."<br>";

  $result= MeterPaymentReceived::get_user_account_status_by_leaf_id_user_and_date_started($test_id , Company::get_system_live_date( Setting::get_leaf_group_id()));
  dd($result);
  $result = LeafAPI::get_all_stayed_room_by_id_house_member($test_id);
  Setting::aa_sort($result,'house_room_member_start_date');
  dd($result);
  $result = $api->get_user_house_membership_detail_by_leaf_house_member_id_for_register(443224);
  dd($result);

  //$listing = $api->set_product_from_leaf_by_group_id(285);
  //printf("Product saved--");

  $c= new Company();
  $c->set_group_id(285);
  $customer = new Customer();

  
//dd($houses);
  foreach($houses['house'] as $house){
        echo nl2br("Next");
    $customer->save_customer_from_leaf_house($house);
  }
  printf("customer saved--");
  dd("end");
});


Route::get('customer_checking', function(){

  $check_list = array();
  $c_list = Customer::all();
  foreach ($c_list as $customer) {
    dd($customer);
    if(!in_array(  $customer['name'] , array_column( $check_list,  'name'))){
      $temp_customer['array_count'] = 1;
        
    }

    array_push($check_list, $temp_customer);
    
  }
  dd($c_list);
});

Route::get('update_customer', function(){
  $leaf_api = new LeafAPI();
  $c= new Company();
  $c->set_group_id(282);
  $customer = new Customer();

//dd($houses);
  $houses = $leaf_api->get_houses();
  if($houses['status_code'] == false){
    dd("No house"); 
  }


  foreach($houses['house'] as $house){
        echo nl2br("Next");
    $customer->save_customer_from_leaf_house_patching($house);
  }
  printf("customer saved--");
  dd("end");


});


Route::get('convertIpayToMeterReceiptModel', function ()
{
  ini_set('max_execution_time', 300000);
  $leaf_api  = new LeafAPI();
  $model = UTransaction::find(2033);
  
  $result = $leaf_api->get_check_payment($model['leaf_payment_id']);

    if($result['payment_paid'] == true){
      if($model['is_paid'] == false){
        $model['is_paid'] = true ;
        echo json_encode($model)."<br>";
        $model->save();
      }
    }
    //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
    
    //get_model_by_leaf_payment_id($model['leaf_payment_id']);


    echo 'Document date='.$result['payment_entry_date'].'||amount='.$result['payment_total_amount'].'||'.$result['payment_customer_name'].'='.$result['payment_customer_email']."=status=".$result['payment_paid'].'</br>';
    if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
      //dd();
      ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
  }
  
  dd("End");
});



Route::get('ipay_leaf_id_check', function ()
{
  $leaf_api = new LeafAPI();
  $check_list = array('b47ee299d16aba2dbfd380321dc2a840','1e32883fe8a7f6dce1e7bac63d6edaf7','8dc537f56798999280b52b04d8eedede','592a2191672aa38afeabc126faa205ab','2e241a2a8ba7d3a2cd9ce79c1d2e0a42','3adbff770d7e8f5ac3035779a9d6eef6','8dce8595babc5af903764626fd894654','cdef9bff66c273d291454ad8d4a94473','2914a06bd9be5e81b339c29b871e19dc','d9ee77628d0891bb6f5ae2db5aa3a43f','309b016aa27e8b6f8d258df6338847db','6bc785298afbbd2944407cc0b1f228e7');

  foreach ($check_list as $check_item) {
  
    $result = $leaf_api->get_check_payment($check_item);
    echo $result['payment_reference']."=".$check_item.'('.$result['payment_paid'].')'.'->'.$result['payment_paid']."<br>";
    //dd($result);
    
  }
});


Route::get('convertIpayToMeterReceipt', function ()
{
  //dd(ARInvoice::get_today_new_record()."=".ARPaymentReceived::get_today_new_record());
  //$leaf_api = new LeafAPI();
       //  $leaf_membership_detail = $leaf_api->get_user_house_membership_detail_by_user_id(2701);
         // $leaf_membership_detail = $leaf_api->get_single_fee_type(57);
      //   dd($leaf_membership_detail);
  /*UTransaction::update_utransaction_by_current_day_interval_leaf_user_id_or_all(30,15151);
  dd("end");*/
  /*$leaf_api  = new LeafAPI();
  $u =  $leaf_api->get_leaf_user_by_leaf_id_user(26115);
  dd($u);*/
  $leaf_api  = new LeafAPI();
  $UTransactionModel = UTransaction::all();

  foreach ($UTransactionModel as $model) {
  
    $result = $leaf_api->get_check_payment($model['leaf_payment_id']);

    if($result['payment_paid'] == true){
      if($model['is_paid'] == false){
        $model['is_paid'] = true ;
        echo json_encode($model)."<br>";
        $model->save();
      }
    }
    //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
    
    //get_model_by_leaf_payment_id($model['leaf_payment_id']);


    //echo 'Document date='.$result['payment_entry_date'].'||amount='.$result['payment_total_amount'].'||'.$result['payment_customer_name'].'='.$result['payment_customer_email']."=status=".$result['payment_paid'].'</br>';
    if($result['payment_paid'] == true){
      //echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
      //dd();
      ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
    }
    
  }
  
  dd("End");
});


Route::get('leafToMembership', function ()
{
  dd(Company::get_currency_term(285));
  $member = Membership::save_membership_detail_by_leaf_id_user(8809);
  dd("Ednd");
});

Route::get('leafToNCLProduct', function ()
{
  $leaf_api  = new LeafAPI();
  $leaf_product = $leaf_api->get_all_leaf_payable_item_model_by_group_id(285);
  //dd($leaf_product);
  foreach ($leaf_product as $row) {
    //$c = LeafAPI::get_leaf_product_category_by_leaf_product_model($row);
    //echo $c."<br>";
    Product::save_product_from_leaf($row);
  }
  dd("Ednd");
});


Route::get('nclSaveCustomer', function ()
{
  $api = new NclAPI();
  $return = $api->get_bank_account_list();
  dd($return);
  dd("done");
});
Route::get('getMembership', function ()
{
  $leaf_api  = new LeafAPI();
  dd($leaf_api->get_fee_type_by_group_id(285));
});


Route::get('webGrabber_costo', function ()
{
  OCCategory::combobox(1);
  dd("End");
  $oc_product  = new OCProduct();
  $oc_product->setConnection('oc_mysql');
  $p = $oc_product->where('product_id' ,'=',30)->first();
  dd($p);

  dd(Company::get_system_live_date());
  dd(Setting::get_month_in_word(date('m')));
  //DB::reconnect('oc_mysql');
  //$return = Product::set_connection('oc_mysql');
  //dd($return);
  //$test = DB::connection('oc_mysql')->select("select * from oc_category");
  //dd($test);
  CostcoWebGrabber::get_all_products_from_costco();
  //dd("Done");
});


Route::get('doc_test', function ()
{
  $meter_subsidiary_model = MeterSubsidiary::find(4);
  $period = MeterSubsidiary::get_subsidy_period($meter_subsidiary_model['starting_date'],$meter_subsidiary_model['ending_date']);
  $tempModel = new MeterPaymentReceived();
  echo $period."<br>";
  for($i = 0 ; $i < $period ; $i++){

    $month_year  = date('Y-m', strtotime("+".$i." months", strtotime($meter_subsidiary_model['starting_date'])));
    $implement_date = $month_year.'-'.$meter_subsidiary_model['implementation_date'];
    $doc_series = $meter_subsidiary_model['code'].'-'.$month_year."/";
    $doc = $tempModel->gen_document_no_by_doc_series($doc_series);
    echo "a:".$month_year." b:".$implement_date.' c:'.$doc_series." d:".$doc."<br>";
  }
  dd("end");
});




Route::get('webGrabber_ego888', function ()
{
  Setting::set_company(282);
  $meter_register_model = new MeterRegister();
  $model = MeterReading::find(750427);

  if ($model->id) {
                Setting::set_company(282);
                if(Company::get_group_id() != 282){
                     Setting::set_company(282);
                }
                $meter_register_model = MeterRegister::find($model->meter_register_id);
                $meter_register_model['last_reading_at'] = date('Y-m-d h:i:s', strtotime('now')) ;
        $meter_register_model['last_reading'] = date('Y-m-d h:i:s', strtotime('now')) ;
                $meter_register_model->update();
                dd($meter_register_model);
          dd("!");
        }

        dd("2");
  //Ego888WebGrabber::get_all_products_from_ego888();
  //dd("Done");
});



Route::get('updatePowerMeterSummary_pre', function ()
{
   //$c_list = Customer::all();
   $leaf_api = new LeafAPI();
   $now = new DateTime();
   $house_list = $leaf_api->get_houses();
   foreach ($house_list['house'] as $house){
    CustomerPowerUsageSummary::update_or_save_customer_summary_by_leaf_house($house);
   }


   dd('Done');

   foreach ($c_list as $row) {
    $return = CustomerPowerUsageSummary::check_is_need_to_update_by_id_house_member($row['id_house_member']);
    echo $row['name'].'update: '.$row['updated_at']."=".$return.'<br>';
    if($return == true){

      CustomerPowerUsageSummary::update_or_save_customer_summary_by_leaf_member_id($row['id_house_member']);
      echo "Data in <br> <br> <br>";
    }
  }
  dd("done");



  foreach ($c_list as $row) {
     $diff_in_second = $now->getTimestamp() - $row['updated_at']->getTimestamp();
         echo $row['name'].'update:'.$row['updated_at']."-Time different : ".($diff_in_second/3600/24)."<br>";
  }
  dd("Done");
  CustomerPowerUsageSummary::update_customer_power_usage_summary(282);
  dd("Done");
});




Route::get('updatePowerMeterSummary', function ()
{
  $c_list = Customer::all();
   $now = new DateTime();

   foreach ($c_list as $row) {
    $return = CustomerPowerUsageSummary::check_is_need_to_update_by_id_house_member($row['id_house_member']);
    echo $row['name'].'update: '.$row['updated_at']."=".$return.'<br>';
    if($return == true){

      CustomerPowerUsageSummary::update_or_save_customer_summary_by_leaf_member_id($row['id_house_member']);
      echo "Data in <br> <br> <br>";
    }
  }
  dd("done");



  foreach ($c_list as $row) {
     $diff_in_second = $now->getTimestamp() - $row['updated_at']->getTimestamp();
         echo $row['name'].'update:'.$row['updated_at']."-Time different : ".($diff_in_second/3600/24)."<br>";
  }
  dd("Done");
  CustomerPowerUsageSummary::update_customer_power_usage_summary(282);
  dd("Done");
});


Route::get('updatePowerMeterSummary_2', function ()
{
  $datetime = new DateTime('-2 hours');
  $starting_date_time = new DateTime('now');
  $ending_date_time = new DateTime('-6 hours');
  echo $starting_date_time->format("Y-m-d H:i:s")."=".$ending_date_time->format("Y-m-d H:i:s");
  $period['starting_date_time'] = $ending_date_time;
  $period['ending_date_time'] = $starting_date_time;

  dd($period['starting_date_time']->diff($period['ending_date_time'])->h);
  $listing = MeterReading::get_reading_in_period($period);

  foreach ($listing as $row) {
    echo $row['meter_register_id']."=".$row['created_at']."<br>";
  }

  dd("Done");
  MeterReading::get_meter_reading_time_frame($listing);
  CustomerPowerUsageSummary::update_customer_power_usage_summary(282);
  dd("Done");
});



//latest
Route::get('user_payment_patch_test', function ()
{
  $leaf_api  = new LeafAPI();
  $u_transaction_listing = UTransaction::update_utransaction_by_current_day_interval_leaf_user_id_or_all(5,16363);
  dd("done");
});



Route::get('patchingTest', function ()
{
  Customer::customer_patching_from_leaf_member_by_leaf_group_id(282);
});

Route::get('updateListTest', function ()
{
  LeafAPI::get_house_by_house_id(33995);
  dd("end");
  $now = new DateTime();
  $leaf_api = new LeafAPI();
  $customer_listing = Customer::all();
  $member_listing = array();
  $return = ['non_exist_member_listing' => array(),
        'existing_member_listing' => array(),
        'updated_member_listing' => array()];

  $houses = $leaf_api->get_houses();
  if($houses['status_code'] == true){
    $houses = $houses['house'];
  }
  
  foreach ($houses as $house) {

    foreach ($house['house_rooms'] as $room) {
      foreach ($room['house_room_members'] as $member) {
        dd($member);
        $temp = $member;
        $temp['id_house_room'] = $room['id_house_room'];
        $temp['id_house'] = $house['id_house'];
        array_push($member_listing, $temp);
      }
    }
  }

    $customer_id_house_member_listing = array_column($customer_listing->toArray(),'id_house_member');
    $id_house_member_listing = array_column($member_listing,'id_house_member');

    foreach ($member_listing as $member) {

    if(!in_array($member['id_house_member'],$customer_id_house_member_listing)){
      array_push($return['non_exist_member_listing'], $member);
    }else{
      foreach ($customer_listing as $customer_model) {

        if($customer_model['id_house_member'] == $member['id_house_member'] && $customer_model['id_house'] == $member['id_house'] && $customer_model['leaf_room_id'] == $member['id_house_room']){
          array_push($return['existing_member_listing'], $member);
        }else if($customer_model['id_house_member'] == $member['id_house_member']){
  
          array_push($return['updated_member_listing'], $member);
        }

      }
      
    }
  }
  
  dd($return);
});



Route::get('getMeterUpdateMonthly', function ()
{
  $date_range = '';
  MeterReadingMonthly::save_all_monthly_meter_reading_by_leaf_group_id(282,$date_range);             
});

Route::get('getMeterUpdate', function ()
{
  $result;
        $fdata = [
                    'status_code'   =>  0,
                    //'status_msg'    =>  Language::trans('Data not yet update.'),
                    'data'   =>  [],
                    ];
        
        $date_range = ['date_started' => date('Y-m-d', strtotime('- 600 day',  strtotime('now'))) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
        //dd($date_range);
        //if($request->input('leaf_group_id') !== null){
            MeterReadingDaily::save_daily_meter_reading_by_leaf_group_id(282,$date_range);        
            $fdata['status_code']   =   1;
           // $fdata['status_msg']    =   Language::trans('Data was update.');
        //}
        return json_encode($fdata);
});

Route::get('getMeterTestList', function ()
{
  $date_range = ['date_started' => '2018-06-06' ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
  $listing = MeterReading::get_daily_meter_reading_by_meter_register_id(21 , $date_range);
  foreach ($listing as $row) {
    echo  date('Y-m-d',strtotime($row['created_at']))."=".$row['total_usage'].'<br>';
  }
  dd("Done");
});

Route::get('getMeterDailyTestList', function ()
{
  $date_range = ['date_started' => '2018-06-06' ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
  $listing =  MeterReadingDaily::where('meter_register_id','=',21)
                                    ->whereBetween('record_date', [$date_range['date_started'], $date_range['date_ended']])
                                    ->select("total_usage", "record_date")
                                    ->get();
   foreach ($listing as $row) {
    echo $row['record_date']."=".$row['total_usage'].'<br>';
  }
  dd("Done");
});



Route::get('checkDetail', function ()
{ 
  $d1=  "2019-06";
  $d2 = "2019-04";
  $mins =   strtotime($d2) -  strtotime($d1);
  dd($mins);

  $leaf_api  = new LeafAPI();
  $leaf_api->get_member_detail_by_member_id(314312);
  dd("Done");
});



Route::get('testD', function ()
{ /* MeterPaymentReceived::get_remaining_subsidy_member_id_by_meter_subsidiary_id(14);
  dd("Stop");*/
  dd(MeterPaymentReceived::get_meter_payment_received_by_meter_register_id(329));
  dd(MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id(14740 ,329));
  
  dd("end");
});


Route::get('getList', function ()
{

  MeterReadingDaily::update_today_record_by_leaf_group_id(282);
    dd("Done");
  dd(MeterReadingDaily::save_daily_meter_reading_by_leaf_group_id(282));
  dd(LeafAPI::get_room_by_leaf_room_id(96));

  //dd($leaf_api->get_customer_list());
  $return_list = $leaf_api->get_customer_list();
  //dd($house_listing['house']);//['house_subgroup']
  //dd($return_list['house'][0]);
  $house_listing = $return_list['house'];
  //dd(array_unique($house_listing['house']));
  $sub_group_list = array_unique(array_column($house_listing, 'house_subgroup'));
  //dd($sub_group);

  foreach ($sub_group_list as $sub_group) {
    print_r($sub_group);
    echo "===============================<br>";

    foreach ($house_listing as $house) {
      //dd($house['house_unit']);
      print_r($house['house_unit']);
      echo "===============================<br>";
      foreach ($house['house_members'] as $member) {
        
        print_r($member['house_member_name']);
        echo "<br>";
      }
    }
  }
  dd(PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user(15151));
});


Route::get('getList2', function ()
{
  $date_range = ['date_started' => '2018-1-12' ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
  dd(MeterReadingDaily::get_consumption_summary_by_leaf_room_id_and_date_range(35,$date_range));
});


Route::get('getList3', function ()
{
  MeterReadingDaily::save_daily_meter_reading_by_leaf_group_id(282);
  dd("Done");
});

Route::get('getDocument', function ()
{

   $product = Product::get_product_by_leaf_id(55);
   dd($product->uom);
  $src = "C:\\xampp\htdocs\opencart\admin\language\\en-gb\common\common";
  dd(OpencartLanguageTranslator::get_current_language_root_directory($src));
  $temp_arr = explode('\\', $src);
  $occurance_count = 0;
  $counter = 0;
  $repeated_key = "";
  $return = "";

  foreach ($temp_arr as $key ) {
    if(strcmp($key, $temp_arr[count($temp_arr)-1]) == 0){
      $occurance_count ++;
      if($occurance_count > 1){
        $repeated_key = $key;
      }
    }
  }

  if($occurance_count > 1){
    unset($temp_arr[count($temp_arr)-1]);
    dd($temp_arr);
  }


  foreach ($temp_arr as $key ) {
    $return = $counter == 0 ? $return : $return.'\\'.$key;
    $counter++;
  }

  dd($return);


  dd(DateTime::createFromFormat('!m', 1));
  //OpencartLanguageTranslator::DEFAULT_PATH;
  dd(env('DB_HOST'));
  $yourdir = env('APP_ENV');
  dd($yourdir);
  $local = 'C:\xampp\htdocs\opencart\admin\language';
  $local_file = $local."\ar-sa\\extension\shipping\purpletree_shipping.php";


  $src = "C:\\xampp\htdocs\opencart\admin\language\\en-gb\common";
  $desc = "C:\\xampp\htdocs\opencart\admin\language\ar-sa\common";
  FileIOHelper::recursive_copy($src,$desc,0755);
  dd("end");
  dd(OpencartLanguageTranslator::get_oc_language_list());
  $local = 'C:\xampp\htdocs\opencart\admin\language';
  $local_file = $local."\ar-sa\\extension\shipping\purpletree_shipping.php";

  dd(OpencartLanguageTranslator::replace_directory_path_language($local_file,"english"));
  $file = file_get_contents($local_file);
  $needle_parameter = '$_[';
  $lines = preg_split('/\n|\r\n?/', $file);
  $selected = array();
  foreach ($lines as $key) {
    if(strpos($key, $needle_parameter) !== false){
      array_push($selected, $key);
    }
  }
  //dd($selected);

  foreach ($selected as $key) {
    $temp = explode("=", $key);
    foreach ($temp as $key) {
      if(strpos($key, $needle) !== false){
        $element = str_replace("'",'',str_replace(']','',str_replace($needle, '', $key)));
        echo $element;
      }else{
        echo $element;
      }
    }
  }

  dd("end");
  $listing = FileIOHelper::get_all_sub_directory_content($local);
  dd($listing);







  $handle = opendir($local);
  $i = 0;
  $language_arr = array();

  //array_push($laganguage_arr , $language);
  while (false !== ($entry = readdir($handle))) {
        //echo $local."\\"."$entry\n";
        $language = $entry;
        array_push($language_arr , $language);
        /*$temp = $local."\\".$entry;
        $local_language_handle = opendir($temp);
        while (false !== ($languege_folder_entry = readdir($local_language_handle))) {
          
          $i++; 
        }*/
    }
   
    closedir($handle);


    $files2 = scandir($local, 1);
    $files3 = array_slice(scandir($local), 2);
  dd($files3);
});


Route::get('testRtoI', function ()
{
  $payment_received_model = ARPaymentReceived::find(35);
  //dd($payment_received_model->items);
  //dd($payment_received_model->customer);
  $product;
  $invoice_item_listing = new ARInvoice();
  $payment_received_items = $payment_received_model->items ;
  $invoice_item = $payment_received_model->items ;
  foreach ($payment_received_items   as $payment_received_item) {
    $temp = new ARInvoiceItem();
    foreach ($payment_received_item   as $pri_key => $pri_value) {
      if($pri_key == 'product_id'){
        $product = Product::find($pri_value);
        foreach ($product->getAttributes() as $p_key => $p_value) {
          foreach ($invoice_item  as $i_key => $i_value) {
            if(strcmp($i_key, $p_key) == 0){            
              $temp[$i_key] = $p_value;
              break;
            }
          }
        }
      }
      array_push($invoice_item_listing, $temp);
    }
  }

  dd('stop');
  //dd($payment_received_model->getAttributes());
  $invoice = new ARInvoice();
  $columns = DB::getSchemaBuilder()->getColumnListing('ar_invoices');
  $remaining_columns = DB::getSchemaBuilder()->getColumnListing('ar_invoices');
  //dd($columns);
  $counter =0 ;
  $customer = $payment_received_model->customer;
  foreach ($columns as $index => $column) {
    foreach ($payment_received_model->getAttributes()  as $pr_key => $pr_value) {
      //dd($column.$pr_key."-".$pr_value);
      //dd(strcmp($column, $pr_key));
      if(strcmp($column, $pr_key) == 0){
        
        if($column =='document_no'){
          $invoice['document_no'] == $invoice->gen_document_no();
        }else if($column == 'created_at' ||$column == 'created_by' ){
          //update if necessary
          $invoice[$column] = $pr_value;
        }else if($column == 'id' || $column == 'ncl_id'){
        }else{
          $invoice[$column] = $pr_value;
        }
        //dd("s");
        /*if (($index = array_search($key, $mandatory_columns_check_by_id)) !== false) {*/
           // unset($remaining_columns[$index]);
          // print_r($counter);
        //}
            break;
      }
    
    }

    foreach ($customer->getAttributes()  as $c_key => $c_value) {

      if(strcmp($column, $c_key) == 0){
        
        if($column == 'created_at' ||$column == 'created_by' ){

        }else if($column == 'id' || $column == 'ncl_id'){
        }else{
          $invoice[$column] = $c_value;
        }
      break;
      }
    }
  }

    $invoice['outstanding_amount'] = 0;
    $invoice['assign_credit'] = $payment_received_model['payment_amount'];


    $invoice['remark'] =  "" ;
    $invoice['payment_term_id'] = 0 ;
    $invoice['payment_term_code'] = "" ;
    $invoice['payment_term_days'] =  "";
    $invoice['due_date'] =  "";

    $invoice['phone_no'] = $customer['phone_no_1'];
    $payment_received_model['reference_no'] = $invoice['document_no'];
    $payment_received_model->save();
$invoice->save();
  dd($invoice);
  


  });

Route::get('testPaymentReceipt', function () {
  
  $fileUrl =  asset('utility_charges_doc/utility_charges.html');
  $fileContent = file_get_contents( $fileUrl ) ;

  $prorated_rate = "1.00";
  $fare_type = "Residential";
  $billing_period = "s";
  $user_profile['id'] =  '2';
  $user_profile['leaf_id_user'] = '2';
  $user_profile['account_no'] =  '2';
  //$user_profile['account_no'] = "ds";
  $user_profile['phone_number'] =  '2';
  $user_profile['fullname'] =  '2';
  $user_profile['email'] =  '2';
  $user_profile['address'] =  '2';
  $user_profile['contact_no'] =  '2';

  $customer['name'] = "Aaron Goh";
  $invoice['fare'] =  "temp";
  $invoice['total_collateral'] =  "temp";
  $invoice['invoice_no'] =  "temp";
  $invoice['billing_date'] =  date("d-m-Y");
  $invoice['billing_end_date'] =  date("d-m-Y");
  $invoice['billing_due_date'] =  date("d-m-Y");
  $invoice['billing_amount_due'] =  "RM 700.00";
  $invoice['outstanding_balance'] =  "RM 700.00";
  $rounding_up = "10";
  $invoice['current_charge'] =  "RM 700.00";
  $invoice['prorated_block'] = "";
  $invoice['prorated_block_amount'] =  "";
  /*$invoice['
  $invoice['*/

  $meter_reading['meter_no'] = "123";
  $meter_reading['current_meter_reading'] = "500";
  $meter_reading['previous_meter_reading'] = "400";
  $meter_reading['usage'] = $meter_reading['current_meter_reading']-$meter_reading['previous_meter_reading']  ;

  $meter_reading['unit_name'] = "kWh";


  $invoice['reading_type'] = "Actual Reading";

  $invoice_title = "Title" ;


    $customer_data = $user_profile['fullname']."<br>".$user_profile['address']."<br>";

    //bill converter
   

    $billing_item_model = array_reverse(Setting::generate_billing_charges_item(575));
//dd($billing_item_model);
    $billingItemHtml ="";
  $sunway_logo = Setting::get_sunway_logo_path();




    $header = "<div class='block1'>
        <div class='color_div purple'>ElECTRIC BILL AND TAX INVOICE</div>
        <br>
        <br>
        <img src=".$sunway_logo." style='height:50px;width:250px;margin:0px 0px 10px 0px;'>
        <!-- <small> <font size='1'> Pay/Manage your account through our app</font></small> -->
    </div>";


    $user_profile_div_2 = "<hr style='height:3px;color:#097d8c;border:none;background-color:#097d8c;'>
                <div class='color_div purple'>Your Electric Usage Profile</div>
                <br>
                    <br>Account Number : ".$user_profile['account_no']."
                            <br> Contract Number : ".$user_profile['contact_no']."
                            <br> Invoice Number : ".$invoice['invoice_no'];


  $user_profile_div = "<hr style='height:3px;color:#097d8c;border:none;background-color:#097d8c;'>

  <table>
    <tr>
      <td class='width_50p'><div class='color_div purple'>Your Electric Usage Profile</div><br></td> 
      <td class='width_50p'><div class='color_div purple'>Service To</div><br></td>
    </tr>

    <tr>
      <td>Account Number : ".$user_profile['account_no']."</td>
      <td>".$user_profile['fullname']."</td>
    </tr>
    
    <tr>
      <td>Contract Number : ".$user_profile['contact_no']."
      </td>
      <td rowspan='2'>".$user_profile['address']."</td>
    </tr>
    
    <tr>
      <td>Invoice Number : ".$invoice['invoice_no']."
      </td>
    </tr>
  </table>";

$payment_summary_table = "<table id='billing_table' style='width:700px'>
                      <tr>
                          <th rowspan='2' width='70%;'>Amount payable</th>
                          <th>Billing Date</th>
                      </tr>
                      <tr>
                          <th>".$invoice['billing_date']."</th>
                      </tr>
                  </table>";


        

  $payment_summary_table_payment_detail = "<table id='customers' style='width:700px;'>
        <tr>
            <td class='width_40p'></td>
            <td class='width_30p'>Amount (RM)</td>
            <td class='width_30p'>Pay Before</td>
        </tr>
        <tr>
            <td>Outstanding Balance</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Current Charges</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Rounding Up</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Total Bill</td>
            <td></td>
            <td>".$invoice['billing_due_date']."</td>
        </tr>

    </table>";




  $payment_item_table = "<table id='customers' style='width:700px;'>
        <tr>
            <td class='width_40p'></td>
            <td class='width_30p'>Amount (RM)</td>
            <td class='width_30p'>Date</td>
        </tr>
        <tr>
            <td>Previous Bill</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Recent Payment</td>
            <td></td>
            <td></td>
        </tr>

    </table>";


    $reading_type_table = "<table id='customers' style='width:700px;'>
        <tr>
            <td class='width_40p'>Reading Type</td>
            <td>".$invoice['reading_type']."</td>

        </tr>
    </table>";

    $billing_period_table = "<table id='billing_table' style='width:700px'>
        <tr>
            <th rowspan='2' class='width_70p'>Billing Period : ".$billing_period."
                <br> Fare : ".$fare_type."</th>
            <th rowspan='2'> Prorated Factor
                <br>".$prorated_rate."</th>
        </tr>
        <tr>

        </tr>
    </table>";


$billing_item_model = array_reverse($billing_item_model,true);
foreach($billing_item_model as $item){
  
  $billingItemHtml = $billingItemHtml."<tr>
                            <td>".$item["consumption_block"]."</td>
                            <td>".$item["prorated_block"]."</td>
                            <td>".$item["rate"]."</td>
                            <td>".round($item["amount"],2)."</td>
                        </tr>";
}
  


    $consumption_detail_table = "<table id='customers' style='width:700px;'>
                    <tr>
                        <td class='width_40p'>Consumption Block (kWh)</td>
                        <td class='width_20p'>Prorated Block (kWh)</td>
                        <td class='width_20p'>Rate (RM)</td>
                        <td class='width_20p'>Amount (RM)</td>
                    </tr>".$billingItemHtml."
                  
                    </table>";

        
/*    <tr>
                        <td class='width_40p'>Consumption Block (kWh)</td>
                        <td class='width_20p'>Prorated Block (kWh)</td>
                        <td class='width_20p'>Rate (RM)</td>
                        <td class='width_20p'>Amount (RM)</td>
                  </tr>*/

    $consumption_gst_detail_table = 
    "<table id='customers' style='width:700px;'>
        <tr>
            <td class='width_40p'>Explanation</td>
            <td class='width_20p'>No GST Charged</td>
            <td class='width_20p'>GST Charged</td>
            <td class='width_20p'>Amount (RM)</td>
        </tr>

        <tr>
            <td>Usage kWh</td>
            <td>A</td>
            <td></td>
            <td>B</td>
        </tr>

        <tr>
            <td>Usage</td>
            <td>A</td>
            <td></td>
            <td>B</td>
        </tr>

        <tr>
            <td>Current Month Consumption</td>
            <td>6% GST</td>
            <td>Funds for Renewable Energy</td>
            <td>Penalty</td>
        </tr>

        <tr>
            <td>Current charges</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>";


  $meter_reading_table = " <table id='billing_table' style='width:700px;'>

        <tr>

            <th   rowspan='2' colspan='2' class='width_20p'>Meter Number</th>
            <th  colspan='2' class='width_40p' style='text-align: center;'>Meter Reading</th>
            <th   rowspan='2' colspan='2' class='width_20p'>Usage</th>
            <th   rowspan='2' colspan='2' class='width_20p'>Unit</th>
        </tr>

        <tr>
            <th>Current Reading</th>
            <th>Previous Reading</th>
        </tr>

        <tr>
            <td colspan='2'>".$meter_reading['meter_no']."</td>
            <td>".$meter_reading['previous_meter_reading']."</td>
            <td>".$meter_reading['current_meter_reading']."</td>
            <td colspan='2'>".$meter_reading['usage']."</td>
            <td colspan='2'>".$meter_reading['unit_name']."</td>
        </tr>
    </table>";




    $fileContentA= str_replace("header",$header,$fileContent);
    $fileContentB= str_replace("user_profile_div",$user_profile_div,$fileContentA);
    $fileContentC= str_replace("payment_summary_table_payment_detail",$payment_summary_table_payment_detail,$fileContentB);
    $fileContentD= str_replace("payment_summary_table",$payment_summary_table,$fileContentC);
  $fileContentE= str_replace("reading_type_table",$reading_type_table,$fileContentD);
  $fileContentF= str_replace("billing_period_table",$billing_period_table,$fileContentE);
  $fileContentG= str_replace("consumption_detail_table",$consumption_detail_table,$fileContentF);
  $fileContentH= str_replace("consumption_gst_detail_table",$consumption_gst_detail_table,$fileContentG);
  $fileContentI= str_replace("meter_reading_table",$meter_reading_table,$fileContentH);
  $fileContentJ= str_replace("payment_item_table",$payment_item_table,$fileContentI);
  $fileContentK= str_replace("invoice_title",$invoice_title,$fileContentJ);
  $pdf = App::make('dompdf.wrapper');
  
  $pdf->loadHTML($fileContentK);

  return $pdf->stream();
  
  // $user['amount'] = '1.00';
  // $user['document_no'] = '180425/001';
  // return view('utility_charges.emails.payment_receipt', compact('user'));
});




Route::get('testUpdate', function(){
//$a = new ARInvoice();
  dd(MeterReading::get_group_last_update_time_by_leaf_group_id(282));
  $c= new Company();
  $c->set_group_id(282);
  dd("s");
  $model = "App\MembershipModel\ARInvoice";

dd($model::get_module_name($model));
dd(OperationRule::get_module_operation_status_by_leaf_group_id());



$model = "App\Product";
$a= $model::where('status','=' , 'true')
  ->get()->count();
dd($a);
  $ar= new ARPaymentReceived();

  $ans = ARPaymentReceived::get_monthly_transaction_data_by_leaf_group_id(285);
  dd($ans);

  $value = "1111-1111-1111-1111";
  dd(Setting::credit_card_masking($value,'*'));

  
  
  $leaf_api  = new LeafAPI();

  $fdata = $leaf_api->get_prepare_payment("test", 4, "ky Goh", 'adelfried1227A@hotmail.com');
dd($fdata);
    if (isset($fdata['payment_page_url'])) {
      return redirect()->to($fdata['payment_page_url']);
    }
    return redirect()->back();


  dd("done");


  $p = new Product();
  $product = Product::where('leaf_group_id' , '=' , 285)->get();

  foreach ($product as $item) {
    $p->mandatory_columns_check_by_id($item['id']);
  }

  

  //dd($leaf_api->post_member_status_update_by_leaf_product_id_and_id_house(57,34485));
  //dd($leaf_api->get_all_leaf_payable_item_model_by_group_id());
  //dd($leaf_api->get_product_by_product_id_and_category(62));

  dd(Currency::get_model_by_code('MYR'));

  $id = 31 ;
  $id_invoice= 21;
  $invoice = ARInvoice::find($id_invoice);
  //dd($invoice->items());
  $receipt = new PaymentReceivedPdf();
  $model = ARPaymentReceived::find($id);
  //dd($model->items);
  $leaf_api->post_membership_status_by_payment_receipt_id($model);

});


Route::get('up_membership', function(){
  
  $leaf_api  = new LeafAPI();
  $model = ARPaymentReceived::find(94);
  $leaf_api->post_membership_status_by_payment_receipt_id($model['id']);
});

Route::get('delete_customer_product_receipt', function(){

    $ar = ARPaymentReceived::all();
    $ar ->truncate();
    printf("receipt deleted--");

    $customer = Customer::all();
    $customer ->truncate();
    printf("customer deleted--");

    $product = Product::all();
    $product ->truncate();
    printf("Product deleted--");

});

Route::get('set_sunway', function(){
  $c= new Company();
  $c->set_group_id(282);
  Setting::setCompany(282);
  $api = new LeafAPI();
  $api->set_cookie_modules();
  print_r("done".Company::get_group_id());
  dd($api->get_modules());
});


Route::get('set_setia', function(){

  Setting::setCompany(285);
  $api = new LeafAPI();
  $api->set_cookie_modules();
  print_r("done".Company::get_group_id());
  dd($api->get_modules());
});


Route::get('sendEmail', function(){

  $email = 'adelfried1227A@hotmail.com';
  $title = "test";
  $html = "content";


    $user = User::find(1);
    //dd($user);
    $user['amount'] = 100;
    $user['document_no'] = '001';
    Mail::send('utility_charges.emails.payment_receipt', ['user'=>$user], function( $message ) use ($user)
    {
        $message->to($user['email'])->subject('[Sunway Medical Centre] Thank you for your payment.');
    });
    dd("end");
        


/*  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTMLFile('https://stackoverflow.com/questions/10921457/php-retrieve-inner-html-as-string-from-url-using-domdocument');*/
/*  $data = $dom->getElementById("banner");
  echo $data->nodeValue."\n"*/
  /*$html = $dom->saveHTML();

  $api = new LeafAPI();
  $api->send_email($email, $title, $html);
  */
  

  // send email to user
  $user = Auth::user();
  $user['email'] = $email;
  $user['amount'] = 100;
  $user['document_no'] = 123;
  dd($user);
  Mail::send('utility_charges.emails.payment_receipt', ['user'=>$user], function( $message ) use ($user)
  {
    $message->to($user['email'])->subject('[Sunway Medical Centre] Thank you for your payment.');
  });
         
  dd("done");



  dd($api->get_user_house_membership_detail_by_user_id(8809));
  //$listing = $api->set_product_from_leaf_by_group_id(285);
  //dd("end_x");
    dd($api->get_product_by_product_id_and_category(336));
  
  $product = Product::get_product_by_leaf_id();
  dd($api->set_product_from_leaf_by_group_id(285));
  //dd($api->get_customer_list());
  
  
  //dd($api->get_customer_list_with_update());
  Customer::set_customer_by_id_house_member(8809);
  
  //dd($api->get_all_leaf_payable_item_by_group_id(285));
  $listing = $api->set_product_from_leaf_by_group_id(285);
  foreach($listing as $item){
    
    Product::save_product_from_leaf($item);
  }

/*  $data = array(
        'name'=> $request->name,
        'email'=> $request->email,
        'text'=> $request->text,
        'category'=> $request->category,
        'company'=> $request->company,
        'number'=> $request->number
    );
    $files = $request->file('files');


    \Mail::send('AltHr/Portal/supportemail', compact('data'), function ($message) use($data, $files){    
        $message->from($data['email']);
        $message->to('nuru7495@gmail.com')->subject($data['company'] . ' - ' .$data['category']);

        if(count($files > 0)) {
            foreach($files as $file) {
                $message->attach($file->getRealPath(), array(
                    'as' => $file->getClientOriginalName(), // If you want you can chnage original name to custom name      
                    'mime' => $file->getMimeType())
                );
            }
        }
    });*/
});


Route::get('receipt', function(){
// header of the listing
  $c= new Company();
  $c->set_group_id(285);

  $id = 31 ;
  $receipt = new PaymentReceivedPdf();
  $model = ARPaymentReceived::find($id);
  $modelX = ARInvoice::find($id);
  //dd($model->items());
  //$listing = ARPaymentReceivedItem::where('ar_payment_received_id' , '=' , $model['id'])->get();
  //dd($listing);
  dd($model->items());
   if (!$model = ARPaymentReceived::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        $company        =   new Company();
        $pdf            =   new PaymentReceivedPdf();
        $pdf->setting   =   new Setting();
        $pdf->document  =   $model;
        $pdf->header_data   =   $pdf->getHeaderFromCompanyModel();


        $pdf->SetMargins(5,5,5);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 75);
        $pdf->AddPage('P','Letter');
        $i=1;
      $pdf->content($model);

        return response($pdf->Output(), 200)
                     ->header('Content-Type', 'application/pdf');


});




Route::get('checkHouse', function(){
  ini_set('max_execution_time', 300);
  $hid = 34485;
  $mR = new MeterReading();
  $c= new Company();
  $c->set_group_id(282);
  $leaf_api  = new LeafAPI();

  $result = $leaf_api->get_room_meter_by_leaf_room_id();
  dd($result);
  $houses =$leaf_api->get_houses_with_meter_register_detail();
  //dd($houses[0]);
  $content = \View::make('utility_charges.meter_registers.meter_pairing.blade.php')->with('houses', $houses);
    return \Response::make($content, '200')->header('Content-Type', 'plain/txt');



  dd(LeafAPI::get_house_by_house_id(33983));

  $meter = MeterRegister::get_meter_register_by_leaf_room_id(205);
  dd($meter);

  dd($mR->get_last_meter_reading_update(155));
  $list = MeterReading::get_meter_created_at_by_meter_id(155);
  dd(get_last_meter_reading);
  foreach($list as $item){
    echo(date('h:00 A', strtotime($item['created_at']))."<br>");

  }

  dd("end");
  dd(LeafAPI::get_house_by_house_id(34015));
  $api = new LeafAPI();
  $customer = new Customer;
  $houses = $api->get_customer_list();
  //dd($houses);
  //dd($api->get_all_member_since_last_update());
  $house = array();
  $customer->save_customer_from_leaf_house($house);
  
  foreach ($houses['house'] as $house) {
    $customer->save_customer_from_leaf_house($house);
  }
  dd("done");
  ($customer->get_user_house_by_user_id(2701));
  //dd($api->get_customer_list());
  dd($api->get_user_house_by_user_id());
  //dd($api->get_all_leaf_payable_item_combobox($hid));
  dd($api->get_house_membership_detail_by_house_id($hid));
  
});



Route::get('testPayment', function(){
  $api = new LeafAPI();
  $c= new Company();
  $c->set_group_id(285);
  dd($api->get_customer_list());
   $payment_detail['id_fee_type'] = 57;
   $payment_detail['expire_date'] = "2018-10-02";
    $payment_detail['id_house'] = 34485;

  dd($api->post_member_status_update($payment_detail));
});

Route::get('testA', function(){
  ini_set('memory_limit', '4024M');
   $c= new Company();
  $c->set_group_id(282);
    $date_range   = array('date_started' => '2018-05-01' ,'date_ended' =>  date('Y-m-d', strtotime('now')));
  $id= 68;
  $customer_id=16117;

  dd(MeterPaymentReceived::getUserBalanceCreditBLeafRoomIdAndDateRange($id , $date_range , $customer_id));
});


Route::get('testpdf2', function(){

    $company= new Company();
    $company->set_group_id(285);
   $payment_received_listing = ARPaymentReceived::find(21);
   dd($payment_received_listing['customer_name']);

    $pdf            =   new PaymentReceivedRPdf();
        $pdf->setting   =   new Setting();
        $pdf->document  =   $payment_received_listing;
        $pdf->company   =   $company->self_profile();   
        $pdf->Content($payment_received_listing); 
        $pdf->SetMargins(5,5,5);
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 75);
        $pdf->AddPage('P','Letter');
        $i=1;

        return response($pdf->Output(), 200)
                     ->header('Content-Type', 'application/pdf');


});


Route::get('testpdf', function(){
// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->set_option('enable_remote', TRUE);

$dompdf->set_option('enable_css_float', TRUE);
$dompdf->set_option('enable_html5_parser', FALSE);
//$dompdf->loadHtml('hello world');
$dompdf->load_html_file("C:\Users\KyGoh\Desktop\Documents\project\php development\AdminLTE-2.4.2\AdminLTE-2.4.2\pages\examples\invoice.html");
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();
$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
// Output the generated PDF to Browser
$dompdf->stream();
});



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Using peter's email to login for admin
Route::get('peter_profile', 'AuthsController@getAdmin');
// Using peter's email to login for admin
Route::get('sunway_tester', 'AuthsController@getSunwayTester');

// Author's Modules
Route::get('login', 'AuthsController@getLogin');
  
Route::get('check_login', 'AuthsController@getCheckLogin');
Route::get('logout', 'AuthsController@getLogout');

// Dashboard url
Route::get('','AppsController@getDashboard');

// Umrah's Module
Route::prefix('umrah')->group(function(){
  Route::get('', 'AppsController@getIndex');
  Route::get('stores','AppsController@getStores');
  Route::get('vouchers/{store_id}','AppsController@getVouchers');
  Route::get('voucher-detail/{id}','AppsController@getVoucherDetail');
  Route::get('voucher-claim', 'AppsController@getVoucherClaim');
  Route::get('to-do-list/categories', 'AppsController@getToDoListCategories');
  Route::get('to-do-lists', 'AppsController@getToDoLists');
  Route::get('to-do-list/view/{id}', 'AppsController@getToDoListView');
  Route::get('to-do-list/checked/{id}', 'AppsController@getToDoChecked');
  Route::get('map', 'AppsController@getMap');
});





Route::group([
'middleware' => ['auth', 'auth_admin'],
'prefix'     => 'admin',
], function () {

//Route::prefix('admin')->group(function () {

  /*************** General Router ***************/

  Route::get('', 'DashboardsController@getIndex');
  Route::get('user-profile', 'DashboardsController@getUserProfile');

  Route::get('mobile/membership/user-profile', 'DashboardsController@getUserProfileMobile');

  Route::get('dashboard/dashboard-data', 'DashboardsController@getDashboardData');


  Route::get('credit/listing', 'DashboardsController@getCreditListing');
  Route::get('customer/power/usage/summary', 'DashboardsController@getCustomerPowerUsageSummary');
  Route::get('reports', 'DashboardsController@getReports');
  Route::get('errors/{folder}/{error}', 'DashboardsController@getError');
  Route::get('versions', 'VersionsController@getIndex');
  Route::get('versions/new/resource', 'VersionsController@getResourcesUpdate');


  // languages indexing
  Route::get('languages', 'LanguagesController@getIndex');
  Route::post('languages', 'LanguagesController@postIndex');
  Route::get('languages/indexing', 'LanguagesController@getIndexing');
  Route::get('set-languages', 'LanguagesController@getLanguage');

  // latest version with combine feature
  Route::get('dashboard/general', 'DashboardsController@getDashboard');
  Route::get('dashboard', 'IOTUniversalsController@getDashboard');
  Route::get('dashboard/count', 'DashboardsController@getDashboardCount');
  Route::get('dashboard/latest/power-usage-summary', 'DashboardsController@getLastestPowerUsageSummary');
  Route::get('settings', 'SettingsController@getIndex');
  Route::get('settings/upddate-selected-group', 'SettingsController@updateSelectedGroup');
  Route::post('settings', 'SettingsController@postIndex');


  /*************** Invoicing & Inventory Router ***************/
  Route::get('device/get-line-chart', 'IOTUniversalsController@getLineChart');

  Route::get('iot/data', 'IOTUniversalsController@getTableData');

  Route::get('device/iot-summary-data', 'IOTUniversalsController@getIotSummaryData');
  Route::get('device/iot-chart-data', 'IOTUniversalsController@getDashboardChartData');
 
  Route::get('redirect-page/{request_type}', 'IOTUniversalsController@getIndex');
  Route::get('page/redirect/device/{device_id}', 'IOTUniversalsController@getDeviceInfo');

  Route::get('wp-label-data', 'IOTUniversalsController@getWPLabelData');
  Route::get('wp-graph-data', 'IOTUniversalsController@wpGraphConverter');
  Route::get('testWpLabel', 'IOTUniversalsController@getTestWpConverter');
  Route::get('testWpGraphHumidity', 'IOTUniversalsController@getTestWpConverter');
  Route::get('testWpGraphTemperature', 'IOTUniversalsController@getTestWpConverter');
  Route::get('getWpDashbaordData', 'IOTUniversalsController@getWPDashboardLabelData');
  Route::get('testWpGraphOnOff', 'IOTUniversalsController@getTestWpConverterOnOff');
  
  
  


  // users crud & listing
  Route::get('users', 'UsersController@getIndex');
  Route::post('users/new', 'UsersController@postNew');
  Route::get('users/edit/{id}', 'UsersController@getEdit');
  Route::post('users/edit/{id}', 'UsersController@postEdit');
  Route::get('users/view/{id}', 'UsersController@getView');
  Route::get('users/delete/{id}', 'UsersController@getDelete');

  // user/groups crud & listing
  Route::get('user/groups', 'UserGroupsController@getIndex');
  Route::get('user/groups/new', 'UserGroupsController@getNew');
  Route::post('user/groups/new', 'UserGroupsController@postNew');
  Route::get('user/groups/edit/{id}', 'UserGroupsController@getEdit');
  Route::post('user/groups/edit/{id}', 'UserGroupsController@postEdit');
  Route::get('user/groups/view/{id}', 'UserGroupsController@getView');
  Route::get('user/groups/delete/{id}', 'UserGroupsController@getDelete');

  // countries crud & listing
  Route::get('countries', 'CountriesController@getIndex');
  Route::get('countries/new', 'CountriesController@getNew');
  Route::post('countries/new', 'CountriesController@postNew');
  Route::get('countries/edit/{id}', 'CountriesController@getEdit');
  Route::post('countries/edit/{id}', 'CountriesController@postEdit');
  Route::get('countries/view/{id}', 'CountriesController@getView');
  Route::get('countries/delete/{id}', 'CountriesController@getDelete');


  // cities crud & listing
  Route::get('cities', 'CitiesController@getIndex');
  Route::get('cities/new', 'CitiesController@getNew');
  Route::post('cities/new', 'CitiesController@postNew');
  Route::get('cities/edit/{id}', 'CitiesController@getEdit');
  Route::post('cities/edit/{id}', 'CitiesController@postEdit');
  Route::get('cities/view/{id}', 'CitiesController@getView');
  Route::get('cities/delete/{id}', 'CitiesController@getDelete');
  Route::get('cities/combobox', 'CitiesController@getCombobox');



  // currencies crud & listing
  Route::get('currencies', 'CurrenciesController@getIndex');
  Route::get('currencies/new', 'CurrenciesController@getNew');
  Route::post('currencies/new', 'CurrenciesController@postNew');
  Route::get('currencies/edit/{id}', 'CurrenciesController@getEdit');
  Route::post('currencies/edit/{id}', 'CurrenciesController@postEdit');
  Route::get('currencies/view/{id}', 'CurrenciesController@getView');
  Route::get('currencies/delete/{id}', 'CurrenciesController@getDelete');
  Route::get('currencies/combobox', 'CurrenciesController@getCombobox');
  Route::get('currencies/by/id', 'CurrenciesController@getCurrencyModelById');
   
  // locations crud & listing
  Route::get('locations', 'LocationsController@getIndex');
  Route::get('locations/new', 'LocationsController@getNew');
  Route::post('locations/new', 'LocationsController@postNew');
  Route::get('locations/edit/{id}', 'LocationsController@getEdit');
  Route::post('locations/edit/{id}', 'LocationsController@postEdit');
  Route::get('locations/view/{id}', 'LocationsController@getView');
  Route::get('locations/delete/{id}', 'LocationsController@getDelete');
  Route::get('locations/combobox', 'LocationsController@getCombobox');
  
  
  Route::get('switch-group', 'OpencartUsersController@getSwitchGroup');

  // customers crud & listing
  Route::get('customers', 'CustomersController@getIndex');
  Route::get('customers/new', 'CustomersController@getNew');
  Route::post('customers/new', 'CustomersController@postNew');
  Route::get('customers/edit/{id}', 'CustomersController@getEdit');
  Route::post('customers/edit/{id}', 'CustomersController@postEdit');
  Route::get('customers/view/{id}', 'CustomersController@getView');
  Route::get('customers/delete/{id}', 'CustomersController@getDelete');
  Route::get('customers/info', 'CustomersController@getInfo');
  Route::get('customers/log', 'CustomersController@getLog');
  Route::get('customers/latest', 'CustomersController@getLatest');
  Route::get('customers/details', 'CustomersController@getDetails');

  Route::prefix('umrah')->group(function() {
    // countries crud & listing
    Route::get('', 'DashboardsController@getUmrahIndex');
    Route::get('countries', 'CountriesController@getIndex');
    Route::get('countries/new', 'CountriesController@getNew');
    Route::post('countries/new', 'CountriesController@postNew');
    Route::get('countries/edit/{id}', 'CountriesController@getEdit');
    Route::post('countries/edit/{id}', 'CountriesController@postEdit');
    Route::get('countries/view/{id}', 'CountriesController@getView');
    Route::get('countries/delete/{id}', 'CountriesController@getDelete');

    // cities crud & listing
    Route::get('cities', 'CitiesController@getIndex');
    Route::get('cities/new', 'CitiesController@getNew');
    Route::post('cities/new', 'CitiesController@postNew');
    Route::get('cities/edit/{id}', 'CitiesController@getEdit');
    Route::post('cities/edit/{id}', 'CitiesController@postEdit');
    Route::get('cities/view/{id}', 'CitiesController@getView');
    Route::get('cities/delete/{id}', 'CitiesController@getDelete');
    Route::get('cities/combobox', 'CitiesController@getCombobox');

    // users crud & listing
    Route::get('users', 'UsersController@getIndex');
    Route::get('users/new', 'UsersController@getNew');
    Route::post('users/new', 'UsersController@postNew');
    Route::get('users/edit/{id}', 'UsersController@getEdit');
    Route::post('users/edit/{id}', 'UsersController@postEdit');
  });

});

Route::get('dashboard/charges', 'DashboardsController@getUtilityChargeIndex');

Route::prefix('user')->group(function(){
  // opencart login and operation
  Route::get('login', 'OpencartUsersController@getLogin');
  Route::get('logout', 'OpencartUsersController@getLogout');
  Route::post('login', 'OpencartUsersController@postLogin');

});



