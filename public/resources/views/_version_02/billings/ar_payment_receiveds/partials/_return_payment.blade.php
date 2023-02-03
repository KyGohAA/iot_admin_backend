 <!-- /.col -->
<div class="col-md-12">
    <!-- .box -->
    <div id="return_payment_div" class="box box-info">
        <!-- .box-header -->
        <div class="box-header with-border">
            <h3 class="box-title">
               {{ Form::checkbox('return_payment', 1, null, ['id'=>'return_payment', 'class' => 'minimal' , 'onchange'=>"init_return_payment_div('return_payment')"]) }}
                Return Payment</h3>
        </div>
        <!-- /.box-header -->
        <!-- .box-body -->
        <div class="box-body">
            <div class="form-group{{ $errors->has('return_payment_date') ? ' has-error' : '' }}">
                {!! Form::label('return_payment_date', App\Language::trans('Date'), ['class'=>'control-label col-md-4']) !!}
                <div class="col-md-8">
                    {!! Form::text('return_payment_date', null, ['class'=>'form-control']) !!} {!!$errors->first('return_payment_date', '
                    <label for="return_payment_date" class="help-block error">:message</label>')!!}
                </div>
            </div>

            <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
                    {!! Form::label('reason', App\Language::trans('Reason'), ['class'=>'control-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('reason', null, ['rows'=>7,'class'=>'form-control']) !!} {!!$errors->first('reason', '
                        <label for="reason" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- /.col -->