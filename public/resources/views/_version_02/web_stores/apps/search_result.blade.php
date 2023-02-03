@extends('web_stores.layouts.app')
@section('content')
<div class="list media-list">
  <ul>
    @foreach($deals as $product)
      <li>
        <a href="{{action('AppsWebStoresController@getProduct', ['product_id'=>$product['id_vendor_product'],'secret_token'=>$product['company']['company_secret_token']])}}" class="item-link item-content external">
          <div class="item-media">
            @foreach($product['vendor_product_photos'] as $photo)
              @if($photo['vendor_product_photo_is_cover'])
                <img src="{{$leaf_acc_api->get_image_src($photo['vendor_product_photo_path'])}}" width="80"/>
              @endif
            @endforeach
          </div>
          <div class="item-inner">
            <div class="item-title-row">
              <div class="item-title">{{$product['vendor_product_name']}}</div>
            </div>
            <div class="item-subtitle">
              <small class="text-danger">
                {{$leaf_acc_api->get_cheaper_price($product)}}
              </small>
            </div>
            <div class="item-text">
              <small>
                {{App\Language::trans('Category : ').$product['vendor_product_category']['vendor_product_category_name']}}
              </small>
            </div>
          </div>
        </a>
      </li>
    @endforeach
  </ul>
</div>
@endsection
@section('script')
@endsection