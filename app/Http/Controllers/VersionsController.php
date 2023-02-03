<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Schema;

class VersionsController extends Controller
{
    public function getResourcesUpdate(){
        if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->where('resource_name','=','Power Meter')->count()) {
                $permissions = [
                                //Power meter settings 
                                ['resource_name'=>'Power Meter','resource_label'=>'Current Power(Summary)','resource_description'=>'','resource_controller'=>'umeterreadings','resource_action'=>'statusdetail','resource_seq'=>'255','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],       
                            ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }

         if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->where('resource_name','=','Subsidy')->count()) {
                $permissions = [
                                //Subsidy settings 
                                ['resource_name'=>'Dashboard','resource_label'=>'Payment Summary','resource_description'=>'','resource_controller'=>'dashboards','resource_action'=>'customerpowerusagesummary','resource_seq'=>'256','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],       
                            ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }


        if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->where('resource_name','=','Subsidy')->count()) {
                $permissions = [
                                //Subsidy settings 
                                ['resource_name'=>'Subsidy','resource_label'=>'Add','resource_description'=>'','resource_controller'=>'umetersubsidiaries','resource_action'=>'new','resource_seq'=>'263','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Subsidy','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'umetersubsidiaries','resource_action'=>'edit','resource_seq'=>'264','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Subsidy','resource_label'=>'View','resource_description'=>'','resource_controller'=>'umetersubsidiaries','resource_action'=>'view','resource_seq'=>'265','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Subsidy','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'umetersubsidiaries','resource_action'=>'delete','resource_seq'=>'266','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                            ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }


         if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->where('resource_name','=','Power Meter Payment Test')->count()) {
                $permissions = [
                                //Power meter settings 
                                ['resource_name'=>'Power Meter Payment Test','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'developers','resource_action'=>'paymentestindex','resource_seq'=>'257','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Power Meter Payment Test','resource_label'=>'Add','resource_description'=>'','resource_controller'=>'developers','resource_action'=>'new','resource_seq'=>'258','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Power Meter Payment Test','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'developers','resource_action'=>'edit','resource_seq'=>'259','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Power Meter Payment Test','resource_label'=>'View','resource_description'=>'','resource_controller'=>'developers','resource_action'=>'view','resource_seq'=>'260','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Power Meter Payment Test','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'developers','resource_action'=>'delete','resource_seq'=>'261','resource_status'=>'1','umrah'=>'','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                            ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }
    }

    public function getIndex()
    {
    	if (!Schema::hasColumn('vouchers', 'status')) {
    	    Schema::table('vouchers', function($table){
    	    	$table->boolean('status')->after('store_id');
    	    });
    	}
        if (!Schema::hasColumn('to_do_lists', 'date')) {
            Schema::table('to_do_lists', function($table){
                $table->integer('category_id')->unsigned()->after('name');
                $table->date('date')->nullable()->after('category_id');
                $table->time('time')->nullable()->after('date');
            });
        }
        if (!Schema::hasTable('to_do_list_categories')) {
            Schema::create('to_do_list_categories', function($table){
                $table->increments('id');
                $table->string('name');
                $table->boolean('status');
                $table->integer('created_by')->unsigned();
                $table->integer('updated_by')->unsigned();
                $table->timestamps();
            });
        }
        if (!Schema::hasColumn('to_do_lists', 'is_checked')) {
            Schema::table('to_do_lists', function($table){
                $table->boolean('is_checked')->after('status');
            });
        }
        if (!Schema::hasTable('translation_words')) {
            Schema::create('translation_words', function ($table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->text('word_str');
                $table->integer('translation_of_id_word')->nullable();
                $table->boolean('is_active');
            });
        }
        if (!Schema::hasTable('translation_languages')) {
            Schema::create('translation_languages', function ($table) {
                $table->increments('id');
                $table->string('name');
            });
        }
        if (!DB::table('translation_languages')->count()) {
            DB::table('translation_languages')->insert(['name'=>'Malay']);
        }
        if (!Schema::hasColumn('meter_registers', 'ip_address')) {
            Schema::table('meter_registers', function($table){
                $table->string('ip_address')->after('meter_id');
            });
        }
        if (!Schema::hasColumn('meter_registers', 'created_by')) {
            Schema::table('meter_registers', function($table){
                $table->integer('created_by')->unsigned()->after('status');
                $table->integer('updated_by')->unsigned()->after('created_by');
            });
        }
        if (!Schema::hasColumn('cities', 'leaf_group_id')) {
            $tables = ['cities','companies','countries','meter_registers','states','stores','to_do_lists','to_do_list_categories','utility_charges','vouchers'];
            foreach ($tables as $table) {
                Schema::table($table, function($table){
                    $table->integer('leaf_group_id')->unsigned();
                });
            }
        }
        if (Schema::hasTable('meter_registers')) {
            if (Schema::hasColumn('meter_registers', 'house_no')) {
                DB::table('meter_registers')->truncate();
                Schema::table('meter_registers', function($table){
                    $table->dropColumn('house_no');
                    $table->integer('leaf_room_id')->unsigned()->after('ip_address');
                });
            }
        }
        if (!Schema::hasColumn('companies', 'min_credit')) {
            Schema::table('companies', function($table){
                $table->boolean('is_min_credit')->after('website');
                $table->double('min_credit',8,2)->after('is_min_credit');
            });
        }
        if (!Schema::hasColumn('meter_registers', 'account_no')) {
            Schema::table('meter_registers', function($table){
                $table->string('account_no')->after('id');
                $table->string('contract_no')->after('account_no');
                $table->double('deposit',8,2)->after('utility_charge_id');
                $table->integer('meter_class_id')->unsigned()->after('meter_id');
            });
        }
        if (!Schema::hasColumn('meter_invoices', 'document_date')) {
            Schema::table('meter_invoices', function($table){
                $table->date('document_date')->after('document_no');
            });
        }
        if (!Schema::hasColumn('meter_invoices', 'gst_amount')) {
            Schema::table('meter_invoices', function($table){
                $table->double('icpt_amount',8,2)->after('current_amount');
                $table->double('current_month_amount',8,2)->after('icpt_amount');
                $table->double('gst_amount',8,2)->after('current_month_amount');
                $table->double('kwtbb_amount',8,2)->after('gst_amount');
                $table->double('late_charge',8,2)->after('kwtbb_amount');
            });
        }
        if (!Schema::hasColumn('meter_invoice_items', 'is_gst')) {
            Schema::table('meter_invoice_items', function($table){
                $table->boolean('is_gst')->after('unit_price');
                $table->double('gst_amount',8,2)->after('is_gst');
                $table->double('total_amount',8,2)->after('total_price');
            });
        }
        if (!Schema::hasColumn('meter_readings', 'last_meter_reading')) {
            Schema::table('meter_readings', function($table){
                $table->string('last_meter_reading')->after('time_ended');
                $table->string('current_meter_reading')->after('last_meter_reading');
            });
            $meter_registers = DB::table('meter_readings')->groupBy('meter_register_id')->pluck('meter_register_id');
            foreach ($meter_registers as $meter) {
                $listing = DB::table('meter_readings')->where('meter_register_id','=',$meter)->get();
                $last_meter_reading = 0;
                foreach ($listing as $row) {
                    $current_meter_reading = $last_meter_reading + $row->usage;
                    DB::table('meter_readings')->where('id','=',$row->id)->update(['last_meter_reading'=>$last_meter_reading, 'current_meter_reading'=>$current_meter_reading]);
                    $last_meter_reading = $current_meter_reading;
                }
            }
        }
        if (!Schema::hasColumn('utility_charges', 'is_hourly')) {
            Schema::table('utility_charges', function($table){
                $table->boolean('is_hourly')->after('name');
                $table->double('hourly_rate',8,2)->after('is_hourly');
            });
            DB::table('utility_charges')->update(['is_hourly'=>false,'hourly_rate'=>'0.00']);
        }
        if (Schema::hasColumn('meter_invoice_items', 'meter_started')) {
            Schema::drop('meter_invoice_items');
            Schema::create('meter_invoice_items', function($table){
                $table->increments('id');
                $table->integer('meter_invoice_id')->unsigned();
                $table->string('meter_block');
                $table->string('meter_usage');
                $table->double('unit_price',8,2);
                $table->double('total_price',8,2);
                $table->boolean('is_gst');
                $table->double('gst_amount',8,2);
                $table->double('total_amount',8,2);
            });
        }
        if (!Schema::hasColumn('meter_invoices', 'is_paid')) {
            Schema::table('meter_invoices', function($table){
                $table->boolean('is_paid')->after('total_amount');
            });
        }
        if (!Schema::hasColumn('meter_invoices', 'due_date')) {
            Schema::table('meter_invoices', function($table){
                $table->date('due_date')->after('document_date');
            });
        }
        if (Schema::hasColumn('meter_readings', 'usage')) {
            Schema::table('meter_readings', function($table){
                $table->renameColumn('usage','current_usage');
            });
        }
        if (!Schema::hasColumn('companies', 'due_date_duration')) {
            Schema::table('companies', function($table){
                $table->integer('due_date_duration')->after('is_min_credit');
            });
        }
        if (!Schema::hasColumn('companies', 'is_prepaid')) {
            Schema::table('companies', function($table){
                $table->boolean('is_prepaid')->after('due_date_duration');
            });
        }
        if (!Schema::hasColumn('companies', 'is_inclusive')) {
            Schema::table('companies', function($table){
                $table->boolean('is_inclusive')->after('is_prepaid');
            });
            DB::table('companies')->update(['is_inclusive'=>true]);
        }
        if (!Schema::hasColumn('companies', 'is_transaction_charge')) {
            Schema::table('companies', function($table){
                $table->boolean('is_transaction_charge')->after('is_inclusive');
            });
        }
        if (!Schema::hasColumn('companies', 'transaction_percent')) {
            Schema::table('companies', function($table){
                $table->double('transaction_percent',8,2)->after('is_transaction_charge');
            });
        }
        if (!Schema::hasColumn('utransactions', 'transaction_charge_percent')) {
            Schema::table('utransactions', function($table){
                $table->string('transaction_charge_percent')->after('amount');
                $table->double('transaction_charge',8,2)->after('transaction_charge_percent');
                $table->string('transaction_charge_gst_percent')->after('transaction_charge');
                $table->double('transaction_charge_gst',8,2)->after('transaction_charge_gst_percent');
            });
        }
        if (!Schema::hasColumn('meter_classes', 'is_bonus')) {
            Schema::table('meter_classes', function($table){
                $table->boolean('is_bonus')->after('name');
                $table->integer('set_date')->after('is_bonus');
                $table->double('amount',8,2)->after('set_date');
            });
        }
        if (!DB::table('taxes')->count()) {
            $leaf_group_id = 282;
            $taxes = [
                        ['code'=>'AJP','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Any adjustment made to Input Tax.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'AJS','type'=>'sale','rate'=>'6.00','status'=>true,'remark'=>'Any adjustment made to Output Tax.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'BL','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Purchases with GST incurred but not claimable or known as Disallowance of Input Tax.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'DS','type'=>'sale','rate'=>'6.00','status'=>true,'remark'=>'Deemed supplies under GST legislations.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'ES','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Exempt supplies under GST legislations.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'GS','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Disregarded supplies under GST legislations.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'IES','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Incidental exempt supplies under GST legislations.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'IM','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Importation of goods with GST incurred.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'IM-CG','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Importation of capital goods with GST incurred.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'IS','type'=>'purchase','rate'=>'0.00','status'=>true,'remark'=>'Imports of goods under Approved Trader Scheme (ATS) whereas the payment of GST chargeable is suspended on the goods imported.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'NR','type'=>'purchase','rate'=>'0.00','status'=>true,'remark'=>'Purchase from non GST-registered supplier with no GST incurred.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'NS','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Matters to be treated as neither a supply of goods nor a supply of services.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'NTX','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Supplies with no tax chargeable.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'OP','type'=>'purchase','rate'=>'0.00','status'=>true,'remark'=>'Purchase transactions which is out of the scope of GST legislation.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'OS','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Out-of-scope supplies under GST legislations.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'OS-TXM','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Out-of-scope supplies made outside Malaysia which will be taxable if made in Malaysia.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'RS','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Relief supplies under GST legislations.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'SR','type'=>'sale','rate'=>'6.00','status'=>true,'remark'=>'Standard-rated supplies with GST Charged.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'SR-JWS','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Supplies under Approved Jeweller Scheme (AJS).','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'SR-MS','type'=>'sale','rate'=>'6.00','status'=>true,'remark'=>'Standard-rated supplies under Margin Scheme.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Purchases with GST incurred at 6% and directly attributable to taxable supplies.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX-CG','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Purchase with GST incurred for capital goods acquisition.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX-ES','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Purchase with GST incurred directly attributable to exempt supplies, and only applicable for partially exempt trader/mixed supplier.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX-FRS','type'=>'purchase','rate'=>'2.00','status'=>true,'remark'=>'Purchase under Flat Rate Scheme.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX-IES','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Purchase with GST incurred directly attributable to incidental exempt supplies.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX-NC','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'GST incurred and choose not to claim the input tax.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'TX-RE','type'=>'purchase','rate'=>'6.00','status'=>true,'remark'=>'Purchase with GST incurred that is not directly attributable to taxable or exempt supplies, and only applicable for partially exempt trader/mixed supplier.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'ZDA','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Supply of goods from Malaysia to Designated Area (Pulau Langkawi, Labuan, & Pulau Tioman) which are subject to zero rated supplies.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'ZP','type'=>'purchase','rate'=>'0.00','status'=>true,'remark'=>'Purchase from GST-registered supplier with subject to GST other than standard rate.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'ZRE','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Exportation of goods or services.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ['code'=>'ZRL','type'=>'sale','rate'=>'0.00','status'=>true,'remark'=>'Local supply of goods or services which are subject to zero rated supplies.','created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s'),'leaf_group_id'=>$leaf_group_id],
                        ];
            DB::table('taxes')->insert($taxes);
        }
        if (Schema::hasColumn('ar_invoice_items', 'uom_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['uom_id','discount','discount_txt','total_inclu_tax','created_at','updated_at']);
                $table->renameColumn('uom_name', 'uom');
                $table->renameColumn('tax_percent_txt', 'tax_txt');
                $table->renameColumn('total_exclu_tax', 'amount');
            });
        }
        if (!Schema::hasColumn('products', 'ncl_id')) {
            Schema::table('products', function($table){
                $table->string('ncl_id')->after('leaf_group_id');
            });
        }
        if (!Schema::hasColumn('customers', 'ncl_id')) {
            Schema::table('customers', function($table){
                $table->string('ncl_id')->after('leaf_group_id');
            });
        }
        if (!Schema::hasColumn('ar_invoices', 'ncl_id')) {
            Schema::table('ar_invoices', function($table){
                $table->string('ncl_id')->after('leaf_group_id');
            });
        }
        if (!Schema::hasTable('customer_logs')) {
            Schema::create('customer_logs', function($table){
                $table->increments('id');
                $table->string('leaf_user_email');
                $table->boolean('is_read');
                $table->timestamps();
                $table->string('leaf_group_id');
            });
        }
        if (Schema::hasColumn('users', 'leaf_id_group')) {
            Schema::table('users', function($table){
                $table->dropColumn('leaf_id_group');
            });
        }
        if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->count()) {
                $permissions = [
                                // device settings
                                ['resource_name'=>'Device Settings','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'umeterregisters','resource_action'=>'index','resource_seq'=>'11','resource_status'=>'1','umrah'=>'','billing'=>'','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Device Settings','resource_label'=>'New','resource_description'=>'','resource_controller'=>'umeterregisters','resource_action'=>'new','resource_seq'=>'12','resource_status'=>'1','umrah'=>'','billing'=>'','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Device Settings','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'umeterregisters','resource_action'=>'edit','resource_seq'=>'13','resource_status'=>'1','umrah'=>'','billing'=>'','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Device Settings','resource_label'=>'View','resource_description'=>'','resource_controller'=>'umeterregisters','resource_action'=>'view','resource_seq'=>'14','resource_status'=>'1','umrah'=>'','billing'=>'','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Device Settings','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'umeterregisters','resource_action'=>'delete','resource_seq'=>'15','resource_status'=>'1','umrah'=>'','billing'=>'','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // user group settings
                                ['resource_name'=>'User Groups','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'usergroups','resource_action'=>'index','resource_seq'=>'21','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'User Groups','resource_label'=>'New','resource_description'=>'','resource_controller'=>'usergroups','resource_action'=>'new','resource_seq'=>'22','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'User Groups','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'usergroups','resource_action'=>'edit','resource_seq'=>'23','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'User Groups','resource_label'=>'View','resource_description'=>'','resource_controller'=>'usergroups','resource_action'=>'view','resource_seq'=>'24','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'User Groups','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'usergroups','resource_action'=>'delete','resource_seq'=>'25','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // user settings
                                ['resource_name'=>'Users','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'users','resource_action'=>'index','resource_seq'=>'31','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Users','resource_label'=>'New','resource_description'=>'','resource_controller'=>'users','resource_action'=>'new','resource_seq'=>'32','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Users','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'users','resource_action'=>'edit','resource_seq'=>'33','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Users','resource_label'=>'View','resource_description'=>'','resource_controller'=>'users','resource_action'=>'view','resource_seq'=>'34','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Users','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'users','resource_action'=>'delete','resource_seq'=>'35','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // country settings
                                ['resource_name'=>'Countries','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'countries','resource_action'=>'index','resource_seq'=>'41','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Countries','resource_label'=>'New','resource_description'=>'','resource_controller'=>'countries','resource_action'=>'new','resource_seq'=>'42','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Countries','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'countries','resource_action'=>'edit','resource_seq'=>'43','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Countries','resource_label'=>'View','resource_description'=>'','resource_controller'=>'countries','resource_action'=>'view','resource_seq'=>'44','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Countries','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'countries','resource_action'=>'delete','resource_seq'=>'45','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // state settings
                                ['resource_name'=>'States','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'states','resource_action'=>'index','resource_seq'=>'51','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'States','resource_label'=>'New','resource_description'=>'','resource_controller'=>'states','resource_action'=>'new','resource_seq'=>'52','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'States','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'states','resource_action'=>'edit','resource_seq'=>'53','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'States','resource_label'=>'View','resource_description'=>'','resource_controller'=>'states','resource_action'=>'view','resource_seq'=>'54','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'States','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'states','resource_action'=>'delete','resource_seq'=>'55','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // city settings
                                ['resource_name'=>'Cities','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'cities','resource_action'=>'index','resource_seq'=>'61','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Cities','resource_label'=>'New','resource_description'=>'','resource_controller'=>'cities','resource_action'=>'new','resource_seq'=>'62','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Cities','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'cities','resource_action'=>'edit','resource_seq'=>'63','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Cities','resource_label'=>'View','resource_description'=>'','resource_controller'=>'cities','resource_action'=>'view','resource_seq'=>'64','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Cities','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'cities','resource_action'=>'delete','resource_seq'=>'65','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // price list settings
                                ['resource_name'=>'Price Lists','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'utilitycharges','resource_action'=>'index','resource_seq'=>'71','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Price Lists','resource_label'=>'New','resource_description'=>'','resource_controller'=>'utilitycharges','resource_action'=>'new','resource_seq'=>'72','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Price Lists','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'utilitycharges','resource_action'=>'edit','resource_seq'=>'73','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Price Lists','resource_label'=>'View','resource_description'=>'','resource_controller'=>'utilitycharges','resource_action'=>'view','resource_seq'=>'74','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Price Lists','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'utilitycharges','resource_action'=>'delete','resource_seq'=>'75','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // account class settings
                                ['resource_name'=>'Account Class','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'umeterclass','resource_action'=>'index','resource_seq'=>'81','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Account Class','resource_label'=>'New','resource_description'=>'','resource_controller'=>'umeterclass','resource_action'=>'new','resource_seq'=>'82','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Account Class','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'umeterclass','resource_action'=>'edit','resource_seq'=>'83','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Account Class','resource_label'=>'View','resource_description'=>'','resource_controller'=>'umeterclass','resource_action'=>'view','resource_seq'=>'84','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Account Class','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'umeterclass','resource_action'=>'delete','resource_seq'=>'85','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // help settings
                                ['resource_name'=>'Help','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'helps','resource_action'=>'index','resource_seq'=>'101','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Help','resource_label'=>'New','resource_description'=>'','resource_controller'=>'helps','resource_action'=>'new','resource_seq'=>'102','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Help','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'helps','resource_action'=>'edit','resource_seq'=>'103','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Help','resource_label'=>'View','resource_description'=>'','resource_controller'=>'helps','resource_action'=>'view','resource_seq'=>'104','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Help','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'helps','resource_action'=>'delete','resource_seq'=>'105','resource_status'=>'1','umrah'=>'1','billing'=>'1','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // invoice settings
                                ['resource_name'=>'Invoice','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'umeterinvoice','resource_action'=>'index','resource_seq'=>'101','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Invoice','resource_label'=>'New','resource_description'=>'','resource_controller'=>'umeterinvoice','resource_action'=>'new','resource_seq'=>'102','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Invoice','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'umeterinvoice','resource_action'=>'edit','resource_seq'=>'103','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Invoice','resource_label'=>'View','resource_description'=>'','resource_controller'=>'umeterinvoice','resource_action'=>'view','resource_seq'=>'104','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // reports settings
                                ['resource_name'=>'Reports','resource_label'=>'Current Power','resource_description'=>'','resource_controller'=>'umeterregisters','resource_action'=>'status','resource_seq'=>'501','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Reports','resource_label'=>'Room Usages','resource_description'=>'','resource_controller'=>'reports','resource_action'=>'roomusages','resource_seq'=>'502','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Reports','resource_label'=>'Monthly Usages','resource_description'=>'','resource_controller'=>'reports','resource_action'=>'monthlyusages','resource_seq'=>'503','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Reports','resource_label'=>'Invoices Reports','resource_description'=>'','resource_controller'=>'reports','resource_action'=>'invoices','resource_seq'=>'504','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Reports','resource_label'=>'Monthly Sales','resource_description'=>'','resource_controller'=>'reports','resource_action'=>'monthlysales','resource_seq'=>'505','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                // general settings
                                ['resource_name'=>'General','resource_label'=>'Settings','resource_description'=>'','resource_controller'=>'settings','resource_action'=>'index','resource_seq'=>'511','resource_status'=>'1','umrah'=>'0','billing'=>'0','power_meter'=>'1','created_at'=>$today,'updated_at'=>$today],
                                ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }
        if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->where('resource_name','=','AR Invoices')->count()) {
                $permissions = [
                                // device settings
                                ['resource_name'=>'AR Invoices','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'arinvoices','resource_action'=>'index','resource_seq'=>'111','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'AR Invoices','resource_label'=>'New','resource_description'=>'','resource_controller'=>'arinvoices','resource_action'=>'new','resource_seq'=>'112','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'AR Invoices','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'arinvoices','resource_action'=>'edit','resource_seq'=>'113','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'AR Invoices','resource_label'=>'View','resource_description'=>'','resource_controller'=>'arinvoices','resource_action'=>'view','resource_seq'=>'114','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'AR Invoices','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'arinvoices','resource_action'=>'delete','resource_seq'=>'115','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'AR Invoices','resource_label'=>'Print','resource_description'=>'','resource_controller'=>'arinvoices','resource_action'=>'print','resource_seq'=>'116','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'AR Invoices','resource_label'=>'Sales Report','resource_description'=>'','resource_controller'=>'reports','resource_action'=>'salesreport','resource_seq'=>'117','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // booking settings
                                ['resource_name'=>'Booking','resource_label'=>'Facility','resource_description'=>'','resource_controller'=>'iframes','resource_action'=>'bookingfacility','resource_seq'=>'121','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // customer group settings
                                ['resource_name'=>'Customer Group','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'customergroups','resource_action'=>'index','resource_seq'=>'131','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer Group','resource_label'=>'New','resource_description'=>'','resource_controller'=>'customergroups','resource_action'=>'new','resource_seq'=>'132','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer Group','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'customergroups','resource_action'=>'edit','resource_seq'=>'133','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer Group','resource_label'=>'View','resource_description'=>'','resource_controller'=>'customergroups','resource_action'=>'view','resource_seq'=>'134','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer Group','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'customergroups','resource_action'=>'delete','resource_seq'=>'135','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // customer settings
                                ['resource_name'=>'Customer','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'customers','resource_action'=>'index','resource_seq'=>'141','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer','resource_label'=>'New','resource_description'=>'','resource_controller'=>'customers','resource_action'=>'new','resource_seq'=>'142','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'customers','resource_action'=>'edit','resource_seq'=>'143','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer','resource_label'=>'View','resource_description'=>'','resource_controller'=>'customers','resource_action'=>'view','resource_seq'=>'144','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Customer','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'customers','resource_action'=>'delete','resource_seq'=>'145','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // uom settings
                                ['resource_name'=>'UOM','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'uoms','resource_action'=>'index','resource_seq'=>'151','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'UOM','resource_label'=>'New','resource_description'=>'','resource_controller'=>'uoms','resource_action'=>'new','resource_seq'=>'152','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'UOM','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'uoms','resource_action'=>'edit','resource_seq'=>'153','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'UOM','resource_label'=>'View','resource_description'=>'','resource_controller'=>'uoms','resource_action'=>'view','resource_seq'=>'154','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'UOM','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'uoms','resource_action'=>'delete','resource_seq'=>'155','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // location settings
                                ['resource_name'=>'Location','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'locations','resource_action'=>'index','resource_seq'=>'161','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Location','resource_label'=>'New','resource_description'=>'','resource_controller'=>'locations','resource_action'=>'new','resource_seq'=>'162','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Location','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'locations','resource_action'=>'edit','resource_seq'=>'163','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Location','resource_label'=>'View','resource_description'=>'','resource_controller'=>'locations','resource_action'=>'view','resource_seq'=>'164','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Location','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'locations','resource_action'=>'delete','resource_seq'=>'165','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // product category settings
                                ['resource_name'=>'Product Category','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'productcategories','resource_action'=>'index','resource_seq'=>'171','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product Category','resource_label'=>'New','resource_description'=>'','resource_controller'=>'productcategories','resource_action'=>'new','resource_seq'=>'172','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product Category','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'productcategories','resource_action'=>'edit','resource_seq'=>'173','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product Category','resource_label'=>'View','resource_description'=>'','resource_controller'=>'productcategories','resource_action'=>'view','resource_seq'=>'174','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product Category','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'productcategories','resource_action'=>'delete','resource_seq'=>'175','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // product settings
                                ['resource_name'=>'Product','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'products','resource_action'=>'index','resource_seq'=>'181','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product','resource_label'=>'New','resource_description'=>'','resource_controller'=>'products','resource_action'=>'new','resource_seq'=>'182','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'products','resource_action'=>'edit','resource_seq'=>'183','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product','resource_label'=>'View','resource_description'=>'','resource_controller'=>'products','resource_action'=>'view','resource_seq'=>'184','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Product','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'products','resource_action'=>'delete','resource_seq'=>'185','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // payment term settings
                                ['resource_name'=>'Payment Term','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'paymentterms','resource_action'=>'index','resource_seq'=>'191','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Payment Term','resource_label'=>'New','resource_description'=>'','resource_controller'=>'paymentterms','resource_action'=>'new','resource_seq'=>'192','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Payment Term','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'paymentterms','resource_action'=>'edit','resource_seq'=>'193','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Payment Term','resource_label'=>'View','resource_description'=>'','resource_controller'=>'paymentterms','resource_action'=>'view','resource_seq'=>'194','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Payment Term','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'paymentterms','resource_action'=>'delete','resource_seq'=>'195','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // currency settings
                                ['resource_name'=>'Currency','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'currencies','resource_action'=>'index','resource_seq'=>'201','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Currency','resource_label'=>'New','resource_description'=>'','resource_controller'=>'currencies','resource_action'=>'new','resource_seq'=>'202','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Currency','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'currencies','resource_action'=>'edit','resource_seq'=>'203','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Currency','resource_label'=>'View','resource_description'=>'','resource_controller'=>'currencies','resource_action'=>'view','resource_seq'=>'204','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Currency','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'currencies','resource_action'=>'delete','resource_seq'=>'205','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                // tax settings
                                ['resource_name'=>'Tax','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'taxes','resource_action'=>'index','resource_seq'=>'211','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Tax','resource_label'=>'New','resource_description'=>'','resource_controller'=>'taxes','resource_action'=>'new','resource_seq'=>'212','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Tax','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'taxes','resource_action'=>'edit','resource_seq'=>'213','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Tax','resource_label'=>'View','resource_description'=>'','resource_controller'=>'taxes','resource_action'=>'view','resource_seq'=>'214','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Tax','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'taxes','resource_action'=>'delete','resource_seq'=>'215','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],  
                            ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }
        if (!Schema::hasTable('user_groups')) {
            Schema::create('user_groups', function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->text('json_permissions');
                $table->text('remark');
                $table->boolean('status');
                $table->integer('created_by')->unsigned();
                $table->integer('updated_by')->unsigned();
                $table->timestamps();
                $table->integer('leaf_group_id')->unsigned();
            });
        }
        if (!Schema::hasColumn('user_groups', 'remark')) {
            Schema::table('user_groups', function($table){
                $table->text('remark')->after('json_permissions');
            });
        }
        if (Schema::hasColumn('users', 'leaf_id_group')) {
            Schema::table('users', function($table){
                $table->dropColumn('leaf_id_group');
            });
        }
        if (!Schema::hasColumn('companies', 'notification_credit')) {
            Schema::table('companies', function($table){
                $table->boolean('notification_credit')->after('is_prepaid');
            });
            DB::table('companies')->update(['notification_credit'=>true]);
        }
        if (Schema::hasTable('resources')) {
            $today = date('Y-m-d H:i:s');
            if (!DB::table('resources')->where('resource_name','=','Ticket Complaint')->count()) {
                $permissions = [
                                // device settings
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'Index','resource_description'=>'','resource_controller'=>'tickets','resource_action'=>'index','resource_seq'=>'121','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'New','resource_description'=>'','resource_controller'=>'tickets','resource_action'=>'new','resource_seq'=>'122','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'Edit','resource_description'=>'','resource_controller'=>'tickets','resource_action'=>'edit','resource_seq'=>'123','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'View','resource_description'=>'','resource_controller'=>'tickets','resource_action'=>'view','resource_seq'=>'124','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'Delete','resource_description'=>'','resource_controller'=>'tickets','resource_action'=>'delete','resource_seq'=>'125','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'Print','resource_description'=>'','resource_controller'=>'tickets','resource_action'=>'print','resource_seq'=>'126','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                                ['resource_name'=>'Ticket Complaint','resource_label'=>'Ticket Complaint Report','resource_description'=>'','resource_controller'=>'reports','resource_action'=>'ticketcomplaintreport','resource_seq'=>'127','resource_status'=>'1','umrah'=>'','billing'=>'1','power_meter'=>'0','created_at'=>$today,'updated_at'=>$today],
                            ];
            }
            if (isset($permissions)) {
                DB::table('resources')->insert($permissions);
            }
        }
        if (!Schema::hasTable('developers')) {
            Schema::create('developers', function($table){
                $table->increments('id');
                $table->string('name');
                $table->string('email');
                $table->boolean('is_main');
            });
            $developers = [
                            ['name'=>'Peter Ooi','email'=>'peterooi83@gmail.com','is_main'=>true],
                            ['name'=>'Priya','email'=>'priyam@sunway.com.my','is_main'=>false],
                            ['name'=>'Wei Nam','email'=>'weinam0110@gmail.com','is_main'=>true],
                            ['name'=>'Khai Yet','email'=>'adelfried1227a@gmail.com','is_main'=>true],
                            ['name'=>'Khai Yet','email'=>'adelfried1227a@hotmail.com','is_main'=>true],
                            ];
            DB::table('developers')->insert($developers);
        }

    	return 'Tables indexing was completely';
    }
}
