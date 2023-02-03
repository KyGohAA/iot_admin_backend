<?php

use App\MeterPaymentReceived;
use App\Company;
use App\Setting;
use App\LeafAPI;
use App\Customer;
use App\ARPaymentReceived;
use Dompdf\Dompdf;
use App\Setia\PaymentReceivedRPdf ;
use App\webGrabber\Ego888WebGrabber ;
use App\MeterReading;
use App\ARInvoice;
use App\MeterSubsidiary;
use App\ARInvoiceItem;
use App\MeterRegister;
use App\ARPaymentReceivedItem;
use App\Product;
use App\User;
use App\Currency;
use App\Setia\PaymentReceivedPdf;
use App\Inflect;
use App\OperationRule;
use App\FileIOHelper;
use App\OpencartLanguageTranslator;
use App\PaymentTestingAllowList;
use App\MeterReadingDaily;
use App\ProjectModelMapping;
use App\UTransaction;
use App\CustomerPowerUsageSummary;


Route::get('populatePowerMeterReport', function(){
	
	ini_set('max_execution_time', 3000000);	
	$user_listing = User::all();
	$update_counter = 0;
	foreach($user_listing as $user)
	{	
		$update_counter ++;
		if($update_counter == 3)
		{
			break;
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
				$api 	= new LeafAPI();
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

				$result 	= $api->get_user_by_email($email);
				if($result['status_code'] == -1){
					$result_data[$user['leaf_id_user']]['msg'] = 'Invalid Email';
				}

				//$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
				$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
				//dd($user_detail);
				if($user_detail['leaf_room_id'] == 0){
					$result_data[$user['leaf_id_user']]['msg'] = 'No stay at room';
				}


				if(Company::get_group_id() == 0){
					setcookie(LeafAPI::label_session_token, $this->session_token);
				}

				$room_listing = LeafAPI::get_all_stayed_room_by_id_house_member($user_detail['member_detail']['id_house_member']);

		        $model    		=	new User();
		        $setting  		=	new Setting();


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
				}

				//new code  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
				$user_profile 	     	    	= $user;
		        $user_profile['account_no'] 	= $meter_register_model->account_no;
				$user_profile['address']    	= $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
				$user_profile_string 	    	= json_encode($user_profile);		
				$is_allow_to_pay		  = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

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
			
				$date_range 	= array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
			
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
				$subsidy_listing	= MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
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
				$last_reading_date_time 		= date('jS F Y h:00 A', strtotime('+8 hours'));
		        $month_usage_listing =		$account_status['month_usage_summary'];
			
				foreach($room_listing as $room){
					echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
				}
				
				//echo 'date_range : '.json_encode($date_range)."<br>";
				
				//echo "Leaf id user :".$user_profile['leaf_id_user']."<br>";
				$result_data[$user['leaf_id_user']]['is_app_user'] = true;
				$result_data[$user['leaf_id_user']]['data'] = ['room_type' => $user_detail['house_room_type'] , 'date_range' => $date_range,'payment_received_listing' => json_encode($payment_received_listing ,'subsidy_lis)ting' =>  json_encode($subsidy_listin)g,'' => ,'month_usage_listing' => json_encode($month_usage_listing) , 'latest_data' => json_encode($statistic)];
				
					
				//return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
				}
			}

		foreach($result_data as $data)
		{
			//if($data')
			UserAccountSummary::save_report_data($data);
		}

		dd('Done');
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
			continue	;
		}

		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['payment_paid']		= $result['payment_paid'] ;
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
	/* foreach ($error_listing as $error_model){
		echo $error_model['id'].'='.$error_model['leaf_payment_id']."<br>";
	}
	 */
	dd('End');


	});


