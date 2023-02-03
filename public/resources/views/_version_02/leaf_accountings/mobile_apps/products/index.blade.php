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
                  <i class="fab fa-wpforms"></i> Item Detail
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12">
                <div class="new-shipping-address">:</div>
              </div>
            </div>


        <div class="row">
          <div class="col s12 m12 l12 ">
            <label for="zip">Category</label>
              {!! Form::select('product_category_id', App\ProductCategory::combobox(), null, ['class'=>'form-control']) !!}
          </div>
        </div>



            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                 {!! Form::text('code', $model['code'] , ['class'=>'validate']) !!}
                <label for="name">Code</label>
              </div>
            </div>


            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                 {!! Form::text('name', $model['name'] , ['class'=>'validate','required']) !!}
                <label for="name">Name</label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                {!! Form::text('remark', '', ['id' => 'remark', 'class'=>'materialize-textarea validate']) !!}
                <label for="remark">Remark</label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                 {!! Form::number('standard_cost', $model['standard_cost'] , ['class'=>'validate','required']) !!}
                <label for="name">Amount</label>
              </div>
            </div>

            <div class="row">
              <div class="col s6 m12 l6 ">
                <label for="zip">Tax</label>
                  {!! Form::select('sale_tax_id', App\Tax::combobox(), null, ['class'=>'form-control']) !!}
              </div>
              <div class="input-field col s6 m12 l6 ">
                {!! Form::text('selling_price', '0.00', ['class'=>'validate','readonly'=>'true']) !!}
                <label for="account_no">Tax Percent</label>
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