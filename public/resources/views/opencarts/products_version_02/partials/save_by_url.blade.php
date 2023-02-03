<section class="hk-sec-wrapper {{$save_by_url_status == true ? '':'hide'}}">
    <h5 class="hk-sec-title mb-40">{{App\Language::trans('Save By Url')}}</h5>
    <div class="row" {!!$save_by_url_status ? '':'style="display: none;"'!!}>
        <div class="col-sm">
            {!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
                <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <input name="SAVE" value="Save" class="btn btn-success" type="submit">
                            <input name="SAVE_EDIT" value="Save And Edit" class="btn btn-secondary" type="submit">
                        </div>
                        {!! Form::text('url', null, ['id'=>'url', 'rows'=>'1000' , 'cols'=>'80','class'=>'form-control','placeholder'=>App\Language::trans('Product Page URL') , 'aria-describedby'=>'basic-addon1' ,'aria-label'=>'' ]) !!}
		                {!!$errors->first('url', '<label for="url" class="help-block error">:message</label>')!!}
                    </div>
                </div>
            {!!Form::close()!!}
        </div>
    </div>
</section>