Route::get('IpayFinal2', function ()
{

 $days_date = array('10-12-2019 01:37:55 PM','10-12-2019 03:23:37 PM','11-12-2019 04:36:11 PM','11-12-2019 06:25:57 PM','11-12-2019 06:26:37 AM','11-12-2019 07:47:47 AM','11-12-2019 07:50:11 AM','11-12-2019 09:45:39 PM','11-12-2019 09:55:43 PM','1-12-2019 01:02:48 AM','1-12-2019 07:03:10 PM','1-12-2019 08:16:30 AM','1-12-2019 08:18:47 AM','1-12-2019 11:04:57 PM','1-12-2019 12:42:45 PM','12-12-2019 01:32:43 AM','13-12-2019 03:21:06 PM','13-12-2019 06:45:27 AM','15-12-2019 01:11:27 PM','15-12-2019 06:30:04 AM','15-12-2019 06:32:43 AM','16-12-2019 11:26:25 AM','3-12-2019 01:29:16 PM','3-12-2019 03:19:52 PM','3-12-2019 04:28:09 PM','3-12-2019 07:35:23 PM','3-12-2019 07:37:57 PM','3-12-2019 09:56:24 PM','3-12-2019 10:21:53 PM','3-12-2019 10:23:03 PM','3-12-2019 10:46:18 PM','3-12-2019 11:08:28 PM','4-12-2019 01:00:13 PM','4-12-2019 01:01:30 PM','4-12-2019 01:02:35 PM','4-12-2019 06:13:49 AM','4-12-2019 06:25:21 PM','4-12-2019 11:50:34 PM','4-12-2019 11:58:38 AM','4-12-2019 12:58:01 PM','5-12-2019 01:17:34 PM','5-12-2019 01:20:14 PM','5-12-2019 01:23:15 PM','5-12-2019 01:26:14 PM','5-12-2019 05:46:34 PM','5-12-2019 06:56:19 PM','5-12-2019 08:08:07 AM','5-12-2019 10:07:15 PM','6-12-2019 01:55:17 PM','6-12-2019 07:25:31 PM','7-12-2019 03:58:32 PM','7-12-2019 06:22:14 PM','7-12-2019 06:25:46 PM','7-12-2019 11:24:54 PM','8-12-2019 02:06:41 AM','8-12-2019 02:47:32 AM','8-12-2019 03:14:13 PM','8-12-2019 06:36:10 AM','8-12-2019 10:34:50 PM','9-12-2019 08:41:26 AM');
$days_reference_no = array('12877','12879','12884','12886','12880','12881','12882','12891','12895','12808','12812','12809','12810','12813','12811','12896','12900','12899','12906','12904','12905','12910','12815','12819','12820','12821','12822','12824','12825','12826','12828','12829','12834','12835','12836','12830','12837','12838','12832','12833','12841','12842','12843','12844','12847','12849','12840','12850','12852','12854','12855','12858','12859','12860','12861','12863','12866','12864','12868','12871');
$days_trans_id = array('T203689944519','T203740706019','T203868788919','T203878427019','T203802240819','T203804998419','T203805116319','T203895659019','T203896532319','T202566261819','T202634263719','T202574157819','T202574288319','T202656024519','T202597818219','T203909417019','T204095073519','T204056087319','T204239507019','T204216208119','T204216231819','T204318090519','T202820365419','T202831477719','T202837806219','T202854157419','T202854369819','T202867658919','T202869809319','T202869911619','T202871689719','T202873281219','T203083566219','T203083701519','T203083812819','T202881726219','T203146259019','T203171830719','T203077069719','T203083342119','T203213535519','T203213834019','T203214181419','T203214518019','T203239895019','T203246146719','T203182590519','T203263886019','T203314631319','T203345989419','T203420862819','T203432333919','T203432604819','T203455600419','T203460882219','T203461406019','T203497500219','T203463226119','T203532580419','T203561189919');
 $days_amount = array('5.00','30.00','1.00','101.73','20.00','10.00','10.00','20.00','20.00','24.00','20.00','21.70','21.70','17.67','10.00','30.00','100.00','22.53','30.00','40.00','40.00','4.86','30.00','10.00','100.00','25.88','25.88','10.00','65.67','52.00','50.00','25.00','13.48','13.48','13.48','6.00','13.95','7.00','13.00','13.48','9.00','9.00','9.00','9.00','28.00','100.00','29.80','3.00','20.00','58.10','2.00','20.00','6.39','3.00','40.00','40.00','3.00','3.00','42.74','6.36');
 $days_user_name = array('Ahmad Hilman Affandi','Tee Jiong Rui Jane','Amin Nazir','hameeza','Nur Syahidah binti Mohaidi','Nurul Najihah','Nurul Najihah','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Nur mizah','ERNIE DUSILY','Remorn anak Jipong','Remorn anak Jipong','Norazlin Binti Iskan','JANESSA anakTERANG','norsyakila yaacob','hew Lee sin','melita','Yung Ying Hsia','Ivory Chin Ai Wei','Ivory Chin Ai Wei','Ainul Mardiah Binti Ideris','Ling Hui Jin','crystal tan','Geetha Nair Sundaram','Siva Gamy','Siva Gamy','Muhammad Safuan Bin Ahmad Jalaludin','Aimi Nabila','siti aisyah','Zulaikha Mohd Taib','wenqi','Nurulfidya Syafika Binti Mohd Shopi','Normarini Morad','Nurulfidya Syafika Binti Mohd Shopi','Nurul Akmal Fatihah bt Abd Hadi','Tan Wen Li','Anis Sabirah','Leong Shwu Jye','Nurulfidya Syafika Binti Mohd Shopi','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Nurul Hidayah Roslan','nor hazwani bt ahmad tarmidi','Celine Ying','Han Yee Chen','Siti Najiha Binti Mohd Razali','ainun shahria','Audry Chieng Wen Wen','Ahmad Kamil Bin Kelin','Shalini Karinalili','Nurul Syahirah','Siti Najiha Binti Mohd Razali','nur shamimi shuhada binti rahimi','Anjum Anwar','Siti Najiha Binti Mohd Razali','Siti Najiha Binti Mohd Razali','liew choon cheuan','Arisya Shahirah');
 $days_status = array('Success','Success','Success','Success','Success','Fail','Success','Fail','Fail','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Fail','Fail','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success');


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
			continue	;
		}

		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
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


Route::get('Ipay88Final', function ()
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
			continue	;
		}

		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
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



Route::get('Ipay88PaymentCheckCrossCheck_new2', function ()
{
	$days_trans_id = array('T003070254619','T003070035320','T002981728520','T002981680520','T002971971319','T002944731320','T002926499720','T002926492219','T002926443920','T002926436419','T002924392820','T002924100020','T002924118920','T002885436020','T002839702820','T002830872919','T002830004720','T002806272920','T002749209919','T002682419420','T002669651120','T002664220520','T002663985020','T002654172320','T002650479319','T002611736420','T002598498320','T002596731020','T002596440320','T002591240719','T002591235620','T002591228719','T002591061020','T002580665420','T002572341620','T002572065320','T002572008320','T002571996019','T002571899120','T002571756920','T002571747020','T002563540220','T002563502120','T002555230220','T002554917920','T002554791020','T002554784120','T002554772720','T002550152719','T002537004320','T002536986020','T002496772820','T002496701420','T002462011520','T002422785620','T002355549620','T002344593020','T002337525020','T002333245520','T002332140920','T002332122320','T002320496120','T002320468520','T002319285020','T002316625219','T002316516019','T002316064219','T002310298519','T002297646919','T002297579719','T002289779719','T002289474019','T002286204619','T002281556719','T002272233919','T002270877019','T002267792719','T002267337319','T002266107619','T002263040119','T002261620219','T002249112319','T002160176719','T002028801919','T001951979119','T001913673319','T213939591219','T213924444219','T213852016119','T213820403619','T213792389619','T213777128319','T213767237320','T213765613119','T213765189819','T213739109919','T213733375719','T213732295719','T213697831719','T213637726419','T213631673919','T213553187920','T213553107219','T213549808419','T213547112019','T213547110220','T213494653720','T213494583820','T213494305719','T213487175619','T213476250819','T213475859619','T213431156919','T213431073819','T213393963819','T213390035619','T213389855019','T213378962919','T213372531819','T213345020020','T213334858419','T213334593819','T213334388619','T213330179619','T213325576420','T213318919719','T213287393919','T213233650419','T212798741919','T212768949219','T212732724820','T212727024519','T212726410419','T212726051619','T212725786719','T212725701219','T212724822819','T212691182019','T212635138719','T212621351320','T212621326120','T212617912119','T212590877920','T212580955419','T212573319519','T212544287019','T212525159619','T212506257819','T212497520619','T212497458219','T212496279219','T212495714619','T212495474019','T212493819519','T212490683319','T212476129719','T212470109020','T212469750520','T212464968819','T212429436519','T212416720119','T212383224220','T212299018419','T212289272019','T212233788519','T212222277819','T212211215919','T212211083019','T212193550419','T212183803419','T212180875419','T212126107119','T212120858319','T212120731419','T212120670219','T212108320419','T212107878519','T212092523319','T212082468219','T212046803919','T212045163219','T212042355519','T212024447019','T212009123019','T212001732219','T211966768419','T211909743519','T211909303419','T211882142619','T211848956919','T211832488719','T211823411919','T211823200419','T211808284119','T211793680119','T211770273219','T211768508619','T211766205219','T211742484819','T211742316519','T211724522619','T211713846519','T211690017819','T211639599819','T211625638419','T211621431219','T211595230719','T211594990719','T211590342819','T211577753319','T211577448519','T211575556119','T211571674119','T211546615419','T211544834619','T211544820219','T211493259519','T211492895919','T211492283019','T211461224619','T211457403519','T211456548819','T211446557319','T211439705019','T211384048419','T211383865419','T211383609219','T211374637719','T211366494519','T211362891819','T211362867219','T211353263619','T211353138219','T211339220319','T211337573019','T211298963319','T211286197419','T211280181519','T211279161219','T211276233819','T211274974419','T211271956419','T211256879319','T211252503819','T211246581219','T211098534519','T211072150119','T211041225219');

	$days_status = array('Success','Success','Success','Customer Drop the Transaction','Success','Success','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Success','Success','Fail','Fail','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Success','Success','Success','Success','Customer Drop the Transaction','Fail','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Customer Drop the Transaction','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Customer Drop the Transaction','Success','Success','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Fail','Fail','Fail','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Fail','Success','Success','Success','Success','Fail','Success','Fail','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success');
		
	$days_user_name = array('Dashinipriya','darshini a/ kalimuthu','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Siti Najiha Binti Mohd Razali','Alissa Shamsudin','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Nurul Faezah binti Badri','Nurul Hidayah Roslan','Nornazifah binti Ahmad Sapri','Siti Najiha Binti Mohd Razali','Nurmeymeng zalia','Nur Shakirah binti Kamal Ariffin','Nurul Akmal Fatihah bt Abd Hadi','Yap Tai Loong','Gan ChinTeng','Gan ChinTeng','syafiqhairunazmi','Gan ChinTeng','Sheba Solomi Moses Vejaya Kumar','Dhashini Devi A/p Sinniah','ainun shahria','ainun shahria','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Mohd Amsyar Bin Bacho','ERNIE DUSILY','Vijy Balan','farah hanis','farah hanis','farah hanis','Nur Fazira Binti Jusoh','Nur Fazira Binti Jusoh','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Faiz Asni','Faiz Asni','Faiz Asni','Faiz Asni','Ng Jing Tien','ainin azyyati','ainin azyyati','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Elizabeth Elly','wenqi','Shaheen Saleam','Jiesee Yong','NURUL SYAFIKA','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Hasmizah Khalid','Hasmizah Khalid','Deepaah Subramamian','Choo Yuen Seng','Choo Yuen Seng','Choo Yuen Seng','liyana binti abdullah','Nur Shakirah binti Kamal Ariffin','Nur Shakirah binti Kamal Ariffin','hijrah md isa','Remorn anak Jipong','Nur Atiqah Binti Manan','Mohammad Amirul Ashraf','nurfarahanim','Zulaikha Mohd Taib','Siva Gamy','Nur Fazieraa Binti Jaafar','Siti Najiha Binti Mohd Razali','Femmy CL','NIVARSHINI','Muhammad Safuan Bin Ahmad Jalaludin','IHSAN ISMAIL','Nurul Ain','Rasnih Nuil','Nur Atiqah Binti Manan','Siti H','nur afrina','yap lee Kei','ROSSHEILAWANI BT MOHD RAZALI','Hanna Hii','noraini binti mohd zaidi','Geetha Nair Sundaram','darshini darshu','Dashinipriya','nor athirah bt ibrahim @ azizi','Mohd Fadeli','Hasmizah Khalid','Ruzan Jehan Mohd Ayob','Khoo Er Teng','Amin Nazir','liew choon cheuan','liew choon cheuan','noor azira','Shalini Karinalili','Shalini Karinalili','Ling Hui Jin','Ling Hui Jin','Maginei Misaa','Thong Ying Hoong','Tilasini Jagathesan','Tilasini Jagathesan','Nur Izzati','Nur Izzati','crystal Lau','Lee Chi Yi','Dan Qing Khaw','Stella Tiong','victoria anak iyip','Goh','Saran Dorai','Sheba Solomi Moses Vejaya Kumar','Sheba Solomi Moses Vejaya Kumar','Nur Syahidah binti Mohaidi','Geetha Nair Sundaram','Lilian Lim','Nurmeymeng zalia','Nurul Hidayah Roslan','Peai Hui','irene smilewan','Anjum Anwar','jimah','jimah','jimah','jimah','jimah','Saran Dorai','Nurul Syahirah','Hoexinjing','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Ateng Roslan','Aylvin Wong','JANESSA anakTERANG','Nurul Nadhirah Binti Hamzah','Liew yue xuan','Soo Jin Gui','Rubini Maniam','Fatin Athira','fairuza munirah bt mazlan','syarmimi rima yolanda','fathi yahya','fathi yahya','Charles97','Alissa Shamsudin','ERNIE DUSILY','Fatin Najihah Abdullah','Fatin Najihah Abdullah','Lilian Lim','Nor Nadzirah Bt Shaari','Haniff Zakwan','Ooi Man Thing','Palanikumar Kamaraj','Zasmin Aisha Binti Naumul','Lim Siow Yin','Shi Ring','Lim Siow Yin','Lim Siow Yin','Sharifah Hazirah Binti Syed Ahmad','Yap Tai Loong','Thong Ying Hoong','Kaiting Lim','rafidah','rafidah','rafidah','Siva Gamy','Siva Gamy','Goh Quo Yee','Nurul Akmal Fatihah bt Abd Hadi','Ana Razaly','Yap Tai Loong','liyana binti abdullah','Amin Nazir','Siti Najiha Binti Mohd Razali','marlia syuhada','Mohamad Jafni','Amila Solihan','Amila Solihan','Deanna Chua Li Ann','farah hanis','Aimi Nabila','Hammsavaally Ganesan','Siti Hajiah Binti Rani','Monica Bandi','Fazleen Izwana Masrom','Mohd Firdaus Bin Ibrahim','Nurul Ain','mohamad humam bin mohamad isa','casterchu','casterchu','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','Altwkzh Wardah','Ros anis farhanah','noraini binti mohd zaidi','Nur Hidayah Bt Ahmad Faizal','wong mei yee','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Eline Tie','aidy md dzahir','aidy md dzahir','Mohd Firdaus Bin Ibrahim','Mohd Khairulamirin','Nur Fazieraa Binti Jaafar','nooradira noordin','Siti Nadia Binti Sapari','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Muhammad Izzat','alice elizabeth','Nursyamimi binti Mazri','mohamad humam bin mohamad isa','Amin Nazir','nur aena','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Syaziana Binti Ali Kabar','Alissa Shamsudin','nurfarahanim','irene smilewan','hijrah md isa','hijrah md isa','Noor Syafiqah','ROZANA BINTI SAHRI','Anne Felicia Paul','Muhamad Hasri Shafee','Nurhafizah Mat Nafi','noraini binti mohd zaidi','Nurmeymeng zalia','Amown Daebak Sieyrien','Hemaa Abby','maisarah','Zulaikha Mohd Taib','Daranica','Wong Pei Ti','yap lee Kei','Tharshini Muthusamy');

	$days_date = array('29-1-2020 03:11:26 PM','29-1-2020 03:09:08 PM','28-1-2020 03:22:21 PM','28-1-2020 03:21:47 PM','28-1-2020 01:52:40 PM','28-1-2020 09:38:25 AM','27-1-2020 10:41:20 PM','27-1-2020 10:41:12 PM','27-1-2020 10:40:28 PM','27-1-2020 10:40:21 PM','27-1-2020 10:13:57 PM','27-1-2020 10:11:25 PM','27-1-2020 10:10:49 PM','27-1-2020 01:41:48 PM','26-1-2020 11:57:46 PM','26-1-2020 09:30:36 PM','26-1-2020 09:18:26 PM','26-1-2020 03:42:08 PM','25-1-2020 07:34:43 PM','24-1-2020 11:07:14 PM','24-1-2020 08:29:58 PM','24-1-2020 07:22:27 PM','24-1-2020 07:19:34 PM','24-1-2020 05:16:47 PM','24-1-2020 04:34:03 PM','24-1-2020 12:05:27 PM','24-1-2020 09:55:01 AM','24-1-2020 09:37:16 AM','24-1-2020 09:33:34 AM','24-1-2020 08:20:26 AM','24-1-2020 08:20:20 AM','24-1-2020 08:20:14 AM','24-1-2020 08:17:17 AM','24-1-2020 02:55:33 AM','23-1-2020 10:52:10 PM','23-1-2020 10:48:19 PM','23-1-2020 10:47:29 PM','23-1-2020 10:47:20 PM','23-1-2020 10:46:02 PM','23-1-2020 10:43:56 PM','23-1-2020 10:43:47 PM','23-1-2020 09:11:29 PM','23-1-2020 09:11:05 PM','23-1-2020 07:39:09 PM','23-1-2020 07:35:07 PM','23-1-2020 07:33:34 PM','23-1-2020 07:33:29 PM','23-1-2020 07:33:21 PM','23-1-2020 06:38:43 PM','23-1-2020 04:21:08 PM','23-1-2020 04:20:53 PM','23-1-2020 03:18:36 PM','23-1-2020 03:17:53 PM','23-1-2020 12:21:34 PM','23-1-2020 09:52:51 AM','22-1-2020 03:58:36 PM','22-1-2020 02:20:41 PM','22-1-2020 01:18:26 PM','22-1-2020 12:39:53 PM','22-1-2020 12:30:29 PM','22-1-2020 12:30:18 PM','22-1-2020 10:47:57 AM','22-1-2020 10:47:44 AM','22-1-2020 10:37:25 AM','22-1-2020 10:14:22 AM','22-1-2020 10:13:38 AM','22-1-2020 10:10:28 AM','22-1-2020 09:17:30 AM','22-1-2020 04:44:57 AM','22-1-2020 04:37:37 AM','21-1-2020 11:11:25 PM','21-1-2020 11:07:11 PM','21-1-2020 10:25:44 PM','21-1-2020 09:39:05 PM','21-1-2020 07:50:14 PM','21-1-2020 07:32:40 PM','21-1-2020 06:54:17 PM','21-1-2020 06:48:01 PM','21-1-2020 06:32:36 PM','21-1-2020 05:54:33 PM','21-1-2020 05:36:37 PM','21-1-2020 03:13:12 PM','20-1-2020 04:21:47 PM','19-1-2020 07:16:06 PM','18-1-2020 07:35:20 PM','18-1-2020 11:46:49 AM','16-1-2020 12:32:29 PM','16-1-2020 10:22:50 AM','15-1-2020 03:02:48 PM','15-1-2020 09:36:00 AM','14-1-2020 08:23:22 PM','14-1-2020 04:47:29 PM','14-1-2020 02:40:37 PM','14-1-2020 02:20:46 PM','14-1-2020 02:15:57 PM','14-1-2020 09:20:56 AM','14-1-2020 07:54:40 AM','14-1-2020 07:26:51 AM','13-1-2020 08:37:48 PM','13-1-2020 12:34:04 AM','12-1-2020 10:17:34 PM','11-1-2020 08:14:43 PM','11-1-2020 08:13:38 PM','11-1-2020 07:25:21 PM','11-1-2020 06:43:58 PM','11-1-2020 06:43:56 PM','10-1-2020 11:57:40 PM','10-1-2020 11:55:55 PM','10-1-2020 11:49:13 PM','10-1-2020 09:49:37 PM','10-1-2020 07:24:42 PM','10-1-2020 07:19:00 PM','10-1-2020 10:48:49 AM','10-1-2020 10:47:56 AM','9-1-2020 11:16:44 PM','9-1-2020 10:14:16 PM','9-1-2020 10:11:40 PM','9-1-2020 07:46:32 PM','9-1-2020 06:14:18 PM','9-1-2020 12:44:01 PM','9-1-2020 10:59:14 AM','9-1-2020 10:56:34 AM','9-1-2020 10:54:33 AM','9-1-2020 10:13:44 AM','9-1-2020 09:28:50 AM','9-1-2020 08:24:11 AM','8-1-2020 07:05:51 PM','8-1-2020 09:32:02 AM','6-1-2020 12:05:29 PM','5-1-2020 11:37:45 PM','5-1-2020 03:01:35 PM','5-1-2020 01:49:52 PM','5-1-2020 01:42:41 PM','5-1-2020 01:38:20 PM','5-1-2020 01:35:09 PM','5-1-2020 01:34:14 PM','5-1-2020 01:23:56 PM','4-1-2020 10:35:40 PM','4-1-2020 10:47:57 AM','4-1-2020 06:28:26 AM','4-1-2020 06:26:04 AM','4-1-2020 12:58:41 AM','3-1-2020 06:25:31 PM','3-1-2020 04:21:54 PM','3-1-2020 02:51:01 PM','3-1-2020 09:40:45 AM','2-1-2020 10:41:28 PM','2-1-2020 06:32:43 PM','2-1-2020 04:39:17 PM','2-1-2020 04:38:30 PM','2-1-2020 04:24:29 PM','2-1-2020 04:18:20 PM','2-1-2020 04:15:36 PM','2-1-2020 03:57:02 PM','2-1-2020 03:21:34 PM','2-1-2020 12:47:11 PM','2-1-2020 11:46:51 AM','2-1-2020 11:43:22 AM','2-1-2020 10:58:21 AM','2-1-2020 03:31:42 AM','1-1-2020 09:50:03 PM','1-1-2020 03:22:54 PM','31-12-2019 05:49:00 PM','31-12-2019 04:12:15 PM','31-12-2019 03:49:52 AM','30-12-2019 10:23:02 PM','30-12-2019 08:17:26 PM','30-12-2019 08:15:53 PM','30-12-2019 04:38:05 PM','30-12-2019 02:57:44 PM','30-12-2019 02:26:50 PM','30-12-2019 09:18:45 AM','30-12-2019 08:02:07 AM','30-12-2019 07:59:10 AM','30-12-2019 07:57:51 AM','29-12-2019 10:39:40 PM','29-12-2019 10:32:40 PM','29-12-2019 07:01:10 PM','29-12-2019 04:36:24 PM','29-12-2019 07:47:52 AM','29-12-2019 06:09:40 AM','29-12-2019 01:19:47 AM','28-12-2019 08:11:55 PM','28-12-2019 04:46:19 PM','28-12-2019 03:13:49 PM','28-12-2019 06:11:26 AM','27-12-2019 01:26:30 PM','27-12-2019 01:21:45 PM','27-12-2019 08:22:31 AM','26-12-2019 11:21:33 PM','26-12-2019 07:39:02 PM','26-12-2019 05:37:16 PM','26-12-2019 05:34:42 PM','26-12-2019 02:48:38 PM','26-12-2019 12:22:32 PM','26-12-2019 08:47:25 AM','26-12-2019 08:22:32 AM','26-12-2019 07:32:40 AM','25-12-2019 11:25:50 PM','25-12-2019 11:22:39 PM','25-12-2019 07:12:45 PM','25-12-2019 04:45:09 PM','25-12-2019 12:02:35 PM','24-12-2019 07:28:29 PM','24-12-2019 04:39:46 PM','24-12-2019 03:54:56 PM','24-12-2019 11:41:06 AM','24-12-2019 11:38:56 AM','24-12-2019 10:57:30 AM','24-12-2019 08:40:35 AM','24-12-2019 08:36:30 AM','24-12-2019 08:08:33 AM','24-12-2019 05:38:09 AM','23-12-2019 10:57:41 PM','23-12-2019 10:31:20 PM','23-12-2019 10:31:08 PM','23-12-2019 02:26:44 PM','23-12-2019 02:23:14 PM','23-12-2019 02:17:15 PM','23-12-2019 09:33:55 AM','23-12-2019 08:51:59 AM','23-12-2019 08:42:10 AM','23-12-2019 01:01:24 AM','22-12-2019 10:43:30 PM','22-12-2019 11:19:55 AM','22-12-2019 11:17:44 AM','22-12-2019 11:14:37 AM','22-12-2019 08:57:21 AM','22-12-2019 12:50:39 AM','21-12-2019 11:26:46 PM','21-12-2019 11:26:13 PM','21-12-2019 09:14:30 PM','21-12-2019 09:13:04 PM','21-12-2019 06:26:01 PM','21-12-2019 06:05:13 PM','21-12-2019 11:01:14 AM','21-12-2019 07:54:14 AM','21-12-2019 01:22:45 AM','21-12-2019 12:45:25 AM','20-12-2019 11:41:35 PM','20-12-2019 11:20:50 PM','20-12-2019 10:38:45 PM','20-12-2019 07:43:19 PM','20-12-2019 06:52:14 PM','20-12-2019 05:44:40 PM','19-12-2019 10:38:31 AM','18-12-2019 10:50:22 PM','18-12-2019 04:43:51 PM');

	$days_amount = array('20.00','40.00','30.00','30.00','9.00','20.00','10.00','10.00','5.46','5.46','30.00','30.00','30.00','40.00','33.50','134.00','8.00','52.06','55.00','30.00','50.00','17.00','6.00','10.00','6.00','2.00','20.00','6.70','9.70','20.00','20.00','20.00','20.00','35.00','20.00','20.00','45.00','45.00','45.00','15.99','15.99','11.60','11.60','20.00','11.58','11.58','11.58','11.58','50.00','146.94','146.94','30.00','30.00','10.00','30.00','13.30','42.51','36.49','44.00','44.00','44.00','5.00','5.00','10.00','30.00','30.00','20.00','6.50','45.00','45.00','50.00','25.00','17.39','84.00','60.00','25.00','9.75','21.00','6.00','50.00','22.00','10.00','100.00','30.00','30.00','3.47','20.00','6.00','5.00','8.00','110.00','12.95','150.00','20.00','20.00','100.00','10.00','5.00','71.67','35.00','6.00','59.07','59.07','1.00','10.00','10.00','30.00','30.00','150.00','10.00','22.71','22.71','20.00','20.00','10.00','30.00','20.00','22.00','8.00','10.00','30.00','30.00','200.00','20.00','150.00','5.00','20.00','17.00','10.00','36.95','25.00','35.00','36.00','36.00','36.50','36.50','30.00','6.48','81.75','17.00','17.00','35.82','200.00','10.00','12.00','10.00','82.00','16.76','50.00','25.00','120.00','10.00','20.00','17.27','10.00','15.00','50.00','50.00','3.00','17.00','100.00','7.85','20.00','9.43','50.00','50.00','50.00','50.00','15.00','20.00','10.00','200.00','10.00','35.00','15.00','39.25','39.25','100.00','8.00','30.00','25.00','4.70','5.00','6.00','58.00','200.00','7.00','10.00','30.00','40.00','10.00','30.00','8.00','7.00','10.00','14.70','30.00','25.00','14.00','14.00','30.00','50.00','60.00','20.00','5.27','20.00','10.00','10.00','50.00','100.00','200.00','14.30','35.00','20.00','30.00','50.00','10.00','9.00','12.43','157.61','50.00','200.00','3.00','20.00','2.10','2.10','3.70','60.00','11.00','50.00','7.55','50.00','50.00','100.00','153.15','64.34','27.00','21.95','10.90','100.00','200.00','30.00','4.20','50.00','30.00','23.59','5.00','37.00');

	$days_model_arr = array();
	for($x = 0; $x< count($days_date) ; $x ++){

		$temp = array('trans_id'=>$days_trans_id[$x], 'document_date' => $days_date[$x], 'amount' => $days_amount[$x], 'name' => $days_user_name[$x], 'status' => $days_status[$x] ,'is_success' => $days_status[$x] == 'Success' ? 1 : 0 , 'date' =>
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
			}
		}
		

		$result = $leaf_api->get_check_payment($model['leaf_payment_id']);
		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
		$model['payment_customer_name'] = $result['payment_customer_name'];
		$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;


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
				<th>'.$result['id'].'</th>
				<th>'.$result['leaf_payment_id'].'</th>
			    <th>'.$result['document_no'].'</th>
				<th>'.$result['model_name'].'</th>
			    <th>'.$result['payment_customer_name'].'</th>
			    <th>'.$result['trans_id'].'</th>
			    <th>'.$result['amount'].'</th>
			    <th>'.$result['created_at'].'</th>
	
			    <th>'.$result['real_result'].'</th>
			    <th>'.$result['is_success'].'</th>
			    	    <th>'.$result['is_paid'].'</th>
			    <th>'.$result['ie_is_paid'].'</th>
		

			    		    <th>'.$result['is_payment_model_created'].'</th>
			    <th>'.$result['result_type'].'</th>
 		 </tr>';
		//echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
	}
	echo '</table>';

	echo 'Total '.count($result_listing).' records.';
	dd('End');


	});


