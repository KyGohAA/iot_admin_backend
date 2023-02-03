<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Price List Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<a class="btn btn-default margin-bottom-15" onclick="add_row('product_table')" href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> {{App\Language::trans('Add Row')}}</a>
				<div class="table-responsive">
					<table id="product_table" class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th class="col-md-4 text-center">
									{{App\Language::trans('Date From')}}
								</th>
								<th class="col-md-4 text-center">
									{{App\Language::trans('Date To')}}
								</th>
								<th class="col-md-2 text-center">
									{{App\Language::trans('Unit Price')}}
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($model->prices as $row)
								<tr>
									<td>{{$i}}</td>
									<td colspan="2">
										<div class="input-daterange">
											<div class="col-xs-6">
												{!!Form::text('prices['.$i.'][date_started]', $row->date_started, ['class'=>'form-control'])!!}
											</div>
											<div class="col-xs-6">
												{!!Form::text('prices['.$i.'][date_ended]', $row->date_ended, ['class'=>'form-control'])!!}
											</div>
										</div>
									</td>
									<td>
										{!!Form::text('prices['.$i.'][price]', $row->price, ['class'=>'form-control'])!!}
									</td>
									<td class="text-center">
										<a class="btn btn-default" onclick="remove_row(this)" href="javascript:void(0)"><i class="fa fa-trash fa-fw"></i></a>
									</td>
								</tr>
								@php $i++; @endphp
							@endforeach
							<tr>
								<td>{{$i}}</td>
								<td colspan="2">
									<div class="input-daterange">
										<div class="col-xs-6">
											{!!Form::text('prices['.$i.'][date_started]', null, ['class'=>'form-control'])!!}
										</div>
										<div class="col-xs-6">
											{!!Form::text('prices['.$i.'][date_ended]', null, ['class'=>'form-control'])!!}
										</div>
										</td>
									</div>
								<td>
									{!!Form::text('prices['.$i.'][price]', null, ['class'=>'form-control'])!!}
								</td>
								<td class="text-center">
									<a class="btn btn-default" onclick="remove_row(this)" href="javascript:void(0)"><i class="fa fa-trash fa-fw"></i></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
