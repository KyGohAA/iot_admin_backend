@extends('commons.layouts.admin')
@section('content')
{!!Form::model($model, array("url"=>null,"method"=>"post","class"=>"form-horizontal","files"=>true))!!}
@include('commons.layouts.partials._alert')
<!-- Form -->
  <div class="panel panel-primary">
    <div class="panel-heading">
      <span class="panel-title">
        {{App\Language::trans('Tester Information')}}</span>
    </div>
    <!-- end .form-header section -->

      <div class="panel-body p25">

        <div class="form-group {!!$errors->first('status') ? 'has-error' : ''!!}">
          <label for="status" class="control-label col-sm-2">{{App\Language::trans('Status')}}</label>
          <div class="col-sm-10">
            <label class="radio-inline">
              {!!Form::radio("status",1 ,true,  ['id'=>'status'])!!}{{App\Language::trans('Enabled')}}
            </label>
            <label class="radio-inline">
              {!!Form::radio("status",0 ,false ,  ['id'=>'status'])!!}{{App\Language::trans('Disabled')}}
            </label>
              {!!$errors->first('status', '<div for="status" class="help-block TMargin10">:message</div>')!!}
          </div>
        </div>
        <!-- .form-group -->

        <div class="form-group {!!$errors->first('code') ? 'has-error' : ''!!}">
          <label for="code" class="control-label col-sm-2">{{App\Language::trans('Code')}}</label>
          <div class="col-sm-10">
            {!!Form::text("code", isset($number) ? $number:null, array("id"=>"code","class"=>"form-control","maxlength"=>"100"))!!}
            {!!$errors->first('code', '<span for="code" class="help-block error">:message</span>')!!}
          </div>
        </div>
        <!-- .form-group -->

        <div class="form-group {!!$errors->first('name') ? 'has-error' : ''!!}">
          <label for="name" class="control-label col-sm-2">{{App\Language::trans('Name')}}</label>
          <div class="col-sm-10">
            {!!Form::text("name", null, array("id"=>"name","class"=>"form-control","maxlength"=>"100"))!!}
            {!!$errors->first('name', '<span for="name" class="help-block error">:message</span>')!!}
          </div>
        </div>
        <!-- .form-group -->

      <div class="row">
			<div class="input-daterange">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('date_started') ? ' has-error' : '' }}">
						{!! Form::label('date_started', App\Language::trans('Date Started'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('date_started', null, ['class'=>'form-control']) !!}
	            {!!$errors->first('date_started', '<label for="date_started" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('date_ended') ? ' has-error' : '' }}">
						{!! Form::label('date_ended', App\Language::trans('Date Ended'), ['class'=>'control-label col-md-4']) !!}
						<div class="col-md-8">
							{!! Form::text('date_ended', null, ['class'=>'form-control']) !!}
	            {!!$errors->first('date_ended', '<label for="date_ended" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			</div>
		</div>
        <!-- .form-group -->

    <!-- Plugin: Dual Select List -->
    <div class="panel panel-primary">
      <div class="panel-heading">
        <span class="panel-title">{{App\Language::trans('Tester listing')}}</span>
      </div>
      <div class="panel-body p25">
        <div class="form-group {!!$errors->first('code') ? 'has-error' : ''!!}">
          <label for="code" class="control-label col-sm-2">{{App\Language::trans('Testing List')}}</label>
          <div class="col-sm-10">
            {!!Form::select("tester_id[]", App\Customer::combobox_from_leaf(), strlen($model->tester_id) >  1 ? json_decode($model->tester_id,true):null, array("style"=>"width: 100%;", "multiple class"=>"chosen-select","class"=>"form-control select2","id"=>"tester_id","multiple"=>true))!!}
          </div>
        </div>
         
    
       </div>
    </div>  



      </div>
      <!-- end .form-body section -->
      <div class="panel-footer">
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">{{App\Language::trans('Submit')}}</button>
            <a class="btn btn-danger" href="javascript:history.go(-1)">{{App\Language::trans('Cancel')}}</a>
          </div>
        </div>
      </div>
      <!-- end .form-footer section -->
  </div>
<!-- end: .admin-form -->
{!!Form::close()!!}
@stop
@section('script')
init_single_select2($("select"));
init_dual_list($("select[id=tester_id]"));
@stop