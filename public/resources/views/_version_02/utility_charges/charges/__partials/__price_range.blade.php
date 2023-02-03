<hr>
<section id="prices_section" class="hk-sec-wrapper">
    <h5 class="hk-sec-title">{{App\Language::trans('Charges Setting')}}</h5><hr>
   		<div class="row">
			<div class="table-responsive">
			<table id="prices" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Started')}}</th>
						<th class="text-center col-md-4">{{App\Language::trans('Unit Ended')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('GST')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('Unit Price (RM)')}}</th>
						<th class="text-center col-md-1">{{App\Language::trans('Action')}}</th>
					</tr>
				</thead>
				<tbody>
					@php $rows = old('prices') ? old('prices'):$model->prices; @endphp
					@foreach($rows as $row)
						<tr>
							<td class="text-center">
								{{$i}}
							</td>
							<td class="text-center">
								{{Form::text('prices['.$i.'][started]', $row['started'], ['class'=>'full-width'])}}
							</td>
							<td class="text-center">
								{{Form::text('prices['.$i.'][ended]', $row['ended'], ['class'=>'full-width'])}}
							</td>
							<td class="text-center">
								{{Form::checkbox('prices['.$i.'][is_gst]', 1, (isset($row['is_gst']) ? $row['is_gst']:false), ['class'=>''])}}
							</td>
							<td class="text-center">
								{{Form::text('prices['.$i.'][unit_price]', $row['unit_price'], ['class'=>'full-width'])}}
							</td>
							<td class="text-center">
								<a onclick="remove_row(this)" href="javascript:void(0)">
									<i class="fa fa-trash-o fa-fw icon-size"></i>
								</a>
							</td>
						</tr>
						@php $i++; @endphp
					@endforeach
					<tr>
						<td class="text-center">
							{{$i}}
						</td>
						<td class="text-center">
							{{Form::text('prices['.$i.'][started]', null, ['class'=>'full-width'])}}
						</td>
						<td class="text-center">
							{{Form::text('prices['.$i.'][ended]', null, ['class'=>'full-width'])}}
						</td>
						<td class="text-center">
							{{Form::checkbox('prices['.$i.'][is_gst]', 1, (count($rows) ? false:true), ['class'=>''])}}
						</td>
						<td class="text-center">
							{{Form::text('prices['.$i.'][unit_price]', null, ['class'=>'full-width'])}}
						</td>
						<td class="text-center">
							<a onclick="remove_row(this)" href="javascript:void(0)">
								<i class="fa fa-trash-o fa-fw icon-size"></i>
							</a>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" class="text-left">
							<a class="btn btn-default" onclick="add_row('prices')" href="javascript:void(0)"><i class="fa fa-plus-square fa-fw"></i> {{App\Language::trans('Add Row')}}</a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		</div>
</section>