Route::get('Ipay88PaymentCheckCrossCheck_new', function ()
{
	$days_status = array('Success','Success','Success','Customer Drop the Transaction','Success','Success','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Success','Success','Fail','Fail','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Success','Success','Success','Success','Customer Drop the Transaction','Fail','Success','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Customer Drop the Transaction','Success','Customer Drop the Transaction','Customer Drop the Transaction','Fail','Customer Drop the Transaction','Success','Success','Success','Success','Success','Success','Fail','Customer Drop the Transaction','Customer Drop the Transaction','Success','Fail','Success','Customer Drop the Transaction','Success','Success','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Fail','Fail','Fail','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Fail','Success','Success','Success','Success','Success','Fail','Fail','Fail','Fail','Fail','Success','Fail','Fail','Fail','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Fail','Success','Success','Success','Success','Fail','Success','Fail','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Success','Fail','Success','Success','Fail','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Fail','Success','Success','Success','Success','Success','Success','Success','Success','Success');
		
	$days_user_name = array('Dashinipriya','darshini a/ kalimuthu','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Siti Najiha Binti Mohd Razali','Alissa Shamsudin','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Nur Fatin Atirah','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Fazleen Izwana Masrom','Nurul Faezah binti Badri','Nurul Hidayah Roslan','Nornazifah binti Ahmad Sapri','Siti Najiha Binti Mohd Razali','Nurmeymeng zalia','Nur Shakirah binti Kamal Ariffin','Nurul Akmal Fatihah bt Abd Hadi','Yap Tai Loong','Gan ChinTeng','Gan ChinTeng','syafiqhairunazmi','Gan ChinTeng','Sheba Solomi Moses Vejaya Kumar','Dhashini Devi A/p Sinniah','ainun shahria','ainun shahria','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Dhashini Devi A/p Sinniah','Mohd Amsyar Bin Bacho','ERNIE DUSILY','Vijy Balan','farah hanis','farah hanis','farah hanis','Nur Fazira Binti Jusoh','Nur Fazira Binti Jusoh','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Faiz Asni','Faiz Asni','Faiz Asni','Faiz Asni','Ng Jing Tien','ainin azyyati','ainin azyyati','Jesica jabah anak sanggat','Jesica jabah anak sanggat','Elizabeth Elly','wenqi','Shaheen Saleam','Jiesee Yong','NURUL SYAFIKA','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Fathulqaraman Qaraman Sukor','Hasmizah Khalid','Hasmizah Khalid','Deepaah Subramamian','Choo Yuen Seng','Choo Yuen Seng','Choo Yuen Seng','liyana binti abdullah','Nur Shakirah binti Kamal Ariffin','Nur Shakirah binti Kamal Ariffin','hijrah md isa','Remorn anak Jipong','Nur Atiqah Binti Manan','Mohammad Amirul Ashraf','nurfarahanim','Zulaikha Mohd Taib','Siva Gamy','Nur Fazieraa Binti Jaafar','Siti Najiha Binti Mohd Razali','Femmy CL','NIVARSHINI','Muhammad Safuan Bin Ahmad Jalaludin','IHSAN ISMAIL','Nurul Ain','Rasnih Nuil','Nur Atiqah Binti Manan','Siti H','nur afrina','yap lee Kei','ROSSHEILAWANI BT MOHD RAZALI','Hanna Hii','noraini binti mohd zaidi','Geetha Nair Sundaram','darshini darshu','Dashinipriya','nor athirah bt ibrahim @ azizi','Mohd Fadeli','Hasmizah Khalid','Ruzan Jehan Mohd Ayob','Khoo Er Teng','Amin Nazir','liew choon cheuan','liew choon cheuan','noor azira','Shalini Karinalili','Shalini Karinalili','Ling Hui Jin','Ling Hui Jin','Maginei Misaa','Thong Ying Hoong','Tilasini Jagathesan','Tilasini Jagathesan','Nur Izzati','Nur Izzati','crystal Lau','Lee Chi Yi','Dan Qing Khaw','Stella Tiong','victoria anak iyip','Goh','Saran Dorai','Sheba Solomi Moses Vejaya Kumar','Sheba Solomi Moses Vejaya Kumar','Nur Syahidah binti Mohaidi','Geetha Nair Sundaram','Lilian Lim','Nurmeymeng zalia','Nurul Hidayah Roslan','Peai Hui','irene smilewan','Anjum Anwar','jimah','jimah','jimah','jimah','jimah','Saran Dorai','Nurul Syahirah','Hoexinjing','Nurul Hidayah Roslan','Nurul Hidayah Roslan','Ateng Roslan','Aylvin Wong','JANESSA anakTERANG','Nurul Nadhirah Binti Hamzah','Liew yue xuan','Soo Jin Gui','Rubini Maniam','Fatin Athira','fairuza munirah bt mazlan','syarmimi rima yolanda','fathi yahya','fathi yahya','Charles97','Alissa Shamsudin','ERNIE DUSILY','Fatin Najihah Abdullah','Fatin Najihah Abdullah','Lilian Lim','Nor Nadzirah Bt Shaari','Haniff Zakwan','Ooi Man Thing','Palanikumar Kamaraj','Zasmin Aisha Binti Naumul','Lim Siow Yin','Shi Ring','Lim Siow Yin','Lim Siow Yin','Sharifah Hazirah Binti Syed Ahmad','Yap Tai Loong','Thong Ying Hoong','Kaiting Lim','rafidah','rafidah','rafidah','Siva Gamy','Siva Gamy','Goh Quo Yee','Nurul Akmal Fatihah bt Abd Hadi','Ana Razaly','Yap Tai Loong','liyana binti abdullah','Amin Nazir','Siti Najiha Binti Mohd Razali','marlia syuhada','Mohamad Jafni','Amila Solihan','Amila Solihan','Deanna Chua Li Ann','farah hanis','Aimi Nabila','Hammsavaally Ganesan','Siti Hajiah Binti Rani','Monica Bandi','Fazleen Izwana Masrom','Mohd Firdaus Bin Ibrahim','Nurul Ain','mohamad humam bin mohamad isa','casterchu','casterchu','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','Altwkzh Wardah','Ros anis farhanah','noraini binti mohd zaidi','Nur Hidayah Bt Ahmad Faizal','wong mei yee','Mohamad Nuraliff Hafizin Bin Mastor','Mohamad Nuraliff Hafizin Bin Mastor','Eline Tie','aidy md dzahir','aidy md dzahir','Mohd Firdaus Bin Ibrahim','Mohd Khairulamirin','Nur Fazieraa Binti Jaafar','nooradira noordin','Siti Nadia Binti Sapari','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Muhammad Izzat','alice elizabeth','Nursyamimi binti Mazri','mohamad humam bin mohamad isa','Amin Nazir','nur aena','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','Syaziana Binti Ali Kabar','Alissa Shamsudin','nurfarahanim','irene smilewan','hijrah md isa','hijrah md isa','Noor Syafiqah','ROZANA BINTI SAHRI','Anne Felicia Paul','Muhamad Hasri Shafee','Nurhafizah Mat Nafi','noraini binti mohd zaidi','Nurmeymeng zalia','Amown Daebak Sieyrien','Hemaa Abby','maisarah','Zulaikha Mohd Taib','Daranica','Wong Pei Ti','yap lee Kei','Tharshini Muthusamy');

	$days_date = array('29-1-2020 03:11:26 PM','29-1-2020 03:09:08 PM','28-1-2020 03:22:21 PM','28-1-2020 03:21:47 PM','28-1-2020 01:52:40 PM','28-1-2020 09:38:25 AM','27-1-2020 10:41:20 PM','27-1-2020 10:41:12 PM','27-1-2020 10:40:28 PM','27-1-2020 10:40:21 PM','27-1-2020 10:13:57 PM','27-1-2020 10:11:25 PM','27-1-2020 10:10:49 PM','27-1-2020 01:41:48 PM','26-1-2020 11:57:46 PM','26-1-2020 09:30:36 PM','26-1-2020 09:18:26 PM','26-1-2020 03:42:08 PM','25-1-2020 07:34:43 PM','24-1-2020 11:07:14 PM','24-1-2020 08:29:58 PM','24-1-2020 07:22:27 PM','24-1-2020 07:19:34 PM','24-1-2020 05:16:47 PM','24-1-2020 04:34:03 PM','24-1-2020 12:05:27 PM','24-1-2020 09:55:01 AM','24-1-2020 09:37:16 AM','24-1-2020 09:33:34 AM','24-1-2020 08:20:26 AM','24-1-2020 08:20:20 AM','24-1-2020 08:20:14 AM','24-1-2020 08:17:17 AM','24-1-2020 02:55:33 AM','23-1-2020 10:52:10 PM','23-1-2020 10:48:19 PM','23-1-2020 10:47:29 PM','23-1-2020 10:47:20 PM','23-1-2020 10:46:02 PM','23-1-2020 10:43:56 PM','23-1-2020 10:43:47 PM','23-1-2020 09:11:29 PM','23-1-2020 09:11:05 PM','23-1-2020 07:39:09 PM','23-1-2020 07:35:07 PM','23-1-2020 07:33:34 PM','23-1-2020 07:33:29 PM','23-1-2020 07:33:21 PM','23-1-2020 06:38:43 PM','23-1-2020 04:21:08 PM','23-1-2020 04:20:53 PM','23-1-2020 03:18:36 PM','23-1-2020 03:17:53 PM','23-1-2020 12:21:34 PM','23-1-2020 09:52:51 AM','22-1-2020 03:58:36 PM','22-1-2020 02:20:41 PM','22-1-2020 01:18:26 PM','22-1-2020 12:39:53 PM','22-1-2020 12:30:29 PM','22-1-2020 12:30:18 PM','22-1-2020 10:47:57 AM','22-1-2020 10:47:44 AM','22-1-2020 10:37:25 AM','22-1-2020 10:14:22 AM','22-1-2020 10:13:38 AM','22-1-2020 10:10:28 AM','22-1-2020 09:17:30 AM','22-1-2020 04:44:57 AM','22-1-2020 04:37:37 AM','21-1-2020 11:11:25 PM','21-1-2020 11:07:11 PM','21-1-2020 10:25:44 PM','21-1-2020 09:39:05 PM','21-1-2020 07:50:14 PM','21-1-2020 07:32:40 PM','21-1-2020 06:54:17 PM','21-1-2020 06:48:01 PM','21-1-2020 06:32:36 PM','21-1-2020 05:54:33 PM','21-1-2020 05:36:37 PM','21-1-2020 03:13:12 PM','20-1-2020 04:21:47 PM','19-1-2020 07:16:06 PM','18-1-2020 07:35:20 PM','18-1-2020 11:46:49 AM','16-1-2020 12:32:29 PM','16-1-2020 10:22:50 AM','15-1-2020 03:02:48 PM','15-1-2020 09:36:00 AM','14-1-2020 08:23:22 PM','14-1-2020 04:47:29 PM','14-1-2020 02:40:37 PM','14-1-2020 02:20:46 PM','14-1-2020 02:15:57 PM','14-1-2020 09:20:56 AM','14-1-2020 07:54:40 AM','14-1-2020 07:26:51 AM','13-1-2020 08:37:48 PM','13-1-2020 12:34:04 AM','12-1-2020 10:17:34 PM','11-1-2020 08:14:43 PM','11-1-2020 08:13:38 PM','11-1-2020 07:25:21 PM','11-1-2020 06:43:58 PM','11-1-2020 06:43:56 PM','10-1-2020 11:57:40 PM','10-1-2020 11:55:55 PM','10-1-2020 11:49:13 PM','10-1-2020 09:49:37 PM','10-1-2020 07:24:42 PM','10-1-2020 07:19:00 PM','10-1-2020 10:48:49 AM','10-1-2020 10:47:56 AM','9-1-2020 11:16:44 PM','9-1-2020 10:14:16 PM','9-1-2020 10:11:40 PM','9-1-2020 07:46:32 PM','9-1-2020 06:14:18 PM','9-1-2020 12:44:01 PM','9-1-2020 10:59:14 AM','9-1-2020 10:56:34 AM','9-1-2020 10:54:33 AM','9-1-2020 10:13:44 AM','9-1-2020 09:28:50 AM','9-1-2020 08:24:11 AM','8-1-2020 07:05:51 PM','8-1-2020 09:32:02 AM','6-1-2020 12:05:29 PM','5-1-2020 11:37:45 PM','5-1-2020 03:01:35 PM','5-1-2020 01:49:52 PM','5-1-2020 01:42:41 PM','5-1-2020 01:38:20 PM','5-1-2020 01:35:09 PM','5-1-2020 01:34:14 PM','5-1-2020 01:23:56 PM','4-1-2020 10:35:40 PM','4-1-2020 10:47:57 AM','4-1-2020 06:28:26 AM','4-1-2020 06:26:04 AM','4-1-2020 12:58:41 AM','3-1-2020 06:25:31 PM','3-1-2020 04:21:54 PM','3-1-2020 02:51:01 PM','3-1-2020 09:40:45 AM','2-1-2020 10:41:28 PM','2-1-2020 06:32:43 PM','2-1-2020 04:39:17 PM','2-1-2020 04:38:30 PM','2-1-2020 04:24:29 PM','2-1-2020 04:18:20 PM','2-1-2020 04:15:36 PM','2-1-2020 03:57:02 PM','2-1-2020 03:21:34 PM','2-1-2020 12:47:11 PM','2-1-2020 11:46:51 AM','2-1-2020 11:43:22 AM','2-1-2020 10:58:21 AM','2-1-2020 03:31:42 AM','1-1-2020 09:50:03 PM','1-1-2020 03:22:54 PM','31-12-2019 05:49:00 PM','31-12-2019 04:12:15 PM','31-12-2019 03:49:52 AM','30-12-2019 10:23:02 PM','30-12-2019 08:17:26 PM','30-12-2019 08:15:53 PM','30-12-2019 04:38:05 PM','30-12-2019 02:57:44 PM','30-12-2019 02:26:50 PM','30-12-2019 09:18:45 AM','30-12-2019 08:02:07 AM','30-12-2019 07:59:10 AM','30-12-2019 07:57:51 AM','29-12-2019 10:39:40 PM','29-12-2019 10:32:40 PM','29-12-2019 07:01:10 PM','29-12-2019 04:36:24 PM','29-12-2019 07:47:52 AM','29-12-2019 06:09:40 AM','29-12-2019 01:19:47 AM','28-12-2019 08:11:55 PM','28-12-2019 04:46:19 PM','28-12-2019 03:13:49 PM','28-12-2019 06:11:26 AM','27-12-2019 01:26:30 PM','27-12-2019 01:21:45 PM','27-12-2019 08:22:31 AM','26-12-2019 11:21:33 PM','26-12-2019 07:39:02 PM','26-12-2019 05:37:16 PM','26-12-2019 05:34:42 PM','26-12-2019 02:48:38 PM','26-12-2019 12:22:32 PM','26-12-2019 08:47:25 AM','26-12-2019 08:22:32 AM','26-12-2019 07:32:40 AM','25-12-2019 11:25:50 PM','25-12-2019 11:22:39 PM','25-12-2019 07:12:45 PM','25-12-2019 04:45:09 PM','25-12-2019 12:02:35 PM','24-12-2019 07:28:29 PM','24-12-2019 04:39:46 PM','24-12-2019 03:54:56 PM','24-12-2019 11:41:06 AM','24-12-2019 11:38:56 AM','24-12-2019 10:57:30 AM','24-12-2019 08:40:35 AM','24-12-2019 08:36:30 AM','24-12-2019 08:08:33 AM','24-12-2019 05:38:09 AM','23-12-2019 10:57:41 PM','23-12-2019 10:31:20 PM','23-12-2019 10:31:08 PM','23-12-2019 02:26:44 PM','23-12-2019 02:23:14 PM','23-12-2019 02:17:15 PM','23-12-2019 09:33:55 AM','23-12-2019 08:51:59 AM','23-12-2019 08:42:10 AM','23-12-2019 01:01:24 AM','22-12-2019 10:43:30 PM','22-12-2019 11:19:55 AM','22-12-2019 11:17:44 AM','22-12-2019 11:14:37 AM','22-12-2019 08:57:21 AM','22-12-2019 12:50:39 AM','21-12-2019 11:26:46 PM','21-12-2019 11:26:13 PM','21-12-2019 09:14:30 PM','21-12-2019 09:13:04 PM','21-12-2019 06:26:01 PM','21-12-2019 06:05:13 PM','21-12-2019 11:01:14 AM','21-12-2019 07:54:14 AM','21-12-2019 01:22:45 AM','21-12-2019 12:45:25 AM','20-12-2019 11:41:35 PM','20-12-2019 11:20:50 PM','20-12-2019 10:38:45 PM','20-12-2019 07:43:19 PM','20-12-2019 06:52:14 PM','20-12-2019 05:44:40 PM','19-12-2019 10:38:31 AM','18-12-2019 10:50:22 PM','18-12-2019 04:43:51 PM');

	$days_amount = array('20.00','40.00','30.00','30.00','9.00','20.00','10.00','10.00','5.46','5.46','30.00','30.00','30.00','40.00','33.50','134.00','8.00','52.06','55.00','30.00','50.00','17.00','6.00','10.00','6.00','2.00','20.00','6.70','9.70','20.00','20.00','20.00','20.00','35.00','20.00','20.00','45.00','45.00','45.00','15.99','15.99','11.60','11.60','20.00','11.58','11.58','11.58','11.58','50.00','146.94','146.94','30.00','30.00','10.00','30.00','13.30','42.51','36.49','44.00','44.00','44.00','5.00','5.00','10.00','30.00','30.00','20.00','6.50','45.00','45.00','50.00','25.00','17.39','84.00','60.00','25.00','9.75','21.00','6.00','50.00','22.00','10.00','100.00','30.00','30.00','3.47','20.00','6.00','5.00','8.00','110.00','12.95','150.00','20.00','20.00','100.00','10.00','5.00','71.67','35.00','6.00','59.07','59.07','1.00','10.00','10.00','30.00','30.00','150.00','10.00','22.71','22.71','20.00','20.00','10.00','30.00','20.00','22.00','8.00','10.00','30.00','30.00','200.00','20.00','150.00','5.00','20.00','17.00','10.00','36.95','25.00','35.00','36.00','36.00','36.50','36.50','30.00','6.48','81.75','17.00','17.00','35.82','200.00','10.00','12.00','10.00','82.00','16.76','50.00','25.00','120.00','10.00','20.00','17.27','10.00','15.00','50.00','50.00','3.00','17.00','100.00','7.85','20.00','9.43','50.00','50.00','50.00','50.00','15.00','20.00','10.00','200.00','10.00','35.00','15.00','39.25','39.25','100.00','8.00','30.00','25.00','4.70','5.00','6.00','58.00','200.00','7.00','10.00','30.00','40.00','10.00','30.00','8.00','7.00','10.00','14.70','30.00','25.00','14.00','14.00','30.00','50.00','60.00','20.00','5.27','20.00','10.00','10.00','50.00','100.00','200.00','14.30','35.00','20.00','30.00','50.00','10.00','9.00','12.43','157.61','50.00','200.00','3.00','20.00','2.10','2.10','3.70','60.00','11.00','50.00','7.55','50.00','50.00','100.00','153.15','64.34','27.00','21.95','10.90','100.00','200.00','30.00','4.20','50.00','30.00','23.59','5.00','37.00');

	$days_model_arr = array();
	for($x = 0; $x< count($days_date) ; $x ++){

		$temp = array( 'document_date' => $days_date[$x], 'amount' => $days_amount[$x], 'name' => $days_user_name[$x], 'status' => $days_status[$x] ,'is_success' => $days_status[$x] == 'Success' ? 1 : 0 , 'date' =>
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
				$model['real_result'] = $temp['status'];
				$model['is_success'] = $temp['status'];
				$model['model_name'] = $temp['name'];
			}
		}
		

		$result = $leaf_api->get_check_payment($model['leaf_payment_id']);
		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
		$model['payment_customer_name'] = $result['payment_customer_name'];
		$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;


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
 	usort($result_listing, 'App\Setting::compare_by_column');
	foreach($result_listing as $result)
	{	echo '<tr>
				<th>'.$result['id'].'</th>
				<th>'.$result['leaf_payment_id'].'</th>
			    <th>'.$result['document_no'].'</th>
				<th>'.$result['model_name'].'</th>
			    <th>'.$result['payment_customer_name'].'</th>
			    <th>'.$result['amount'].'</th>
			    <th>'.$result['created_at'].'</th>
	
			    <th>'.$result['real_result'].'</th>
			    <th>'.$result['is_success'].'</th>
			    	    <th>'.$result['is_paid'].'</th>
			    <th>'.$result['ie_is_paid'].'</th>
		

			    		    <th>'.$result['is_payment_model_created'].'</th>
			    <th>'.$result['result_type'].'</th>
 		 </tr>';
		//echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
	}
	echo '</table>';

	echo 'Total '.count($result_listing).' records.';
	dd('End');


	});



