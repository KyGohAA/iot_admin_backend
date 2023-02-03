@extends('_version_02.commons.layouts.admin')
@section('content')
<!-- 16:9 aspect ratio -->
<div class="embed-responsive embed-responsive-16by9">
  <iframe class="embed-responsive-item" src="{{$url}}"></iframe>
</div>
@stop
@section('script')
@stop