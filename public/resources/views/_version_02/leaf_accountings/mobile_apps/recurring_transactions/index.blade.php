@extends('_version_02.leaf_accountings.mobile_apps.layouts.main')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
<!-- CONTENT -->
<div id="page-content" class="shipping-checkout-page">
  <div class="cart-page">
    <div class="container">
      <div class="row">
        <div class="col s12">    
          <br>
          <div class="shipping-info-wrap ck-box">
            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                <div class="payment-method-text">
                  <i class="fab fa-wpforms"></i> Recurring Transaction
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12">
                <div class="new-shipping-address">Account is setup to record transaction flow:</div>
              </div>
            </div>


            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                 {!! Form::text('description', $model['document_no'] , ['class'=>'validate','required']) !!}
                <label for="document_no">Description</label>
              </div>
            </div>

            <div class="row">
              <div class="col s12 m12 l12 ">
                <label for="zip">Type</label>
                  {!! Form::select('payment_method', App\IETransaction::transaction_flow_combobox(), null, ['id'=> 'editable-select','class'=>'form-control']) !!}
              </div>
            </div>

            <div class="row" style="margin-bottom:5px;">
              <div class="col s12 m12 l12">
                <label for="zip">Recurring On</label>
                  {!! Form::select('recurring_on', App\Setting::select_days_combobox(), null, ['class'=>'form-control' , 'multiple' => 'true']) !!}
              </div>
            </div>
        
            <div class="row">
              <div class="col s12 m12 l12 ">
                <label for="zip">User Account</label>
                  {!! Form::select('payment_method', App\UserAccount::combobox(), null, ['class'=>'form-control']) !!}
              </div>
            </div>


          <div class="row">
            <div class="input-field col s12 m12 l12 center">
              <button  type="submit" class="nav-link-attach btn theme-btn-rounded">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END CONTENT -->


{!! Form::close() !!}
<br><br><br>
@endsection
@section('script')
@endsection