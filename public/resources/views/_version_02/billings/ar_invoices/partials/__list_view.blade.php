<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Product List Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="product_table" class="table table-bordered table-hover" style="min-width: 1000px;">
						<thead>
							<tr>
								<th>#</th>
								<th class="col-md-2">
									{{App\Language::trans('Product Code')}}
								</th>
								<th class="col-md-5">
									{{App\Language::trans('Description')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Qty')}}<br>
									{{App\Language::trans('UOM')}}<br>
									{{App\Language::trans('Unit Price')}}
								</th>
								<th class="col-md-1 text-center">
									{{App\Language::trans('Tax')}}<br>
									{{App\Language::trans('Tax Amount')}}
								</th>
								<th class="col-md-2 text-center">
									{{App\Language::trans('Amount')}} 
									(<span class="currency_label">{{$model->currency_code}}</span>)
								</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($model->items as $row)
								<tr>
									<td><p class="form-control-static">{{$i}}</p></td>
									<td>
										<p class="form-control-static">{{$row->display_relationed('product', 'code')}}</p>
									</td>
									<td>
										<p class="form-control-static">{!!nl2br($row->product_description)!!}</p>
									</td>
									<td>
										<p class="form-control-static text-center">{{$row->quantity}}</p>
										<p class="form-control-static text-center">{{$row->uom}}</p>
										<p class="form-control-static text-center">{{$row->unit_price}}</p>
									</td>
									<td>
										<p class="form-control-static text-center">{{$row->display_relationed('tax', 'code')}}</p>
										<p class="form-control-static text-center">{{$row->tax_txt}}</p>
									</td>
									<td>
										<p class="form-control-static text-right">{{$row->getDouble($row->amount)}}</p>
									</td>
									<td></td>
								</tr>
								@php $i++; @endphp
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('Sub Total')}} :</td>
								<td class="text-right sub_total">{{$model->getDouble($model->amount)}}</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('GST @ 6.00%')}} :</td>
								<td class="text-right gst_total">{{$model->getDouble($model->gst_amount)}}</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" colspan="5">{{App\Language::trans('Grand Total')}} :</td>
								<td class="text-right grand_total">{{$model->getDouble($model->total_amount)}}</td>
								<td></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
