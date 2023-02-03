<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Price Table Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table id="prices" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center col-md-1">#</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Started')}}</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Ended')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('GST')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('Unit Price')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($model->prices as $row)
						<tr>
							<td class="text-center">
								{{$i}}
							</td>
							<td class="text-center">
								{{$row['started']}}
							</td>
							<td class="text-center">
								{{$row['ended']}}
							</td>
							<td class="text-center">
								{{$row['is_gst'] ? App\Language::trans('Yes'):App\Language::trans('No')}}
							</td>
							<td class="text-center">
								{{$row['unit_price']}}
							</td>
						</tr>
						@php $i++; @endphp
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
