@extends('web_stores.layouts.app')
@section('content')
<div class="login-screen-title">{{App\Language::trans('Web Store')}}</div>
{!!Form::open()!!}
  <div class="list">
    <ul>
      <li class="item-content item-input">
        <div class="item-inner">
          <div class="item-title item-label">{{App\Language::trans('Email')}}</div>
          <div class="item-input-wrap">
            {!!Form::hidden('redirect', old('redirect'))!!}
            {!!Form::hidden('secret_token', old('secret_token'))!!}
            {!!Form::hidden('store_id', old('store_id'))!!}
            {!!Form::email('user_email', null, ['id'=>'user_email','placeholder'=>App\Language::trans('Email address'),'required'])!!}
          </div>
        </div>
      </li>
      <li class="item-content item-input">
        <div class="item-inner">
          <div class="item-title item-label">{{App\Language::trans('Password')}}</div>
          <div class="item-input-wrap">
            {!!Form::password('user_password', ['id'=>'user_password','placeholder'=>App\Language::trans('Your Password'),'get_required_files()'])!!}
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="list">
    <ul>
      <li><a class="item-link list-button btn-submit" href="javascript:void(0)">{{App\Language::trans('Sign In')}}</a></li>
      <li><a class="item-link list-button external" href="https://cloud.leaf.com.my/web/forgot-password.php" onclick="window.open(this.href); return false;">{{App\Language::trans('Forgot Password')}}</a></li>
    </ul>
  </div>
{!!Form::close()!!}
@endsection
@section('script')
var field_not_fill = "{{App\Language::trans('All fields are required. Please fill before submit.')}}";

$(".btn-submit").on("click", function(){
  var status = true;
  $("input").each(function(){
    if($(this).attr("name") !== "redirect") {
      if($(this).val() == "" && status == true) {
        app.dialog.alert(field_not_fill, "{{App\Language::trans('Login Failed')}}");
        $(this).focus();
        status = false;
      }
    }
  });
  if(status) {
    $("form").submit();
  }
});
@endsection