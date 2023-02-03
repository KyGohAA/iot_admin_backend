@extends('umrah.layouts.app')
@section('content')
	<h3 class="text-center">{{$model->name}}</h3>
	<hr>
@if($model->date)
	<p class="date">{{App\Language::trans('Date : ').$model->date}}</p>
	<p class="time">{{App\Language::trans('Time : ').$model->time}}</p>
	<hr>
@endif
	<p class="title">{{App\Language::trans('Description : ')}}</p>
	<p class="description">{!!nl2br($model->description)!!}</p>
@if(!$model->is_checked)
	<center>
		<a onclick="return confirm('{{App\Language::trans('Are you sure?')}}')" href="{{action('AppsController@getToDoChecked', [$model->id])}}">
			{{App\Language::trans('Has completed')}}
		</a>
	</center>
@endif
@endsection
@section('script')
@endsection