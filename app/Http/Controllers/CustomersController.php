<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Schema;

use App\LeafAPI;
use App\Setting;
use App\Company;
use App\Language;
use App\Customer;
use App\PowerMeterModel\MeterPaymentReceived;
use App\User;

class CustomersController extends Controller
{
    public function __construct()
    {
        $this->page_variables = [
                                    'page_title'   =>   Language::trans('Customers Page'),
                                    'return_url' => class_basename($this).'@getIndex',
                                    'edit_link' => class_basename($this).'@getEdit' ,
                                    'view_link' => class_basename($this).'@getView' ,
                                    'delete_link' => class_basename($this).'@getDelete',
                                    'new_file_link' => class_basename($this).'@getNew' 
                                ];

        $this->leaf_api     =   new LeafAPI();
        $this->middleware('acl', ['only'=>['getIndex','getNew','getEdit','getView','getDelete']]);
        $this->middleware('auth_admin');
    }

    //wip
    public function sync_leaf_house_member_to_customer_table($model,$source=null){
        
        //check if exist
         $result = LeafAPI::update_all_customer_from_leaf();
         $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('No data was updated.'),
                    'data'   =>  [],
                    ];

        if (count($result) > 0) {

              $fdata = [
                        'status_code'   =>   1, 
                        'status_msg'    =>   Language::trans('Data was update.'), 
                        'data'          =>   $result->toArray(), 
                        'number_record_update' => count($result), 
                        ];
        }

        return $fdata;
        
    }

    public function getInfo(Request $request)
    { 
        $leaf_api = $this->leaf_api;
        $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not found.'),
                    'data'   =>  [],
                    ];

        if ($result = Customer::find($request->input('customer_id'))) {
            $type = $request->input('type') !== null ? $request->input('type') : '';

            if($type == 'meter'){
               //print_r($result['leaf_user_id']);
                $result->payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($result['leaf_id_user'],Setting::get_leaf_group_id());
                //$result->power_meter_account_status = MeterPaymentReceived::get_user_account_status_by_leaf_id_user_and_date_started($result['leaf_id_user'] , User::get_date_statarted_temp_by_id_house_member($result['id_house_member']));

            }elseif($type == 'membership' || $type == ''){
                  
                $result->membership_detail =  $result['id_house'] != 0 ? $leaf_api->get_house_membership_detail_by_house_id($result['id_house']) : null;
                $result->membership_detail_personal_info =  $result['leaf_id_user'] != 0 ? $leaf_api->get_user_house_membership_detail_by_user_id($result['leaf_id_user']) : null;   
            }   
            
             
            $result->currency_rate  =   $result->display_relationed('currency', 'rate');
            $result->currency_label  =   $result->display_relationed('currency', 'symbol');
            //$result->power_meter_detail = 
            //dd($result);
            $fdata = [
                    'status_code'   =>   1,
                    'status_msg'    =>   Language::trans('Data was found.'),
                    'data'          =>   $result->toArray(),
                      ];
        }
      
        return json_encode($fdata);
    }

    public function getIndex()
    {
        $page_variables = $this->page_variables;
        $i              =   1;
        $model          =   new Customer();
        $cols           =   $model->listing_header();
        $model          =   $model->listing()->paginate(Setting::paginate);

        return view(Setting::UI_VERSION.'commons.customers.index', compact('model','i','cols','page_variables'));
    }

    public function getNew()
    {
        $page_variables = $this->page_variables;
        $model = new Customer();

        return view(Setting::UI_VERSION.'commons.customers.form', compact('model','page_variables'));
    }

    public function postNew(Request $request)
    {
        $model = new Customer();
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());

        return redirect()->action('CustomersController@getEdit', [$model->id])
                            ->with(Setting::session_alert_icon, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully created.'));
    }

    public function getView($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = Customer::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        return view(Setting::UI_VERSION.'commons.customers.view', compact('model','page_variables'));
    }

    public function getEdit($id)
    {
        $page_variables = $this->page_variables;
        if (!$model = Customer::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }

        return view(Setting::UI_VERSION.'commons.customers.form', compact('model','page_variables'));
    }

    public function postEdit(Request $request, $id)
    {
        if (!$model = Customer::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $errors = $model->validate_form($request->all());
        if ($errors) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $model->save_form($request->all());
        return redirect()->action('CustomersController@getEdit', [$id])
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $model->name.Language::trans(' was successfully updated.'));
    }

    public function getDelete($id)
    {
        if (!$model = Customer::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $temp = $model->name;
        $model->delete();
        return redirect()->action('CustomersController@getIndex')
                            ->with(Setting::session_alert_status, 'check')
                            ->with(Setting::session_alert_status, 'success')
                            ->with(Setting::session_alert_msg, $temp.Language::trans(' was successfully deleted.'));
    }

    public function getLog(Request $request)
    {
        $data['status_code']    =   false;
        $data['status_msg']     =   'User was failed to inserted.';
        if ($request->input('user_email')) {
            $log = [
                    'leaf_user_email'   =>  $request->input('user_email'),
                    'is_read'           =>  false,
                    'created_at'        =>  date('Y-m-d H:i:s'),
                    'updated_at'        =>  date('Y-m-d H:i:s'),
                    'leaf_group_id'     =>  285,
                    // 'leaf_group_id' =>  $request->input('leaf_group_id'),
                    ];
            DB::table('customer_logs')->insert($log);
            $data['status_code']    =   true;
            $data['status_msg']     =   'User was successfully to inserted.';
        }
        return json_encode($data);
    }

    public function getLatest(Request $request)
    {
        $data['status_code']    =   false;
        $data['user_email']     =   '';
        $data['user_fullname']  =   '';
        $model = DB::table('customer_logs')
                            ->where('leaf_group_id','=',Company::get_group_id())
                            ->where('is_read','=',false)
                            ->first();
        if ($model) {
            // DB::table('customer_logs')
                            // ->where('leaf_group_id','=',Company::get_group_id())
                            // ->update(['is_read'=>true]);
            $leaf_api = new LeafAPI();
            $user = $leaf_api->get_user_profile_by_email($model->leaf_user_email);
            $data['status_code']    =   true;
            $data['user_email']     =   $user['user_email'];
            $data['user_fullname']  =   $user['user_fullname'];
        }
        return json_encode($data);
    }

    public function getDetails(Request $request)
    {
        // https://cloud.leaf.com.my/web/management.php?action=facility&id={id_group}&tab=booking&id_user={id_user}&app_secret={md5_app_secret}&session_token={md5_session_token}&contentonly=1&headerfooter=none
        // https://cloud.leaf.com.my/web/management.php?action=inout&id={id_group}&id_user={id_user}&app_secret={md5_app_secret}&session_token={md5_session_token}&contentonly=1&headerfooter=none
        $leaf_api               =   new LeafAPI();
        $data['user_id']        =   $request->input('user_id');
        $data['group_id']       =   Company::get_group_id();
        $data['app_secret']     =   $leaf_api::app_secret;
        $data['session_token']  =   $_COOKIE['session_token'];
        $path                   =   '&id='.$data['group_id'].'&id_user='.$data['user_id'].'&app_secret='.$data['app_secret'].'&session_token='.$data['session_token'].'&contentonly=1&headerfooter=none';
        $facility_booking_src   =   'https://cloud.leaf.com.my/web/management.php?action=facility&tab=booking'.$path;
        $in_out_record_src      =   'https://cloud.leaf.com.my/web/management.php?action=inout'.$path;
        return view(Setting::UI_VERSION.'commons.customers.details', compact('facility_booking_src','in_out_record_src'));
    }
}
