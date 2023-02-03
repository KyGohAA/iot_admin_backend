<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Shared_Font;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_IOFactory;
use PHPExcel_Settings;
use App\Company;
use App\Setting;
use App\LeafAPI;
use App\Language;
use App\MembershipModel\ARInvoice;
use App\PowerMeterModel\MeterReading;
use App\PowerMeterModel\MeterInvoice;
use App\PowerMeterModel\MeterRegister;
use App\SMC\InvoiceReportPdf;
use App\Setia\SalesReportPdf;
use App\SMC\RoomUsageReportPdf;
use App\SMC\MonthlySaleReportPdf;
use App\SMC\MonthlyUsageReportPdf;
use App\PowerMeterModel\UCInvoiceReportExcel;
use App\PowerMeterModel\UCMonthlySalesReportExcel;
use App\PowerMeterModel\UCMonthlyUsageReportExcel;
use App\PowerMeterModel\UCRoomUsageReportExcel;
use App\PowerMeterModel\MeterReadingDaily;
use App\PowerMeterModel\MeterPaymentReceived;
use App\PowerMeterModel\CustomerPowerUsageSummary;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->return_url   =   class_basename($this).'@getIndex';
        $this->new_file_link = class_basename($this).'@getNew';
        $this->report_export_type = ['html', 'excel' , 'pdf'];
        $this->header = Company::where('leaf_group_id','=',Company::get_group_id())->first();
    }

    public function getLatestDailyMeterReading(){

        $result;
        $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not yet update.'),
                    'data'   =>  [],
                    ];
                  
        //if($request->input('leaf_group_id') !== null){
            MeterReadingDaily::save_daily_meter_reading_by_leaf_group_id(282);        
            $fdata['status_code']   =   1;
            $fdata['status_msg']    =   Language::trans('Data was update.');
        //}
        return json_encode($fdata);
    }

    public function getLatestDailyMeterReadingByDailyRecordSummary_2(Request $request){

        $result;
        $fdata = [
                    'status_code'   =>  0,
                    'status_msg'    =>  Language::trans('Data not yet update.'),
                    'data'   =>  [],
                    ];
        
        $date_range = ['date_started' => date('Y-m-d', strtotime('- 10 day', date('Y-m-d', strtotime('now')))) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
        //if($request->input('leaf_group_id') !== null){
            MeterReadingDaily::save_daily_meter_reading_by_leaf_group_id(282);        
            $fdata['status_code']   =   1;
            $fdata['status_msg']    =   Language::trans('Data was update.');
        //}
        return json_encode($fdata);
    }

    //wip
    public function getLatestDailyMeterReadingByDailyRecordSummary(Request $request)
    {
        if ($request->input('test')) {
            return json_encode(['status_code'=>'1', 'status_message'=>'helo world']);
        }
        if ($request->input('app_secret') == Setting::app_secret) {
            
            $result;
            $fdata = [
                        'status_code'   =>  0,
                        'status_msg'    =>  Language::trans('Data not yet update.'),
                        'data'   =>  [],
                        ];
            
            $date_range = ['date_started' => date('Y-m-d', strtotime('- 15 day', date('Y-m-d', strtotime('now')))) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
          
            if($request->input('leaf_group_id') !== null){
                MeterReadingDaily::save_daily_meter_reading_by_leaf_group_id($request->input('leaf_group_id'));
                $fdata['status_code']   =   1;
                $fdata['status_msg']    =   Language::trans('Data was update.');
            }
            return json_encode($fdata);
        }

        return json_encode(['status_code'=>'-1', 'status_message'=>'Authentication Failed','id'=>0]);
    }

    public function getSalesReport(Request $request)
    {       
        $is_model_page  = false;
        $page_variables = [
                                    'page_title'   =>   Language::trans('Invoices Report'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];

        $is_model_page    = false;
        $is_search_result = false;
        $model = new ARInvoice();
        $setting = new Setting();
        $total = 0;
        $listing = [];
        if ($request->input()) {
            $is_search_result = true;
            $listing = new ARInvoice();
            $input = $request->input();
            $date_range = Setting::convert_date_range_string_to_array($input['daterange']);

            if ($date_range['date_started']) {
                $listing = $listing->whereBetween('document_date', [$date_range]);
            }
            if ($input['from_customer_id'] && !$input['to_customer_id']) {
             
                $listing = $listing->whereIn('customer_id', $customers);
            }

            $listing = $listing->get();
            $report_title = Setting::get_sunway_report_title_by_type_and_date_range(Setting::SUNWAY_SALES_REPORT,$date_range);


            switch ($request->input('export_by')) {
                case 'pdf':
                    $pdf                =   new SalesReportPdf();
                    $pdf->AddPage('L', 'Letter');
                    $pdf->listing       =   $listing;
                    $pdf->setting       =   $setting;
                    $pdf->model         =   $model;
                    $pdf->is_finished   =   false;
                    $pdf->header_title  =   $this->header;
                    $pdf->setTitle($report_title);
                    $pdf->date_range    =   $model->getDate($request->input('date_started')).' - '.$model->getDate($request->input('date_ended'));
                    $pdf->content();
                    return response($pdf->Output(), 200)
                                 ->header('Content-Type', 'application/pdf');
                    break;

                case 'excel':
            
                   // Setting::init_php_excel();
                    $objPHPExcel = new UCMonthlySalesReportExcel();
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

                     return response()->download($file, $report_title.'.xlsx');
                     break;


                default:
                    break;
            }
        }

        return view(Setting::UI_VERSION.'billings.reports.sales', compact('page_variables','is_model_page','model','setting','rooms','listing','total','is_search_result','is_model_page'));
    }

    public function getInvoices(Request $request)
    {
        $is_model_page  = false;
        $page_variables = [
                                    'page_title'   =>   Language::trans('Invoices Report'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];

        $is_search_result = false;
        $model = new MeterInvoice();
        $leaf_api = new LeafAPI();
        $rooms = $leaf_api->get_houses();
        $setting = new Setting();
        $listing = [];
        $total = 0;
        if ($request->input()) {
            $is_search_result = true;
            $model->leaf_house_id   =   $request->input('leaf_house_id');
            $model->leaf_room_id    =   $request->input('leaf_room_id');
            $model->is_paid         =   $request->input('is_paid');
            $meter_registers = MeterRegister::where('leaf_room_id','=',$model->leaf_room_id)->pluck('id')->toArray();
            $listing = MeterInvoice::whereIn('meter_register_id', $meter_registers)
                                        ->whereBetween('document_date', [$setting->setDate($request->input('date_started')), $setting->setDate($request->input('date_ended'))]);
            if ($request->input('is_paid') && $request->input('is_paid') != 'all') {
                $listing = $listing->where('is_paid','=',$request->input('is_paid'));
            }
            $listing = $listing->get();
            switch ($request->input('export_by')) {
                case 'pdf':
                    $pdf                =   new InvoiceReportPdf();
                    $pdf->AddPage('L', 'Letter');
                    $pdf->listing       =   $listing;
                    $pdf->rooms         =   $rooms;
                    $pdf->setting       =   $setting;
                    $pdf->is_finished   =   false;
                    $pdf->model         =   $model;
                    $pdf->header_title  =   $this->header;
                    $pdf->setTitle($report_title);
                    $pdf->date_range    =   $model->getDate($request->input('date_started')).' - '.$model->getDate($request->input('date_ended'));
                    $pdf->content();
                    return response($pdf->Output(), 200)
                                 ->header('Content-Type', 'application/pdf');
                    break;

                case 'excel':

                   // Setting::init_php_excel();
                    $objPHPExcel = new UCInvoiceReportExcel($listing);
                    $objPHPExcel->content();
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

                     return response()->download($file, $report_title.'.xlsx');
                     break;

                //wip
                case 'html':
                    $leaf_room_id = $request->input('leaf_room_id');
                    return view(Setting::UI_VERSION.'utility_charges.reports.room_usages', compact('houses_detail','page_variables','is_model_page','model','listing','total','leaf_room_id','is_search_result'));

                default:
                    break;
            }
        }
        return view(Setting::UI_VERSION.'utility_charges.reports.invoices', compact('page_variables','is_model_page','model','setting','rooms','listing','total','is_search_result'));
    }

    public function getMonthlySales(Request $request)
    {
        $this->initComponent();
        $is_model_page  = false;
        $page_variables = [
                                    'page_title'   =>   Language::trans('Monthly Sales Report'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];

        $is_search_result = false;
        $month_started = '01-'.$request->input('month_started');
        $month_ended = '31-'.$request->input('month_ended');
        $model = new MeterInvoice();
        $leaf_api = new LeafAPI();
        
        $setting = new Setting();
        $listing = [];
        $total = 0;

        $date_range = ['date_started' => $month_started,
                    'date_ended'    => $month_ended
                    ];

        $report_title = Setting::get_sunway_report_title_by_type_and_date_range(Setting::SUNWAY_MONTHLY_SALES_REPORT,$date_range);

        if ($request->input()) {

            $rooms = $leaf_api->get_houses();
            $is_search_result = true;  
            $leaf_house_id = $request->input('leaf_house_id');
            $room_id = $request->input('leaf_room_id');    
            $listing = MeterPaymentReceived::whereBetween('document_date', [$setting->setDate($month_started), $setting->setDate($month_ended)]);

            if($room_id != 0){
               
               $listing = $listing->where('leaf_room_id','=',$room_id);
            }

            if($leaf_house_id){  

                $listing = $listing->where('leaf_house_id','=',$leaf_house_id);
            }
            $listing = $listing->where('payment_method','!=', MeterPaymentReceived::label_subsidy);
            $listing = $listing->get();

            $input = $request->input();
            switch ($input['export_by']) {
                case 'pdf':
                    $pdf                =   new MonthlySaleReportPdf();
                    $pdf->AddPage('L', 'Letter');
                    $pdf->listing       =   $listing;
                    $pdf->rooms         =   $rooms;
                    $pdf->setting       =   $setting;
                    $pdf->model         =   $model;
                    $pdf->header_title  =   $this->header;
                    $pdf->is_finished   =   false;
                    //$pdf->header_title  =   $report_title;
                    $pdf->setTitle($report_title);
                    $pdf->date_range    =   $model->getDate($month_started).' - '.$model->getDate($month_ended);
                    $pdf->content();

                return response($pdf->Output(), 200)
                                 ->header('Content-Type', 'application/pdf');
                    break;

                case 'excel':

                   // Setting::init_php_excel();
                    $objPHPExcel = new UCMonthlySalesReportExcel();
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

                     return response()->download($file, $report_title.'.xlsx');
                     break;

                //wip
                case 'html':
                    $leaf_room_id = $request->input('leaf_room_id');
                    return view(Setting::UI_VERSION.'utility_charges.reports.monthly_sales', compact('houses_detail','page_variables','is_model_page','model','listing','total','leaf_room_id','is_search_result'));


                default:
                    break;
            }
        }
        return view(Setting::UI_VERSION.'utility_charges.reports.monthly_sales', compact('page_variables','is_model_page','model','listing','setting','rooms','total','is_search_result'));
    }

    public function getRoomUsages(Request $request)
    {
        //dd('The report is current under optimization.');
        /*ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '4096M'); */
        $this->initComponent();
        $is_model_page  = false;
        $page_variables = [
                                    'page_title'   =>   Language::trans('Room Usages'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];

        $is_search_result = false;
        $model = new MeterReading();
        $leaf_api = new LeafAPI();
        $total = 0;
        $setting = new Setting();
        $listing = [];
        $houses_detail = [];
        $meter_register_id_arr = array();
       
        /*$date_range = ['date_started' => $model->getDate($request->input('date_started')),
                        'date_ended'    => $model->getDate($request->input('date_ended'))
                        ];*/

       

        if ($request->all()) {
            //For result
            $input = $request->input();
            $temp_daterange = explode(' - ',$input['daterange']);
            //dd($temp_daterange);
            $date_range = ['date_started' =>   date('Y-m-d H:m:s', strtotime($temp_daterange[0])) , 'date_ended' =>  date('Y-m-d H:m:s', strtotime($temp_daterange[1])) ];
            $report_title = Setting::get_sunway_report_title_by_type_and_date_range(Setting::SUNWAY_ROOM_USAGE_REPORT,$date_range);
              

            if ($request->input()) {
                $is_search_result = true;
                $leaf_house_id = $request->input('leaf_house_id');
                $room_id = $request->input('leaf_room_id');
        
                if($leaf_house_id == 0){
                   $room_title = "All rooms with meter registers";
                   $houses_detail = $leaf_api->get_houses_with_meter_register_detail();

                }else if($leaf_house_id !=0 && $room_id != 0){
                   
                   $meter_register_id_arr = MeterRegister::where('leaf_room_id','=',$room_id)->pluck('id')->toArray();
                   $house = $leaf_api->get_houses_with_meter_register_detail($leaf_house_id);
                   array_push($houses_detail , $house);
                   $room_title = "All rooms in ".$house['house_unit'];

                }else if($leaf_house_id !=0 && $room_id == 0){      

                   $house = $leaf_api->get_houses_with_meter_register_detail($leaf_house_id);
                   array_push($houses_detail , $house);
                   $room_title = LeafAPI::get_room_name_by_leaf_room_id($room_id);
                }
            }

            $houses = isset($houses_detail[0]) ? $houses_detail[0] : $houses_detail;
            if(isset($houses_detail) && $room_id == 0)
            {
              foreach($houses as $house)
              {
                    foreach ($house['house_rooms'] as $room) 
                    {
                        if(isset($room['meter']))
                        {
                             array_push($meter_register_id_arr, $room['meter']['id']);
                        }
                    }
              }  
            }

            //For result
            $input = $request->input();
            $temp_daterange = explode(' - ',$input['daterange']);
            //dd($temp_daterange);
            $date_range = ['date_started' =>   date('Y-m-d', strtotime($temp_daterange[0])) , 'date_ended' =>  date('Y-m-d', strtotime($temp_daterange[1])) ];
   

            $report_detail['date_range'] = $date_range['date_started'].'-'.$date_range['date_ended'];
            $report_detail['report_title'] = $report_title;
            $report_detail['room_title'] = $room_title;


            if (count($meter_register_id_arr) > 0) {
                $temp_listing = MeterReading::whereIn('meter_register_id', $meter_register_id_arr)->whereBetween('current_date', [$setting->setDate($date_range['date_started']), $setting->setDate($date_range['date_ended'])])->get();

                foreach ($temp_listing  as $row) {
                    $listing[$row['meter_register_id']] = isset($listing[$row['meter_register_id']]) ? $listing[$row['meter_register_id']] : array();
                    array_push($listing[$row['meter_register_id']] , $row);
                }

            }

            switch ($input['export_by']) {
                case 'pdf':
                    $pdf                =   new RoomUsageReportPdf();
                    $pdf->AddPage('L', 'Letter');
                    $pdf->listing       =   $listing;
                    $pdf->houses_detail =   $houses_detail;
                    $pdf->setting       =   new  Setting();
                    $pdf->model         =   $model;
                    $pdf->header_title  =   $this->header;
                    $pdf->room_title    =   $room_title;
                    $pdf->setTitle($report_title);
                    $pdf->SetAutoPageBreak(true,20);
                    $pdf->is_finished   =   false;
                    $pdf->date_range    =   $model->getDate($date_range['date_started']).' - '.$model->getDate($date_range['date_ended']);
                    $pdf->room_no       =   LeafAPI::get_room_name_by_leaf_room_id($room_id);
                    $pdf->content($room_id);
                    return response($pdf->Output(), 200)
                                 ->header('Content-Type', 'application/pdf');
                    break;

                case 'excel':

                   //// Setting::init_php_excel();
                    $objPHPExcel = new UCRoomUsageReportExcel($listing);
                    $objPHPExcel->content($listing,$houses_detail,$report_detail);
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
                   
                    $file = storage_path('framework/views/'.str_replace(':', '-', $report_title).'.xlsx');
                    $objWriter->save($file);

                     return response()->download($file, $report_title.'.xlsx');
                     break;

                case 'html':
                   $leaf_room_id = $request->input('leaf_room_id');
                   return view(Setting::UI_VERSION.'utility_charges.reports.room_usages', compact('houses_detail','page_variables','is_model_page','model','listing','total','leaf_room_id','is_search_result'));

                default:
                    break;
            }
        }
        return view(Setting::UI_VERSION.'utility_charges.reports.room_usages', compact('page_variables','is_model_page','model','listing','total','is_search_result'));
    }


    public function getMonthlyUsages(Request $request)
    {
        //dd(Setting::UI_VERSION.'utility_charges.reports.monthly_usages');
        //dd($request->input());
        $this->initComponent();

        $is_model_page  = false;
        $page_variables = [
                                    'page_title'   =>   Language::trans('Monthly Usages'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];

        /*$export_type = null;
        foreach ($this->report_export_type as $type)
        {
            if($request->input($type) !== null)
            {
                $export_type = $type;
            }
        }*/

        $setting = new Setting();
        $is_search_result = false;
        $isMeterRegister = false;
        $month_started = '01-'.$request->input('month_started');
        $month_ended = '31-'.$request->input('month_ended');
        $model = new MeterReading();
        $leaf_api = new LeafAPI();
       
        $report_detail;
        
    
        $total = 0;
        $listing = [];
        $selected_meter_ids = array();
        $date_range = ['date_started' => $month_started,
                        'date_ended'    => $month_ended
                        ];

        $report_title = Setting::get_sunway_report_title_by_type_and_date_range(Setting::SUNWAY_MONTHLY_USAGE_REPORT,$date_range);

        if ($request->input()) {

            $rooms = $leaf_api->get_houses_with_meter_register_detail(null,true);
            $house_room_detail = $leaf_api->get_self_houses(true);
            $houses_detail = [];

            $is_search_result = true;
            $leaf_house_id = $request->input('leaf_house_id');
            $room_id = $request->input('leaf_room_id');
           
            if($leaf_house_id == 0){
                 $room_title = "All rooms with meter registers";
                 $houses_detail = $rooms;

                 foreach($houses_detail as $house)
                 {  
                     $selected_meter_ids = array_merge($selected_meter_ids , array_column(  array_column( $house['house_rooms'] , 'meter' ) , 'id'));
                      
                 }

            }else if($leaf_house_id !=0 && $room_id != 0){

                $house = $leaf_api->get_house_by_house_id($leaf_house_id);
                array_push($houses_detail , $house);
                $room_title = "All rooms in ".$house['house_unit'];
                $selected_meter_ids = array_column(  array_column( $house['house_rooms'] , 'meter' ) , 'id');


            }else if($leaf_house_id !=0 && $room_id == 0){

                   $house = $leaf_api->get_house_by_house_id($leaf_house_id);
                   array_push($houses_detail , $house);
                   $room_title = LeafAPI::get_room_name_by_leaf_room_id($room_id);
                   foreach($houses_detail as $house)
                   {
                         $selected_meter_ids =  array_merge($selected_meter_ids , array_column(  array_column( $house['house_rooms'] , 'meter' ) , 'id'));
                   }
            }
         //echo json_encode($selected_meter_ids);

            $meter_ids = trim( trim( json_encode( $selected_meter_ids) , '[' ), ']') ;
          
            if($meter_ids == '')
            {
                $meter_ids = '9999999999999';
            }
            $meter_listing = DB::select('SELECT * FROM `meter_registers` WHERE `ID` IN  ('.$meter_ids.')' );
        

            $listing = DB::select('SELECT `meter_register_id` ,`current_date`, COUNT(*) as total_hours, AVG(current_usage) as average_usage, MAX(current_usage) as max_usage, MIN(current_usage) as min_usage, SUM(current_usage) as total_usage FROM `meter_readings` WHERE `current_date` >= ? AND `current_date` <= ? AND `meter_register_id` IN  ('.$meter_ids.') GROUP BY `meter_register_id`,YEAR(`current_date`), MONTH(`current_date`)  ASC', [$model->setDate($month_started), $model->setDate($month_ended)]);
 //dd($listing);
            //$listing = DB::select('SELECT `meter_register_id` ,`current_date`, COUNT(*) as total_hours, AVG(current_usage) as average_usage, MAX(current_usage) as max_usage, MIN(current_usage) as min_usage, SUM(current_usage) as total_usage FROM `meter_readings` WHERE `current_date` >= ? AND `current_date` <= ? GROUP BY `meter_register_id`,YEAR(`current_date`), MONTH(`current_date`)  ASC', [$model->setDate($month_started), $model->setDate($month_ended)]);
//dd($listing);
        // /    dd("x");
            //dd($listing);
             //dd($houses_detail);
            $report_detail['date_range'] = $date_range['date_started'].'-'.$date_range['date_ended'];
            $report_detail['report_title'] = $report_title;
            $report_detail['room_title'] = $room_title;
//dd($model->setDate($month_started).'-'.$model->setDate($month_ended));
//dd("'SELECT `meter_register_id` ,`current_date`, COUNT(*) as total_hours, AVG(current_usage) as average_usage, MAX(current_usage) as max_usage, MIN(current_usage) as min_usage, SUM(current_usage) as total_usage FROM `meter_reading_monthlys` WHERE `current_date` >= ? AND `current_date` <= ? AND `meter_register_id` IN  ('.$meter_ids.') GROUP BY `meter_register_id`,YEAR(`current_date`), MONTH(`current_date`)  ASC");
            
            $input = $request->input();
            $is_show_tenant = true;
            switch ($input['export_by']) {
                case 'pdf':
                    $pdf                =   new MonthlyUsageReportPdf();
                    $pdf->AddPage('L', 'Letter');
                    $pdf->houses_detail =   $houses_detail;
                    $pdf->report_detail =   $report_detail;
                    $pdf->listing       =   $listing;
                    $pdf->setting       =   new  Setting();
                    $pdf->model         =   $model;
                    $pdf->is_finished   =   false;
                    $pdf->is_show_tenant   =   true;
                    $pdf->header_title  =   $this->header;
                    $pdf->room_title    =   $room_title;
                    $pdf->setTitle($report_title);
                    $pdf->date_range    =   $report_detail['date_range'];
                    $pdf->content();
  

                    return response($pdf->Output(), 200)
                                 ->header('Content-Type', 'application/pdf');
                    break;


                case 'excel':   
                  // // Setting::init_php_excel(); 
                    $objPHPExcel = new UCMonthlyUsageReportExcel();
                    $objPHPExcel->setting = new Setting();
                    $objPHPExcel->content($listing,$houses_detail,$report_detail);
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

                     return response()->download($file, $report_title.'.xlsx');
                     break;

                case 'html':

                    return view(Setting::UI_VERSION.'utility_charges.reports.monthly_usages', compact('page_variables','is_model_page','model','listing','total','house_room_detail','houses_detail','is_search_result','is_show_tenant'));
                    break;

                default:
                    break;
            }
        }

        return view(Setting::UI_VERSION.'utility_charges.reports.monthly_usages', compact('page_variables','is_model_page','model','listing','total','house_room_detail','is_search_result'));
    }

    public function getMonthlyUsagesObsoleted(Request $request)
    {
        //dd(Setting::UI_VERSION.'utility_charges.reports.monthly_usages');
        //dd($request->input());

        $is_model_page  = false;
        $page_variables = [
                                    'page_title'   =>   Language::trans('Monthly Usages'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];

        $setting = new Setting();
        $is_search_result = false;
        $isMeterRegister = false;
        $month_started = '01-'.$request->input('month_started');
        $month_ended = '31-'.$request->input('month_ended');
        $model = new MeterReading();
        $leaf_api = new LeafAPI();
        $house_room_detail = $leaf_api->get_self_houses();
        $houses_detail = [];
        $report_detail;
        $rooms = $leaf_api->get_houses_with_meter_register_detail();
        $total = 0;
        $listing = [];
        $date_range = ['date_started' => $month_started,
                        'date_ended'    => $month_ended
                        ];

        $report_title = Setting::get_sunway_report_title_by_type_and_date_range(Setting::SUNWAY_MONTHLY_USAGE_REPORT,$date_range);

        if ($request->input()) {
            $is_search_result = true;
            $leaf_house_id = $request->input('leaf_house_id');
            $room_id = $request->input('leaf_room_id');
           
            if($leaf_house_id == 0){
                 $room_title = "All rooms with meter registers";
                 $houses_detail = $rooms;
            }else if($leaf_house_id !=0 && $room_id != 0){

                $house = $leaf_api->get_house_by_house_id($leaf_house_id);
                array_push($houses_detail , $house);
                $room_title = "All rooms in ".$house['house_unit'];

            }else if($leaf_house_id !=0 && $room_id == 0){

                   $house = $leaf_api->get_house_by_house_id($leaf_house_id);
                   array_push($houses_detail , $house);
                   $room_title = LeafAPI::get_room_name_by_leaf_room_id($room_id);
            }
            
            $listing = DB::select('SELECT `meter_register_id` ,`current_date`, COUNT(*) as total_hours, AVG(current_usage) as average_usage, MAX(current_usage) as max_usage, MIN(current_usage) as min_usage, SUM(current_usage) as total_usage FROM `meter_readings` WHERE `current_date` >= ? AND `current_date` <= ? GROUP BY `meter_register_id`,YEAR(`current_date`), MONTH(`current_date`)  ASC', [$model->setDate($month_started), $model->setDate($month_ended)]);

             //dd($houses_detail);
            $report_detail['date_range'] = $date_range['date_started'].'-'.$date_range['date_ended'];
            $report_detail['report_title'] = $report_title;
            $report_detail['room_title'] = $room_title;

            switch ($request->input('export_by')) {
                case 'pdf':
                    $pdf                =   new MonthlyUsageReportPdf();
                    $pdf->AddPage('L', 'Letter');
                    $pdf->houses_detail =   $houses_detail;
                    $pdf->listing       =   $listing;
                    $pdf->setting       =   new  Setting();
                    $pdf->model         =   $model;
                    $pdf->is_finished   =   false;
                    $pdf->header_title  =   $report_title;
                    $pdf->setTitle($report_title);
                    $pdf->date_range    =   $model->getDate($month_started).' - '.$model->getDate($month_ended);
                    $pdf->content();
  
                    return response($pdf->Output(), 200)
                                 ->header('Content-Type', 'application/pdf');
                    break;

                case 'excel':   
                    //Setting::init_php_excel(); 
                    $objPHPExcel = new UCMonthlyUsageReportExcel();
                    $objPHPExcel->setting = new Setting();
                    $objPHPExcel->content($listing,$houses_detail,$report_detail);
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

                     return response()->download($file, $report_title.'.xlsx');
                     break;

                case 'html':

                    return view(Setting::UI_VERSION.'utility_charges.reports.monthly_usages', compact('page_variables','is_model_page','model','listing','total','house_room_detail','houses_detail','is_search_result'));
                    break;

                default:
                    break;
            }
        }

        return view(Setting::UI_VERSION.'utility_charges.reports.monthly_usages', compact('page_variables','is_model_page','model','listing','total','house_room_detail','is_search_result'));
    }

    public function getUserSummaryReport(Request $request)
    {
        $model = new UserAccountSummaryData();
        $page_title = Language::trans('Monthly Sales Report');
        $listing = UserAccountSummaryData::all();

        $report_type = $_GET['report_type'];
        $leaf_api = new LeafAPI();
        $rooms = $leaf_api->get_houses();
        $setting = new Setting();
        $listing = [];
        $total = 0;

        $date_range = ['date_started' => $month_started,
                    'date_ended'    => $month_ended
                    ];

        $report_title = 'User Account Summary Report';
        
           
        $pdf                =   new UserSummaryReport();
        $pdf->AddPage('L', 'Letter');
        $pdf->listing       =   $listing;
        $pdf->setting       =   $setting;
        $pdf->model         =   $model;
        $pdf->header_title  =   $this->header;
        $pdf->is_finished   =   false;

        $pdf->setTitle($report_title);
        $pdf->date_range    =   $model->getDate($month_started).' - '.$model->getDate($month_ended);
        $pdf->content();
        return response($pdf->Output(), 200)
                     ->header('Content-Type', 'application/pdf');
              
    }



    public function getUserSummaryReportWeb(Request $request)
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '4096M'); 
        $model =array();
        $listing =  CustomerPowerUsageSummary::all();
        $is_model_page = false;
        $page_title = Language::trans('User Account Summary Report');
        $report_title = 'User Account Summary Report';
        $page_variables = [
                                    'download_link'=>class_basename($this).'@getUserSummaryReportDownload',
                                    'page_title'   =>   Language::trans('User Account Summary Report'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];
        $leaf_api = new LeafAPI();
        $rooms = $leaf_api->get_houses();
        $setting = new Setting();
        $listing = [];
        $total = 0;

        $report_title = 'User Account Summary Report';
   
        return view(Setting::UI_VERSION.'utility_charges.reports.user_summaries', compact('page_variables','is_download','is_model_page','page_title','model','listing'));
  
              
    }
    public function getUserSummaryReportDownload(Request $request)
    {
        
        
        $report_title = "user_summary";
        //Language::trans('Outstanding Listing');
      
        $listing = null;//CustomerPowerUsageSummary::get_customer_with_credit_more_or_equal_than_by_leaf_group_id($min_credit,Company::get_group_id());
        $objPHPExcel = new UCUserSummaryReportExcel();
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
        /*$model =array();
        $listing =  CustomerPowerUsageSummary::all();
        $is_model_page = false;
        $page_title = Language::trans('User Account Summary Report');
        $report_title = 'User Account Summary Report';
        $page_variables = [
                                    'page_title'   =>   Language::trans('User Account Summary Report'),
                                    'return_url' => class_basename($this).'@getSalesReport',
                                ];
        $leaf_api = new LeafAPI();
        $rooms = $leaf_api->get_houses();
        $setting = new Setting();
        $listing = [];
        $total = 0;

        $report_title = 'User Account Summary Report';
   
        return view(Setting::UI_VERSION.'utility_charges.reports.user_summaries', compact('page_variables','is_model_page','page_title','model','listing'));
  
              */
    }

    public function initComponent()
    {
        //ini_set('max_execution_time', 3000);
        //ini_set('memory_limit', '4096M'); 
        $this->header = Company::where('leaf_group_id','=',Company::get_group_id())->first();
    }
}


