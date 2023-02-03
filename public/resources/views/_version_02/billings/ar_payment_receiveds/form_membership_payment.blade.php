@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.billings.ar_payment_receiveds.partials._hidden_variable')
<div>
{!! Form::model($model, ['class'=>'form-horizontal','id'=>'membership-payment-form']) !!}
    <h3>
        <span class="wizard-icon-wrap"><i class="ion ion-md-person-add"></i></span>
        <span class="wizard-head-text-wrap">
            <span class="step-head">Membership Renewal</span>
        </span> 
    </h3>
    <section>

        <h3 class="display-4 mb-20">Membership Renewal Application</h3>
        <div class="row">   
            <div class="col-xl-4 mb-20">
                <div class="card mt-10">
                    <div class="card-body bg-light">
                        <h5 class="card-title"><label id="lbl_product_name">{{$membership['type']}}</label></h5> 
                        <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                               <!--  {!! Form::select('product_id', App\Product::leaf_all_payable_item_combobox(), null, ['class'=>'form-control card-title','onchange'=>'init_state_selectbox(this)']) !!} -->
                                {!!$errors->first('product_id', '<label for="product_id" class="help-block error">:message</label>')!!}
                            </div>
                        </div>
                        <p class="card-text">Annual fee : RM <label id="lbl_product_amount">{{$model['amount']}}</label></h5></p>                    
                    </div>
                </div>

                <p class="mb-10">Membership renewal application require staff to verify.</p>
                <p><a href="https://itunes.apple.com/my/app/setia-community/id1437036518?mt=8">Email will be sent once application is approved , you can get your mobile apps for latest information. </a></p>
                
            </div>

            <div class="col-xl-8 mb-20">
                     
                    <div class="form-group">
                        <label for="username">Name</label>
                        <div class="input-group">
                            <input class="form-control" id="username" placeholder="Username" type="text" value="{{$customer['name']}}" readonly="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" id="email" placeholder="you@example.com" type="email" value="{{$customer['email']}}" readonly="true">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input class="form-control" id="address" placeholder="Address" value="" type="text" readonly="true">
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-10">
                            <label for="country">Country</label>
                            {!! Form::select('country_id', App\Country::combobox(), null, ['class'=>'form-control','onchange'=>'init_state_selectbox(this)']) !!}
                            {!!$errors->first('country_id', '<label for="country_id" class="help-block error">:message</label>')!!}
                        </div>
                        <div class="col-md-4 mb-10">
                            <label for="state">State</label>
                            {!! Form::select('state_id', App\State::combobox(old('country_id') ? old('country_id'):$model->country_id), null, ['class'=>'form-control','onchange'=>'init_city_selectbox(this)']) !!}
                            {!!$errors->first('state_id', '<label for="state_id" class="help-block error">:message</label>')!!}
                        </div>
                        <div class="col-md-3 mb-10">
                            <label for="zip">City</label>
                            {!! Form::select('city_id', App\City::combobox(old('state_id') ? old('state_id'):$model->state_id), null, ['class'=>'form-control']) !!}
                            {!!$errors->first('city_id', '<label for="city_id" class="help-block error">:message</label>')!!}
                        </div>
                    </div>
                    <hr>
               
            </div>
        </div>
    </section>

    <h3>
		<span class="wizard-icon-wrap"><i class="ion ion-md-paper"></i></span>
		<span class="wizard-head-text-wrap">
			<span class="step-head">Review Application</span>
		</span>	
	</h3>
    <section>
        <h3 class="display-4 mb-20">Members</h3>
        <div class="row">
            <div class="col-xl-8 mb-20">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Identity Card No.</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                                @if(isset($membership['id']))
                                    @foreach($membership->items as $member)
                                        <tr id={{$member['id_house_member']}}>
                                            <td><img class="w-80p" src="{{$member["house_member_photo"] == "" ?  asset('img/img-thumb.jpg')  : $member["house_member_photo"]}}" alt="icon" /></td>
                                            <th scope="row">{{$member['name']}}</th>
                                            <td>{{$member['ic']}}</td>
                                            <td class="text-dark">{{$member['email']}}</td>
                                            <td>
                                                    <span class="" onclick="create_or_update_member_modal({{$member['id_house_member']}});"><i data-feather="search"></i></span>
                                            </td>
                            
                                        </tr> 
                                    @endforeach              
                                @endif                             
                            </tbody>
                            <tfoot>
                                <tr>
                                    <!-- <td colspan="2">
                                        <div class="input-group">
                                            <input type="text" class="form-control filled-input" placeholder="Enter coupon code">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">Apply</button>
                                            </div>
                                        </div>
                                    </td> -->
                                    <!-- <td class="text-right" colspan="2"><small class="pr-5 text-muted font-weight-500">Discount:</small><span class="text-dark font-weight-500">$15</span></td> -->
                                    <!-- <td class="text-right" colspan="2"><small class="pr-5 text-muted font-weight-500">Sub Total:</small><span class="text-dark font-weight-500">$859</span></td> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
           
            <div class="col-xl-4 mb-20">
                <div class="card">
                    <h6 class="card-header border-0">
						<i class="ion ion-md-clipboard font-21 mr-10"></i>Summary [RM]
					</h6>
                    <div class="card-body pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="w-70" scope="row">Sub Total</th>
                                            <th class="w-30" scope="row" id="member_sub_total">{{$model['amount']}}</th>
                                        </tr>
                                        <tr>
                                            <td class="w-70">Discount</td>
                                            <td class="w-30">-</td>
                                        </tr>
                                        <!-- <tr>
                                            <td class="w-70">Packging charges</td>
                                            <td class="w-30">$8</td>
                                        </tr> -->
                                        <tr>
                                            <td class="w-70">Tax</td>
                                            <td class="w-30">-</td>
                                        </tr>
                                        <!-- <tr>
                                            <td class="w-70 text-success">Delivery charges</td>
                                            <td class="w-30 text-success">Free</td>
                                        </tr> -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th class="text-dark text-uppercase" scope="row">To Pay</th>
                                            <th class="text-dark font-18" scope="row" id="member_payable_amount">{{$model['amount']}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <h3>
		<span class="wizard-icon-wrap"><i class="ion ion-md-card"></i></span>
		<span class="wizard-head-text-wrap">  
			<span class="step-head">Payment</span>
		</span>	
	</h3>
    <hr>
    <section>
        <h3 class="display-4 mb-10">Choose payment method</h3>
        <div class="row">
            <div class="col-xl-5 mb-20">
                <div class="tab-content">
                    <div class="tab-pane fade show active">
                        <h6 class="my-15"><i class="ion ion-md-card text-grey pr-10"></i>Online Transfer</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-25">
                                    <span class="font-14 d-block font-weight-600 text-uppercase mb-10">We are using Molpay as payment gateway for online payment.</span>
                                    <img src="{{asset('img/payment/molpay-logo.png')}}" alt="card" class="avatar avatar-md rounded-circle" />
                                </div>
                                <span class="d-block text-dark font-20 letter-spacing-20 font-weight-600 "><!-- Any inquiry , please contact person incharge. --></span>
                            </div>
                        </div>
                    </div>
                </div> 

                <p>The most common alternative payment methods are debit cards, charge cards, prepaid cards, direct debit, bank transfers, phone and mobile payments, checks, money orders and cash payments.</p>
                 
                       
               
            </div>
            <div class="col-xl-7 mb-20">
                <form class="list" id="my-form" action="{{action('AppAccountingDashboardsController@getPayment')}}" method="get">
                    <div class="d-flex align-items-center mb-30">
                        <span class="font-12 pr-15 text-dark text-uppercase font-weight-600">We accept</span>
                        <img class="mr-15" src={{asset('version_2/dist/img/card-visa.png')}} alt="card" />
                        <img class="mr-15" src={{asset('version_2/dist/img/card-mc.png')}} alt="card" />
                    </div>

                    <div class="row mt-15">
                            <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                                {!! Form::label('payment_method', App\Language::trans('Payment Method'), ['class'=>'control-label col-md-12']) !!}
                                <div class="col-md-12">
                                    {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control','autofocus','required' , 'onchange'=>'change_payment_label_text_by_payment_method(this)']) !!} {!!$errors->first('payment_method', '
                                    <label for="payment_method" class="help-block error">:message</label>')!!}
                                </div>
                            </div>
                    </div>
                    <div class="row">
                     <div class="form-group{{ $errors->has('reference_no') ? ' has-error' : '' }}">
                            {!! Form::label('doc_payment_no_ref_no', App\Language::trans('Reference No.'), ['id'=>'doc_payment_no_ref_no', 'class'=>'control-label col-md-12']) !!}
                            <div class="col-md-12">
                                {!! Form::text('reference_no', null, ['class'=>'form-control','autofocus','required']) !!} {!!$errors->first('reference_no', '
                                <label for="reference_no" class="help-block error">:message</label>')!!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="custom-control custom-checkbox checkbox-success">
                            <input class="custom-control-input" id="same-card" type="checkbox" name="is_agreed_to_tac" id="is_agreed_to_tac" onclick="check_is_allow_to_pay(this);">
                            <label class="custom-control-label" for="same-card">You are agreed with the Term and Conditions.</label>
                    </div>
                    <button href="{{action('AppAccountingDashboardsController@getPayment')}}" target='_blank' class="btn btn-primary btn-block" id="btn_payment" disabled="true">Pay {{$model['amount']}}</button>
                    <small class="form-text text-muted">Payment details will be saved securely as per industry standard</small>
                    
                    <input type="hidden" id="user" name="user" value="{{$user_profile_string}}">
                    <input type="hidden" id="leaf_group_id" name="leaf_group_id" value="{{$leaf_group_id}}">
                    <input type="hidden" id="product_model" name="product_model" value="{{$model}}">
                    <input type="hidden" id="payment_amount" name="payment_amount" value="{{$model['amount']}}">
                    <input type="hidden" id="customer_model" name="customer_model" value="{{json_encode($customer)}}">
                    <input type="hidden" id="membership_model" name="membership_model" value="{{json_encode($membership)}}">
                    <input type="hidden" id="payment_method" name="payment_method" value="molpay">
                </form>
            </div>
        </div>
    </section>   
{!! Form::close() !!}
</div>
@endsection
@section('script')
var customerInfoUrl = "{{action('CustomersController@getInfo')}}";  
var productInfoByLeafProductIdUrl = "{{action('ProductsController@getInfoByLeafProductId')}}";

function init_membership_info_by_leaf_product_id(me, type) {
        $.get(productInfoByLeafProductIdUrl, {product_id:$(me).val()}, function(fdata){     
           product = fdata.data;
           //$('#lbl_product_name').html(product.description);
           $('#lbl_product_amount').html(product.amount);
           
           $('#summary_total_amount').html(product.amount);
           $('#btn_payment').html('Pay '+product.amount);

           $('#summary_sub_total').html(product.amount);
           $('#summary_total').html(product.amount);

           $('#member_payable_amount').html(product.amount);
           $('#member_sub_total').html(product.amount);
           
           
        },"json");
}
 
@endsection