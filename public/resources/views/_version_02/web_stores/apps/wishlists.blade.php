@extends('web_stores.layouts.app')
@section('content')
<div class="card card-outline">
  <div class="card-content card-content-padding">
  <div class="list media-list">
    <ul>
      @if(isset($model))
        @foreach($model as $row)
          <li class="row">
            <a href="{{action('AppsWebStoresController@getProduct', ['product_id'=>$row['id_vendor_product'],'secret_token'=>$leaf_acc_api->get_secret_token($row['vendor_product_owner'])])}}" class="item-content external">
              <div class="item-media col-25"><img src="{{$leaf_acc_api->get_image_src($leaf_acc_api->get_default_image($row['vendor_product_photos']))}}" width="80"/></div>
              <div class="item-inner col-65">
                <div class="item-title-row">
                  <div class="item-title">{{$row['vendor_product_name']}}</div>
                </div>
                <div class="item-subtitle"><small>{{App\Language::trans('Store').' : '.$leaf_acc_api->get_company_name($row['vendor_product_owner'])}}</small></div>
                <div class="item-subtitle text-danger"><small>{{$leaf_acc_api->get_cheaper_price($row, $leaf_acc_api->get_company_currency_code($row['vendor_product_owner']))}}</small></div>
              </div>
            </a>
              <div class="item-inner col-10">
                <a onclick="func_remove_wishlist('{{$row['id_vendor_product']}}', this)" href="javascript:void(0)">
                  <i class="f7-icons text-danger">trash</i>
                </a>
              </div>
          </li>
        @endforeach
      @endif
    </ul>
  </div>
  </div>
</div>
@endsection
@section('script')
  function func_remove_wishlist(product, me) {
    var result = remove_from_wishlist(product);
    $(me).closest("li").remove();
    app.dialog.alert("{{App\Language::trans('Product was removed to wishlist.')}}", "{{App\Language::trans('Wishlist Removed')}}");
  }

@endsection