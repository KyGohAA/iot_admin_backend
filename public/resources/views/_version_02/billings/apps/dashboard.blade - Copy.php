@extends('billings.layouts.app') 
@section('content')

  @if($membership_detail['is_payable_member'])
   <div class="tab-content">
              <div class="tab-pane active" id="tab_1">      
                @include('_version_02.billings.apps.user_top_up')       
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                @include('_version_02.billings.apps.user_payment_history')
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                 @include('_version_02.billings.apps.user_manual')
              </div>
              <!-- /.tab-pane -->
   </div>
            <!-- /.tab-content -->
  @else
     @include('_version_02.billings.apps.user_info')
  @endif

@stop 
@section('script')

@stop