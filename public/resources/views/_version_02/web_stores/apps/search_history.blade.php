@extends('web_stores.layouts.app')
@section('content')
<style type="text/css">
  .text-right {
    text-align: right;
  }
</style>
    <!-- search target list -->
    <div class="list searchbar-found">
      <ul>
        <li class="item-content">
          <div class="item-inner">
            <span class="item-title">
              {{App\Language::trans('Search Recently')}}
            </span>
            @if(isset($_COOKIE[App\LeafAccAPI::search_cookie_label]))
              <a href="#" class="item-after text-danger confirm-ok">
                {{App\Language::trans('Clear History')}}
              </a>
            @endif
          </div>
        </li>
        @if(isset($_COOKIE[App\LeafAccAPI::search_cookie_label]))
          @foreach(json_decode($_COOKIE[App\LeafAccAPI::search_cookie_label], true) as $row)
            <li class="item-content">
              <a class="item-inner external" href="{{action('AppsWebStoresController@getSearchResult', ['secret_token'=>$main_store['company_secret_token'],'store_id'=>$main_store['id_company'],'search'=>$row])}}">
                {{$row}}
              </a>
            </li>        
          @endforeach
        @endif
      </ul>
    </div>
@endsection
@section('script')
$$('.confirm-ok').on('click', function () {
  app.dialog.confirm("{{App\Language::trans('Are you sure clear all histories?')}}", function () {
    var url = "{!!action('AppsWebStoresController@getClearSearchHistory', $request->input())!!}";
    window.location.href = url;
  });
});
@endsection