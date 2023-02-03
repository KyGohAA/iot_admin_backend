<div class="hk-pg-header">
    <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="server"></i></span></span>{{$page_variables['page_title']}}</h4>
    @if(!isset($is_model_page))
	    <div class="box-tools pull-right">
			<div class="button-list mb-15">
				@if(isset($advance_search_status))
					<a class="btn btn-primary" data-toggle="collapse" href="#advance_search" role="button" aria-expanded="false" aria-controls="advance_search"><i class="fa fa-glass"></i> {{App\Language::trans('Advance Search')}}
					</a>
				@endif

				<a href="{{action($page_variables['new_file_link'])}}" class="btn btn-info">
					<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
				</a>
			</div>       
	     </div>
	@endif
</div>