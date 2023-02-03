<!-- Default box -->
<div class="box {{$save_by_url_status == true ? '':'hide'}}">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Save By Url')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('OCProductsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus fa-fw"></i>
			</button>
		</div>
	</div>
	<div class="box-body" {!!$save_by_url_status ? '':'style="display: none;"'!!}>
		{!!Form::model($model, ['class'=>'form-horizontal','method'=>'get'])!!}
			<div class="row">
				<div class="col-md-12">
					<div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
						{!! Form::label('url', App\Language::trans('Url'), ['class'=>'control-label col-md-2']) !!}
						<div class="col-md-10">
							{!! Form::text('url', null, ['id'=>'url', 'rows'=>'1000' , 'cols'=>'80','class'=>'form-control']) !!}
		                    {!!$errors->first('url', '<label for="url" class="help-block error">:message</label>')!!}
						</div>
					</div>
				</div>
			</div>	
	</div>	
	<!-- /.box-body -->
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<input id="SAVE" name="SAVE" type="submit" value="Save" class="btn btn-success">
				<input id="SAVE_EDIT" name="SAVE_EDIT" value="Save And Edit" type="submit" class="btn btn-success">
			</div>
		</div>

		

		<br>

		{!!Form::close()!!}
	</div>
<!-- 
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button value="Save All From EGO888" onclick="save_all_product_from_ego88();" class="btn btn-success">{{App\Language::trans('Save All Products From Ego888')}}</button>
			</div>
		</div>
	</div>
-->
	<!-- /.box-footer-->
</div>

