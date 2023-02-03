@extends('utility_charges.layouts.app') 
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" style="background-color:#b3d9fc;">
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('Payment notification')}}</h3>
            </div>
			
            <!-- /.box-body -->
            <div class="box-footer">
                    <h5>{{App\Language::trans('Dear value user,')}}</h5>        
                    <br>
                    <p>{{App\Language::trans('Payment is going to live soon , Please make sure your app are up-to-date.')}}</p>
                <!-- <p>{{App\Language::trans('If you cannot make payment. please contact our customer service hotline at 03-5566 9191. or email us for futher assistance.')}}</p>  -->
                <!-- <span class="help-block">{{App\Language::trans('Order id')}}: 12938712873612387</span> -->
                    <br><br>
                    <a class="btn btn-default" href="{{action('AppsUtilityChargesController@getDashboard')}}">{{App\Language::trans('Continue with us')}}</a>
            </div>
            <!-- /.box-footer-->
        </div>
        <!--/.direct-chat -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@stop 
@section('script')

@stop