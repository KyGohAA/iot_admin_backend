<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\Company;
use App\Setting;
use App\ToDoList;
use App\Language;
use App\UserVoucher;
use App\ToDoListCategory;

class AppsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth', ['except'=>['getDashboard']]);
		$this->auth = 5560;
		// $this->auth = Auth::id();
		$this->company = new Company();
	}

	public function getIndex(Request $request)
	{
        if (!$request->input('group_id')) {
            return Language::trans('please select group before access.');
        }
        $this->company->set_group_id($request->input('group_id'));
        return redirect()->action('AppsController@getStores');
	}

	public function getDashboard()
	{
        $page_title   =   Language::trans('Documentation Page');
		return view('dashboards.index', compact('page_title'));
	}

	public function getStores()
	{
        $page_title   =   Language::trans('Stores');
		$stores = UserVoucher::where('user_id','=',$this->auth)->groupBy('store_id')->get();
		return view('umrah.apps.stores', compact('stores','page_title'));
	}

	public function getToDoListCategories()
	{
        $page_title   =   Language::trans('To Do Categories');
		$to_do_list_categories = ToDoListCategory::ofAvailable('status',true)->get();
		return view('umrah.apps.to_do_list_categories', compact('to_do_list_categories','page_title'));
	}

	public function getToDoLists(Request $request)
	{
        $page_title   =   Language::trans('To Do');
		$to_do_lists = ToDoList::ofAvailable('status',true)
								->where('category_id','=',$request->input('category_id'))
								->orderBy('date','asc')
								->orderBy('time','asc')
								->get();
		return view('umrah.apps.to_do_lists', compact('to_do_lists','page_title'));
	}

	public function getToDoListView($id)
	{
        $page_title   =   Language::trans('To Do (Info)');
        if (!$model = ToDoList::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
		return view('umrah.apps.to_do_lists.view', compact('model','page_title'));
	}

	public function getToDoChecked($id)
	{
        if (!$model = ToDoList::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $model->stamp_checked();
		return redirect()->action('AppsController@getToDoListView', [$id]);
	}

	public function getMap()
	{
        $page_title   =   Language::trans('Map');
		return view('umrah.apps.map', compact('page_title'));
	}

	public function getVouchers($store_id)
	{
        $page_title   =   Language::trans('Vouchers');
		$vouchers = UserVoucher::where('user_vouchers.store_id','=',$store_id)
									->where('user_vouchers.user_id','=',$this->auth)
									->leftJoin('vouchers','vouchers.id','=','user_vouchers.voucher_id')
									->groupBy('voucher_id')
									->select([DB::raw('SUM(quantity) as total_quantity'),'vouchers.name','user_vouchers.id'])
									->get();
		return view('umrah.apps.vouchers', compact('vouchers','page_title'));
	}

	public function getVoucherDetail($id)
	{
        $page_title   =   Language::trans('Voucher (Info)');
        if (!$model = UserVoucher::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
		$voucher = $model->voucher()->first();

		return view('umrah.apps.voucher_detail', compact('model','voucher','page_title'));
	}

	public function getVoucherClaim(Request $request)
	{
        $page_title   =   Language::trans('Voucher (Claim)');
		if (!$id = $request->input('voucher')) {
            return redirect()->action('DashboardsController@getError', 404);
		}
		$setting = new Setting();
		$id = $setting->decrypt($id);
		$pass = true;

        if (!$model = UserVoucher::find($id)) {
            return redirect()->action('DashboardsController@getError', 404);
        }
        $status_msg = Language::trans('Transaction was successfully.');
		// check the store valid
        if ($model->store_id != Auth::user()->store_id) {
        	$pass = false;
	        $status_msg = Language::trans('You do not have permission to claim this voucher.');
        }
		$user_voucher = new UserVoucher();
		// check the quantity valid
		if ($user_voucher->check_quantity($model->user_id, $model->voucher_id) < 1) {
        	$pass = false;
	        $status_msg = Language::trans('Please check the quantity before do transaction.');
		}
		if ($pass) {
			// save the transaction into table
			$user_voucher->save_form($model->user_id, $model->store_id, $model->voucher_id, '-1');
		}

		// return view and notice that the transaction was successfully.
		return view('umrah.apps.claim_voucher', compact('status_msg','page_title'));
	}

}