Route::get('Ipay88PaymentCheckCrossCheck_2', function ()
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
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
		$model['payment_customer_name'] = $result['payment_customer_name'];
		$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;


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
				<th width="50px;">Payment number</th>
			    <th width="50px;">Document number</th>
				<th width="50px;">Model Name</th>
			    <th width="50px;">Name</th>
			    <th width="50px;">Payment Amount</th>
			    <th width="50px;">Created At</th>
			    <th width="50px;">Is with Model</th>
			   
			   <th width="50px;">Sunmed Ipay88 Status</th>
			   <th width="50px;">Sunmed Ipay88 Result</th>
			    <th width="50px;">Ie Status</th>
			    <th width="50px;">requery Status</th>
			    <th width="50px;">Status</th>
 		 </tr>';

 	
 	usort($result_listing, 'App\Setting::compare_by_created_at');
 	usort($result_listing, 'App\Setting::compare_by_column');
	foreach($result_listing as $result)
	{	echo '<tr>
				<th>'.$result['id'].'</th>
				<th>'.$result['leaf_payment_id'].'</th>
			    <th>'.$result['document_no'].'</th>
				<th>'.$result['model_name'].'</th>
			    <th>'.$result['payment_customer_name'].'</th>
			    <th>'.$result['amount'].'</th>
			    <th>'.$result['created_at'].'</th>
			    <th>'.$result['is_payment_model_created'].'</th>
			    <th>'.$result['real_result'].'</th>
			    <th>'.$result['is_success'].'</th>
			    <th>'.$result['ie_is_paid'].'</th>
			    <th>'.$result['is_paid'].'</th>
			    <th>'.$result['result_type'].'</th>
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
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
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



Route::get('Ipay88PaymentCheckNew', function ()
{
	$success_name_list = array('Nur mizah','Remorn anak Jipong','ERNIE DUSILY','Norazlin Binti Iskan','crystal tan','Muhammad Safuan Bin Ahmad Jalaludin','Aimi Nabila','siti aisyah','Zulaikha Mohd Taib','wenqi','Nurul Akmal Fatihah bt Abd Hadi','Leong Shwu Jye','Normarini Morad','Nurulfidya Syafika Binti Mohd Shopi','Tan Wen Li','Anis Sabirah','Han Yee Chen','nor hazwani bt ahmad tarmidi','Celine Ying','Siti Najiha Binti Mohd Razali','ainun shahria','Audry Chieng Wen Wen','Ahmad Kamil Bin Kelin','Shalini Karinalili','Nurul Syahirah','Siti Najiha Binti Mohd Razali','nur shamimi shuhada binti rahimi','Anjum Anwar','Siti Najiha Binti Mohd Razali','Siti Najiha Binti Mohd Razali','Arisya Shahirah','Ahmad Hilman Affandi','Tee Jiong Rui Jane','Nur Syahidah binti Mohaidi','Nurul Najihah','Amin Nazir','hameeza','norsyakila yaacob','melita','hew Lee sin','Ivory Chin Ai Wei','Yung Ying Hsia','Ainul Mardiah Binti Ideris','Tharshini Muthusamy','yap lee Kei','Wong Pei Ti','Daranica','Zulaikha Mohd Taib','maisarah','Hemaa Abby','Amown Daebak Sieyrien','Nurmeymeng zalia','Nurhafizah Mat Nafi','Muhamad Hasri Shafee','Anne Felicia Paul','ROZANA BINTI SAHRI','Noor Syafiqah','hijrah md isa','irene smilewan','nurfarahanim','Alissa Shamsudin','Syaziana Binti Ali Kabar','Mohammad Fajly Bin Barahim','Mohammad Fajly Bin Barahim','nur aena','Amin Nazir','mohamad humam bin mohamad isa','Nursyamimi binti Mazri','Muhammad Izzat','Mui Zhu Chai Pei Yoke','Mui Zhu Chai Pei Yoke','Siti Nadia Binti Sapari','nooradira noordin','Nur Fazieraa Binti Jaafar','Mohd Khairulamirin','aidy md dzahir','aidy md dzahir','Eline Tie','Mohamad Nuraliff Hafizin Bin Mastor','wong mei yee','noraini binti mohd zaidi','Ros anis farhanah','Altwkzh Wardah','SITI NUR LATIFA SORAYA BT MOHD HASNAFIAH','casterchu','mohamad humam bin mohamad isa','Mohd Firdaus Bin Ibrahim','Fazleen Izwana Masrom','Monica Bandi','Siti Hajiah Binti Rani','Hammsavaally Ganesan','Aimi Nabila','farah hanis','Deanna Chua Li Ann','Amila Solihan','Amila Solihan','Mohamad Jafni','marlia syuhada','Siti Najiha Binti Mohd Razali','Amin Nazir','liyana binti abdullah','Ana Razaly','Nurul Akmal Fatihah bt Abd Hadi','Goh Quo Yee','Siva Gamy','rafidah','Kaiting Lim','Thong Ying Hoong','Yap Tai Loong','Sharifah Hazirah Binti Syed Ahmad','Shi Ring','Lim Siow Yin','Zasmin Aisha Binti Naumul','Palanikumar Kamaraj');

	ini_set('max_execution_time', 300000);
	$leaf_api  = new LeafAPI();
	$uTransactionModel = UTransaction::all();
	$result_listing = array();

	foreach ($uTransactionModel as $model) {
		
		$result = $leaf_api->get_check_payment($model['leaf_payment_id']);
		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		$model['ie_is_paid']		= $model['is_paid'] ;
		$model['is_paid']		= $result['payment_paid'] ;
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
			    <th width="50px;">Name</th>
			    <th width="50px;">Payment Amount</th>
			    <th width="50px;">Created At</th>
			     <th width="50px;">Is inside list</th>
			    <th width="50px;">Is with Model</th>
			   
			    <th width="50px;">Ie Status</th>
			    <th width="50px;">requery Status</th>
			    <th width="50px;">Status</th>
 		 </tr>';

 	
 	usort($result_listing, 'App\Setting::compare_by_created_at');
 	usort($result_listing, 'App\Setting::compare_by_column');
	foreach($result_listing as $result)
	{	echo '<tr>
				<th>'.$result['id'].'</th>
				<th>'.$result['leaf_payment_id'].'</th>
			    <th>'.$result['document_no'].'</th>
			    <th>'.$result['payment_customer_name'].'</th>
			    <th>'.$result['amount'].'</th>
			    <th>'.$result['created_at'].'</th>
			    <th>'.in_array( $result['payment_customer_name'] , $success_name_list).'</th>
			    <th>'.$result['is_payment_model_created'].'</th>
			    <th>'.$result['ie_is_paid'].'</th>
			    <th>'.$result['is_paid'].'</th>
			    <th>'.$result['result_type'].'</th>
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
		/* if( $model['leaf_payment_id'] != '096c3bd77ce7cdad99e9b44985f912e6'){
			continue;
		 }*/
		$result = $leaf_api->get_check_payment($model['leaf_payment_id']);
		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		
		/* if( $model['leaf_payment_id'] == '096c3bd77ce7cdad99e9b44985f912e6'){
			echo json_encode($meter_payment_model)."<br>";
			dd("get");
		 }*/
		//if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
		//dd($meter_payment_model);
		if($model['is_paid'] == true){
			if($result['payment_paid'] == false){
				$model['result_type'] = 'revert_item [true ie wrong actual]';
				$model['is_paid']		= $result['payment_paid'] ;
				$model['payment_customer_name'] = $result['payment_customer_name'];
				$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
				$model['link_model'] ;
				array_push($result_listing,$model);
			}else if($result['payment_paid'] == true){
				if( !isset($meter_payment_model['id']))
				{
					/* if( $model['leaf_payment_id'] == '096c3bd77ce7cdad99e9b44985f912e6'){
						dd($meter_payment_model);
					 }*/
					$model['result_type'] = 'null_pr_model_item [true ie true actual]';
					$model['is_paid']		= $result['payment_paid'] ;
					$model['payment_customer_name'] = $result['payment_customer_name'];
					$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
					$model['link_model'] ;
					array_push($result_listing,$model);
				}
				
			}else{	

					$model['result_type'] = 'Query no status [True ie]';
					$model['is_paid']		= $result['payment_paid'] ;
					$model['payment_customer_name'] = $result['payment_customer_name'];
					$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
					$model['link_model'] ;
					array_push($result_listing,$model);
			}

		}else if($result['payment_paid']  != false && $result['payment_paid']  != true ){
			$model['result_type'] = 'pending_update_item [wrong actual result]';
			$model['is_paid']		= $result['payment_paid'] ;
			$model['payment_customer_name'] = $result['payment_customer_name'];
			$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
			$model['link_model'] ;
			array_push($result_listing,$model);
		}

	

		if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
			
				$model['result_type'] = 'success payment - wrong ie - no model';
				$model['is_paid']		= $result['payment_paid'] ;
				$model['payment_customer_name'] = $result['payment_customer_name'];
				$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
				$model['link_model'] ;
				array_push($result_listing,$model);

			if($result['payment_paid'] == true){
			//echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
				//ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
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
	{	echo '<tr>
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







Route::get('Ipay88PaymentCheck2', function ()
{
	ini_set('max_execution_time', 300000);
	$leaf_api  = new LeafAPI();
	$uTransactionModel = UTransaction::all();
	$result_listing = array();

	foreach ($uTransactionModel as $model) {
		
		$result = $leaf_api->get_check_payment($model['leaf_payment_id']);
		$meter_payment_model = MeterPaymentReceived::get_model_by_leaf_payment_id($model['leaf_payment_id']);
		if( $model['leaf_payment_id'] == '096c3bd77ce7cdad99e9b44985f912e6'){
			echo json_encode($meter_payment_model)."<br>";
		}
		//if( isset($meter_payment_model['id'])) {dd($meter_payment_model);}
		//dd($meter_payment_model);
		if($model['is_paid'] == true){
			if($result['payment_paid'] == false){
				$model['result_type'] = 'revert_item [true ie wrong actual]';
				$model['is_paid']		= $result['payment_paid'] ;
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
					$model['is_paid']		= $result['payment_paid'] ;
					$model['payment_customer_name'] = $result['payment_customer_name'];
					$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
					$model['link_model'] ;
					array_push($result_listing,$model);
				}
				
			}else{	

					$model['result_type'] = 'Query no status [True ie]';
					$model['is_paid']		= $result['payment_paid'] ;
					$model['payment_customer_name'] = $result['payment_customer_name'];
					$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
					$model['link_model'] ;
					array_push($result_listing,$model);
			}

		}else if($result['payment_paid']  != false && $result['payment_paid']  != true ){
			$model['result_type'] = 'pending_update_item [wrong actual result]';
			$model['is_paid']		= $result['payment_paid'] ;
			$model['payment_customer_name'] = $result['payment_customer_name'];
			$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
			$model['link_model'] ;
			array_push($result_listing,$model);
		}

	

		if($result['payment_paid'] == true && !isset($meter_payment_model['id']) && $model['is_paid'] == false){
			
				$model['result_type'] = 'success payment - wrong ie - no model';
				$model['is_paid']		= $result['payment_paid'] ;
				$model['payment_customer_name'] = $result['payment_customer_name'];
				$model['is_payment_model_created']  = isset($meter_payment_model['id']) == true ? true : false ;
				$model['link_model'] ;
				array_push($result_listing,$model);

			if($result['payment_paid'] == true){
			//echo $result['payment_customer_name'].'-'.$result['payment_total_amount'].'-'.$result['payment_paid']."<br>";
				//ProjectModelMapping::leaf_to_meter_payment_received_mapper($model,true);
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
			    <th width="50px;">Status</th>
 		 </tr>';
 	
 	
 	usort($result_listing, 'App\Setting::compare_by_created_at');
 	usort($result_listing, 'App\Setting::compare_by_column');
	foreach($result_listing as $result)
	{	echo '<tr>
				<th>'.$result['id'].'</th>
				<th>'.$result['leaf_payment_id'].'</th>
			    <th>'.$result['document_no'].'</th>
			    <th>'.$result['payment_customer_name'].'</th>
			    <th>'.$result['amount'].'</th>
			    <th>'.$result['created_at'].'</th>
			    <th>'.$result['is_payment_model_created'].'</th>
			    <th>'.$result['result_type'].'</th>
 		 </tr>';
		//echo $result['result_type'].'='.$result['created_at'].'-'.'='.$result['payment_customer_name'].'-'.$result['amount'] .'-'.$result['is_paid']."<br>";
	}
	echo '</table>';

	echo 'Total '.count($result_listing).' records.';
	dd('End');


	});
	




Route::get('get_summary_account_info_by_email', function(){
		
		$room;
		$api 	= new LeafAPI();
		$email = "";
		$leaf_group_id = Setting::SUNWAY_GROUP_ID;
		$company = Company::get_model_by_leaf_group_id($leaf_group_id);
		Setting::setCompany($leaf_group_id);
		$page_title     =   "Testing";
		$new_converted_single_room_aug_staff_id_house_member_arr = array(314212,314207,314241);
		$new_converted_single_room_aug_staff_id_arr = array(16083,30763,16167,18446,16164,16090,16178,16083,16192,16166,16088,16081,31126,16094,16224,16170,24806,16082);
		$converted_single_room_aug_staff_id_arr = array(21869,22764, 21853,21853,26491,21948,25262,19006,18618,18505,30440,22677,18241,22842,22678,21546,25208,19973,20121,21457,26467,18688,20944,20692,20239,25139,18302,20085,19964,19980,19966,19971,18867);
		$converted_twin_room_aug_staff_id_arr = array(18125,19275,19785,18187,26298,18121,18108,20673,26842,20098,20662,18699,29876,25901,19947,21497,17340,18544,18649,16096);
		$staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);

		//dd($email);
		if(isset($_GET["email"])){
			$email = $_GET["email"];
		}else{
			echo "No email enter , please add '?email=EmailToCheck', after get_summary_account_info_by_email.<br>";
			dd("http://webview.leaf.com.my/get_summary_account_info_by_email?email=abc@gmail.com");
		}

		$result 	= $api->get_user_by_email($email);
		if($result['status_code'] == -1){
			dd("Invalid email.");
		}

		//$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
		$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
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
		//dd($room_listing);
        $model    		=	new User();
        $setting  		=	new Setting();


        if (!$result['status_code']) {
            $data['status']	   	    = false;
            $data['status_msg'] 	= $result['error'];
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
		$user_profile 	     	    	= $user;
        $user_profile['account_no'] 	= $meter_register_model->account_no;
		$user_profile['address']    	= $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
		$user_profile_string 	    	= json_encode($user_profile);		
		$is_allow_to_pay		  = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

		$date_started = "";
	//dd($user_profile);
		if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
			$date_started = '2019-04-01';
			echo "Checking 1 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 2 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$new_converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 3 <br>";
		}else if(in_array($user_profile['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
			$date_started = '2019-08-01';
			echo "Checking 4 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_twin_room_aug_staff_id_arr)){
			echo "Checking 5 <br>";
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

		$date_range 	= array('date_started' => $date_started ,'date_ended' =>  date('Y-m-d', strtotime('now')));

		echo "Pass 1 <br>";
		$account_status = MeterPaymentReceived::get_user_account_status_by_leaf_id_user_and_date_started($user_detail['member_detail']['house_member_id_user'] , $date_range['date_started']);
		$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
		$subsidy_listing	= MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
		//dd($subsidy_listing);
		//dd($payment_received_listing);
		
		//dd($account_status);
		
		//forea
		//test multuple account status
		
		if(count($payment_received_listing) > 0){
            foreach ($payment_received_listing as $row) {
				//dd($row);
                $account_status['total_paid_amount'] += $row['total_amount'];
            }   
    	}
		
		usort($account_status['room_history'], 'App\Setting::compare_by_timeStamp_date_range_date_started');
		foreach ($account_status['room_history'] as $status) {
            //Need room name
            echo "Room id: ".$status['leaf_room_id']."<br>";
            echo $status['date_range']['date_started']." to ".$status['date_range']['date_ended']."<br>";
            echo " Monthly Usage";
            
            echo "<table border='1'>";
            echo "<th>Date</th> <th>Usage Kwh</th> <th>Amount RM</th>";
			//dd($account_status);
            foreach($status['month_usage_summary'] as $monthly_usage_summary){
               echo "<tr><td><font color='black'>".$monthly_usage_summary['date']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_usage_kwh']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_payable_amount']."</font></td></tr>";
            }
            echo "</table> <br><br>";
          
        }
    	//dd($payment_received_listing);
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		//$credit = MeterPaymentReceived::getUserBalanceCreditBLeafRoomIdAndDateRange($room['id_house_room'], $date_range , $user->leaf_id_user);
		//$statistic = MeterReading::convertUserCreditToMeterReadingStatistic($credit);

		//Get statistic 
		$statistic['currentUsageKwh'] =  isset($account_status['current_room']['month_usage_summary'])  ? (count($account_status['current_room']['month_usage_summary']) > 0 ? $account_status['current_room']['month_usage_summary'][count($account_status['current_room']['month_usage_summary'])-1]['total_usage_kwh'] : 0):0; 
		$statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
		$statistic['balanceAmount'] = $account_status['current_room']['total_paid_amount'] + $account_status['current_room']['total_subsidy_amount'] -  $account_status['current_room']['total_payable_amount'];  

		if($statistic['balanceAmount'] > 0 ){
				 $statistic['currentBalanceKwh'] = Setting::convert_balance_to_kwh_by_current_usage_and_balance($statistic['currentUsageKwh'] , $statistic['balanceAmount']);
		}else{
				$statistic['currentBalanceKwh'] = 0;
		}
		//Get statistic 
		session(['statistic' =>  $statistic]);
		$last_reading_date_time 		= date('jS F Y h:00 A', strtotime('+8 hours'));
        $month_usage_listing =		$account_status['current_room']['month_usage_summary'];
		
	
		foreach($room_listing as $room){
			//dd($room['house_rooms']['house_room_name']);
			//dd($room['house_rooms']['house_room_member_start_date']."-".$room['house_rooms']['house_room_member_end_date']);
			echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
		}
		//dd($account_status);
		// $room['house_room_type'].'-'.
		return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
	});
	

	
	Route::get('get_summary_account_info_by_email_twin_check', function(){
		
		$room;
		$api 	= new LeafAPI();
		$email = "";
		$leaf_group_id = Setting::SUNWAY_GROUP_ID;
		$company = Company::get_model_by_leaf_group_id($leaf_group_id);
		Setting::setCompany($leaf_group_id);
		$page_title     =   "Testing";
		$new_converted_single_room_aug_staff_id_house_member_arr = array(314212,314207,314241);
		$new_converted_single_room_aug_staff_id_arr = array(16083,30763,16167,18446,16164,16090,16178,16083,16192,16166,16088,16081,31126,16094,16224,16170,24806,16082);
		$converted_single_room_aug_staff_id_arr = array(21869,22764, 21853,21853,26491,21948,25262,19006,18618,18505,30440,22677,18241,22842,22678,21546,25208,19973,20121,21457,26467,18688,20944,20692,20239,25139,18302,20085,19964,19980,19966,19971,18867);
		$converted_twin_room_aug_staff_id_arr = array(18125,19275,19785,18187,26298,18121,18108,20673,26842,20098,20662,18699,29876,25901,19947,21497,17340,18544,18649,16096);
		$staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);
		$change_room_arry = array(21908);
		$change_room_arry_2020_04_21 = array(44468);
		$remove_previous_record = array(18618);
		$c2_1_1_july_2020_adjustment = array(38490);
	
	
		//dd($email);
		if(isset($_GET["email"])){
			$email = $_GET["email"];
		}else{
			echo "No email enter , please add '?email=EmailToCheck', after get_summary_account_info_by_email.<br>";
			dd("http://webview.leaf.com.my/get_summary_account_info_by_email?email=abc@gmail.com");
		}

		$result 	= $api->get_user_by_email($email);
		if($result['status_code'] == -1){
			dd("Invalid email.");
		}

		//$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
		$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
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
		//dd($room_listing);
        $model    		=	new User();
        $setting  		=	new Setting();


        if (!$result['status_code']) {
            $data['status']	   	    = false;
            $data['status_msg'] 	= $result['error'];
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
		$user_profile 	     	    	= $user;
        $user_profile['account_no'] 	= $meter_register_model->account_no;
		$user_profile['address']    	= $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
		$user_profile_string 	    	= json_encode($user_profile);		
		$is_allow_to_pay		  = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

		$date_started = "";
	//dd($user_profile);
	
		if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			$date_started = '2020-07-01';
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21)){
			$date_started = '2020-04-21';
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
			$date_started = '2019-04-01';
			echo "Checking 1 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 2 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$new_converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 3 <br>";
		}else if(in_array($user_profile['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
			$date_started = '2019-08-01';
			echo "Checking 4 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_twin_room_aug_staff_id_arr)){
			echo "Checking 5 <br>";
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

		$date_range 	= array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
		//dd($date_range);
		$account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range( $room['id_house_room'] , $date_range);

		if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
			echo "Twin scenario : <br>";
			$user_stay_detail = $api->get_user_stay_detail_for_twin_room_by_leaf_room_id($user_detail['leaf_room_id']);
			$user_stay_detail['date_range'] = $date_range;
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_user_start_stay_detail_all($user_stay_detail,$leaf_group_id);
		}else{
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
		}
		
		$subsidy_listing	= MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
		//dd($payment_received_listing);
		echo 'Total before process :'.$account_status['total_paid_amount']."<br>";
		if(count($payment_received_listing) > 0){
            foreach ($payment_received_listing as $row) {
				
				if(in_array($user_profile['leaf_id_user'],$change_room_arry) || in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21) || in_array($user_profile['leaf_id_user'],$remove_previous_record)  ){
					echo 'Get remove '.$row['document_no'].'='.$row['amount']."<br>";
					if(date("Y-m-d", strtotime($row['document_date'])) <  $date_range['date_started']){
						continue;
					}
					
				}else{
					echo 'Get retaing '.$row['document_no'].'='.$row['amount']."<br>";
				}
				
				echo $date_range['date_started'].'>'.date("Y-m-d", strtotime($row['document_date'])).'='.(date("Y-m-d", strtotime($row['document_date'])) >  $date_range['date_started'])."<br>";
				echo $row['document_date'].'='.$row['total_amount']."<br>";
                $account_status['total_paid_amount'] += $row['total_amount'];
            }   
    	}
		echo 'Total after process :'.$account_status['total_paid_amount']."<br>";
		//dd($account_status);
		/* if(count($account_status['room_history']) > 1){
			usort($account_status['room_history'], 'App\Setting::compare_by_timeStamp_date_range_date_started');
		}
		
		foreach ($account_status['room_history'] as $status) {
            //Need room name
            echo "Room id: ".$status['leaf_room_id']."<br>";
            echo $status['date_range']['date_started']." to ".$status['date_range']['date_ended']."<br>";
            echo " Monthly Usage";
            
            echo "<table border='1'>";
            echo "<th>Date</th> <th>Usage Kwh</th> <th>Amount RM</th>";
			//dd($account_status);
            foreach($status['month_usage_summary'] as $monthly_usage_summary){
               echo "<tr><td><font color='black'>".$monthly_usage_summary['date']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_usage_kwh']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_payable_amount']."</font></td></tr>";
            }
            echo "</table> <br><br>";
          
        } */
    	//dd($payment_received_listing);
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

		if($user_profile['leaf_id_user']== '22764' ){
			$statistic['balanceAmount'] = $statistic['balanceAmount'] - 78.04 ;
		}
		//Get statistic 
		session(['statistic' =>  $statistic]);
		$last_reading_date_time 		= date('jS F Y h:00 A', strtotime('+8 hours'));
        $month_usage_listing =		$account_status['month_usage_summary'];

	
		foreach($room_listing as $room){
			//dd($room['house_rooms']['house_room_name']);
			//dd($room['house_rooms']['house_room_member_start_date']."-".$room['house_rooms']['house_room_member_end_date']);
			echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
		}
		echo 'date_range : '.json_encode($date_range)."<br>";
		echo "Leaf id user :".$user_profile['leaf_id_user']."<br>";
		//dd($account_status);
		// $room['house_room_type'].'-'.
		return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
	});

Route::get('utility_adjustment_check_by_email', function(){
		
		$room;
		$api 	= new LeafAPI();
		$email = "";
		$leaf_group_id = Setting::SUNWAY_GROUP_ID;
		$company = Company::get_model_by_leaf_group_id($leaf_group_id);
		Setting::setCompany($leaf_group_id);
		$page_title     =   "Testing";
		$new_converted_single_room_aug_staff_id_house_member_arr = array(314212,314207,314241);
		$new_converted_single_room_aug_staff_id_arr = array(16083,30763,16167,18446,16164,16090,16178,16083,16192,16166,16088,16081,31126,16094,16224,16170,24806,16082);
		$converted_single_room_aug_staff_id_arr = array(21869,22764, 21853,21853,26491,21948,25262,19006,18618,18505,30440,22677,18241,22842,22678,21546,25208,19973,20121,21457,26467,18688,20944,20692,20239,25139,18302,20085,19964,19980,19966,19971,18867);
		$converted_twin_room_aug_staff_id_arr = array(18125,19275,19785,18187,26298,18121,18108,20673,26842,20098,20662,18699,29876,25901,19947,21497,17340,18544,18649,16096);
		$staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);
		$change_room_arry = array(21908);
		$change_room_arry_2020_04_21 = array(44468);
		$remove_previous_record = array(18618);
		$new_rearrangement_record_2019_08_01 = array(16111);
		$payment_adjustment_user_9_7_2020 = array(16104);
		$payment_adjustment_user = array(19971);
		
		$carry_credit_2020_06_10_42_32 = array(18618);
		$c2_1_1_july_2020_adjustment = array(38490);
	
		//dd($email);
		if(isset($_GET["email"])){
			$email = $_GET["email"];
		}else{
			echo "No email enter , please add '?email=EmailToCheck', after utility_adjustment_check_by_email.<br>";
			dd("http://webview.leaf.com.my/utility_adjustment_check_by_email?email=abc@gmail.com");
		}

		$result 	= $api->get_user_by_email($email);
		if($result['status_code'] == -1){
			dd("Invalid email.");
		}

		//$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
		$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
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
		//dd($room_listing);
        $model    		=	new User();
        $setting  		=	new Setting();


        if (!$result['status_code']) {
            $data['status']	   	    = false;
            $data['status_msg'] 	= $result['error'];
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
		$user_profile 	     	    	= $user;
        $user_profile['account_no'] 	= $meter_register_model->account_no;
		$user_profile['address']    	= $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
		$user_profile_string 	    	= json_encode($user_profile);		
		$is_allow_to_pay		  = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

		$date_started = "";
	//dd($user_profile);
	
	
	
		if(in_array($user_profile['leaf_id_user'], $payment_adjustment_user_9_7_2020)){
			//$date_started = '2020-07-01';
			$date_started = '2020-09-07';
			
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'], $new_rearrangement_record_2019_08_01)){
			//$date_started = '2020-07-01';
			$date_started = '2019-08-01';
			
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			//$date_started = '2020-07-01';
			$date_started = '2020-01-03';
			
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21)){
			$date_started = '2020-04-21';
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
			$date_started = '2019-04-01';
			echo "Checking 1 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 2 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$new_converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 3 <br>";
		}else if(in_array($user_profile['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
			$date_started = '2019-08-01';
			echo "Checking 4 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_twin_room_aug_staff_id_arr)){
			echo "Checking 5 <br>";
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
	
		$date_range 	= array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
		//dd($date_range);
		
		if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
			$date_range['date_started'] = '2020-06-10';
		
		}

		$account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment( $room['id_house_room'] , $date_range);

		if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
			echo "Twin scenario : <br>";
			$user_stay_detail = $api->get_user_stay_detail_for_twin_room_by_leaf_room_id($user_detail['leaf_room_id']);
			$user_stay_detail['date_range'] = $date_range;
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_user_start_stay_detail_all($user_stay_detail,$leaf_group_id);
		}else{
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
		}
		//echo json_encode($carry_credit_2020_06_10_42_32).'='.$user_profile['leaf_id_user']."<br>";
		$subsidy_listing	= MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
		//dd($payment_received_listing);
		$to_removed = array();
		$counter = 0 ;
		if(count($payment_received_listing) > 0){
            foreach ($payment_received_listing as $row) {
				
				//echo date("Y-m-d", strtotime($row['created_at']))."<br>";
				if(in_array($user_profile['leaf_id_user'],$payment_adjustment_user_9_7_2020)){
					if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2020-09-07')) == true)
					{
						continue;
					}
				}
			
				
				if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-06-10'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}
				
				
				if(in_array($user_profile['leaf_id_user'],$payment_adjustment_user)){
					
					//echo 'Removed test'.date("Y-m-d", strtotime($row['document_date'])).'<br>';
					//echo 'Result  :'.(date("Y-m-d", strtotime($row['document_date'])) <  '2019-07-17').'<br>';
					if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2019-07-17')) == true){
						array_push($to_removed,$counter);
						$counter++;
						//echo 'Fail result :'.(date("Y-m-d", strtotime($row['document_date'])) <  '2019-07-17').'<br>';
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['document_date']))) <  '2020-06-10'.'<br>';
						continue;
					}else{
						$counter++;
						//cho 'Skip else <br>';
						//echo 'Pass result :'.date("Y-m-d", strtotime($row['document_date'])) <  '2020-07-17'.'<br>';
					}
					//echo 'End r test <br>';
				}
				
				if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-07-01'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}
				
					
				if(in_array($user_profile['leaf_id_user'],$change_room_arry) || in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21) || in_array($user_profile['leaf_id_user'],$remove_previous_record)  ){
	
					if(date("Y-m-d", strtotime($row['document_date'])) <  $date_range['date_started']){
						continue;
					}
					
				}
				//echo $date_range['date_started'].'>'.date("Y-m-d", strtotime($row['document_date'])).'='.(date("Y-m-d", strtotime($row['document_date'])) >  $date_range['date_started'])."<br>";
				//echo $row['document_date'].'='.$row['total_amount']."<br>";
                $account_status['total_paid_amount'] += $row['total_amount'];
				//echo $row['document_date'].'='.$row['total_amount'].'>>'.$account_status['total_paid_amount']."<br>";
            }   
    	}
		if(count($to_removed) > 0)
		{
			foreach($to_removed as $key => $value){	
				unset($payment_received_listing[$value]);
			}
		}
			//dd();

		if(in_array($user_profile['leaf_id_user'],$payment_adjustment_user_9_7_2020)){
			$account_status['total_paid_amount'] += 44.53;
		}
		if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			$account_status['total_paid_amount'] += 122;
		}

		if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){	
				$account_status['total_paid_amount'] += 42.32;
		}
		//dd($account_status);
		/* if(count($account_status['room_history']) > 1){
			usort($account_status['room_history'], 'App\Setting::compare_by_timeStamp_date_range_date_started');
		}
		
		foreach ($account_status['room_history'] as $status) {
            //Need room name
            echo "Room id: ".$status['leaf_room_id']."<br>";
            echo $status['date_range']['date_started']." to ".$status['date_range']['date_ended']."<br>";
            echo " Monthly Usage";
            
            echo "<table border='1'>";
            echo "<th>Date</th> <th>Usage Kwh</th> <th>Amount RM</th>";
			//dd($account_status);
            foreach($status['month_usage_summary'] as $monthly_usage_summary){
               echo "<tr><td><font color='black'>".$monthly_usage_summary['date']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_usage_kwh']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_payable_amount']."</font></td></tr>";
            }
            echo "</table> <br><br>";
          
        } */
    	//dd($payment_received_listing);
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		//$credit = MeterPaymentReceived::getUserBalanceCreditBLeafRoomIdAndDateRange($room['id_house_room'], $date_range , $user->leaf_id_user);
		//$statistic = MeterReading::convertUserCreditToMeterReadingStatistic($credit);

		//Get statistic 

		$statistic['currentUsageKwh'] =  count($account_status['month_usage_summary']) > 0 ? $account_status['month_usage_summary'][count($account_status['month_usage_summary'])-1]['total_usage_kwh'] : 0; 
		$statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
		$statistic['balanceAmount'] = $account_status['total_paid_amount'] + $account_status['total_subsidy_amount'] -  $account_status['total_payable_amount'];  
		//dd($account_status['total_paid_amount'].'='.$statistic['balanceAmount'].'='.$account_status['total_payable_amount'] );
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
		$last_reading_date_time 		= date('jS F Y h:00 A', strtotime('+8 hours'));
        $month_usage_listing =		$account_status['month_usage_summary'];

	
		foreach($room_listing as $room){
			//dd($room['house_rooms']['house_room_name']);
			//dd($room['house_rooms']['house_room_member_start_date']."-".$room['house_rooms']['house_room_member_end_date']);
			echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
		}
		echo 'date_range : '.json_encode($date_range)."<br>";
		echo "Leaf id user :".$user_profile['leaf_id_user']."<br>";
		//dd($account_status);
		// $room['house_room_type'].'-'.
		return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
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
	
Route::get('utility_adjustment_check_by_email_second_test', function(){
		
		$room;
		$api 	= new LeafAPI();
		$email = "";
		$leaf_group_id = Setting::SUNWAY_GROUP_ID;
		$company = Company::get_model_by_leaf_group_id($leaf_group_id);
		Setting::setCompany($leaf_group_id);
		$page_title     =   "Testing";
		$new_converted_single_room_aug_staff_id_house_member_arr = array(314212,314207,314241);
		$new_converted_single_room_aug_staff_id_arr = array(16083,30763,16167,18446,16164,16090,16178,16083,16192,16166,16088,16081,31126,16094,16224,16170,24806,16082);
		$converted_single_room_aug_staff_id_arr = array(21869,22764, 21853,21853,26491,21948,25262,19006,18618,18505,30440,22677,18241,22842,22678,21546,25208,19973,20121,21457,26467,18688,20944,20692,20239,25139,18302,20085,19964,19980,19966,19971,18867);
		$converted_twin_room_aug_staff_id_arr = array(18125,19275,19785,18187,26298,18121,18108,20673,26842,20098,20662,18699,29876,25901,19947,21497,17340,18544,18649,16096);
		$staff_id_arr = array(16204,16190,16185,16184,16327,16189,16181,26786,16182,16194,16197,16202,16198,16123 , 16265,16179,16196);
		$change_room_arry = array(21908);
		$change_room_arry_2020_04_21 = array(44468);
		$remove_previous_record = array(18618);
		$new_rearrangement_record_2019_08_01 = array(16111);
		
		$payment_adjustment_user = array(19971);
		
		$carry_credit_2020_06_10_42_32 = array(18618);
		$c2_1_1_july_2020_adjustment = array(38490);
	
		//dd($email);
		if(isset($_GET["email"])){
			$email = $_GET["email"];
		}else{
			echo "No email enter , please add '?email=EmailToCheck', after utility_adjustment_check_by_email.<br>";
			dd("http://webview.leaf.com.my/utility_adjustment_check_by_email?email=abc@gmail.com");
		}

		$result 	= $api->get_user_by_email($email);
		if($result['status_code'] == -1){
			dd("Invalid email.");
		}

		//$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
		$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
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
		//dd($room_listing);
        $model    		=	new User();
        $setting  		=	new Setting();


        if (!$result['status_code']) {
            $data['status']	   	    = false;
            $data['status_msg'] 	= $result['error'];
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
		$user_profile 	     	    	= $user;
        $user_profile['account_no'] 	= $meter_register_model->account_no;
		$user_profile['address']    	= $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
		$user_profile_string 	    	= json_encode($user_profile);		
		$is_allow_to_pay		  = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

		$date_started = "";
	//dd($user_profile);
	
		if(in_array($user_profile['leaf_id_user'], $new_rearrangement_record_2019_08_01)){
			//$date_started = '2020-07-01';
			$date_started = '2019-08-01';
			
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			//$date_started = '2020-07-01';
			$date_started = '2020-01-03';
			
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21)){
			$date_started = '2020-04-21';
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
			$date_started = '2019-04-01';
			echo "Checking 1 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 2 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$new_converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 3 <br>";
		}else if(in_array($user_profile['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
			$date_started = '2019-08-01';
			echo "Checking 4 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_twin_room_aug_staff_id_arr)){
			echo "Checking 5 <br>";
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
	
		$date_range 	= array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
		//dd($date_range);
		
		if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
			$date_range['date_started'] = '2020-06-10';
		
		}

		$account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment_second_test( $room['id_house_room'] , $date_range);

		if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
			echo "Twin scenario : <br>";
			$user_stay_detail = $api->get_user_stay_detail_for_twin_room_by_leaf_room_id($user_detail['leaf_room_id']);
			$user_stay_detail['date_range'] = $date_range;
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_user_start_stay_detail_all($user_stay_detail,$leaf_group_id);
		}else{
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
		}
		//echo json_encode($carry_credit_2020_06_10_42_32).'='.$user_profile['leaf_id_user']."<br>";
		$subsidy_listing	= MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
		//dd($payment_received_listing);
		$to_removed = array();
		$counter = 0 ;
		if(count($payment_received_listing) > 0){
            foreach ($payment_received_listing as $row) {
				
				//echo date("Y-m-d", strtotime($row['created_at']))."<br>";
				if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-06-10'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}
				
				
				if(in_array($user_profile['leaf_id_user'],$payment_adjustment_user)){
					
					//echo 'Removed test'.date("Y-m-d", strtotime($row['document_date'])).'<br>';
					//echo 'Result  :'.(date("Y-m-d", strtotime($row['document_date'])) <  '2019-07-17').'<br>';
					if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2019-07-17')) == true){
						array_push($to_removed,$counter);
						$counter++;
						//echo 'Fail result :'.(date("Y-m-d", strtotime($row['document_date'])) <  '2019-07-17').'<br>';
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['document_date']))) <  '2020-06-10'.'<br>';
						continue;
					}else{
						$counter++;
						//cho 'Skip else <br>';
						//echo 'Pass result :'.date("Y-m-d", strtotime($row['document_date'])) <  '2020-07-17'.'<br>';
					}
					//echo 'End r test <br>';
				}
				
				if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-07-01'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}
				
					
				if(in_array($user_profile['leaf_id_user'],$change_room_arry) || in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21) || in_array($user_profile['leaf_id_user'],$remove_previous_record)  ){
	
					if(date("Y-m-d", strtotime($row['document_date'])) <  $date_range['date_started']){
						continue;
					}
					
				}
				//echo $date_range['date_started'].'>'.date("Y-m-d", strtotime($row['document_date'])).'='.(date("Y-m-d", strtotime($row['document_date'])) >  $date_range['date_started'])."<br>";
				//echo $row['document_date'].'='.$row['total_amount']."<br>";
                $account_status['total_paid_amount'] += $row['total_amount'];
				//echo $row['document_date'].'='.$row['total_amount'].'>>'.$account_status['total_paid_amount']."<br>";
            }   
    	}
		if(count($to_removed) > 0)
		{
			foreach($to_removed as $key => $value){	
				unset($payment_received_listing[$value]);
			}
		}
			//dd();

		
		if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			$account_status['total_paid_amount'] += 122;
		}

		if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){	
				$account_status['total_paid_amount'] += 42.32;
		}
		//dd($account_status);
		/* if(count($account_status['room_history']) > 1){
			usort($account_status['room_history'], 'App\Setting::compare_by_timeStamp_date_range_date_started');
		}
		
		foreach ($account_status['room_history'] as $status) {
            //Need room name
            echo "Room id: ".$status['leaf_room_id']."<br>";
            echo $status['date_range']['date_started']." to ".$status['date_range']['date_ended']."<br>";
            echo " Monthly Usage";
            
            echo "<table border='1'>";
            echo "<th>Date</th> <th>Usage Kwh</th> <th>Amount RM</th>";
			//dd($account_status);
            foreach($status['month_usage_summary'] as $monthly_usage_summary){
               echo "<tr><td><font color='black'>".$monthly_usage_summary['date']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_usage_kwh']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_payable_amount']."</font></td></tr>";
            }
            echo "</table> <br><br>";
          
        } */
    	//dd($payment_received_listing);
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		//$credit = MeterPaymentReceived::getUserBalanceCreditBLeafRoomIdAndDateRange($room['id_house_room'], $date_range , $user->leaf_id_user);
		//$statistic = MeterReading::convertUserCreditToMeterReadingStatistic($credit);

		//Get statistic 

		$statistic['currentUsageKwh'] =  count($account_status['month_usage_summary']) > 0 ? $account_status['month_usage_summary'][count($account_status['month_usage_summary'])-1]['total_usage_kwh'] : 0; 
		$statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
		$statistic['balanceAmount'] = $account_status['total_paid_amount'] + $account_status['total_subsidy_amount'] -  $account_status['total_payable_amount'];  
		//dd($account_status['total_paid_amount'].'='.$statistic['balanceAmount'].'='.$account_status['total_payable_amount'] );
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
		$last_reading_date_time 		= date('jS F Y h:00 A', strtotime('+8 hours'));
        $month_usage_listing =		$account_status['month_usage_summary'];

	
		foreach($room_listing as $room){
			//dd($room['house_rooms']['house_room_name']);
			//dd($room['house_rooms']['house_room_member_start_date']."-".$room['house_rooms']['house_room_member_end_date']);
			echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
		}
		echo 'date_range : '.json_encode($date_range)."<br>";
		echo "Leaf id user :".$user_profile['leaf_id_user']."<br>";
		//dd($account_status);
		// $room['house_room_type'].'-'.
		return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
});
	
