@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')




<section id="mobile_app_testing_section" class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Import Data')}}</h5><hr>
    
        <!-- @if(isset($url))
            <h5 class="hk-sec-title">{{App\Language::trans('Mock UI')}}</h5><hr>
            <div style=" height: 600px;width: 50%;">
                <div class="embed-responsive embed-responsive-4by3">
                  <iframe frameborder="1" class="embed-responsive-item" src="{{$url}}"></iframe>
                </div>
            </div>
        @endif

        @if(isset($url))
            {{$url}}
        @endif -->

        {!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
                <h6 class="hk-sec-title"><small>{{App\Language::trans('Select data type want to import. ')}}</small></h6>
                <div class="form-group{{ $errors->has('tester_email') ? ' has-error' : '' }} row">
                    {!! Form::label('tester_email', App\Language::trans('Data Type'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('import_data', App\Setting::data_type_combobox(), null, ['class'=>'form-control','required']) !!}
                         <smaller><label for="company_logo_del">{{App\Language::trans('Import data might take some time, please be patient')}}</label></smaller>
                        {!!$errors->first('tester_email', '<label for="tester_email" class="help-block error">:message</label>')!!}
                    </div>                 
                </div>

               
                <hr>
                <button type="submit" class="btn btn-success mr-10" href=>{{App\Language::trans('Import')}}<i class="fa fa-arrow-right fa-fw"></i></button>

        {!!Form::close()!!}

</section>

@endsection
@section('script')
@endsection