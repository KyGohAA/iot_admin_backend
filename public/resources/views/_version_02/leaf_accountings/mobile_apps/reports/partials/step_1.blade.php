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
                  <i class="fab fa-wpforms"></i> Generate Report
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col s12">
                <div class="new-shipping-address">Please check before submit:</div>
              </div>
            </div>

              <div class="row">
               <div class="col-md-12">
                 <div class="form-group{{ $errors->has('daterange') ? ' has-error' : '' }}">
                  {!! Form::label('daterange', App\Language::trans('Date Range'), ['class'=>'control-label col-md-4']) !!}
                  <div class="col-md-12">
                    <input class="form-control" type="text" name="daterange" id="daterange"/>
                      {!!$errors->first('daterange', '<label for="daterange" class="help-block error">:message</label>')!!}
                  </div>
                </div>
              </div>
            </div>

          <div class="row">
            <div class="input-field col s12 m12 l12 center">
              <button  type="submit" class="nav-link-attach btn theme-btn-rounded">Submit</button>
            </div>
          </div>

              <div style="overflow-x:auto;">
            <embed src="{{asset($file_name)}}" width="800px" height="2100px" />
          </div>
          
        </div>
      </div>
    </div>
  </div>


</div>


<!-- END CONTENT -->