Route::get('utility_adjustment_check_by_email_second', function(){
		
		$room;
		$api 	= new LeafAPI();
		$email = "";
		$leaf_group_id = Setting::SUNWAY_GROUP_ID;
		$company = Company::get_model_by_leaf_group_id($leaf_group_id);
		Setting::setCompany($leaf_group_id);
		$page_title     =   "Testing";
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
		//dd($email);
		if(isset($_GET["email"])){
			$email = $_GET["email"];
		}else{
			echo "No email enter , please add '?email=EmailToCheck', after utility_adjustment_check_by_email.<br>";
			dd("http://webview.leaf.com.my/utility_adjustment_check_by_email?email=abc@gmail.com");
		}

		$result 	= $api->get_user_by_email($email);
		if($result['status_code'] == -1){
			dd("Invalid email.");
		}

		//$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user($result['id_user']);
		$user_detail =  $api->get_user_house_membership_detail_by_leaf_id_user_for_register_2($result['id_user']);
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
		//dd($room_listing);
        $model    		=	new User();
        $setting  		=	new Setting();


        if (!$result['status_code']) {
            $data['status']	   	    = false;
            $data['status_msg'] 	= $result['error'];
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
		$user_profile 	     	    	= $user;
        $user_profile['account_no'] 	= $meter_register_model->account_no;
		$user_profile['address']    	= $room['house_unit'].' '.$meter_register_model->billing_address1.' '.$meter_register_model->billing_address2.' '.$meter_register_model->billing_postcode ;
		$user_profile_string 	    	= json_encode($user_profile);		
		$is_allow_to_pay		  = PaymentTestingAllowList::check_is_user_is_tester_by_leaf_id_user($user_profile['leaf_id_user'],$leaf_group_id);

		$date_started = "";
	//dd($user_profile);
	
		if(in_array($user_profile['leaf_id_user'], $c4_1_4_remove_payment_adjustment_2020_10_17)){
			//$date_started = '2020-07-01';
			$date_started = '2020-10-20';

		}else if(in_array($user_profile['leaf_id_user'], $twin_adjustment_2019_08_01)){
			//$date_started = '2020-07-01';
			$date_started = '2019-08-01';

		}else if(in_array($user_profile['leaf_id_user'], $twin_room_adjustment_2019_03_01)){
			//$date_started = '2020-07-01';
			//$date_started = '2019-03-01';
			$date_started = '2019-08-01';
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'], $twin_room_later_move_in_adjustment_2019_07_05)){
			//$date_started = '2020-07-01';
			$date_started = '2019-07-05';
			
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'], $change_room_arry_2020_10_7)){
			//$date_started = '2020-07-01';
			$date_started = '2020-10-07';
			
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'], $new_rearrangement_record_2019_08_01)){
			//$date_started = '2020-07-01';
			$date_started = '2019-08-01';
			
			echo "Date adjusted <br>";
		
		}else if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			//$date_started = '2020-07-01';
			$date_started = '2020-01-03';
			
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21)){
			$date_started = '2020-04-21';
			echo "Checking 1:change date <br>";
		}else if(in_array($user_profile['leaf_id_user'],$staff_id_arr)){
			$date_started = '2019-04-01';
			echo "Checking 1 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 2 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$new_converted_single_room_aug_staff_id_arr)){
			$date_started = '2019-08-01';
			echo "Checking 3 <br>";
		}else if(in_array($user_profile['id_house_member'],$new_converted_single_room_aug_staff_id_house_member_arr)){
			$date_started = '2019-08-01';
			echo "Checking 4 <br>";
		}else if(in_array($user_profile['leaf_id_user'],$converted_twin_room_aug_staff_id_arr)){
			echo "Checking 5 <br>";
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
	
		$date_range 	= array('date_started' => date('Y-m-d', strtotime($date_started)) ,'date_ended' =>  date('Y-m-d', strtotime('now')));
		//dd($date_range);
		
		if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
			$date_range['date_started'] = '2020-06-10';
		
		}

		$account_status = MeterPaymentReceived::get_user_balance_credit_by_leaf_room_id_and_date_range_adjustment_second( $room['id_house_room'] , $date_range);

		if($user_detail['house_room_type'] == LeafAPI::label_twin_room){
			echo "Twin scenario : <br>";
			$user_stay_detail = $api->get_user_stay_detail_for_twin_room_by_leaf_room_id($user_detail['leaf_room_id']);
			$user_stay_detail['date_range'] = $date_range;
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_user_start_stay_detail_all($user_stay_detail,$leaf_group_id);
		}else{
			$payment_received_listing = MeterPaymentReceived::get_meter_payment_received_by_leaf_id_user($user_profile['leaf_id_user'] ,$leaf_group_id);
		}
		//echo json_encode($carry_credit_2020_06_10_42_32).'='.$user_profile['leaf_id_user']."<br>";
		$subsidy_listing	= MeterPaymentReceived::get_user_subsidy_by_leaf_id_user_and_meter_register_id_and_date_range($user['leaf_id_user'] ,$meter_register_model->id , $date_range,$leaf_group_id);
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
				
				
				//echo date("Y-m-d", strtotime($row['created_at']))."<br>";
				if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-06-10'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}
				
				if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_10_7)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-10-07'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}/* 
				
				if(in_array($user_profile['leaf_id_user'],$twin_room_later_move_in_adjustment_2019_07_05)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2019-07-05'){
					
						continue;
					}

				} */
				
				if(in_array($user_profile['leaf_id_user'],$payment_adjustment_user)){
					
					//echo 'Removed test'.date("Y-m-d", strtotime($row['document_date'])).'<br>';
					//echo 'Result  :'.(date("Y-m-d", strtotime($row['document_date'])) <  '2019-07-17').'<br>';
					if(date("Y-m-d", strtotime($row['document_date'])) <  date("Y-m-d", strtotime('2019-07-17')) == true){
						array_push($to_removed,$counter);
						$counter++;
						//echo 'Fail result :'.(date("Y-m-d", strtotime($row['document_date'])) <  '2019-07-17').'<br>';
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['document_date']))) <  '2020-06-10'.'<br>';
						continue;
					}else{
						$counter++;
						//cho 'Skip else <br>';
						//echo 'Pass result :'.date("Y-m-d", strtotime($row['document_date'])) <  '2020-07-17'.'<br>';
					}
					//echo 'End r test <br>';
				}
				
				if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
					if(date("Y-m-d", strtotime($row['created_at'])) <  '2020-07-01'){
						//echo 'Get out :'.(date("Y-m-d", strtotime($row['created_at']))) <  '2020-06-10'.'<br>';
						continue;
					}

				}
				
					
				if(in_array($user_profile['leaf_id_user'],$change_room_arry) || in_array($user_profile['leaf_id_user'],$change_room_arry_2020_04_21) || in_array($user_profile['leaf_id_user'],$remove_previous_record)  ){
	
					if(date("Y-m-d", strtotime($row['document_date'])) <  $date_range['date_started']){
						continue;
					}
					
				}
				//echo $date_range['date_started'].'>'.date("Y-m-d", strtotime($row['document_date'])).'='.(date("Y-m-d", strtotime($row['document_date'])) >  $date_range['date_started'])."<br>";
				//echo $row['document_date'].'='.$row['total_amount']."<br>";
                $account_status['total_paid_amount'] += $row['total_amount'];
				//echo $row['document_date'].'='.$row['total_amount'].'>>'.$account_status['total_paid_amount']."<br>";
            }   
    	}
		if(count($to_removed) > 0)
		{
			foreach($to_removed as $key => $value){	
				unset($payment_received_listing[$value]);
			}
		}
			//dd();

		
		if(in_array($user_profile['leaf_id_user'],$c2_1_1_july_2020_adjustment)){
			$account_status['total_paid_amount'] += 122;
		}


		if(in_array($user_profile['leaf_id_user'],$change_room_arry_2020_10_7)){
			$account_status['total_paid_amount'] += 44.04;
		}

		if(in_array($user_profile['leaf_id_user'],$carry_credit_2020_06_10_42_32)){	
				$account_status['total_paid_amount'] += 42.32;
		}
		//dd($account_status);
		/* if(count($account_status['room_history']) > 1){
			usort($account_status['room_history'], 'App\Setting::compare_by_timeStamp_date_range_date_started');
		}
		
		foreach ($account_status['room_history'] as $status) {
            //Need room name
            echo "Room id: ".$status['leaf_room_id']."<br>";
            echo $status['date_range']['date_started']." to ".$status['date_range']['date_ended']."<br>";
            echo " Monthly Usage";
            
            echo "<table border='1'>";
            echo "<th>Date</th> <th>Usage Kwh</th> <th>Amount RM</th>";
			//dd($account_status);
            foreach($status['month_usage_summary'] as $monthly_usage_summary){
               echo "<tr><td><font color='black'>".$monthly_usage_summary['date']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_usage_kwh']."</font></td> <td><font color='black'>".$monthly_usage_summary['total_payable_amount']."</font></td></tr>";
            }
            echo "</table> <br><br>";
          
        } */
    	//dd($payment_received_listing);
		//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		//$credit = MeterPaymentReceived::getUserBalanceCreditBLeafRoomIdAndDateRange($room['id_house_room'], $date_range , $user->leaf_id_user);
		//$statistic = MeterReading::convertUserCreditToMeterReadingStatistic($credit);

		//Get statistic 

		$statistic['currentUsageKwh'] =  count($account_status['month_usage_summary']) > 0 ? $account_status['month_usage_summary'][count($account_status['month_usage_summary'])-1]['total_usage_kwh'] : 0; 
		$statistic['currentUsageCharges'] =  Setting::calculate_utility_fee($statistic['currentUsageKwh']);
		$statistic['balanceAmount'] = $account_status['total_paid_amount'] + $account_status['total_subsidy_amount'] -  $account_status['total_payable_amount'];  
		//dd($account_status['total_paid_amount'].'='.$statistic['balanceAmount'].'='.$account_status['total_payable_amount'] );
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
		$last_reading_date_time 		= date('jS F Y h:00 A', strtotime('+8 hours'));
        $month_usage_listing =		$account_status['month_usage_summary'];
