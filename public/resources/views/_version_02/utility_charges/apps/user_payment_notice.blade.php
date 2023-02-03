@extends('utility_charges.layouts.app') 
@section('content')
<div class="receipt-page text-center">
        <i class="fa fa-sad-tear fa-fw fa-5x text-primary" aria-hidden="true"></i>
        @if($status_level == 'up')
            <h1>{{App\Language::trans('Your payment is Successful')}}</h1>
            <p>{{App\Language::trans('You will receive your e-receipt in your email shortly.')}}</p>
            <p>{{App\Language::trans('If you do not receive your e-receipt within next 30 minutes. please contact our customer service hotline at 03-5566 9191. or email us for futher assistance.')}}</p>
        @else
            <h1>{{App\Language::trans('Your payment is Failed')}}</h1>
            <p>{{App\Language::trans('Please contact our customer service hotline at 03-5566 9191. or email us for futher assistance.')}}</p>
        @endif
        <!-- <span class="help-block">{{App\Language::trans('Order id')}}: 12938712873612387</span> -->
        <span class="help-block">Email: smc@sunway.com.my</span>
        <a class="btn btn-default" href="{{action('AppsUtilityChargesController@getDashboard')}}">{{App\Language::trans('Continue with us')}}</a>
</div>
@stop
@section('script')
@stop


