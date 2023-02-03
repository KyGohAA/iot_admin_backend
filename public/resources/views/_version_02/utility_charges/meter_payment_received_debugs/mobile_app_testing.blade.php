@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')




<section id="mobile_app_testing_section" class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Testing Barcode Generator')}}</h5><hr>
    
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
                <h6 class="hk-sec-title"><small>{{App\Language::trans('Enter email(s) of the account that going to test , scan barcode to access testing environment. ')}}</small></h6>
                <div class="form-group{{ $errors->has('tester_email') ? ' has-error' : '' }} row">
                    {!! Form::label('tester_email', App\Language::trans('Email'), ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('tester_email', '', ['id'=>'tester_email', 'rows'=>'10' , 'cols'=>'80','class'=>'form-control' , 'placeholder'=>'Enter Tester Email']) !!}
                         <smaller><label for="company_logo_del">{{App\Language::trans('If more than one email , separate by comma "," . e.g. abc@gmail.com , xyz.gmail.com')}}</label></smaller>
                        {!!$errors->first('tester_email', '<label for="tester_email" class="help-block error">:message</label>')!!}
                    </div>                 
                </div>

                
                @if(isset($tester_barcodes))
                    @if(count($tester_barcodes) > 0)
                             <h6 class="hk-sec-title">{{App\Language::trans('Scan The Barcode To Access Testing Site')}}</h6><hr>
                             <div class="row">
                                <div class="col-sm">
                                    <div class="form-row">
                                        <!-- <div class="col-md-12 mb-15"> -->
                                           @foreach($tester_barcodes as $barcode)
                                               <div class="col-md-4">
                                                  <img  width="300" height ="300" class="img-fluid img-thumbnail img-responsive" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(1000)->generate($barcode['qr_value'])) !!} "><br>
                                                  <label for="company_logo_del">{{App\Language::trans('Account :').' '.$barcode['email']}}</label>
                                               </div>
                                           @endforeach
                                       <!--  </div> -->
                                    </div>
                                </div>
                            </div>  
                    @endif
                @endif
                <hr>
                <button type="submit" class="btn btn-success mr-10" href=>{{App\Language::trans('Generate')}}<i class="fa fa-arrow-right fa-fw"></i></button>

        {!!Form::close()!!}

</section>

@endsection
@section('script')
@endsection