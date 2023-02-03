<div class="hk-pg-header">
    <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="server"></i></span></span>{{$page_variables['page_title']}}</h4>
    @if(isset($is_model_page))
	    <div class="box-tools pull-right">
			<div class="button-list">
				@if(isset($advance_search_status))
					@if($advance_search_status == true)
						<a class="btn btn-primary loading-label" data-toggle="collapse" href="#advance_search" role="button" aria-expanded="false" aria-controls="advance_search"><i class="fa fa-glass"></i> {{App\Language::trans('Advance Search')}}
						</a>
					@endif
				@endif

				@if(isset($page_variables['new_file_link']))
					@if($page_variables['new_file_link'] != '')
						<a href="{{action($page_variables['new_file_link'])}}" class="btn btn-info loading-label">
							<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
						</a>
					@endif
				@endif	
			</div>       
	     </div>
	@endif
</div>