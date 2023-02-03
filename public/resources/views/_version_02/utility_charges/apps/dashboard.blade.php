@extends('_version_02.utility_charges.layouts.app') 
@section('content')

   

   <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                @include('_version_02.utility_charges.apps.new_test') 
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                @include('_version_02.utility_charges.apps.new_test_2')
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                @include('_version_02.utility_charges.apps.new_test_3')
              </div>
              <!-- /.tab-pane -->

              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_4">
                 @include('_version_02.utility_charges.apps.new_test_4')
              </div>
              <!-- /.tab-pane -->

	</div>
	<!-- /.tab-content -->


@stop 
@section('script')

@stop