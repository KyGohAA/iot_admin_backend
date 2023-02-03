@extends('umrah.layouts.app')
@section('content')
<style type="text/css">
</style>
<ul data-role="listview">
	@foreach($to_do_lists as $to_do_list)
		<li>
			<a href="{{action('AppsController@getToDoListView', [$to_do_list->id])}}">
				{{$to_do_list->date}}<br>
				{{$to_do_list->name}}
				{{-- <span class="ui-li-count">{{$to_do_list->is_checked ? App\Language::trans('Done'):App\Language::trans('Pending')}}</span> --}}
				<span class="ui-li-count">{!!$to_do_list->is_checked ? '<div class="status done"></div>':'<div class="status pending"></div>'!!}</span>
			</a>
		</li>
	@endforeach
</ul>
@endsection
@section('script')
@endsection