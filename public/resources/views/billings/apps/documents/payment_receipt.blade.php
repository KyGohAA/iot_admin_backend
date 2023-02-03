
   <div class="row">
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> AdminLTE, Inc.
            <small class="pull-right">{{App\Language::trans('Date')}}: 2/10/2014</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          {{App\Language::trans('From')}}
          <address>
             <strong>{{$payment_received_model['customer']['name']}}</strong><br>
            {{$payment_received_model['customer']['billing_address1']}}<br>
            {{$payment_received_model['customer']['billing_address2']}}<br>
            {{App\Language::trans('Phone')}}: {{$payment_received_model['customer']['phone_no_1']}}<br>
            {{App\Language::trans('Email')}}: {{$payment_received_model['customer']['email']}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
        {{App\Language::trans('To')}}
          <address>
            <strong>{{$payment_received_model['customer']['name']}}</strong><br>
            {{$payment_received_model['customer']['billing_address1']}}<br>
            {{$payment_received_model['customer']['billing_address2']}}<br>
            {{App\Language::trans('Phone')}}: {{$payment_received_model['customer']['phone_no_1']}}<br>
            {{App\Language::trans('Email')}}: {{$payment_received_model['customer']['email']}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>{{App\Language::trans('Receipt')}} #007612</b><br>
          <br>
          <b>Order ID:</b> 4F3S8J<br>
          <b>Payment Due:</b> 2/22/2014<br>
          <b>Account:</b> 968-34567
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <br>
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
                <th>{{App\Language::trans('No')}}</th>
                <th>{{App\Language::trans('Product')}}</th>
                <th>{{App\Language::trans('Quantity')}}</th>
                <th>{{App\Language::trans('Unit Price')}}</th>
                <th>{{App\Language::trans('Subtotal')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($payment_received_model->items as $item)
                <tr>
                  <td>No</td>
                  <td>{{$item['description']}}</td>
                  <td>{{$item['quantity']}}</td>
                  <td>{{ number_format($item['received_amount'],2,'.','')}}</td>
                  <td>{{ number_format($item['received_amount'],2,'.','')}}</td>
                </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="col-sm-4 invoice-col">
          <b>Invoice #007612</b><br>
          <br>
          <b>Order ID:</b> 4F3S8J<br>
          <b>Payment Due:</b> 2/22/2014<br>
          <b>Account:</b> 968-34567
        </div>

          <div class="col-sm-4 invoice-col">
          <b>Invoice #007612</b><br>
          <br>
          <b>Order ID:</b> 4F3S8J<br>
          <b>Payment Due:</b> 2/22/2014<br>
          <b>Account:</b> 968-34567
        </div>

         <br><br><br>

    </section>
    <!-- /.content -->
   
</div>
@section('script')

@stop

