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
                  <i class="fab fa-wpforms"></i> Expenses Summary
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12">
                <div class="new-shipping-address">Please check before submit:</div>
              </div>
            </div>


            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                   <div class="new-shipping-address">Transaction Slip</div>
                {!!Form::file("receipt_filename", array("id"=>"receipt_filename","class"=>"validate",'required'))!!}  
              </div>
            </div>
            <hr>

            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                 {!! Form::text('document_no', $model['document_no'] , ['class'=>'validate','required']) !!}
                <label for="document_no">Document Number</label>
              </div>
            </div>


        <div class="row">
          <div class="input-field col s6 m12 l12 ">
            {!! Form::text('document_date', null, ['class'=>'validate','required']) !!}
            <label for="account_no">Document Date</label>
          </div>
          <div class="input-field col s6 m12 l12 ">
            {!! Form::text('transaction_date', null, ['class'=>'validate','required']) !!}
            <label for="account_no">Transaction Date</label>
          </div>
        </div>

        <div class="row">
          <div class="col s12 m12 l12 ">
            <label for="zip">Description</label>
              {!! Form::select('description', App\Product::self_combobox($user['id']), null, ['id'=> 'editable-select','class'=>'form-control']) !!}
          </div>
        </div>


        <div class="row">
          <div class="col s6 m12 l6 ">
            <label for="zip">Payment Method</label>
              {!! Form::select('payment_method', App\Setting::payment_method(), null, ['class'=>'form-control']) !!}
          </div>
          <div class="input-field col s6 m12 l6 ">
            {!! Form::text('amount', '0.00', ['class'=>'validate','required']) !!}
            <label for="account_no">Amount</label>
          </div>
        </div>


            

            <div class="row">
              <div class="input-field col s12 m12 l12 ">
                {!! Form::textarea('remark', '', ['id' => 'remark', 'class'=>'materialize-textarea validate']) !!}
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

