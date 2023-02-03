@extends('utility_charges.layouts.app') 
@section('content')

   <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                @include('utility_charges.apps.user_statistic')
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                @include('utility_charges.apps.user_payment_history')
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                 @include('utility_charges.apps.user_top_up')
              </div>
              <!-- /.tab-pane -->

              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_4">
                 @include('utility_charges.apps.user_manual')
              </div>
              <!-- /.tab-pane -->

	</div>
	<!-- /.tab-content -->


@stop 
@section('script')

@stop