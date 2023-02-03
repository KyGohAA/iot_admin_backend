@extends('utility_charges.layouts.app') 
@section('content')

   <div class="row">
    <div class="col-md-12">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="box box-info box-solid" style="background-color:#b3d9fc;">
            <div class="box-header with-border" style="background-color:#59abf7">
                <h3 class="box-title">{{App\Language::trans('You have no room register under your account')}}</h3>

                <!-- <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div> -->
            </div>
			
            <!-- /.box-body -->
            <div class="box-footer">
                <b>{{App\Language::trans('If you are tenant here')}}?</b><br>
                    <b>{{App\Language::trans('If you are')}}</b><a> <font color="blue">{{App\Language::trans('tenant here')}}</font></a>, {{App\Language::trans('please contact management office for room registration')}}.<br>
                    <b>{{App\Language::trans('If you are')}}</b><a> <font color="blue">{{App\Language::trans('are not tenant here')}}</font></a> , {{App\Language::trans('this module is not open for non-tenant')}}.<br>
                    <br>
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