//dd($payment_received_listing);
	
		foreach($room_listing as $room){
			//dd($room['house_rooms']['house_room_name']);
			//dd($room['house_rooms']['house_room_member_start_date']."-".$room['house_rooms']['house_room_member_end_date']);
			echo $room['house_rooms']['house_room_name'].":".$room['house_room_member_start_date']."-".$room['house_room_member_end_date']."<br>";
		}
		echo 'date_range : '.json_encode($date_range)."<br>";
		echo "Leaf id user :".$user_profile['leaf_id_user']."<br>";
		//dd($account_status);
		// $room['house_room_type'].'-'.
		return view('utility_charges.apps.dashboard', compact('is_allow_to_pay','status_msg','page_title', 'subsidy_listing', 'payment_received_listing' ,'listing','user_profile_string','user_profile' , 'statistic', 'meter_register_model', 'session_token','last_reading_date_time','company','get_model_by_leaf_group_id','leaf_group_id','month_usage_listing'));
	});
	
Route::get('sunmed_utility_summary_test', function(){
	set_time_limit(0);
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

	

Route::get('convertIpayToMeterReceiptModel', function ()
{

	$leaf_api  = new LeafAPI();
	$model = UTransaction::find(1947);
	
	$result = $leaf_api->get_check_payment($model['leaf_payment_id']);

			if($result['payment_paid'] == true){
				if($model['is_paid'] == false){
					$model['is_paid'] = true ;
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
		
		dd("End");
});

Route::get('convertIpayToMeterReceipt', function ()
{

	/*UTransaction::update_utransaction_by_current_day_interval_leaf_user_id_or_all(30,15151);
	dd("end");*/
	$leaf_api  = new LeafAPI();
	$UTransactionModel = UTransaction::all();
	foreach ($UTransactionModel as $model) {
	
		$result = $leaf_api->get_check_payment($model['leaf_payment_id']);

		if($result['payment_paid'] == true){
			if($model['is_paid'] == false){
				$model['is_paid'] = true ;
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

Route::get('updatePowerMeterSummary', function ()
{
	$c_list = Customer::all();
	 $now = new DateTime();

	 foreach ($c_list as $row) {
	 	$return = CustomerPowerUsageSummary::check_is_need_to_update_by_id_house_member($row['id_house_member']);
	 	echo $row['name'].'update:'.$row['updated_at']."=".$return.'<br>';
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
	Customer::customer_patching_from_leaf_member_by_leaf_group_id(285);
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

Route::get('getMeterUpdate', function ()
{
	$result;
        $fdata = [
                    'status_code'   =>  0,
                    //'status_msg'    =>  Language::trans('Data not yet update.'),
                    'data'   =>  [],
                    ];
        
        $date_range = ['date_started' => date('Y-m-d', strtotime('- 10 day',  strtotime('now'))) ,'date_ended'    => date('Y-m-d', strtotime('now')) ];
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

Route::get('initSub', function ()
{	/* MeterPaymentReceived::get_remaining_subsidy_member_id_by_meter_subsidiary_id(14);
	dd("Stop");*/
	$date_range['starting_date'] =  "2019-03";
	$date_range['ending_date']  = "2019-05";
	$subsidy_listing = MeterSubsidiary::get_subsidy_by_leaf_group_id(282);
	foreach ($subsidy_listing as $row) {
		MeterPaymentReceived::create_subsidy_meter_payment_received_model_patching($row['id'], $date_range);
	}
	
	dd("end");
});

Route::get('testD', function ()
{	/* MeterPaymentReceived::get_remaining_subsidy_member_id_by_meter_subsidiary_id(14);
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
	$leaf_api  = new LeafAPI();
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
	
	$fileUrl = 	asset('utility_charges_doc/utility_charges.html');
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
	$model = "App\ARInvoice";

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

Route::get('update_product_customer', function(){
	$c= new Company();
	$c->set_group_id(285);
	$customer = new Customer();

	$api = new LeafAPI();
	//$listing = $api->set_product_from_leaf_by_group_id(285);
	//printf("Product saved--");
	$houses= $api->get_customer_list();
//dd($houses);
	foreach($houses['house'] as $house){
        echo nl2br("Next");
		$customer->save_customer_from_leaf_house($house);
	}
	printf("customer saved--");
	dd("end");
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
        


/*	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTMLFile('https://stackoverflow.com/questions/10921457/php-retrieve-inner-html-as-string-from-url-using-domdocument');*/
/*	$data = $dom->getElementById("banner");
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
	Customer::set_customer_from_leaf(8809);
	
	//dd($api->get_all_leaf_payable_item_by_group_id(285));
	$listing = $api->set_product_from_leaf_by_group_id(285);
	foreach($listing as $item){
		
		Product::save_product_from_leaf($item);
	}

/*	$data = array(
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

	$houses =$leaf_api->get_houses_with_meter_register_detail();
	dd($houses[0]);
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
    $date_range 	= array('date_started' => '2018-05-01' ,'date_ended' =>  date('Y-m-d', strtotime('now')));
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

Route::prefix('web_store')->group(function() {
	Route::get('', 'AppsWebStoresController@getDashboard');
	Route::get('login', 'AppsWebStoresController@getLogin');
	Route::post('login', 'AppsWebStoresController@postLogin');
	Route::get('logout', 'AppsWebStoresController@getLogout');
	Route::get('search-histories', 'AppsWebStoresController@getSearchHistory');
	Route::get('clear-search-histories', 'AppsWebStoresController@getClearSearchHistory');
	Route::get('search-results', 'AppsWebStoresController@getSearchResult');
	Route::get('category', 'AppsWebStoresController@getCategory');
	Route::get('category-lists', 'AppsWebStoresController@getCategoryList');
	Route::get('faqs', 'AppsWebStoresController@getFAQ');
	Route::get('shipping-policy', 'AppsWebStoresController@getShippingPolicy');
	Route::get('refund-policy', 'AppsWebStoresController@getRefundPolicy');
	Route::get('store', 'AppsWebStoresController@getStore');
	Route::get('product', 'AppsWebStoresController@getProduct');
	Route::get('carts', 'AppsWebStoresController@getCart');
	Route::get('wishlist', 'AppsWebStoresController@getWishlist');
	Route::get('order-histories', 'AppsWebStoresController@getOrderHistory');
	Route::get('order-detail/{id}', 'AppsWebStoresController@getOrderDetail');
	Route::get('checkout', 'AppsWebStoresController@getCheckout');
	Route::post('checkout', 'AppsWebStoresController@postCheckout');
	Route::get('delivery-cost', 'AppsWebStoresController@getDeliveryCost');
	Route::get('return-payment-status', 'AppsWebStoresController@getReturnPaymentStatus');
	Route::get('my-page', 'AppsWebStoresController@getMypage');
	Route::get('user-profile', 'AppsWebStoresController@getUserProfile');
	Route::get('blank-page', 'AppsWebStoresController@getBlankpage');
	Route::get('contacts', 'AppsWebStoresController@getContactLists');
	Route::get('contacts/new', 'AppsWebStoresController@getNewContact');
	Route::post('contacts/new', 'AppsWebStoresController@postNewContact');
	Route::get('contacts/edit/{id}', 'AppsWebStoresController@getEditContact');
	Route::post('contacts/edit/{id}', 'AppsWebStoresController@postEditContact');
	Route::get('contacts/delete/{id}', 'AppsWebStoresController@getDeleteContact');
	Route::get('states/combobox', 'AppsWebStoresController@getStateCombobox');
});

Route::prefix('utility_charges')->group(function() {
	Route::get('dashboard', 'AppsUtilityChargesController@getDashboard');
	Route::get('dashboard/payment', 'AppsUtilityChargesController@getPayment');
	Route::get('dashboard/payment/history', 'AppsUtilityChargesController@getPaymentHistory');
	Route::get('dashboard/redirect/payment-gateway', 'AppsUtilityChargesController@getRedirectPaymentGateway');
	Route::get('dashboard/data', 'AppsUtilityChargesController@getDataTest');
});

Route::prefix('web')->group(function(){
	Route::prefix('utility_charges')->group(function(){
		Route::get('login', 'WebUtilityChargesController@getLogin');
		Route::get('logout', 'WebUtilityChargesController@getLogout');
		Route::post('login', 'WebUtilityChargesController@postLogin');
		Route::get('prepare-payment', 'WebUtilityChargesController@getPreparePayment');
		Route::post('prepare-payment', 'WebUtilityChargesController@postPreparePayment');
		Route::get('redirect/payment-gateway', 'WebUtilityChargesController@getRedirectPaymentGateway');
		Route::get('redirect/history-statements', 'WebUtilityChargesController@getHistoryUsage');
		Route::get('redirect/history-usages', 'WebUtilityChargesController@getHistoryStatement');
		Route::get('switch-group', 'WebUtilityChargesController@getSwitchGroup');
		Route::get('help', 'WebUtilityChargesController@getHelp');
		// Route::get('bill', 'WebUtilityChargesController@getBill');
	});
});


Route::prefix('payment')->group(function(){
	Route::get('/portals', 'AppAccountingDashboardsController@getDashboard');
	Route::get('/make-payment', 'AppAccountingDashboardsController@getPayment');
	Route::get('/payment-item/by/product-category-id', 'AppAccountingDashboardsController@getPaymentItemByProductCategoryId');
	Route::get('/payment-info/by/product-id', 'AppAccountingDashboardsController@getPaymentInfoByProductId');
	Route::get('/payment-page/by/product-id', 'AppAccountingDashboardsController@getPaymentPageByProductId');
});

Route::prefix('accounting')->group(function(){
	Route::get('/invoice/by/customer/id', 'AccountingsController@getInoviceByCustomerId');
	Route::get('/invoice/by/customer/id/type', 'AccountingsController@getCustomerDocumentByTypeAndId');
	Route::get('/document/by/customer/id/type', 'AccountingsController@getCustomerDocumentByCustomerIdAndType');
});

Route::prefix('api')->group(function(){
	Route::get('payment-received/related-document/new/{payment_id}', 'ARPaymentReceivedsController@postPaymentReceiptDocument');
	Route::get('store/create/product/', 'OCProductsController@createProduct');
	Route::get('store/edit/product/', 'OCProductsController@editProduct');
	Route::get('store/get/product/', 'OCProductsController@getProduct');
});

Route::prefix('admin')->group(function () {

	/*************** General Router ***************/

	Route::get('', 'DashboardsController@getIndex');
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

	// latest version with combine feature
	Route::get('dashboard', 'DashboardsController@getDashboard');
	Route::get('dashboard/count', 'DashboardsController@getDashboardCount');
	Route::get('dashboard/latest/power-usage-summary', 'DashboardsController@getLastestPowerUsageSummary');
	Route::get('settings', 'SettingsController@getIndex');
	Route::post('settings', 'SettingsController@postIndex');

	/*************** Invoicing & Inventory Router ***************/

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

	// states crud & listing
	Route::get('states', 'StatesController@getIndex');
	Route::get('states/new', 'StatesController@getNew');
	Route::post('states/new', 'StatesController@postNew');
	Route::get('states/edit/{id}', 'StatesController@getEdit');
	Route::post('states/edit/{id}', 'StatesController@postEdit');
	Route::get('states/view/{id}', 'StatesController@getView');
	Route::get('states/delete/{id}', 'StatesController@getDelete');
	Route::get('states/combobox', 'StatesController@getCombobox');


	// states crud & listing
	Route::get('mobile-settings', 'MobileSettingsController@getIndex');
	Route::get('mobile-settings/new', 'MobileSettingsController@getNew');
	Route::post('mobile-settings/new', 'MobileSettingsController@postNew');
	Route::get('mobile-settings/edit/{id}', 'MobileSettingsController@getEdit');
	Route::post('mobile-settings/edit/{id}', 'MobileSettingsController@postEdit');
	Route::get('mobile-settings/view/{id}', 'MobileSettingsController@getView');
	Route::get('mobile-settings/delete/{id}', 'MobileSettingsController@getDelete');
	Route::get('mobile-settings/combobox', 'MobileSettingsController@getCombobox');


	// cities crud & listing
	Route::get('cities', 'CitiesController@getIndex');
	Route::get('cities/new', 'CitiesController@getNew');
	Route::post('cities/new', 'CitiesController@postNew');
	Route::get('cities/edit/{id}', 'CitiesController@getEdit');
	Route::post('cities/edit/{id}', 'CitiesController@postEdit');
	Route::get('cities/view/{id}', 'CitiesController@getView');
	Route::get('cities/delete/{id}', 'CitiesController@getDelete');
	Route::get('cities/combobox', 'CitiesController@getCombobox');

	// payment-terms crud & listing
	Route::get('payment-terms', 'PaymentTermsController@getIndex');
	Route::get('payment-terms/new', 'PaymentTermsController@getNew');
	Route::post('payment-terms/new', 'PaymentTermsController@postNew');
	Route::get('payment-terms/edit/{id}', 'PaymentTermsController@getEdit');
	Route::post('payment-terms/edit/{id}', 'PaymentTermsController@postEdit');
	Route::get('payment-terms/view/{id}', 'PaymentTermsController@getView');
	Route::get('payment-terms/delete/{id}', 'PaymentTermsController@getDelete');
	Route::get('payment-terms/combobox', 'PaymentTermsController@getCombobox');

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

	// uoms crud & listing
	Route::get('uoms', 'UomsController@getIndex');
	Route::get('uoms/new', 'UomsController@getNew');
	Route::post('uoms/new', 'UomsController@postNew');
	Route::get('uoms/edit/{id}', 'UomsController@getEdit');
	Route::post('uoms/edit/{id}', 'UomsController@postEdit');
	Route::get('uoms/view/{id}', 'UomsController@getView');
	Route::get('uoms/delete/{id}', 'UomsController@getDelete');
	Route::get('uoms/combobox', 'UomsController@getCombobox');

	// products crud & listing
	Route::get('products', 'ProductsController@getIndex');
	Route::get('products/new', 'ProductsController@getNew');
	Route::post('products/new', 'ProductsController@postNew');
	Route::get('products/edit/{id}', 'ProductsController@getEdit');
	Route::post('products/edit/{id}', 'ProductsController@postEdit');
	Route::get('products/view/{id}', 'ProductsController@getView');
	Route::get('products/delete/{id}', 'ProductsController@getDelete');
	Route::get('products/combobox', 'ProductsController@getCombobox');
	Route::get('products/info', 'ProductsController@getInfo');
	Route::get('products/info/by/leaf-product-id', 'ProductsController@getInfoByLeafProductId');

	// products crud & listing
	Route::get('oc/products', 'OCProductsController@getIndex');
	Route::get('oc/products/new', 'OCProductsController@getNew');
	Route::post('products/new', 'OCProductsController@postNew');
	Route::get('oc/products/edit/{id}', 'OCProductsController@getEdit');
	Route::post('oc/products/edit/{id}', 'OCProductsController@postEdit');
	Route::get('oc/products/view/{id}', 'OCProductsController@getView');
	Route::get('oc/products/delete/{id}', 'OCProductsController@getDelete');
	Route::get('oc/products/detail', 'OCProductsController@getProductDetail');
	Route::get('oc/products/edit/price', 'OCProductsController@getProductPriceUpdate');
	Route::get('oc/get/all-products-from-ego888/', 'OCProductsController@getAllProductsFromEgo888');
	

	// product-categories crud & listing
	Route::get('product-categories', 'ProductCategoriesController@getIndex');
	Route::get('product-categories/new', 'ProductCategoriesController@getNew');
	Route::post('product-categories/new', 'ProductCategoriesController@postNew');
	Route::get('product-categories/edit/{id}', 'ProductCategoriesController@getEdit');
	Route::post('product-categories/edit/{id}', 'ProductCategoriesController@postEdit');
	Route::get('product-categories/view/{id}', 'ProductCategoriesController@getView');
	Route::get('product-categories/delete/{id}', 'ProductCategoriesController@getDelete');
	Route::get('product-categories/combobox', 'ProductCategoriesController@getCombobox');

	// taxes crud & listing
	Route::get('taxes', 'TaxesController@getIndex');
	Route::get('taxes/new', 'TaxesController@getNew');
	Route::post('taxes/new', 'TaxesController@postNew');
	Route::get('taxes/edit/{id}', 'TaxesController@getEdit');
	Route::post('taxes/edit/{id}', 'TaxesController@postEdit');
	Route::get('taxes/view/{id}', 'TaxesController@getView');
	Route::get('taxes/delete/{id}', 'TaxesController@getDelete');
	Route::get('taxes/info', 'TaxesController@getInfo');


	// mobile layout crud & listing
	Route::get('mobile-layouts', 'MobileLayoutsController@getIndex');
	Route::get('mobile-layouts/new', 'MobileLayoutsController@getNew');
	Route::post('mobile-layouts/new', 'MobileLayoutsController@postNew');
	Route::get('mobile-layouts/edit/{id}', 'MobileLayoutsController@getEdit');
	Route::post('mobile-layouts/edit/{id}', 'MobileLayoutsController@postEdit');
	Route::get('mobile-layouts/view/{id}', 'MobileLayoutsController@getView');
	Route::get('mobile-layouts/delete/{id}', 'MobileLayoutsController@getDelete');
	
	// helps crud & listing
	Route::get('helps', 'HelpsController@getIndex');
	Route::get('helps/new', 'HelpsController@getNew');
	Route::post('helps/new', 'HelpsController@postNew');
	Route::get('helps/edit/{id}', 'HelpsController@getEdit');
	Route::post('helps/edit/{id}', 'HelpsController@postEdit');
	Route::get('helps/view/{id}', 'HelpsController@getView');
	Route::get('helps/delete/{id}', 'HelpsController@getDelete');

	// developer crud & listing
	Route::get('developers', 'DevelopersController@getIndex');
	Route::get('developers/payment-test', 'DevelopersController@getPaymentTestIndex');
	Route::get('developers/new', 'DevelopersController@getNew');
	Route::post('developers/new', 'DevelopersController@postNew');
	Route::get('developers/edit/{id}', 'DevelopersController@getEdit');
	Route::post('developers/edit/{id}', 'DevelopersController@postEdit');
	Route::get('developers/view/{id}', 'DevelopersController@getView');
	Route::get('developers/delete/{id}', 'DevelopersController@getDelete');

	// payment listing crud & listing
	Route::get('payment/testing/allow/lists', 'PaymentTestingAllowListsController@getIndex');
	Route::get('payment/testing/allow/lists/payment-test', 'PaymentTestingAllowListsController@getPaymentTestIndex');
	Route::get('payment/testing/allow/lists/new', 'PaymentTestingAllowListsController@getNew');
	Route::post('payment/testing/allow/lists/new', 'PaymentTestingAllowListsController@postNew');
	Route::get('payment/testing/allow/lists/edit/{id}', 'PaymentTestingAllowListsController@getEdit');
	Route::post('payment/testing/allow/lists/edit/{id}', 'PaymentTestingAllowListsController@postEdit');
	Route::get('payment/testing/allow/lists/view/{id}', 'PaymentTestingAllowListsController@getView');
	Route::get('payment/testing/allow/lists/delete/{id}', 'PaymentTestingAllowListsController@getDelete');

	// opencart translator crud & listing	
	Route::get('opencart-translators', 'OpencartLanguageTranslatorsController@getIndex');
	Route::get('opencart-translators/log', 'OpencartLanguageTranslatorsController@getTranslationStatus');
	Route::get('opencart-translators/copy/{id}', 'OpencartLanguageTranslatorsController@getCopy');	
	Route::get('opencart-translators/new', 'OpencartLanguageTranslatorsController@getNew');
	Route::get('opencart-translators/next/{id}', 'OpencartLanguageTranslatorsController@getNext');
	Route::post('opencart-translators/new', 'OpencartLanguageTranslatorsController@postNew');
	Route::get('opencart-translators/edit/{id}', 'OpencartLanguageTranslatorsController@getEdit');
	Route::post('opencart-translators/edit/{id}', 'OpencartLanguageTranslatorsController@postEdit');
	Route::get('opencart-translators/view/{id}', 'OpencartLanguageTranslatorsController@getView');
	Route::get('opencart-translators/delete/{id}', 'OpencartLanguageTranslatorsController@getDelete');

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

	// customer-groups crud & listing
	Route::get('customer-groups', 'CustomerGroupsController@getIndex');
	Route::get('customer-groups/new', 'CustomerGroupsController@getNew');
	Route::post('customer-groups/new', 'CustomerGroupsController@postNew');
	Route::get('customer-groups/edit/{id}', 'CustomerGroupsController@getEdit');
	Route::post('customer-groups/edit/{id}', 'CustomerGroupsController@postEdit');
	Route::get('customer-groups/view/{id}', 'CustomerGroupsController@getView');
	Route::get('customer-groups/delete/{id}', 'CustomerGroupsController@getDelete');

	// ar-invoices crud & listing
	Route::get('ar-invoices', 'ARInvoicesController@getIndex');
	Route::get('ar-invoices/new', 'ARInvoicesController@getNew');
	Route::post('ar-invoices/new', 'ARInvoicesController@postNew');
	Route::get('ar-invoices/edit/{id}', 'ARInvoicesController@getEdit');
	Route::post('ar-invoices/edit/{id}', 'ARInvoicesController@postEdit');
	Route::get('ar-invoices/view/{id}', 'ARInvoicesController@getView');
	Route::get('ar-invoices/print/{id}', 'ARInvoicesController@getPrint');
	Route::get('ar-invoices/delete/{id}', 'ARInvoicesController@getDelete');

	// tickets crud & listing
	Route::get('tickets', 'TicketsController@getIndex');
	Route::get('tickets/new', 'TicketsController@getNew');
	Route::post('tickets/new', 'TicketsController@postNew');
	Route::get('tickets/edit/{id}', 'TicketsController@getEdit');
	Route::post('tickets/edit/{id}', 'TicketsController@postEdit');
	Route::get('tickets/view/{id}', 'TicketsController@getView');
	Route::get('tickets/solve/{id}', 'TicketsController@getSolve');
	Route::post('tickets/solve/{id}', 'TicketsController@postSolve');
	Route::get('tickets/delete/{id}', 'TicketsController@getDelete');
	Route::get('booking/facilities', 'IframesController@getBookingFacility');

	// internal tickets crud & listing
	Route::get('internal-tickets', 'InternalTicketsController@getIndex');
	Route::get('internal-tickets/new', 'InternalTicketsController@getNew');
	Route::post('internal-tickets/new', 'InternalTicketsController@postNew');
	Route::get('internal-tickets/edit/{id}', 'InternalTicketsController@getEdit');
	Route::post('internal-tickets/edit/{id}', 'InternalTicketsController@postEdit');
	Route::get('internal-tickets/view/{id}', 'InternalTicketsController@getView');
	Route::get('internal-tickets/solve/{id}', 'InternalTicketsController@getSolve');
	Route::post('internal-tickets/solve/{id}', 'InternalTicketsController@postSolve');
	Route::get('internal-tickets/delete/{id}', 'InternalTicketsController@getDelete');	

	// payment received crud & listing
	Route::get('payment-received', 'ARPaymentReceivedsController@getIndex');
	Route::get('payment-received/new', 'ARPaymentReceivedsController@getNew');
	Route::post('payment-received/new', 'ARPaymentReceivedsController@postNew');
	Route::get('payment-received/edit/{id}', 'ARPaymentReceivedsController@getEdit');
	Route::post('payment-received/edit/{id}', 'ARPaymentReceivedsController@postEdit');
	Route::get('payment-received/view/{id}', 'ARPaymentReceivedsController@getView');
	Route::get('payment-received/print/{id}', 'ARPaymentReceivedsController@getPrint');
	Route::get('payment-received/setia/pdf/{id}', 'ARPaymentReceivedsController@getSetiaPdf');
	Route::get('payment-received/delete/{id}', 'ARPaymentReceivedsController@getDelete');
	Route::get('payment-received/transaction/summary', 'ARPaymentReceivedsController@getARTransactionSummary');
	
	// refund crud & listing
	Route::get('ar-refunds', 'ARRefundsController@getIndex');
	Route::get('ar-refunds/new', 'ARRefundsController@getNew');
	Route::post('ar-refunds/new', 'ARRefundsController@postNew');
	Route::get('ar-refunds/edit/{id}', 'ARRefundsController@getEdit');
	Route::post('ar-refunds/edit/{id}', 'ARRefundsController@postEdit');
	Route::get('ar-refunds/view/{id}', 'ARRefundsController@getView');
	Route::get('ar-refunds/print/{id}', 'ARRefundsController@getPrint');
	Route::get('ar-refunds/delete/{id}', 'ARRefundsController@getDelete');

	// leaf payment item to ncl account mapper crud & listing
	Route::get('leaf-payment-item-to-ncl-account-mapper', 'LeafPaymentItemToNCLAccountMappersController@getIndex');
	Route::get('leaf-payment-item-to-ncl-account-mapper/new', 'LeafPaymentItemToNCLAccountMappersController@getNew');
	Route::post('leaf-payment-item-to-ncl-account-mapper/new', 'LeafPaymentItemToNCLAccountMappersController@postNew');
	Route::get('leaf-payment-item-to-ncl-account-mapper/edit/{id}', 'LeafPaymentItemToNCLAccountMappersController@getEdit');
	Route::post('leaf-payment-item-to-ncl-account-mapper/edit/{id}', 'LeafPaymentItemToNCLAccountMappersController@postEdit');
	Route::get('leaf-payment-item-to-ncl-account-mapper/view/{id}', 'LeafPaymentItemToNCLAccountMappersController@getView');
	Route::get('leaf-payment-item-to-ncl-account-mapper/delete/{id}', 'LeafPaymentItemToNCLAccountMappersController@getDelete');

	/*************** Power Meter Router ***************/

	// meter invoices crud & listing
	Route::get('meter/invoices', 'UMeterInvoiceController@getIndex');
	Route::get('meter/invoices/new', 'UMeterInvoiceController@getNew');
	Route::post('meter/invoices/new', 'UMeterInvoiceController@postNew');
	Route::get('meter/invoices/edit/{id}', 'UMeterInvoiceController@getEdit');
	Route::post('meter/invoices/edit/{id}', 'UMeterInvoiceController@postEdit');
	Route::get('meter/invoices/view/{id}', 'UMeterInvoiceController@getView');
	Route::get('meter/invoices/get/invoice/room-id/type', 'UMeterInvoiceController@getInvoiceDocumentByRoomIdAndType');
	

	// meter payment received crud & listing
	Route::get('meter/payment-received', 'UMeterPaymentReceivedsController@getIndex');
	Route::get('meter/payment-received/new', 'UMeterPaymentReceivedsController@getNew');
	Route::post('meter/payment-received/new', 'UMeterPaymentReceivedsController@postNew');
	Route::get('meter/payment-received/edit/{id}', 'UMeterPaymentReceivedsController@getEdit');
	Route::post('meter/payment-received/edit/{id}', 'UMeterPaymentReceivedsController@postEdit');
	Route::get('meter/payment-received/view/{id}', 'UMeterPaymentReceivedsController@getView');

	// meter refund crud & listing
	Route::get('meter/ar-refunds', 'UMeterRefundsController@getIndex');
	Route::get('meter/ar-refunds/new', 'UMeterRefundsController@getNew');
	Route::post('meter/ar-refunds/new', 'UMeterRefundsController@postNew');
	Route::get('meter/ar-refunds/edit/{id}', 'UMeterRefundsController@getEdit');
	Route::post('meter/ar-refunds/edit/{id}', 'UMeterRefundsController@postEdit');
	Route::get('meter/ar-refunds/view/{id}', 'UMeterRefundsController@getView');

	// meter subsidiary
	Route::get('meter/subsidiaries', 'UMeterSubsidiariesController@getIndex');
	Route::get('meter/subsidiaries/new', 'UMeterSubsidiariesController@getNew');
	Route::post('meter/subsidiaries/new', 'UMeterSubsidiariesController@postNew');
	Route::get('meter/subsidiaries/edit/{id}', 'UMeterSubsidiariesController@getEdit');
	Route::post('meter/subsidiaries/edit/{id}', 'UMeterSubsidiariesController@postEdit');
	Route::get('meter/subsidiaries/view/{id}', 'UMeterSubsidiariesController@getView');

	// meter reading
	Route::get('meter/readings', 'UMeterReadingController@getIndex');

	// meter report
	Route::get('reports/sales', 'ReportsController@getSalesReport');
	Route::get('reports/invoices', 'ReportsController@getInvoices');
	Route::get('reports/monthly/sales', 'ReportsController@getMonthlySales');
	Route::get('reports/monthly/usages', 'ReportsController@getMonthlyUsages');
	Route::get('reports/room/usages', 'ReportsController@getRoomUsages');
	Route::get('reports/lastest/daily-meter-reading', 'ReportsController@getLatestDailyMeterReading');
	Route::get('reports/lastest/daily-meter-reading-by-daily-record-summary', 'ReportsController@getLatestDailyMeterReadingByDailyRecordSummary');

	// charges crud & listing
	Route::get('charges', 'UtilityChargesController@getIndex');
	Route::get('charges/new', 'UtilityChargesController@getNew');
	Route::post('charges/new', 'UtilityChargesController@postNew');
	Route::get('charges/edit/{id}', 'UtilityChargesController@getEdit');
	Route::post('charges/edit/{id}', 'UtilityChargesController@postEdit');
	Route::get('charges/view/{id}', 'UtilityChargesController@getView');
	Route::get('charges/delete/{id}', 'UtilityChargesController@getDelete');
	Route::get('charges/list', 'UtilityChargesController@getList');
	Route::get('charges/charge-estimated', 'UtilityChargesController@getChargeEstimated');

	// meter registers crud & listing
	Route::get('meter/registers', 'UMeterRegistersController@getIndex');
	Route::get('meter/registers/new', 'UMeterRegistersController@getNew');
	Route::post('meter/registers/new', 'UMeterRegistersController@postNew');
	Route::get('meter/registers/edit/{id}', 'UMeterRegistersController@getEdit');
	Route::post('meter/registers/edit/{id}', 'UMeterRegistersController@postEdit');
	Route::get('meter/registers/view/{id}', 'UMeterRegistersController@getView');
	Route::get('meter/registers/delete/{id}', 'UMeterRegistersController@getDelete');
	Route::get('meter/registers/combobox', 'UMeterRegistersController@getCombobox');
	Route::get('meter/registers/room/info', 'UMeterRegistersController@getRoomInfo');
	Route::get('meter/registers/rate', 'UMeterRegistersController@getRate');
	Route::get('meter/status', 'UMeterRegistersController@getStatus');
	Route::post('meter/status', 'UMeterRegistersController@postStatus');
	Route::get('meter/status/detail', 'UMeterRegistersController@getStatusDetail');
	Route::post('meter/status/detail', 'UMeterRegistersController@postStatusDetail');
 	Route::get('meter/by/room-id', 'UMeterRegistersController@getMeterRegisteryRoomId');
 	Route::get('meter-detail/by/room-id', 'UMeterRegistersController@getMeterDetail');



	// meter classes crud & listing
	Route::get('meter/classes', 'UMeterClassController@getIndex');
	Route::get('meter/classes/new', 'UMeterClassController@getNew');
	Route::post('meter/classes/new', 'UMeterClassController@postNew');
	Route::get('meter/classes/edit/{id}', 'UMeterClassController@getEdit');
	Route::post('meter/classes/edit/{id}', 'UMeterClassController@postEdit');
	Route::get('meter/classes/view/{id}', 'UMeterClassController@getView');
	Route::get('meter/classes/delete/{id}', 'UMeterClassController@getDelete');

	/*************** Umrah Router ***************/

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

		// states crud & listing
		Route::get('states', 'StatesController@getIndex');
		Route::get('states/new', 'StatesController@getNew');
		Route::post('states/new', 'StatesController@postNew');
		Route::get('states/edit/{id}', 'StatesController@getEdit');
		Route::post('states/edit/{id}', 'StatesController@postEdit');
		Route::get('states/view/{id}', 'StatesController@getView');
		Route::get('states/delete/{id}', 'StatesController@getDelete');
		Route::get('states/combobox', 'StatesController@getCombobox');

		// cities crud & listing
		Route::get('cities', 'CitiesController@getIndex');
		Route::get('cities/new', 'CitiesController@getNew');
		Route::post('cities/new', 'CitiesController@postNew');
		Route::get('cities/edit/{id}', 'CitiesController@getEdit');
		Route::post('cities/edit/{id}', 'CitiesController@postEdit');
		Route::get('cities/view/{id}', 'CitiesController@getView');
		Route::get('cities/delete/{id}', 'CitiesController@getDelete');
		Route::get('cities/combobox', 'CitiesController@getCombobox');

		// to-do-list crud & listing
		Route::get('to-do-list', 'ToDoListsController@getIndex');
		Route::get('to-do-list/new', 'ToDoListsController@getNew');
		Route::post('to-do-list/new', 'ToDoListsController@postNew');
		Route::get('to-do-list/edit/{id}', 'ToDoListsController@getEdit');
		Route::post('to-do-list/edit/{id}', 'ToDoListsController@postEdit');
		Route::get('to-do-list/view/{id}', 'ToDoListsController@getView');
		Route::get('to-do-list/delete/{id}', 'ToDoListsController@getDelete');

		// to-do-list categories crud & listing
		Route::get('to-do-list/categories', 'ToDoListCategoriesController@getIndex');
		Route::get('to-do-list/categories/new', 'ToDoListCategoriesController@getNew');
		Route::post('to-do-list/categories/new', 'ToDoListCategoriesController@postNew');
		Route::get('to-do-list/categories/edit/{id}', 'ToDoListCategoriesController@getEdit');
		Route::post('to-do-list/categories/edit/{id}', 'ToDoListCategoriesController@postEdit');
		Route::get('to-do-list/categories/view/{id}', 'ToDoListCategoriesController@getView');
		Route::get('to-do-list/categories/delete/{id}', 'ToDoListCategoriesController@getDelete');

		// stores crud & listing
		Route::get('stores', 'StoresController@getIndex');
		Route::get('stores/new', 'StoresController@getNew');
		Route::post('stores/new', 'StoresController@postNew');
		Route::get('stores/edit/{id}', 'StoresController@getEdit');
		Route::post('stores/edit/{id}', 'StoresController@postEdit');
		Route::get('stores/view/{id}', 'StoresController@getView');
		Route::get('stores/delete/{id}', 'StoresController@getDelete');

		// vouchers crud & listing
		Route::get('vouchers', 'VouchersController@getIndex');
		Route::get('vouchers/new', 'VouchersController@getNew');
		Route::post('vouchers/new', 'VouchersController@postNew');
		Route::get('vouchers/edit/{id}', 'VouchersController@getEdit');
		Route::post('vouchers/edit/{id}', 'VouchersController@postEdit');
		Route::get('vouchers/view/{id}', 'VouchersController@getView');
		Route::get('vouchers/delete/{id}', 'VouchersController@getDelete');

		// voucher assigns crud & listing
		Route::get('voucher/assigns', 'VoucherAssignsController@getIndex');
		Route::get('voucher/assigns/new', 'VoucherAssignsController@getNew');
		Route::post('voucher/assigns/new', 'VoucherAssignsController@postNew');
		Route::get('voucher/assigns/view/{id}', 'VoucherAssignsController@getView');

		// users crud & listing
		Route::get('users', 'UsersController@getIndex');
		Route::get('users/new', 'UsersController@getNew');
		Route::post('users/new', 'UsersController@postNew');
		Route::get('users/edit/{id}', 'UsersController@getEdit');
		Route::post('users/edit/{id}', 'UsersController@postEdit');
	});

});

Route::get('dashboard/charges', 'DashboardsController@getUtilityChargeIndex');

Route::prefix('api')->group(function(){
	Route::prefix('utility_charges')->group(function(){
		Route::get('meter_reading/new', 'UMeterReadingController@getNew');
		Route::get('meter_reading/listing', 'UMeterReadingController@getListing');
	});
});

Route::prefix('test')->group(function(){
		Route::get('test/index', 'TestCasesController@getIndex');
		Route::get('test/update-customer-from-leaf', 'TestCasesController@getIndex');
});


// Whatsapp API
Route::prefix('whatsapp')->group(function(){
	Route::get('send_message', 'WhatsappController@getSendMessage');
	Route::get('pull_message', 'WhatsappController@getMessage');
	Route::get('get_credit', 'WhatsappController@getCredit');
	Route::post('inbound', 'WhatsappInboundController@postTakeResponse');
});





