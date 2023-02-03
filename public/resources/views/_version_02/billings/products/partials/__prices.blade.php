
		<h5 class="hk-sec-title">{{App\Language::trans('Price List Form')}}</h5><hr>

		<div class="row">
			<div class="col-md-12">
				<a class="btn btn-success mb-15" onclick="add_row('product_table')" href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> {{App\Language::trans('Add Row')}}</a>
				<div class="table-wrap">
					<div class="table-responsive-md">
						<table id="product_table" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th width="50 px;">#</th>
									<th class="text-center">
										{{App\Language::trans('Date Range')}}
									</th>
									<th class="text-center">
										{{App\Language::trans('Unit Price')}}
									</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($model->prices as $row)
									<tr>
										<td>{{$i}}</td>
										<td>
							<!-- 				<div class="input-daterange">
												<div class="col-xs-6">
													{!!Form::text('prices['.$i.'][date_started]', $row->date_started, ['class'=>'form-control'])!!}
												</div>
												<div class="col-xs-6">
													{!!Form::text('prices['.$i.'][date_ended]', $row->date_ended, ['class'=>'form-control'])!!}
												</div>
											</div> -->
											<div class="form-group{{ $errors->has('daterange') ? ' has-error' : '' }} row">
									            <div class="col-xs-12">
									                  <input class="form-control" type="text" id="date_range" name="prices['.$i.']['date_range']"	/>
									            </div>
									        </div>

										</td>
										<td>
											{!!Form::number('prices['.$i.'][price]', $row->price, ['min'=>0,'step'=>'0.01', 'class'=>'form-control'])!!}
										</td>
										<td class="text-center">
											<a class="btn btn-default" onclick="remove_row(this)" href="javascript:void(0)"><i class="fa fa-trash fa-fw"></i></a>
										</td>
									</tr>
									@php $i++; @endphp
								@endforeach
								<tr>
									<td>{{$i}}</td>
									<td>
										<div class="form-group{{ $errors->has('daterange') ? ' has-error' : '' }} row">
									            <!-- <div class="col-xs-12"> -->
									                  <input class="form-control" type="text" id="date_range"  name="prices['.$i.']['date_range']"	/>
									            <!-- </div> -->
									    </div>
									<td>
										{!!Form::number('number['.$i.'][price]', null, ['min'=>0,'step'=>'0.01', 'class'=>'form-control'])!!}
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
