<div class="row">  <!-- Calendar -->
  <div class="box box-solid bg-green-gradient">
  <!-- /.box-body -->
    <?php $i=1; ?>                               
    @foreach($product_listing as $row)
     <a class="external" target="_blank" href="{{action('AppAccountingDashboardsController@getPaymentPageByProductId', ['product_id'=>$row['id'], 'session_token'=>$session_token])}}">
       <div class="box-footer text-black div_background_{{$i}}"  style="height :130px;">
         <div class="inner" style="height :50%;">
            <h4><strong>{{$row['name']}}</strong></h4>
            <p></p>
         </div>
      </div>
     </a> 
	  <?php $i++; ?>
    @endforeach
  
  
  </div>
  <!-- /.box -->
</div>
<!-- /.row -->

@section('script') 
@stop