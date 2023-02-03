@extends('billings.layouts.admin')
@section('content')
@include('billings.layouts.partials._alert')

<div class="box">
    <div class="box-header with-border">
        <h4 class="page-header">{{App\Language::trans('Alert')}}</h4>
        <div class="row">
            <div class="col-md-12">
                <div>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong>{{App\Language::trans('No Active Product')}} </strong>
                        <br>{{App\Language::trans("Make sure at least one product are in active status for the operation")}}
                        <br>
                    </p>
                </div>
            </div>
        </div>   
    </div>

    <div class="box-footer">
        <!-- <label onclick="go_to_step_with_previous_show(get_current_step(),'backward');" class="btn btn-default">Back</label> -->
       <a href="{{action('ProductsController@getIndex')}}" class="btn btn-info pull-left">Go to Product Setting</a>
    </div>
</div>
<!-- /.box -->


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


