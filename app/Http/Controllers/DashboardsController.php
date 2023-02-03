<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use Auth;
use Carbon\Carbon;
use App\User;
use App\UserAssign;
use App\Setting;
use App\Ticket;
use App\LeafAPI;
use App\Company;
use App\Customer;
use App\MembershipModel\ARPaymentReceived;
use App\Language;
use App\OperationRule;
use App\BackendData;
use App\UCDashboardReportExcel;
use App\UCDashboardPaymentReportExcel;
use App\MembershipModel\ARInvoice;
use App\PowerMeterModel\MeterRegister;
use App\PowerMeterModel\MeterReading;
use App\PowerMeterModel\MeterPaymentReceived;
use App\PowerMeterModel\CustomerPowerUsageSummary;
use Illuminate\Support\Facades\Log;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Shared_Font;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_IOFactory;
use PHPExcel_Settings;

class DashboardsController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Dashboard'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                     'new_file_link' => '' 
                                   /* 'new_file_link' => class_basename($this).'@getNew' */
                                ];

        $this->middleware('auth_admin');
        //$this->middleware('acl', ['only'=>['getCustomerPowerUsageSummary']]);
        $this->label_session_token = 'session_token';
        $this->company      =   new Company();
        $this->leaf_api     =   new LeafAPI();
    }

    public function getUserProfile(){

        $is_model_page  = false;
        $page_variables = $this->page_variables;
        $page_variables['page_title'] = Language::trans('Member Profile');

        $company            =   new Company();
        $company            =   $company->self_profile();

        //$min_credit         =   isset($company) ? $company['min_credit'] :0;
        //Setting::set_company(Company::get_group_id());

        $is_allow_to_pay = false;   
        $leaf_api = $this->leaf_api;
        $membership_detail        = $leaf_api->get_user_house_membership_detail_by_user_id(Auth::user()->leaf_id_user);
        //dd(Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date']) - $company['membership_payment_allow_day']);
        if($membership_detail['membership_start_date'] == '')
        {
             $is_allow_to_pay = false;
        }else{
           // dd(Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date']));
          

             if( $company['membership_payment_allow_day'] <= date('Y-m-d', strtotime('now')))
             {
                 $is_allow_to_pay = true;  
                 //dd(Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date'])); 
                 $is_allow_to_pay = Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date'])  <= 0 ? true : false;
             }
        }
       
        
        if(isset( $membership_detail['members'])){
            foreach( $membership_detail['members'] as $member){
                $user = User::get_model_by_leaf_id_user($member['house_member_id_user']);
                $member['house_member_photo'] = isset($user['photo']) ? $user['photo'] : '';
            }
        }
        //dd($membership_detail);
        return view(Setting::UI_VERSION.'commons.dashboards.user_profile', compact('page_variables','membership_detail' ,'is_allow_to_pay','is_model_page'));
      
    }

 
    public function getIndex(Request $request)
    { 
        $page_variables = $this->page_variables;
        $leaf_group_id = !$request->input('group_id') ? ( Company::get_group_id() ?  Company::get_group_id(): 0) : $request->input('group_id');
        if ($leaf_group_id == 0) {
            return Language::trans('please select group before access.');
        }
        if (!$request->input('redirect')) {
            return Language::trans('system request redirect parameter to access.');
        }
        if (!$request->input('session_token')) {
            return Language::trans('system request session token parameter to access.');
        }
        $session_token = $request->input('session_token');
        $group_id = $request->input('group_id');
        // $session_token = 'ffHcJKbeRG3udv4JQoC97upAn2f2FJ5n';
        $leaf_api = new LeafAPI();
        $model = new User();
        $result = $leaf_api->get_user_profile($session_token);
        $result['leaf_id_user'] = $result['id_user'];
        unset($result['id_user']);
        $user = $model->get_or_create_user_account($result);
        setcookie($this->label_session_token, $session_token);
        $_COOKIE[$this->label_session_token] = $session_token;
        $_COOKIE[Company::cookie_label] = $group_id;
        Auth::loginUsingId($user->id, true);
        $this->company->set_group_id($group_id);
        $this->leaf_api->set_cookie_modules();
        

        return redirect()->to(url('admin/'.$request->input('redirect')));
    }

    public function getDashboardData(Request $request)
    {
        ini_set('max_execution_time', 3000);
        $leaf_group_id =Company::get_group_id();
        $company_model = Company::get_model_by_leaf_group_id($leaf_group_id);
        $backend_data_model = BackendData::get_model_by_leaf_group_id($company_model['leaf_group_id']);
        $request_data_type = $request->input('request_data_type') ? $request->input('request_data_type') : '' ;
        $finish_time = Carbon::now();
        //$last_update_at = MeterReading::get_group_last_update_time_by_leaf_group_id(282);
        //$last_update_past = $finish_time->diff($last_update_at)->format('%M %D %H:%i:%s');

        $is_need_update = false;
        $data;
 
        if(isset($backend_data_model['id']))
        {
            $backend_data_model = BackendData::get_model_by_leaf_group_id($company_model['leaf_group_id']);
            $data = (array) json_decode($backend_data_model['dashboard_data']);
          /*echo '1';
           echo 'Count :'.count($data);
          dd($data);*/
            if(!is_array($data))
            {
                $is_need_update = true;
            }else{

                if(count($data) == 0)
                {
                    $is_need_update = true;
                }
            }
        }else{
            $backend_data_model  = new BackendData();
            $is_need_update = true;
        }

        $is_need_update = true;
         //$is_need_update = Setting::update_flag;
        if($is_need_update == true)
        {
            

            $power_meter_op_setting = json_decode($company_model['power_meter_operational_setting'], true);
            $min_credit         =   isset($power_meter_op_setting['credit_threshold']) ? $power_meter_op_setting['credit_threshold'] : 0 ;
            //CustomerPowerUsageSummary::getUserBelowCredit($min_credit,Company::get_group_id())

           // $last_update = '2020-11-19 00:13:31';
            //$last_update =  MeterReading::get_group_last_update_time_by_leaf_group_id(282);
            $data = [
              'outstanding_count' => count(CustomerPowerUsageSummary::getUserBelowCredit($min_credit,Company::get_group_id())) , 
              'min_credit_count' => count(CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id())) , 
              'customer_count' => Customer::get_today_new_record() > 0 ? '+' : Customer::get_today_new_record() , 
              'invoice_count' => ARInvoice::get_today_new_record() > 0 ? '+' : ARInvoice::get_today_new_record() , 
              'today_meter_payment_received_count' => MeterPaymentReceived::get_today_new_record() > 0 ? '+' : MeterPaymentReceived::get_today_new_record() , 
              'payment_received_count' => ARPaymentReceived::get_today_new_record() > 0 ? '+' : ARPaymentReceived::get_today_new_record() , 
              'ticket_complaint_count' => Ticket::get_today_new_record() > 0 ? '+' : Ticket::get_today_new_record() , 
              'meter_payment_received_count'   =>   count(MeterPaymentReceived::get_recent_pay_by_leaf_group_id(Company::get_group_id())),
              //'last_update_past'     =>  MeterReading::get_group_last_update_time_by_leaf_group_id(282)->toDateTimeString(),
                //MoiCW - Modify to display the date
                'last_update_at' =>  MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id()),//MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id()) !== null ? MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id())->toDateTimeString() : '-',
              //'last_update_at' =>  MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id()) !== null ? MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id())->toDateTimeString() : '-',
            ];

            $backend_data_model['dashboard_data'] = json_encode($data);
            $backend_data_model->save();

        }

        $complains          =   Ticket::/*where('document_date','=',date('Y-m-d'))->*/skip(0)->take(5)->get();
        //$min_credit_count = count(CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id()));
        //$outstanding_count = count(CustomerPowerUsageSummary::get_customer_with_outstanding_by_leaf_group_id(Company::get_group_id()));
        $min_credit_count = Language::trans('Please wait ...');
        $outstanding_count = Language::trans('Please wait ...');
        $fdata['data']= $data;

        return json_encode($fdata);
    }

    public function getDashboardChartData()
    {
        ini_set('max_execution_time', 300);
        $monthly_usage = DB::select('SELECT `current_date`, SUM(`current_usage`) as current_usage FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY `current_date`', [date('Y-m-').'01', date('Y-m-t')]);

        $total_usage = '';
        if (count($monthly_usage)) {
            $total_usage .= '[';
            foreach ($monthly_usage as $row) {
                $total_usage .= '[\''.date('d/m', strtotime($row->current_date)).'\', '.$row->current_usage.'],';
            }
            $total_usage .= ']';
            trim($total_usage, ',');
        }
        if ($total_usage == '') {
            $total_usage = '[]';
        }

        $module_status_listing = OperationRule::get_module_operation_status_by_leaf_group_id(Company::get_group_id());
        $area_chart_data    =   json_encode(Setting::get_area_chart_data_by_leaf_groud_id(Company::get_group_id()));

        $complains          =   Ticket::/*where('document_date','=',date('Y-m-d'))->*/skip(0)->take(5)->get();
        //$min_credit_count = count(CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id()));
        //$outstanding_count = count(CustomerPowerUsageSummary::get_customer_with_outstanding_by_leaf_group_id(Company::get_group_id()));
        $min_credit_count = Language::trans('Please wait ...');
        $outstanding_count = Language::trans('Please wait ...');

        $recent_pay_count   =   count(MeterPaymentReceived::get_recent_pay_by_leaf_group_id(Company::get_group_id()));
        $last_update_at     =  MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id());

        return json_encode($data);
    }

    public function getDashboard(Request $request)
    {
        
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

        $graph_info = array();
        $device_ids = ['24e124136c225107','24e124141c141557','24e124141c147463','24e124148b495286','24e124535b316056','24e124538c019556','24e124600c124993'];
        foreach($device_ids as $device_id)
        {
            $feeds = DeviceReading::getByDeviceEui($device_id);
            $tg_info = $this->getIotSummaryData($feeds);
            array_push($graph_info , $tg_info);
        }
        //dd($graph_info);
        //dd('x');
        //$module_status_listing = OperationRule::get_module_operation_status_by_leaf_group_id(Company::get_group_id());
        return view(Setting::UI_VERSION.'iot.dashboards.index', compact('page_variables','graph_info'));
    }

    public function getDashboardPass(Request $request)
    {
        $is_model_page  = false;
        $page_variables = $this->page_variables;
        $company            =   new Company();
        $company            =   $company->self_profile();

        if (!Auth::check()) {
            return redirect()->action('OpencartUsersController@getLogin');
        }
        //$min_credit         =   isset($company) ? $company['min_credit'] :0;

        $monthly_usage = DB::select('SELECT `current_date`, SUM(`current_usage`) as current_usage FROM `meter_readings` WHERE `current_date` BETWEEN ? AND ? GROUP BY `current_date`', [date('Y-m-').'01', date('Y-m-t')]);

        $total_usage = '';
        if (count($monthly_usage)) {
            $total_usage .= '[';
            foreach ($monthly_usage as $row) {
                $total_usage .= '[\''.date('d/m', strtotime($row->current_date)).'\', '.$row->current_usage.'],';
            }
            $total_usage .= ']';
            trim($total_usage, ',');
        }
        if ($total_usage == '') {
            $total_usage = '[]';
        }

        $module_status_listing = OperationRule::get_module_operation_status_by_leaf_group_id(Company::get_group_id());
        $area_chart_data    =   json_encode(Setting::get_area_chart_data_by_leaf_groud_id(Company::get_group_id()));

        $complains          =   Ticket::/*where('document_date','=',date('Y-m-d'))->*/skip(0)->take(5)->get();
        //$min_credit_count = count(CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id()));
        //$outstanding_count = count(CustomerPowerUsageSummary::get_customer_with_outstanding_by_leaf_group_id(Company::get_group_id()));
        $min_credit_count = Language::trans('Please wait ...');
        $outstanding_count = Language::trans('Please wait ...');

        $recent_pay_count   =   count(MeterPaymentReceived::get_recent_pay_by_leaf_group_id(Company::get_group_id()));
        $last_update_at     =  MeterReading::get_group_last_update_time_by_leaf_group_id(Company::get_group_id());
        //Setting::set_company(Company::get_group_id());

        /*if(Company::get_group_id() == Company::get_group_id()){
            $leaf_api = $this->leaf_api;
            $membership_detail        = $leaf_api->get_user_house_membership_detail_by_user_id(Auth::user()->leaf_id_user);*/
            //dd(Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date']) - $company['membership_payment_allow_day']);
            /*$is_allow_to_pay = Setting::get_date_different_in_day(date('Y-m-d', strtotime('now')),$membership_detail['membership_end_date']) - $company['membership_payment_allow_day'] <= 0 ? true : false;
            if(isset($membership_detail['members'])){
               foreach( $membership_detail['members'] as $member)
               {
                  $user = User::get_model_by_leaf_id_user($member['house_member_id_user']);
                  $member['house_member_photo'] = isset($user['photo']) ? $user['photo'] : '';
               } 
            }*/
            
            //dd($membership_detail);
        /*    return view(Setting::UI_VERSION.'commons.dashboards.index', compact('page_variables','total_usage','outstanding_count','min_credit_count','recent_pay_count','complains','area_chart_data','module_status_listing','membership_detail' ,'is_allow_to_pay','is_model_page'));
        }*/

        return view(Setting::UI_VERSION.'commons.dashboards.index', compact('page_variables','total_usage','outstanding_count','min_credit_count','recent_pay_count','complains','area_chart_data','module_status_listing','last_update_at','is_model_page'));
    }

    public function getDashboardCount()
    {
        $leaf_api           =   new LeafAPI();
        $company            =   new Company();
        $company            =   $company->self_profile();  
        $power_meter_op_setting = json_decode($company_model['power_meter_operational_setting'], true);
        $min_credit         =   isset($power_meter_op_setting['credit_threshold']) ? $power_meter_op_setting['credit_threshold'] : 0 ;
        $min_credit_count = count(CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id()));
        $outstanding_count = count(CustomerPowerUsageSummary::getUserBelowCredit($min_credit,Company::get_group_id()));

        return json_encode(['outstanding_count'=>$outstanding_count,'min_credit_count'=>$min_credit_count]);
    }

    public function getLastestPowerUsageSummary()
    {
        CustomerPowerUsageSummary::update_customer_power_usage_summary(Company::get_group_id());
        return true;
    }
         

    public function getCreditListing(Request $request)
    {
        $is_model_page = false;
        $page_variables = $this->page_variables;
        $recent_pay     =   MeterPaymentReceived::whereBetween('document_date', 
                                [date('Y-m-d', strtotime('-100 days')), date('Y-m-d', strtotime('now'))])
                                    ->groupBy('leaf_room_id')->pluck('leaf_room_id')->toArray();

        $listing        =   MeterRegister::where('status','=',true)->get();
        $page_title     =   Language::trans('Meter Register\'s Listing');
        $house_list     =   [];
        $leaf_api       =   new LeafAPI();
        $list           =   $leaf_api->get_houses();
        $company        =   new Company();
        $company        =   $company->self_profile();
        $min_credit     =   isset($company) ? $company['min_credit']:0;
       
        if (isset($list['house'])) {
            $house_list = $list['house'];
        }
        foreach ($listing as $index => $row) {
            $pass=false;
            if (isset($listing) && isset($list['house'])) {
                foreach ($list['house'] as $house) {
                    if (isset($house['house_rooms']) && count($house['house_rooms'])) {
                        foreach ($house['house_rooms'] as $room) {
                            $date_range['date_started'] = $room['house_room_entry_date'];
                            $date_range['date_ended']   = date('Y-m-d H:i:s');
                            $customer_id = 0;
                            foreach ($room['house_room_members'] as $member) {
                                if (!$member['house_room_member_deleted']) {
                                    $customer_id    =   $member['house_member_id_user'];
                                }
                            }
                            if ($customer_id) {
                                if ($credit = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range($room['id_house_room'], $date_range, $customer_id)) {
                                    $total_balance = isset($credit[0]['totalBalance']) ? $credit[0]['totalBalance']:0;
                                    if ($request->input('type') == 'min_credit' && $total_balance <= $min_credit) {
                                        $pass=true;
                                    }
                                    if ($request->input('type') == 'outstanding' && $total_balance <= 0) {
                                        $pass=true;
                                    }
                                }
                            }
                            if ($request->input('type') == 'recent_pay' && in_array($room['id_house_room'], $recent_pay)) {
                                $pass=true;
                            }
                        }
                    }
                }
            }
            if (!$pass) {
                unset($listing[$index]);
            }
        }

        return view('billings.dashboards.credit_listing', compact('page_title','listing','house_list','page_variables','is_model_page'));
    }

    //wip
    public function getCustomerPowerUsageSummary(Request $request)
    {
        /*$page_variables = $this->page_variables;
        $page_title = $page_variables['page_title'];
        $type = $request->input('type') ; 
        $is_model_page = false;
        $company        =   new Company();
        $company        =   $company->self_profile();
        $power_meter_op_setting = json_decode($company['power_meter_operational_setting'], true);
        $min_credit         =   isset($power_meter_op_setting['credit_threshold']) ? $power_meter_op_setting['credit_threshold'] : 0 ;
     
        if ($type == 'recent_pay'){
            $page_title = Language::trans('Payment Listing');
            $listing = MeterPaymentReceived::get_recent_pay_by_leaf_group_id(Company::get_group_id());
         
        }else if($type  == 'min_credit'){
            $type  = 'power_usage';
            $page_title = Language::trans('Healthy Credit ( Credit > RM ').$min_credit.' )';
            $listing = CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id());

        }else if($type  == 'outstanding'){
            $page_title = Language::trans('Below Credit ( Credit < RM ').$min_credit.' )';
            //Language::trans('Outstanding Listing');
            $type  = 'power_usage';
            $listing = CustomerPowerUsageSummary::getUserBelowCredit($min_credit,Company::get_group_id());
        }

    
        $page_variables['page_title'] = $page_title ;
        return view(Setting::UI_VERSION.'commons.dashboards.'.$type .'_summary', compact('page_variables','listing','is_model_page'));
        */
        $page_variables = $this->page_variables;
        $page_title = $page_variables['page_title'];
        $type = $request->input('type') ; 
        $is_model_page = false;
        $company        =   new Company();
        $company        =   $company->self_profile();
        $power_meter_op_setting = json_decode($company['power_meter_operational_setting'], true);
        $min_credit         =   isset($power_meter_op_setting['credit_threshold']) ? $power_meter_op_setting['credit_threshold'] : 0 ;
     
        if ($type == 'recent_pay'){
            $page_title = Language::trans('Payment Listing');
            $listing = MeterPaymentReceived::get_recent_pay_by_leaf_group_id(Company::get_group_id());
            
            $page_variables['download_link']= class_basename($this).'@getPaymentList';
            $is_download = true;
        }else if($type  == 'min_credit'){
            $type  = 'power_usage';
            $page_variables['download_link'] =  class_basename($this).'@getMinCredit';
            $page_title = Language::trans('Healthy Credit ( Credit > RM ').$min_credit.' )';
            $listing = CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id());
            $is_download = true;
        }else if($type  == 'outstanding'){
            $page_title = Language::trans('Below Credit ( Credit < RM ').$min_credit.' )';
            //Language::trans('Outstanding Listing');
            $type  = 'power_usage';
            $page_variables['download_link'] =  class_basename($this).'@getOutstanding';
            $listing = CustomerPowerUsageSummary::getUserBelowCredit($min_credit,Company::get_group_id());
            $is_download = true;
        }
       
    
        $page_variables['page_title'] = $page_title ;
        
        return view(Setting::UI_VERSION.'commons.dashboards.'.$type .'_summary', compact('page_variables','is_download','listing','is_model_page'));
        
    }
    //MoiCW - Add getOutstading report
    public function getPaymentList()
    {
        Log::Info("Get Get Out");
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '4096M'); 
        $company        =   new Company();
        $company        =   $company->self_profile();
    
        $report_title = "recent_pay";
            //Language::trans('Outstanding Listing');
            LOG::INFO("Download");
            $listing = MeterPaymentReceived::get_recent_pay_by_leaf_group_id(Company::get_group_id());
            $objPHPExcel = new UCDashboardPaymentReportExcel();
            $objPHPExcel->content($listing);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.5);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        
            $file = storage_path('framework/views/'.$report_title.'.xlsx');
            $objWriter->save($file);
            ob_end_clean(); // Clear format error
            LOG::INFO("Download".$file);
            //return response()->download($file, $report_title.'.xlsx');
            return response()->download($file,  $report_title.'.xlsx', [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "inline"
            ]);
            
    }
    //MoiCW - Add getOutstading report
    public function getOutstanding()
    {
        Log::Info("Get Get Out");
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '4096M'); 
        $company        =   new Company();
        $company        =   $company->self_profile();
        $power_meter_op_setting = json_decode($company['power_meter_operational_setting'], true);
        $min_credit         =   isset($power_meter_op_setting['credit_threshold']) ? $power_meter_op_setting['credit_threshold'] : 0 ;
    
        $report_title = "outstanding_data";
            //Language::trans('Outstanding Listing');
            LOG::INFO("Download");
            $listing = CustomerPowerUsageSummary::getUserBelowCredit($min_credit,Company::get_group_id());
            $objPHPExcel = new UCDashboardReportExcel();
            $objPHPExcel->content($listing);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.5);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        
            $file = storage_path('framework/views/'.$report_title.'.xlsx');
            $objWriter->save($file);
            ob_end_clean(); // Clear format error
            LOG::INFO("Download".$file);
            //return response()->download($file, $report_title.'.xlsx');
            return response()->download($file,  $report_title.'.xlsx', [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "inline"
            ]);
            
    }
    //MoiCW - Add getMinCredit report
    public function getMinCredit()
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '4096M'); 
        $company        =   new Company();
        $company        =   $company->self_profile();
        $power_meter_op_setting = json_decode($company['power_meter_operational_setting'], true);
        $min_credit         =   isset($power_meter_op_setting['credit_threshold']) ? $power_meter_op_setting['credit_threshold'] : 0 ;
    
            $report_title = "min_credit";
            //Language::trans('Outstanding Listing');
            LOG::INFO("Download");
            $listing = CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id());
            $objPHPExcel = new UCDashboardReportExcel();
            $objPHPExcel->content($listing);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.5);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.2);
            $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.2);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        
            $file = storage_path('framework/views/'.$report_title.'.xlsx');
            $objWriter->save($file);
            ob_end_clean(); // Clear format error
            LOG::INFO("Download".$file);
            //return response()->download($file, $report_title.'.xlsx');
            return response()->download($file,  $report_title.'.xlsx', [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => "inline"
            ]);
            
    }
    public function getUtilityChargeIndex()
    {
        $page_variables = $this->page_variables;
        return view('utility_charges.dashboards.index',compact('page_variables'));
    }

    public function getUmrahIndex(Request $request)
    {
        $page_variables = $this->page_variables;
        return view('umrah.dashboards.index',compact('page_variables'));
    }

    public function getReports()
    {
        $page_title = 'Laporkan Analisis';
        return view('test', compact('page_title'));
    }

    public function getError($folder, $error)
    {
        $page_variables = $this->page_variables;

        switch ($error) {
            case '500':
                $datas['error_code']    =   500;
                $datas['message']       =   Language::trans('Internal Server Error');
                break;
            
            case '404':
                $datas['error_code']    =   404;
                $datas['message']       =   Language::trans('Page Not Found.');
                break;
            
            
            case '403':
                $datas['error_code']    =   403;
                $datas['message']       =   Language::trans('Forbidden Access.');
                break;
            
            default:
                # code...
                break;
        }

        return view(Setting::UI_VERSION.'commons.layouts.error', compact('datas','page_variables'));
    }
}
