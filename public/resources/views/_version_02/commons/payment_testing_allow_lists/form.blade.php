@extends('_version_02.commons.layouts.admin')
@section('content')
{!!Form::model($model, array("url"=>null,"method"=>"post","class"=>"form-horizontal","files"=>true))!!}
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Tester Information')}}</h5><hr>
     

     <div class="row">
      <div class="col-md-6">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
          {!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
          <div class="col-md-12">
             <div class="row">  
              <div class="col-md-3">
                  <div class="custom-control custom-radio">
                      <input type="radio" id="status_on" name="status" checked class="custom-control-input">
                      <label class="custom-control-label" for="status_on">{{App\ExtendModel::status_true_word()}}</label>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="custom-control custom-radio">
                      <input type="radio" id="status_off" name="status"  class="custom-control-input">
                      <label class="custom-control-label" for="status_off">{{App\ExtendModel::status_false_word()}}</label>
                  </div>
              </div>
             </div>
             {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
          </div>
        </div>
      </div>
    </div>


        <div class="form-group {!!$errors->first('code') ? 'has-error' : ''!!}">
          <label for="code" class="control-label col-md-2">{{App\Language::trans('Code')}}</label>
          <div class="col-md-10">
            {!!Form::text("code", isset($number) ? $number:null, array("id"=>"code","class"=>"form-control","maxlength"=>"100"))!!}
            {!!$errors->first('code', '<span for="code" class="help-block error">:message</span>')!!}
          </div>
        </div>
        <!-- .form-group -->

        <div class="form-group {!!$errors->first('name') ? 'has-error' : ''!!}">
          <label for="name" class="control-label col-md-2">{{App\Language::trans('Name')}}</label>
          <div class="col-md-10">
            {!!Form::text("name", null, array("id"=>"name","class"=>"form-control","maxlength"=>"100"))!!}
            {!!$errors->first('name', '<span for="name" class="help-block error">:message</span>')!!}
          </div>
        </div>
        <!-- .form-group -->

     <!--  <div class="row">
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
    </div> -->
        <!-- .form-group -->

        <div class="form-group {!!$errors->first('name') ? 'has-error' : ''!!}">
          <label for="name" class="control-label col-md-2">{{App\Language::trans('Testing Period')}}</label>
          <div class="col-md-10">
                <input class="form-control" type="text" name="daterange"/>
            {!!$errors->first('daterange', '<span for="name" class="help-block error">:message</span>')!!}
          </div>
        </div>
        <!-- .form-group -->

    <!-- Plugin: Dual Select List -->

  
      
    
          <div class="form-group {!!$errors->first('code') ? 'has-error' : ''!!}">
            <label for="code" class="control-label col-md-2">{{App\Language::trans('Tester List')}}</label>
            <div class="col-md-10">
              {!!Form::select("tester_id[]", App\Customer::combobox_from_leaf(), strlen($model->tester_id) >  1 ? json_decode($model->tester_id,true):null, array("style"=>"width: 100%;", "multiple class"=>"chosen-select","class"=>"form-control select2","id"=>"tester_id","multiple"=>true))!!}
            </div>
          </div>
     

 
</section>
@include('_version_02.commons.layouts.partials._form_floaring_footer_standard')
{!!Form::close()!!}
@stop
@section('script')
init_single_select2($("select"));
init_dual_list($("select[id=tester_id]"));
@stop