@extends('_version_02.utility_charges.mobile_apps.layouts.main')
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
                  <i class="fab fa-wpforms"></i> Account Detail
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
                 {!! Form::text('name', $model['name'] , ['class'=>'validate','required']) !!}
                <label for="name">Account Name</label>
              </div>
            </div>


        <div class="row">
          <div class="col s6 m12 l6 ">
            <label for="zip">Bank</label>
              {!! Form::select('bank_id', App\MembershipModel\Bank::combobox(), null, ['class'=>'form-control']) !!}
          </div>
          <div class="input-field col s6 m12 l6 ">
            {!! Form::text('account_no', '0.00', ['class'=>'validate']) !!}
            <label for="account_no">Account No.</label>
          </div>
        </div>

            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                {!! Form::text('remark', '', ['id' => 'remark', 'class'=>'materialize-textarea validate']) !!}
                <label for="remark">Remark</label>
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