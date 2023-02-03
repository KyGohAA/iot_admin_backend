<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Price List Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
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
							</tr>
						</thead>
						<tbody>
							@foreach($model->prices as $row)
								<tr>
									<td><p class="form-control-static">{{$i}}</p></td>
									<td>
										<p class="form-control-static text-center">{{$row->date_started}}</p>
									</td>
									<td>
										<p class="form-control-static text-center">{{$row->date_ended}}</p>
									</td>
									<td>
										<p class="form-control-static text-center">{{$row->price}}</p>
									</td>
								</tr>
								@php $i++; @endphp
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
