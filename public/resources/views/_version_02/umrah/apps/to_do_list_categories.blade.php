@extends('umrah.layouts.app')
@section('content')
<ul data-role="listview">
	@foreach($to_do_list_categories as $to_do_list_category)
		<li><a href="{{action('AppsController@getToDoLists', ['category_id'=>$to_do_list_category->id])}}">{{$to_do_list_category->name}}<span class="ui-li-count">{{$to_do_list_category->total_available()}}</span></a></li>
	@endforeach
</ul>
@endsection
@section('script')
@endsection