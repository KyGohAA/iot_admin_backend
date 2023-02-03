@extends('web_stores.layouts.app')
@section('content')
<div class="card">
  <div class="card-header">{{App\Language::trans('Shipping Policy')}}</div>
  <div class="card-content card-content-padding">
    {!!nl2br($model)!!}
  </div>
</div>
@endsection
@section('script')
@endsection