@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')    
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">{{App\Language::trans('No Active Product')}}</h5>
                    <div class="card-action-wrap">
                        <a class="inline-block card-close" href="#" data-effect="fadeOut">
                            <i class="ion ion-md-close"></i>
                        </a>
                    </div>
                </div>
                <p class="card-text">- {{App\Language::trans("Make sure at least one product are in active status for the operation")}}</p>
                 <a  class="btn btn-success" href="{{action('ProductsController@getIndex')}}" class="btn btn-info pull-left">{{App\Language::trans("Go to Product Setting")}}</a>   
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

var customerInfoUrl = "{{action('CustomersController@getInfo')}}";
    function init_customer_info(me) {
        $.get(customerInfoUrl, {customer_id:$(me).val()}, function(fdata){
            for (var key in fdata.data) {
                console.log("key " + key + " has value " + fdata.data[key]);
            }
        },"json");
    }
    
    init_select2($("select[name=customer_id]"));
    init_select2($("select[name=sort_by]"));
@endsection


