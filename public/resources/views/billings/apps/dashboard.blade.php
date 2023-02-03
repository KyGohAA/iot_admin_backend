@extends('billings.layouts.app') 
@section('content')

 @if($membership_detail['is_payable_member'])
   <div class="tab-content">
        <div class="tab-pane active" id="tab_1">    
              @include('billings.apps.user_landing_page')
        </div>

        <div class="tab-pane" id="tab_2"> 
            @if(isset($product_listing))
               @include('billings.apps.user_statistic')
            @else
              @include('billings.apps.user_membership_detail')         
              @include('billings.apps.user_apply_membership')
              @include('billings.apps.user_top_up')
              
            @endif
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_3">
          @include('billings.apps.user_payment_history')
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_4">
           @include('billings.apps.user_manual')
        </div>
        <!-- /.tab-pane -->
</div>
            <!-- /.tab-content -->

  @else
      @include('billings.apps.user_info')
  @endif


@stop 
@section('script')

@stop