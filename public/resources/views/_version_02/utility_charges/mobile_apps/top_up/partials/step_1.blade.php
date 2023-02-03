<!-- CONTENT -->
<!--  class="shipping-checkout-page" -->
<div id="page-content fullscreen" >
  <div class="cart-page">
    <!-- <div class="container"> -->
      <div>
      <div class="row">
        <div class="col s12">    
          <br>
          <div class="shipping-info-wrap ck-box fullscreen  flex-ppal-setup " style="background-image: radial-gradient( circle 1224px at 10.6% 8.8%,  rgba(255,255,255,1) 0%, rgba(153,202,251,1) 100.2% );margin:2% 2% 2%/*30%*/ 2%;">
            <div class="row"> 
              <div class="input-field col s12 m12 l12 ">
                <div class="payment-method-text">
                 <h1 class='text-center' style="padding-top:10%"> <i class="fab fa-wpforms"></i> Top Up </h1>
                </div>
              </div>
            </div>
            <div class="row" style="padding-top:5%">
              <div class="col s12">
                <div class="new-shipping-address">Please check before submit:</div>
              </div>
            </div>

             <div class="row" >
              <div class="input-field col s12 m12 l12 ">
                 {!! Form::number('top_up_amount_txt', number_format(($model['amount'] >= 1 ? $model['amount'] : 1),2,'.',''), ['min'=>2,'max'=>200,'step'=>'0.01', 'id'=>'top_up_amount_txt' ,'class'=>'form-control','required','onkeyup'=>"checkMaxNumInputAndDisableTarget('top_up_amount_txt','btn_pay_now',99999);"]) !!}
                <label for="document_no"><!-- Top Up  -->Amount</label>
              </div> 
            </div>

        <!-- <div class="row">
          <div class="input-field col s6 m12 l12 ">
            {!! Form::text('document_date', null, ['class'=>'validate','required', 'readonly']) !!}
            <label for="account_no">Document Date</label>
          </div>
          <div class="input-field col s6 m12 l12 ">
            {!! Form::text('transaction_date', null, ['class'=>'validate','required', 'readonly']) !!}
            <label for="account_no">Transaction Date</label>
          </div>
        </div> -->

        <div class="row">
          <div class="col s12 m12 l12 ">
            <label for="zip">Description</label>
             {!! Form::text('description', 'Utility Fee Top Up', ['class'=>'validate','required','', 'readonly']) !!}
          </div>
        </div>


        <!-- <div class="row">
          <div class="col s6 m12 l6 ">
            <label for="zip">Payment Method</label>
              {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control']) !!}
          </div>
          <div class="input-field col s6 m12 l6 ">
            {!! Form::text('amount', '0.00', ['class'=>'validate','required']) !!}
            {!! Form::number('top_up_amount_txt', number_format(($model['amount'] >= 1 ? $model['amount'] : 1),2,'.',''), ['min'=>1,'max'=>200,'step'=>'0.01', 'id'=>'top_up_amount_txt' ,'class'=>'form-control','required','onkeyup'=>"checkMaxNumInputAndDisableTarget('top_up_amount_txt','btn_pay_now',99999);"]) !!}
            <label for="account_no">Amount</label>
          </div>
        </div> -->

          <div class="row" style="padding-top:25%;">
            <div class="input-field col s12 m12 l12 center">
              <button href="{{action('AppAccountingDashboardsController@getPayment')}}" id='btn_pay_now' name='btn_pay_now'  type="submit" class="nav-link-attach btn theme-btn-rounded">Submit</button>
              <input type="hidden" id="leaf_group_id" name="leaf_group_id" value="{{App\Company::get_group_id()}}">
              <input type="hidden" id="leaf_room_id" name="leaf_room_id" value="{{$user_profile['leaf_room_id']}}">
              <input type="hidden" id="user" name="user" value="{{$user_profile_string}}">
 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END CONTENT -->

