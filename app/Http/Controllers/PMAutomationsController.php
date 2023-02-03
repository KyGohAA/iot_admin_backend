<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Schema;
use Log;
use App\Setting;
use App\Language;
use App\Company;
use App\PowerMeterModel\CustomerPowerUsageSummary;
use App\LeafAPI;
/*use App\Company;
use App\Company;*/

class PMAutomationsController extends Controller
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

        //$this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        //$this->middleware('auth_admin');
    }

    public function getOnOffAutomation()
    {
            Log::debug('Trigger Automation.');
             //power_meter_mailbox_setting

            //return 'abc';
            $default_language  = 'english';
            $company_model = new Company();
            $leaf_api = new LeafAPI();
            $company_model = $company_model->self_profile();
            $backend_data = $company_model->backend_data;
            $power_meter_turn_off_meter_email = json_decode($backend_data['power_meter_turn_off_meter_email'] , true);
            $power_meter_low_credit_reminder = json_decode($backend_data['power_meter_low_credit_reminder'] , true);
            $power_meter_op_setting = json_decode($company_model['power_meter_operational_setting'], true);
            $tester_list = $power_meter_op_setting['uat_tester_list'];
            $power_meter_power_supply_restore_email = json_decode($backend_data['power_meter_power_supply_restore_email'] , true);

            if($power_meter_op_setting['power_supply_on_off_automation'] == false)
            {
               return false;
            }

            if( $power_meter_op_setting['is_in_uat'] == true)
            {
                Log::debug('Currently UAT on-going.');
            }


            if($power_meter_op_setting['is_auto_turn_off_meter'] == true)
            {
                
                //['user_preferred_language'];
                $cpus_listing = CustomerPowerUsageSummary::all();//CustomerPowerUsageSummary::getAllByLeafGroupId($company_model['leaf_group_id']);
                if(count($cpus_listing) > 0)
                {
                    foreach($cpus_listing as $cpus_model)
                    {

                          if( $power_meter_op_setting['is_in_uat'] == true)
                          {
                              
                              if(!in_array($cpus_model['id'] , $tester_list ))
                              {
                                  continue;
                              }else{

                              }

                          }

                          if($cpus_model['leaf_id_user'] == 0){continue;}
                            $user_model = $cpus_model->getCurrentAccountUser();
                            $email = $user_model['email'];
                            echo json_encode($cpus_model)."<br>";
                            echo $email."<br>";

                            $cpus_model->getOrUpdateMeterRegister();
                            $language = $cpus_model->getUserPreferLanguage();
                            $total_balance = $cpus_model['current_credit_amount'];
                            //$total_balance = $cpus_model['current_credit_amount'] + $cpus_model['total_subsidy_amount'];

                            if( $total_balance < $power_meter_op_setting['credit_threshold'] && $cpus_model['is_power_supply_on'] == true){

                                // termination flow
                                Log::debug('Termination Flow.');
                                
                                //echo  ($cpus_model['stop_supply_termination_time'] <= date('Y-m-d H:i:s', strtotime('now')));
                                if($cpus_model['warning_email_number'] >= $power_meter_op_setting['warning_email_number']){
                                  echo 'Termination user <br>';
                                    if(strlen($cpus_model['stop_supply_termination_time']) > 5 )
                                    {

                                          if($power_meter_op_setting['is_auto_turn_off_meter'] == true)
                                          { //dd($cpus_model['stop_supply_termination_time'] <= date('Y-m-d H:i:s', strtotime('now')));
                                        echo 'in termination process <br> <br>';
                                              if( $cpus_model['stop_supply_termination_time'] <= date('Y-m-d H:i:s', strtotime('now'))){
                                                //dd('To stop'. $cpus_model['is_power_supply_on']);
                                                  if($cpus_model['is_power_supply_on'] == true)
                                                    Log::debug('Termination.');
                                                    Log::debug('-------------------------------------------------------------.');
                                                    $cpus_model->terminate_power_supply();
                                                    Log::debug(json_encode($cpus_model));
                                                    Log::debug('-------------------------------------------------------------.');
                                                  }
                                          }

                                    }else{


                                          $email_response = $leaf_api->send_email($email, $power_meter_turn_off_meter_email[$default_language]['title'], $power_meter_turn_off_meter_email[$default_language]['content']);

                                         if($email_response['status_code'])
                                         {
                                              Log::debug('Payment Notification Successfully.');
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
                                    //dd($cpus_model['last_below_credit_notification_email_at'] >= date('Y-m-d H:i:s', strtotime('now')));
                                    if($cpus_model['last_below_credit_notification_email_at'] >= date('Y-m-d H:i:s', strtotime('now')) == false)
                                    {
                                        /*if($cpus_model['below_credit_notification_count'] > $cpus_model['warning_email_number'])
                                        {
                                            $email_response= $leaf_api->send_email($email, $power_meter_low_credit_reminder[$default_language]['title'], $power_meter_low_credit_reminder[$default_language]['content']);
                                            $cpus_model['warning_email_number'] += 1 ;
                                            $cpus_model->save();
                                        }*/

                                        if($cpus_model['below_credit_notification_count'] <= $cpus_model['warning_email_number'])
                                        {
                                            $email_response= $leaf_api->send_email($email, $power_meter_low_credit_reminder[$default_language]['title'], $power_meter_low_credit_reminder[$default_language]['content']);
                                            $cpus_model['warning_email_number'] += 1 ;
                                            $cpus_model->save();
                                        }
                                    }

                                    //notification checker
                                    if($cpus_model['last_below_credit_notification_email_at'] == null || $cpus_model['last_below_credit_notification_email_at'] ==  '')
                                    {
                                        $next_email_time = date('Y-m-d H:i:s', strtotime('now'));
                                    }else if($cpus_model['below_credit_notification_count'] == $cpus_model['warning_email_number']){
                                        
                                        $next_email_time = date('Y-m-d H:i:s', strtotime("+".$power_meter_op_setting['warning_email_interval']." minutes", strtotime($cpus_model['last_below_credit_notification_email_at']) ));
                                          
                                    }

                                    if($next_email_time !== '')
                                    {
                                        if(date('Y-m-d H:i:s', strtotime('now')) <= $next_email_time ){
                                            $cpus_model->updateNotificationHistory($next_email_time);
                                        }
                                    }
                                    
                                    
                                }
                                
                                 
                            }else{

                                if($cpus_model['below_credit_notification_count'] > 0)
                                { //dd($cpus_model);
                                    if($cpus_model->restore_power_supply()){

                                        $cpus_model->reset_warning_counter();
                                        //send restoration email

                                        Log::debug('Restoration.');
                                        Log::debug('-------------------------------------------------------------.');
                                        //$cpus_model->terminate_power_supply();
                                        Log::debug(json_encode($cpus_model));
                                        Log::debug('-------------------------------------------------------------.');

                                        $email_response= $leaf_api->send_email($email, $power_meter_power_supply_restore_email[$default_language]['title'], $power_meter_power_supply_restore_email[$default_language]['content']);
                                       

                                    }
                                    
                                }
                            }

                    }


                }

            }

            return 'Process end ';
    }


    public function getOnOffAutomationTestingVer()
    {
             //power_meter_mailbox_setting
            $default_language  = 'english';
            $company_model = new Company();
            $leaf_api = new LeafAPI();
            $company_model = $company_model->self_profile();
            //dd($company_model);
            $backend_data = $company_model->backend_data;
            $power_meter_turn_off_meter_email = json_decode($backend_data['power_meter_turn_off_meter_email'] , true);
            $power_meter_low_credit_reminder = json_decode($backend_data['power_meter_low_credit_reminder'] , true);
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
    }


    public function getOnOffAutomationFirst()
    {
            //power_meter_mailbox_setting
            $default_language  = 'english';
            $company_model = new Company();
            $leaf_api = new LeafAPI();
            $company_model = $company_model->self_profile();
            $backend_data = $company_model->backend_data;
            $power_meter_turn_off_meter_email = json_decode($backend_data['power_meter_turn_off_meter_email'] , true);
            $power_meter_low_credit_reminder = json_decode($backend_data['power_meter_low_credit_reminder'] , true);
            $power_meter_op_setting = json_decode($company_model['power_meter_operational_setting'], true);
            $tester_list = $power_meter_op_setting['uat_tester_list'];

            if($power_meter_op_setting['power_supply_on_off_automation'] == false)
            {
              return false;
            }

            if($power_meter_op_setting['is_auto_turn_off_meter'] == true)
            {

                $cpus_listing = CustomerPowerUsageSummary::all();//CustomerPowerUsageSummary::getAllByLeafGroupId($company_model['leaf_group_id']);
                if(count($cpus_listing) > 0)
                {
                    foreach($cpus_listing as $cpus_model)
                    {
                          if( $power_meter_op_setting['is_in_uat'] == true)
                          {
                              if(!in_array($cpus_model['id'] , $tester_list ))
                              {
                                  continue;
                              }else{}

                          }

                          if($cpus_model['leaf_id_user'] == 0){continue;}
                            $user_model = $cpus_model->getCurrentAccountUser();
                            $email = $user_model['email'];
                            $cpus_model->getOrUpdateMeterRegister();
                            $language = $cpus_model->getUserPreferLanguage();
                            $total_balance = $cpus_model['current_credit_amount'] + $cpus_model['total_subsidy_amount'];

                            if( $total_balance < $power_meter_op_setting['credit_threshold']){
                                echo 'Below credit '.$cpus_model['below_credit_notification_count'] .' == '.$power_meter_op_setting['warning_email_number'].' <br>';

                                // termination flow
                                if($cpus_model['below_credit_notification_count'] >= $power_meter_op_setting['warning_email_number']){
               
                                    if($cpus_model['stop_supply_termination_time'] !== '')
                                    {

                                          if($power_meter_op_setting['is_auto_turn_off_meter'] == true)
                                          { 
                                              if( $cpus_model['stop_supply_termination_time'] >= date('Y-m-d H:i:s', strtotime('now'))){
                                                  if($cpus_model['is_power_supply_on'] == true)
                                                    $cpus_model->terminate_power_supply();
                                                  }
                                          }

                                    }else{

                                        $email_response = $leaf_api->send_email($email, $power_meter_turn_off_meter_email[$default_language]['title'], $power_meter_turn_off_meter_email[$default_language]['content']);
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
                                    if($cpus_model['last_below_credit_notification_email_at'] >= date('Y-m-d H:i:s', strtotime('now')))
                                    {
                                        if($cpus_model['below_credit_notification_count'] > $cpus_model['warning_email_number'])
                                        {
                                            $email_response= $leaf_api->send_email($email, $power_meter_low_credit_reminder[$default_language]['title'], $power_meter_low_credit_reminder[$default_language]['content']);
                                            $cpus_model['warning_email_number'] += 1 ;
                                            $cpus_model->save();
                                        }
                                    }

                                    //notification checker
                                    if($cpus_model['last_below_credit_notification_email_at'] == null || $cpus_model['last_below_credit_notification_email_at'] ==  '')
                                    {
                                        $next_email_time = date('Y-m-d H:i:s', strtotime('now'));
                                    }else if($cpus_model['below_credit_notification_count'] == $cpus_model['warning_email_number']){
                                        
                                        $next_email_time = date('Y-m-d H:i:s', strtotime("+".$power_meter_op_setting['warning_email_interval']." minutes", strtotime($cpus_model['last_below_credit_notification_email_at']) ));
                                          
                                    }

                                    if($next_email_time !== '')
                                    {
                                        if(date('Y-m-d H:i:s', strtotime('now')) <= $next_email_time ){
                                            $cpus_model->updateNotificationHistory($next_email_time);
                                        }
                                    }                
                                }
                                
                        }else{
                            if($cpus_model['below_credit_notification_count'] > 0)
                            {
                                if($cpus_model->restore_power_supply()){
                                    $cpus_model->reset_warning_counter();
                                }
                                
                            }
                        }
                    }

                }
            }
    }

   
}
