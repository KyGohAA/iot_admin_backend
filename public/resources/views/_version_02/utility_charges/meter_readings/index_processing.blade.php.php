@extends('commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')

<div class="box">
    <div class="box-header with-border">
        <h4 class="page-header">{{App\Language::trans('Information')}}</h4>
        <div class="row">
            <div class="col-md-12">
                <div>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong>{{App\Language::trans('Information')}} </strong>
                        <br>{{App\Language::trans($message)}}
                        <br>
                    </p>
                </div>
            </div>
        </div>   
    </div>

    <div class="box-footer">
    </div>
</div>
<!-- /.box -->


@endsection
@section('script')
@endsection


