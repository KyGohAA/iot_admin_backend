<?php

namespace App;

use DB;
use Auth;
use Schema;
use Validator;
use App\LeafAPI;
use App\NclAPI;
use DateTime;

use Illuminate\Database\Eloquent\Builder;

class Customer extends ExtendModel
{
    protected $table = 'customers';
    public $timestamps = true;
    protected $listing_except_columns = ['is_suspend','acc_id_customer','id_house','leaf_room_id','leaf_id_user','id_house_member','registration_no','gst_no','customer_group_id','payment_term_id','currency_id','contact_person','phone_no_1','phone_no_2','fax_no','email','website','billing_address1','billing_address2','billing_postcode','billing_country_id','billing_state_id','billing_city_id','delivery_address1','delivery_address2','delivery_postcode','delivery_country_id','delivery_state_id','delivery_city_id','remark','created_by','updated_by','created_at','updated_at','leaf_group_id','ncl_id'];

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | Here to manage of Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */
    
    public function getCreditLimitAttribute($value)
    {
        return $this->getDouble($value);
    }


    public static function get_customer_by_leaf_id_user($leaf_id_user)
    {
        return User::where('leaf_id_user','=',$leaf_id_user)
                    ->first();
    }

    public static function get_customers_by_leaf_id_user_json($leaf_id_user_json)
    {
        return User::whereIn('leaf_id_user', json_decode($leaf_id_user_json))
                    ->get();
    }

    public static function get_customer_name_by_id($id)
    {
        $customer = Customer::where('id','=',$id)
                    ->first();

        return isset($customer['id']) ? $customer->name : '';
    }

    public static function get_customer_model_by_leaf_id_user($leaf_id_user)
    {  
        return static::where('leaf_id_user','=',$leaf_id_user)->first();
    }

    public static function get_customer_model_by_leaf_id_house_member($id_house_member)
    {  
        return  static::where('id_house_member','=',$id_house_member)->first();
    }

    public static function set_customer_from_leaf_2($leaf_id_user){

        $leaf_api = new LeafAPI();
        $customer = new Customer();
        $model = static::get_customer_model_by_leaf_id_user($leaf_id_user);
        //$date = date('Y-m-d', strtotime('now'));
        if($model['id'])
        {
            return true;
        }else
        {

            $member_model         =   $leaf_api->get_house_member_by_leaf_id_house_member($leaf_id_user /*, $date*/);
            $customer_model     =   Customer::get_customer_model_by_leaf_id_house_member($member_model['house_member_id_user']);
   
            if(isset($customer_model['id'])){
                return ;   
            }else{
                $customer->save_customer_from_leaf_house(null,$leaf_id_user);
            }
        }

        return ;   
    }

    public static function set_customer_from_leaf_pm($leaf_id_user){

        $leaf_api = new LeafAPI();
        $customer = new Customer();
        $model = static::get_customer_model_by_leaf_id_user($leaf_id_user);
        //$date = date('Y-m-d', strtotime('now'));
        if($model['id'])
        {
            return $model;
        }else
        {

            $member_model         =   $leaf_api->get_house_member_by_leaf_id_house_member($leaf_id_user /*, $date*/);
            $customer_model     =   Customer::get_customer_model_by_leaf_id_house_member($member_model['house_member_id_user']);
   
            if(isset($customer_model['id'])){
                return $customer_model;   
            }else{
                $customer->save_customer_from_leaf_house(null,$leaf_id_user);
            }
        }

        return $customer ;   
    }

    public static function set_customer_by_id_house_member($id_house_member){

        $leaf_api = new LeafAPI();
        $customer = new Customer();
        $model = static::get_customer_model_by_leaf_id_house_member($id_house_member);

        //$date = date('Y-m-d', strtotime('now'));
        if($model['id'])
        {
            return $model;
        }else
        {
            $model = $customer->save_customer_from_leaf_house(null,$id_house_member);
        }

        return  $model;   
    }

