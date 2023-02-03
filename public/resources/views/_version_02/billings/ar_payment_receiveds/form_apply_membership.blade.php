@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.billings.ar_payment_receiveds.partials._hidden_variable')
@include('_version_02.billings.ar_payment_receiveds.partials._add_new_member_modal')
<div>
    {!! Form::model($model, ['class'=>'form-horizontal','id'=>'membership-application-form']) !!}
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
                        {!!Form::select('product_id', App\Product::membership_combobox(), isset($leaf_product_id) ? $leaf_product_id : null , ['class'=>'form-control','onchange'=>'init_membership_info_by_leaf_product_id(this, "sales")'])!!}
                        <!-- <h5 class="card-title"><label id="lbl_product_name">{{$model['name']}}</label></h5> -->
                        <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                               <!--  {!! Form::select('product_id', App\Product::leaf_all_payable_item_combobox(), null, ['class'=>'form-control card-title','onchange'=>'init_state_selectbox(this)']) !!} -->
                                {!!$errors->first('product_id', '<label for="product_id" class="help-block efrror">:message</label>')!!}
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
                            <input class="form-control" id="username" placeholder="Username" type="text" value="{{$customer['name']}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" id="email" placeholder="you@example.com" type="email" value="{{$customer['email']}}">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input class="form-control" id="address" placeholder="Address" value="" type="text">
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
                        <table class="table mb-0" id="membership_item_table">
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
                                            <td><img class="w-80p" src="{{$member["profile_photo"] == "" ?  asset('img/img-thumb.jpg')  : $member["house_member_photo"]}}" alt="icon" /></td>
                                            <th scope="row">{{$member['name']}}</th>
                                            <td>{{$member['ic']}}</td>
                                            <td class="text-dark">{{$member['email']}}</td>
                                            <td>
                                                <label class="alert alert-success alert-wth-icon alert-dismissible fade show" role="alert" onclick="create_or_update_member_modal({{$member['id_house_member']}});">
                                                    <span class="alert-icon-wrap"><i class="zmdi zmdi-edit"></i></span> Edit
                                                </label>
                                            </td>
                                            <td>
                                                <button type="button" class="close" aria-label="Edit" onclick="remove_row_membership_application({{$member['id_house_member']}} , 'membership_item_table');">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </td> 
                                        </tr> 
                                    @endforeach 
                                    <!-- <tr>
                                        <td colspan="6" class="text-center">
                                          
                                                    <span class="feather-icon"><i data-feather="file-plus" data-toggle="modal" data-target="#add_nem_member_modal"></i>Add</span>
                                                    @include('_version_02.billings.ar_payment_receiveds.partials._add_new_member_modal')
                                         
                                        </td>
                                    <tr> -->
                                @endif 

                                 @php
                                    $leaf_api = new App\LeafAPI();
                                    if($membership != false && isset($membership['leaf_product_id'])){
                                        $fee_type = $leaf_api->get_single_fee_type($membership['leaf_product_id']);  
                                    }
                                    $remaining_slot = isset($fee_type['fee_type_user_per_unit']) ?   ($fee_type['fee_type_user_per_unit'] - ( isset($membership['id']) ? count($membership->items)  : 0 ))  : 0;

                                @endphp    
                                @for($i=0 ; $i < $remaining_slot ; $i++ )

                                    <tr id={{$i."_new_member"}}>
                                            <td colspan="6" class="text-center"> 
                                                <label class="alert alert-success alert-wth-icon alert-dismissible fade show" role="alert"  onclick="init_member_register_modal();">
                                                    <span class="alert-icon-wrap"><i class="zmdi zmdi-file-plus"></i></span> Fill in member detail
                                                </label>
                                            </td>

                                    </tr>
                                @endfor 
                                
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
        <span class="wizard-icon-wrap"><i class="ion ion-md-checkmark-circle-outline"></i></span>
        <span class="wizard-head-text-wrap">
            <span class="step-head">Summary</span>
        </span> 
    </h3>
    <section>
        <h3 class="display-4 mb-40">Application Summary</h3>
        <div class="row">
            <div class="col-xl-8 mb-20">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th class="w-70" scope="row">Sub Total</th>
                                    <th class="w-30" scope="row" id="summary_sub_total">{{$model['amount']}}</th>
                                </tr> 
                                <tr>
                                    <td class="w-70">Tax</td>
                                    <td class="w-30">-</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th class="text-dark text-uppercase" scope="row">Paid</th>
                                    <th class="text-dark font-18" scope="row" id="summary_total">{{$model['amount']}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- <textarea class="form-control mt-35" rows="2" placeholder="Any suggestions? We will pass it on.."></textarea> -->
            </div>
            <div class="col-xl-4 mb-20">
                <div class="alert alert-success mb-20" role="alert">
                    Click Submit button to submit the application.
                </div>
                <p class="mb-10">Please double check your application , confirmation email will be sent after verification , please proceed to payment after this.</p>
                <div class="custom-control custom-checkbox checkbox-success">
                            <input class="custom-control-input" id="same-card" type="checkbox" name="is_agreed_to_tac" id="is_agreed_to_tac" onclick="check_is_allow_to_pay(this);">
                            <label disabled="true" class="custom-control-label" for="same-card">You are agreed with the Term and Conditions.</label>
                </div>
                <button id="btn_payment" name="btn_payment" class="btn btn-primary btn-block mb-10" type="submit" disabled="true">Submit</button>

            </div>
        </div>
    </section>
    {!! Form::close() !!}
</div>

@endsection
@section('script')

var productInfoByLeafProductIdUrl = "{{action('ProductsController@getInfoByLeafProductId')}}";

function init_membership_info_by_leaf_product_id(me, type) {
        $.get(productInfoByLeafProductIdUrl, {product_id:$(me).val()}, function(fdata){     
           product = fdata.data;
           leaf_product = product.leaf_product_model;
           console.log(product);
           //$('#lbl_product_name').html(product.description);
           $('#lbl_product_amount').html(product.amount);
           
           $('#summary_total_amount').html(product.amount);
           $('#btn_payment').html('Pay '+product.amount);

           $('#summary_sub_total').html(product.amount);
           $('#summary_total').html(product.amount);

           $('#member_payable_amount').html(product.amount);
           $('#member_sub_total').html(product.amount);
           
           $('#').val(leaf_product.fee_type_user_per_unit);
           $('#').val(leaf_product.fee_type_user_min_age);
           $('#').val(leaf_product.fee_type_user_max_age);
           

        },"json");
}

function checkc bn_member_table(table_id,slot){

}
 
@endsection