    public static function update_all_customer_from_leaf(){
        return ;
    }


    /*
    |--------------------------------------------------------------------------
    | Here to manage of relationships
    |--------------------------------------------------------------------------
    |
    */

    public function customer_group()
    {
        return $this->belongsTo('App\CustomerGroup', 'customer_group_id');
    }

    public function payment_term()
    {
        return $this->belongsTo('App\PaymentTerm', 'payment_term_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }

    public function billing_country()
    {
        return $this->belongsTo('App\Country', 'billing_country_id');
    }

    public function billing_state()
    {
        return $this->belongsTo('App\State', 'billing_state_id');
    }

    public function billing_city()
    {
        return $this->belongsTo('App\City', 'billing_city_id');
    }

    public function delivery_country()
    {
        return $this->belongsTo('App\Country', 'delivery_country_id');
    }

    public function delivery_state()
    {
        return $this->belongsTo('App\State', 'delivery_state_id');
    }

    public function delivery_city()
    {
        return $this->belongsTo('App\City', 'delivery_city_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of scope
    |--------------------------------------------------------------------------
    |
    */

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('owned_by', function (Builder $builder) {
            $builder->where('leaf_group_id', '=', Company::get_group_id());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of data's listing
    |--------------------------------------------------------------------------
    |
    */

    public static function combobox()
    {
        $leaf_api = new LeafAPI();
        return static::ofAvailable('status',true)
                                ->select(['id', DB::raw("CONCAT(code,' --- ',name)  AS name")])
                                ->orderBy('name','asc')
                                ->pluck('name','id')
                                ->prepend(Language::trans('Please select customer...'), '');
    }

    public static function combobox_from_leaf()
    {
        $leaf_api = new LeafAPI();
        return $leaf_api->get_customer_combobox();
    }

    public static function combobox_from_leaf_by_room_type($room_type)
    {  
        $leaf_api = new LeafAPI();
        $listing = $leaf_api->get_houses();
        $room_listing = array();
        $member_listing = array();
        
        foreach ($listing['house'] as $houses) {
            foreach ($houses['house_rooms'] as $room) {
                if($room['house_room_type'] == $room_type){
                       //array_push($room_listing, $room);
                    foreach ($room['house_room_members'] as $member) {
                       
                         $member_listing[$member['house_member_id_user']]  = $member['house_member_name'] ;
                        // $temp['leaf_id_user'] = $member['house_member_id_user'];
                        // $temp['name'] = $member['house_member_name'];
                        //array_push($member_listing, $temp);
                    }
                }
            }
        }
        
        return $member_listing;
    }

    public static function combobox_from_leaf_by_room_type_member_id($room_type)
    {  
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '4096M'); 
        $leaf_api = new LeafAPI();
        $listing = $leaf_api->get_houses();
        $room_listing = array();
        $member_listing = array();

        if($listing['house'] !== null){
              foreach ($listing['house'] as $houses) {
                      foreach ($houses['house_rooms'] as $room) {
                        if($room['house_room_type'] == $room_type){
                               //array_push($room_listing, $room);
                            foreach ($room['house_room_members'] as $member) {
                                
                                //$member_listing[$member['house_member_id_user']]  = $member['house_member_name'] ;
                                $member_listing[$member['id_house_member']]  = $member['house_member_name'] ;
                                // $temp['leaf_id_user'] = $member['house_member_id_user'];
                                // $temp['name'] = $member['house_member_name'];
                                //array_push($member_listing, $temp);
                            }
                        }
                    }
                }
                //dd($member_listing);
                return $member_listing;
        }else{

            return array();
        }
      
    }
    
    public static function by_name_combobox()
    {
        return static::ofAvailable('status',true)
                                ->orderBy('name','asc')
                                ->pluck('name','name')
                                ->prepend(Language::trans('Please select customer...'), '');
    }

    public static function total_count()
    {
        return static::ofAvailable('status',true)->count();
    }

    public static function get_today_new_record()
    {
        return static::ofAvailable('status',true)
                ->where('leaf_group_id','=' , Setting::get_leaf_group_id())
                ->where('created_at' ,'=' , date('Y-m-d', strtotime('now')))
                ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Here to manage of index listing displayed
    |--------------------------------------------------------------------------
    |
    */

    public function table_cols()
    {
        $except = $this->listing_except_columns;

        return array_diff(Schema::getColumnListing($this->table), $except);
    }

    public function listing_header()
    {
        return array_diff($this->table_cols(), $this->listing_except_columns);
    }

    public function scopeListing($query) 
    {
        return $query->select(array_diff($this->table_cols(), $this->listing_except_columns));
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
                    'code'      =>  'required|unique:customers,code,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    'name'      =>  'required|unique:customers,name,NULL,id,leaf_group_id,'.Company::get_group_id(),
                    ];

        if ($this->id) {
            $rules['code'] = 'required|unique:customers,code,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
            $rules['name'] = 'required|unique:customers,name,'.$this->id.',id,leaf_group_id,'.Company::get_group_id();
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public static function customer_patching_from_leaf_member_by_leaf_group_id($leaf_group_id=null)
    {
        $leaf_group_id = Setting::get_leaf_group_id($leaf_group_id);
        Setting::set_company($leaf_group_id);
        $member_listing = static::get_leaf_member_status_list();

        $non_exist_member_listing = $member_listing['non_exist_member_listing'];
        $updated_member_listing = $member_listing['updated_member_listing'];
       //foreach ($updated_member_listing as $member) {
            //dd($member);
       //     echo $member['house_member_name']."<br>";
       // }
       // dd('stop');
        foreach ($non_exist_member_listing as $member) {
            $customer = new Customer();
            $customer->save_customer_from_leaf_house(null,$member['id_house_member']);
        }

       

        
    }

    public static function get_leaf_member_status_list()
    {
        //$now = new DateTime();
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

        return $return;
    }

    //if do not have leaf house input , member id will get the member detail from leaf house listing
    //if both variable are available will take the $house as object
    const customer_model_default_values = ['currency_id'=> 1,'customer_group_id' => 0,'payment_term_id'   => 0,'gst_no'=> '','credit_limit'=> 0 , 'is_suspend' => false ,'website' => '','fax_no' => '','remark' => '','status' => true ];
    const custoemr_to_house_mappers = ['contact_person'    =>  '' , 'sales_person'      =>  'house_sales_person'  , 'delivery_address1'    =>  'house_unit'  , 'delivery_postcode'    =>  'house_postcode'  , 'delivery_city_id'     =>  'house_city'  , 'delivery_state_id'    =>  'house_state_id'  , 'delivery_country_id'  =>  'house_country_id'  , 'billing_address1'     =>  'house_unit'  , 'billing_postcode'     =>  'house_postcode'  , 'billing_city_id'      =>  'house_city'  , 'billing_state_id'     =>  'house_state_id'  , 'billing_country_id'   =>  'house_country_id' ,'id_house' => 'id_house' ];
    const address2_variables = ['house_address1','house_address2','house_postcode','house_city'];
    public function save_customer_from_leaf_house($house=null , $member_id = null){

        //this flow is to cater insertion of dedicated member
        $is_live = true;
        $leaf_api = new LeafAPI();
        $membership_detail;
        if(!isset($house['id_house']) && isset($member_id)){
           
             $model = static::get_customer_model_by_leaf_id_house_member($member_id);
            
                //if last update different only return
            if(isset($model['id'])){
                $now = new DateTime();
                $diff_in_second = $now->getTimestamp() - $model['updated_at']->getTimestamp();
                if($diff_in_second/3600 <= 1){
                   return ;
                }
            }

            $membership_detail = $leaf_api->get_user_house_membership_detail_by_leaf_house_member_id_for_register($member_id);
            $house;
          
            //for user with membership service subscribe
            if(isset($membership_detail['id_house'])){
              
                $house = LeafAPI::get_house_by_house_id($membership_detail['id_house']);
                $house['house_members'] = array();
                //null checker
                //dd($membership_detail['member_detail']);
                array_push($house['house_members'] , $membership_detail['member_detail']);

            //for user with
            }else if(isset($member_id))
            {
                $house = LeafAPI::get_house_by_member_id($member_id , $is_live);
                $member = LeafAPI::get_member_detail_by_member_id($member_id , $is_live);
                //dd($member);
                if(is_array($house['house_members'] ))
                {
                    array_push($house['house_members'] , $member);
                }
                
            }else{
                
            }

        if(isset($house['id_house'])){
            DB::beginTransaction();
            try{
              // dd($house);
           //  echo 'M id :'.$member_id."<br>";
   $member = isset($member['id_house_member']) ?  LeafAPI::get_member_detail_by_member_id($member_id , $is_live) : null; 
//dd($member);
                        $customer = isset($model['id']) ? $model : new Customer();
                        foreach (static::customer_model_default_values as $key => $value)
                        {
                            $customer[$key] = $value;
                        }

                        foreach (static::custoemr_to_house_mappers as $key => $house_key)
                        {
                            $customer[$key] = isset($house[$house_key]) ? $house[$house_key] : '';
                        }

                        $counter = 0;
                        $address2 = '';
                        foreach (static::address2_variables as $key )
                        {
                            $address2 = $house[$key].($counter == count(static::address2_variables) ? '' : ' , ');
                        }

                        $customer['billing_address2']       = $address2;
                        $customer['delivery_address2'] = $address2  ;
                        

                       /* //$currency_model = Currency::get_model_by_code();
                        $customer['id_house'] = $house['id_house'];
                        
                        $customer['currency_id']        = 1;
                        $customer['customer_group_id']  = 0;
                        $customer['payment_term_id']    = 0;
                        $customer['contact_person']     = "wip";
                        $customer['sales_person']       = $house['house_sales_person'];
                        $customer['delivery_address1']     =   $house['house_unit'].' '.$house['house_address1'];
                        $customer['delivery_address2']     =   $house['house_address2'];
                        $customer['delivery_postcode']     =   $house['house_postcode'];
                        $customer['delivery_city_id']      =   $house['house_city'] != '' ?  (is_numeric($house['house_city']) ? $house['house_city']  : 0 ) : 0;
                        $customer['delivery_state_id']     =   $house['house_state']!= '' ? (is_numeric($house['house_state']) ? $house['house_state']  : 0 ) : 0;;
                        $customer['delivery_country_id']   =   $house['house_country']!= '' ? (is_numeric($house['house_country']) ? $house['house_country']  : 0 ) : 0;;

                        $customer['billing_address1']      =   $house['house_unit'].' '.$house['house_address1'];
                        $customer['billing_address2']      =   $house['house_address2'];
                        $customer['billing_postcode']      =   $house['house_postcode'];
                        $customer['billing_city_id']       =   $house['house_city'] != '' ?  (is_numeric($house['house_city']) ? $house['house_city']  : 0 ) : 0;
                        $customer['billing_state_id']      =   $house['house_state'] != '' ?  (is_numeric($house['house_state']) ? $house['house_state']  : 0 ) : 0;
                        $customer['billing_country_id']    =   $house['house_country'] != '' ?  (is_numeric($house['house_country']) ? $house['house_country']  : 0 ) : 0;

                        $customer['gst_no']                = "";
                        $customer['credit_limit']          =  0;
                        $customer['is_suspend']            = false;
                        $customer['website']               = "";
                        $customer['fax_no']                = "";
                        $customer['remark']                = "";
                        $customer['status']                = true;*/

                    if(!isset($house['house_members'])){
                        //dd($house);
                       //dd($customer);
                        return false;
                    }
                    foreach($house['house_members'] as $member){

                            if(!isset($member['id_house_member'])){continue;}
                            $model = static::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
                            if(isset($model['id'])){
                                $now = new DateTime();
                                $diff_in_second = $now->getTimestamp() - $model['updated_at']->getTimestamp();
                                if($diff_in_second/3600 <= 1){
                                   //return ;
                                    break;
                                }
                            }
                            
                            //save if specific member is indicated      
                            
                            $customer['id_house_member']    = $member['id_house_member'];
                            $customer['leaf_room_id']       = isset($membership_detail['leaf_room_id']) != 0? $membership_detail['leaf_room_id'] : (isset($member['id_house_leaf_room_idroom']) != 0 ? $member['leaf_room_id'] :0);
                            $customer['acc_id_customer']    = isset($member['acc_id_customer']) ? $member['acc_id_customer'] : '';
                            $customer['leaf_group_id']      = Company::get_group_id();
                            $customer['leaf_id_user']       = isset($member['house_member_id_user']) ? $member['house_member_id_user'] : '';
    
                            //wip for setia 
                            $customer['code']               = $house['house_unit'];
                            $customer['name']               = isset($member['house_member_name'])? $member['house_member_name'] : '';
                            $customer['email']              = isset($member['house_member_email'])? $member['house_member_email'] : '';
                            $customer['registration_no']    = isset($member['house_member_ic'])? $member['house_member_ic'] : '';
                            $customer['phone_no_1']         = isset($member['house_member_phonenumber'])? $member['house_member_phonenumber'] : '';
                            $customer['phone_no_2']         = isset($member['house_member_home_phonenumber'])? $member['house_member_home_phonenumber'] : '';

                            $customer->save();
                            $params = clone $customer;

                             foreach ($customer->attributes as $key => $value) {
                                if ($key != '_token') {
                                    $params->$key = (string) $value;
                                }
                             }

                            $params['contact_person']   =   $customer->contact_person;
                            $params['payment_terms']    =   $customer->display_relationed('payment_term', 'code');
                            $params['status']           =   $customer->status ? 'active':'inactive';
                            $params['address_line_1']   =   $customer->billing_address1;
                            $params['address_line_2']   =   $customer->billing_address2;
                            $params['address_postcode'] =   $customer->billing_postcode;
                            $params['address_city']     =   $customer->display_relationed('billing_city', 'name');
                            $params['address_state']    =   $customer->display_relationed('billing_state', 'name');
                            $params['address_country']  =   $customer->display_relationed('billing_country', 'name');

                            $ncl_api = new NclAPI();
                            $ncl_id = $customer->ncl_id ? $customer->ncl_id:null;
                            
                            if($result = $ncl_api->set_customer($params, $ncl_id)){
                                DB::table('customers')->where('id','=',$customer->id)->update(['ncl_id'=>$result['register_id']]);
                            }

                            if(!$customer->id){
                                $customer['created_by']       =   Auth::id() ? Auth::id():0;
                                $customer['created_at']       =   date('Y-m-d', strtotime('now'));

                            }else{
                               

                                $customer['updated_at']       =   date('Y-m-d', strtotime('now'));
                                $customer['updated_by']         = Auth::id() ? Auth::id():0;
                                //$customer['leaf_group_id']    =   Company::get_group_id();
                            }

                            $customer->save();

                         }
               
                }catch (Exception $e) {
                    throw $e;
                    DB::rollBack();
                }
            
            DB::commit();
            return $customer;
        }

    }
}

    public function save_customer_from_leaf_house_patching($house=null , $member_id = null){

        //this flow is to cater insertion of dedicated member
        $leaf_api = new LeafAPI();
        $membership_detail;
        if(!isset($house['id_house']) && isset($member_id)){
           
             $model = static::get_customer_model_by_leaf_id_house_member($member_id);
            
                //if last update different only return
            if(isset($model['id'])){
                $now = new DateTime();
                $diff_in_second = $now->getTimestamp() - $model['updated_at']->getTimestamp();
                if($diff_in_second/3600 <= 1){
                   return ;
                }
            }

            $membership_detail = $leaf_api->get_user_house_membership_detail_by_leaf_house_member_id_for_register($member_id);
            $house;
          
            //for user with membership service subscribe
            if(isset($membership_detail['id_house'])){
              
                $house = LeafAPI::get_house_by_house_id($membership_detail['id_house']);
                $house['house_members'] = array();
                //null checker
                //dd($membership_detail['member_detail']);
                array_push($house['house_members'] , $membership_detail['member_detail']);

            //for user with
            }else if(isset($member_id))
            {
                $house = LeafAPI::get_house_by_member_id($member_id);
                $member = LeafAPI::get_member_detail_by_member_id($member_id);
                //dd($member);
                array_push($house['house_members'] , $member);
            }else{
                
            }
           
        }

        if(isset($house['id_house'])){
            DB::beginTransaction();
            try{
               
                        $customer = isset($model['id']) ? $model : new Customer();
                        $customer['id_house'] = $house['id_house'];
                        //$currency_model = Currency::get_model_by_code();
                        $customer['currency_id']        = 1;
                        $customer['customer_group_id']  = 0;
                        $customer['payment_term_id']    = 0;
                        $customer['contact_person']     = "wip";
                        $customer['sales_person']       = $house['house_sales_person'];
                        $customer['delivery_address1']     =   $house['house_unit'].' '.$house['house_address1'];
                        $customer['delivery_address2']     =   $house['house_address2'];
                        $customer['delivery_postcode']     =   $house['house_postcode'];
                        $customer['delivery_city_id']      =   $house['house_city'] != '' ?$house['house_city'] : 0;
                        $customer['delivery_state_id']     =   $house['house_state']!= '' ?$house['house_state'] : 0;;
                        $customer['delivery_country_id']   =   $house['house_country']!= '' ?$house['house_country'] : 0;;

                        $customer['billing_address1']      =   $house['house_unit'].' '.$house['house_address1'];
                        $customer['billing_address2']      =   $house['house_address2'];
                        $customer['billing_postcode']      =   $house['house_postcode'];
                        $customer['billing_city_id']       =   $house['house_city'] != '' ? $house['house_city'] : 0;
                        $customer['billing_state_id']      =   $house['house_state'] != '' ? $house['house_state'] : 0;
                        $customer['billing_country_id']    =   $house['house_country'] != '' ? $house['house_country'] : 0;

                        $customer['gst_no']                = "";
                        $customer['credit_limit']          =  0;
                        $customer['is_suspend']            = false;
                        $customer['website']               = "";
                        $customer['fax_no']                = "";
                        $customer['remark']                = "";
                        $customer['status']                = true;

                    /*if(!isset($house['house_members'])){
                       //dd($house);
                        return null;
                    }*/

                    foreach ($house['house_rooms'] as $room) {

                       foreach($room['house_room_members'] as $member){
                       
                            $model = static::get_customer_model_by_leaf_id_house_member($member['id_house_member']);
                            if(isset($model['id'])){
                                $now = new DateTime();
                                $diff_in_second = $now->getTimestamp() - $model['updated_at']->getTimestamp();
                                if($diff_in_second/3600 <= 1){
                                   return ;
                                }
                            }
                            
                            //save if specific member is indicated      
                            
                            $customer['id_house_member']    = $member['id_house_member'];
                            $customer['leaf_room_id']       = isset($membership_detail['leaf_room_id']) != 0? $membership_detail['leaf_room_id'] : (isset($member['id_house_leaf_room_idroom']) != 0 ? $member['leaf_room_id'] :0);
                            $customer['acc_id_customer']    = isset($member['acc_id_customer']) ? $member['acc_id_customer'] : '';
                            $customer['leaf_group_id']      = Company::get_group_id();
                            $customer['leaf_id_user']       = isset($member['house_member_id_user']) ? $member['house_member_id_user'] : '';
    
                            //wip for setia 
                            $customer['code']               = $house['house_unit'];
                            $customer['name']               = isset($member['house_member_name'])? $member['house_member_name'] : '';
                            $customer['email']              = isset($member['house_member_email'])? $member['house_member_email'] : '';
                            $customer['registration_no']    = isset($member['house_member_ic'])? $member['house_member_ic'] : '';
                            $customer['phone_no_1']         = isset($member['house_member_phonenumber'])? $member['house_member_phonenumber'] : '';
                            $customer['phone_no_2']         = isset($member['house_member_home_phonenumber'])? $member['house_member_home_phonenumber'] : '';

                            $customer->save();
                            $params = clone $customer;

                             foreach ($customer->attributes as $key => $value) {
                                if ($key != '_token') {
                                    $params->$key = (string) $value;
                                }
                             }

                            $params['contact_person']   =   $customer->contact_person;
                            $params['payment_terms']    =   $customer->display_relationed('payment_term', 'code');
                            $params['status']           =   $customer->status ? 'active':'inactive';
                            $params['address_line_1']   =   $customer->billing_address1;
                            $params['address_line_2']   =   $customer->billing_address2;
                            $params['address_postcode'] =   $customer->billing_postcode;
                            $params['address_city']     =   $customer->display_relationed('billing_city', 'name');
                            $params['address_state']    =   $customer->display_relationed('billing_state', 'name');
                            $params['address_country']  =   $customer->display_relationed('billing_country', 'name');

                            $ncl_api = new NclAPI();
                            $ncl_id = $customer->ncl_id ? $customer->ncl_id:null;
                            
                            if($result = $ncl_api->set_customer($params, $ncl_id)){
                                DB::table('customers')->where('id','=',$customer->id)->update(['ncl_id'=>$result['register_id']]);
                            }

                            if(!$customer->id){
                                $customer['updated_at']       =   date('Y-m-d', strtotime('now'));
                                $customer['created_by']       =   Auth::id() ? Auth::id():0;
                                $customer['updated_by']       =   0;
                                $customer['leaf_group_id']    =   Company::get_group_id();
                            }else{
                                $customer['created_by']       =   Auth::id() ? Auth::id():0;
                                $customer['created_at']       =   date('Y-m-d', strtotime('now'));
                            }

                            $customer->save();

                        }
                    }
                        
               
                }catch (Exception $e) {
                    throw $e;
                    DB::rollBack();
                }
            
            DB::commit();
            return $customer;
        }

    }



    public function save_form($input)
    {
        DB::beginTransaction();
        try {
            foreach ($input as $key => $value) {
                if ($key != '_token') {
                    $this->$key = (string) $value;
                }
            }
            if (!$this->id) {
                $this->created_by       =   Auth::id() ? Auth::id():0;
                $this->updated_by       =   0;
                $this->leaf_group_id    =   Company::get_group_id();
            } else {
                $this->updated_by       =   Auth::id() ? Auth::id():0;
            }
            $this->save();
            $params = $this;
            $params['contact_person']     =   $this->contact_person;
            $params['payment_terms']    =   $this->display_relationed('payment_term', 'code');
            $params['status']           =   $this->status ? 'active':'inactive';
            $params['address_line_1']   =   $this->billing_address1;
            $params['address_line_2']   =   $this->billing_address2;
            $params['address_postcode'] =   $this->billing_postcode;
            $params['address_city']     =   $this->display_relationed('billing_city', 'name');
            $params['address_state']    =   $this->display_relationed('billing_state', 'name');
            $params['address_country']  =   $this->display_relationed('billing_country', 'name');
            $ncl_api = new NclAPI();
            $ncl_id = $this->ncl_id ? $this->ncl_id:null;
            if ($result = $ncl_api->set_customer($params, $ncl_id)) {
                DB::table('customers')->where('id','=',$this->id)->update(['ncl_id'=>$result['register_id']]);
            }
        } catch (Exception $e) {
            throw $e;
            DB::rollBack();
        }
        DB::commit();
    }
}
